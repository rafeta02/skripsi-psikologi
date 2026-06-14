<?php

namespace App\Services;

use App\Models\Application;
use App\Models\MbkmRegistration;
use App\Models\SkripsiRegistration;
use App\Models\MbkmSeminar;
use App\Models\SkripsiSeminar;
use App\Models\ApplicationAction;
use App\Models\ApplicationResultSeminar;
use App\Models\ApplicationSchedule;
use App\Models\SkripsiDefense;
use App\Models\ApplicationResultDefense;
use App\Models\Mahasiswa;

class FormAccessService
{
    /**
     * Check if student can access MBKM Registration
     */
    public function canAccessMbkmRegistration($mahasiswaId)
    {
        // Check if student has any existing applications
        $applications = Application::where('mahasiswa_id', $mahasiswaId)
            ->orderBy('created_at', 'desc')
            ->get();

        // New student - can register
        if ($applications->isEmpty()) {
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
        $applications = Application::where('mahasiswa_id', $mahasiswaId)
            ->orderBy('created_at', 'desc')
            ->get();

        // New student - can register
        if ($applications->isEmpty()) {
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
        // Must have registration accepted by supervisor (dosen)
        $registrationApp = Application::where('mahasiswa_id', $mahasiswaId)
            ->where('type', 'mbkm')
            ->where('stage', 'registration')
            ->where('status', 'approved')
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
            ->whereIn('status', ['submitted', 'approved', 'scheduled'])
            ->first();

        if ($seminarApp) {
            return [
                'allowed' => false,
                'message' => 'Anda sudah mendaftar seminar MBKM. Tunggu proses persetujuan.',
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
                    ->from('application_result_seminars')
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
        $mbkmSeminar = MbkmSeminar::whereHas('application', function ($query) use ($mahasiswaId) {
            $query->where('mahasiswa_id', $mahasiswaId)
                ->where('type', 'mbkm')
                ->where('stage', 'seminar')
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
            $mbkmSeminar = $mbkmContext['seminar'];
            $mbkmSchedule = $mbkmContext['schedule'];

            if (!$mbkmSchedule) {
                return [
                    'allowed' => false,
                    'message' => 'Ajukan jadwal seminar MBKM terlebih dahulu sebelum melaporkan hasil seminar.',
                    'application' => $mbkmSeminar->application,
                ];
            }

            if (!$mbkmSchedule->isReadyForResultReport()) {
                return [
                    'allowed' => false,
                    'message' => 'Jadwal seminar MBKM masih menunggu verifikasi admin.',
                    'application' => $mbkmSeminar->application,
                ];
            }

            if ($forCreate && !$mbkmSchedule->isSeminarHeld()) {
                return [
                    'allowed' => false,
                    'message' => 'Pelaporan hasil seminar MBKM tersedia setelah seminar dilaksanakan sesuai jadwal.',
                    'application' => $mbkmSeminar->application,
                ];
            }

            $existingMbkm = ApplicationResultSeminar::where('application_id', $mbkmSeminar->application_id)->exists();
            if ($forCreate && $existingMbkm) {
                return [
                    'allowed' => false,
                    'message' => 'Laporan hasil seminar MBKM sudah pernah dikirim.',
                    'application' => $mbkmSeminar->application,
                ];
            }

            return [
                'allowed' => true,
                'message' => null,
                'application' => $mbkmSeminar->application,
            ];
        }

        $seminar = SkripsiSeminar::whereHas('application', function ($query) use ($mahasiswaId) {
            $query->where('mahasiswa_id', $mahasiswaId)->where('type', 'skripsi');
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

        $existing = ApplicationResultSeminar::where('application_id', $seminar->application_id)->exists();

        if ($forCreate && $existing) {
            return [
                'allowed' => false,
                'message' => 'Laporan hasil review sudah pernah dikirim.',
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
        return Application::where('mahasiswa_id', $mahasiswaId)
            ->where('stage', 'seminar')
            ->whereIn('type', ['skripsi', 'mbkm'])
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
                'message' => 'Anda harus menyelesaikan seminar proposal dan mengirim laporan hasil terlebih dahulu.',
                'application' => null,
            ];
        }

        $seminarResult = ApplicationResultSeminar::where('application_id', $seminarApp->id)
            ->orderByDesc('created_at')
            ->first();

        if (!$seminarResult) {
            return [
                'allowed' => false,
                'message' => 'Anda harus mengirim laporan hasil seminar proposal terlebih dahulu.',
                'application' => $seminarApp,
            ];
        }

        if ($seminarResult->result === 'failed') {
            return [
                'allowed' => false,
                'message' => $seminarApp->type === 'mbkm'
                    ? 'Seminar MBKM tidak lulus. Selesaikan perbaikan seminar terlebih dahulu.'
                    : 'Review proposal tidak lulus. Perbaiki pendaftaran reviewer terlebih dahulu.',
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

        if ($seminarResult->result === 'passed' && !$this->isSeminarResultValidatedByAdmin($seminarApp->id)) {
            return [
                'allowed' => false,
                'message' => 'Laporan hasil lulus menunggu validasi admin. Anda belum dapat mendaftar sidang skripsi.',
                'application' => $seminarApp,
                'pending_admin_validation' => true,
            ];
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
     * Whether Laporan Hasil Review shortcuts should appear (dashboard, menu, dokumen).
     * Hidden after admin validates a passed report.
     */
    public function canShowApplicationResultSeminarShortcut($mahasiswaId): array
    {
        $seminarApp = Application::where('mahasiswa_id', $mahasiswaId)
            ->where('stage', 'seminar')
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
            if (
                $seminarApp->type === 'skripsi'
                && $result->result === 'passed'
                && $this->isSeminarResultValidatedByAdmin($seminarApp->id)
            ) {
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

        if ($seminarApp->type === 'mbkm') {
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

        return $this->canAccessApplicationResultSeminar($mahasiswaId, true);
    }

    /**
     * Aplikasi seminar/sidang yang memenuhi syarat status untuk pengajuan jadwal.
     */
    private function findScheduleEligibleApplication($mahasiswaId, string $stage): ?Application
    {
        return Application::where('mahasiswa_id', $mahasiswaId)
            ->where('stage', $stage)
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
     * Mahasiswa dapat melaporkan hasil sidang setelah jadwal sidang diverifikasi admin.
     */
    public function canAccessDefenseResult($mahasiswaId): array
    {
        $defenseApp = Application::where('mahasiswa_id', $mahasiswaId)
            ->where('stage', 'defense')
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

        $schedule = ApplicationSchedule::where('application_id', $defenseApp->id)->first();

        if (!$schedule) {
            return [
                'allowed' => false,
                'message' => 'Ajukan jadwal sidang dan tunggu verifikasi admin sebelum melaporkan hasil sidang.',
                'application' => $defenseApp,
            ];
        }

        if (!$schedule->isApprovedByAdmin() && $defenseApp->status !== 'scheduled') {
            return [
                'allowed' => false,
                'message' => 'Jadwal sidang belum diverifikasi admin.',
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
