<?php

namespace App\Policies;

use App\Models\Application;
use App\Models\ApplicationAssignment;
use App\Models\ApplicationResultDefense;
use App\Models\ApplicationScore;
use App\Models\SkripsiDefense;
use App\Models\SkripsiDefenseExaminer;
use App\Models\User;

class ApplicationPolicy
{
    public function view(User $user, Application $application): bool
    {
        if ($user->is_admin) {
            return true;
        }

        if ($user->mahasiswa_id && (int) $application->mahasiswa_id === (int) $user->mahasiswa_id) {
            return true;
        }

        if (!$user->dosen_id) {
            return false;
        }

        $mahasiswaApplicationIds = Application::where('mahasiswa_id', $application->mahasiswa_id)
            ->pluck('id');

        if (ApplicationAssignment::where('lecturer_id', $user->dosen_id)
            ->whereIn('application_id', $mahasiswaApplicationIds)
            ->exists()) {
            return true;
        }

        $resultDefense = ApplicationResultDefense::where('application_id', $application->id)->first();
        if ($resultDefense && ApplicationScore::where('application_result_defence_id', $resultDefense->id)
            ->where('examiner_id', $user->dosen_id)
            ->exists()) {
            return true;
        }

        $defense = SkripsiDefense::where('application_id', $application->id)->first();
        if ($defense && SkripsiDefenseExaminer::where('skripsi_defense_id', $defense->id)
            ->where('dosen_id', $user->dosen_id)
            ->exists()) {
            return true;
        }

        return false;
    }
}
