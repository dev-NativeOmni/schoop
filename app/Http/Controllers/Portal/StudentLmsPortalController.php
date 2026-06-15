<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Http\Requests\Lms\SubmitLmsAssignmentRequest;
use App\Http\Requests\Lms\SubmitLmsQuizAttemptRequest;
use App\Models\LmsCourse;
use App\Models\LmsLesson;
use App\Models\LmsAssignment;
use App\Models\LmsQuiz;
use App\Models\LmsQuizAttempt;
use App\Services\Lms\LmsAccessService;
use App\Services\Lms\LmsProgressService;
use App\Services\Lms\LmsAssignmentService;
use App\Services\Lms\LmsQuizService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class StudentLmsPortalController extends Controller
{
    public function __construct(
        private readonly LmsAccessService $accessService,
        private readonly LmsProgressService $progressService,
        private readonly LmsAssignmentService $assignmentService,
        private readonly LmsQuizService $quizService,
    ) {
        //
    }

    public function index(Request $request): View
    {
        $student = Auth::user()->studentProfile;
        if (!$student) {
            abort(403, 'Santri profile tidak ditemukan.');
        }

        $courses = $student->courses()
            ->where('visibility', 'published')
            ->get();

        return view('portal.student.lms.index', compact('courses'));
    }

    public function showCourse(LmsCourse $course): View
    {
        if (!$this->accessService->canViewCourse(Auth::user(), $course)) {
            abort(403);
        }

        $student = Auth::user()->studentProfile;
        $course->load(['modules.lessons' => function ($query) {
            $query->where('visibility', 'published')->orderBy('sort_order');
        }]);

        // Fetch completed lesson IDs for checkmarks
        $completedLessonIds = \App\Models\LmsLessonProgress::where('student_id', $student->id)
            ->where('status', 'completed')
            ->pluck('lesson_id')
            ->toArray();

        return view('portal.student.lms.course', compact('course', 'completedLessonIds'));
    }

    public function showLesson(LmsLesson $lesson): View
    {
        if (!$this->accessService->canViewLesson(Auth::user(), $lesson)) {
            abort(403);
        }

        $student = Auth::user()->studentProfile;
        
        // Mark lesson progress as in progress
        $this->progressService->markAsInProgress($student->id, $lesson->id);

        $lesson->load(['resources', 'assignment.submissions' => function ($query) use ($student) {
            $query->where('student_id', $student->id);
        }, 'quiz.attempts' => function ($query) use ($student) {
            $query->where('student_id', $student->id);
        }]);

        $progress = \App\Models\LmsLessonProgress::where('student_id', $student->id)
            ->where('lesson_id', $lesson->id)
            ->first();

        return view('portal.student.lms.lesson', compact('lesson', 'progress'));
    }

    public function completeLesson(LmsLesson $lesson): RedirectResponse
    {
        if (!$this->accessService->canViewLesson(Auth::user(), $lesson)) {
            abort(403);
        }

        if (in_array($lesson->lesson_type, ['assignment', 'quiz'], true)) {
            return redirect()->back()->with('error', 'Materi bertipe tugas atau kuis harus dikerjakan secara langsung untuk diselesaikan.');
        }

        $student = Auth::user()->studentProfile;
        $this->progressService->markAsComplete($student->id, $lesson->id);

        return redirect()->route('portal.student.lms.course.show', $lesson->course_id)
            ->with('success', "Materi '{$lesson->title}' ditandai selesai.");
    }

    public function submitAssignment(SubmitLmsAssignmentRequest $request, LmsAssignment $assignment): RedirectResponse
    {
        if (!$this->accessService->canSubmitAssignment(Auth::user(), $assignment)) {
            abort(403);
        }

        $student = Auth::user()->studentProfile;
        
        try {
            $this->assignmentService->submitAssignment(
                $assignment->id,
                $student->id,
                $request->validated(),
                $request->file('file')
            );
        } catch (\InvalidArgumentException $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('portal.student.lms.lesson.show', $assignment->lesson_id)
            ->with('success', 'Tugas berhasil dikumpulkan.');
    }

    public function startQuiz(LmsQuiz $quiz): RedirectResponse
    {
        if (!$this->accessService->canAttemptQuiz(Auth::user(), $quiz)) {
            abort(403);
        }

        $student = Auth::user()->studentProfile;
        
        try {
            $attempt = $this->quizService->startAttempt($quiz->id, $student->id);
        } catch (\RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->route('portal.student.lms.quiz.attempt.show', $attempt->id);
    }

    public function showQuizAttempt(LmsQuizAttempt $attempt): View
    {
        $student = Auth::user()->studentProfile;
        if (!$student || $attempt->student_id !== $student->id) {
            abort(403);
        }

        if ($attempt->status !== 'started') {
            return view('portal.student.lms.quiz_result', compact('attempt'));
        }

        // Load quiz questions without correct_answers (they are hidden in model definition)
        $attempt->load(['quiz.questions' => function ($query) {
            $query->orderBy('sort_order');
        }]);

        return view('portal.student.lms.quiz_attempt', compact('attempt'));
    }

    public function submitQuizAttempt(SubmitLmsQuizAttemptRequest $request, LmsQuizAttempt $attempt): RedirectResponse
    {
        $student = Auth::user()->studentProfile;
        if (!$student || $attempt->student_id !== $student->id) {
            abort(403);
        }

        try {
            $this->quizService->submitAttempt($attempt->id, $request->input('answers'));
        } catch (\RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->route('portal.student.lms.lesson.show', $attempt->quiz->lesson_id)
            ->with('success', 'Kuis berhasil dikerjakan.');
    }
}
