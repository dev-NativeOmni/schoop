<?php

namespace App\Console\Commands;

use App\Models\ApiRequestLog;
use Illuminate\Console\Command;

class PruneApiRequestLogsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:prune-api-request-logs {--days=90 : Keep logs newer than this number of days}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prune old external API request logs.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $days = max(1, (int) $this->option('days'));
        $deleted = ApiRequestLog::query()
            ->where('created_at', '<', now()->subDays($days))
            ->delete();

        $this->info("Pruned {$deleted} API request logs older than {$days} days.");

        return self::SUCCESS;
    }
}
