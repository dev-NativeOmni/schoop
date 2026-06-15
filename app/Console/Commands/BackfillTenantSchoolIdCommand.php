<?php

namespace App\Console\Commands;

use App\Services\Tenancy\TenantDataBackfillService;
use Illuminate\Console\Command;

class BackfillTenantSchoolIdCommand extends Command
{
    protected $signature = 'app:backfill-tenant-school-id {--dry-run}';

    protected $description = 'Backfill school_id for tenant-owned records.';

    public function handle(TenantDataBackfillService $service): int
    {
        $result = $service->backfill((bool) $this->option('dry-run'));

        foreach ($result as $table => $count) {
            $this->line("{$table}: {$count} rows processed");
        }

        return self::SUCCESS;
    }
}
