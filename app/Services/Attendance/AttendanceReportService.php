<?php

namespace App\Services\Attendance;

use App\Models\AttendanceRecord;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class AttendanceReportService
{
    public function dashboard(array $filters = []): array
    {
        $startDate = Carbon::parse($filters['start_date'] ?? now()->startOfMonth()->toDateString())->toDateString();
        $endDate = Carbon::parse($filters['end_date'] ?? now()->toDateString())->toDateString();

        $recordQuery = AttendanceRecord::query()
            ->with(['student', 'session'])
            ->whereBetween('attendance_date', [$startDate, $endDate]);

        if (! empty($filters['class_room_id'])) {
            $recordQuery->whereHas('student', function (Builder $query) use ($filters): void {
                $query->where('class_room_id', $filters['class_room_id']);
            });
        }

        if (! empty($filters['student_id'])) {
            $recordQuery->where('student_id', $filters['student_id']);
        }

        $records = $recordQuery->get();

        return [
            'period' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
            ],
            'summary' => [
                'total_records' => $records->count(),
                'present' => $records->where('status', 'present')->count(),
                'late' => $records->where('status', 'late')->count(),
                'sick' => $records->where('status', 'sick')->count(),
                'permission' => $records->where('status', 'permission')->count(),
                'absent' => $records->where('status', 'absent')->count(),
            ],
            'by_student' => $this->groupByStudent($records),
            'records' => $records->sortByDesc('attendance_date')->values(),
        ];
    }

    public function studentSnapshot(Student $student, array $filters = []): array
    {
        $startDate = Carbon::parse($filters['start_date'] ?? now()->startOfMonth()->toDateString())->toDateString();
        $endDate = Carbon::parse($filters['end_date'] ?? now()->toDateString())->toDateString();

        $records = AttendanceRecord::query()
            ->with('session')
            ->where('student_id', $student->id)
            ->whereBetween('attendance_date', [$startDate, $endDate])
            ->orderByDesc('attendance_date')
            ->get();

        return [
            'student' => $student,
            'period' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
            ],
            'summary' => [
                'total_records' => $records->count(),
                'present' => $records->where('status', 'present')->count(),
                'late' => $records->where('status', 'late')->count(),
                'sick' => $records->where('status', 'sick')->count(),
                'permission' => $records->where('status', 'permission')->count(),
                'absent' => $records->where('status', 'absent')->count(),
            ],
            'records' => $records,
        ];
    }

    private function groupByStudent(Collection $records): Collection
    {
        return $records
            ->groupBy('student_id')
            ->map(function (Collection $studentRecords): array {
                $first = $studentRecords->first();

                return [
                    'student' => $first?->student,
                    'total' => $studentRecords->count(),
                    'present' => $studentRecords->where('status', 'present')->count(),
                    'late' => $studentRecords->where('status', 'late')->count(),
                    'sick' => $studentRecords->where('status', 'sick')->count(),
                    'permission' => $studentRecords->where('status', 'permission')->count(),
                    'absent' => $studentRecords->where('status', 'absent')->count(),
                ];
            })
            ->values();
    }
}
