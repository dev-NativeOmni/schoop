<?php

namespace App\Services\Tahsin;

use App\Models\Student;
use App\Models\TahsinAssessment;
use App\Models\TahsinAssessmentItem;
use App\Models\TahsinSkill;
use App\Models\TahsinStudentProfile;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TahsinAssessmentService
{
    public function createAssessment(array $data, User $user): TahsinAssessment
    {
        return DB::transaction(function () use ($data, $user): TahsinAssessment {
            $student = Student::query()->findOrFail($data['student_id']);

            $items = collect($data['items'] ?? []);

            if ($items->isEmpty()) {
                throw ValidationException::withMessages([
                    'items' => 'Minimal satu skill tahsin harus dinilai.',
                ]);
            }

            $overallScore = $this->calculateOverallScore($items);
            $grade = $this->resolveGrade($overallScore);

            $assessment = TahsinAssessment::query()->create([
                'school_id' => $student->school_id ?? null,
                'student_id' => $student->id,
                'teacher_id' => $data['teacher_id'] ?? $user->id,
                'tahsin_level_id' => $data['tahsin_level_id'] ?? null,
                'assessment_date' => $data['assessment_date'],
                'assessment_type' => $data['assessment_type'],
                'overall_score' => $overallScore,
                'grade' => $grade,
                'status' => $data['status'] ?? 'submitted',
                'note' => $data['note'] ?? null,
                'recommendation' => $data['recommendation'] ?? null,
                'created_by' => $user->id,
            ]);

            foreach ($items as $item) {
                $skill = TahsinSkill::query()
                    ->whereKey($item['tahsin_skill_id'] ?? null)
                    ->where('is_active', true)
                    ->first();

                if (! $skill) {
                    throw ValidationException::withMessages([
                        'items' => 'Skill tahsin tidak ditemukan atau tidak aktif.',
                    ]);
                }

                TahsinAssessmentItem::query()->create([
                    'tahsin_assessment_id' => $assessment->id,
                    'tahsin_skill_id' => $skill->id,
                    'score' => $item['score'] ?? 0,
                    'status' => $item['status'] ?? 'not_tested',
                    'note' => $item['note'] ?? null,
                ]);
            }

            $this->syncStudentProfile($student, $assessment);

            return $assessment->fresh(['student', 'teacher', 'level', 'items.skill']);
        });
    }

    private function calculateOverallScore(Collection $items): float
    {
        $scores = $items
            ->pluck('score')
            ->filter(fn ($score): bool => $score !== null)
            ->map(fn ($score): float => (float) $score);

        if ($scores->isEmpty()) {
            return 0;
        }

        return round($scores->avg(), 2);
    }

    private function resolveGrade(float $overallScore): string
    {
        if ($overallScore >= 90) {
            return TahsinAssessment::GRADE_EXCELLENT;
        }

        if ($overallScore >= 80) {
            return TahsinAssessment::GRADE_GOOD;
        }

        if ($overallScore >= 70) {
            return TahsinAssessment::GRADE_FAIR;
        }

        return TahsinAssessment::GRADE_NEEDS_IMPROVEMENT;
    }

    private function syncStudentProfile(Student $student, TahsinAssessment $assessment): void
    {
        $status = $assessment->overall_score >= 70
            ? TahsinStudentProfile::STATUS_IN_PROGRESS
            : TahsinStudentProfile::STATUS_NEEDS_ATTENTION;

        $profile = TahsinStudentProfile::query()->firstOrNew([
            'student_id' => $student->id,
        ]);

        $profile->fill([
            'school_id' => $student->school_id ?? null,
            'current_tahsin_level_id' => $assessment->tahsin_level_id,
            'assigned_teacher_id' => $assessment->teacher_id,
            'status' => $status,
            'placement_score' => $assessment->assessment_type === TahsinAssessment::TYPE_PLACEMENT
                ? $assessment->overall_score
                : $profile->placement_score,
            'started_at' => $profile->started_at ?? now()->toDateString(),
        ]);

        $profile->save();
    }
}
