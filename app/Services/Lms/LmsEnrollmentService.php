<?php

namespace App\Services\Lms;

use App\Models\LmsCourse;
use App\Models\LmsCourseEnrollment;
use App\Models\Student;
use App\Services\Tenancy\TenantContextService;
use Illuminate\Support\Facades\DB;

class LmsEnrollmentService
{
    public function __construct(
        private readonly TenantContextService $tenantContext,
        private readonly LmsActivityLogger $logger,
    ) {
        //
    }

    public function enrollStudent(int $courseId, int $studentId): LmsCourseEnrollment
    {
        return DB::transaction(function () use ($courseId, $studentId) {
            $schoolId = $this->tenantContext->activeSchoolId();

            $enrollment = LmsCourseEnrollment::firstOrCreate([
                'course_id' => $courseId,
                'student_id' => $studentId,
            ], [
                'school_id' => $schoolId,
                'progress_percentage' => 0.00,
                'status' => 'active',
                'enrolled_at' => now(),
            ]);

            $student = Student::find($studentId);
            $course = LmsCourse::find($courseId);
            $this->logger->log('course_enroll', "Student '{$student->full_name}' enrolled in course '{$course->title}'.", $enrollment);

            return $enrollment;
        });
    }

    public function enrollClassRoom(int $courseId, int $classRoomId): int
    {
        return DB::transaction(function () use ($courseId, $classRoomId) {
            $students = Student::where('class_room_id', $classRoomId)
                ->where('is_active', true)
                ->get();

            $count = 0;
            foreach ($students as $student) {
                $this->enrollStudent($courseId, $student->id);
                $count++;
            }

            return $count;
        });
    }

    public function unenrollStudent(int $courseId, int $studentId): void
    {
        DB::transaction(function () use ($courseId, $studentId) {
            $enrollment = LmsCourseEnrollment::where('course_id', $courseId)
                ->where('student_id', $studentId)
                ->first();

            if ($enrollment) {
                $student = Student::find($studentId);
                $course = LmsCourse::find($courseId);
                $enrollment->delete();
                $this->logger->log('course_unenroll', "Student '{$student->full_name}' unenrolled from course '{$course->title}'.", $course);
            }
        });
    }
}
