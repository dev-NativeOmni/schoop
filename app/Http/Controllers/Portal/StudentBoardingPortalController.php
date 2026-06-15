<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\BoardingRollCallRecord;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentBoardingPortalController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user();
        abort_unless($user->isStudent(), 403);

        $student = $user->studentProfile;
        abort_unless($student !== null, 403);

        $student->load(['activeBoardingAssignment.dormitory', 'activeBoardingAssignment.room', 'activeBoardingAssignment.bed']);
        $assignment = $student->activeBoardingAssignment;

        $leaveRequests = $student->boardingLeaveRequests()
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        $healthLogs = $student->boardingHealthLogs()
            ->orderByDesc('logged_at')
            ->limit(10)
            ->get();

        $disciplineLogs = $student->boardingDisciplineLogs()
            ->orderByDesc('logged_at')
            ->limit(10)
            ->get();

        $rollCallRecords = BoardingRollCallRecord::query()
            ->where('student_id', $student->id)
            ->with('session')
            ->orderByDesc('recorded_at')
            ->limit(10)
            ->get();

        return view('portal.student.boarding', compact(
            'student',
            'assignment',
            'leaveRequests',
            'healthLogs',
            'disciplineLogs',
            'rollCallRecords'
        ));
    }
}
