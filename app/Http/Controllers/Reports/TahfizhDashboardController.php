<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reports\DashboardFilterRequest;
use App\Models\ClassRoom;
use App\Models\HafalanRecord;
use App\Models\Student;
use App\Models\User;
use App\Services\Reports\ReportPeriodResolver;
use App\Services\Reports\TahfizhDashboardSummaryService;
use Illuminate\View\View;

class TahfizhDashboardController extends Controller
{
    public function __construct(
        private readonly ReportPeriodResolver $periodResolver,
        private readonly TahfizhDashboardSummaryService $summaryService,
    ) {
        //
    }

    public function __invoke(DashboardFilterRequest $request): View
    {
        [$dateFrom, $dateUntil] = $this->periodResolver->custom(
            $request->input('date_from'),
            $request->input('date_until')
        );

        $summary = $this->summaryService->summarize(
            user: $request->user(),
            dateFrom: $dateFrom,
            dateUntil: $dateUntil,
            classRoomId: $request->integer('class_room_id') ?: null,
            studentId: $request->integer('student_id') ?: null,
            teacherId: $request->integer('teacher_id') ?: null,
            status: $request->input('status')
        );

        return view('reports.tahfizh.dashboard.index', [
            'summary' => $summary,
            'dateFrom' => $dateFrom,
            'dateUntil' => $dateUntil,
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
}
