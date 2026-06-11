<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reports\MonthlyTahfizhReportRequest;
use App\Models\ClassRoom;
use App\Models\HafalanRecord;
use App\Models\Student;
use App\Models\User;
use App\Services\Reports\MonthlyTahfizhReportService;
use App\Services\Reports\ReportPeriodResolver;
use Illuminate\View\View;

class MonthlyTahfizhReportController extends Controller
{
    public function __construct(
        private readonly ReportPeriodResolver $periodResolver,
        private readonly MonthlyTahfizhReportService $reportService,
    ) {
        //
    }

    public function index(MonthlyTahfizhReportRequest $request): View
    {
        [$periodStart, $periodEnd] = $this->periodResolver->month(
            $request->input('month')
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

        return view('reports.tahfizh.monthly.index', [
            'rows' => $rows,
            'periodStart' => $periodStart,
            'periodEnd' => $periodEnd,
            'month' => $periodStart->format('Y-m'),
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

    public function show(MonthlyTahfizhReportRequest $request, Student $student): View
    {
        [$periodStart, $periodEnd] = $this->periodResolver->month(
            $request->input('month')
        );

        $records = $student->hafalanRecords()
            ->with(['teacher', 'startSurah', 'endSurah'])
            ->whereBetween('record_date', [
                $periodStart->toDateString(),
                $periodEnd->toDateString(),
            ])
            ->latest('record_date')
            ->get();

        return view('reports.tahfizh.monthly.show', [
            'student' => $student->load(['classRoom', 'school']),
            'records' => $records,
            'periodStart' => $periodStart,
            'periodEnd' => $periodEnd,
            'month' => $periodStart->format('Y-m'),
        ]);
    }
}
