<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Http\Requests\Portal\StudentProgressFilterRequest;
use App\Models\HafalanRecord;
use App\Models\Student;
use App\Services\Portal\StudentProgressSnapshotService;
use Illuminate\View\View;

class StudentProgressPortalController extends Controller
{
    public function __construct(
        private readonly StudentProgressSnapshotService $snapshotService,
    ) {
        //
    }

    public function dashboard(StudentProgressFilterRequest $request): View
    {
        $student = $this->studentFromUser($request);

        if (! $student) {
            return view('portal.student.dashboard', [
                'student' => null,
                'snapshot' => null,
            ]);
        }

        $snapshot = $this->snapshotService->snapshot(
            student: $student,
            dateFrom: $request->input('date_from'),
            dateUntil: $request->input('date_until')
        );

        return view('portal.student.dashboard', [
            'student' => $student->load(['school', 'classRoom']),
            'snapshot' => $snapshot,
        ]);
    }

    public function records(StudentProgressFilterRequest $request): View
    {
        $student = $this->studentFromUser($request);

        if (! $student) {
            return view('portal.student.records', [
                'student' => null,
                'records' => collect(),
                'dateFrom' => null,
                'dateUntil' => null,
            ]);
        }

        $dateFrom = $request->input('date_from') ?: now()->startOfMonth()->toDateString();
        $dateUntil = $request->input('date_until') ?: now()->endOfDay()->toDateString();

        $records = HafalanRecord::query()
            ->with(['teacher', 'startSurah', 'endSurah', 'tahfizhTarget'])
            ->where('student_id', $student->id)
            ->whereBetween('record_date', [$dateFrom, $dateUntil])
            ->latest('record_date')
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view('portal.student.records', [
            'student' => $student->load(['school', 'classRoom']),
            'records' => $records,
            'dateFrom' => $dateFrom,
            'dateUntil' => $dateUntil,
        ]);
    }

    public function monthly(StudentProgressFilterRequest $request): View
    {
        $student = $this->studentFromUser($request);

        if (! $student) {
            return view('portal.student.monthly', [
                'student' => null,
                'report' => null,
            ]);
        }

        $report = $this->snapshotService->monthlyRows(
            student: $student,
            month: $request->input('month')
        );

        return view('portal.student.monthly', [
            'student' => $student->load(['school', 'classRoom']),
            'report' => $report,
        ]);
    }

    private function studentFromUser(StudentProgressFilterRequest $request): ?Student
    {
        return Student::query()
            ->with(['school', 'classRoom'])
            ->where('user_id', $request->user()->id)
            ->first();
    }
}
