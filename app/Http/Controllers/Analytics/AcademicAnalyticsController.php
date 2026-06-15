<?php

namespace App\Http\Controllers\Analytics;

use App\Http\Controllers\Controller;
use App\Http\Requests\Analytics\SchoolAnalyticsFilterRequest;
use App\Services\Analytics\AcademicAnalyticsService;
use App\Services\Analytics\AnalyticsAccessLogger;
use App\Services\Analytics\AnalyticsAccessService;
use App\Services\Analytics\AnalyticsPeriodResolver;
use App\Services\Analytics\AnalyticsPrivacyGuard;
use App\Services\Tenancy\TenantContextService;

class AcademicAnalyticsController extends Controller
{
    public function __construct(
        private readonly AnalyticsAccessService $access,
        private readonly AcademicAnalyticsService $service,
        private readonly AnalyticsAccessLogger $logger,
        private readonly AnalyticsPrivacyGuard $privacyGuard,
        private readonly AnalyticsPeriodResolver $periodResolver,
        private readonly TenantContextService $tenantContext
    ) {}

    public function index(SchoolAnalyticsFilterRequest $request)
    {
        $schoolId = $request->input('school_id') ?: $this->tenantContext->activeSchoolId();
        abort_unless($schoolId, 403, 'Akses analitik akademik memerlukan tenant context.');

        $this->privacyGuard->assertSchoolScope((int) $schoolId, $request->user());
        $this->access->canViewSchoolAnalytics($request->user(), (int) $schoolId) or abort(403);

        $period = $this->periodResolver->resolve($request);
        $this->logger->log($request, 'academic_analytics', 'view', (int) $schoolId);

        $data = $this->service->data((int) $schoolId, $period['date_from'], $period['date_until']);

        return view('analytics.academic.dashboard', array_merge($data, [
            'schoolId' => $schoolId,
            'dateFrom' => $period['date_from']->toDateString(),
            'dateUntil' => $period['date_until']->toDateString(),
        ]));
    }
}
