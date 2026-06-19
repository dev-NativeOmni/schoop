<?php

namespace App\Services\Billing;

use App\Models\School;
use App\Models\SchoolSubscription;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PlanLimitService
{
    public function getPlanLimit(School $school, string $limitKey): ?int
    {
        $subscription = SchoolSubscription::query()
            ->with('plan')
            ->where('school_id', $school->id)
            ->whereIn('status', ['trialing', 'active'])
            ->latest('id')
            ->first();

        if (! $subscription || ! $subscription->plan) {
            return null;
        }

        $limits = $subscription->plan->limits ?? [];

        return isset($limits[$limitKey]) ? (int) $limits[$limitKey] : null;
    }

    public function getUsage(School $school, string $usageKey): int
    {
        return match ($usageKey) {
            'students_count' => $this->countStudents($school),
            'teachers_count' => $this->countTeacherProfiles($school),
            'parents_count' => $this->countParentProfiles($school),
            'exports_this_month' => 0,
            default => 0,
        };
    }

    public function isWithinLimit(School $school, string $limitKey, int $nextValue = 1): bool
    {
        $limit = $this->getPlanLimit($school, $limitKey);

        if ($limit === null) {
            return true;
        }

        $usageKey = match ($limitKey) {
            'max_students' => 'students_count',
            'max_teachers' => 'teachers_count',
            'max_parents' => 'parents_count',
            'max_exports_per_month' => 'exports_this_month',
            default => null,
        };

        if (! $usageKey) {
            return true;
        }

        return ($this->getUsage($school, $usageKey) + $nextValue) <= $limit;
    }

    public function refreshUsage(School $school): void
    {
        DB::table('subscription_usages')->updateOrInsert(
            [
                'school_id' => $school->id,
                'usage_key' => 'students_count',
                'period_starts_at' => null,
            ],
            [
                'used' => $this->countStudents($school),
                'limit' => $this->getPlanLimit($school, 'max_students'),
                'period_ends_at' => null,
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );
    }

    private function countStudents(School $school): int
    {
        if (! Schema::hasTable('students')) {
            return 0;
        }

        return Student::query()
            ->where('school_id', $school->id)
            ->count();
    }

    private function countTeacherProfiles(School $school): int
    {
        if (! Schema::hasTable('teacher_profiles')) {
            return 0;
        }

        return DB::table('teacher_profiles')
            ->where('school_id', $school->id)
            ->count();
    }

    private function countParentProfiles(School $school): int
    {
        if (! Schema::hasTable('parent_profiles')) {
            return 0;
        }

        return DB::table('parent_profiles')
            ->where('school_id', $school->id)
            ->count();
    }
}
