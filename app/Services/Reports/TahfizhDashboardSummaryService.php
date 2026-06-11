<?php

namespace App\Services\Reports;

use App\Models\HafalanRecord;
use App\Models\Student;
use App\Models\TahfizhDebt;
use App\Models\User;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;

class TahfizhDashboardSummaryService
{
    public function summarize(
        User $user,
        CarbonInterface $dateFrom,
        CarbonInterface $dateUntil,
        ?int $classRoomId = null,
        ?int $studentId = null,
        ?int $teacherId = null,
        ?string $status = null
    ): array {
        $studentQuery = Student::query()
            ->where('is_active', true)
            ->when($classRoomId, function (Builder $query) use ($classRoomId): void {
                $query->where('class_room_id', $classRoomId);
            })
            ->when($studentId, function (Builder $query) use ($studentId): void {
                $query->where('id', $studentId);
            });

        $recordQuery = HafalanRecord::query()
            ->whereBetween('record_date', [
                $dateFrom->toDateString(),
                $dateUntil->toDateString(),
            ])
            ->when($classRoomId, function (Builder $query) use ($classRoomId): void {
                $query->whereHas('student', function (Builder $studentQuery) use ($classRoomId): void {
                    $studentQuery->where('class_room_id', $classRoomId);
                });
            })
            ->when($studentId, function (Builder $query) use ($studentId): void {
                $query->where('student_id', $studentId);
            })
            ->when($teacherId, function (Builder $query) use ($teacherId): void {
                $query->where('teacher_id', $teacherId);
            })
            ->when($status, function (Builder $query) use ($status): void {
                $query->where('status', $status);
            });

        if ($user->hasRole('teacher')) {
            $recordQuery->where('teacher_id', $user->id);
        }

        $debtQuery = TahfizhDebt::query()
            ->whereBetween('period_start', [
                $dateFrom->toDateString(),
                $dateUntil->toDateString(),
            ])
            ->when($classRoomId, function (Builder $query) use ($classRoomId): void {
                $query->where('class_room_id', $classRoomId);
            })
            ->when($studentId, function (Builder $query) use ($studentId): void {
                $query->where('student_id', $studentId);
            });

        $totalStudents = (clone $studentQuery)->count();
        $totalRecords = (clone $recordQuery)->count();
        $totalLines = (int) (clone $recordQuery)->sum('total_lines');

        $totalDebtLines = (int) (clone $debtQuery)->sum('debt_lines');
        $totalSurplusLines = (int) (clone $debtQuery)->sum('surplus_lines');
        $totalCumulativeDebtLines = (int) (clone $debtQuery)->sum('cumulative_debt_lines');

        $behindCount = (clone $debtQuery)
            ->where('status', TahfizhDebt::STATUS_BEHIND)
            ->count();

        $metCount = (clone $debtQuery)
            ->where('status', TahfizhDebt::STATUS_MET)
            ->count();

        $aheadCount = (clone $debtQuery)
            ->where('status', TahfizhDebt::STATUS_AHEAD)
            ->count();

        $noTargetCount = (clone $debtQuery)
            ->where('status', TahfizhDebt::STATUS_NO_TARGET)
            ->count();

        $teacherActivity = HafalanRecord::query()
            ->selectRaw('teacher_id, COUNT(*) as total_records, SUM(total_lines) as total_lines')
            ->with('teacher')
            ->whereBetween('record_date', [
                $dateFrom->toDateString(),
                $dateUntil->toDateString(),
            ])
            ->when($classRoomId, function (Builder $query) use ($classRoomId): void {
                $query->whereHas('student', function (Builder $studentQuery) use ($classRoomId): void {
                    $studentQuery->where('class_room_id', $classRoomId);
                });
            })
            ->when($user->hasRole('teacher'), function (Builder $query) use ($user): void {
                $query->where('teacher_id', $user->id);
            })
            ->groupBy('teacher_id')
            ->orderByDesc('total_records')
            ->limit(10)
            ->get();

        $atRiskStudents = TahfizhDebt::query()
            ->with(['student.classRoom'])
            ->where('status', TahfizhDebt::STATUS_BEHIND)
            ->whereBetween('period_start', [
                $dateFrom->toDateString(),
                $dateUntil->toDateString(),
            ])
            ->when($classRoomId, function (Builder $query) use ($classRoomId): void {
                $query->where('class_room_id', $classRoomId);
            })
            ->orderByDesc('cumulative_debt_lines')
            ->limit(10)
            ->get();

        return [
            'total_students' => $totalStudents,
            'total_records' => $totalRecords,
            'total_lines' => $totalLines,
            'total_debt_lines' => $totalDebtLines,
            'total_surplus_lines' => $totalSurplusLines,
            'total_cumulative_debt_lines' => $totalCumulativeDebtLines,
            'behind_count' => $behindCount,
            'met_count' => $metCount,
            'ahead_count' => $aheadCount,
            'no_target_count' => $noTargetCount,
            'teacher_activity' => $teacherActivity,
            'at_risk_students' => $atRiskStudents,
        ];
    }
}
