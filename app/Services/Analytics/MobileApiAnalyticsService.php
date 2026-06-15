<?php

namespace App\Services\Analytics;

use App\Models\MobileApiUsageSnapshot;
use Carbon\CarbonInterface;

class MobileApiUsageSnapshotModel // placeholder
{}

class MobileApiAnalyticsService
{
    public function data(?int $schoolId, CarbonInterface $dateFrom, CarbonInterface $dateUntil): array
    {
        $snapshots = MobileApiUsageSnapshot::query()
            ->where('school_id', $schoolId)
            ->whereBetween('snapshot_date', [$dateFrom->toDateString(), $dateUntil->toDateString()])
            ->orderBy('snapshot_date')
            ->get();

        return [
            'snapshots' => $snapshots->toArray(),
            'total_api_requests' => $snapshots->sum('api_requests_count'),
            'total_api_errors' => $snapshots->sum('api_error_count'),
            'total_webhook_success' => $snapshots->sum('webhook_success_count'),
            'total_webhook_failed' => $snapshots->sum('webhook_failed_count'),
        ];
    }
}
