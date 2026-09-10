<?php

namespace App\Http\Controllers\Lms;

use App\Http\Controllers\Controller;
use App\Http\Requests\Lms\StoreLmsLessonRequest;
use App\Http\Requests\Lms\UpdateLmsLessonRequest;
use App\Models\LmsCourse;
use App\Models\LmsLesson;
use App\Services\Lms\LmsAccessService;
use App\Services\Lms\LmsLessonService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LmsLessonController extends Controller
{
    public function __construct(
        private readonly LmsLessonService $lessonService,
        private readonly LmsAccessService $accessService,
    ) {
        //
    }

    public function show(LmsLesson $lesson): View
    {
        if (! $this->accessService->canViewLesson(Auth::user(), $lesson)) {
            abort(403);
        }

        $lesson->load(['course', 'module', 'resources', 'assignment', 'quiz']);

        return view('lms.lessons.show', compact('lesson'));
    }

    public function store(StoreLmsLessonRequest $request): RedirectResponse
    {
        $course = LmsCourse::findOrFail($request->input('course_id'));
        if (! $this->accessService->canManageCourse(Auth::user(), $course)) {
            abort(403);
        }

        $lesson = $this->lessonService->createLesson($request->validated(), Auth::id());

        return redirect()->route('lms.courses.show', $course->id)
            ->with('success', "Materi '{$lesson->title}' berhasil ditambahkan.");
    }

    public function update(UpdateLmsLessonRequest $request, LmsLesson $lesson): RedirectResponse
    {
        if (! $this->accessService->canManageLesson(Auth::user(), $lesson)) {
            abort(403);
        }

        $this->lessonService->updateLesson($lesson, $request->validated());

        return redirect()->route('lms.lessons.show', $lesson->id)
            ->with('success', "Materi '{$lesson->title}' berhasil diperbarui.");
    }

    public function destroy(LmsLesson $lesson): RedirectResponse
    {
        if (! $this->accessService->canManageLesson(Auth::user(), $lesson)) {
            abort(403);
        }

        $courseId = $lesson->course_id;
        $this->lessonService->deleteLesson($lesson);

        return redirect()->route('lms.courses.show', $courseId)
            ->with('success', 'Materi berhasil dihapus.');
    }

    public function reorder(Request $request): JsonResponse
    {
        $request->validate([
            'lesson_ids' => 'required|array',
            'lesson_ids.*' => 'exists:lms_lessons,id',
        ]);

        $ids = $request->input('lesson_ids');
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'Empty array']);
        }

        $firstLesson = LmsLesson::findOrFail($ids[0]);
        if (! $this->accessService->canManageCourse(Auth::user(), $firstLesson->course)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $this->lessonService->reorderLessons($ids);

        return response()->json(['success' => true, 'message' => 'Lessons reordered successfully.']);
    }
}
