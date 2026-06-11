<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Services\Mutabaah\MutabaahAccessService;
use App\Services\Mutabaah\MutabaahReportService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentMutabaahPortalController extends Controller
{
    public function __construct(
        private readonly MutabaahAccessService $accessService,
        private readonly MutabaahReportService $reportService,
    ) {}

    public function index(Request $request): View
    {
        $user = $request->user();

        if (! $this->accessService->isStudent($user)) {
            abort(403, 'Halaman ini hanya untuk santri.');
        }

        $student = $user->studentProfile;

        if (! $student) {
            abort(404, 'Data santri tidak ditemukan.');
        }

        $filters  = $request->only(['start_date', 'end_date']);
        $snapshot = $this->reportService->studentSnapshot($student, $filters);

        $student->load('user', 'classRoom');

        return view('portal.student.mutabaah', array_merge($snapshot, [
            'filters' => $filters,
        ]));
    }
}
