<?php

namespace App\Http\Controllers\Api\Mobile\V1\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Mobile\V1\StoreMobileMutabaahRecordRequest;
use App\Http\Requests\Api\Mobile\V1\StoreMobileTahfizhRecordRequest;
use App\Http\Requests\Api\Mobile\V1\StoreMobileTahsinAssessmentRequest;
use App\Models\HafalanRecord;
use App\Models\Student;
use App\Services\Mobile\MobileAccessService;
use App\Services\Mobile\MobilePortalSummaryService;
use App\Services\Mobile\MobileTeacherWorkflowService;
use App\Support\MobileApiResponse;
use Illuminate\Http\Request;

class TeacherMobilePortalController extends Controller
{
    public function __construct(
        private readonly MobileAccessService $access,
        private readonly MobilePortalSummaryService $summaries,
        private readonly MobileTeacherWorkflowService $workflow,
    ) {}

    public function dashboard(Request $request)
    {
        return MobileApiResponse::ok($this->workflow->dashboard($request->user()));
    }

    public function classes(Request $request)
    {
        return MobileApiResponse::ok([
            'classes' => $this->workflow->classes($request->user()),
        ]);
    }

    public function students(Request $request)
    {
        return MobileApiResponse::ok([
            'students' => $this->workflow->students($request->user()),
        ]);
    }

    public function studentSummary(Request $request, Student $student)
    {
        $this->access->ensureTeacherCanAccessStudent($request->user(), $student);

        return MobileApiResponse::ok($this->summaries->summary($student));
    }

    public function storeTahfizhRecord(StoreMobileTahfizhRecordRequest $request)
    {
        $record = $this->workflow->storeTahfizhRecord($request->validated(), $request->user());

        return MobileApiResponse::ok([
            'record' => $record,
        ], 'Setoran tahfizh tersimpan.', status: 201);
    }

    public function tahfizhRecords(Request $request)
    {
        $this->access->ensureRole($request->user(), ['super_admin', 'admin', 'teacher']);

        return MobileApiResponse::ok([
            'records' => HafalanRecord::query()
                ->with(['student.classRoom', 'teacher', 'startSurah', 'endSurah'])
                ->where('school_id', $this->access->activeSchoolId())
                ->latest('record_date')
                ->latest('id')
                ->limit(100)
                ->get(),
        ]);
    }

    public function storeMutabaahRecords(StoreMobileMutabaahRecordRequest $request)
    {
        return MobileApiResponse::ok([
            'records' => $this->workflow->storeMutabaahRecords($request->validated(), $request->user()),
        ], 'Mutabaah tersimpan.', status: 201);
    }

    public function attendanceSessions(Request $request)
    {
        return MobileApiResponse::ok([
            'sessions' => $this->workflow->attendanceSessions($request->user()),
        ]);
    }

    public function scanAttendance(Request $request)
    {
        $payload = $request->validate([
            'attendance_session_id' => ['required', 'integer', 'exists:attendance_sessions,id'],
            'qr_payload' => ['required', 'string'],
        ]);

        return MobileApiResponse::ok([
            'record' => $this->workflow->scanAttendance(
                $request->user(),
                (int) $payload['attendance_session_id'],
                $payload['qr_payload']
            ),
        ], 'Presensi QR tersimpan.');
    }

    public function storeManualAttendance(Request $request)
    {
        $payload = $request->validate([
            'attendance_session_id' => ['required', 'integer', 'exists:attendance_sessions,id'],
            'student_id' => ['required', 'integer', 'exists:students,id'],
            'status' => ['required', 'string', 'in:present,late,sick,permission,absent'],
            'check_in_at' => ['nullable', 'date'],
            'check_out_at' => ['nullable', 'date'],
            'note' => ['nullable', 'string', 'max:2000'],
        ]);

        return MobileApiResponse::ok([
            'record' => $this->workflow->storeManualAttendance($request->user(), $payload),
        ], 'Presensi manual tersimpan.');
    }

    public function storeTahsinAssessment(StoreMobileTahsinAssessmentRequest $request)
    {
        return MobileApiResponse::ok([
            'assessment' => $this->workflow->storeTahsinAssessment($request->validated(), $request->user()),
        ], 'Assessment tahsin tersimpan.', status: 201);
    }
}
