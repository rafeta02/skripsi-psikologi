<?php

namespace App\Console\Commands;

use App\Services\ReviewerAssignmentService;
use Illuminate\Console\Command;

class CheckReviewerDeadlines extends Command
{
    protected $signature = 'thesis:check-reviewer-deadlines';

    protected $description = 'Expire reviewer assignments without response and create admin alerts for overdue feedback';

    public function handle(ReviewerAssignmentService $service): int
    {
        $stats = $service->processDeadlines();

        $this->info(sprintf(
            'Done. Expired: %d, warnings: %d, overdue: %d',
            $stats['expired'],
            $stats['warnings'],
            $stats['overdue']
        ));

        return self::SUCCESS;
    }
}
