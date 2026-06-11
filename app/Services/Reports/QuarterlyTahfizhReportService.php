<?php

namespace App\Services\Reports;

use App\Models\HafalanRecord;
use App\Models\Student;
use App\Models\TahfizhDebt;
use App\Models\User;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class QuarterlyTahfizhReportService
{
    public function build(
        User $user,
        CarbonInterface $periodStart,
        CarbonInterface $periodEnd,
        ?int $classRoomId = null,
        ?int $studentId = null,
        ?int $teacherId = null,
        ?string $status = null
    ): Collection {
        $students = Student::query()
            ->with(['classRoom', 'school'])
            ->where('is_active', true)
            ->when($classRoomId, function (Builder $query) use ($classRoomId): void {
                $query->where('class_room_id', $classRoomId);
            })
            ->when($studentId, function (Builder $query) use ($studentId): void {
                $query->where('id', $studentId);
            })
            ->orderBy('full_name')
            ->get();

        return $students->map(function (Student $student) use (
            $user,
            $periodStart,
            $periodEnd,
            $teacherId,
            $status
        ): array {
            $recordQuery = HafalanRecord::query()
                ->where('student_id', $student->id)
                ->whereBetween('record_date', [
                    $periodStart->toDateString(),
                    $periodEnd->toDateString(),
                ])
                ->when($teacherId, function (Builder $query) use ($teacherId): void {
                    $query->where('teacher_id', $teacherId);
                })
                ->when($status, function (Builder $query) use ($status): void {
                    $query->where('status', $status);
                });

            if ($user->hasRole('teacher')) {
                $recordQuery->where('teacher_id', $user->id);
            }

            $actualLines = (int) (clone $recordQuery)->sum('total_lines');
            $recordCount = (clone $recordQuery)->count();

            $monthlyDebts = TahfizhDebt::query()
                ->where('student_id', $student->id)
                ->where('period_type', TahfizhDebt::PERIOD_MONTHLY)
                ->whereDate('period_start', '>=', $periodStart->toDateString())
                ->whereDate('period_end', '<=', $periodEnd->toDateString())
                ->orderBy('period_start')
                ->get();

            $targetLines = (int) $monthlyDebts->sum('target_lines');
            $debtLines = (int) $monthlyDebts->sum('debt_lines');
            $surplusLines = (int) $monthlyDebts->sum('surplus_lines');
            $cumulativeDebtLines = (int) ($monthlyDebts->last()?->cumulative_debt_lines ?? 0);

            $statusValue = match (true) {
                $targetLines <= 0 => TahfizhDebt::STATUS_NO_TARGET,
                $debtLines > 0 => TahfizhDebt::STATUS_BEHIND,
                $surplusLines > 0 => TahfizhDebt::STATUS_AHEAD,
                default => TahfizhDebt::STATUS_MET,
            };

            return [
                'student' => $student,
                'class_room' => $student->classRoom,
                'record_count' => $recordCount,
                'actual_lines' => $actualLines,
                'target_lines' => $targetLines,
                'debt_lines' => $debtLines,
                'surplus_lines' => $surplusLines,
                'cumulative_debt_lines' => $cumulativeDebtLines,
                'status' => $statusValue,
                'monthly_debts' => $monthlyDebts,
            ];
        });
    }
}
