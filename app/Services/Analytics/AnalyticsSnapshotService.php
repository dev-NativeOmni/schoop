<?php

namespace App\Services\Analytics;

use App\Models\AcademicYear;
use App\Models\AnalyticsSnapshot;
use App\Models\AttendanceRecord;
use App\Models\HafalanRecord;
use App\Models\MutabaahRecord;
use App\Models\School;
use App\Models\SchoolAcademicSnapshot;
use App\Models\SchoolFinanceSnapshot;
use App\Models\SchoolOperationalSnapshot;
use App\Models\SchoolSupportSnapshot;
use App\Models\MobileApiUsageSnapshot;
use App\Models\Student;
use App\Models\TeacherProfile;
use App\Models\User;
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
        $schoolIds = School::query()->where('is_active', true)->pluck('id')->toArray();
        if (!empty($schoolIds)) {
            $this->captureForSchools($schoolIds, $date);
        }

        // Capture global
        $this->captureGlobal($date);
    }

    public function captureForSchool(int $schoolId, CarbonInterface $date): void
    {
        $this->captureForSchools([$schoolId], $date);
    }

    public function captureForSchools(array $schoolIds, CarbonInterface $date): void
    {
        $this->captureAcademic($schoolIds, $date);
        $this->captureOperational($schoolIds, $date);
        $this->captureFinance($schoolIds, $date);
        $this->captureSupport($schoolIds, $date);
        $this->captureMobileApi($schoolIds, $date);

        // Also compile into the general analytics_snapshots table
        $this->compileToGeneralSnapshots($schoolIds, $date);
    }

    public function captureGlobal(CarbonInterface $date): void
    {
        $this->captureSupport(null, $date);
        $this->captureMobileApi(null, $date);

        // General snapshots for system level
        $this->compileGlobalSnapshots($date);
    }

    /**
     * @param int|array $schoolIds
     */
    public function captureAcademic($schoolIds, CarbonInterface $date): void
    {
        $schoolIds = (array) $schoolIds;
        if (empty($schoolIds)) return;

        $dateStr = $date->toDateString();

        $activeStudents = Student::query()->whereIn('school_id', $schoolIds)->where('is_active', true)
            ->groupBy('school_id')->selectRaw('school_id, count(*) as count')->pluck('count', 'school_id');
        $activeTeachers = TeacherProfile::query()->whereIn('school_id', $schoolIds)->where('is_active', true)
            ->groupBy('school_id')->selectRaw('school_id, count(*) as count')->pluck('count', 'school_id');

        $hafalanCount = HafalanRecord::query()
            ->whereIn('school_id', $schoolIds)
            ->whereDate('created_at', $dateStr)
            ->groupBy('school_id')->selectRaw('school_id, count(*) as count')->pluck('count', 'school_id');

        $hafalanLines = HafalanRecord::query()
            ->whereIn('school_id', $schoolIds)
            ->whereDate('created_at', $dateStr)
            ->groupBy('school_id')->selectRaw('school_id, sum(total_lines) as total')->pluck('total', 'school_id');

        // Target Achievement
        $totalTargetLines = collect();
        $actualLinesSum = collect();
        $behindTargetCount = collect();
        if (Schema::hasTable('tahfizh_targets')) {
            $totalTargetLines = DB::table('tahfizh_targets')
                ->whereIn('school_id', $schoolIds)
                ->where('is_active', true)
                ->groupBy('school_id')->selectRaw('school_id, sum(daily_target_lines) as total')->pluck('total', 'school_id');
            
            $actualLinesSum = HafalanRecord::query()
                ->whereIn('school_id', $schoolIds)
                ->whereDate('created_at', $dateStr)
                ->groupBy('school_id')->selectRaw('school_id, sum(total_lines) as total')->pluck('total', 'school_id');

            // Simple estimation of students behind target
            if (Schema::hasTable('tahfizh_debts')) {
                $behindTargetCount = DB::table('tahfizh_debts')
                    ->whereIn('school_id', $schoolIds)
                    ->where('debt_lines', '>', 0)
                    ->groupBy('school_id')->selectRaw('school_id, count(distinct student_id) as count')->pluck('count', 'school_id');
            }
        }

        // Mutabaah
        $mutabaahCount = MutabaahRecord::query()
            ->whereIn('school_id', $schoolIds)
            ->whereDate('record_date', $dateStr)
            ->groupBy('school_id')->selectRaw('school_id, count(*) as count')->pluck('count', 'school_id');

        // Tahsin
        $tahsinCount = collect();
        $tahsinAvg = collect();
        if (Schema::hasTable('tahsin_assessments')) {
            $tahsinCount = DB::table('tahsin_assessments')
                ->whereIn('school_id', $schoolIds)
                ->whereDate('assessment_date', $dateStr)
                ->groupBy('school_id')->selectRaw('school_id, count(*) as count')->pluck('count', 'school_id');
            
            if (Schema::hasTable('tahsin_assessment_items')) {
                $tahsinAvg = DB::table('tahsin_assessment_items')
                    ->join('tahsin_assessments', 'tahsin_assessment_items.tahsin_assessment_id', '=', 'tahsin_assessments.id')
                    ->whereIn('tahsin_assessments.school_id', $schoolIds)
                    ->whereDate('tahsin_assessments.assessment_date', $dateStr)
                    ->groupBy('tahsin_assessments.school_id')
                    ->selectRaw('tahsin_assessments.school_id, avg(tahsin_assessment_items.score) as average')
                    ->pluck('average', 'school_id');
            }
        }

        foreach ($schoolIds as $schoolId) {
            $sActiveStudents = $activeStudents->get($schoolId) ?? 0;
            $sActiveTeachers = $activeTeachers->get($schoolId) ?? 0;
            $sHafalanCount = $hafalanCount->get($schoolId) ?? 0;
            $sHafalanLines = $hafalanLines->get($schoolId) ?? 0;

            $sTotalTargetLines = $totalTargetLines->get($schoolId) ?? 0;
            $sActualLinesSum = $actualLinesSum->get($schoolId) ?? 0;
            $sBehindTargetCount = $behindTargetCount->get($schoolId) ?? 0;

            $sTargetAchievement = 0;
            if ($sTotalTargetLines > 0) {
                $sTargetAchievement = min(100.00, round(($sActualLinesSum / $sTotalTargetLines) * 100, 2));
            }

            $sMutabaahCount = $mutabaahCount->get($schoolId) ?? 0;
            $sMutabaahRate = 0;
            if ($sActiveStudents > 0) {
                $sMutabaahRate = min(100.00, round(($sMutabaahCount / $sActiveStudents) * 100, 2));
            }

            $sTahsinCount = $tahsinCount->get($schoolId) ?? 0;
            $sTahsinAvg = $tahsinAvg->get($schoolId) ?? 0;

            SchoolAcademicSnapshot::query()->updateOrCreate(
                [
                    'school_id' => $schoolId,
                    'snapshot_date' => $dateStr,
                    'period_type' => 'daily',
                ],
                [
                    'active_students_count' => $sActiveStudents,
                    'active_teachers_count' => $sActiveTeachers,
                    'hafalan_records_count' => $sHafalanCount,
                    'hafalan_total_lines' => $sHafalanLines,
                    'tahfizh_target_achievement_rate' => $sTargetAchievement,
                    'students_behind_target_count' => $sBehindTargetCount,
                    'mutabaah_records_count' => $sMutabaahCount,
                    'mutabaah_completion_rate' => $sMutabaahRate,
                    'tahsin_assessments_count' => $sTahsinCount,
                    'tahsin_average_score' => $sTahsinAvg,
                    'raw_metrics' => [
                        'timestamp' => now()->toIso8601String(),
                    ],
                ]
            );
        }
    }

    /**
     * @param int|array $schoolIds
     */
    public function captureOperational($schoolIds, CarbonInterface $date): void
    {
        $schoolIds = (array) $schoolIds;
        if (empty($schoolIds)) return;

        $dateStr = $date->toDateString();

        $attRecords = collect();
        if (Schema::hasTable('attendance_records')) {
            $attRecords = DB::table('attendance_records')
                ->whereIn('school_id', $schoolIds)
                ->whereDate('attendance_date', $dateStr)
                ->groupBy('school_id', 'status')
                ->selectRaw('school_id, status, count(*) as count')
                ->get()
                ->groupBy('school_id');
        }

        // Boarding Modules
        $rollCallCount = collect();
        $leaveCount = collect();
        $healthCount = collect();
        $disciplineCount = collect();

        if (Schema::hasTable('boarding_roll_call_records')) {
            $rollCallCount = DB::table('boarding_roll_call_records')
                ->whereIn('school_id', $schoolIds)
                ->whereDate('created_at', $dateStr)
                ->groupBy('school_id')->selectRaw('school_id, count(*) as count')->pluck('count', 'school_id');
        }

        if (Schema::hasTable('boarding_leave_requests')) {
            $leaveCount = DB::table('boarding_leave_requests')
                ->whereIn('school_id', $schoolIds)
                ->whereDate('created_at', $dateStr)
                ->groupBy('school_id')->selectRaw('school_id, count(*) as count')->pluck('count', 'school_id');
        }

        if (Schema::hasTable('boarding_health_logs')) {
            $healthCount = DB::table('boarding_health_logs')
                ->whereIn('school_id', $schoolIds)
                ->whereDate('created_at', $dateStr)
                ->groupBy('school_id')->selectRaw('school_id, count(*) as count')->pluck('count', 'school_id');
        }

        if (Schema::hasTable('boarding_discipline_logs')) {
            $disciplineCount = DB::table('boarding_discipline_logs')
                ->whereIn('school_id', $schoolIds)
                ->whereDate('created_at', $dateStr)
                ->groupBy('school_id')->selectRaw('school_id, count(*) as count')->pluck('count', 'school_id');
        }

        foreach ($schoolIds as $schoolId) {
            $sAttRecords = $attRecords->get($schoolId) ?? collect();

            $present = $sAttRecords->where('status', 'present')->sum('count');
            $late = $sAttRecords->where('status', 'late')->sum('count');
            $absent = $sAttRecords->where('status', 'absent')->sum('count');
            $sick = $sAttRecords->where('status', 'sick')->sum('count');
            $permission = $sAttRecords->where('status', 'permission')->sum('count');
            $totalAtt = $present + $late + $absent + $sick + $permission;

            $attRate = 0;
            $lateRate = 0;
            if ($totalAtt > 0) {
                $attRate = min(100.00, round((($present + $late) / $totalAtt) * 100, 2));
                $lateRate = min(100.00, round(($late / $totalAtt) * 100, 2));
            }

            $sRollCallCount = $rollCallCount->get($schoolId) ?? 0;
            $sLeaveCount = $leaveCount->get($schoolId) ?? 0;
            $sHealthCount = $healthCount->get($schoolId) ?? 0;
            $sDisciplineCount = $disciplineCount->get($schoolId) ?? 0;

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
                    'boarding_roll_call_records_count' => $sRollCallCount,
                    'boarding_leave_requests_count' => $sLeaveCount,
                    'boarding_health_logs_count' => $sHealthCount,
                    'boarding_discipline_logs_count' => $sDisciplineCount,
                    'raw_metrics' => [
                        'timestamp' => now()->toIso8601String(),
                    ],
                ]
            );
        }
    }

    /**
     * @param int|array $schoolIds
     */
    public function captureFinance($schoolIds, CarbonInterface $date): void
    {
        $schoolIds = (array) $schoolIds;
        if (empty($schoolIds)) return;

        $dateStr = $date->toDateString();

        $billsTotal = collect();
        $paymentsTotal = collect();
        $outstandingTotal = collect();
        $overdueCount = collect();
        $voidBills = collect();
        $voidPayments = collect();

        if (Schema::hasTable('student_bills')) {
            $billsTotal = DB::table('student_bills')
                ->whereIn('school_id', $schoolIds)
                ->where('status', '!=', 'void')
                ->whereDate('created_at', $dateStr)
                ->groupBy('school_id')->selectRaw('school_id, sum(total_amount) as total')->pluck('total', 'school_id');

            $outstandingTotal = DB::table('student_bills')
                ->whereIn('school_id', $schoolIds)
                ->where('status', '!=', 'void')
                ->groupBy('school_id')->selectRaw('school_id, sum(outstanding_amount) as total')->pluck('total', 'school_id');

            $overdueCount = DB::table('student_bills')
                ->whereIn('school_id', $schoolIds)
                ->where('status', 'unpaid')
                ->whereDate('due_date', '<', $dateStr)
                ->groupBy('school_id')->selectRaw('school_id, count(*) as count')->pluck('count', 'school_id');

            $voidBills = DB::table('student_bills')
                ->whereIn('school_id', $schoolIds)
                ->where('status', 'void')
                ->whereDate('updated_at', $dateStr)
                ->groupBy('school_id')->selectRaw('school_id, count(*) as count')->pluck('count', 'school_id');
        }

        if (Schema::hasTable('student_payments')) {
            $paymentsTotal = DB::table('student_payments')
                ->whereIn('school_id', $schoolIds)
                ->where('status', '!=', 'void')
                ->whereDate('payment_date', $dateStr)
                ->groupBy('school_id')->selectRaw('school_id, sum(amount) as total')->pluck('total', 'school_id');

            $voidPayments = DB::table('student_payments')
                ->whereIn('school_id', $schoolIds)
                ->where('status', 'void')
                ->whereDate('updated_at', $dateStr)
                ->groupBy('school_id')->selectRaw('school_id, count(*) as count')->pluck('count', 'school_id');
        }

        // Cashless POS
        $cashlessTopup = collect();
        $cashlessPurchase = collect();
        $cashlessRefund = collect();
        $cashlessVoid = collect();
        $cashlessNegative = collect();
        $cashlessPending = collect();

        if (Schema::hasTable('wallet_transactions')) {
            $cashlessTopup = DB::table('wallet_transactions')
                ->whereIn('school_id', $schoolIds)
                ->where('type', 'topup')
                ->where('status', 'success')
                ->whereDate('created_at', $dateStr)
                ->groupBy('school_id')->selectRaw('school_id, sum(amount) as total')->pluck('total', 'school_id');

            $cashlessPurchase = DB::table('wallet_transactions')
                ->whereIn('school_id', $schoolIds)
                ->where('type', 'purchase')
                ->where('status', 'success')
                ->whereDate('created_at', $dateStr)
                ->groupBy('school_id')->selectRaw('school_id, sum(amount) as total')->pluck('total', 'school_id');

            $cashlessRefund = DB::table('wallet_transactions')
                ->whereIn('school_id', $schoolIds)
                ->where('type', 'refund')
                ->where('status', 'success')
                ->whereDate('created_at', $dateStr)
                ->groupBy('school_id')->selectRaw('school_id, sum(amount) as total')->pluck('total', 'school_id');

            $cashlessVoid = DB::table('wallet_transactions')
                ->whereIn('school_id', $schoolIds)
                ->where('status', 'void')
                ->whereDate('updated_at', $dateStr)
                ->groupBy('school_id')->selectRaw('school_id, count(*) as count')->pluck('count', 'school_id');
        }

        if (Schema::hasTable('student_wallets')) {
            $cashlessNegative = DB::table('student_wallets')
                ->whereIn('school_id', $schoolIds)
                ->where('balance', '<', 0)
                ->groupBy('school_id')->selectRaw('school_id, count(*) as count')->pluck('count', 'school_id');
        }

        if (Schema::hasTable('merchant_settlements')) {
            $cashlessPending = DB::table('merchant_settlements')
                ->whereIn('school_id', $schoolIds)
                ->where('status', 'pending')
                ->groupBy('school_id')->selectRaw('school_id, count(*) as count')->pluck('count', 'school_id');
        }

        foreach ($schoolIds as $schoolId) {
            $sBillsTotal = $billsTotal->get($schoolId) ?? 0;
            $sPaymentsTotal = $paymentsTotal->get($schoolId) ?? 0;
            $sOutstandingTotal = $outstandingTotal->get($schoolId) ?? 0;
            $sOverdueCount = $overdueCount->get($schoolId) ?? 0;
            $sVoidBills = $voidBills->get($schoolId) ?? 0;
            $sVoidPayments = $voidPayments->get($schoolId) ?? 0;

            $sCashlessTopup = $cashlessTopup->get($schoolId) ?? 0;
            $sCashlessPurchase = $cashlessPurchase->get($schoolId) ?? 0;
            $sCashlessRefund = $cashlessRefund->get($schoolId) ?? 0;
            $sCashlessVoid = $cashlessVoid->get($schoolId) ?? 0;
            $sCashlessNegative = $cashlessNegative->get($schoolId) ?? 0;
            $sCashlessPending = $cashlessPending->get($schoolId) ?? 0;

            SchoolFinanceSnapshot::query()->updateOrCreate(
                [
                    'school_id' => $schoolId,
                    'snapshot_date' => $dateStr,
                    'period_type' => 'daily',
                ],
                [
                    'student_bills_total' => $sBillsTotal,
                    'student_payments_total' => $sPaymentsTotal,
                    'student_outstanding_total' => $sOutstandingTotal,
                    'overdue_bills_count' => $sOverdueCount,
                    'void_bills_count' => $sVoidBills,
                    'void_payments_count' => $sVoidPayments,
                    'cashless_topup_total' => $sCashlessTopup,
                    'cashless_purchase_total' => $sCashlessPurchase,
                    'cashless_refund_total' => $sCashlessRefund,
                    'cashless_void_count' => $sCashlessVoid,
                    'cashless_negative_balance_anomaly_count' => $sCashlessNegative,
                    'cashless_pending_settlement_count' => $sCashlessPending,
                    'raw_metrics' => [
                        'timestamp' => now()->toIso8601String(),
                    ],
                ]
            );
        }
    }

    /**
     * @param int|array|null $schoolIds
     */
    public function captureSupport($schoolIds, CarbonInterface $date): void
    {
        $schoolIds = is_null($schoolIds) ? [null] : (array) $schoolIds;
        if (empty($schoolIds)) return;

        $dateStr = $date->toDateString();

        $isGlobal = in_array(null, $schoolIds, true);
        if ($isGlobal) {
            $schoolIds = [null]; // If global, only process one null item
        }

        $openCount = collect();
        $critical = collect();
        $high = collect();
        $medium = collect();
        $low = collect();
        $slaBreached = collect();
        $incidents = collect();
        $sev1 = collect();
        $sev2 = collect();

        if (Schema::hasTable('support_tickets')) {
            $query = DB::table('support_tickets');
            if (!$isGlobal) {
                $query->whereIn('school_id', $schoolIds);
                $openCount = (clone $query)->whereIn('status', ['open', 'in_progress'])->groupBy('school_id')->selectRaw('school_id, count(*) as count')->pluck('count', 'school_id');
                $critical = (clone $query)->where('priority', 'critical')->whereIn('status', ['open', 'in_progress'])->groupBy('school_id')->selectRaw('school_id, count(*) as count')->pluck('count', 'school_id');
                $high = (clone $query)->where('priority', 'high')->whereIn('status', ['open', 'in_progress'])->groupBy('school_id')->selectRaw('school_id, count(*) as count')->pluck('count', 'school_id');
                $medium = (clone $query)->where('priority', 'medium')->whereIn('status', ['open', 'in_progress'])->groupBy('school_id')->selectRaw('school_id, count(*) as count')->pluck('count', 'school_id');
                $low = (clone $query)->where('priority', 'low')->whereIn('status', ['open', 'in_progress'])->groupBy('school_id')->selectRaw('school_id, count(*) as count')->pluck('count', 'school_id');
                $slaBreached = (clone $query)->where('sla_breached', true)->groupBy('school_id')->selectRaw('school_id, count(*) as count')->pluck('count', 'school_id');
            } else {
                $openCount = collect(["" => (clone $query)->whereIn('status', ['open', 'in_progress'])->count()]);
                $critical = collect(["" => (clone $query)->where('priority', 'critical')->whereIn('status', ['open', 'in_progress'])->count()]);
                $high = collect(["" => (clone $query)->where('priority', 'high')->whereIn('status', ['open', 'in_progress'])->count()]);
                $medium = collect(["" => (clone $query)->where('priority', 'medium')->whereIn('status', ['open', 'in_progress'])->count()]);
                $low = collect(["" => (clone $query)->where('priority', 'low')->whereIn('status', ['open', 'in_progress'])->count()]);
                $slaBreached = collect(["" => (clone $query)->where('sla_breached', true)->count()]);
            }
        }

        if (Schema::hasTable('incident_reports')) {
            $queryInc = DB::table('incident_reports');
            if (!$isGlobal) {
                $queryInc->whereIn('school_id', $schoolIds);
                $incidents = (clone $queryInc)->whereDate('detected_at', $dateStr)->groupBy('school_id')->selectRaw('school_id, count(*) as count')->pluck('count', 'school_id');
                $sev1 = (clone $queryInc)->where('severity', 'sev1')->whereDate('detected_at', $dateStr)->groupBy('school_id')->selectRaw('school_id, count(*) as count')->pluck('count', 'school_id');
                $sev2 = (clone $queryInc)->where('severity', 'sev2')->whereDate('detected_at', $dateStr)->groupBy('school_id')->selectRaw('school_id, count(*) as count')->pluck('count', 'school_id');
            } else {
                $incidents = collect(["" => (clone $queryInc)->whereDate('detected_at', $dateStr)->count()]);
                $sev1 = collect(["" => (clone $queryInc)->where('severity', 'sev1')->whereDate('detected_at', $dateStr)->count()]);
                $sev2 = collect(["" => (clone $queryInc)->where('severity', 'sev2')->whereDate('detected_at', $dateStr)->count()]);
            }
        }

        foreach ($schoolIds as $schoolId) {
            $idx = $isGlobal ? "" : $schoolId;
            $sOpenCount = $openCount->get($idx) ?? 0;
            $sCritical = $critical->get($idx) ?? 0;
            $sHigh = $high->get($idx) ?? 0;
            $sMedium = $medium->get($idx) ?? 0;
            $sLow = $low->get($idx) ?? 0;
            $sSlaBreached = $slaBreached->get($idx) ?? 0;
            $sIncidents = $incidents->get($idx) ?? 0;
            $sSev1 = $sev1->get($idx) ?? 0;
            $sSev2 = $sev2->get($idx) ?? 0;

            SchoolSupportSnapshot::query()->updateOrCreate(
                [
                    'school_id' => $schoolId,
                    'snapshot_date' => $dateStr,
                    'period_type' => 'daily',
                ],
                [
                    'open_tickets_count' => $sOpenCount,
                    'critical_tickets_count' => $sCritical,
                    'high_tickets_count' => $sHigh,
                    'medium_tickets_count' => $sMedium,
                    'low_tickets_count' => $sLow,
                    'sla_breached_tickets_count' => $sSlaBreached,
                    'incidents_count' => $sIncidents,
                    'sev1_incidents_count' => $sSev1,
                    'sev2_incidents_count' => $sSev2,
                    'average_first_response_minutes' => 0,
                    'average_resolution_minutes' => 0,
                    'raw_metrics' => [
                        'timestamp' => now()->toIso8601String(),
                    ],
                ]
            );
        }
    }

    /**
     * @param int|array|null $schoolIds
     */
    public function captureMobileApi($schoolIds, CarbonInterface $date): void
    {
        $schoolIds = is_null($schoolIds) ? [null] : (array) $schoolIds;
        if (empty($schoolIds)) return;

        $dateStr = $date->toDateString();

        $isGlobal = in_array(null, $schoolIds, true);
        if ($isGlobal) {
            $schoolIds = [null];
        }

        $devicesCount = collect();
        $androidCount = collect();
        $iosCount = collect();
        $activeDevices = collect();
        $apiRequests = collect();
        $apiErrors = collect();

        $extApiRequests = collect();
        $extApiErrors = collect();
        $rateLimitHits = collect();
        $failedAuth = collect();
        $webhookSuccess = collect();
        $webhookFailed = collect();

        if (Schema::hasTable('mobile_devices')) {
            $query = DB::table('mobile_devices');
            if (!$isGlobal) {
                $query->whereIn('school_id', $schoolIds);
                $devicesCount = (clone $query)->groupBy('school_id')->selectRaw('school_id, count(*) as count')->pluck('count', 'school_id');
                $androidCount = (clone $query)->where('platform', 'android')->groupBy('school_id')->selectRaw('school_id, count(*) as count')->pluck('count', 'school_id');
                $iosCount = (clone $query)->where('platform', 'ios')->groupBy('school_id')->selectRaw('school_id, count(*) as count')->pluck('count', 'school_id');
            } else {
                $devicesCount = collect(["" => (clone $query)->count()]);
                $androidCount = collect(["" => (clone $query)->where('platform', 'android')->count()]);
                $iosCount = collect(["" => (clone $query)->where('platform', 'ios')->count()]);
            }
        }

        if (Schema::hasTable('mobile_api_audit_logs')) {
            $query = DB::table('mobile_api_audit_logs')->whereDate('created_at', $dateStr);
            if (!$isGlobal) {
                $query->whereIn('school_id', $schoolIds);
                $activeDevices = (clone $query)->groupBy('school_id')->selectRaw('school_id, count(distinct mobile_device_id) as count')->pluck('count', 'school_id');
                $apiRequests = (clone $query)->groupBy('school_id')->selectRaw('school_id, count(*) as count')->pluck('count', 'school_id');
                $apiErrors = (clone $query)->where('status_code', '>=', 400)->groupBy('school_id')->selectRaw('school_id, count(*) as count')->pluck('count', 'school_id');
            } else {
                $activeDevices = collect(["" => (clone $query)->distinct('mobile_device_id')->count()]);
                $apiRequests = collect(["" => (clone $query)->count()]);
                $apiErrors = collect(["" => (clone $query)->where('status_code', '>=', 400)->count()]);
            }
        }

        if (Schema::hasTable('api_request_logs')) {
            $query = DB::table('api_request_logs')->whereDate('created_at', $dateStr);
            if (!$isGlobal) {
                $query->whereIn('school_id', $schoolIds);
                $extApiRequests = (clone $query)->groupBy('school_id')->selectRaw('school_id, count(*) as count')->pluck('count', 'school_id');
                $extApiErrors = (clone $query)->where('response_status', '>=', 400)->groupBy('school_id')->selectRaw('school_id, count(*) as count')->pluck('count', 'school_id');
                $rateLimitHits = (clone $query)->where('response_status', 429)->groupBy('school_id')->selectRaw('school_id, count(*) as count')->pluck('count', 'school_id');
                $failedAuth = (clone $query)->where('response_status', 412)->groupBy('school_id')->selectRaw('school_id, count(*) as count')->pluck('count', 'school_id');
            } else {
                $extApiRequests = collect(["" => (clone $query)->count()]);
                $extApiErrors = collect(["" => (clone $query)->where('response_status', '>=', 400)->count()]);
                $rateLimitHits = collect(["" => (clone $query)->where('response_status', 429)->count()]);
                $failedAuth = collect(["" => (clone $query)->where('response_status', 412)->count()]);
            }
        }

        if (Schema::hasTable('webhook_deliveries')) {
            $query = DB::table('webhook_deliveries')->whereDate('created_at', $dateStr);
            if (!$isGlobal) {
                $query->whereIn('school_id', $schoolIds);
                $webhookSuccess = (clone $query)->where('status', 'delivered')->groupBy('school_id')->selectRaw('school_id, count(*) as count')->pluck('count', 'school_id');
                $webhookFailed = (clone $query)->where('status', 'failed')->groupBy('school_id')->selectRaw('school_id, count(*) as count')->pluck('count', 'school_id');
            } else {
                $webhookSuccess = collect(["" => (clone $query)->where('status', 'delivered')->count()]);
                $webhookFailed = collect(["" => (clone $query)->where('status', 'failed')->count()]);
            }
        }

        foreach ($schoolIds as $schoolId) {
            $idx = $isGlobal ? "" : $schoolId;

            $sDevicesCount = $devicesCount->get($idx) ?? 0;
            $sActiveDevices = $activeDevices->get($idx) ?? 0;
            $sAndroidCount = $androidCount->get($idx) ?? 0;
            $sIosCount = $iosCount->get($idx) ?? 0;

            $sApiRequests = ($apiRequests->get($idx) ?? 0) + ($extApiRequests->get($idx) ?? 0);
            $sApiErrors = ($apiErrors->get($idx) ?? 0) + ($extApiErrors->get($idx) ?? 0);

            $sRateLimitHits = $rateLimitHits->get($idx) ?? 0;
            $sFailedAuth = $failedAuth->get($idx) ?? 0;
            $sWebhookSuccess = $webhookSuccess->get($idx) ?? 0;
            $sWebhookFailed = $webhookFailed->get($idx) ?? 0;

            MobileApiUsageSnapshot::query()->updateOrCreate(
                [
                    'school_id' => $schoolId,
                    'snapshot_date' => $dateStr,
                    'period_type' => 'daily',
                ],
                [
                    'mobile_devices_count' => $sDevicesCount,
                    'active_mobile_devices_count' => $sActiveDevices,
                    'android_devices_count' => $sAndroidCount,
                    'ios_devices_count' => $sIosCount,
                    'force_update_devices_count' => 0,
                    'api_requests_count' => $sApiRequests,
                    'api_error_count' => $sApiErrors,
                    'api_rate_limit_hits_count' => $sRateLimitHits,
                    'api_failed_auth_count' => $sFailedAuth,
                    'webhook_success_count' => $sWebhookSuccess,
                    'webhook_failed_count' => $sWebhookFailed,
                    'raw_metrics' => [
                        'timestamp' => now()->toIso8601String(),
                    ],
                ]
            );
        }
    }

    /**
     * @param int|array $schoolIds
     */
    private function compileToGeneralSnapshots($schoolIds, CarbonInterface $date): void
    {
        $schoolIds = (array) $schoolIds;
        if (empty($schoolIds)) return;

        $dateStr = $date->toDateString();

        $academic = SchoolAcademicSnapshot::query()
            ->whereIn('school_id', $schoolIds)
            ->where('snapshot_date', $dateStr)
            ->get()->keyBy('school_id');

        $operational = SchoolOperationalSnapshot::query()
            ->whereIn('school_id', $schoolIds)
            ->where('snapshot_date', $dateStr)
            ->get()->keyBy('school_id');

        $finance = SchoolFinanceSnapshot::query()
            ->whereIn('school_id', $schoolIds)
            ->where('snapshot_date', $dateStr)
            ->get()->keyBy('school_id');

        $support = SchoolSupportSnapshot::query()
            ->whereIn('school_id', $schoolIds)
            ->where('snapshot_date', $dateStr)
            ->get()->keyBy('school_id');

        $mobile = MobileApiUsageSnapshot::query()
            ->whereIn('school_id', $schoolIds)
            ->where('snapshot_date', $dateStr)
            ->get()->keyBy('school_id');

        foreach ($schoolIds as $schoolId) {
            $sAcademic = $academic->get($schoolId);
            $sOperational = $operational->get($schoolId);
            $sFinance = $finance->get($schoolId);
            $sSupport = $support->get($schoolId);
            $sMobile = $mobile->get($schoolId);

            $metrics = [
                'usage.active_users' => $sAcademic?->active_students_count ?: 0,
                'academic.hafalan_records' => $sAcademic?->hafalan_records_count ?: 0,
                'academic.tahfizh_achievement_rate' => $sAcademic?->tahfizh_target_achievement_rate ?: 0,
                'academic.students_behind_target' => $sAcademic?->students_behind_target_count ?: 0,
                'mutabaah.completion_rate' => $sAcademic?->mutabaah_completion_rate ?: 0,
                'attendance.attendance_rate' => $sOperational?->attendance_rate ?: 0,
                'attendance.late_rate' => $sOperational?->late_rate ?: 0,
                'tahsin.average_score' => $sAcademic?->tahsin_average_score ?: 0,
                'finance.outstanding_total' => $sFinance?->student_outstanding_total ?: 0,
                'cashless.purchase_total' => $sFinance?->cashless_purchase_total ?: 0,
                'support.sla_breach_rate' => $sSupport && $sSupport->open_tickets_count > 0 ? round(($sSupport->sla_breached_tickets_count / $sSupport->open_tickets_count) * 100, 2) : 0,
                'mobile.active_devices' => $sMobile?->active_mobile_devices_count ?: 0,
                'api.error_rate' => $sMobile && $sMobile->api_requests_count > 0 ? round(($sMobile->api_error_count / $sMobile->api_requests_count) * 100, 2) : 0,
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
