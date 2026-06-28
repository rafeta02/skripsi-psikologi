<?php

namespace App\Services;

use App\Models\Application;
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
            $supervisorIds = ApplicationAssignment::where('application_id', $regApp->id)
                ->where('role', 'supervisor')
                ->where('status', 'accepted')
                ->pluck('lecturer_id')
                ->all();
            $ids = array_merge($ids, $supervisorIds);
        }

        $skripsiDefense = SkripsiDefense::where('application_id', $application->id)->first();
        if ($skripsiDefense) {
            $examinerIds = $skripsiDefense->examiners()->pluck('dosen_id')->all();
            $ids = array_merge($ids, $examinerIds);
        }

        return array_values(array_unique(array_filter($ids)));
    }

    /**
     * Buat record ApplicationScore kosong setelah sidang dilaksanakan.
     */
    public function ensureScoreAssignments(Application $application): void
    {
        if (!$this->isDefenseHeld($application)) {
            return;
        }

        if (ApplicationResultDefense::where('application_id', $application->id)->exists()) {
            return;
        }

        foreach ($this->getScorerDosenIds($application) as $dosenId) {
            ApplicationScore::firstOrCreate(
                [
                    'application_id' => $application->id,
                    'examiner_id' => $dosenId,
                ],
                [
                    'application_result_defence_id' => null,
                ]
            );
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
        if (!in_array($dosenId, $this->getScorerDosenIds($application), true)) {
            return false;
        }

        if (!$this->isDefenseHeld($application)) {
            return false;
        }

        if (ApplicationResultDefense::where('application_id', $application->id)->exists()) {
            return false;
        }

        return true;
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
}
