<?php

namespace App\Http\Controllers\Lms;

use App\Http\Controllers\Controller;
use App\Models\LmsAssignment;
use App\Models\LmsAssignmentSubmission;
use App\Services\Lms\LmsAssignmentService;
use App\Services\Lms\LmsAccessService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LmsAssignmentSubmissionController extends Controller
{
    public function __construct(
        private readonly LmsAssignmentService $assignmentService,
        private readonly LmsAccessService $accessService,
    ) {
        //
    }

    public function index(Request $request): View
    {
        $assignmentId = $request->input('assignment_id');
        $assignment = LmsAssignment::findOrFail($assignmentId);

        if (!$this->accessService->canManageCourse(Auth::user(), $assignment->course)) {
            abort(403);
        }

        $submissions = LmsAssignmentSubmission::with(['student'])
            ->where('assignment_id', $assignmentId)
            ->get();

        return view('lms.submissions.index', compact('assignment', 'submissions'));
    }

    public function show(LmsAssignmentSubmission $submission): View
    {
        if (!$this->accessService->canManageCourse(Auth::user(), $submission->assignment->course)) {
            abort(403);
        }

        $submission->load(['student', 'assignment']);
        return view('lms.submissions.show', compact('submission'));
    }

    public function grade(Request $request, LmsAssignmentSubmission $submission): RedirectResponse
    {
        if (!$this->accessService->canManageCourse(Auth::user(), $submission->assignment->course)) {
            abort(403);
        }

        $request->validate([
            'score' => 'required|numeric|min:0|max:' . $submission->assignment->max_score,
            'teacher_feedback' => 'nullable|string',
        ]);

        $this->assignmentService->gradeSubmission(
            $submission->id,
            (float) $request->input('score'),
            $request->input('teacher_feedback'),
            Auth::id()
        );

        return redirect()->route('lms.submissions.index', ['assignment_id' => $submission->assignment_id])
            ->with('success', 'Tugas berhasil dinilai.');
    }
}
