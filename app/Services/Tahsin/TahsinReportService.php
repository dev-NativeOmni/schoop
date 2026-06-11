<?php

namespace App\Services\Tahsin;

use App\Models\Student;
use App\Models\TahsinAssessment;
use App\Models\TahsinStudentProfile;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class TahsinReportService
{
    public function dashboard(array $filters = []): array
    {
        $startDate = Carbon::parse($filters['start_date'] ?? now()->startOfMonth()->toDateString())->toDateString();
        $endDate = Carbon::parse($filters['end_date'] ?? now()->toDateString())->toDateString();

        $assessmentQuery = TahsinAssessment::query()
            ->with(['student.classRoom', 'teacher', 'level'])
            ->whereBetween('assessment_date', [$startDate, $endDate]);

        if (! empty($filters['class_room_id'])) {
            $assessmentQuery->whereHas('student', function (Builder $query) use ($filters): void {
                $query->where('class_room_id', $filters['class_room_id']);
            });
        }

        if (! empty($filters['student_id'])) {
            $assessmentQuery->where('student_id', $filters['student_id']);
        }

        if (! empty($filters['teacher_id'])) {
            $assessmentQuery->where('teacher_id', $filters['teacher_id']);
        }

        if (! empty($filters['tahsin_level_id'])) {
            $assessmentQuery->where('tahsin_level_id', $filters['tahsin_level_id']);
        }

        $assessments = $assessmentQuery->get();

        return [
            'period' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
            ],
            'summary' => [
                'total_assessments' => $assessments->count(),
                'average_score' => $assessments->count() > 0
                    ? round($assessments->avg('overall_score'), 2)
                    : 0,
                'excellent' => $assessments->where('grade', 'excellent')->count(),
                'good' => $assessments->where('grade', 'good')->count(),
                'fair' => $assessments->where('grade', 'fair')->count(),
                'needs_improvement' => $assessments->where('grade', 'needs_improvement')->count(),
            ],
            'by_student' => $this->groupByStudent($assessments),
            'assessments' => $assessments->sortByDesc('assessment_date')->values(),
        ];
    }

    public function studentSnapshot(Student $student, array $filters = []): array
    {
        $startDate = Carbon::parse($filters['start_date'] ?? now()->startOfMonth()->toDateString())->toDateString();
        $endDate = Carbon::parse($filters['end_date'] ?? now()->toDateString())->toDateString();

        $profile = TahsinStudentProfile::query()
            ->with(['currentLevel', 'assignedTeacher'])
            ->where('student_id', $student->id)
            ->first();

        $assessments = TahsinAssessment::query()
            ->with(['level', 'teacher', 'items.skill'])
            ->where('student_id', $student->id)
            ->whereBetween('assessment_date', [$startDate, $endDate])
            ->orderByDesc('assessment_date')
            ->get();

        return [
            'student' => $student,
            'profile' => $profile,
            'period' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
            ],
            'summary' => [
                'total_assessments' => $assessments->count(),
                'average_score' => $assessments->count() > 0
                    ? round($assessments->avg('overall_score'), 2)
                    : 0,
                'latest_score' => $assessments->first()?->overall_score,
                'latest_grade' => $assessments->first()?->grade,
            ],
            'assessments' => $assessments,
        ];
    }

    private function groupByStudent(Collection $assessments): Collection
    {
        return $assessments
            ->groupBy('student_id')
            ->map(function (Collection $studentAssessments): array {
                $first = $studentAssessments->first();

                return [
                    'student' => $first?->student,
                    'total' => $studentAssessments->count(),
                    'average_score' => $studentAssessments->count() > 0
                        ? round($studentAssessments->avg('overall_score'), 2)
                        : 0,
                    'latest_assessment' => $studentAssessments->sortByDesc('assessment_date')->first(),
                ];
            })
            ->sortByDesc('average_score')
            ->values();
    }
}
