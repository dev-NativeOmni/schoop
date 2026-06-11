<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Http\Requests\Portal\ParentProgressFilterRequest;
use App\Models\HafalanRecord;
use App\Models\Student;
use App\Services\Portal\ParentStudentAccessService;
use App\Services\Portal\StudentProgressSnapshotService;
use Illuminate\View\View;

class ParentProgressPortalController extends Controller
{
    public function __construct(
        private readonly ParentStudentAccessService $accessService,
        private readonly StudentProgressSnapshotService $snapshotService,
    ) {
        //
    }

    public function dashboard(ParentProgressFilterRequest $request): View
    {
        $children = $this->accessService->children($request->user());

        $snapshots = $children->map(function (Student $student) use ($request): array {
            return $this->snapshotService->snapshot(
                student: $student,
                dateFrom: $request->input('date_from'),
                dateUntil: $request->input('date_until')
            );
        });

        return view('portal.parent.dashboard', [
            'children' => $children,
            'snapshots' => $snapshots,
        ]);
    }

    public function progress(ParentProgressFilterRequest $request, Student $student): View
    {
        $this->accessService->abortIfCannotAccess($request->user(), $student);

        $snapshot = $this->snapshotService->snapshot(
            student: $student,
            dateFrom: $request->input('date_from'),
            dateUntil: $request->input('date_until')
        );

        return view('portal.parent.progress', [
            'student' => $student->load(['school', 'classRoom']),
            'snapshot' => $snapshot,
        ]);
    }

    public function records(ParentProgressFilterRequest $request, Student $student): View
    {
        $this->accessService->abortIfCannotAccess($request->user(), $student);

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

        return view('portal.parent.records', [
            'student' => $student->load(['school', 'classRoom']),
            'records' => $records,
            'dateFrom' => $dateFrom,
            'dateUntil' => $dateUntil,
        ]);
    }

    public function monthly(ParentProgressFilterRequest $request, Student $student): View
    {
        $this->accessService->abortIfCannotAccess($request->user(), $student);

        $report = $this->snapshotService->monthlyRows(
            student: $student,
            month: $request->input('month')
        );

        return view('portal.parent.monthly', [
            'student' => $student->load(['school', 'classRoom']),
            'report' => $report,
        ]);
    }
}
