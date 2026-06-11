<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Http\Requests\Attendance\AttendanceReportFilterRequest;
use App\Models\Student;
use App\Services\Attendance\AttendanceAccessService;
use App\Services\Attendance\AttendanceReportService;
use Illuminate\View\View;

class ParentAttendancePortalController extends Controller
{
    public function index(
        AttendanceReportFilterRequest $request,
        AttendanceAccessService $accessService,
        AttendanceReportService $reportService
    ): View {
        abort_unless($accessService->isParent($request->user()), 403);

        $students = $accessService
            ->applyStudentScope(Student::query()->orderBy('full_name'), $request->user())
            ->get();

        $selectedStudent = null;
        $snapshot = null;

        if ($students->isNotEmpty()) {
            $selectedStudent = $students->firstWhere('id', (int) $request->input('student_id'))
                ?? $students->first();

            $snapshot = $reportService->studentSnapshot($selectedStudent, $request->validated());
        }

        return view('portal.parent.attendance', compact(
            'students',
            'selectedStudent',
            'snapshot'
        ));
    }
}
