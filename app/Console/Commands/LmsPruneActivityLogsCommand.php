<?php

namespace App\Console\Commands;

use App\Models\LmsActivityLog;
use Illuminate\Console\Command;

class LmsPruneActivityLogsCommand extends Command
{
    protected $signature = 'app:lms-prune-activity-logs {days=90}';

    protected $description = 'Prune LMS activity logs older than specified number of days';

    public function handle(): int
    {
        $days = (int) $this->argument('days');
        $date = now()->subDays($days);

        $this->info("Pruning LMS activity logs older than {$days} days (before {$date->toDateTimeString()})...");

        $deleted = LmsActivityLog::where('created_at', '<', $date)->delete();

        $this->info("Pruning complete. Deleted {$deleted} log record(s).");

        return self::SUCCESS;
    }
}
