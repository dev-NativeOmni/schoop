<?php

namespace App\Http\Controllers\Analytics;

use App\Http\Controllers\Controller;
use App\Http\Requests\Analytics\GenerateExecutiveReportRequest;
use App\Models\ExecutiveReportRun;
use App\Models\School;
use App\Services\Analytics\AnalyticsAccessLogger;
use App\Services\Analytics\AnalyticsAccessService;
use App\Services\Analytics\AnalyticsPrivacyGuard;
use App\Services\Analytics\ExecutiveReportService;
use App\Services\Tenancy\TenantContextService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ExecutiveReportController extends Controller
{
    public function __construct(
        private readonly AnalyticsAccessService $access,
        private readonly ExecutiveReportService $service,
        private readonly AnalyticsAccessLogger $logger,
        private readonly AnalyticsPrivacyGuard $privacyGuard,
        private readonly TenantContextService $tenantContext
    ) {}

    public function index(Request $request)
    {
        $schoolId = $this->tenantContext->activeSchoolId();
        if ($schoolId) {
            $this->privacyGuard->assertSchoolScope($schoolId, $request->user());
            $this->access->canGenerateExecutiveReport($request->user(), $schoolId) or abort(403);
        } else {
            $this->access->canGenerateExecutiveReport($request->user(), null) or abort(403);
        }

        $this->logger->log($request, 'executive_reports_index');

        $reports = ExecutiveReportRun::query()
            ->with(['school', 'user'])
            ->where('school_id', $schoolId)
            ->latest()
            ->paginate(20);

        return view('analytics.executive-reports.index', [
            'reports' => $reports,
            'schoolId' => $schoolId,
        ]);
    }

    public function create(Request $request)
    {
        $schoolId = $this->tenantContext->activeSchoolId();
        if ($schoolId) {
            $this->access->canGenerateExecutiveReport($request->user(), $schoolId) or abort(403);
        } else {
            $this->access->canGenerateExecutiveReport($request->user(), null) or abort(403);
        }

        return view('analytics.executive-reports.create', [
            'schoolId' => $schoolId,
            'schools' => School::query()->where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function store(GenerateExecutiveReportRequest $request)
    {
        $schoolId = $request->input('school_id') ?: $this->tenantContext->activeSchoolId();
        if ($schoolId) {
            $this->privacyGuard->assertSchoolScope((int) $schoolId, $request->user());
            $this->access->canGenerateExecutiveReport($request->user(), (int) $schoolId) or abort(403);
        } else {
            $this->access->canGenerateExecutiveReport($request->user(), null) or abort(403);
        }

        $start = Carbon::parse($request->input('period_start'));
        $end = Carbon::parse($request->input('period_end'));

        if ($schoolId) {
            $report = $this->service->generateMonthlySchoolReport((int) $schoolId, $start, $end, $request->user()->id);
        } else {
            $report = $this->service->generateMonthlyInternalReport($start, $end, $request->user()->id);
        }

        $this->logger->log($request, 'executive_report_generate', 'generate', $schoolId ? (int) $schoolId : null);

        return redirect()->route('analytics.executive-reports.show', $report)->with('success', 'Executive report draft created successfully.');
    }

    public function show(Request $request, ExecutiveReportRun $report)
    {
        if ($report->school_id) {
            $this->privacyGuard->assertSchoolScope($report->school_id, $request->user());
            $this->access->canViewSchoolAnalytics($request->user(), $report->school_id) or abort(403);
        } else {
            $this->access->canViewInternalExecutiveAnalytics($request->user()) or abort(403);
        }

        $this->logger->log($request, 'executive_report_show', 'view', $report->school_id);

        return view('analytics.executive-reports.show', [
            'report' => $report->load(['sections', 'school', 'user']),
        ]);
    }

    public function print(Request $request, ExecutiveReportRun $report)
    {
        if ($report->school_id) {
            $this->privacyGuard->assertSchoolScope($report->school_id, $request->user());
            $this->access->canViewSchoolAnalytics($request->user(), $report->school_id) or abort(403);
        } else {
            $this->access->canViewInternalExecutiveAnalytics($request->user()) or abort(403);
        }

        $this->logger->log($request, 'executive_report_print', 'print', $report->school_id);

        return view('analytics.executive-reports.print', [
            'report' => $report->load(['sections', 'school', 'user']),
        ]);
    }
}
