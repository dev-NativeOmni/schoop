<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\LmsCourse;
use App\Models\LmsLessonProgress;
use App\Models\Student;
use App\Services\Lms\LmsAccessService;
use App\Services\Lms\LmsReportService;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ParentLmsPortalController extends Controller
{
    public function __construct(
        private readonly LmsAccessService $accessService,
        private readonly LmsReportService $reportService,
    ) {
        //
    }

    public function index(): View
    {
        $parent = Auth::user()->parentProfile;
        if (! $parent) {
            abort(403, 'Profil wali murid tidak ditemukan.');
        }

        $children = $parent->students()->with(['courses' => function ($query) {
            $query->where('visibility', 'published');
        }])->get();

        return view('portal.parent.lms.index', compact('children'));
    }

    public function showChildProgress(Student $student, LmsCourse $course): View
    {
        $user = Auth::user();
        if (! $this->accessService->canViewChildProgress($user, $student)) {
            abort(403, 'Anda tidak diizinkan memantau progres santri ini.');
        }

        if (! $this->accessService->canViewCourse($user, $course)) {
            abort(403, 'Santri tidak terdaftar pada kelas ini.');
        }

        $summary = $this->reportService->getStudentCourseSummary($student->id, $course->id);

        $course->load(['modules.lessons' => function ($query) {
            $query->where('visibility', 'published')->orderBy('sort_order');
        }]);

        // Load student lesson progress
        $completedLessonIds = LmsLessonProgress::where('student_id', $student->id)
            ->where('status', 'completed')
            ->pluck('lesson_id')
            ->toArray();

        return view('portal.parent.lms.child_progress', compact('student', 'course', 'summary', 'completedLessonIds'));
    }
}
