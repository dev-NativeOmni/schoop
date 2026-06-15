<?php

namespace App\Services\Boarding;

use App\Models\BoardingDormitory;
use App\Models\BoardingRoom;
use App\Models\BoardingBed;
use App\Models\BoardingLeaveRequest;
use App\Models\BoardingHealthLog;
use App\Models\BoardingDisciplineLog;
use App\Models\BoardingRollCallRecord;
use App\Models\BoardingStudentAssignment;
use Illuminate\Support\Facades\DB;

class BoardingReportService
{
    /**
     * Get overview dashboard stats for a school.
     */
    public function getDashboardOverview(?int $schoolId): array
    {
        $dormQuery = BoardingDormitory::query()->where('is_active', true);
        if ($schoolId) {
            $dormQuery->where('school_id', $schoolId);
        }
        $dormitories = $dormQuery->get();
        $dormitoryIds = $dormitories->pluck('id');

        // Rooms
        $roomsCount = BoardingRoom::query()->whereIn('boarding_dormitory_id', $dormitoryIds)->count();

        // Beds
        $beds = BoardingBed::query()
            ->whereHas('room', function ($q) use ($dormitoryIds) {
                $q->whereIn('boarding_dormitory_id', $dormitoryIds);
            })->get();

        $totalBeds = $beds->count();
        $occupiedBeds = $beds->where('status', 'occupied')->count();
        $availableBeds = $beds->where('status', 'available')->count();
        $maintenanceBeds = $beds->where('status', 'maintenance')->count();

        $occupancyRate = $totalBeds > 0 ? round(($occupiedBeds / $totalBeds) * 100, 1) : 0;

        // Active leaves (status = approved, meaning they are currently away)
        $activeLeaves = BoardingLeaveRequest::query()
            ->where('status', 'approved')
            ->whereHas('student', function ($q) use ($schoolId) {
                if ($schoolId) {
                    $q->where('school_id', $schoolId);
                }
            })->count();

        // Recent Health Logs
        $recentHealthLogs = BoardingHealthLog::query()
            ->whereHas('student', function ($q) use ($schoolId) {
                if ($schoolId) {
                    $q->where('school_id', $schoolId);
                }
            })
            ->with(['student', 'recordedBy'])
            ->orderBy('logged_at', 'desc')
            ->limit(5)
            ->get();

        // Recent Discipline Logs
        $recentDisciplineLogs = BoardingDisciplineLog::query()
            ->whereHas('student', function ($q) use ($schoolId) {
                if ($schoolId) {
                    $q->where('school_id', $schoolId);
                }
            })
            ->with(['student', 'recordedBy'])
            ->orderBy('logged_at', 'desc')
            ->limit(5)
            ->get();

        return [
            'total_dormitories' => $dormitories->count(),
            'total_rooms' => $roomsCount,
            'total_beds' => $totalBeds,
            'occupied_beds' => $occupiedBeds,
            'available_beds' => $availableBeds,
            'maintenance_beds' => $maintenanceBeds,
            'occupancy_rate' => $occupancyRate,
            'active_leaves' => $activeLeaves,
            'recent_health_logs' => $recentHealthLogs,
            'recent_discipline_logs' => $recentDisciplineLogs,
        ];
    }

    /**
     * Get a discipline leaderboard/summary for a school.
     */
    public function getDisciplineSummary(?int $schoolId, int $limit = 10): array
    {
        // Get top students with violations (negative points or general warning count)
        $topViolators = BoardingDisciplineLog::query()
            ->select('student_id', DB::raw('SUM(points) as total_points'), DB::raw('COUNT(id) as violations_count'))
            ->where('type', 'violation')
            ->whereHas('student', function ($q) use ($schoolId) {
                if ($schoolId) {
                    $q->where('school_id', $schoolId);
                }
            })
            ->groupBy('student_id')
            ->orderBy('total_points', 'asc') // Negative points mean more violations
            ->with('student')
            ->limit($limit)
            ->get();

        // Get top students with achievements (positive points)
        $topAchievers = BoardingDisciplineLog::query()
            ->select('student_id', DB::raw('SUM(points) as total_points'), DB::raw('COUNT(id) as achievements_count'))
            ->where('type', 'achievement')
            ->whereHas('student', function ($q) use ($schoolId) {
                if ($schoolId) {
                    $q->where('school_id', $schoolId);
                }
            })
            ->groupBy('student_id')
            ->orderBy('total_points', 'desc')
            ->with('student')
            ->limit($limit)
            ->get();

        return [
            'top_violators' => $topViolators,
            'top_achievers' => $topAchievers,
        ];
    }

    /**
     * Get roll call statistics for a date range.
     */
    public function getRollCallStats(?int $schoolId, string $startDate, string $endDate): array
    {
        $records = BoardingRollCallRecord::query()
            ->whereHas('session', function ($q) use ($schoolId, $startDate, $endDate) {
                $q->whereBetween('session_date', [$startDate, $endDate]);
                if ($schoolId) {
                    $q->whereHas('dormitory', function ($dq) use ($schoolId) {
                        $dq->where('school_id', $schoolId);
                    });
                }
            })->get();

        $total = $records->count();
        if ($total === 0) {
            return [
                'total_records' => 0,
                'present' => 0,
                'late' => 0,
                'permission' => 0,
                'sick' => 0,
                'absent' => 0,
                'attendance_rate' => 0,
            ];
        }

        $present = $records->where('status', 'present')->count();
        $late = $records->where('status', 'late')->count();
        $permission = $records->where('status', 'permission')->count();
        $sick = $records->where('status', 'sick')->count();
        $absent = $records->where('status', 'absent')->count();

        // Attendance rate is present + late + permission + sick divided by total, or just present + late
        $attendanceRate = round((($present + $late) / $total) * 100, 1);

        return [
            'total_records' => $total,
            'present' => $present,
            'late' => $late,
            'permission' => $permission,
            'sick' => $sick,
            'absent' => $absent,
            'attendance_rate' => $attendanceRate,
        ];
    }
}
