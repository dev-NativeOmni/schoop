<?php

namespace App\Services\Analytics;

use App\Models\SchoolAcademicSnapshot;
use App\Models\SchoolFinanceSnapshot;
use App\Models\SchoolOperationalSnapshot;
use App\Models\TenantHealthScore;
use Carbon\Carbon;

class SchoolAnalyticsDashboardService
{
    public function stats(int $schoolId): array
    {
        $today = Carbon::today()->toDateString();

        $academic = SchoolAcademicSnapshot::query()
            ->where('school_id', $schoolId)
            ->where('snapshot_date', $today)
            ->first();

        $operational = SchoolOperationalSnapshot::query()
            ->where('school_id', $schoolId)
            ->where('snapshot_date', $today)
            ->first();

        $finance = SchoolFinanceSnapshot::query()
            ->where('school_id', $schoolId)
            ->where('snapshot_date', $today)
            ->first();

        $health = TenantHealthScore::query()
            ->where('school_id', $schoolId)
            ->where('score_date', $today)
            ->first();

        return [
            'active_students' => $academic?->active_students_count ?: 0,
            'active_teachers' => $academic?->active_teachers_count ?: 0,
            'tahfizh_achievement' => $academic?->tahfizh_target_achievement_rate ?: 0,
            'students_behind_target' => $academic?->students_behind_target_count ?: 0,
            'mutabaah_completion' => $academic?->mutabaah_completion_rate ?: 0,
            'attendance_rate' => $operational?->attendance_rate ?: 0,
            'late_rate' => $operational?->late_rate ?: 0,
            'tahsin_score' => $academic?->tahsin_average_score ?: 0,
            'outstanding_finance' => $finance?->student_outstanding_total ?: 0,
            'cashless_purchases' => $finance?->cashless_purchase_total ?: 0,
            'health_score' => $health?->score ?: 0,
            'health_status' => $health?->status ?: 'unknown',
            'health_recommendations' => $health?->recommendations ?: ['Belum ada rekomendasi.'],
        ];
    }

    public function academicTrend(int $schoolId, int $limit = 30): array
    {
        return SchoolAcademicSnapshot::query()
            ->where('school_id', $schoolId)
            ->orderByDesc('snapshot_date')
            ->limit($limit)
            ->get()
            ->reverse()
            ->values()
            ->toArray();
    }

    public function operationalTrend(int $schoolId, int $limit = 30): array
    {
        return SchoolOperationalSnapshot::query()
            ->where('school_id', $schoolId)
            ->orderByDesc('snapshot_date')
            ->limit($limit)
            ->get()
            ->reverse()
            ->values()
            ->toArray();
    }

    public function financeTrend(int $schoolId, int $limit = 30): array
    {
        return SchoolFinanceSnapshot::query()
            ->where('school_id', $schoolId)
            ->orderByDesc('snapshot_date')
            ->limit($limit)
            ->get()
            ->reverse()
            ->values()
            ->toArray();
    }
}
