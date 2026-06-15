<?php

namespace App\Http\Controllers\Exports;

use App\Exports\Tahfizh\DashboardTahfizhSummaryExport;
use App\Exports\Tahfizh\MonthlyTahfizhReportExport;
use App\Exports\Tahfizh\QuarterlyTahfizhReportExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Exports\TahfizhExportRequest;
use App\Models\ClassRoom;
use App\Models\HafalanRecord;
use App\Models\Student;
use App\Models\User;
use App\Services\Reports\MonthlyTahfizhReportService;
use App\Services\Reports\QuarterlyTahfizhReportService;
use App\Services\Reports\ReportPeriodResolver;
use App\Services\Reports\TahfizhDashboardSummaryService;
use App\Services\Tenancy\TenantContextService;
use App\Services\WhiteLabel\WhiteLabelPublicationService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class TahfizhExportController extends Controller
{
    public function index(): View
    {
        return view('exports.tahfizh.index', [
            'classRooms' => ClassRoom::query()
                ->where('is_active', true)
                ->orderBy('name')
                ->get(),

            'students' => Student::query()
                ->where('is_active', true)
                ->orderBy('full_name')
                ->get(),

            'teachers' => User::query()
                ->whereHas('role', fn ($query) => $query->where('name', 'teacher'))
                ->where('is_active', true)
                ->orderBy('name')
                ->get(),

            'statuses' => [
                HafalanRecord::STATUS_LUNAS,
                HafalanRecord::STATUS_KURANG,
                HafalanRecord::STATUS_LEBIH,
                HafalanRecord::STATUS_TIDAK_HADIR,
                HafalanRecord::STATUS_IZIN,
                HafalanRecord::STATUS_SAKIT,
            ],
        ]);
    }

    public function monthlyExcel(
        TahfizhExportRequest $request,
        ReportPeriodResolver $periodResolver,
        MonthlyTahfizhReportService $reportService
    ): BinaryFileResponse {
        [$periodStart, $periodEnd] = $periodResolver->month($request->input('month'));

        $rows = $reportService->build(
            user: $request->user(),
            periodStart: $periodStart,
            periodEnd: $periodEnd,
            classRoomId: $this->nullableInteger($request->input('class_room_id')),
            studentId: $this->nullableInteger($request->input('student_id')),
            teacherId: $this->nullableInteger($request->input('teacher_id')),
            status: $request->input('status')
        );

        $filename = 'laporan-tahfizh-bulanan-' . $periodStart->format('Y-m') . '.xlsx';

        return Excel::download(
            new MonthlyTahfizhReportExport($rows, $periodStart, $periodEnd),
            $filename
        );
    }

    public function monthlyPdf(
        TahfizhExportRequest $request,
        ReportPeriodResolver $periodResolver,
        MonthlyTahfizhReportService $reportService
    ): Response {
        [$periodStart, $periodEnd] = $periodResolver->month($request->input('month'));

        $rows = $reportService->build(
            user: $request->user(),
            periodStart: $periodStart,
            periodEnd: $periodEnd,
            classRoomId: $this->nullableInteger($request->input('class_room_id')),
            studentId: $this->nullableInteger($request->input('student_id')),
            teacherId: $this->nullableInteger($request->input('teacher_id')),
            status: $request->input('status')
        );

        $filename = 'laporan-tahfizh-bulanan-' . $periodStart->format('Y-m') . '.pdf';

        $pdf = Pdf::loadView('exports.pdf.monthly-tahfizh-report', [
            'rows' => $rows,
            'periodStart' => $periodStart,
            'periodEnd' => $periodEnd,
            'generatedBy' => $request->user(),
            'generatedAt' => now(),
            'pdfBrand' => $this->pdfBranding($request->user()),
        ])->setPaper('a4', 'landscape');

        return $pdf->download($filename);
    }

    public function quarterlyExcel(
        TahfizhExportRequest $request,
        ReportPeriodResolver $periodResolver,
        QuarterlyTahfizhReportService $reportService
    ): BinaryFileResponse {
        [$periodStart, $periodEnd] = $periodResolver->quarter(
            year: $this->nullableInteger($request->input('year')),
            quarter: $this->nullableInteger($request->input('quarter'))
        );

        $rows = $reportService->build(
            user: $request->user(),
            periodStart: $periodStart,
            periodEnd: $periodEnd,
            classRoomId: $this->nullableInteger($request->input('class_room_id')),
            studentId: $this->nullableInteger($request->input('student_id')),
            teacherId: $this->nullableInteger($request->input('teacher_id')),
            status: $request->input('status')
        );

        $filename = 'laporan-tahfizh-triwulan-' . $periodStart->format('Y-m-d') . '-' . $periodEnd->format('Y-m-d') . '.xlsx';

        $periodLabel = $periodResolver->quarterLabel(
            year: $this->nullableInteger($request->input('year')),
            quarter: $this->nullableInteger($request->input('quarter'))
        );

        return Excel::download(
            new QuarterlyTahfizhReportExport($rows, $periodStart, $periodEnd, $periodLabel),
            $filename
        );
    }

    public function quarterlyPdf(
        TahfizhExportRequest $request,
        ReportPeriodResolver $periodResolver,
        QuarterlyTahfizhReportService $reportService
    ): Response {
        [$periodStart, $periodEnd] = $periodResolver->quarter(
            year: $this->nullableInteger($request->input('year')),
            quarter: $this->nullableInteger($request->input('quarter'))
        );

        $periodLabel = $periodResolver->quarterLabel(
            year: $this->nullableInteger($request->input('year')),
            quarter: $this->nullableInteger($request->input('quarter'))
        );

        $rows = $reportService->build(
            user: $request->user(),
            periodStart: $periodStart,
            periodEnd: $periodEnd,
            classRoomId: $this->nullableInteger($request->input('class_room_id')),
            studentId: $this->nullableInteger($request->input('student_id')),
            teacherId: $this->nullableInteger($request->input('teacher_id')),
            status: $request->input('status')
        );

        $filename = 'laporan-tahfizh-triwulan-' . $periodStart->format('Y-m-d') . '-' . $periodEnd->format('Y-m-d') . '.pdf';

        $pdf = Pdf::loadView('exports.pdf.quarterly-tahfizh-report', [
            'rows' => $rows,
            'periodStart' => $periodStart,
            'periodEnd' => $periodEnd,
            'periodLabel' => $periodLabel,
            'generatedBy' => $request->user(),
            'generatedAt' => now(),
            'pdfBrand' => $this->pdfBranding($request->user()),
        ])->setPaper('a4', 'landscape');

        return $pdf->download($filename);
    }

    public function dashboardExcel(
        TahfizhExportRequest $request,
        ReportPeriodResolver $periodResolver,
        TahfizhDashboardSummaryService $summaryService
    ): BinaryFileResponse {
        [$periodStart, $periodEnd] = $periodResolver->custom(
            dateFrom: $request->input('date_from'),
            dateUntil: $request->input('date_until')
        );

        $summary = $summaryService->summarize(
            user: $request->user(),
            dateFrom: $periodStart,
            dateUntil: $periodEnd,
            classRoomId: $this->nullableInteger($request->input('class_room_id')),
            studentId: $this->nullableInteger($request->input('student_id')),
            teacherId: $this->nullableInteger($request->input('teacher_id')),
            status: $request->input('status')
        );

        $filename = 'ringkasan-dashboard-tahfizh-' . $periodStart->format('Y-m-d') . '-' . $periodEnd->format('Y-m-d') . '.xlsx';

        return Excel::download(
            new DashboardTahfizhSummaryExport($summary, $periodStart, $periodEnd),
            $filename
        );
    }

    public function dashboardPdf(
        TahfizhExportRequest $request,
        ReportPeriodResolver $periodResolver,
        TahfizhDashboardSummaryService $summaryService
    ): Response {
        [$periodStart, $periodEnd] = $periodResolver->custom(
            dateFrom: $request->input('date_from'),
            dateUntil: $request->input('date_until')
        );

        $summary = $summaryService->summarize(
            user: $request->user(),
            dateFrom: $periodStart,
            dateUntil: $periodEnd,
            classRoomId: $this->nullableInteger($request->input('class_room_id')),
            studentId: $this->nullableInteger($request->input('student_id')),
            teacherId: $this->nullableInteger($request->input('teacher_id')),
            status: $request->input('status')
        );

        $filename = 'ringkasan-dashboard-tahfizh-' . $periodStart->format('Y-m-d') . '-' . $periodEnd->format('Y-m-d') . '.pdf';

        $pdf = Pdf::loadView('exports.pdf.dashboard-tahfizh-summary', [
            'summary' => $summary,
            'periodStart' => $periodStart,
            'periodEnd' => $periodEnd,
            'generatedBy' => $request->user(),
            'generatedAt' => now(),
            'pdfBrand' => $this->pdfBranding($request->user()),
        ])->setPaper('a4', 'portrait');

        return $pdf->download($filename);
    }

    private function nullableInteger(mixed $value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }

        return (int) $value;
    }

    private function pdfBranding(User $user): array
    {
        $schoolId = app(TenantContextService::class)->activeSchoolId() ?: $user->school_id;

        if (! $schoolId) {
            return [
                'name' => 'HafizPlus School Platform',
                'tagline' => 'Tahfizh Monitoring App',
                'primary_color' => '#111827',
            ];
        }

        $settings = app(WhiteLabelPublicationService::class)->getActivePublishedSettings((int) $schoolId);
        $brand = $settings['brand'];
        $theme = $settings['theme'];

        return [
            'name' => $brand->display_name ?: 'HafizPlus School Platform',
            'tagline' => $brand->tagline ?: 'Tahfizh Monitoring App',
            'primary_color' => preg_match('/^#[0-9A-Fa-f]{6}$/', (string) $theme->primary_color)
                ? $theme->primary_color
                : '#111827',
        ];
    }
}
