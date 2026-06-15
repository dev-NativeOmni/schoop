<?php

namespace App\Console\Commands;

use App\Services\Analytics\AnalyticsSnapshotService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CaptureAnalyticsSnapshotsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:capture-analytics-snapshots {--date=} {--school_id=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Capture daily analytics snapshots for schools and global.';

    /**
     * Execute the console command.
     */
    public function handle(AnalyticsSnapshotService $service): int
    {
        $dateStr = $this->option('date');
        $date = $dateStr ? Carbon::parse($dateStr) : Carbon::today();
        $schoolId = $this->option('school_id');

        if ($schoolId) {
            $this->info("Capturing snapshots for school ID: {$schoolId} on date: {$date->toDateString()}");
            $service->captureForSchool((int) $schoolId, $date);
        } else {
            $this->info("Capturing daily snapshots for all active schools on date: {$date->toDateString()}");
            $service->captureDaily($date);
        }

        $this->info('Daily snapshots successfully captured!');

        return self::SUCCESS;
    }
}
