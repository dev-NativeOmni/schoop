<?php

namespace App\Console\Commands;

use App\Services\Lms\LmsProgressService;
use Illuminate\Console\Command;

class LmsRecalculateProgressCommand extends Command
{
    protected $signature = 'app:lms-recalculate-progress';

    protected $description = 'Recalculate progress percentage for all student enrollments in LMS';

    public function handle(LmsProgressService $progressService): int
    {
        $this->info('Starting LMS progress recalculation...');
        $count = $progressService->recalculateAllProgress();
        $this->info("Recalculation complete. Processed {$count} enrollment(s).");

        return self::SUCCESS;
    }
}
