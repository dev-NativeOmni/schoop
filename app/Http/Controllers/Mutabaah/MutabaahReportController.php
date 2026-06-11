<?php

namespace App\Http\Controllers\Mutabaah;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mutabaah\MutabaahReportFilterRequest;
use App\Models\ClassRoom;
use App\Models\Student;
use App\Services\Mutabaah\MutabaahAccessService;
use App\Services\Mutabaah\MutabaahReportService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MutabaahReportController extends Controller
{
    public function __construct(
        private readonly MutabaahAccessService $accessService,
        private readonly MutabaahReportService $reportService,
    ) {}

    public function dashboard(MutabaahReportFilterRequest $request): View
    {
        $user = $request->user();

        if (! $this->accessService->canViewInternalReport($user)) {
            abort(403, 'Anda tidak memiliki akses ke laporan mutabaah.');
        }

        $filters = $request->validated();
        $data    = $this->reportService->dashboard($filters);

        $classRooms = ClassRoom::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('mutabaah.reports.dashboard', array_merge($data, [
            'classRooms' => $classRooms,
            'filters'    => $filters,
        ]));
    }

    public function studentReport(Request $request, Student $student): View
    {
        $user = $request->user();

        if (! $this->accessService->canViewInternalReport($user)) {
            abort(403, 'Anda tidak memiliki akses ke laporan mutabaah.');
        }

        if (! $this->accessService->canViewStudent($user, $student)) {
            abort(403, 'Anda tidak memiliki akses ke data santri ini.');
        }

        $filters  = $request->only(['start_date', 'end_date', 'year', 'month']);
        $snapshot = $this->reportService->studentSnapshot($student, $filters);

        $student->load('user', 'classRoom');

        return view('mutabaah.reports.student', array_merge($snapshot, [
            'filters' => $filters,
        ]));
    }
}
