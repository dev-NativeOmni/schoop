<?php

namespace App\Http\Controllers\Boarding;

use App\Http\Controllers\Controller;
use App\Http\Requests\Boarding\BoardingReportFilterRequest;
use App\Models\BoardingDormitory;
use App\Services\Boarding\BoardingAccessService;
use App\Services\Boarding\BoardingReportService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BoardingReportController extends Controller
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

    public function __invoke(BoardingReportFilterRequest $request): View
    {
        abort_unless($this->accessService->canAccessDashboard($request->user()), 403);

        $schoolId = $request->user()->school_id;
        
        $startDate = $request->input('start_date', now()->subDays(30)->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());
        $dormitoryId = $request->input('boarding_dormitory_id');

        $dormitories = BoardingDormitory::query()
            ->where('school_id', $schoolId)
            ->where('is_active', true)
            ->get();

        $stats = $this->reportService->getDashboardOverview($schoolId);
        $disciplineStats = $this->reportService->getDisciplineSummary($schoolId);
        $rollCallStats = $this->reportService->getRollCallStats($schoolId, $startDate, $endDate);

        return view('boarding.reports.dashboard', compact(
            'dormitories',
            'stats',
            'disciplineStats',
            'rollCallStats',
            'startDate',
            'endDate',
            'dormitoryId'
        ));
    }
}
