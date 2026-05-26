<?php

namespace App\Policies;

use App\Models\ApplicationAssignment;
use App\Models\User;

class ApplicationAssignmentPolicy
{
    public function view(User $user, ApplicationAssignment $assignment): bool
    {
        if ($user->is_admin) {
            return true;
        }

        if ($user->dosen_id && (int) $assignment->lecturer_id === (int) $user->dosen_id) {
            return true;
        }

        if ($user->mahasiswa_id && $assignment->application
            && (int) $assignment->application->mahasiswa_id === (int) $user->mahasiswa_id) {
            return true;
        }

        return false;
    }
}
