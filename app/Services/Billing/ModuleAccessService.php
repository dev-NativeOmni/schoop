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

    public function isEnabledForSchool(School $school, string $moduleKey): bool
    {
        $cacheKey = "{$school->id}:{$moduleKey}";

        if (array_key_exists($cacheKey, $this->cache)) {
            return $this->cache[$cacheKey];
        }

        $module = SystemModule::query()
            ->where('module_key', $moduleKey)
            ->where('is_active', true)
            ->first();

        if (! $module) {
            return $this->cache[$cacheKey] = false;
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

        if ($override) {
            return $this->cache[$cacheKey] = $override->is_enabled;
        }

        $subscription = SchoolSubscription::query()
            ->where('school_id', $school->id)
            ->whereIn('status', ['trialing', 'active'])
            ->latest('id')
            ->first();

        if (! $subscription || ! $subscription->isActive()) {
            return $this->cache[$cacheKey] = false;
        }

        $result = PlanModule::query()
            ->where('subscription_plan_id', $subscription->subscription_plan_id)
            ->where('system_module_id', $module->id)
            ->where('is_included', true)
            ->exists();

        return $this->cache[$cacheKey] = $result;
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
