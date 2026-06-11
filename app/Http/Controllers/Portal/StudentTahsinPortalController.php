<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tahsin\TahsinReportFilterRequest;
use App\Models\Student;
use App\Services\Tahsin\TahsinAccessService;
use App\Services\Tahsin\TahsinReportService;
use Illuminate\View\View;

class StudentTahsinPortalController extends Controller
{
    public function index(
        TahsinReportFilterRequest $request,
        TahsinAccessService $accessService,
        TahsinReportService $reportService
    ): View {
        abort_unless($accessService->isStudent($request->user()), 403);

        $student = Student::query()
            ->where('user_id', $request->user()->id)
            ->first();

        if (! $student) {
            return view('portal.student.tahsin', [
                'student' => null,
                'snapshot' => null,
            ]);
        }

        $snapshot = $reportService->studentSnapshot($student, $request->validated());

        return view('portal.student.tahsin', compact('student', 'snapshot'));
    }
}
