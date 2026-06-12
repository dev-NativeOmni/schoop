<?php

namespace App\Http\Controllers\SchoolOs;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Services\SchoolOs\SchoolOsAccessService;
use App\Services\SchoolOs\Student360SnapshotService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class Student360Controller extends Controller
{
    public function show(
        Request $request,
        Student $student,
        SchoolOsAccessService $accessService,
        Student360SnapshotService $snapshotService
    ): View {
        abort_unless($accessService->canViewStudent($request->user(), $student), 403);

        $snapshot = $snapshotService->snapshot($student);

        return view('schoolos.students.show-360', compact('student', 'snapshot'));
    }
}
