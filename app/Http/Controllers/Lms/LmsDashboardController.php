<?php

namespace App\Http\Controllers\Lms;

use App\Http\Controllers\Controller;
use App\Models\LmsActivityLog;
use App\Models\LmsCourse;
use App\Models\LmsCourseEnrollment;
use App\Services\Tenancy\TenantContextService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LmsDashboardController extends Controller
{
    public function __construct(
        private readonly TenantContextService $tenantContext,
    ) {
        //
    }

    public function index(Request $request): View
    {
        $schoolId = $this->tenantContext->activeSchoolId();

        $totalCourses = LmsCourse::count();
        $totalEnrollments = LmsCourseEnrollment::count();
        $averageProgress = LmsCourseEnrollment::avg('progress_percentage') ?? 0.00;

        $recentActivities = LmsActivityLog::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('lms.dashboard', compact(
            'totalCourses',
            'totalEnrollments',
            'averageProgress',
            'recentActivities'
        ));
    }
}
