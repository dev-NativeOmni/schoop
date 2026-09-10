<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Services\Analytics\AnalyticsSnapshotService;
use App\Models\School;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

DB::enableQueryLog();
DB::beginTransaction();

// Create 50 fake schools to simulate load
for ($i = 0; $i < 50; $i++) {
    School::insert([
        'code' => 'SCH' . $i,
        'name' => 'School ' . $i,
        'is_active' => true,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}

$service = new AnalyticsSnapshotService();

$start = microtime(true);
$service->captureDaily(Carbon::today());
$end = microtime(true);

echo "Execution time: " . ($end - $start) . " seconds\n";
echo "DB query count: " . count(DB::getQueryLog()) . "\n";

DB::rollBack();
