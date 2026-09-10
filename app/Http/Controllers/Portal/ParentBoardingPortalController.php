<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\BoardingRollCallRecord;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ParentBoardingPortalController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user();
        abort_unless($user->isParent(), 403);

        $parentProfile = $user->parentProfile;
        $students = $parentProfile ? $parentProfile->students()->with(['activeBoardingAssignment.dormitory', 'activeBoardingAssignment.room', 'activeBoardingAssignment.bed'])->get() : collect();

        $selectedStudentId = $request->input('student_id', $students->first()?->id);
        $student = $students->firstWhere('id', $selectedStudentId);

        $assignment = null;
        $leaveRequests = collect();
        $healthLogs = collect();
        $disciplineLogs = collect();
        $rollCallRecords = collect();

        if ($student) {
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
        }

        return view('portal.parent.boarding', compact(
            'students',
            'student',
            'assignment',
            'leaveRequests',
            'healthLogs',
            'disciplineLogs',
            'rollCallRecords'
        ));
    }
}
