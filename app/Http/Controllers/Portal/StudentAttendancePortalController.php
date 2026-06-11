<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Http\Requests\Attendance\AttendanceReportFilterRequest;
use App\Models\Student;
use App\Services\Attendance\AttendanceAccessService;
use App\Services\Attendance\AttendanceReportService;
use Illuminate\View\View;

class StudentAttendancePortalController extends Controller
{
    public function index(
        AttendanceReportFilterRequest $request,
        AttendanceAccessService $accessService,
        AttendanceReportService $reportService
    ): View {
        abort_unless($accessService->isStudent($request->user()), 403);

        $student = Student::query()
            ->where('user_id', $request->user()->id)
            ->first();

        if (! $student) {
            return view('portal.student.attendance', [
                'student' => null,
                'snapshot' => null,
            ]);
        }

        $snapshot = $reportService->studentSnapshot($student, $request->validated());

        return view('portal.student.attendance', compact('student', 'snapshot'));
    }
}
