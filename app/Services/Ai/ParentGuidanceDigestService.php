<?php

namespace App\Services\Ai;

use App\Models\Student;
use App\Models\AiLearningProfile;
use App\Models\AiLearningRecommendation;
use App\Models\AiPracticePlan;
use App\Services\Ai\AiSafetyGuardService;

class ParentGuidanceDigestService
{
    protected AiSafetyGuardService $safetyGuard;

    public function __construct(AiSafetyGuardService $safetyGuard)
    {
        $this->safetyGuard = $safetyGuard;
    }

    public function getDigestForChild(Student $student): array
    {
        // Fetch latest published profile
        $profile = AiLearningProfile::query()
            ->withoutGlobalScopes()
            ->where('student_id', $student->id)
            ->where('profile_status', 'published')
            ->latest('profile_date')
            ->first();

        // Fetch published recommendations
        $recommendations = AiLearningRecommendation::query()
            ->withoutGlobalScopes()
            ->where('student_id', $student->id)
            ->where('status', 'published')
            ->get();

        // Fetch published practice plans
        $practicePlans = AiPracticePlan::query()
            ->withoutGlobalScopes()
            ->where('student_id', $student->id)
            ->where('status', 'published')
            ->with(['items'])
            ->get();

        // Sanitize & Format
        $digestSummary = $profile ? $this->safetyGuard->sanitizeForParent($profile->summary ?? '') : 'Belum ada ringkasan laporan berkala.';

        $formattedRecs = $recommendations->map(function ($rec) {
            return [
                'id' => $rec->id,
                'title' => $rec->title,
                'description' => $this->safetyGuard->sanitizeForParent($rec->description ?? ''),
                'recommendation_type' => $rec->recommendation_type,
                'priority' => $rec->priority,
                'recommended_actions' => $rec->recommended_actions ?? [],
            ];
        });

        return [
            'student_name' => $student->user?->name ?? 'Ananda',
            'profile' => $profile ? [
                'id' => $profile->id,
                'profile_date' => $profile->profile_date?->toDateString(),
                'summary' => $digestSummary,
                'strengths' => $profile->strengths ?? [],
                'focus_areas' => $profile->focus_areas ?? [],
                'tahfizh_trend' => $profile->tahfizh_trend,
                'tahsin_trend' => $profile->tahsin_trend,
                'mutabaah_trend' => $profile->mutabaah_trend,
            ] : null,
            'recommendations' => $formattedRecs,
            'practice_plans' => $practicePlans,
        ];
    }
}
