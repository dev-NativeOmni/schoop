<?php

namespace App\Services\Lms;

use App\Models\LmsAssignmentSubmission;
use App\Models\LmsCourse;
use App\Models\LmsCourseEnrollment;
use App\Models\LmsQuizAttempt;
use App\Models\SchoolAcademicSnapshot;
use App\Services\Tenancy\TenantContextService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LmsAnalyticsSnapshotService
{
    public function __construct(
        private readonly TenantContextService $tenantContext,
    ) {
        //
    }

    public function generateSnapshot(int $schoolId, ?Carbon $date = null): SchoolAcademicSnapshot
    {
        $date = $date ?? Carbon::today();

        // Scope queries to the specific school_id
        // Since we might run this in a background command (globally or per school),
        // we'll fetch stats scoped to the school.
        $totalCourses = LmsCourse::withoutGlobalScope('school')
            ->where('school_id', $schoolId)
            ->count();

        $totalEnrollments = LmsCourseEnrollment::withoutGlobalScope('school')
            ->where('school_id', $schoolId)
            ->count();

        $completedEnrollments = LmsCourseEnrollment::withoutGlobalScope('school')
            ->where('school_id', $schoolId)
            ->where('status', 'completed')
            ->count();

        $averageProgress = LmsCourseEnrollment::withoutGlobalScope('school')
            ->where('school_id', $schoolId)
            ->avg('progress_percentage') ?? 0.00;

        $assignmentsSubmitted = LmsAssignmentSubmission::withoutGlobalScope('school')
            ->where('school_id', $schoolId)
            ->count();

        $quizAttempts = LmsQuizAttempt::withoutGlobalScope('school')
            ->where('school_id', $schoolId)
            ->count();

        // Find or create academic snapshot for this school and date
        $snapshot = SchoolAcademicSnapshot::firstOrNew([
            'school_id' => $schoolId,
            'snapshot_date' => $date->toDateString(),
            'period_type' => 'daily',
        ]);

        $rawMetrics = $snapshot->raw_metrics ?? [];
        $rawMetrics['lms'] = [
            'courses_count' => $totalCourses,
            'enrollments_count' => $totalEnrollments,
            'completed_enrollments_count' => $completedEnrollments,
            'average_progress_percentage' => round($averageProgress, 2),
            'assignments_submitted_count' => $assignmentsSubmitted,
            'quiz_attempts_count' => $quizAttempts,
        ];

        $snapshot->raw_metrics = $rawMetrics;
        $snapshot->save();

        return $snapshot;
    }

    public function generateAllSnapshots(?Carbon $date = null): int
    {
        $date = $date ?? Carbon::today();

        // Find all schools in database
        $schoolIds = DB::table('schools')->pluck('id');
        $count = 0;

        foreach ($schoolIds as $schoolId) {
            $this->generateSnapshot($schoolId, $date);
            $count++;
        }

        return $count;
    }
}
