<?php

namespace App\Services\Tahfizh;

use App\Models\HafalanRecord;
use App\Models\Student;
use App\Models\TahfizhDebt;
use App\Models\TahfizhTarget;
use App\Models\User;
use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;
use InvalidArgumentException;

class TahfizhDebtCalculator
{
    public function __construct(
        private readonly ActiveTahfizhTargetResolver $targetResolver,
    ) {
        //
    }

    public function calculateForStudent(
        Student $student,
        CarbonInterface|string $date,
        string $periodType = TahfizhDebt::PERIOD_DAILY,
        ?User $calculatedBy = null
    ): TahfizhDebt {
        $date = $date instanceof CarbonInterface
            ? Carbon::instance($date->toDateTime())
            : Carbon::parse($date);

        [$periodStart, $periodEnd] = $this->periodRange($date, $periodType);

        $target = $this->targetResolver->resolveForStudent($student, $date);

        $targetLines = $target
            ? $this->targetLines($target, $periodType)
            : 0;

        $actualLines = $this->actualLines(
            student: $student,
            periodStart: $periodStart,
            periodEnd: $periodEnd
        );

        $debtLines = max(0, $targetLines - $actualLines);
        $surplusLines = max(0, $actualLines - $targetLines);

        $previousDebt = TahfizhDebt::query()
            ->where('student_id', $student->id)
            ->where('period_type', $periodType)
            ->whereDate('period_end', '<', $periodStart)
            ->orderByDesc('period_end')
            ->orderByDesc('id')
            ->first();

        $previousCumulativeDebt = $previousDebt?->cumulative_debt_lines ?? 0;

        $cumulativeDebtLines = max(
            0,
            $previousCumulativeDebt + $debtLines - $surplusLines
        );

        $status = $this->status(
            targetLines: $targetLines,
            actualLines: $actualLines,
            debtLines: $debtLines,
            surplusLines: $surplusLines,
        );

        return TahfizhDebt::query()->updateOrCreate(
            [
                'student_id' => $student->id,
                'period_type' => $periodType,
                'period_start' => $periodStart->toDateString(),
                'period_end' => $periodEnd->toDateString(),
            ],
            [
                'school_id' => $student->school_id,
                'class_room_id' => $student->class_room_id,
                'tahfizh_target_id' => $target?->id,
                'calculation_date' => $date->toDateString(),
                'target_lines' => $targetLines,
                'actual_lines' => $actualLines,
                'debt_lines' => $debtLines,
                'surplus_lines' => $surplusLines,
                'cumulative_debt_lines' => $cumulativeDebtLines,
                'status' => $status,
                'calculated_by' => $calculatedBy?->id,
                'notes' => $this->notes($target, $targetLines, $actualLines, $debtLines, $surplusLines),
            ]
        );
    }

    public function calculateForStudents(
        iterable $students,
        CarbonInterface|string $date,
        string $periodType = TahfizhDebt::PERIOD_DAILY,
        ?User $calculatedBy = null
    ): array {
        $results = [];

        foreach ($students as $student) {
            $results[] = $this->calculateForStudent(
                student: $student,
                date: $date,
                periodType: $periodType,
                calculatedBy: $calculatedBy
            );
        }

        return $results;
    }

    private function actualLines(Student $student, Carbon $periodStart, Carbon $periodEnd): int
    {
        return (int) HafalanRecord::query()
            ->where('student_id', $student->id)
            ->whereBetween('record_date', [
                $periodStart->toDateString(),
                $periodEnd->toDateString(),
            ])
            ->whereIn('status', [
                HafalanRecord::STATUS_LUNAS,
                HafalanRecord::STATUS_KURANG,
                HafalanRecord::STATUS_LEBIH,
            ])
            ->sum('total_lines');
    }

    private function periodRange(Carbon $date, string $periodType): array
    {
        return match ($periodType) {
            TahfizhDebt::PERIOD_DAILY => [
                $date->copy()->startOfDay(),
                $date->copy()->endOfDay(),
            ],
            TahfizhDebt::PERIOD_WEEKLY => [
                $date->copy()->startOfWeek(),
                $date->copy()->endOfWeek(),
            ],
            TahfizhDebt::PERIOD_MONTHLY => [
                $date->copy()->startOfMonth(),
                $date->copy()->endOfMonth(),
            ],
            default => throw new InvalidArgumentException('Jenis periode tidak valid.'),
        };
    }

    private function targetLines(TahfizhTarget $target, string $periodType): int
    {
        return match ($periodType) {
            TahfizhDebt::PERIOD_DAILY => (int) $target->daily_target_lines,
            TahfizhDebt::PERIOD_WEEKLY => (int) $target->weekly_target_lines,
            TahfizhDebt::PERIOD_MONTHLY => (int) $target->monthly_target_lines,
            default => 0,
        };
    }

    private function status(
        int $targetLines,
        int $actualLines,
        int $debtLines,
        int $surplusLines
    ): string {
        if ($targetLines <= 0) {
            return TahfizhDebt::STATUS_NO_TARGET;
        }

        if ($debtLines > 0) {
            return TahfizhDebt::STATUS_BEHIND;
        }

        if ($surplusLines > 0) {
            return TahfizhDebt::STATUS_AHEAD;
        }

        return TahfizhDebt::STATUS_MET;
    }

    private function notes(
        ?TahfizhTarget $target,
        int $targetLines,
        int $actualLines,
        int $debtLines,
        int $surplusLines
    ): string {
        if (! $target || $targetLines <= 0) {
            return 'Belum ada target aktif untuk periode ini.';
        }

        if ($debtLines > 0) {
            return "Kurang {$debtLines} baris dari target {$targetLines} baris.";
        }

        if ($surplusLines > 0) {
            return "Lebih {$surplusLines} baris dari target {$targetLines} baris.";
        }

        return "Target tercapai tepat {$actualLines} baris.";
    }
}
