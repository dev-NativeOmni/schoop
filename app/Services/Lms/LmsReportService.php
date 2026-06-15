<?php

namespace App\Services\Lms;

use App\Models\LmsCourse;
use App\Models\LmsCourseEnrollment;
use App\Models\LmsLesson;
use App\Models\LmsLessonProgress;
use App\Models\LmsAssignmentSubmission;
use App\Models\LmsQuizAttempt;
use Illuminate\Support\Facades\DB;

class LmsReportService
{
    public function getCourseCompletionStats(int $courseId): array
    {
        $totalEnrolled = LmsCourseEnrollment::where('course_id', $courseId)->count();
        $completedCount = LmsCourseEnrollment::where('course_id', $courseId)
            ->where('status', 'completed')
            ->count();

        $completionRate = $totalEnrolled > 0 ? ($completedCount / $totalEnrolled) * 100 : 0;

        return [
            'total_enrolled' => $totalEnrolled,
            'completed_count' => $completedCount,
            'completion_rate' => round($completionRate, 2),
        ];
    }

    public function getLessonProgressStats(int $lessonId): array
    {
        $lesson = LmsLesson::findOrFail($lessonId);
        $courseId = $lesson->course_id;

        // Only count students enrolled in the course
        $enrolledStudentIds = LmsCourseEnrollment::where('course_id', $courseId)
            ->pluck('student_id')
            ->toArray();

        if (empty($enrolledStudentIds)) {
            return [
                'not_started' => 0,
                'in_progress' => 0,
                'completed' => 0,
            ];
        }

        $progressCounts = LmsLessonProgress::where('lesson_id', $lessonId)
            ->whereIn('student_id', $enrolledStudentIds)
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $completed = $progressCounts['completed'] ?? 0;
        $inProgress = $progressCounts['in_progress'] ?? 0;
        $totalEnrolled = count($enrolledStudentIds);
        $notStarted = max(0, $totalEnrolled - $completed - $inProgress);

        return [
            'not_started' => $notStarted,
            'in_progress' => $inProgress,
            'completed' => $completed,
        ];
    }

    public function getStudentCourseSummary(int $studentId, int $courseId): array
    {
        $enrollment = LmsCourseEnrollment::where('course_id', $courseId)
            ->where('student_id', $studentId)
            ->first();

        if (!$enrollment) {
            return [];
        }

        $course = LmsCourse::with(['lessons'])->find($courseId);
        $lessons = $course->lessons;
        $lessonIds = $lessons->pluck('id')->toArray();

        $completedLessonsCount = LmsLessonProgress::where('student_id', $studentId)
            ->whereIn('lesson_id', $lessonIds)
            ->where('status', 'completed')
            ->count();

        // Assignment submissions
        $assignmentSubmissions = LmsAssignmentSubmission::where('student_id', $studentId)
            ->whereIn('assignment_id', function ($query) use ($courseId) {
                $query->select('id')->from('lms_assignments')->where('course_id', $courseId);
            })->get();

        // Quiz attempts
        $quizAttempts = LmsQuizAttempt::where('student_id', $studentId)
            ->whereIn('quiz_id', function ($query) use ($courseId) {
                $query->select('id')->from('lms_quizzes')->where('course_id', $courseId);
            })->get();

        return [
            'progress_percentage' => $enrollment->progress_percentage,
            'status' => $enrollment->status,
            'total_lessons' => count($lessonIds),
            'completed_lessons' => $completedLessonsCount,
            'assignments_submitted' => $assignmentSubmissions->count(),
            'assignments_average_score' => $assignmentSubmissions->avg('score') ?? 0.00,
            'quizzes_attempted' => $quizAttempts->unique('quiz_id')->count(),
            'quizzes_average_score' => $quizAttempts->where('status', 'completed')->avg('score') ?? 0.00,
        ];
    }
}
