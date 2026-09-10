<?php

namespace App\Console\Commands;

use App\Models\AiAuditLog;
use Carbon\Carbon;
use Illuminate\Console\Command;

class PruneAiAuditLogsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:ai-prune-audit-logs {--days=730}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prune old AI audit logs based on retention policy';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $days = (int) ($this->option('days') ?? 730);
        $cutOffDate = Carbon::now()->subDays($days);

        $this->info("Pruning AI audit logs older than {$days} days (cutoff: {$cutOffDate->toDateString()})...");

        $deletedCount = AiAuditLog::query()
            ->withoutGlobalScopes()
            ->where('created_at', '<', $cutOffDate)
            ->delete();

        $this->info("Pruned {$deletedCount} old AI audit log records.");

        return 0;
    }
}
