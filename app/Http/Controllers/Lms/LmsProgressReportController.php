<?php

namespace App\Http\Controllers\Lms;

use App\Http\Controllers\Controller;
use App\Http\Requests\Lms\LmsProgressReportFilterRequest;
use App\Models\LmsCourse;
use App\Models\LmsCourseEnrollment;
use App\Models\ClassRoom;
use App\Services\Lms\LmsReportService;
use App\Services\Lms\LmsAccessService;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LmsProgressReportController extends Controller
{
    public function __construct(
        private readonly LmsReportService $reportService,
        private readonly LmsAccessService $accessService,
    ) {
        //
    }

    public function index(LmsProgressReportFilterRequest $request): View
    {
        $courses = LmsCourse::orderBy('sort_order')->get();
        $classRooms = ClassRoom::all();

        $selectedCourseId = $request->input('course_id') ?? ($courses->first() ? $courses->first()->id : null);
        $selectedClassRoomId = $request->input('class_room_id');

        $enrollmentsQuery = LmsCourseEnrollment::with(['student.classRoom', 'course'])
            ->where('course_id', $selectedCourseId);

        if ($selectedClassRoomId) {
            $enrollmentsQuery->whereHas('student', function ($query) use ($selectedClassRoomId) {
                $query->where('class_room_id', $selectedClassRoomId);
            });
        }

        $enrollments = $enrollmentsQuery->get();

        $completionStats = [];
        if ($selectedCourseId) {
            $completionStats = $this->reportService->getCourseCompletionStats($selectedCourseId);
        }

        return view('lms.reports.index', compact(
            'courses',
            'classRooms',
            'selectedCourseId',
            'selectedClassRoomId',
            'enrollments',
            'completionStats'
        ));
    }
}
