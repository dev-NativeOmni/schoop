<?php

namespace App\Services\Ai;

use App\Models\AiFeatureFlag;

class AiFeatureFlagService
{
    public function isEnabled(int $schoolId, string $featureKey): bool
    {
        $flag = AiFeatureFlag::query()
            ->withoutGlobalScopes()
            ->where('school_id', $schoolId)
            ->where('feature_key', $featureKey)
            ->first();

        return $flag ? (bool) $flag->is_enabled : false;
    }

    public function requiresTeacherReview(int $schoolId, string $featureKey): bool
    {
        $flag = AiFeatureFlag::query()
            ->withoutGlobalScopes()
            ->where('school_id', $schoolId)
            ->where('feature_key', $featureKey)
            ->first();

        return $flag ? (bool) $flag->requires_teacher_review : true;
    }

    public function visibleToParent(int $schoolId, string $featureKey): bool
    {
        $flag = AiFeatureFlag::query()
            ->withoutGlobalScopes()
            ->where('school_id', $schoolId)
            ->where('feature_key', $featureKey)
            ->first();

        return $flag ? (bool) $flag->visible_to_parent : false;
    }

    public function visibleToStudent(int $schoolId, string $featureKey): bool
    {
        $flag = AiFeatureFlag::query()
            ->withoutGlobalScopes()
            ->where('school_id', $schoolId)
            ->where('feature_key', $featureKey)
            ->first();

        return $flag ? (bool) $flag->visible_to_student : false;
    }
}
