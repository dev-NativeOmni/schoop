<?php

namespace App\Services\Portal;

use App\Models\HafalanRecord;
use App\Models\Student;
use App\Models\TahfizhDebt;
use App\Models\TahfizhTarget;
use App\Services\Tahfizh\ActiveTahfizhTargetResolver;
use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;

class StudentProgressSnapshotService
{
    public function __construct(
        private readonly ActiveTahfizhTargetResolver $targetResolver,
    ) {
        //
    }

    public function snapshot(
        Student $student,
        CarbonInterface|string|null $dateFrom = null,
        CarbonInterface|string|null $dateUntil = null
    ): array {
        $dateFrom = $dateFrom
            ? Carbon::parse($dateFrom)->startOfDay()
            : now()->startOfMonth();

        $dateUntil = $dateUntil
            ? Carbon::parse($dateUntil)->endOfDay()
            : now()->endOfDay();

        $activeTarget = $this->targetResolver->resolveForStudent($student, now());

        $recordsQuery = HafalanRecord::query()
            ->where('student_id', $student->id)
            ->whereBetween('record_date', [
                $dateFrom->toDateString(),
                $dateUntil->toDateString(),
            ]);

        $totalRecords = (clone $recordsQuery)->count();

        $totalLines = (int) (clone $recordsQuery)
            ->whereIn('status', [
                HafalanRecord::STATUS_LUNAS,
                HafalanRecord::STATUS_KURANG,
                HafalanRecord::STATUS_LEBIH,
            ])
            ->sum('total_lines');

        $latestRecord = HafalanRecord::query()
            ->with(['teacher', 'startSurah', 'endSurah'])
            ->where('student_id', $student->id)
            ->latest('record_date')
            ->latest('id')
            ->first();

        $latestDebt = TahfizhDebt::query()
            ->where('student_id', $student->id)
            ->latest('period_end')
            ->latest('id')
            ->first();

        $monthlyDebt = TahfizhDebt::query()
            ->where('student_id', $student->id)
            ->where('period_type', TahfizhDebt::PERIOD_MONTHLY)
            ->whereDate('period_start', now()->startOfMonth()->toDateString())
            ->whereDate('period_end', now()->endOfMonth()->toDateString())
            ->first();

        $recentRecords = HafalanRecord::query()
            ->with(['teacher', 'startSurah', 'endSurah'])
            ->where('student_id', $student->id)
            ->latest('record_date')
            ->latest('id')
            ->limit(10)
            ->get();

        return [
            'student' => $student->load(['school', 'classRoom']),
            'period_start' => $dateFrom,
            'period_end' => $dateUntil,
            'active_target' => $activeTarget,
            'total_records' => $totalRecords,
            'total_lines' => $totalLines,
            'latest_record' => $latestRecord,
            'latest_debt' => $latestDebt,
            'monthly_debt' => $monthlyDebt,
            'recent_records' => $recentRecords,
            'target_daily_lines' => $activeTarget?->daily_target_lines ?? 0,
            'target_weekly_lines' => $activeTarget?->weekly_target_lines ?? 0,
            'target_monthly_lines' => $activeTarget?->monthly_target_lines ?? 0,
            'current_debt_lines' => $latestDebt?->cumulative_debt_lines ?? 0,
            'monthly_status' => $monthlyDebt?->status ?? TahfizhDebt::STATUS_NO_TARGET,
        ];
    }

    public function monthlyRows(Student $student, ?string $month = null)
    {
        $date = $month
            ? Carbon::createFromFormat('Y-m', $month)->startOfMonth()
            : now()->startOfMonth();

        $periodStart = $date->copy()->startOfMonth();
        $periodEnd = $date->copy()->endOfMonth();

        $records = HafalanRecord::query()
            ->with(['teacher', 'startSurah', 'endSurah'])
            ->where('student_id', $student->id)
            ->whereBetween('record_date', [
                $periodStart->toDateString(),
                $periodEnd->toDateString(),
            ])
            ->orderBy('record_date')
            ->orderBy('id')
            ->get();

        $debt = TahfizhDebt::query()
            ->where('student_id', $student->id)
            ->where('period_type', TahfizhDebt::PERIOD_MONTHLY)
            ->whereDate('period_start', $periodStart->toDateString())
            ->whereDate('period_end', $periodEnd->toDateString())
            ->first();

        return [
            'period_start' => $periodStart,
            'period_end' => $periodEnd,
            'month' => $periodStart->format('Y-m'),
            'records' => $records,
            'debt' => $debt,
            'total_lines' => (int) $records->sum('total_lines'),
            'total_records' => $records->count(),
        ];
    }
}
