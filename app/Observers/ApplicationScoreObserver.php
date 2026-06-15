<?php

namespace App\Observers;

use App\Models\ApplicationScore;
use App\Models\ApplicationResultDefense;

class ApplicationScoreObserver
{
    public function created(ApplicationScore $applicationScore)
    {
        $this->syncDefenseCompletion($applicationScore);
    }

    public function updated(ApplicationScore $applicationScore)
    {
        $this->syncDefenseCompletion($applicationScore);
    }

    public function deleted(ApplicationScore $applicationScore)
    {
        $this->syncDefenseCompletion($applicationScore);
    }

    protected function syncDefenseCompletion(ApplicationScore $applicationScore)
    {
        if (!$applicationScore->application_result_defence_id) {
            return;
        }

        $defense = ApplicationResultDefense::find($applicationScore->application_result_defence_id);

        if (!$defense || !$defense->isValidatedByAdmin() || !$defense->application) {
            return;
        }

        $totalScorers = $defense->scores()->count();
        $completedScorers = $defense->scores()->whereNotNull('score')->count();

        if ($totalScorers > 0 && $totalScorers === $completedScorers) {
            $defense->application->update(['status' => 'done']);
        }
    }
}
