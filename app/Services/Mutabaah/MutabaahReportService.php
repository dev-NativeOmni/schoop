<?php

namespace App\Services\Mutabaah;

use App\Models\MutabaahActivity;
use App\Models\MutabaahRecord;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class MutabaahReportService
{
    public function dashboard(array $filters = []): array
    {
        $startDate = Carbon::parse($filters['start_date'] ?? now()->startOfWeek()->toDateString())->startOfDay();
        $endDate = Carbon::parse($filters['end_date'] ?? now()->endOfWeek()->toDateString())->endOfDay();

        $recordQuery = MutabaahRecord::query()
            ->with(['student.user', 'activity.category'])
            ->whereBetween('record_date', [
                $startDate->toDateString(),
                $endDate->toDateString(),
            ]);

        if (! empty($filters['class_room_id'])) {
            $recordQuery->whereHas('student', function (Builder $query) use ($filters): void {
                $query->where('class_room_id', $filters['class_room_id']);
            });
        }

        if (! empty($filters['student_id'])) {
            $recordQuery->where('student_id', $filters['student_id']);
        }

        $records = $recordQuery->get();

        $totalRecords = $records->count();
        $doneRecords = $records->where('status', 'done')->count();
        $notDoneRecords = $records->where('status', 'not_done')->count();
        $excusedRecords = $records->where('status', 'excused')->count();

        $completionRate = $totalRecords > 0
            ? round(($doneRecords / $totalRecords) * 100, 2)
            : 0;

        return [
            'period' => [
                'start_date' => $startDate->toDateString(),
                'end_date' => $endDate->toDateString(),
            ],
            'summary' => [
                'total_records' => $totalRecords,
                'done_records' => $doneRecords,
                'not_done_records' => $notDoneRecords,
                'excused_records' => $excusedRecords,
                'completion_rate' => $completionRate,
            ],
            'by_student' => $this->groupByStudent($records),
            'by_activity' => $this->groupByActivity($records),
        ];
    }

    public function studentSnapshot(Student $student, array $filters = []): array
    {
        $startDate = Carbon::parse($filters['start_date'] ?? now()->startOfWeek()->toDateString())->startOfDay();
        $endDate = Carbon::parse($filters['end_date'] ?? now()->endOfWeek()->toDateString())->endOfDay();

        $activities = MutabaahActivity::query()
            ->with('category')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $records = MutabaahRecord::query()
            ->with(['activity.category', 'submittedBy'])
            ->where('student_id', $student->id)
            ->whereBetween('record_date', [
                $startDate->toDateString(),
                $endDate->toDateString(),
            ])
            ->orderByDesc('record_date')
            ->get();

        $totalRecords = $records->count();
        $doneRecords = $records->where('status', 'done')->count();

        // Group records by date for easy template rendering
        $recordsByDate = $records->groupBy(fn ($r) => $r->record_date->toDateString());

        return [
            'student' => $student,
            'period' => [
                'start_date' => $startDate->toDateString(),
                'end_date' => $endDate->toDateString(),
            ],
            'activities' => $activities,
            'records' => $records,
            'records_by_date' => $recordsByDate,
            'summary' => [
                'total_records' => $totalRecords,
                'done_records' => $doneRecords,
                'completion_rate' => $totalRecords > 0
                    ? round(($doneRecords / $totalRecords) * 100, 2)
                    : 0,
            ],
        ];
    }

    public function weeklyGrouped(array $filters = []): array
    {
        $year = (int) ($filters['year'] ?? now()->year);
        $month = (int) ($filters['month'] ?? now()->month);

        $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        $records = MutabaahRecord::query()
            ->with(['student.user', 'activity'])
            ->whereBetween('record_date', [$startDate->toDateString(), $endDate->toDateString()])
            ->when(! empty($filters['class_room_id']), function (Builder $q) use ($filters): void {
                $q->whereHas('student', fn (Builder $sq) => $sq->where('class_room_id', $filters['class_room_id']));
            })
            ->get();

        return [
            'period' => [
                'year' => $year,
                'month' => $month,
                'start_date' => $startDate->toDateString(),
                'end_date' => $endDate->toDateString(),
            ],
            'summary' => [
                'total' => $records->count(),
                'done' => $records->where('status', 'done')->count(),
                'rate' => $records->count() > 0
                    ? round($records->where('status', 'done')->count() / $records->count() * 100, 1)
                    : 0,
            ],
            'by_student' => $this->groupByStudent($records),
        ];
    }

    private function groupByStudent(Collection $records): Collection
    {
        return $records
            ->groupBy('student_id')
            ->map(function (Collection $studentRecords): array {
                $first = $studentRecords->first();
                $total = $studentRecords->count();
                $done = $studentRecords->where('status', 'done')->count();

                return [
                    'student' => $first?->student,
                    'total' => $total,
                    'done' => $done,
                    'not_done' => $studentRecords->where('status', 'not_done')->count(),
                    'excused' => $studentRecords->where('status', 'excused')->count(),
                    'completion_rate' => $total > 0 ? round(($done / $total) * 100, 2) : 0,
                ];
            })
            ->sortByDesc('completion_rate')
            ->values();
    }

    private function groupByActivity(Collection $records): Collection
    {
        return $records
            ->groupBy('mutabaah_activity_id')
            ->map(function (Collection $activityRecords): array {
                $first = $activityRecords->first();
                $total = $activityRecords->count();
                $done = $activityRecords->where('status', 'done')->count();

                return [
                    'activity' => $first?->activity,
                    'total' => $total,
                    'done' => $done,
                    'not_done' => $activityRecords->where('status', 'not_done')->count(),
                    'excused' => $activityRecords->where('status', 'excused')->count(),
                    'completion_rate' => $total > 0 ? round(($done / $total) * 100, 2) : 0,
                ];
            })
            ->sortByDesc('completion_rate')
            ->values();
    }
}
