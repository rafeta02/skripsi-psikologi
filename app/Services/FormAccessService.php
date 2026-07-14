<?php

namespace App\Services;

use App\Models\Application;
use App\Models\MbkmRegistration;
use App\Models\SkripsiRegistration;
use App\Models\MbkmSeminar;
use App\Models\SkripsiSeminar;
use App\Models\ApplicationAction;
use App\Models\ApplicationResultSeminar;
use App\Models\ApplicationResultReview;
use App\Models\ApplicationSchedule;
use App\Models\SkripsiDefense;
use App\Models\ApplicationResultDefense;
use App\Models\Mahasiswa;
use App\Services\MbkmGroupProgressService;

class FormAccessService
{
    private function groupProgress(): MbkmGroupProgressService
    {
        return app(MbkmGroupProgressService::class);
    }

    /**
     * Check if student can access MBKM Registration
     */
    public function canAccessMbkmRegistration($mahasiswaId)
    {
        if ($this->groupProgress()->isFollowerAnggota($mahasiswaId)) {
            return [
                'allowed' => false,
                'message' => 'Anda sudah tergabung sebagai anggota kelompok MBKM. Progres mengikuti form ketua kelompok.',
            ];
        }

        // Check if student has any existing applications (exclude pure mirrors for "empty" check)
        $applications = Application::where('mahasiswa_id', $mahasiswaId)
            ->where('is_group_mirror', false)
            ->orderBy('created_at', 'desc')
            ->get();

        // New student - can register
        if ($applications->isEmpty()) {
            // Mirror-only still blocks new registration
            if (Application::where('mahasiswa_id', $mahasiswaId)->where('is_group_mirror', true)->exists()) {
                return [
                    'allowed' => false,
                    'message' => 'Anda sudah tergabung sebagai anggota kelompok MBKM.',
                ];
            }

            return [
                'allowed' => true,
                'message' => null
            ];
        }

        // Check if there's an existing MBKM registration that was rejected as "ineligible"
        $rejectedMbkm = $applications->where('type', 'mbkm')
            ->where('status', 'rejected')
            ->first();

        if ($rejectedMbkm) {
            // If rejected from MBKM, cannot access MBKM registration anymore
            return [
                'allowed' => false,
                'message' => 'Anda tidak eligible untuk jalur MBKM. Silakan daftar melalui jalur Skripsi Reguler.'
            ];
        }

        // Check if student has active or approved MBKM registration
        $activeMbkm = $applications->where('type', 'mbkm')
            ->whereIn('status', ['submitted', 'approved', 'scheduled'])
            ->first();

        if ($activeMbkm) {
            return [
                'allowed' => false,
                'message' => 'Anda sudah memiliki pendaftaran MBKM yang aktif. Tunggu proses persetujuan.'
            ];
        }

        // Check if student has active Skripsi registration
        $activeSkripsi = $applications->where('type', 'skripsi')
            ->whereIn('status', ['submitted', 'approved', 'scheduled'])
            ->first();

        if ($activeSkripsi) {
            return [
                'allowed' => false,
                'message' => 'Anda sudah memilih jalur Skripsi Reguler dan tidak dapat beralih ke jalur MBKM.'
            ];
        }

        return [
            'allowed' => true,
            'message' => null
        ];
    }

    /**
     * Check if student can access Skripsi Registration
     */
    public function canAccessSkripsiRegistration($mahasiswaId)
    {
        if ($this->groupProgress()->isFollowerAnggota($mahasiswaId)) {
            return [
                'allowed' => false,
                'message' => 'Anda sudah tergabung sebagai anggota kelompok MBKM dan tidak dapat mendaftar jalur Skripsi Reguler.',
            ];
        }

        $applications = Application::where('mahasiswa_id', $mahasiswaId)
            ->where('is_group_mirror', false)
            ->orderBy('created_at', 'desc')
            ->get();

        // New student - can register
        if ($applications->isEmpty()) {
            if (Application::where('mahasiswa_id', $mahasiswaId)->where('is_group_mirror', true)->exists()) {
                return [
                    'allowed' => false,
                    'message' => 'Anda sudah tergabung sebagai anggota kelompok MBKM.',
                ];
            }

            return [
                'allowed' => true,
                'message' => null
            ];
        }

        // Check if student has active or approved Skripsi registration
        $activeSkripsi = $applications->where('type', 'skripsi')
            ->whereIn('status', ['submitted', 'approved', 'scheduled'])
            ->first();

        if ($activeSkripsi) {
            return [
                'allowed' => false,
                'message' => 'Anda sudah memiliki pendaftaran Skripsi yang aktif.'
            ];
        }

        // Check if student has active MBKM registration that is approved
        $activeMbkm = $applications->where('type', 'mbkm')
            ->whereIn('status', ['approved', 'scheduled'])
            ->first();

        if ($activeMbkm) {
            return [
                'allowed' => false,
                'message' => 'Anda sudah memilih jalur MBKM dan tidak dapat beralih ke jalur Skripsi Reguler.'
            ];
        }

        return [
            'allowed' => true,
            'message' => null
        ];
    }

    /**
     * Check if student can access MBKM Seminar
     */
    public function canAccessMbkmSeminar($mahasiswaId)
    {
        if ($this->groupProgress()->isFollowerAnggota($mahasiswaId)) {
            return [
                'allowed' => false,
                'message' => 'Review Kelayakan Proposal diisi oleh ketua kelompok (1 form per kelompok). Status Anda mengikuti pengajuan ketua sebagai lanjutan MbkmRegistration.',
                'application' => $this->groupProgress()->resolveOwnerApplication($mahasiswaId, 'registration'),
                'group_follower' => true,
            ];
        }

        // Must have registration accepted by supervisor (dosen)
        $registrationApp = Application::where('mahasiswa_id', $mahasiswaId)
            ->where('type', 'mbkm')
            ->where('stage', 'registration')
            ->where('status', 'approved')
            ->where('is_group_mirror', false)
            ->whereHas('assignments', function ($query) {
                $query->where('role', 'supervisor')->where('status', 'accepted');
            })
            ->first();

        if (!$registrationApp) {
            return [
                'allowed' => false,
                'message' => 'Anda harus menyelesaikan pendaftaran MBKM terlebih dahulu dan mendapat persetujuan.',
                'application' => null
            ];
        }

        // Check if there's already a seminar application
        $seminarApp = Application::where('mahasiswa_id', $mahasiswaId)
            ->where('type', 'mbkm')
            ->where('stage', 'seminar')
            ->where('is_group_mirror', false)
            ->whereIn('status', ['submitted', 'approved', 'scheduled'])
            ->first();

        if ($seminarApp) {
            return [
                'allowed' => false,
                'message' => 'Anda sudah mendaftar Review Kelayakan Proposal. Tunggu proses persetujuan.',
                'application' => $registrationApp
            ];
        }

        return [
            'allowed' => true,
            'message' => null,
            'application' => $registrationApp
        ];
    }

    /**
     * Check if student can access Skripsi Seminar
     */
    public function canAccessSkripsiSeminar($mahasiswaId)
    {
        // Must have registration accepted by supervisor (dosen)
        $registrationApp = Application::where('mahasiswa_id', $mahasiswaId)
            ->where('type', 'skripsi')
            ->where('stage', 'registration')
            ->where('status', 'approved')
            ->whereHas('assignments', function ($query) {
                $query->where('role', 'supervisor')->where('status', 'accepted');
            })
            ->first();

        if (!$registrationApp) {
            return [
                'allowed' => false,
                'message' => 'Anda harus menyelesaikan pendaftaran Skripsi terlebih dahulu dan mendapat persetujuan.',
                'application' => null
            ];
        }

        $seminarApp = Application::where('mahasiswa_id', $mahasiswaId)
            ->where('type', 'skripsi')
            ->where('stage', 'seminar')
            ->orderByDesc('created_at')
            ->first();

        if ($seminarApp) {
            $retrySeminar = $this->getSkripsiSeminarForFailedRetry($mahasiswaId);
            if ($retrySeminar) {
                return [
                    'allowed' => false,
                    'message' => 'Review proposal tidak lulus. Silakan perbaiki dan unggah ulang pendaftaran reviewer yang ada.',
                    'application' => $registrationApp,
                    'retry_seminar' => $retrySeminar,
                ];
            }

            if (in_array($seminarApp->status, ['submitted', 'approved', 'scheduled', 'revision'])) {
                return [
                    'allowed' => false,
                    'message' => 'Anda sudah mendaftar reviewer proposal. Tunggu proses persetujuan.',
                    'application' => $registrationApp,
                ];
            }
        }

        return [
            'allowed' => true,
            'message' => null,
            'application' => $registrationApp,
        ];
    }

    /**
     * Seminar registration that can be re-edited after a failed review report.
     */
    public function getSkripsiSeminarForFailedRetry($mahasiswaId): ?SkripsiSeminar
    {
        return SkripsiSeminar::whereHas('application', function ($query) use ($mahasiswaId) {
            $query->where('mahasiswa_id', $mahasiswaId)
                ->where('type', 'skripsi')
                ->where('stage', 'seminar')
                ->where('status', 'rejected');
        })
            ->whereIn('application_id', function ($query) {
                $query->select('application_id')
                    ->from('application_result_reviews')
                    ->where('result', 'failed')
                    ->whereNull('deleted_at');
            })
            ->with('application')
            ->orderByDesc('created_at')
            ->first();
    }

    /**
     * Whether mahasiswa may edit this SkripsiSeminar (revision/submitted or failed retry).
     */
    public function canEditSkripsiSeminar(SkripsiSeminar $seminar, $mahasiswaId): array
    {
        $seminar->loadMissing('application');

        if (!$seminar->application || (int) $seminar->application->mahasiswa_id !== (int) $mahasiswaId) {
            return [
                'allowed' => false,
                'message' => 'Anda tidak memiliki akses ke pendaftaran ini.',
                'retry_after_failed' => false,
            ];
        }

        if (in_array($seminar->application->status, ['submitted', 'revision'])) {
            return [
                'allowed' => true,
                'message' => null,
                'retry_after_failed' => false,
            ];
        }

        $retrySeminar = $this->getSkripsiSeminarForFailedRetry($mahasiswaId);
        if ($retrySeminar && (int) $retrySeminar->id === (int) $seminar->id) {
            return [
                'allowed' => true,
                'message' => null,
                'retry_after_failed' => true,
            ];
        }

        return [
            'allowed' => false,
            'message' => 'Pendaftaran tidak dapat diedit pada status ini.',
            'retry_after_failed' => false,
        ];
    }

    /**
     * Konteks MBKM untuk pelaporan hasil seminar.
     */
    private function findMbkmSeminarResultContext(int $mahasiswaId): ?array
    {
        if ($this->groupProgress()->isFollowerAnggota($mahasiswaId)) {
            // Anggota tidak mengisi laporan; konteks hanya untuk pesan
            $ownerSeminar = $this->groupProgress()->resolveOwnerApplication($mahasiswaId, 'seminar');
            if (!$ownerSeminar) {
                return null;
            }

            $mbkmSeminar = MbkmSeminar::where('application_id', $ownerSeminar->id)
                ->whereNotNull('reviewer_1_id')
                ->whereNotNull('reviewer_2_id')
                ->with('application')
                ->first();

            if (!$mbkmSeminar) {
                return null;
            }

            $schedule = ApplicationSchedule::where('application_id', $ownerSeminar->id)
                ->whereIn('schedule_type', ['mbkm_seminar', 'seminar'])
                ->orderByDesc('id')
                ->first();

            return [
                'seminar' => $mbkmSeminar,
                'application' => $ownerSeminar,
                'schedule' => $schedule,
                'group_follower' => true,
            ];
        }

        $mbkmSeminar = MbkmSeminar::whereHas('application', function ($query) use ($mahasiswaId) {
            $query->where('mahasiswa_id', $mahasiswaId)
                ->where('type', 'mbkm')
                ->where('stage', 'seminar')
                ->where('is_group_mirror', false)
                ->whereIn('status', ['approved', 'scheduled']);
        })
            ->whereNotNull('reviewer_1_id')
            ->whereNotNull('reviewer_2_id')
            ->with('application')
            ->orderByDesc('created_at')
            ->first();

        if (!$mbkmSeminar) {
            return null;
        }

        $schedule = ApplicationSchedule::where('application_id', $mbkmSeminar->application_id)
            ->whereIn('schedule_type', ['mbkm_seminar', 'seminar'])
            ->orderByDesc('id')
            ->first();

        return [
            'seminar' => $mbkmSeminar,
            'application' => $mbkmSeminar->application,
            'schedule' => $schedule,
        ];
    }

    /**
     * Check if student can submit/view laporan hasil review proposal (ApplicationResultSeminar).
     */
    public function canAccessApplicationResultSeminar($mahasiswaId, bool $forCreate = true)
    {
        $mbkmContext = $this->findMbkmSeminarResultContext($mahasiswaId);

        if ($mbkmContext) {
            if (!empty($mbkmContext['group_follower']) && $forCreate) {
                return [
                    'allowed' => false,
                    'message' => 'Laporan hasil Review Kelayakan Proposal diisi oleh ketua kelompok.',
                    'application' => $mbkmContext['application'],
                    'group_follower' => true,
                ];
            }

            $mbkmSeminar = $mbkmContext['seminar'];
            $mbkmSchedule = $mbkmContext['schedule'];

            if (!$mbkmSchedule) {
                return [
                    'allowed' => false,
                    'message' => 'Ajukan jadwal Review Kelayakan Proposal terlebih dahulu sebelum melaporkan hasil seminar.',
                    'application' => $mbkmSeminar->application,
                ];
            }

            if (!$mbkmSchedule->isReadyForResultReport()) {
                return [
                    'allowed' => false,
                    'message' => 'Jadwal Review Kelayakan Proposal masih menunggu verifikasi admin.',
                    'application' => $mbkmSeminar->application,
                ];
            }

            if ($forCreate && !$mbkmSchedule->isSeminarHeld()) {
                return [
                    'allowed' => false,
                    'message' => 'Pelaporan hasil Review Kelayakan Proposal tersedia setelah review dilaksanakan sesuai jadwal.',
                    'application' => $mbkmSeminar->application,
                ];
            }

            $existingMbkm = ApplicationResultSeminar::where('application_id', $mbkmSeminar->application_id)->exists();
            if ($forCreate && $existingMbkm) {
                return [
                    'allowed' => false,
                    'message' => 'Laporan hasil Review Kelayakan Proposal sudah pernah dikirim.',
                    'application' => $mbkmSeminar->application,
                ];
            }

            return [
                'allowed' => true,
                'message' => null,
                'application' => $mbkmSeminar->application,
            ];
        }

        return [
            'allowed' => false,
            'message' => null,
            'application' => null,
        ];
    }

    /**
     * Check if student can submit/view laporan hasil review proposal (ApplicationResultReview) — Skripsi Reguler.
     */
    public function canAccessApplicationResultReview($mahasiswaId, bool $forCreate = true): array
    {
        $seminar = SkripsiSeminar::whereHas('application', function ($query) use ($mahasiswaId) {
            $query->where('mahasiswa_id', $mahasiswaId)
                ->where('type', 'skripsi')
                ->where('stage', 'seminar');
        })
            ->whereHas('application', fn ($query) => $query->where('status', 'approved'))
            ->whereNotNull('reviewer_1_id')
            ->with('application')
            ->orderByDesc('created_at')
            ->first();

        if (!$seminar) {
            return [
                'allowed' => false,
                'message' => 'Anda harus menyelesaikan pendaftaran reviewer proposal dan mendapat persetujuan admin terlebih dahulu.',
                'application' => null,
            ];
        }

        $existing = ApplicationResultReview::where('application_id', $seminar->application_id)->exists();

        if ($forCreate && $existing) {
            return [
                'allowed' => false,
                'message' => 'Laporan hasil review proposal sudah pernah dikirim.',
                'application' => $seminar->application,
            ];
        }

        return [
            'allowed' => true,
            'message' => null,
            'application' => $seminar->application,
        ];
    }

    /**
     * Whether admin has validated a passed review result report (Skripsi Reguler).
     */
    public function isReviewResultValidatedByAdmin(int $applicationId): bool
    {
        return ApplicationAction::where('application_id', $applicationId)
            ->where('action_type', 'result_review_approved')
            ->exists();
    }

    /**
     * Whether admin has validated a passed seminar result report (mahasiswa may proceed to defense).
     */
    public function isSeminarResultValidatedByAdmin(int $applicationId): bool
    {
        return ApplicationAction::where('application_id', $applicationId)
            ->where('action_type', 'result_seminar_approved')
            ->exists();
    }

    /**
     * Aplikasi tahap seminar (Skripsi Reguler atau MBKM) yang menjadi prasyarat sidang.
     */
    private function findSeminarApplicationForDefense(int $mahasiswaId): ?Application
    {
        // Anggota kelompok: gunakan seminar ketua (bukan mirror)
        $ownerSeminar = $this->groupProgress()->resolveOwnerApplication($mahasiswaId, 'seminar');
        if ($ownerSeminar && !$ownerSeminar->is_group_mirror) {
            return $ownerSeminar;
        }

        return Application::where('mahasiswa_id', $mahasiswaId)
            ->where('stage', 'seminar')
            ->whereIn('type', ['skripsi', 'mbkm'])
            ->where('is_group_mirror', false)
            ->orderByDesc('created_at')
            ->first();
    }

    /**
     * Check if student can access Skripsi Defense (requires passed result validated by admin).
     */
    public function canAccessSkripsiDefense($mahasiswaId)
    {
        $seminarApp = $this->findSeminarApplicationForDefense($mahasiswaId);

        if (!$seminarApp) {
            return [
                'allowed' => false,
                'message' => 'Anda harus menyelesaikan review proposal dan mengirim laporan hasil terlebih dahulu.',
                'application' => null,
            ];
        }

        if ($seminarApp->type === 'mbkm') {
            $seminarResult = ApplicationResultSeminar::where('application_id', $seminarApp->id)
                ->orderByDesc('created_at')
                ->first();

            if (!$seminarResult) {
                return [
                    'allowed' => false,
                    'message' => 'Anda harus mengirim laporan hasil Review Kelayakan Proposal terlebih dahulu.',
                    'application' => $seminarApp,
                ];
            }

            if ($seminarResult->result === 'failed') {
                return [
                    'allowed' => false,
                    'message' => 'Review Kelayakan Proposal tidak lulus. Selesaikan perbaikan seminar terlebih dahulu.',
                    'application' => $seminarApp,
                ];
            }

            if ($seminarResult->result === 'revision') {
                return [
                    'allowed' => false,
                    'message' => 'Proposal masih dalam tahap revisi. Selesaikan revisi dan laporan hasil terlebih dahulu.',
                    'application' => $seminarApp,
                ];
            }

            if ($seminarResult->isEligibleOutcome() && !$this->isSeminarResultValidatedByAdmin($seminarApp->id)) {
                return [
                    'allowed' => false,
                    'message' => 'Laporan hasil menunggu validasi admin. Anda belum dapat mendaftar sidang skripsi.',
                    'application' => $seminarApp,
                    'pending_admin_validation' => true,
                ];
            }
        } else {
            $reviewResult = ApplicationResultReview::where('application_id', $seminarApp->id)
                ->orderByDesc('created_at')
                ->first();

            if (!$reviewResult) {
                return [
                    'allowed' => false,
                    'message' => 'Anda harus mengirim laporan hasil review proposal terlebih dahulu.',
                    'application' => $seminarApp,
                ];
            }

            if ($reviewResult->result === 'failed') {
                return [
                    'allowed' => false,
                    'message' => 'Review proposal tidak lulus. Perbaiki pendaftaran reviewer terlebih dahulu.',
                    'application' => $seminarApp,
                ];
            }

            if ($reviewResult->result === 'revision') {
                return [
                    'allowed' => false,
                    'message' => 'Proposal masih dalam tahap revisi. Selesaikan revisi dan laporan hasil terlebih dahulu.',
                    'application' => $seminarApp,
                ];
            }

            if ($reviewResult->result === 'passed' && !$this->isReviewResultValidatedByAdmin($seminarApp->id)) {
                return [
                    'allowed' => false,
                    'message' => 'Laporan hasil lulus menunggu validasi admin. Anda belum dapat mendaftar sidang skripsi.',
                    'application' => $seminarApp,
                    'pending_admin_validation' => true,
                ];
            }
        }

        if ($this->hasActiveDefenseApplicationBlockingRegistration($mahasiswaId)) {
            return [
                'allowed' => false,
                'message' => 'Anda sudah memiliki pendaftaran sidang skripsi yang masih aktif.',
                'application' => $seminarApp,
            ];
        }

        return [
            'allowed' => true,
            'message' => null,
            'application' => $seminarApp,
            'retry_after_failed' => $this->hasValidatedFailedDefenseResult($mahasiswaId),
        ];
    }

    /**
     * Hasil sidang tidak lulus sudah divalidasi admin — mahasiswa boleh mendaftar ulang SkripsiDefense.
     */
    public function hasValidatedFailedDefenseResult(int $mahasiswaId): bool
    {
        return ApplicationResultDefense::query()
            ->where('result', 'failed')
            ->whereHas('application', function ($q) use ($mahasiswaId) {
                $q->where('mahasiswa_id', $mahasiswaId)->where('stage', 'defense');
            })
            ->get()
            ->contains(fn (ApplicationResultDefense $result) => $result->isValidatedByAdmin());
    }

    /**
     * Apakah masih ada aplikasi tahap defense yang menghalangi pendaftaran sidang baru.
     */
    private function hasActiveDefenseApplicationBlockingRegistration(int $mahasiswaId): bool
    {
        $defenseApps = Application::where('mahasiswa_id', $mahasiswaId)
            ->whereIn('type', ['skripsi', 'mbkm'])
            ->where('stage', 'defense')
            ->whereIn('status', ['submitted', 'approved', 'scheduled', 'result', 'revision'])
            ->get();

        foreach ($defenseApps as $defenseApp) {
            if ($this->defenseCycleClosedByValidatedFailure($defenseApp->id)) {
                continue;
            }

            return true;
        }

        return false;
    }

    /**
     * Siklus sidang dianggap selesai (gagal) setelah admin memvalidasi laporan hasil failed.
     */
    public function defenseCycleClosedByValidatedFailure(int $defenseApplicationId): bool
    {
        $resultDefense = ApplicationResultDefense::where('application_id', $defenseApplicationId)->first();

        if (!$resultDefense || $resultDefense->result !== 'failed') {
            return false;
        }

        return $resultDefense->isValidatedByAdmin();
    }

    /**
     * Whether Laporan Hasil Seminar shortcuts should appear (MBKM only).
     */
    public function canShowApplicationResultSeminarShortcut($mahasiswaId): array
    {
        $seminarApp = Application::where('mahasiswa_id', $mahasiswaId)
            ->where('stage', 'seminar')
            ->where('type', 'mbkm')
            ->orderByDesc('created_at')
            ->first();

        if (!$seminarApp) {
            return [
                'allowed' => false,
                'message' => null,
            ];
        }

        $result = ApplicationResultSeminar::where('application_id', $seminarApp->id)->first();

        if ($result) {
            if ($result->isEligibleOutcome() && $this->isSeminarResultValidatedByAdmin($seminarApp->id)) {
                return [
                    'allowed' => false,
                    'message' => 'Laporan hasil seminar sudah divalidasi admin.',
                ];
            }

            return [
                'allowed' => true,
                'message' => null,
            ];
        }

        $mbkmContext = $this->findMbkmSeminarResultContext($mahasiswaId);

        if ($mbkmContext && $mbkmContext['schedule']?->isReadyForResultReport()) {
            return [
                'allowed' => true,
                'message' => null,
            ];
        }

        return [
            'allowed' => false,
            'message' => null,
        ];
    }

    /**
     * Whether Laporan Hasil Review Proposal shortcuts should appear (Skripsi Reguler only).
     */
    public function canShowApplicationResultReviewShortcut($mahasiswaId): array
    {
        $seminarApp = Application::where('mahasiswa_id', $mahasiswaId)
            ->where('stage', 'seminar')
            ->where('type', 'skripsi')
            ->orderByDesc('created_at')
            ->first();

        if (!$seminarApp) {
            return [
                'allowed' => false,
                'message' => null,
            ];
        }

        $result = ApplicationResultReview::where('application_id', $seminarApp->id)->first();

        if ($result) {
            if ($result->result === 'passed' && $this->isReviewResultValidatedByAdmin($seminarApp->id)) {
                return [
                    'allowed' => false,
                    'message' => 'Laporan hasil review sudah divalidasi admin.',
                ];
            }

            return [
                'allowed' => true,
                'message' => null,
            ];
        }

        return $this->canAccessApplicationResultReview($mahasiswaId, true);
    }

    /**
     * Aplikasi seminar/sidang yang memenuhi syarat status untuk pengajuan jadwal.
     */
    private function findScheduleEligibleApplication($mahasiswaId, string $stage): ?Application
    {
        return Application::where('mahasiswa_id', $mahasiswaId)
            ->where('stage', $stage)
            ->where('is_group_mirror', false)
            ->where(function ($query) {
                $query->whereIn('status', ['approved', 'scheduled'])
                    ->orWhere(function ($rejectedQuery) {
                        $rejectedQuery->where('status', 'rejected')
                            ->whereHas('actions', fn ($action) => $action->where('action_type', 'schedule_rejected'));
                    });
            })
            ->orderByDesc('created_at')
            ->first();
    }

    /**
     * Mahasiswa dapat mengajukan jadwal sidang setelah pendaftaran sidang diterima admin.
     */
    public function canAccessDefenseSchedule($mahasiswaId)
    {
        $defenseApp = $this->findScheduleEligibleApplication($mahasiswaId, 'defense');

        if (!$defenseApp) {
            return [
                'allowed' => false,
                'message' => 'Pendaftaran sidang belum disetujui admin.',
                'application' => null,
            ];
        }

        $defense = SkripsiDefense::where('application_id', $defenseApp->id)->first();

        if (!$defense || !$defense->isAccepted()) {
            return [
                'allowed' => false,
                'message' => 'Pendaftaran sidang harus diterima admin (status diterima) sebelum mengajukan jadwal.',
                'application' => $defenseApp,
            ];
        }

        if (ApplicationSchedule::hasBlockingScheduleFor($defenseApp->id)) {
            return [
                'allowed' => false,
                'message' => 'Jadwal sidang masih menunggu verifikasi admin. Pantau status di menu Jadwal.',
                'application' => $defenseApp,
            ];
        }

        return [
            'allowed' => true,
            'message' => null,
            'application' => $defenseApp,
        ];
    }

    /**
     * Mahasiswa dapat mengajukan jadwal untuk seminar (MBKM/Skripsi) yang approved
     * atau sidang skripsi yang pendaftarannya sudah diterima admin.
     */
    public function canAccessApplicationSchedule($mahasiswaId): array
    {
        if ($this->groupProgress()->isFollowerAnggota($mahasiswaId)) {
            return [
                'allowed' => false,
                'message' => 'Pengajuan jadwal Review Kelayakan Proposal dilakukan oleh ketua kelompok.',
                'application' => $this->groupProgress()->resolveOwnerApplication($mahasiswaId, 'seminar'),
                'context' => null,
                'group_follower' => true,
            ];
        }

        $defenseAccess = $this->canAccessDefenseSchedule($mahasiswaId);
        if ($defenseAccess['allowed']) {
            return [
                'allowed' => true,
                'message' => null,
                'application' => $defenseAccess['application'],
                'context' => 'defense',
            ];
        }

        $seminarApp = $this->findScheduleEligibleApplication($mahasiswaId, 'seminar');

        if ($seminarApp && ApplicationSchedule::applicationEligibleForNewSchedule($seminarApp->id)) {
            return [
                'allowed' => true,
                'message' => null,
                'application' => $seminarApp,
                'context' => 'seminar',
            ];
        }

        return [
            'allowed' => false,
            'message' => 'Belum ada pendaftaran seminar/sidang yang memenuhi syarat pengajuan jadwal.',
            'application' => null,
            'context' => null,
        ];
    }

    /**
     * Mahasiswa dapat melaporkan hasil sidang setelah jadwal sidang diverifikasi admin
     * dan waktu pelaksanaan sidang sudah lewat (Skripsi Reguler & MBKM).
     */
    public function canAccessDefenseResult($mahasiswaId): array
    {
        $defenseApp = Application::where('mahasiswa_id', $mahasiswaId)
            ->where('stage', 'defense')
            ->whereIn('type', ['skripsi', 'mbkm'])
            ->orderByDesc('created_at')
            ->first();

        if (!$defenseApp) {
            return [
                'allowed' => false,
                'message' => 'Tidak ada aplikasi tahap sidang.',
                'application' => null,
            ];
        }

        $defense = SkripsiDefense::where('application_id', $defenseApp->id)->first();

        if (!$defense || !$defense->isAccepted()) {
            return [
                'allowed' => false,
                'message' => 'Pendaftaran sidang harus diterima admin terlebih dahulu.',
                'application' => $defenseApp,
            ];
        }

        $scoringService = app(DefenseScoringService::class);
        $schedule = $scoringService->resolveDefenseSchedule($defenseApp);

        if (!$schedule) {
            return [
                'allowed' => false,
                'message' => 'Ajukan jadwal sidang dan tunggu verifikasi admin sebelum melaporkan hasil sidang.',
                'application' => $defenseApp,
            ];
        }

        if (!$scoringService->isDefenseScheduleApproved($defenseApp)) {
            return [
                'allowed' => false,
                'message' => 'Jadwal sidang belum diverifikasi admin.',
                'application' => $defenseApp,
            ];
        }

        if (!$scoringService->isDefenseHeld($defenseApp)) {
            return [
                'allowed' => false,
                'message' => 'Sidang belum dilaksanakan. Laporan hasil dapat diajukan setelah waktu sidang lewat.',
                'application' => $defenseApp,
            ];
        }

        if (ApplicationResultDefense::where('application_id', $defenseApp->id)->exists()) {
            return [
                'allowed' => false,
                'message' => 'Hasil sidang sudah dilaporkan.',
                'application' => $defenseApp,
            ];
        }

        return [
            'allowed' => true,
            'message' => null,
            'application' => $defenseApp,
        ];
    }

    /**
     * Tampilkan menu hasil sidang jika sudah bisa lapor atau sudah pernah lapor.
     */
    public function canShowDefenseResultShortcut($mahasiswaId): array
    {
        $defenseApp = Application::where('mahasiswa_id', $mahasiswaId)
            ->where('stage', 'defense')
            ->whereIn('type', ['skripsi', 'mbkm'])
            ->orderByDesc('created_at')
            ->first();

        if (!$defenseApp) {
            return ['allowed' => false, 'message' => null];
        }

        if (ApplicationResultDefense::where('application_id', $defenseApp->id)->exists()) {
            return ['allowed' => true, 'message' => null];
        }

        return $this->canAccessDefenseResult($mahasiswaId);
    }

    /**
     * Get allowed forms for a student
     */
    public function getAllowedForms($mahasiswaId)
    {
        return [
            'mbkm_registration' => $this->canAccessMbkmRegistration($mahasiswaId),
            'skripsi_registration' => $this->canAccessSkripsiRegistration($mahasiswaId),
            'mbkm_seminar' => $this->canAccessMbkmSeminar($mahasiswaId),
            'skripsi_seminar' => $this->canAccessSkripsiSeminar($mahasiswaId),
            'application_result_seminar' => $this->canShowApplicationResultSeminarShortcut($mahasiswaId),
            'application_result_review' => $this->canShowApplicationResultReviewShortcut($mahasiswaId),
            'skripsi_defense' => $this->canAccessSkripsiDefense($mahasiswaId),
            'defense_schedule' => $this->canAccessDefenseSchedule($mahasiswaId),
            'application_schedule' => $this->canAccessApplicationSchedule($mahasiswaId),
            'defense_result' => $this->canShowDefenseResultShortcut($mahasiswaId),
        ];
    }

    /**
     * Check if mahasiswa has any active application
     */
    public function hasActiveApplication($mahasiswaId)
    {
        return Application::where('mahasiswa_id', $mahasiswaId)
            ->whereIn('status', ['submitted', 'approved', 'scheduled'])
            ->exists();
    }

    /**
     * Get active application for mahasiswa
     */
    public function getActiveApplication($mahasiswaId)
    {
        return Application::where('mahasiswa_id', $mahasiswaId)
            ->whereIn('status', ['submitted', 'approved', 'scheduled'])
            ->orderBy('created_at', 'desc')
            ->first();
    }
}
