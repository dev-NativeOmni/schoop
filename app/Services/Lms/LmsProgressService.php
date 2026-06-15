<?php

namespace App\Services\Lms;

use App\Models\LmsCourse;
use App\Models\LmsCourseEnrollment;
use App\Models\LmsLesson;
use App\Models\LmsLessonProgress;
use App\Services\Tenancy\TenantContextService;
use Illuminate\Support\Facades\DB;

class LmsProgressService
{
    public function __construct(
        private readonly TenantContextService $tenantContext,
        private readonly LmsActivityLogger $logger,
    ) {
        //
    }

    public function markAsInProgress(int $studentId, int $lessonId): LmsLessonProgress
    {
        $schoolId = $this->tenantContext->activeSchoolId();
        
        $progress = LmsLessonProgress::firstOrCreate([
            'lesson_id' => $lessonId,
            'student_id' => $studentId,
        ], [
            'school_id' => $schoolId,
            'status' => 'in_progress',
        ]);

        if ($progress->status === 'not_started') {
            $progress->update(['status' => 'in_progress']);
        }

        return $progress;
    }

    public function markAsComplete(int $studentId, int $lessonId): LmsLessonProgress
    {
        return DB::transaction(function () use ($studentId, $lessonId) {
            $schoolId = $this->tenantContext->activeSchoolId();

            $progress = LmsLessonProgress::updateOrCreate([
                'lesson_id' => $lessonId,
                'student_id' => $studentId,
            ], [
                'school_id' => $schoolId,
                'status' => 'completed',
                'completed_at' => now(),
            ]);

            $lesson = LmsLesson::find($lessonId);
            if ($lesson) {
                $this->recalculateProgress($studentId, $lesson->course_id);
                $this->logger->log('lesson_complete', "Lesson '{$lesson->title}' marked as complete.", $progress);
            }

            return $progress;
        });
    }

    public function recalculateProgress(int $studentId, int $courseId): float
    {
        return DB::transaction(function () use ($studentId, $courseId) {
            $course = LmsCourse::find($courseId);
            if (!$course) {
                return 0.00;
            }

            $lessonIds = $course->lessons()->pluck('id')->toArray();
            if (empty($lessonIds)) {
                $this->updateEnrollmentProgress($studentId, $courseId, 0.00);
                return 0.00;
            }

            $requiredLessonsQuery = $course->lessons()->where('is_required', true);
            $totalRequired = $requiredLessonsQuery->count();

            if ($totalRequired > 0) {
                $requiredLessonIds = $requiredLessonsQuery->pluck('id')->toArray();
                $completedRequired = LmsLessonProgress::where('student_id', $studentId)
                    ->whereIn('lesson_id', $requiredLessonIds)
                    ->where('status', 'completed')
                    ->count();

                $percentage = ($completedRequired / $totalRequired) * 100.00;
            } else {
                // If no lessons are strictly marked as required, use all lessons
                $totalLessons = count($lessonIds);
                $completedLessons = LmsLessonProgress::where('student_id', $studentId)
                    ->whereIn('lesson_id', $lessonIds)
                    ->where('status', 'completed')
                    ->count();

                $percentage = ($completedLessons / $totalLessons) * 100.00;
            }

            // Cap between 0 and 100
            $percentage = max(0.00, min(100.00, round($percentage, 2)));

            $this->updateEnrollmentProgress($studentId, $courseId, $percentage);

            return $percentage;
        });
    }

    private function updateEnrollmentProgress(int $studentId, int $courseId, float $percentage): void
    {
        $enrollment = LmsCourseEnrollment::where('course_id', $courseId)
            ->where('student_id', $studentId)
            ->first();

        if ($enrollment) {
            $updateData = [
                'progress_percentage' => $percentage,
            ];

            if ($percentage >= 100.00 && $enrollment->status !== 'completed') {
                $updateData['status'] = 'completed';
                $updateData['completed_at'] = now();
            } elseif ($percentage < 100.00 && $enrollment->status === 'completed') {
                $updateData['status'] = 'active';
                $updateData['completed_at'] = null;
            }

            $enrollment->update($updateData);
        }
    }

    public function recalculateAllProgress(): int
    {
        $enrollments = LmsCourseEnrollment::all();
        $count = 0;
        foreach ($enrollments as $enrollment) {
            $this->recalculateProgress($enrollment->student_id, $enrollment->course_id);
            $count++;
        }
        return $count;
    }
}
