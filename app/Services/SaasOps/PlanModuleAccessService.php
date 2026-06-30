<?php

namespace App\Services\SaasOps;

use App\Models\SaasSchoolSubscription;
use App\Models\SaasSubscriptionPlan;
use App\Models\SystemModule;
use App\Models\TenantModule;
use App\Models\User;
use Illuminate\Support\Collection;

class PlanModuleAccessService
{
    private const ACCESSIBLE_SUBSCRIPTION_STATUSES = ['trial', 'active', 'grace_period'];

    public function currentSubscription(int $schoolId): ?SaasSchoolSubscription
    {
        return SaasSchoolSubscription::query()
            ->with('plan')
            ->where('school_id', $schoolId)
            ->latest('id')
            ->first();
    }

    public function subscriptionAllowsAccess(int $schoolId): bool
    {
        $subscription = $this->currentSubscription($schoolId);

        if (! $subscription) {
            return true;
        }

        return in_array($subscription->status, self::ACCESSIBLE_SUBSCRIPTION_STATUSES, true);
    }

    public function moduleIsAllowedByPlan(int $schoolId, string $moduleKey): bool
    {
        $subscription = $this->currentSubscription($schoolId);

        if (! $subscription) {
            return true;
        }

        if (! in_array($subscription->status, self::ACCESSIBLE_SUBSCRIPTION_STATUSES, true)) {
            return false;
        }

        return in_array($moduleKey, $this->allowedModulesForPlan($subscription->plan), true);
    }

    public function canAccessModule(?User $user, int $schoolId, string $moduleKey): bool
    {
        if ($user?->hasRole(['super_admin', 'operations_manager'])) {
            return true;
        }

        if (! $this->currentSubscription($schoolId)) {
            $tenantModule = TenantModule::query()
                ->where('school_id', $schoolId)
                ->where('module_key', $moduleKey)
                ->first();

            return $tenantModule ? (bool) $tenantModule->is_enabled : true;
        }

        if (! $this->moduleIsAllowedByPlan($schoolId, $moduleKey)) {
            return false;
        }

        return TenantModule::query()
            ->where('school_id', $schoolId)
            ->where('module_key', $moduleKey)
            ->where('is_enabled', true)
            ->exists();
    }

    public function syncTenantModulesForSubscription(SaasSchoolSubscription $subscription): void
    {
        $subscription->loadMissing('plan');

        $allowedModules = $this->allowedModulesForPlan($subscription->plan);
        $systemModules = $this->tenantManagedSystemModules();

        foreach ($systemModules as $systemModule) {
            $allowed = in_array($systemModule->module_key, $allowedModules, true);

            TenantModule::query()->updateOrCreate(
                [
                    'school_id' => $subscription->school_id,
                    'module_key' => $systemModule->module_key,
                ],
                [
                    'module_name' => $systemModule->name,
                    'is_enabled' => $allowed
                        ? $this->existingEnabledState((int) $subscription->school_id, $systemModule->module_key)
                        : false,
                ]
            );
        }
    }

    public function syncTenantModulesForPlan(SaasSubscriptionPlan $plan): void
    {
        $plan->subscriptions()
            ->with('plan')
            ->each(fn (SaasSchoolSubscription $subscription) => $this->syncTenantModulesForSubscription($subscription));
    }

    public function allowedModulesForPlan(?SaasSubscriptionPlan $plan): array
    {
        if (! $plan) {
            return [];
        }

        $allowedModules = $plan->allowed_modules;

        if (is_array($allowedModules) && $allowedModules !== []) {
            return $this->normalizeModuleKeys($allowedModules);
        }

        return $this->inferModuleKeysFromFeatures($plan->features ?? []);
    }

    public function tenantManagedSystemModules(): Collection
    {
        return SystemModule::query()
            ->whereNull('school_id')
            ->where('is_active', true)
            ->whereNotIn('module_key', ['saas_ops'])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
    }

    private function existingEnabledState(int $schoolId, string $moduleKey): bool
    {
        $existing = TenantModule::query()
            ->where('school_id', $schoolId)
            ->where('module_key', $moduleKey)
            ->first();

        return $existing ? (bool) $existing->is_enabled : true;
    }

    private function normalizeModuleKeys(array $moduleKeys): array
    {
        $validKeys = $this->tenantManagedSystemModules()
            ->pluck('module_key')
            ->all();

        return collect($moduleKeys)
            ->map(fn ($key) => trim((string) $key))
            ->filter()
            ->unique()
            ->intersect($validKeys)
            ->values()
            ->all();
    }

    private function inferModuleKeysFromFeatures(array $features): array
    {
        $moduleLookup = $this->tenantManagedSystemModules()
            ->mapWithKeys(fn (SystemModule $module) => [
                strtolower($module->module_key) => $module->module_key,
                strtolower($module->name) => $module->module_key,
            ]);

        return collect($features)
            ->map(fn ($feature) => strtolower(trim((string) $feature)))
            ->flatMap(function (string $feature) use ($moduleLookup): array {
                $matches = [];

                foreach ($moduleLookup as $label => $moduleKey) {
                    if ($feature === $label || str_contains($feature, $label)) {
                        $matches[] = $moduleKey;
                    }
                }

                return $matches;
            })
            ->unique()
            ->values()
            ->all();
    }
}
