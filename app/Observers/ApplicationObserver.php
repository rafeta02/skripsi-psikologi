<?php

namespace App\Observers;

use App\Models\Application;
use App\Services\MbkmGroupProgressService;

class ApplicationObserver
{
    public function created(Application $application): void
    {
        if ($application->is_group_mirror || $application->type !== 'mbkm') {
            return;
        }

        app(MbkmGroupProgressService::class)->ensureMirrorsForNewOwnerApplication($application);
    }

    public function updated(Application $application): void
    {
        if ($application->is_group_mirror || $application->type !== 'mbkm') {
            return;
        }

        if ($application->wasChanged(['status', 'stage'])) {
            app(MbkmGroupProgressService::class)->syncMirrorsFromOwner($application);
        }
    }
}
