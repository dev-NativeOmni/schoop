<?php

namespace App\Services\Reports;

use App\Models\HafalanRecord;
use App\Models\Student;
use App\Models\TahfizhDebt;
use App\Models\User;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class MonthlyTahfizhReportService
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

            $debt = TahfizhDebt::query()
                ->where('student_id', $student->id)
                ->where('period_type', TahfizhDebt::PERIOD_MONTHLY)
                ->whereDate('period_start', $periodStart->toDateString())
                ->whereDate('period_end', $periodEnd->toDateString())
                ->first();

            return [
                'student' => $student,
                'class_room' => $student->classRoom,
                'record_count' => $recordCount,
                'actual_lines' => $actualLines,
                'target_lines' => $debt?->target_lines ?? 0,
                'debt_lines' => $debt?->debt_lines ?? 0,
                'surplus_lines' => $debt?->surplus_lines ?? 0,
                'cumulative_debt_lines' => $debt?->cumulative_debt_lines ?? 0,
                'status' => $debt?->status ?? TahfizhDebt::STATUS_NO_TARGET,
                'notes' => $debt?->notes ?? 'Belum ada perhitungan hutang bulanan.',
            ];
        });
    }
}
