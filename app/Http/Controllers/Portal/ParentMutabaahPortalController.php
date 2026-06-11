<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Services\Mutabaah\MutabaahAccessService;
use App\Services\Mutabaah\MutabaahReportService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ParentMutabaahPortalController extends Controller
{
    public function __construct(
        private readonly MutabaahAccessService $accessService,
        private readonly MutabaahReportService $reportService,
    ) {}

    public function show(Request $request, Student $student): View
    {
        $user = $request->user();

        if (! $this->accessService->isParent($user)) {
            abort(403, 'Halaman ini hanya untuk orang tua santri.');
        }

        if (! $this->accessService->canViewStudent($user, $student)) {
            abort(403, 'Anda tidak memiliki akses ke data anak ini.');
        }

        $filters  = $request->only(['start_date', 'end_date']);
        $snapshot = $this->reportService->studentSnapshot($student, $filters);

        $student->load('user', 'classRoom');

        // Get parent's children list
        $parentProfile = $user->parentProfile;
        $children      = $parentProfile?->students()->with('user')->get() ?? collect();

        return view('portal.parent.mutabaah', array_merge($snapshot, [
            'children' => $children,
            'filters'  => $filters,
        ]));
    }
}
