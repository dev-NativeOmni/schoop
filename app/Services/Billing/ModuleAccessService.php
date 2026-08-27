<?php

namespace App\Services\Billing;

use App\Models\PlanModule;
use App\Models\School;
use App\Models\SchoolModuleOverride;
use App\Models\SchoolSubscription;
use App\Models\SystemModule;
use Illuminate\Support\Collection;

class ModuleAccessService
{
    protected array $cache = [];
    protected array $preloadedSchools = [];

    public function preloadForSchool(School $school): void
    {
        if (isset($this->preloadedSchools[$school->id])) {
            return;
        }

        // 1. Get all active system modules in 1 query
        $systemModules = SystemModule::query()
            ->where('is_active', true)
            ->get()
            ->keyBy('id');

        // 2. Get all active overrides for this school in 1 query
        $overrides = SchoolModuleOverride::query()
            ->where('school_id', $school->id)
            ->where(function ($query): void {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>=', now());
            })
            ->latest('id')
            ->get()
            ->keyBy('system_module_id');

        // 3. Get active subscription + plan modules in 1 query with eager loading
        $subscription = SchoolSubscription::query()
            ->with(['plan.planModules' => function ($q) {
                $q->where('is_included', true);
            }])
            ->where('school_id', $school->id)
            ->whereIn('status', ['trialing', 'active'])
            ->latest('id')
            ->first();

        $planIncludedModuleIds = [];
        if ($subscription && $subscription->isActive() && $subscription->plan) {
            $planIncludedModuleIds = $subscription->plan->planModules->pluck('system_module_id')->flip()->all();
        }

        // Precompute cache for all system modules for this school
        foreach ($systemModules as $module) {
            $cacheKey = "{$school->id}:{$module->module_key}";
            if (isset($overrides[$module->id])) {
                $this->cache[$cacheKey] = (bool) $overrides[$module->id]->is_enabled;
            } elseif (isset($planIncludedModuleIds[$module->id])) {
                $this->cache[$cacheKey] = true;
            } else {
                $this->cache[$cacheKey] = false;
            }
        }

        $this->preloadedSchools[$school->id] = true;
    }

    public function isEnabledForSchool(School $school, string $moduleKey): bool
    {
        $cacheKey = "{$school->id}:{$moduleKey}";

        if (array_key_exists($cacheKey, $this->cache)) {
            return $this->cache[$cacheKey];
        }

        $this->preloadForSchool($school);

        return $this->cache[$cacheKey] ?? false;
    }

    public function reasonForDeniedAccess(School $school, string $moduleKey): string
    {
        $module = SystemModule::query()
            ->where('module_key', $moduleKey)
            ->first();

        if (! $module) {
            return 'Module tidak ditemukan.';
        }

        if (! $module->is_active) {
            return 'Module sedang tidak aktif secara global.';
        }

        $override = SchoolModuleOverride::query()
            ->where('school_id', $school->id)
            ->where('system_module_id', $module->id)
            ->where(function ($query): void {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>=', now());
            })
            ->latest('id')
            ->first();

        if ($override && ! $override->is_enabled) {
            return 'Module dinonaktifkan khusus untuk sekolah ini.';
        }

        $subscription = SchoolSubscription::query()
            ->with('plan')
            ->where('school_id', $school->id)
            ->latest('id')
            ->first();

        if (! $subscription) {
            return 'Sekolah belum memiliki subscription aktif.';
        }

        if (! in_array($subscription->status, ['trialing', 'active'], true)) {
            return 'Subscription sekolah tidak aktif.';
        }

        if (! $subscription->isActive()) {
            return 'Masa subscription sekolah sudah berakhir.';
        }

        return 'Module tidak termasuk dalam plan sekolah saat ini.';
    }

    public function enabledModulesForSchool(School $school): Collection
    {
        return SystemModule::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->filter(fn (SystemModule $module): bool => $this->isEnabledForSchool($school, $module->module_key))
            ->values();
    }

    public function moduleStatusForSchool(School $school, string $moduleKey): array
    {
        $enabled = $this->isEnabledForSchool($school, $moduleKey);

        return [
            'module_key' => $moduleKey,
            'enabled' => $enabled,
            'reason' => $enabled ? 'Module aktif.' : $this->reasonForDeniedAccess($school, $moduleKey),
        ];
    }
}
