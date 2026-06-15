<?php

namespace App\Http\Controllers\Analytics;

use App\Http\Controllers\Controller;
use App\Http\Requests\Analytics\SchoolAnalyticsFilterRequest;
use App\Services\Analytics\AnalyticsAccessLogger;
use App\Services\Analytics\AnalyticsAccessService;
use App\Services\Analytics\AnalyticsPeriodResolver;
use App\Services\Analytics\AnalyticsPrivacyGuard;
use App\Services\Analytics\MobileApiAnalyticsService;
use App\Services\Tenancy\TenantContextService;

class MobileApiAnalyticsController extends Controller
{
    public function __construct(
        private readonly AnalyticsAccessService $access,
        private readonly MobileApiAnalyticsService $service,
        private readonly AnalyticsAccessLogger $logger,
        private readonly AnalyticsPrivacyGuard $privacyGuard,
        private readonly AnalyticsPeriodResolver $periodResolver,
        private readonly TenantContextService $tenantContext
    ) {}

    public function index(SchoolAnalyticsFilterRequest $request)
    {
        $schoolId = $request->input('school_id') ?: $this->tenantContext->activeSchoolId();
        if ($schoolId) {
            $this->privacyGuard->assertSchoolScope((int) $schoolId, $request->user());
            $this->access->canViewSchoolAnalytics($request->user(), (int) $schoolId) or abort(403);
        } else {
            $this->access->canViewInternalExecutiveAnalytics($request->user()) or abort(403);
        }

        $period = $this->periodResolver->resolve($request);
        $this->logger->log($request, 'mobile_api_analytics', 'view', $schoolId ? (int) $schoolId : null);

        $data = $this->service->data($schoolId ? (int) $schoolId : null, $period['date_from'], $period['date_until']);

        return view('analytics.mobile-api.dashboard', array_merge($data, [
            'schoolId' => $schoolId,
            'dateFrom' => $period['date_from']->toDateString(),
            'dateUntil' => $period['date_until']->toDateString(),
        ]));
    }
}
