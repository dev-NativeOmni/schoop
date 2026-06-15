<?php

namespace App\Http\Controllers\Lms;

use App\Http\Controllers\Controller;
use App\Models\LmsQuizAttempt;
use App\Services\Lms\LmsAccessService;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LmsQuizAttemptController extends Controller
{
    public function __construct(
        private readonly LmsAccessService $accessService,
    ) {
        //
    }

    public function show(LmsQuizAttempt $attempt): View
    {
        if (!$this->accessService->canManageCourse(Auth::user(), $attempt->quiz->course)) {
            abort(403);
        }

        $attempt->load(['student', 'quiz', 'answers.question']);
        return view('lms.attempts.show', compact('attempt'));
    }
}
