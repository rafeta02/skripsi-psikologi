<?php

namespace App\Services;

use App\Models\ApplicationAssignment;
use App\Models\ApplicationScore;
use App\Models\Dosen;

class DosenPortalService
{
    public function resolveDosenId(): ?int
    {
        $user = auth()->user();
        if (!$user) {
            return null;
        }

        if ($user->dosen_id) {
            return (int) $user->dosen_id;
        }

        $dosen = Dosen::where('nip', $user->email)
            ->orWhere('nidn', $user->email)
            ->first();

        return $dosen?->id;
    }

    public function getNavigation(): array
    {
        $dosenId = $this->resolveDosenId();
        $pendingAssignments = $dosenId ? $this->countPendingAssignments($dosenId) : 0;
        $pendingScores = $dosenId ? $this->countPendingDefenseScores($dosenId) : 0;

        return [
            [
                'title' => 'Beranda',
                'items' => [
                    $this->navItem('Dashboard', 'dosen.dashboard', 'fa-home', ['dosen.dashboard']),
                    $this->navItem('Pengumuman', 'dosen.pengumuman', 'fa-bullhorn', ['dosen.pengumuman', 'dosen.pengumuman.show']),
                ],
            ],
            [
                'title' => 'Bimbingan & Tugas',
                'items' => [
                    $this->navItem('Mahasiswa Bimbingan', 'dosen.mahasiswa-bimbingan', 'fa-user-graduate', ['dosen.mahasiswa-bimbingan']),
                    $this->navItem('Penugasan', 'dosen.task-assignments', 'fa-tasks', ['dosen.task-assignments', 'dosen.review-proposal'], $pendingAssignments),
                ],
            ],
            [
                'title' => 'Penilaian',
                'items' => [
                    $this->navItem('Penilaian Sidang', 'dosen.scores', 'fa-star', ['dosen.scores', 'dosen.application-scores.*'], $pendingScores),
                ],
            ],
            [
                'title' => 'Akun',
                'items' => [
                    $this->navItem('Profil', 'dosen.profile', 'fa-user-circle', ['dosen.profile']),
                ],
            ],
        ];
    }

    public function getQuickActions(int $dosenId): array
    {
        $actions = [];

        $pendingAssignments = ApplicationAssignment::withoutGroupMirrors()
            ->where('lecturer_id', $dosenId)
            ->where('status', 'assigned')
            ->count();

        if ($pendingAssignments > 0) {
            $actions[] = [
                'label' => "Tinjau Penugasan ({$pendingAssignments})",
                'url' => route('dosen.task-assignments'),
                'icon' => 'fa-clipboard-list',
                'color' => 'warning',
            ];
        }

        $pendingScores = $this->countPendingDefenseScores($dosenId);
        if ($pendingScores > 0) {
            $actions[] = [
                'label' => "Nilai Sidang ({$pendingScores})",
                'url' => route('dosen.scores'),
                'icon' => 'fa-star',
                'color' => 'success',
            ];
        }

        $actions[] = [
            'label' => 'Mahasiswa Bimbingan',
            'url' => route('dosen.mahasiswa-bimbingan'),
            'icon' => 'fa-users',
            'color' => 'primary',
        ];

        $actions[] = [
            'label' => 'Semua Penugasan',
            'url' => route('dosen.task-assignments'),
            'icon' => 'fa-tasks',
            'color' => 'info',
        ];

        return $actions;
    }

    public function getActivityTimeline(int $dosenId, int $limit = 12): array
    {
        $assignments = ApplicationAssignment::withoutGroupMirrors()
            ->with([
                'application.mahasiswa',
                'application.skripsiRegistration',
                'application.mbkmRegistration.groupMembers.mahasiswa',
            ])
            ->where('lecturer_id', $dosenId)
            ->orderByDesc('assigned_at')
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();

        return $assignments->map(function (ApplicationAssignment $assignment) {
            $roleLabel = match ($assignment->role) {
                'supervisor' => 'Pembimbing',
                'examiner_1', 'examiner' => 'Penguji 1',
                'examiner_2' => 'Penguji 2',
                'reviewer' => 'Reviewer',
                default => ucfirst($assignment->role ?? '-'),
            };

            $status = match ($assignment->status) {
                'accepted' => ['done', 'success', 'Diterima'],
                'rejected' => ['failed', 'danger', 'Ditolak'],
                default => ['active', 'warning', 'Menunggu respons'],
            };

            $title = $assignment->application?->skripsiRegistration?->title
                ?? $assignment->application?->mbkmRegistration?->title_mbkm
                ?? $assignment->application?->mbkmRegistration?->title
                ?? '-';

            $members = $assignment->application?->mbkmRegistration?->groupMembers;
            $groupLabel = ($assignment->application?->type === 'mbkm' && $members && $members->count() > 1)
                ? 'Kelompok MBKM (' . $members->count() . ') — '
                : '';

            $url = $assignment->status === 'assigned'
                ? route('dosen.review-proposal', $assignment->id)
                : route('dosen.task-assignments');

            return [
                'label' => $groupLabel . ($assignment->application?->mahasiswa?->nama ?? 'Mahasiswa') . ' — ' . $roleLabel,
                'sublabel' => $status[2],
                'detail' => \Illuminate\Support\Str::limit($title, 60),
                'status' => $status[0],
                'badge' => $status[1],
                'date' => $assignment->assigned_at
                    ? \Carbon\Carbon::parse($assignment->assigned_at)->format('d M Y')
                    : ($assignment->created_at?->format('d M Y') ?? '-'),
                'url' => $url,
                'icon' => 'fa-user-tie',
            ];
        })->all();
    }

    public function getSummaryStats(int $dosenId): array
    {
        return [
            'mahasiswa_bimbingan' => ApplicationAssignment::withoutGroupMirrors()
                ->where('lecturer_id', $dosenId)
                ->where('role', 'supervisor')
                ->where('status', 'accepted')
                ->distinct('application_id')
                ->count('application_id'),
            'total_penugasan' => ApplicationAssignment::withoutGroupMirrors()
                ->where('lecturer_id', $dosenId)
                ->count(),
            'menunggu_respons' => $this->countPendingAssignments($dosenId),
            'penilaian_pending' => $this->countPendingDefenseScores($dosenId),
        ];
    }

    private function countPendingAssignments(int $dosenId): int
    {
        return ApplicationAssignment::withoutGroupMirrors()
            ->where('lecturer_id', $dosenId)
            ->where('status', 'assigned')
            ->count();
    }

    private function countPendingDefenseScores(int $dosenId): int
    {
        return ApplicationScore::where('examiner_id', $dosenId)
            ->whereNull('score')
            ->where(function ($query) {
                $query->where(function ($preReport) {
                    $preReport->whereNotNull('application_id')
                        ->whereDoesntHave('application_result_defence');
                })->orWhereHas('application_result_defence', function ($resultQuery) {
                    $resultQuery->whereIn('result', ['passed', 'revision'])
                        ->whereHas('application.actions', function ($actionQuery) {
                            $actionQuery->where('action_type', 'result_defense_approved');
                        });
                });
            })
            ->count();
    }

    private function navItem(string $label, string $route, string $icon, array $activeRoutes, int $badge = 0): array
    {
        return [
            'label' => $label,
            'url' => route($route),
            'icon' => $icon,
            'active' => request()->routeIs(...$activeRoutes),
            'badge' => $badge,
        ];
    }
}
