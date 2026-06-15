<?php

namespace App\Http\Controllers\Lms;

use App\Http\Controllers\Controller;
use App\Http\Requests\Lms\StoreLmsQuizRequest;
use App\Http\Requests\Lms\UpdateLmsQuizRequest;
use App\Models\LmsCourse;
use App\Models\LmsQuiz;
use App\Services\Lms\LmsQuizService;
use App\Services\Lms\LmsAccessService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class LmsQuizController extends Controller
{
    public function __construct(
        private readonly LmsQuizService $quizService,
        private readonly LmsAccessService $accessService,
    ) {
        //
    }

    public function store(StoreLmsQuizRequest $request): RedirectResponse
    {
        $course = LmsCourse::findOrFail($request->input('course_id'));
        if (!$this->accessService->canManageCourse(Auth::user(), $course)) {
            abort(403);
        }

        $quiz = $this->quizService->createQuiz($request->validated());

        return redirect()->route('lms.lessons.show', $quiz->lesson_id)
            ->with('success', "Kuis '{$quiz->title}' berhasil dikonfigurasi.");
    }

    public function update(UpdateLmsQuizRequest $request, LmsQuiz $quiz): RedirectResponse
    {
        if (!$this->accessService->canManageCourse(Auth::user(), $quiz->course)) {
            abort(403);
        }

        $this->quizService->updateQuiz($quiz, $request->validated());

        return redirect()->route('lms.lessons.show', $quiz->lesson_id)
            ->with('success', "Kuis '{$quiz->title}' berhasil diperbarui.");
    }

    public function destroy(LmsQuiz $quiz): RedirectResponse
    {
        if (!$this->accessService->canManageCourse(Auth::user(), $quiz->course)) {
            abort(403);
        }

        $lessonId = $quiz->lesson_id;
        $quiz->delete();

        return redirect()->route('lms.lessons.show', $lessonId)
            ->with('success', 'Kuis berhasil dihapus.');
    }
}
