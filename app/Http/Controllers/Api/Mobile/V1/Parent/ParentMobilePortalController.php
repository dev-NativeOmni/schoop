<?php

namespace App\Http\Controllers\Api\Mobile\V1\Parent;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Services\Mobile\MobileAccessService;
use App\Services\Mobile\MobilePortalSummaryService;
use App\Services\Portal\ParentStudentAccessService;
use App\Support\MobileApiResponse;
use Illuminate\Http\Request;

class ParentMobilePortalController extends Controller
{
    public function __construct(
        private readonly MobileAccessService $access,
        private readonly ParentStudentAccessService $parentStudents,
        private readonly MobilePortalSummaryService $summaries,
    ) {}

    public function children(Request $request)
    {
        $this->access->ensureRole($request->user(), ['parent'], 'Endpoint ini hanya untuk orang tua.');

        return MobileApiResponse::ok([
            'children' => $this->parentStudents->children($request->user())
                ->map(fn (Student $student): array => $this->summaries->studentCard($student))
                ->values(),
        ]);
    }

    public function summary(Request $request, Student $student)
    {
        $this->access->ensureParentCanAccess($request->user(), $student);

        return MobileApiResponse::ok($this->summaries->summary($student));
    }

    public function tahfizh(Request $request, Student $student)
    {
        $this->access->ensureParentCanAccess($request->user(), $student);

        return MobileApiResponse::ok($this->summaries->tahfizh($student));
    }

    public function mutabaah(Request $request, Student $student)
    {
        $this->access->ensureParentCanAccess($request->user(), $student);

        return MobileApiResponse::ok($this->summaries->mutabaah($student));
    }

    public function attendance(Request $request, Student $student)
    {
        $this->access->ensureParentCanAccess($request->user(), $student);

        return MobileApiResponse::ok($this->summaries->attendance($student));
    }

    public function tahsin(Request $request, Student $student)
    {
        $this->access->ensureParentCanAccess($request->user(), $student);

        return MobileApiResponse::ok($this->summaries->tahsin($student));
    }

    public function finance(Request $request, Student $student)
    {
        $this->access->ensureParentCanAccess($request->user(), $student);

        return MobileApiResponse::ok($this->summaries->finance($student));
    }

    public function cashless(Request $request, Student $student)
    {
        $this->access->ensureParentCanAccess($request->user(), $student);

        return MobileApiResponse::ok($this->summaries->cashless($student));
    }

    public function notifications(Request $request, Student $student)
    {
        $this->access->ensureParentCanAccess($request->user(), $student);

        return MobileApiResponse::ok($this->summaries->notifications($request->user()));
    }
}
