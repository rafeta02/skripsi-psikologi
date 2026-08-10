<?php

namespace App\Services;

use App\Models\Application;
use App\Models\ApplicationAction;
use App\Models\ApplicationAssignment;
use App\Models\ApplicationResultDefense;
use App\Models\ApplicationSchedule;
use App\Models\ApplicationScore;
use App\Models\SkripsiDefense;

class DefenseScoringService
{
    public function resolveDefenseSchedule(Application $application): ?ApplicationSchedule
    {
        return ApplicationSchedule::where('application_id', $application->id)
            ->whereIn('schedule_type', ['skripsi_defense', 'defense'])
            ->orderByDesc('id')
            ->first();
    }

    public function isDefenseScheduleApproved(Application $application): bool
    {
        $schedule = $this->resolveDefenseSchedule($application);

        if (!$schedule) {
            return false;
        }

        return $schedule->isDefenseScheduleVerified();
    }

    /**
     * Sidang sudah dilaksanakan (jadwal disetujui admin dan waktu sidang sudah lewat).
     */
    public function isDefenseHeld(Application $application): bool
    {
        $schedule = $this->resolveDefenseSchedule($application);

        if (!$schedule || !$this->isDefenseScheduleApproved($application)) {
            return false;
        }

        return $schedule->isSeminarHeld();
    }

    /**
     * Dosen pembimbing + penguji sidang yang wajib memberi nilai.
     *
     * @return array<int>
     */
    public function getScorerDosenIds(Application $application): array
    {
        $ids = [];

        $regApp = Application::where('mahasiswa_id', $application->mahasiswa_id)
            ->where('type', $application->type)
            ->where('stage', 'registration')
            ->orderByDesc('created_at')
            ->first();

        if ($regApp) {
            $regApp->loadMissing('skripsiRegistration');

            if ($regApp->skripsiRegistration?->assigned_supervisor_id) {
                $ids[] = (int) $regApp->skripsiRegistration->assigned_supervisor_id;
            }

            $supervisorIds = ApplicationAssignment::where('application_id', $regApp->id)
                ->where('role', 'supervisor')
                ->where('status', 'accepted')
                ->pluck('lecturer_id')
                ->all();
            $ids = array_merge($ids, $supervisorIds);
        }

        $supervisorId = $application->resolveSupervisorLecturerId();
        if ($supervisorId) {
            $ids[] = $supervisorId;
        }

        $skripsiDefense = SkripsiDefense::queryForDosenPortal()
            ->where('application_id', $application->id)
            ->first();
        if ($skripsiDefense) {
            $examinerIds = $skripsiDefense->examiners()->pluck('dosen_id')->all();
            $ids = array_merge($ids, $examinerIds);
        }

        return array_values(array_unique(array_filter($ids)));
    }

    /**
     * Pendaftaran sidang dianggap disetujui (termasuk data lama yang belum sinkron status).
     */
    public function isDefenseApprovedForManuscriptAccess(SkripsiDefense $skripsiDefense): bool
    {
        if ($skripsiDefense->isAccepted()) {
            return true;
        }

        if (! $skripsiDefense->application_id) {
            return false;
        }

        if (ApplicationAction::where('application_id', $skripsiDefense->application_id)
            ->where('action_type', 'defense_approved')
            ->exists()) {
            return true;
        }

        $application = $skripsiDefense->application;

        return $application
            && in_array($application->status, ['approved', 'scheduled', 'result', 'revision'], true)
            && $skripsiDefense->examiners()->count() >= 2;
    }

    public function hasCompleteDefenseExaminers(SkripsiDefense $skripsiDefense): bool
    {
        return $skripsiDefense->examiners()->count() >= 2
            || ($skripsiDefense->examiner1?->dosen_id && $skripsiDefense->examiner2?->dosen_id);
    }

    /**
     * Buat record ApplicationScore kosong setelah sidang dilaksanakan.
     */
    public function ensureScoreAssignments(Application $application): void
    {
        if (! $this->isDefenseHeld($application)) {
            return;
        }

        $resultDefense = ApplicationResultDefense::where('application_id', $application->id)->first();

        if ($resultDefense?->result === 'failed') {
            return;
        }

        foreach ($this->getScorerDosenIds($application) as $dosenId) {
            ApplicationScore::firstOrCreate(
                [
                    'application_id' => $application->id,
                    'examiner_id' => $dosenId,
                ],
                [
                    'application_result_defence_id' => $resultDefense?->id,
                ]
            );
        }

        if ($resultDefense) {
            ApplicationScore::where('application_id', $application->id)
                ->whereNull('application_result_defence_id')
                ->update(['application_result_defence_id' => $resultDefense->id]);
        }
    }

    public function isScoringComplete(Application $application): bool
    {
        $scores = ApplicationScore::where('application_id', $application->id)->get();

        if ($scores->isEmpty()) {
            return false;
        }

        return $scores->every(fn (ApplicationScore $score) => $score->isComplete());
    }

    public function canDosenScore(Application $application, int $dosenId): bool
    {
        if (! in_array($dosenId, $this->getScorerDosenIds($application), true)) {
            return false;
        }

        if (! $this->isDefenseHeld($application)) {
            return false;
        }

        $resultDefense = ApplicationResultDefense::where('application_id', $application->id)->first();

        if ($resultDefense?->result === 'failed') {
            return false;
        }

        if ($resultDefense?->isValidatedByAdmin()) {
            return in_array($resultDefense->result, ['passed', 'revision'], true);
        }

        return true;
    }

    public function findScoreAssignmentForApplication(Application $application, int $dosenId): ?ApplicationScore
    {
        if (! in_array($dosenId, $this->getScorerDosenIds($application), true)) {
            return null;
        }

        $this->ensureScoreAssignments($application);

        return ApplicationScore::where('application_id', $application->id)
            ->where('examiner_id', $dosenId)
            ->first();
    }

    /**
     * @return \Illuminate\Support\Collection<int, ApplicationScore>
     */
    public function getScoreAssignmentsForDosen(int $dosenId): \Illuminate\Support\Collection
    {
        $this->syncAssignmentsForDosen($dosenId);

        return ApplicationScore::with([
            'application.mahasiswa.prodi',
            'application.resultDefense',
            'application.skripsiDefense',
            'application_result_defence.application.mahasiswa.prodi',
        ])
            ->where('examiner_id', $dosenId)
            ->orderByRaw('CASE WHEN score IS NULL THEN 0 ELSE 1 END')
            ->orderByDesc('updated_at')
            ->get()
            ->filter(function (ApplicationScore $score) use ($dosenId) {
                $application = $score->application ?? $score->application_result_defence?->application;

                if (! $application) {
                    return false;
                }

                if ($score->application_result_defence?->isValidatedByAdmin()) {
                    return in_array($score->application_result_defence->result, ['passed', 'revision'], true);
                }

                return $this->canDosenScore($application, $dosenId);
            })
            ->values();
    }

    public function countPendingScoresForDosen(int $dosenId): int
    {
        return $this->getScoreAssignmentsForDosen($dosenId)
            ->filter(fn (ApplicationScore $score) => ! $score->isComplete())
            ->count();
    }

    /**
     * Sinkronkan penugasan nilai untuk semua aplikasi sidang yang relevan dengan dosen.
     */
    public function syncAssignmentsForDosen(int $dosenId): void
    {
        Application::where('stage', 'defense')
            ->orderByDesc('created_at')
            ->get()
            ->each(function (Application $application) use ($dosenId) {
                if (in_array($dosenId, $this->getScorerDosenIds($application), true)) {
                    $this->ensureScoreAssignments($application);
                }
            });
    }

    public function linkScoresToResultDefense(ApplicationResultDefense $resultDefense): void
    {
        ApplicationScore::where('application_id', $resultDefense->application_id)
            ->where(function ($query) use ($resultDefense) {
                $query->whereNull('application_result_defence_id')
                    ->orWhere('application_result_defence_id', $resultDefense->id);
            })
            ->update([
                'application_result_defence_id' => $resultDefense->id,
                'application_id' => $resultDefense->application_id,
            ]);

        $resultDefense->refresh();
    }

    /**
     * Pembimbing atau penguji boleh membaca naskah sidang setelah admin menerima
     * pendaftaran dan penguji sudah ditetapkan.
     */
    public function canDosenViewDefenseManuscript(
        Application $application,
        int $dosenId,
        ?SkripsiDefense $skripsiDefense = null
    ): bool {
        if (! in_array($dosenId, $this->getScorerDosenIds($application), true)) {
            return false;
        }

        $skripsiDefense ??= SkripsiDefense::queryForDosenPortal()
            ->where('application_id', $application->id)
            ->first();

        if (! $skripsiDefense) {
            return false;
        }

        if (! $this->isDefenseApprovedForManuscriptAccess($skripsiDefense)) {
            return false;
        }

        return $this->hasCompleteDefenseExaminers($skripsiDefense);
    }

    /**
     * @return \Illuminate\Support\Collection<int, SkripsiDefense>
     */
    public function getViewableDefensesForDosen(int $dosenId): \Illuminate\Support\Collection
    {
        $defenses = SkripsiDefense::queryForDosenPortal()
            ->with([
                'application.mahasiswa.prodi',
                'application.actions',
                'examiners.dosen',
                'examiner1.dosen',
                'examiner2.dosen',
            ])
            ->whereHas('application', function ($query) {
                $query->where('stage', 'defense');
            })
            ->orderByDesc('updated_at')
            ->get();

        return $defenses
            ->filter(function (SkripsiDefense $defense) use ($dosenId) {
                $application = $defense->application;

                return $application
                    && $this->canDosenViewDefenseManuscript($application, $dosenId, $defense);
            })
            ->values();
    }

    /**
     * Cari naskah sidang aktif mahasiswa yang dapat diakses dosen pembimbing.
     */
    public function findViewableDefenseForMahasiswa(int $mahasiswaId, int $dosenId): ?SkripsiDefense
    {
        $applications = Application::query()
            ->where('mahasiswa_id', $mahasiswaId)
            ->where('stage', 'defense')
            ->orderByDesc('created_at')
            ->get();

        foreach ($applications as $application) {
            $defense = SkripsiDefense::queryForDosenPortal()
                ->where('application_id', $application->id)
                ->first();

            if ($defense && $this->canDosenViewDefenseManuscript($application, $dosenId, $defense)) {
                return $defense;
            }
        }

        return null;
    }

    public function countViewableDefensesForDosen(int $dosenId): int
    {
        return $this->getViewableDefensesForDosen($dosenId)->count();
    }

    /**
     * Pembimbing/penguji boleh melihat laporan hasil sidang setelah admin memvalidasi laporan mahasiswa.
     */
    public function canDosenViewDefenseResultReport(
        Application $application,
        int $dosenId,
        ?ApplicationResultDefense $resultDefense = null
    ): bool {
        if (! in_array($dosenId, $this->getScorerDosenIds($application), true)) {
            return false;
        }

        $resultDefense ??= ApplicationResultDefense::where('application_id', $application->id)->first();

        return $resultDefense !== null && $resultDefense->isValidatedByAdmin();
    }

    /**
     * @return \Illuminate\Support\Collection<int, ApplicationResultDefense>
     */
    public function getViewableDefenseResultsForDosen(int $dosenId): \Illuminate\Support\Collection
    {
        return ApplicationResultDefense::query()
            ->with([
                'application.mahasiswa.prodi',
                'application.skripsiDefense',
            ])
            ->whereHas('application', function ($query) {
                $query->where('stage', 'defense');
            })
            ->orderByDesc('created_at')
            ->get()
            ->filter(function (ApplicationResultDefense $resultDefense) use ($dosenId) {
                $application = $resultDefense->application;

                return $application
                    && $this->canDosenViewDefenseResultReport($application, $dosenId, $resultDefense);
            })
            ->values();
    }

    public function countViewableDefenseResultsForDosen(int $dosenId): int
    {
        return $this->getViewableDefenseResultsForDosen($dosenId)->count();
    }

    public function findViewableDefenseResultForMahasiswa(int $mahasiswaId, int $dosenId): ?ApplicationResultDefense
    {
        $applications = Application::query()
            ->where('mahasiswa_id', $mahasiswaId)
            ->where('stage', 'defense')
            ->orderByDesc('created_at')
            ->get();

        foreach ($applications as $application) {
            $resultDefense = ApplicationResultDefense::where('application_id', $application->id)->first();

            if ($resultDefense && $this->canDosenViewDefenseResultReport($application, $dosenId, $resultDefense)) {
                return $resultDefense;
            }
        }

        return null;
    }
}
