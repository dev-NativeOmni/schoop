<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\AttendanceRecord;
use App\Models\AttendanceSession;
use App\Models\Student;
use App\Services\DeveloperPortal\ApiAccessService;
use App\Services\DeveloperPortal\ApiResponseFormatter;
use Illuminate\Http\Request;

class AttendanceApiController extends Controller
{
    public function __construct(
        private readonly ApiAccessService $access,
        private readonly ApiResponseFormatter $response,
    ) {}

    public function index(Request $request)
    {
        $schoolId = $this->access->externalSchoolId($request);

        return $this->response->ok([
            'attendance_records' => AttendanceRecord::query()
                ->where('school_id', $schoolId)
                ->when($request->filled('student_id'), fn ($query) => $query->where('student_id', $request->integer('student_id')))
                ->when($request->filled('date_from'), fn ($query) => $query->whereDate('attendance_date', '>=', $request->date('date_from')))
                ->when($request->filled('date_until'), fn ($query) => $query->whereDate('attendance_date', '<=', $request->date('date_until')))
                ->latest('attendance_date')
                ->limit(200)
                ->get()
                ->map(fn (AttendanceRecord $record): array => $this->payload($record))
                ->values(),
        ]);
    }

    public function store(Request $request)
    {
        $payload = $request->validate([
            'attendance_session_id' => ['required', 'integer', 'exists:attendance_sessions,id'],
            'student_id' => ['required', 'integer', 'exists:students,id'],
            'status' => ['required', 'string', 'in:present,late,sick,permission,absent'],
            'check_in_at' => ['nullable', 'date'],
            'check_out_at' => ['nullable', 'date'],
            'note' => ['nullable', 'string', 'max:2000'],
        ]);

        $schoolId = $this->access->externalSchoolId($request);
        $session = AttendanceSession::query()->where('school_id', $schoolId)->findOrFail($payload['attendance_session_id']);
        $student = Student::query()->where('school_id', $schoolId)->findOrFail($payload['student_id']);

        $record = AttendanceRecord::query()->updateOrCreate(
            [
                'attendance_session_id' => $session->id,
                'student_id' => $student->id,
            ],
            [
                'school_id' => $schoolId,
                'attendance_date' => $session->attendance_date,
                'status' => $payload['status'],
                'check_in_at' => $payload['check_in_at'] ?? null,
                'check_out_at' => $payload['check_out_at'] ?? null,
                'source' => 'external_api',
                'note' => $payload['note'] ?? null,
            ]
        );

        return $this->response->ok([
            'attendance_record' => $this->payload($record),
        ], 'Attendance record stored.', status: 201);
    }

    private function payload(AttendanceRecord $record): array
    {
        return [
            'id' => $record->id,
            'student_id' => $record->student_id,
            'date' => $record->attendance_date?->toDateString(),
            'status' => $record->status,
            'check_in_at' => $record->check_in_at?->toIso8601String(),
            'check_out_at' => $record->check_out_at?->toIso8601String(),
        ];
    }
}
