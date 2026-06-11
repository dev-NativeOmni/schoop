<?php

namespace App\Http\Controllers\Tahsin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tahsin\TahsinReportFilterRequest;
use App\Models\ClassRoom;
use App\Models\Student;
use App\Models\TahsinLevel;
use App\Models\User;
use App\Services\Tahsin\TahsinAccessService;
use App\Services\Tahsin\TahsinReportService;
use Illuminate\View\View;

class TahsinReportController extends Controller
{
    public function dashboard(
        TahsinReportFilterRequest $request,
        TahsinAccessService $accessService,
        TahsinReportService $reportService
    ): View {
        abort_unless($accessService->canViewInternalReport($request->user()), 403);

        $filters = $request->validated();
        $report = $reportService->dashboard($filters);

        $classRooms = ClassRoom::query()
            ->orderBy('name')
            ->get();

        $students = $accessService
            ->applyStudentScope(Student::query()->orderBy('full_name'), $request->user())
            ->limit(300)
            ->get();

        $levels = TahsinLevel::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $teachers = User::query()
            ->orderBy('name')
            ->get();

        return view('tahsin.reports.dashboard', compact(
            'report',
            'filters',
            'classRooms',
            'students',
            'levels',
            'teachers'
        ));
    }
}
