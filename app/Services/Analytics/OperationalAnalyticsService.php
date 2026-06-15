<?php

namespace App\Services\Analytics;

use App\Models\SchoolOperationalSnapshot;
use Carbon\CarbonInterface;

class OperationalAnalyticsService
{
    public function data(int $schoolId, CarbonInterface $dateFrom, CarbonInterface $dateUntil): array
    {
        $snapshots = SchoolOperationalSnapshot::query()
            ->where('school_id', $schoolId)
            ->where('snapshot_date', [$dateFrom->toDateString(), $dateUntil->toDateString()])
            ->orderBy('snapshot_date')
            ->get();

        return [
            'snapshots' => $snapshots->toArray(),
            'avg_attendance_rate' => round($snapshots->avg('attendance_rate') ?: 0, 2),
            'avg_late_rate' => round($snapshots->avg('late_rate') ?: 0, 2),
            'total_boarding_roll_calls' => $snapshots->sum('boarding_roll_call_records_count'),
            'total_boarding_leave_requests' => $snapshots->sum('boarding_leave_requests_count'),
        ];
    }
}
