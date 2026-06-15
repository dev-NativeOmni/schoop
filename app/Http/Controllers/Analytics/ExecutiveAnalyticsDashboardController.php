<?php

namespace App\Http\Controllers\Analytics;

use App\Http\Controllers\Controller;
use App\Services\Analytics\AnalyticsAccessLogger;
use App\Services\Analytics\AnalyticsAccessService;
use App\Services\Analytics\ExecutiveDashboardService;
use Illuminate\Http\Request;

class ExecutiveAnalyticsDashboardController extends Controller
{
    public function __construct(
        private readonly AnalyticsAccessService $access,
        private readonly ExecutiveDashboardService $dashboard,
        private readonly AnalyticsAccessLogger $logger
    ) {}

    public function index(Request $request)
    {
        $this->access->canViewInternalExecutiveAnalytics($request->user()) or abort(403);

        $this->logger->log($request, 'executive_dashboard');

        return view('analytics.executive.dashboard', [
            'stats' => $this->dashboard->stats(),
            'topTenants' => $this->dashboard->topTenants(),
            'riskTenants' => $this->dashboard->riskTenantsList(),
        ]);
    }
}
