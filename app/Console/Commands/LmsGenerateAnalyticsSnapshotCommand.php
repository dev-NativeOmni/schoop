<?php

namespace App\Console\Commands;

use App\Services\Lms\LmsAnalyticsSnapshotService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class LmsGenerateAnalyticsSnapshotCommand extends Command
{
    protected $signature = 'app:lms-generate-analytics-snapshot {date?}';

    protected $description = 'Generate academic snapshots containing LMS metrics for Executive Intelligence';

    public function handle(LmsAnalyticsSnapshotService $snapshotService): int
    {
        $dateStr = $this->argument('date');
        $date = $dateStr ? Carbon::parse($dateStr) : Carbon::today();

        $this->info("Starting LMS analytics snapshot generation for {$date->toDateString()}...");
        $count = $snapshotService->generateAllSnapshots($date);
        $this->info("Snapshot generation complete. Processed {$count} school(s).");

        return self::SUCCESS;
    }
}
