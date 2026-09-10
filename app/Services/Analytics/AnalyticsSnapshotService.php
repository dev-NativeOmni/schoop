<?php

namespace App\Services\Analytics;

use App\Models\AnalyticsSnapshot;
use App\Models\AttendanceRecord;
use App\Models\HafalanRecord;
use App\Models\MobileApiUsageSnapshot;
use App\Models\MutabaahRecord;
use App\Models\School;
use App\Models\SchoolAcademicSnapshot;
use App\Models\SchoolFinanceSnapshot;
use App\Models\SchoolOperationalSnapshot;
use App\Models\SchoolSupportSnapshot;
use App\Models\Student;
use App\Models\TeacherProfile;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AnalyticsSnapshotService
{
    public function captureDaily(?CarbonInterface $date = null): void
    {
        $date = $date ?: Carbon::today();

        // Capture for each school
        $schools = School::query()->where('is_active', true)->get();
        foreach ($schools as $school) {
            $this->captureForSchool($school->id, $date);
        }

        // Capture global
        $this->captureGlobal($date);
    }

    public function captureForSchool(int $schoolId, CarbonInterface $date): void
    {
        $this->captureAcademic($schoolId, $date);
        $this->captureOperational($schoolId, $date);
        $this->captureFinance($schoolId, $date);
        $this->captureSupport($schoolId, $date);
        $this->captureMobileApi($schoolId, $date);

        // Also compile into the general analytics_snapshots table
        $this->compileToGeneralSnapshots($schoolId, $date);
    }

    public function captureGlobal(CarbonInterface $date): void
    {
        $this->captureSupport(null, $date);
        $this->captureMobileApi(null, $date);

        // General snapshots for system level
        $this->compileGlobalSnapshots($date);
    }

    public function captureAcademic(int $schoolId, CarbonInterface $date): void
    {
        $dateStr = $date->toDateString();

        $activeStudents = Student::query()->where('school_id', $schoolId)->where('is_active', true)->count();
        $activeTeachers = TeacherProfile::query()->where('school_id', $schoolId)->where('is_active', true)->count();

        $hafalanCount = HafalanRecord::query()
            ->where('school_id', $schoolId)
            ->whereDate('created_at', $dateStr)
            ->count();

        $hafalanLines = HafalanRecord::query()
            ->where('school_id', $schoolId)
            ->whereDate('created_at', $dateStr)
            ->sum('total_lines');

        // Target Achievement
        $targetAchievement = 0;
        $behindTargetCount = 0;
        if (Schema::hasTable('tahfizh_targets')) {
            $totalTargetLines = DB::table('tahfizh_targets')
                ->where('school_id', $schoolId)
                ->where('is_active', true)
                ->sum('daily_target_lines');

            if ($totalTargetLines > 0) {
                $actualLinesSum = HafalanRecord::query()
                    ->where('school_id', $schoolId)
                    ->whereDate('created_at', $dateStr)
                    ->sum('total_lines');
                $targetAchievement = min(100.00, round(($actualLinesSum / $totalTargetLines) * 100, 2));
            }

            // Simple estimation of students behind target
            if (Schema::hasTable('tahfizh_debts')) {
                $behindTargetCount = DB::table('tahfizh_debts')
                    ->where('school_id', $schoolId)
                    ->where('debt_lines', '>', 0)
                    ->distinct('student_id')
                    ->count();
            }
        }

        // Mutabaah
        $mutabaahCount = MutabaahRecord::query()
            ->where('school_id', $schoolId)
            ->whereDate('record_date', $dateStr)
            ->count();

        $mutabaahRate = 0;
        if ($activeStudents > 0) {
            $mutabaahRate = min(100.00, round(($mutabaahCount / $activeStudents) * 100, 2));
        }

        // Tahsin
        $tahsinCount = 0;
        $tahsinAvg = 0;
        if (Schema::hasTable('tahsin_assessments')) {
            $tahsinCount = DB::table('tahsin_assessments')
                ->where('school_id', $schoolId)
                ->whereDate('assessment_date', $dateStr)
                ->count();

            if (Schema::hasTable('tahsin_assessment_items')) {
                $tahsinAvg = DB::table('tahsin_assessment_items')
                    ->join('tahsin_assessments', 'tahsin_assessment_items.tahsin_assessment_id', '=', 'tahsin_assessments.id')
                    ->where('tahsin_assessments.school_id', $schoolId)
                    ->whereDate('tahsin_assessments.assessment_date', $dateStr)
                    ->avg('tahsin_assessment_items.score') ?: 0;
            }
        }

        SchoolAcademicSnapshot::query()->updateOrCreate(
            [
                'school_id' => $schoolId,
                'snapshot_date' => $dateStr,
                'period_type' => 'daily',
            ],
            [
                'active_students_count' => $activeStudents,
                'active_teachers_count' => $activeTeachers,
                'hafalan_records_count' => $hafalanCount,
                'hafalan_total_lines' => $hafalanLines,
                'tahfizh_target_achievement_rate' => $targetAchievement,
                'students_behind_target_count' => $behindTargetCount,
                'mutabaah_records_count' => $mutabaahCount,
                'mutabaah_completion_rate' => $mutabaahRate,
                'tahsin_assessments_count' => $tahsinCount,
                'tahsin_average_score' => $tahsinAvg,
                'raw_metrics' => [
                    'timestamp' => now()->toIso8601String(),
                ],
            ]
        );
    }

    public function captureOperational(int $schoolId, CarbonInterface $date): void
    {
        $dateStr = $date->toDateString();

        $attRecords = AttendanceRecord::query()
            ->where('school_id', $schoolId)
            ->whereDate('attendance_date', $dateStr)
            ->get();

        $totalAtt = $attRecords->count();
        $present = $attRecords->where('status', 'present')->count();
        $late = $attRecords->where('status', 'late')->count();
        $absent = $attRecords->where('status', 'absent')->count();
        $sick = $attRecords->where('status', 'sick')->count();
        $permission = $attRecords->where('status', 'permission')->count();

        $attRate = 0;
        $lateRate = 0;
        if ($totalAtt > 0) {
            $attRate = min(100.00, round((($present + $late) / $totalAtt) * 100, 2));
            $lateRate = min(100.00, round(($late / $totalAtt) * 100, 2));
        }

        // Boarding Modules
        $rollCallCount = 0;
        $leaveCount = 0;
        $healthCount = 0;
        $disciplineCount = 0;

        if (Schema::hasTable('boarding_roll_call_records')) {
            $rollCallCount = DB::table('boarding_roll_call_records')
                ->where('school_id', $schoolId)
                ->whereDate('created_at', $dateStr)
                ->count();
        }

        if (Schema::hasTable('boarding_leave_requests')) {
            $leaveCount = DB::table('boarding_leave_requests')
                ->where('school_id', $schoolId)
                ->whereDate('created_at', $dateStr)
                ->count();
        }

        if (Schema::hasTable('boarding_health_logs')) {
            $healthCount = DB::table('boarding_health_logs')
                ->where('school_id', $schoolId)
                ->whereDate('created_at', $dateStr)
                ->count();
        }

        if (Schema::hasTable('boarding_discipline_logs')) {
            $disciplineCount = DB::table('boarding_discipline_logs')
                ->where('school_id', $schoolId)
                ->whereDate('created_at', $dateStr)
                ->count();
        }

        SchoolOperationalSnapshot::query()->updateOrCreate(
            [
                'school_id' => $schoolId,
                'snapshot_date' => $dateStr,
                'period_type' => 'daily',
            ],
            [
                'attendance_records_count' => $totalAtt,
                'present_count' => $present,
                'late_count' => $late,
                'absent_count' => $absent,
                'sick_count' => $sick,
                'permission_count' => $permission,
                'attendance_rate' => $attRate,
                'late_rate' => $lateRate,
                'boarding_roll_call_records_count' => $rollCallCount,
                'boarding_leave_requests_count' => $leaveCount,
                'boarding_health_logs_count' => $healthCount,
                'boarding_discipline_logs_count' => $disciplineCount,
                'raw_metrics' => [
                    'timestamp' => now()->toIso8601String(),
                ],
            ]
        );
    }

    public function captureFinance(int $schoolId, CarbonInterface $date): void
    {
        $dateStr = $date->toDateString();

        $billsTotal = 0;
        $paymentsTotal = 0;
        $outstandingTotal = 0;
        $overdueCount = 0;
        $voidBills = 0;
        $voidPayments = 0;

        if (Schema::hasTable('student_bills')) {
            $billsTotal = DB::table('student_bills')
                ->where('school_id', $schoolId)
                ->where('status', '!=', 'void')
                ->whereDate('created_at', $dateStr)
                ->sum('total_amount') ?: 0;

            $outstandingTotal = DB::table('student_bills')
                ->where('school_id', $schoolId)
                ->where('status', '!=', 'void')
                ->sum('outstanding_amount') ?: 0;

            $overdueCount = DB::table('student_bills')
                ->where('school_id', $schoolId)
                ->where('status', 'unpaid')
                ->whereDate('due_date', '<', $dateStr)
                ->count();

            $voidBills = DB::table('student_bills')
                ->where('school_id', $schoolId)
                ->where('status', 'void')
                ->whereDate('updated_at', $dateStr)
                ->count();
        }

        if (Schema::hasTable('student_payments')) {
            $paymentsTotal = DB::table('student_payments')
                ->where('school_id', $schoolId)
                ->where('status', '!=', 'void')
                ->whereDate('payment_date', $dateStr)
                ->sum('amount') ?: 0;

            $voidPayments = DB::table('student_payments')
                ->where('school_id', $schoolId)
                ->where('status', 'void')
                ->whereDate('updated_at', $dateStr)
                ->count();
        }

        // Cashless POS
        $cashlessTopup = 0;
        $cashlessPurchase = 0;
        $cashlessRefund = 0;
        $cashlessVoid = 0;
        $cashlessNegative = 0;
        $cashlessPending = 0;

        if (Schema::hasTable('wallet_transactions')) {
            $cashlessTopup = DB::table('wallet_transactions')
                ->where('school_id', $schoolId)
                ->where('type', 'topup')
                ->where('status', 'success')
                ->whereDate('created_at', $dateStr)
                ->sum('amount') ?: 0;

            $cashlessPurchase = DB::table('wallet_transactions')
                ->where('school_id', $schoolId)
                ->where('type', 'purchase')
                ->where('status', 'success')
                ->whereDate('created_at', $dateStr)
                ->sum('amount') ?: 0;

            $cashlessRefund = DB::table('wallet_transactions')
                ->where('school_id', $schoolId)
                ->where('type', 'refund')
                ->where('status', 'success')
                ->whereDate('created_at', $dateStr)
                ->sum('amount') ?: 0;

            $cashlessVoid = DB::table('wallet_transactions')
                ->where('school_id', $schoolId)
                ->where('status', 'void')
                ->whereDate('updated_at', $dateStr)
                ->count();
        }

        if (Schema::hasTable('student_wallets')) {
            $cashlessNegative = DB::table('student_wallets')
                ->where('school_id', $schoolId)
                ->where('balance', '<', 0)
                ->count();
        }

        if (Schema::hasTable('merchant_settlements')) {
            $cashlessPending = DB::table('merchant_settlements')
                ->where('school_id', $schoolId)
                ->where('status', 'pending')
                ->count();
        }

        SchoolFinanceSnapshot::query()->updateOrCreate(
            [
                'school_id' => $schoolId,
                'snapshot_date' => $dateStr,
                'period_type' => 'daily',
            ],
            [
                'student_bills_total' => $billsTotal,
                'student_payments_total' => $paymentsTotal,
                'student_outstanding_total' => $outstandingTotal,
                'overdue_bills_count' => $overdueCount,
                'void_bills_count' => $voidBills,
                'void_payments_count' => $voidPayments,
                'cashless_topup_total' => $cashlessTopup,
                'cashless_purchase_total' => $cashlessPurchase,
                'cashless_refund_total' => $cashlessRefund,
                'cashless_void_count' => $cashlessVoid,
                'cashless_negative_balance_anomaly_count' => $cashlessNegative,
                'cashless_pending_settlement_count' => $cashlessPending,
                'raw_metrics' => [
                    'timestamp' => now()->toIso8601String(),
                ],
            ]
        );
    }

    public function captureSupport(?int $schoolId, CarbonInterface $date): void
    {
        $dateStr = $date->toDateString();

        $openCount = 0;
        $critical = 0;
        $high = 0;
        $medium = 0;
        $low = 0;
        $slaBreached = 0;
        $incidents = 0;
        $sev1 = 0;
        $sev2 = 0;
        $avgResponse = 0;
        $avgResolution = 0;

        if (Schema::hasTable('support_tickets')) {
            $query = DB::table('support_tickets');
            if ($schoolId) {
                $query->where('school_id', $schoolId);
            }
            $openCount = (clone $query)->whereIn('status', ['open', 'in_progress'])->count();
            $critical = (clone $query)->where('priority', 'critical')->whereIn('status', ['open', 'in_progress'])->count();
            $high = (clone $query)->where('priority', 'high')->whereIn('status', ['open', 'in_progress'])->count();
            $medium = (clone $query)->where('priority', 'medium')->whereIn('status', ['open', 'in_progress'])->count();
            $low = (clone $query)->where('priority', 'low')->whereIn('status', ['open', 'in_progress'])->count();

            // SLA breach and average response calculations if columns exist
            // Simple fallback if SLA check field doesn't exist
            $slaBreached = (clone $query)->where('sla_breached', true)->count();
        }

        if (Schema::hasTable('incident_reports')) {
            $queryInc = DB::table('incident_reports');
            if ($schoolId) {
                $queryInc->where('school_id', $schoolId);
            }
            $incidents = (clone $queryInc)->whereDate('detected_at', $dateStr)->count();
            $sev1 = (clone $queryInc)->where('severity', 'sev1')->whereDate('detected_at', $dateStr)->count();
            $sev2 = (clone $queryInc)->where('severity', 'sev2')->whereDate('detected_at', $dateStr)->count();
        }

        SchoolSupportSnapshot::query()->updateOrCreate(
            [
                'school_id' => $schoolId,
                'snapshot_date' => $dateStr,
                'period_type' => 'daily',
            ],
            [
                'open_tickets_count' => $openCount,
                'critical_tickets_count' => $critical,
                'high_tickets_count' => $high,
                'medium_tickets_count' => $medium,
                'low_tickets_count' => $low,
                'sla_breached_tickets_count' => $slaBreached,
                'incidents_count' => $incidents,
                'sev1_incidents_count' => $sev1,
                'sev2_incidents_count' => $sev2,
                'average_first_response_minutes' => $avgResponse,
                'average_resolution_minutes' => $avgResolution,
                'raw_metrics' => [
                    'timestamp' => now()->toIso8601String(),
                ],
            ]
        );
    }

    public function captureMobileApi(?int $schoolId, CarbonInterface $date): void
    {
        $dateStr = $date->toDateString();

        $devicesCount = 0;
        $activeDevices = 0;
        $androidCount = 0;
        $iosCount = 0;
        $forceUpdateCount = 0;
        $apiRequests = 0;
        $apiErrors = 0;
        $rateLimitHits = 0;
        $failedAuth = 0;
        $webhookSuccess = 0;
        $webhookFailed = 0;

        if (Schema::hasTable('mobile_devices')) {
            $query = DB::table('mobile_devices');
            if ($schoolId) {
                $query->where('school_id', $schoolId);
            }
            $devicesCount = (clone $query)->count();
            $androidCount = (clone $query)->where('platform', 'android')->count();
            $iosCount = (clone $query)->where('platform', 'ios')->count();
        }

        if (Schema::hasTable('mobile_api_audit_logs')) {
            $query = DB::table('mobile_api_audit_logs')->whereDate('created_at', $dateStr);
            if ($schoolId) {
                $query->where('school_id', $schoolId);
            }
            $activeDevices = (clone $query)->distinct('mobile_device_id')->count();
            $apiRequests = (clone $query)->count();
            $apiErrors = (clone $query)->where('status_code', '>=', 400)->count();
        }

        if (Schema::hasTable('api_request_logs')) {
            $query = DB::table('api_request_logs')->whereDate('created_at', $dateStr);
            if ($schoolId) {
                $query->where('school_id', $schoolId);
            }
            // Add external API logs into aggregate count
            $apiRequests += (clone $query)->count();
            $apiErrors += (clone $query)->where('response_status', '>=', 400)->count();
            $rateLimitHits = (clone $query)->where('response_status', 429)->count();
            $failedAuth = (clone $query)->where('response_status', 412)->count();
        }

        if (Schema::hasTable('webhook_deliveries')) {
            $query = DB::table('webhook_deliveries')->whereDate('created_at', $dateStr);
            if ($schoolId) {
                $query->where('school_id', $schoolId);
            }
            $webhookSuccess = (clone $query)->where('status', 'delivered')->count();
            $webhookFailed = (clone $query)->where('status', 'failed')->count();
        }

        MobileApiUsageSnapshot::query()->updateOrCreate(
            [
                'school_id' => $schoolId,
                'snapshot_date' => $dateStr,
                'period_type' => 'daily',
            ],
            [
                'mobile_devices_count' => $devicesCount,
                'active_mobile_devices_count' => $activeDevices,
                'android_devices_count' => $androidCount,
                'ios_devices_count' => $iosCount,
                'force_update_devices_count' => $forceUpdateCount,
                'api_requests_count' => $apiRequests,
                'api_error_count' => $apiErrors,
                'api_rate_limit_hits_count' => $rateLimitHits,
                'api_failed_auth_count' => $failedAuth,
                'webhook_success_count' => $webhookSuccess,
                'webhook_failed_count' => $webhookFailed,
                'raw_metrics' => [
                    'timestamp' => now()->toIso8601String(),
                ],
            ]
        );
    }

    private function compileToGeneralSnapshots(int $schoolId, CarbonInterface $date): void
    {
        $dateStr = $date->toDateString();

        $academic = SchoolAcademicSnapshot::query()
            ->where('school_id', $schoolId)
            ->where('snapshot_date', $dateStr)
            ->first();

        $operational = SchoolOperationalSnapshot::query()
            ->where('school_id', $schoolId)
            ->where('snapshot_date', $dateStr)
            ->first();

        $finance = SchoolFinanceSnapshot::query()
            ->where('school_id', $schoolId)
            ->where('snapshot_date', $dateStr)
            ->first();

        $support = SchoolSupportSnapshot::query()
            ->where('school_id', $schoolId)
            ->where('snapshot_date', $dateStr)
            ->first();

        $mobile = MobileApiUsageSnapshot::query()
            ->where('school_id', $schoolId)
            ->where('snapshot_date', $dateStr)
            ->first();

        $metrics = [
            'usage.active_users' => $academic?->active_students_count ?: 0,
            'academic.hafalan_records' => $academic?->hafalan_records_count ?: 0,
            'academic.tahfizh_achievement_rate' => $academic?->tahfizh_target_achievement_rate ?: 0,
            'academic.students_behind_target' => $academic?->students_behind_target_count ?: 0,
            'mutabaah.completion_rate' => $academic?->mutabaah_completion_rate ?: 0,
            'attendance.attendance_rate' => $operational?->attendance_rate ?: 0,
            'attendance.late_rate' => $operational?->late_rate ?: 0,
            'tahsin.average_score' => $academic?->tahsin_average_score ?: 0,
            'finance.outstanding_total' => $finance?->student_outstanding_total ?: 0,
            'cashless.purchase_total' => $finance?->cashless_purchase_total ?: 0,
            'support.sla_breach_rate' => $support && $support->open_tickets_count > 0 ? round(($support->sla_breached_tickets_count / $support->open_tickets_count) * 100, 2) : 0,
            'mobile.active_devices' => $mobile?->active_mobile_devices_count ?: 0,
            'api.error_rate' => $mobile && $mobile->api_requests_count > 0 ? round(($mobile->api_error_count / $mobile->api_requests_count) * 100, 2) : 0,
        ];

        foreach ($metrics as $key => $val) {
            AnalyticsSnapshot::query()->updateOrCreate(
                [
                    'school_id' => $schoolId,
                    'snapshot_date' => $dateStr,
                    'period_type' => 'daily',
                    'scope' => 'school',
                    'metric_key' => $key,
                ],
                [
                    'metric_value' => $val,
                ]
            );
        }
    }

    private function compileGlobalSnapshots(CarbonInterface $date): void
    {
        $dateStr = $date->toDateString();

        $activeTenants = School::query()->where('is_active', true)->count();

        // Active users across all schools
        $totalActiveUsers = SchoolAcademicSnapshot::query()
            ->where('snapshot_date', $dateStr)
            ->sum('active_students_count') ?: 0;

        // General snapshots for system level
        $metrics = [
            'tenant.active_count' => $activeTenants,
            'usage.active_users' => $totalActiveUsers,
        ];

        foreach ($metrics as $key => $val) {
            AnalyticsSnapshot::query()->updateOrCreate(
                [
                    'school_id' => null,
                    'snapshot_date' => $dateStr,
                    'period_type' => 'daily',
                    'scope' => 'global',
                    'metric_key' => $key,
                ],
                [
                    'metric_value' => $val,
                ]
            );
        }
    }
}
