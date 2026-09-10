<?php

namespace App\Http\Controllers\Lms;

use App\Http\Controllers\Controller;
use App\Http\Requests\Lms\StoreLmsQuizQuestionRequest;
use App\Models\LmsQuiz;
use App\Models\LmsQuizQuestion;
use App\Services\Lms\LmsAccessService;
use App\Services\Lms\LmsQuizService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class LmsQuizQuestionController extends Controller
{
    public function __construct(
        private readonly LmsQuizService $quizService,
        private readonly LmsAccessService $accessService,
    ) {
        //
    }

    public function store(StoreLmsQuizQuestionRequest $request, LmsQuiz $quiz): RedirectResponse
    {
        if (! $this->accessService->canManageCourse(Auth::user(), $quiz->course)) {
            abort(403);
        }

        $this->quizService->addQuestion($quiz->id, $request->validated());

        return redirect()->route('lms.lessons.show', $quiz->lesson_id)
            ->with('success', 'Pertanyaan kuis berhasil ditambahkan.');
    }

    public function update(StoreLmsQuizQuestionRequest $request, LmsQuiz $quiz, LmsQuizQuestion $question): RedirectResponse
    {
        if (! $this->accessService->canManageCourse(Auth::user(), $quiz->course)) {
            abort(403);
        }

        $this->quizService->updateQuestion($question, $request->validated());

        return redirect()->route('lms.lessons.show', $quiz->lesson_id)
            ->with('success', 'Pertanyaan kuis berhasil diperbarui.');
    }

    public function destroy(LmsQuiz $quiz, LmsQuizQuestion $question): RedirectResponse
    {
        if (! $this->accessService->canManageCourse(Auth::user(), $quiz->course)) {
            abort(403);
        }

        $this->quizService->deleteQuestion($question);

        return redirect()->route('lms.lessons.show', $quiz->lesson_id)
            ->with('success', 'Pertanyaan kuis berhasil dihapus.');
    }
}
