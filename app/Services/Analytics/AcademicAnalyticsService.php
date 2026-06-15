<?php

namespace App\Services\Analytics;

use App\Models\SchoolAcademicSnapshot;
use Carbon\CarbonInterface;

class AcademicAnalyticsService
{
    public function data(int $schoolId, CarbonInterface $dateFrom, CarbonInterface $dateUntil): array
    {
        $snapshots = SchoolAcademicSnapshot::query()
            ->where('school_id', $schoolId)
            ->whereBetween('snapshot_date', [$dateFrom->toDateString(), $dateUntil->toDateString()])
            ->orderBy('snapshot_date')
            ->get();

        return [
            'snapshots' => $snapshots->toArray(),
            'avg_tahfizh_rate' => round($snapshots->avg('tahfizh_target_achievement_rate') ?: 0, 2),
            'avg_mutabaah_rate' => round($snapshots->avg('mutabaah_completion_rate') ?: 0, 2),
            'avg_tahsin_score' => round($snapshots->avg('tahsin_average_score') ?: 0, 2),
        ];
    }
}
