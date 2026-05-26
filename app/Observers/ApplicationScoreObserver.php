<?php

namespace App\Observers;

use App\Models\ApplicationScore;
use App\Models\ApplicationResultDefense;

class ApplicationScoreObserver
{
    /**
     * Handle the ApplicationScore "created" event.
     */
    public function created(ApplicationScore $applicationScore)
    {
        $this->updateDefenseGrade($applicationScore);
    }

    /**
     * Handle the ApplicationScore "updated" event.
     */
    public function updated(ApplicationScore $applicationScore)
    {
        $this->updateDefenseGrade($applicationScore);
    }

    /**
     * Handle the ApplicationScore "deleted" event.
     */
    public function deleted(ApplicationScore $applicationScore)
    {
        $this->updateDefenseGrade($applicationScore);
    }

    /**
     * Update the defense grade letter based on final score
     */
    protected function updateDefenseGrade(ApplicationScore $applicationScore)
    {
        if (!$applicationScore->application_result_defence_id) {
            return;
        }

        $defense = ApplicationResultDefense::find($applicationScore->application_result_defence_id);

        if (!$defense) {
            return;
        }

        $finalScore = $defense->final_score;
        $gradeLetter = ApplicationResultDefense::convertScoreToGrade($finalScore);

        $defense->update([
            'final_grade' => $finalScore,
            'final_grade_letter' => $gradeLetter,
        ]);

        if (!$defense->isValidatedByAdmin()) {
            return;
        }

        $totalScorers = $defense->scores()->count();
        $completedScorers = $defense->scores()->whereNotNull('score')->count();

        if ($totalScorers > 0 && $totalScorers === $completedScorers && $defense->application) {
            $defense->application->update(['status' => 'done']);
        }
    }
}

