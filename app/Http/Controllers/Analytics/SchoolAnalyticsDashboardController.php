<?php

namespace App\Http\Controllers\Analytics;

use App\Http\Controllers\Controller;
use App\Http\Requests\Analytics\SchoolAnalyticsFilterRequest;
use App\Services\Analytics\AnalyticsAccessLogger;
use App\Services\Analytics\AnalyticsAccessService;
use App\Services\Analytics\AnalyticsPrivacyGuard;
use App\Services\Analytics\SchoolAnalyticsDashboardService;
use App\Services\Tenancy\TenantContextService;

class SchoolAnalyticsDashboardController extends Controller
{
    public function __construct(
        private readonly AnalyticsAccessService $access,
        private readonly SchoolAnalyticsDashboardService $dashboard,
        private readonly AnalyticsAccessLogger $logger,
        private readonly AnalyticsPrivacyGuard $privacyGuard,
        private readonly TenantContextService $tenantContext
    ) {}

    public function index(SchoolAnalyticsFilterRequest $request)
    {
        $schoolId = $request->input('school_id') ?: $this->tenantContext->activeSchoolId();
        abort_unless($schoolId, 403, 'Akses analitik sekolah memerlukan tenant context.');

        $this->privacyGuard->assertSchoolScope((int) $schoolId, $request->user());
        $this->access->canViewSchoolAnalytics($request->user(), (int) $schoolId) or abort(403);

        $this->logger->log($request, 'school_dashboard', 'view', (int) $schoolId);

        return view('analytics.school.dashboard', [
            'stats' => $this->dashboard->stats((int) $schoolId),
            'schoolId' => $schoolId,
        ]);
    }
}
