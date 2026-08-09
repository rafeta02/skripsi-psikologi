<?php

namespace App\Services;

use App\Models\Application;
use App\Models\ApplicationResultDefense;
use App\Models\ApplicationResultReview;
use App\Models\ApplicationResultSeminar;
use App\Models\ApplicationSchedule;
use App\Models\SkripsiDefense;
use Illuminate\Support\Facades\Route;

class MahasiswaPortalService
{
    public function getNavigation(array $allowedForms): array
    {
        $groups = [
            [
                'title' => 'Beranda',
                'items' => [
                    $this->navItem('Dashboard', 'mahasiswa.dashboard', 'fa-home', ['mahasiswa.dashboard']),
                    $this->navItem('Aplikasi Saya', 'mahasiswa.aplikasi', 'fa-layer-group', ['mahasiswa.aplikasi']),
                ],
            ],
            [
                'title' => 'Proses',
                'items' => [
                    $this->navItem('Bimbingan', 'mahasiswa.bimbingan', 'fa-user-tie', ['mahasiswa.bimbingan']),
                    $this->navItem('Jadwal', 'mahasiswa.jadwal', 'fa-calendar-alt', ['mahasiswa.jadwal']),
                    $this->navItem('Dokumen', 'mahasiswa.dokumen', 'fa-folder-open', ['mahasiswa.dokumen']),
                ],
            ],
            [
                'title' => 'Form Pengajuan',
                'items' => $this->formNavItems($allowedForms),
            ],
            [
                'title' => 'Akun',
                'items' => [
                    $this->navItem('Profil', 'mahasiswa.profile', 'fa-user-circle', ['mahasiswa.profile']),
                ],
            ],
        ];

        return array_values(array_filter($groups, fn ($g) => !empty($g['items'])));
    }

    public function getQuickActions(array $allowedForms): array
    {
        $actions = [];

        if ($allowedForms['mbkm_registration']['allowed'] ?? false) {
            $actions[] = $this->action('Daftar MBKM', 'frontend.mbkm-registrations.index', 'fa-briefcase', 'primary');
        }
        if ($allowedForms['skripsi_registration']['allowed'] ?? false) {
            $actions[] = $this->action('Daftar Skripsi', 'frontend.skripsi-registrations.index', 'fa-book', 'primary');
        }
        if ($allowedForms['mbkm_seminar']['allowed'] ?? false) {
            $actions[] = $this->action('Review Kelayakan Proposal', 'frontend.mbkm-seminars.index', 'fa-chalkboard', 'info');
        }
        if ($allowedForms['skripsi_seminar']['allowed'] ?? false) {
            $actions[] = $this->action('Review Kelayakan Proposal', 'frontend.skripsi-seminars.index', 'fa-users', 'info');
        }
        if ($allowedForms['application_schedule']['allowed'] ?? false) {
            $actions[] = $this->action('Ajukan Jadwal', 'frontend.application-schedules.create', 'fa-calendar-plus', 'warning');
        }
        if ($allowedForms['application_result_seminar']['allowed'] ?? false) {
            $actions[] = $this->action('Laporan Seminar', 'frontend.application-result-seminars.index', 'fa-clipboard-check', 'success');
        }
        if ($allowedForms['application_result_review']['allowed'] ?? false) {
            $actions[] = $this->action('Laporan Review', 'frontend.application-result-reviews.index', 'fa-clipboard-list', 'success');
        }
        if ($allowedForms['skripsi_defense']['allowed'] ?? false) {
            $actions[] = $this->action('Daftar Sidang', 'frontend.skripsi-defenses.index', 'fa-graduation-cap', 'purple');
        }
        if ($allowedForms['defense_result']['allowed'] ?? false) {
            $actions[] = $this->action('Laporan Sidang', 'frontend.application-result-defenses.create', 'fa-award', 'success');
        }

        return $actions;
    }

    public function getProcessTimeline(int $mahasiswaId): array
    {
        $applications = Application::where('mahasiswa_id', $mahasiswaId)
            ->with([
                'skripsiRegistration',
                'mbkmRegistration',
                'skripsiSeminar',
                'mbkmSeminar',
                'skripsiDefense',
                'schedules',
                'resultDefense',
                'parentApplication.schedules',
                'parentApplication.mbkmSeminar',
            ])
            ->orderBy('created_at')
            ->get();

        $steps = [];

        foreach ($applications as $app) {
            $typeLabel = $app->type === 'mbkm' ? 'MBKM' : 'Skripsi';
            $stageLabel = match ($app->stage) {
                'registration' => 'Pendaftaran',
                'seminar' => 'Review Kelayakan Proposal',
                'defense' => 'Sidang',
                default => ucfirst($app->stage),
            };

            $mirrorNote = !empty($app->is_group_mirror) ? ' (kelompok)' : '';

            $steps[] = [
                'label' => "{$stageLabel} ({$typeLabel}){$mirrorNote}",
                'sublabel' => $this->statusLabel($app->status),
                'status' => $this->stepStatus($app->status),
                'badge' => $this->statusBadge($app->status),
                'date' => $app->submitted_at ?? $app->created_at?->format('d M Y'),
                'url' => $app->stageDetailUrl(),
                'icon' => $this->stageIcon($app->stage),
            ];

            $scheduleSource = (!empty($app->is_group_mirror) && $app->parentApplication)
                ? $app->parentApplication
                : $app;

            foreach ($scheduleSource->schedules->sortByDesc(fn ($schedule) => $schedule->getRawOriginal('waktu')) as $schedule) {
                $scheduleLabel = ApplicationSchedule::SCHEDULE_TYPE_SELECT[$schedule->schedule_type] ?? $schedule->schedule_type;
                $validation = $schedule->adminValidationStatus();
                $rawWaktu = $schedule->getRawOriginal('waktu');

                $steps[] = [
                    'label' => "Jadwal: {$scheduleLabel}",
                    'sublabel' => $validation['label'],
                    'status' => $validation['badge'] === 'success' ? 'done' : ($validation['badge'] === 'danger' ? 'failed' : 'active'),
                    'badge' => $validation['badge'],
                    'date' => $rawWaktu ? \Carbon\Carbon::parse($rawWaktu)->format('d M Y H:i') : ($schedule->waktu ?? '-'),
                    'url' => Route::has('frontend.application-schedules.show')
                        ? route('frontend.application-schedules.show', $schedule->id)
                        : route('mahasiswa.jadwal'),
                    'icon' => 'fa-calendar-check',
                ];
            }

            $resultAppId = (!empty($app->is_group_mirror) && $app->parent_application_id)
                ? $app->parent_application_id
                : $app->id;

            if ($app->stage === 'seminar' && $app->type === 'skripsi' && empty($app->is_group_mirror)) {
                $result = ApplicationResultReview::where('application_id', $app->id)->first();
                if ($result) {
                    $steps[] = $this->resultStep('Laporan Review Proposal', $result->result, $result->created_at, 'frontend.application-result-reviews.show', $result->id);
                }
            }

            if ($app->stage === 'seminar' && $app->type === 'mbkm') {
                $result = ApplicationResultSeminar::where('application_id', $resultAppId)->first();
                if ($result) {
                    $steps[] = $this->resultStep('Laporan Hasil Seminar', $result->result, $result->created_at, 'frontend.application-result-seminars.show', $result->id);
                }
            }

            if ($app->stage === 'defense') {
                $result = ApplicationResultDefense::where('application_id', $app->id)->first();
                if ($result) {
                    $steps[] = $this->resultStep('Laporan Hasil Sidang', $result->result, $result->created_at, 'frontend.application-result-defenses.show', $result->id);
                }
            }
        }

        return $steps;
    }

    private function formNavItems(array $allowedForms): array
    {
        $items = [];

        $forms = [
            ['label' => 'Pendaftaran Skripsi', 'route' => 'frontend.skripsi-registrations.index', 'icon' => 'fa-file-signature', 'routes' => ['frontend.skripsi-registrations.*'], 'always' => true],
            ['label' => 'Pendaftaran MBKM', 'route' => 'frontend.mbkm-registrations.index', 'icon' => 'fa-file-signature', 'routes' => ['frontend.mbkm-registrations.*'], 'always' => true],
            ['label' => 'Review Kelayakan Proposal', 'route' => 'frontend.skripsi-seminars.index', 'icon' => 'fa-presentation', 'routes' => ['frontend.skripsi-seminars.*'], 'always' => true],
            ['label' => 'Review Kelayakan Proposal', 'route' => 'frontend.mbkm-seminars.index', 'icon' => 'fa-presentation', 'routes' => ['frontend.mbkm-seminars.*'], 'always' => true],
            ['label' => 'Jadwal', 'route' => 'frontend.application-schedules.index', 'icon' => 'fa-calendar-plus', 'routes' => ['frontend.application-schedules.*'], 'always' => true],
            ['label' => 'Laporan Review', 'route' => 'frontend.application-result-reviews.index', 'icon' => 'fa-clipboard-list', 'routes' => ['frontend.application-result-reviews.*'], 'key' => 'application_result_review'],
            ['label' => 'Laporan Seminar', 'route' => 'frontend.application-result-seminars.index', 'icon' => 'fa-clipboard-check', 'routes' => ['frontend.application-result-seminars.*'], 'key' => 'application_result_seminar'],
            ['label' => 'Pendaftaran Sidang', 'route' => 'frontend.skripsi-defenses.index', 'icon' => 'fa-graduation-cap', 'routes' => ['frontend.skripsi-defenses.*'], 'key' => 'skripsi_defense'],
            ['label' => 'Hasil Sidang', 'route' => 'frontend.application-result-defenses.index', 'icon' => 'fa-award', 'routes' => ['frontend.application-result-defenses.*'], 'key' => 'defense_result'],
        ];

        foreach ($forms as $form) {
            if (!($form['always'] ?? false)) {
                $key = $form['key'] ?? null;
                if ($key && !($allowedForms[$key]['allowed'] ?? false)) {
                    $existing = $this->hasExistingRecord($key, auth()->user()?->mahasiswa_id);
                    if (!$existing) {
                        continue;
                    }
                }
            }

            if (!Route::has($form['route'])) {
                continue;
            }

            $items[] = $this->navItem($form['label'], $form['route'], $form['icon'], $form['routes']);
        }

        if (auth()->user()?->can('application_report_access')) {
            $items[] = $this->navItem('Laporan Kendala', 'frontend.application-reports.index', 'fa-flag', ['frontend.application-reports.*']);
        }

        return $items;
    }

    private function hasExistingRecord(string $key, ?int $mahasiswaId): bool
    {
        if (!$mahasiswaId) {
            return false;
        }

        $appIds = Application::where('mahasiswa_id', $mahasiswaId)->pluck('id');

        if ($key === 'application_result_seminar') {
            if (ApplicationResultSeminar::whereIn('application_id', $appIds)->exists()) {
                return true;
            }

            $ownerSeminar = app(MbkmGroupProgressService::class)
                ->resolveOwnerApplication((int) $mahasiswaId, 'seminar');

            return $ownerSeminar
                && ApplicationResultSeminar::where('application_id', $ownerSeminar->id)->exists();
        }

        return match ($key) {
            'application_result_review' => ApplicationResultReview::whereIn('application_id', $appIds)->exists(),
            'skripsi_defense' => SkripsiDefense::whereIn('application_id', $appIds)->exists(),
            'defense_result' => ApplicationResultDefense::whereIn('application_id', $appIds)->exists(),
            default => false,
        };
    }

    private function navItem(string $label, string $route, string $icon, array $activeRoutes): array
    {
        return [
            'label' => $label,
            'url' => route($route),
            'icon' => $icon,
            'active' => request()->routeIs(...$activeRoutes),
        ];
    }

    private function action(string $label, string $route, string $icon, string $color): array
    {
        return [
            'label' => $label,
            'url' => route($route),
            'icon' => $icon,
            'color' => $color,
        ];
    }

    private function resultStep(string $label, string $result, $date, string $route, int $id): array
    {
        $badge = match ($result) {
            'passed' => 'success',
            'revision' => 'warning',
            'failed' => 'danger',
            default => 'secondary',
        };

        return [
            'label' => $label,
            'sublabel' => match ($result) {
                'passed' => 'Lulus',
                'revision' => 'Revisi',
                'failed' => 'Tidak Lulus',
                default => ucfirst($result),
            },
            'status' => $result === 'failed' ? 'failed' : 'done',
            'badge' => $badge,
            'date' => $date?->format('d M Y') ?? '-',
            'url' => route($route, $id),
            'icon' => 'fa-file-alt',
        ];
    }

    private function stageIcon(string $stage): string
    {
        return match ($stage) {
            'registration' => 'fa-file-alt',
            'seminar' => 'fa-chalkboard-teacher',
            'defense' => 'fa-graduation-cap',
            default => 'fa-circle',
        };
    }

    private function stepStatus(string $status): string
    {
        return match ($status) {
            'done' => 'done',
            'rejected' => 'failed',
            'submitted', 'approved', 'scheduled', 'revision', 'result' => 'active',
            default => 'pending',
        };
    }

    private function statusBadge(string $status): string
    {
        return match ($status) {
            'approved', 'done' => 'success',
            'scheduled' => 'info',
            'revision', 'submitted' => 'warning',
            'rejected' => 'danger',
            default => 'secondary',
        };
    }

    private function statusLabel(string $status): string
    {
        return match ($status) {
            'submitted' => 'Menunggu verifikasi',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            'revision' => 'Perlu revisi',
            'scheduled' => 'Terjadwal',
            'result' => 'Hasil dilaporkan',
            'done' => 'Selesai',
            default => ucfirst($status),
        };
    }
}
