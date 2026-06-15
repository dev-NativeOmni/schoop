<?php

namespace App\Http\Controllers\Lms;

use App\Http\Controllers\Controller;
use App\Http\Requests\Lms\StoreLmsCourseRequest;
use App\Http\Requests\Lms\UpdateLmsCourseRequest;
use App\Models\LmsCourse;
use App\Models\TeacherProfile;
use App\Services\Lms\LmsCourseService;
use App\Services\Lms\LmsAccessService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LmsCourseController extends Controller
{
    public function __construct(
        private readonly LmsCourseService $courseService,
        private readonly LmsAccessService $accessService,
    ) {
        //
    }

    public function index(Request $request): View
    {
        $courses = LmsCourse::with(['instructors.user'])->orderBy('sort_order')->get();
        return view('lms.courses.index', compact('courses'));
    }

    public function create(): View
    {
        $this->authorizeAccess();

        $teachers = TeacherProfile::with('user')->where('is_active', true)->get();
        return view('lms.courses.create', compact('teachers'));
    }

    public function store(StoreLmsCourseRequest $request): RedirectResponse
    {
        $this->authorizeAccess();

        $course = $this->courseService->createCourse($request->validated(), Auth::id());

        return redirect()->route('lms.courses.show', $course->id)
            ->with('success', "Kelas '{$course->title}' berhasil dibuat.");
    }

    public function show(LmsCourse $course): View
    {
        if (!$this->accessService->canViewCourse(Auth::user(), $course)) {
            abort(403);
        }

        $course->load(['modules.lessons.assignment', 'modules.lessons.quiz', 'instructors.user']);
        return view('lms.courses.show', compact('course'));
    }

    public function edit(LmsCourse $course): View
    {
        if (!$this->accessService->canManageCourse(Auth::user(), $course)) {
            abort(403);
        }

        $teachers = TeacherProfile::with('user')->where('is_active', true)->get();
        $currentTeacherIds = $course->instructors()->pluck('teacher_profiles.id')->toArray();
        $primaryTeacher = $course->instructors()->wherePivot('is_primary', true)->first();
        $primaryTeacherId = $primaryTeacher ? $primaryTeacher->id : null;

        return view('lms.courses.edit', compact('course', 'teachers', 'currentTeacherIds', 'primaryTeacherId'));
    }

    public function update(UpdateLmsCourseRequest $request, LmsCourse $course): RedirectResponse
    {
        if (!$this->accessService->canManageCourse(Auth::user(), $course)) {
            abort(403);
        }

        $this->courseService->updateCourse($course, $request->validated(), Auth::id());

        return redirect()->route('lms.courses.show', $course->id)
            ->with('success', "Kelas '{$course->title}' berhasil diperbarui.");
    }

    public function destroy(LmsCourse $course): RedirectResponse
    {
        if (!$this->accessService->canManageCourse(Auth::user(), $course)) {
            abort(403);
        }

        $this->courseService->deleteCourse($course);

        return redirect()->route('lms.courses.index')
            ->with('success', "Kelas berhasil dihapus.");
    }

    private function authorizeAccess(): void
    {
        if (!$this->accessService->canCreateCourse(Auth::user())) {
            abort(403, 'Anda tidak diizinkan melakukan aksi ini.');
        }
    }
}
