<?php

namespace App\Http\Controllers\Lms;

use App\Http\Controllers\Controller;
use App\Http\Requests\Lms\StoreLmsAssignmentRequest;
use App\Http\Requests\Lms\UpdateLmsAssignmentRequest;
use App\Models\LmsCourse;
use App\Models\LmsAssignment;
use App\Services\Lms\LmsAssignmentService;
use App\Services\Lms\LmsAccessService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class LmsAssignmentController extends Controller
{
    public function __construct(
        private readonly LmsAssignmentService $assignmentService,
        private readonly LmsAccessService $accessService,
    ) {
        //
    }

    public function store(StoreLmsAssignmentRequest $request): RedirectResponse
    {
        $course = LmsCourse::findOrFail($request->input('course_id'));
        if (!$this->accessService->canManageCourse(Auth::user(), $course)) {
            abort(403);
        }

        $assignment = $this->assignmentService->createAssignment($request->validated());

        return redirect()->route('lms.lessons.show', $assignment->lesson_id)
            ->with('success', "Tugas '{$assignment->title}' berhasil dikonfigurasi.");
    }

    public function update(UpdateLmsAssignmentRequest $request, LmsAssignment $assignment): RedirectResponse
    {
        if (!$this->accessService->canManageCourse(Auth::user(), $assignment->course)) {
            abort(403);
        }

        $this->assignmentService->updateAssignment($assignment, $request->validated());

        return redirect()->route('lms.lessons.show', $assignment->lesson_id)
            ->with('success', "Tugas '{$assignment->title}' berhasil diperbarui.");
    }

    public function destroy(LmsAssignment $assignment): RedirectResponse
    {
        if (!$this->accessService->canManageCourse(Auth::user(), $assignment->course)) {
            abort(403);
        }

        $lessonId = $assignment->lesson_id;
        $assignment->delete();

        return redirect()->route('lms.lessons.show', $lessonId)
            ->with('success', 'Tugas berhasil dihapus.');
    }
}
