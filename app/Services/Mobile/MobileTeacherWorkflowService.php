<?php

namespace App\Services\Mobile;

use App\Models\AttendanceRecord;
use App\Models\AttendanceSession;
use App\Models\ClassRoom;
use App\Models\HafalanRecord;
use App\Models\Student;
use App\Models\User;
use App\Services\Attendance\AttendanceScanService;
use App\Services\Mutabaah\MutabaahRecordService;
use App\Services\Notifications\NotificationDispatchService;
use App\Services\Tahfizh\HafalanRecordService;
use App\Services\Tahsin\TahsinAssessmentService;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;

class MobileTeacherWorkflowService
{
    public function __construct(
        private readonly MobileAccessService $access,
        private readonly MobilePortalSummaryService $summaries,
        private readonly HafalanRecordService $hafalanRecordService,
        private readonly MutabaahRecordService $mutabaahRecords,
        private readonly TahsinAssessmentService $tahsinAssessments,
        private readonly AttendanceScanService $attendanceScanner,
    ) {}

    public function dashboard(User $teacher): array
    {
        $this->access->ensureRole($teacher, ['super_admin', 'admin', 'teacher']);
        $schoolId = $this->access->activeSchoolId();

        return [
            'students_count' => Student::query()->where('school_id', $schoolId)->where('is_active', true)->count(),
            'classes_count' => ClassRoom::query()->where('school_id', $schoolId)->where('is_active', true)->count(),
            'tahfizh_records_today' => HafalanRecord::query()->where('school_id', $schoolId)->whereDate('record_date', now()->toDateString())->count(),
            'attendance_sessions_today' => AttendanceSession::query()->where('school_id', $schoolId)->whereDate('attendance_date', now()->toDateString())->count(),
            'students_need_attention' => Student::query()
                ->where('school_id', $schoolId)
                ->whereHas('tahfizhDebts', fn ($query) => $query->where('status', 'behind'))
                ->limit(10)
                ->get()
                ->map(fn (Student $student): array => $this->summaries->studentCard($student))
                ->values(),
        ];
    }

    public function classes(User $teacher)
    {
        $this->access->ensureRole($teacher, ['super_admin', 'admin', 'teacher']);

        return ClassRoom::query()
            ->withCount('students')
            ->where('school_id', $this->access->activeSchoolId())
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    public function students(User $teacher)
    {
        $this->access->ensureRole($teacher, ['super_admin', 'admin', 'teacher']);

        return Student::query()
            ->with('classRoom')
            ->where('school_id', $this->access->activeSchoolId())
            ->where('is_active', true)
            ->orderBy('full_name')
            ->limit(200)
            ->get()
            ->map(fn (Student $student): array => $this->summaries->studentCard($student))
            ->values();
    }

    public function storeTahfizhRecord(array $payload, User $teacher): HafalanRecord
    {
        $student = Student::query()->findOrFail($payload['student_id']);
        $this->access->ensureTeacherCanAccessStudent($teacher, $student);

        // Populate school_id implicitly from student
        $payload['school_id'] = $student->school_id;

        $record = $this->hafalanRecordService->createRecord($payload, $teacher);

        app(NotificationDispatchService::class)->notifyHafalanRecordCreated($record);

        return $record->fresh(['student', 'teacher', 'startSurah', 'endSurah']);
    }

    public function storeMutabaahRecords(array $payload, User $teacher)
    {
        $student = Student::query()->findOrFail($payload['student_id']);
        $this->access->ensureTeacherCanAccessStudent($teacher, $student);

        return $this->mutabaahRecords->saveDailyRecords(
            student: $student,
            recordDate: $payload['record_date'],
            records: $payload['records'],
            submittedBy: $teacher,
            source: 'teacher_mobile'
        );
    }

    public function storeTahsinAssessment(array $payload, User $teacher)
    {
        $student = Student::query()->findOrFail($payload['student_id']);
        $this->access->ensureTeacherCanAccessStudent($teacher, $student);

        $payload['teacher_id'] = $teacher->hasRole('teacher') ? $teacher->id : ($payload['teacher_id'] ?? $teacher->id);

        return $this->tahsinAssessments->createAssessment($payload, $teacher);
    }

    public function attendanceSessions(User $teacher)
    {
        $this->access->ensureRole($teacher, ['super_admin', 'admin', 'teacher']);

        return AttendanceSession::query()
            ->with('classRoom')
            ->where('school_id', $this->access->activeSchoolId())
            ->latest('attendance_date')
            ->latest('id')
            ->limit(50)
            ->get();
    }

    public function scanAttendance(User $teacher, int $sessionId, string $qrPayload): AttendanceRecord
    {
        $this->access->ensureRole($teacher, ['super_admin', 'admin', 'teacher']);

        $session = AttendanceSession::query()
            ->where('school_id', $this->access->activeSchoolId())
            ->findOrFail($sessionId);

        return $this->attendanceScanner->scan($qrPayload, $session, $teacher);
    }

    public function storeManualAttendance(User $teacher, array $payload): AttendanceRecord
    {
        $this->access->ensureRole($teacher, ['super_admin', 'admin', 'teacher']);

        $session = AttendanceSession::query()
            ->where('school_id', $this->access->activeSchoolId())
            ->findOrFail($payload['attendance_session_id']);

        $student = Student::query()->findOrFail($payload['student_id']);
        $this->access->ensureTeacherCanAccessStudent($teacher, $student);

        return AttendanceRecord::query()->updateOrCreate(
            [
                'attendance_session_id' => $session->id,
                'student_id' => $student->id,
            ],
            [
                'school_id' => $student->school_id,
                'attendance_date' => $session->attendance_date,
                'status' => $payload['status'],
                'check_in_at' => $payload['check_in_at'] ?? null,
                'check_out_at' => $payload['check_out_at'] ?? null,
                'source' => 'manual_mobile',
                'note' => $payload['note'] ?? null,
                'updated_by' => $teacher->id,
            ]
        );
    }
}
