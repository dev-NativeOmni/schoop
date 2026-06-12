<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Http\Requests\Finance\FinanceReportFilterRequest;
use App\Models\ClassRoom;
use App\Models\Student;
use App\Services\Finance\FinanceAccessService;
use App\Services\Finance\FinanceReportService;
use Illuminate\View\View;

class FinanceReportController extends Controller
{
    public function dashboard(
        FinanceReportFilterRequest $request,
        FinanceAccessService $accessService,
        FinanceReportService $reportService
    ): View {
        abort_unless($accessService->canViewFinanceReport($request->user()), 403);

        $filters = $request->validated();
        $report = $reportService->dashboard($filters);

        $classRooms = ClassRoom::query()
            ->orderBy('name')
            ->get();

        $students = Student::query()
            ->orderBy('full_name')
            ->limit(500)
            ->get();

        return view('finance.reports.dashboard', compact(
            'report',
            'filters',
            'classRooms',
            'students'
        ));
    }
}
