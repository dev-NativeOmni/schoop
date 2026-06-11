<?php

namespace App\Http\Controllers\Attendance;

use App\Http\Controllers\Controller;
use App\Http\Requests\Attendance\StoreManualAttendanceRecordRequest;
use App\Models\AttendanceRecord;
use App\Models\AttendanceSession;
use App\Models\Student;
use App\Services\Attendance\AttendanceAccessService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceManualRecordController extends Controller
{
    public function create(Request $request, AttendanceAccessService $accessService): View
    {
        abort_unless($accessService->canInputManual($request->user()), 403);

        $sessions = AttendanceSession::query()
            ->whereIn('status', ['active', 'closed'])
            ->orderByDesc('attendance_date')
            ->limit(50)
            ->get();

        $students = $accessService
            ->applyStudentScope(Student::query()->orderBy('full_name'), $request->user())
            ->limit(300)
            ->get();

        return view('attendance.manual.create', compact('sessions', 'students'));
    }

    public function store(
        StoreManualAttendanceRecordRequest $request,
        AttendanceAccessService $accessService
    ): RedirectResponse {
        $session = AttendanceSession::query()->findOrFail($request->validated('attendance_session_id'));
        $student = Student::query()->findOrFail($request->validated('student_id'));

        abort_unless($accessService->canViewStudent($request->user(), $student), 403);

        AttendanceRecord::query()->updateOrCreate(
            [
                'attendance_session_id' => $session->id,
                'student_id' => $student->id,
            ],
            [
                'school_id' => $student->school_id ?? $session->school_id,
                'attendance_date' => $session->attendance_date,
                'status' => $request->validated('status'),
                'check_in_at' => $request->validated('check_in_at'),
                'check_out_at' => $request->validated('check_out_at'),
                'source' => 'manual',
                'note' => $request->validated('note'),
                'updated_by' => $request->user()->id,
            ]
        );

        return back()->with('success', 'Presensi manual berhasil disimpan.');
    }
}
