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
        if ($applicationScore->application_result_defence_id) {
            $defense = ApplicationResultDefense::find($applicationScore->application_result_defence_id);
            
            if ($defense) {
                // Calculate final score
                $finalScore = $defense->final_score;
                
                // Convert to letter grade
                $gradeLetter = ApplicationResultDefense::convertScoreToGrade($finalScore);
                
                // Update defense record
                $defense->update([
                    'final_grade' => $finalScore,
                    'final_grade_letter' => $gradeLetter
                ]);
            }
        }
    }
}

