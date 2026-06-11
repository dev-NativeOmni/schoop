<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tahsin\TahsinReportFilterRequest;
use App\Models\Student;
use App\Services\Tahsin\TahsinAccessService;
use App\Services\Tahsin\TahsinReportService;
use Illuminate\View\View;

class ParentTahsinPortalController extends Controller
{
    public function index(
        TahsinReportFilterRequest $request,
        TahsinAccessService $accessService,
        TahsinReportService $reportService
    ): View {
        abort_unless($accessService->isParent($request->user()), 403);

        $students = $accessService
            ->applyStudentScope(Student::query()->orderBy('full_name'), $request->user())
            ->get();

        $selectedStudent = null;
        $snapshot = null;

        if ($students->isNotEmpty()) {
            $selectedStudent = $students->firstWhere('id', (int) $request->input('student_id'))
                ?? $students->first();

            $snapshot = $reportService->studentSnapshot($selectedStudent, $request->validated());
        }

        return view('portal.parent.tahsin', compact(
            'students',
            'selectedStudent',
            'snapshot'
        ));
    }
}
