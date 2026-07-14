<?php

namespace App\Services;

use App\Models\Application;
use App\Models\ApplicationAssignment;
use App\Models\Dosen;
use App\Models\MbkmGroupMember;
use App\Models\MbkmRegistration;
use App\Models\MbkmSeminar;
use App\Models\SkripsiDefenseExaminer;
use App\Models\SkripsiSeminar;
use Illuminate\Support\Collection;

class DosenWorkloadService
{
    public function getPembimbingRecap(): Collection
    {
        $assignments = ApplicationAssignment::withoutGroupMirrors()
            ->with([
                'application.mbkmRegistration.groupMembers',
                'application.mirrorApplications',
            ])
            ->where('role', 'supervisor')
            ->get();

        $workload = [];

        foreach ($assignments as $assignment) {
            $dosenId = (int) $assignment->lecturer_id;
            if (!$dosenId) {
                continue;
            }

            $weight = $this->mbkmStudentWeight($assignment->application);
            $this->initPembimbingRow($workload, $dosenId);

            $workload[$dosenId]['total'] += $weight;

            if ($assignment->status === 'assigned') {
                $workload[$dosenId]['menunggu'] += $weight;
            } elseif ($assignment->status === 'accepted') {
                $workload[$dosenId]['diterima'] += $weight;

                $appStatus = $assignment->application?->status;
                if ($appStatus && !in_array($appStatus, ['done', 'rejected'], true)) {
                    $workload[$dosenId]['aktif'] += $weight;
                } else {
                    $workload[$dosenId]['selesai'] += $weight;
                }

                if ($assignment->application?->type === 'skripsi') {
                    $workload[$dosenId]['reguler'] += $weight;
                } elseif ($assignment->application?->type === 'mbkm') {
                    $workload[$dosenId]['mbkm'] += $weight;
                }
            } elseif ($assignment->status === 'rejected') {
                $workload[$dosenId]['ditolak'] += $weight;
            }
        }

        if (empty($workload)) {
            return collect();
        }

        $dosens = Dosen::with('prodi')
            ->whereIn('id', array_keys($workload))
            ->orderBy('nama')
            ->get()
            ->keyBy('id');

        return collect($workload)
            ->map(function (array $row, int $dosenId) use ($dosens) {
                $dosen = $dosens->get($dosenId);

                return array_merge($row, [
                    'dosen_id' => $dosenId,
                    'nama' => $dosen->nama ?? '-',
                    'nidn' => $dosen->nidn ?? '-',
                    'nip' => $dosen->nip ?? '-',
                    'prodi' => $dosen->prodi->name ?? '-',
                ]);
            })
            ->sortByDesc('aktif')
            ->values();
    }

    public function getPembimbingDetail(int $dosenId): Collection
    {
        $items = collect();

        ApplicationAssignment::withoutGroupMirrors()
            ->with([
                'application.mahasiswa.prodi',
                'application.skripsiRegistration',
                'application.mbkmRegistration.groupMembers.mahasiswa.prodi',
                'application.mirrorApplications.mahasiswa.prodi',
            ])
            ->where('lecturer_id', $dosenId)
            ->where('role', 'supervisor')
            ->orderByDesc('assigned_at')
            ->get()
            ->each(function (ApplicationAssignment $assignment) use ($items) {
                $app = $assignment->application;
                $title = $app?->skripsiRegistration?->title
                    ?? $app?->mbkmRegistration?->title_mbkm
                    ?? $app?->mbkmRegistration?->title
                    ?? '-';

                $assignedAt = $assignment->getRawOriginal('assigned_at')
                    ? \Carbon\Carbon::parse($assignment->getRawOriginal('assigned_at'))->format('d M Y')
                    : '-';

                $students = $this->resolveMbkmStudents($app);

                if ($students->isEmpty()) {
                    $items->push([
                        'mahasiswa' => $app?->mahasiswa?->nama ?? '-',
                        'nim' => $app?->mahasiswa?->nim ?? '-',
                        'prodi' => $app?->mahasiswa?->prodi?->name ?? '-',
                        'type' => strtoupper($app?->type ?? '-'),
                        'stage' => ucfirst($app?->stage ?? '-'),
                        'status_penugasan' => $assignment->status,
                        'status_aplikasi' => $app?->status ?? '-',
                        'judul' => $title,
                        'assigned_at' => $assignedAt,
                        'peran_kelompok' => null,
                    ]);

                    return;
                }

                foreach ($students as $student) {
                    $items->push([
                        'mahasiswa' => $student['nama'],
                        'nim' => $student['nim'],
                        'prodi' => $student['prodi'],
                        'type' => strtoupper($app?->type ?? '-'),
                        'stage' => ucfirst($app?->stage ?? '-'),
                        'status_penugasan' => $assignment->status,
                        'status_aplikasi' => $app?->status ?? '-',
                        'judul' => $title,
                        'assigned_at' => $assignedAt,
                        'peran_kelompok' => $student['role'],
                    ]);
                }
            });

        return $items->values();
    }

    public function getPengujiRecap(): Collection
    {
        $workload = [];

        $this->accumulateSeminarReviewers(
            SkripsiSeminar::query()->with('application')->whereNotNull('reviewer_1_id')->get(),
            'reviewer_1_id',
            'seminar_reguler',
            $workload
        );
        $this->accumulateSeminarReviewers(
            SkripsiSeminar::query()->with('application')->whereNotNull('reviewer_2_id')->get(),
            'reviewer_2_id',
            'seminar_reguler',
            $workload
        );
        $this->accumulateSeminarReviewers(
            MbkmSeminar::query()
                ->with([
                    'application.mbkmRegistration.groupMembers',
                    'application.mirrorApplications',
                    'application.parentApplication.mbkmRegistration.groupMembers',
                ])
                ->whereNotNull('reviewer_1_id')
                ->get(),
            'reviewer_1_id',
            'seminar_mbkm',
            $workload,
            true
        );
        $this->accumulateSeminarReviewers(
            MbkmSeminar::query()
                ->with([
                    'application.mbkmRegistration.groupMembers',
                    'application.mirrorApplications',
                    'application.parentApplication.mbkmRegistration.groupMembers',
                ])
                ->whereNotNull('reviewer_2_id')
                ->get(),
            'reviewer_2_id',
            'seminar_mbkm',
            $workload,
            true
        );

        SkripsiDefenseExaminer::with(['skripsiDefense.application'])
            ->get()
            ->each(function (SkripsiDefenseExaminer $examiner) use (&$workload) {
                $dosenId = (int) $examiner->dosen_id;
                if (!$dosenId) {
                    return;
                }

                $this->initPengujiRow($workload, $dosenId);
                $workload[$dosenId]['sidang']++;
                $workload[$dosenId]['total']++;

                $appStatus = $examiner->skripsiDefense?->application?->status;
                if ($appStatus && !in_array($appStatus, ['done', 'rejected'], true)) {
                    $workload[$dosenId]['aktif']++;
                } else {
                    $workload[$dosenId]['selesai']++;
                }
            });

        if (empty($workload)) {
            return collect();
        }

        $dosens = Dosen::with('prodi')
            ->whereIn('id', array_keys($workload))
            ->orderBy('nama')
            ->get()
            ->keyBy('id');

        return collect($workload)
            ->map(function (array $row, int $dosenId) use ($dosens) {
                $dosen = $dosens->get($dosenId);

                return array_merge($row, [
                    'dosen_id' => $dosenId,
                    'nama' => $dosen->nama ?? '-',
                    'nidn' => $dosen->nidn ?? '-',
                    'nip' => $dosen->nip ?? '-',
                    'prodi' => $dosen->prodi->name ?? '-',
                ]);
            })
            ->sortByDesc('total')
            ->values();
    }

    public function getPengujiDetail(int $dosenId): Collection
    {
        $items = collect();

        SkripsiSeminar::with(['application.mahasiswa.prodi'])
            ->where(function ($q) use ($dosenId) {
                $q->where('reviewer_1_id', $dosenId)->orWhere('reviewer_2_id', $dosenId);
            })
            ->get()
            ->each(function (SkripsiSeminar $seminar) use ($dosenId, $items) {
                $role = (int) $seminar->reviewer_1_id === $dosenId ? 'Reviewer 1' : 'Reviewer 2';
                $items->push($this->formatPengujiItem(
                    'Seminar Reguler',
                    $role,
                    $seminar->application,
                    $seminar->title
                ));
            });

        MbkmSeminar::with([
            'application.mahasiswa.prodi',
            'application.mbkmRegistration.groupMembers.mahasiswa.prodi',
            'application.mirrorApplications.mahasiswa.prodi',
            'application.parentApplication.mbkmRegistration.groupMembers.mahasiswa.prodi',
        ])
            ->where(function ($q) use ($dosenId) {
                $q->where('reviewer_1_id', $dosenId)->orWhere('reviewer_2_id', $dosenId);
            })
            ->get()
            ->each(function (MbkmSeminar $seminar) use ($dosenId, $items) {
                $role = (int) $seminar->reviewer_1_id === $dosenId ? 'Reviewer 1' : 'Reviewer 2';
                $students = $this->resolveMbkmStudents($seminar->application);

                if ($students->isEmpty()) {
                    $items->push($this->formatPengujiItem(
                        'Review Kelayakan Proposal',
                        $role,
                        $seminar->application,
                        $seminar->title ?? null
                    ));

                    return;
                }

                foreach ($students as $student) {
                    $items->push([
                        'jenis' => 'Review Kelayakan Proposal',
                        'peran' => $role . ($student['role'] ? ' (' . $student['role'] . ')' : ''),
                        'mahasiswa' => $student['nama'],
                        'nim' => $student['nim'],
                        'prodi' => $student['prodi'],
                        'type' => 'MBKM',
                        'status_aplikasi' => $seminar->application?->status ?? '-',
                        'judul' => $seminar->title ?? '-',
                    ]);
                }
            });

        SkripsiDefenseExaminer::with(['skripsiDefense.application.mahasiswa.prodi'])
            ->where('dosen_id', $dosenId)
            ->get()
            ->each(function (SkripsiDefenseExaminer $examiner) use ($items) {
                $defense = $examiner->skripsiDefense;
                $roleLabel = match ($examiner->role) {
                    'examiner_1' => 'Penguji 1',
                    'examiner_2' => 'Penguji 2',
                    default => ucfirst(str_replace('_', ' ', $examiner->role ?? 'Penguji')),
                };

                $items->push($this->formatPengujiItem(
                    'Sidang Skripsi',
                    $roleLabel,
                    $defense?->application,
                    $defense?->title
                ));
            });

        return $items->sortBy('mahasiswa')->values();
    }

    private function accumulateSeminarReviewers(
        $seminars,
        string $reviewerField,
        string $bucket,
        array &$workload,
        bool $weightByMbkmGroup = false
    ): void {
        foreach ($seminars as $seminar) {
            $dosenId = (int) $seminar->{$reviewerField};
            if (!$dosenId) {
                continue;
            }

            $weight = $weightByMbkmGroup
                ? $this->mbkmStudentWeight($seminar->application)
                : 1;

            $this->initPengujiRow($workload, $dosenId);
            $workload[$dosenId][$bucket] += $weight;
            $workload[$dosenId]['total'] += $weight;

            $appStatus = $seminar->application?->status;
            if ($appStatus && !in_array($appStatus, ['done', 'rejected'], true)) {
                $workload[$dosenId]['aktif'] += $weight;
            } else {
                $workload[$dosenId]['selesai'] += $weight;
            }
        }
    }

    private function initPembimbingRow(array &$workload, int $dosenId): void
    {
        if (!isset($workload[$dosenId])) {
            $workload[$dosenId] = [
                'total' => 0,
                'menunggu' => 0,
                'diterima' => 0,
                'ditolak' => 0,
                'aktif' => 0,
                'selesai' => 0,
                'reguler' => 0,
                'mbkm' => 0,
            ];
        }
    }

    private function initPengujiRow(array &$workload, int $dosenId): void
    {
        if (!isset($workload[$dosenId])) {
            $workload[$dosenId] = [
                'seminar_reguler' => 0,
                'seminar_mbkm' => 0,
                'sidang' => 0,
                'total' => 0,
                'aktif' => 0,
                'selesai' => 0,
            ];
        }
    }

    private function formatPengujiItem(string $jenis, string $peran, $application, ?string $judul): array
    {
        return [
            'jenis' => $jenis,
            'peran' => $peran,
            'mahasiswa' => $application?->mahasiswa?->nama ?? '-',
            'nim' => $application?->mahasiswa?->nim ?? '-',
            'prodi' => $application?->mahasiswa?->prodi?->name ?? '-',
            'type' => strtoupper($application?->type ?? '-'),
            'status_aplikasi' => $application?->status ?? '-',
            'judul' => $judul ?? '-',
        ];
    }

    /**
     * Beban MBKM kelompok dihitung per mahasiswa (ketua + anggota),
     * bukan per form/penugasan kelompok. Sidang sudah individu → bobot 1.
     */
    private function mbkmStudentWeight(?Application $application): int
    {
        if (!$application || $application->type !== 'mbkm') {
            return 1;
        }

        if ($application->stage === 'defense') {
            return 1;
        }

        $members = $this->resolveMbkmGroupMembers($application);
        if ($members->isNotEmpty()) {
            return $members->count();
        }

        $owner = $this->resolveMbkmOwnerApplication($application);
        if (!$owner) {
            return 1;
        }

        $mirrorCount = $owner->relationLoaded('mirrorApplications')
            ? $owner->mirrorApplications->count()
            : Application::where('parent_application_id', $owner->id)
                ->where('is_group_mirror', true)
                ->count();

        return max(1, 1 + (int) $mirrorCount);
    }

    /**
     * @return Collection<int, array{nama: string, nim: string, prodi: string, role: string|null}>
     */
    private function resolveMbkmStudents(?Application $application): Collection
    {
        if (!$application || $application->type !== 'mbkm' || $application->stage === 'defense') {
            return collect();
        }

        $members = $this->resolveMbkmGroupMembers($application);
        if ($members->isNotEmpty()) {
            return $members->map(function (MbkmGroupMember $member) {
                $roleLabel = MbkmGroupMember::ROLE_SELECT[$member->role] ?? $member->role;

                return [
                    'nama' => $member->mahasiswa?->nama ?? '-',
                    'nim' => $member->mahasiswa?->nim ?? '-',
                    'prodi' => $member->mahasiswa?->prodi?->name ?? '-',
                    'role' => $roleLabel,
                ];
            })->values();
        }

        $owner = $this->resolveMbkmOwnerApplication($application);
        if (!$owner) {
            return collect();
        }

        $students = collect([[
            'nama' => $owner->mahasiswa?->nama ?? '-',
            'nim' => $owner->mahasiswa?->nim ?? '-',
            'prodi' => $owner->mahasiswa?->prodi?->name ?? '-',
            'role' => 'Ketua',
        ]]);

        $mirrors = $owner->relationLoaded('mirrorApplications')
            ? $owner->mirrorApplications
            : Application::with('mahasiswa.prodi')
                ->where('parent_application_id', $owner->id)
                ->where('is_group_mirror', true)
                ->get();

        foreach ($mirrors as $mirror) {
            $students->push([
                'nama' => $mirror->mahasiswa?->nama ?? '-',
                'nim' => $mirror->mahasiswa?->nim ?? '-',
                'prodi' => $mirror->mahasiswa?->prodi?->name ?? '-',
                'role' => 'Anggota',
            ]);
        }

        return $students->values();
    }

    private function resolveMbkmGroupMembers(?Application $application): Collection
    {
        if (!$application || $application->type !== 'mbkm') {
            return collect();
        }

        $registration = $application->resolveOwnerMbkmRegistration();

        if (!$registration) {
            $owner = $this->resolveMbkmOwnerApplication($application);
            if ($owner) {
                $registration = MbkmRegistration::whereHas('application', function ($q) use ($owner) {
                    $q->where('mahasiswa_id', $owner->mahasiswa_id)
                        ->where('type', 'mbkm')
                        ->where('stage', 'registration')
                        ->where(function ($inner) {
                            $inner->where('is_group_mirror', false)
                                ->orWhereNull('is_group_mirror');
                        });
                })
                    ->with(['groupMembers.mahasiswa.prodi'])
                    ->latest('id')
                    ->first();
            }
        } else {
            $registration->loadMissing(['groupMembers.mahasiswa.prodi']);
        }

        return $registration?->groupMembers ?? collect();
    }

    private function resolveMbkmOwnerApplication(Application $application): ?Application
    {
        if ($application->is_group_mirror && $application->parent_application_id) {
            return $application->relationLoaded('parentApplication')
                ? $application->parentApplication
                : Application::with(['mahasiswa.prodi', 'mirrorApplications.mahasiswa.prodi'])
                    ->find($application->parent_application_id);
        }

        return $application;
    }
}
