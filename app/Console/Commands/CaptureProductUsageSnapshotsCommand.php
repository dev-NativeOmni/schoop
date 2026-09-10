<?php

namespace App\Console\Commands;

use App\Models\School;
use App\Services\SaasOps\UsageSnapshotService;
use Illuminate\Console\Command;

class CaptureProductUsageSnapshotsCommand extends Command
{
    protected $signature = 'app:capture-product-usage-snapshots {--school_id=}';

    protected $description = 'Capture aggregate product usage snapshots per tenant.';

    public function handle(UsageSnapshotService $snapshots): int
    {
        $count = 0;
        School::query()
            ->where('is_active', true)
            ->when($this->option('school_id'), fn ($query, $schoolId) => $query->where('id', $schoolId))
            ->chunkById(50, function ($schools) use ($snapshots, &$count): void {
                foreach ($schools as $school) {
                    $snapshots->captureForSchool($school);
                    $count++;
                }
            });

        $this->info("Captured usage snapshots: {$count}");

        return self::SUCCESS;
    }
}
