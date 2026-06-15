<?php

namespace App\Http\Controllers\Boarding;

use App\Http\Controllers\Controller;
use App\Services\Boarding\BoardingAccessService;
use App\Services\Boarding\BoardingReportService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BoardingDashboardController extends Controller
{
    protected BoardingAccessService $accessService;
    protected BoardingReportService $reportService;

    public function __construct(
        BoardingAccessService $accessService,
        BoardingReportService $reportService
    ) {
        $this->accessService = $accessService;
        $this->reportService = $reportService;
    }

    public function __invoke(Request $request): View
    {
        abort_unless($this->accessService->canAccessDashboard($request->user()), 403);

        $schoolId = $request->user()->school_id;
        $stats = $this->reportService->getDashboardOverview($schoolId);

        return view('boarding.dashboard', compact('stats'));
    }
}
