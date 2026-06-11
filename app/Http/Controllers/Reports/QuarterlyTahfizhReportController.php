<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reports\QuarterlyTahfizhReportRequest;
use App\Models\ClassRoom;
use App\Models\HafalanRecord;
use App\Models\Student;
use App\Models\User;
use App\Services\Reports\QuarterlyTahfizhReportService;
use App\Services\Reports\ReportPeriodResolver;
use Illuminate\View\View;

class QuarterlyTahfizhReportController extends Controller
{
    public function __construct(
        private readonly ReportPeriodResolver $periodResolver,
        private readonly QuarterlyTahfizhReportService $reportService,
    ) {
        //
    }

    public function index(QuarterlyTahfizhReportRequest $request): View
    {
        $year = $request->integer('year') ?: (int) now()->format('Y');
        $quarter = $request->integer('quarter') ?: (int) ceil(now()->month / 3);

        [$periodStart, $periodEnd] = $this->periodResolver->quarter(
            $year,
            $quarter
        );

        $rows = $this->reportService->build(
            user: $request->user(),
            periodStart: $periodStart,
            periodEnd: $periodEnd,
            classRoomId: $request->integer('class_room_id') ?: null,
            studentId: $request->integer('student_id') ?: null,
            teacherId: $request->integer('teacher_id') ?: null,
            status: $request->input('status')
        );

        return view('reports.tahfizh.quarterly.index', [
            'rows' => $rows,
            'periodStart' => $periodStart,
            'periodEnd' => $periodEnd,
            'year' => $year,
            'quarter' => $quarter,
            'label' => $this->periodResolver->quarterLabel($year, $quarter),
            'classRooms' => ClassRoom::query()->where('is_active', true)->orderBy('name')->get(),
            'students' => Student::query()->where('is_active', true)->orderBy('full_name')->get(),
            'teachers' => User::query()
                ->whereHas('role', fn ($query) => $query->where('name', 'teacher'))
                ->orderBy('name')
                ->get(),
            'statuses' => [
                HafalanRecord::STATUS_LUNAS,
                HafalanRecord::STATUS_KURANG,
                HafalanRecord::STATUS_LEBIH,
            ],
        ]);
    }

    public function show(QuarterlyTahfizhReportRequest $request, Student $student): View
    {
        $year = $request->integer('year') ?: (int) now()->format('Y');
        $quarter = $request->integer('quarter') ?: (int) ceil(now()->month / 3);

        [$periodStart, $periodEnd] = $this->periodResolver->quarter(
            $year,
            $quarter
        );

        $records = $student->hafalanRecords()
            ->with(['teacher', 'startSurah', 'endSurah'])
            ->whereBetween('record_date', [
                $periodStart->toDateString(),
                $periodEnd->toDateString(),
            ])
            ->latest('record_date')
            ->get();

        return view('reports.tahfizh.quarterly.show', [
            'student' => $student->load(['classRoom', 'school']),
            'records' => $records,
            'periodStart' => $periodStart,
            'periodEnd' => $periodEnd,
            'year' => $year,
            'quarter' => $quarter,
            'label' => $this->periodResolver->quarterLabel($year, $quarter),
        ]);
    }
}
