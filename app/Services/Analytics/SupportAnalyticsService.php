<?php

namespace App\Services\Analytics;

use App\Models\SchoolSupportSnapshot;
use Carbon\CarbonInterface;

class SupportAnalyticsService
{
    public function data(?int $schoolId, CarbonInterface $dateFrom, CarbonInterface $dateUntil): array
    {
        $snapshots = SchoolSupportSnapshot::query()
            ->where('school_id', $schoolId)
            ->whereBetween('snapshot_date', [$dateFrom->toDateString(), $dateUntil->toDateString()])
            ->orderBy('snapshot_date')
            ->get();

        return [
            'snapshots' => $snapshots->toArray(),
            'total_open_tickets' => $snapshots->last()?->open_tickets_count ?: 0,
            'total_sla_breaches' => $snapshots->sum('sla_breached_tickets_count'),
            'total_incidents' => $snapshots->sum('incidents_count'),
        ];
    }
}
