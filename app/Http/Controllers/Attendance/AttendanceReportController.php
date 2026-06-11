<?php

namespace App\Http\Controllers\Attendance;

use App\Http\Controllers\Controller;
use App\Http\Requests\Attendance\AttendanceReportFilterRequest;
use App\Models\ClassRoom;
use App\Models\Student;
use App\Services\Attendance\AttendanceAccessService;
use App\Services\Attendance\AttendanceReportService;
use Illuminate\View\View;

class AttendanceReportController extends Controller
{
    public function dashboard(
        AttendanceReportFilterRequest $request,
        AttendanceAccessService $accessService,
        AttendanceReportService $reportService
    ): View {
        abort_unless($accessService->canViewInternalReport($request->user()), 403);

        $filters = $request->validated();
        $report = $reportService->dashboard($filters);

        $classRooms = ClassRoom::query()
            ->orderBy('name')
            ->get();

        $students = $accessService
            ->applyStudentScope(Student::query()->orderBy('full_name'), $request->user())
            ->limit(300)
            ->get();

        return view('attendance.reports.dashboard', compact(
            'report',
            'filters',
            'classRooms',
            'students'
        ));
    }
}
