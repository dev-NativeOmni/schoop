<?php

namespace App\Console\Commands;

use App\Services\SaasOps\SupportSlaService;
use Illuminate\Console\Command;

class CheckSupportSlaBreachesCommand extends Command
{
    protected $signature = 'app:check-support-sla-breaches';

    protected $description = 'Mark support tickets that breached configured SLA.';

    public function handle(SupportSlaService $sla): int
    {
        $count = $sla->markBreaches();
        $this->info("SLA breaches marked: {$count}");

        return self::SUCCESS;
    }
}
