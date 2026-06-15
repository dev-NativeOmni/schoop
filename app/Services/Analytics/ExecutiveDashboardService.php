<?php

namespace App\Services\Analytics;

use App\Models\School;
use App\Models\SchoolAcademicSnapshot;
use App\Models\SchoolFinanceSnapshot;
use App\Models\SchoolOperationalSnapshot;
use App\Models\SchoolSupportSnapshot;
use App\Models\MobileApiUsageSnapshot;
use App\Models\TenantHealthScore;
use Carbon\Carbon;

class ExecutiveDashboardService
{
    public function stats(): array
    {
        $today = Carbon::today()->toDateString();

        $activeTenants = School::query()->where('is_active', true)->count();
        
        $avgHealthScore = TenantHealthScore::query()
            ->where('score_date', $today)
            ->avg('score') ?: TenantHealthScore::query()->avg('score') ?: 0;

        $riskTenants = TenantHealthScore::query()
            ->where('score_date', $today)
            ->whereIn('status', ['risk', 'critical'])
            ->count();

        $academic = SchoolAcademicSnapshot::query()
            ->where('snapshot_date', $today)
            ->first();

        // Get total outstanding payments
        $outstandingTotal = SchoolFinanceSnapshot::query()
            ->where('snapshot_date', $today)
            ->sum('student_outstanding_total') ?: 0;

        // Active devices
        $activeDevices = MobileApiUsageSnapshot::query()
            ->where('snapshot_date', $today)
            ->sum('active_mobile_devices_count') ?: 0;

        // Support tickets
        $ticketsCount = SchoolSupportSnapshot::query()
            ->where('snapshot_date', $today)
            ->sum('open_tickets_count') ?: 0;

        return [
            'active_tenants' => $activeTenants,
            'average_health_score' => round($avgHealthScore, 1),
            'risk_tenants' => $riskTenants,
            'total_students' => SchoolAcademicSnapshot::query()->where('snapshot_date', $today)->sum('active_students_count') ?: 0,
            'total_teachers' => SchoolAcademicSnapshot::query()->where('snapshot_date', $today)->sum('active_teachers_count') ?: 0,
            'outstanding_bills' => $outstandingTotal,
            'active_mobile_devices' => $activeDevices,
            'open_support_tickets' => $ticketsCount,
        ];
    }

    public function topTenants(): array
    {
        $today = Carbon::today()->toDateString();

        return TenantHealthScore::query()
            ->with('school')
            ->where('score_date', $today)
            ->orderByDesc('score')
            ->limit(5)
            ->get()
            ->toArray();
    }

    public function riskTenantsList(): array
    {
        $today = Carbon::today()->toDateString();

        return TenantHealthScore::query()
            ->with('school')
            ->where('score_date', $today)
            ->whereIn('status', ['risk', 'critical'])
            ->orderBy('score')
            ->limit(5)
            ->get()
            ->toArray();
    }
}
