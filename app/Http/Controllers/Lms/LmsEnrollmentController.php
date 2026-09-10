<?php

namespace App\Http\Controllers\Lms;

use App\Http\Controllers\Controller;
use App\Http\Requests\Lms\StoreLmsEnrollmentRequest;
use App\Models\ClassRoom;
use App\Models\LmsCourse;
use App\Models\LmsCourseEnrollment;
use App\Models\Student;
use App\Services\Lms\LmsAccessService;
use App\Services\Lms\LmsEnrollmentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LmsEnrollmentController extends Controller
{
    public function __construct(
        private readonly LmsEnrollmentService $enrollmentService,
        private readonly LmsAccessService $accessService,
    ) {
        //
    }

    public function index(Request $request): View
    {
        $courseId = $request->input('course_id');
        $course = LmsCourse::findOrFail($courseId);

        if (! $this->accessService->canViewCourse(Auth::user(), $course)) {
            abort(403);
        }

        $enrollments = LmsCourseEnrollment::with(['student'])
            ->where('course_id', $courseId)
            ->get();

        $students = Student::where('is_active', true)->get();
        $classRooms = ClassRoom::all();

        return view('lms.enrollments.index', compact('course', 'enrollments', 'students', 'classRooms'));
    }

    public function store(StoreLmsEnrollmentRequest $request): RedirectResponse
    {
        $course = LmsCourse::findOrFail($request->input('course_id'));
        if (! $this->accessService->canManageCourse(Auth::user(), $course)) {
            abort(403);
        }

        $type = $request->input('enrollment_type');
        if ($type === 'student') {
            $this->enrollmentService->enrollStudent($course->id, $request->input('student_id'));
            $msg = 'Santri berhasil didaftarkan ke kelas.';
        } else {
            $count = $this->enrollmentService->enrollClassRoom($course->id, $request->input('class_room_id'));
            $msg = "{$count} santri dari kelas berhasil didaftarkan.";
        }

        return redirect()->route('lms.enrollments.index', ['course_id' => $course->id])
            ->with('success', $msg);
    }

    public function destroy(LmsCourseEnrollment $enrollment): RedirectResponse
    {
        $course = $enrollment->course;
        if (! $this->accessService->canManageCourse(Auth::user(), $course)) {
            abort(403);
        }

        $courseId = $enrollment->course_id;
        $this->enrollmentService->unenrollStudent($courseId, $enrollment->student_id);

        return redirect()->route('lms.enrollments.index', ['course_id' => $courseId])
            ->with('success', 'Pendaftaran santri berhasil dibatalkan.');
    }
}
