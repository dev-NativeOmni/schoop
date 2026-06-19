<?php

namespace App\Services\Tenancy;

use App\Models\TenantModule;
use App\Services\SaasOps\PlanModuleAccessService;
use Illuminate\Support\Facades\DB;

class TenantModuleService
{
    protected TenantAuditLogger $logger;

    public function __construct(TenantAuditLogger $logger, private readonly PlanModuleAccessService $planAccess)
    {
        $this->logger = $logger;
    }

    public function isModuleEnabled(int $schoolId, string $moduleKey): bool
    {
        return TenantModule::query()
            ->where('school_id', $schoolId)
            ->where('module_key', $moduleKey)
            ->where('is_enabled', true)
            ->exists();
    }

    public function updateModule(TenantModule $module, array $data): TenantModule
    {
        if ((bool) $data['is_enabled'] && ! $this->planAccess->moduleIsAllowedByPlan((int) $module->school_id, $module->module_key)) {
            abort(403, 'Modul ini tidak termasuk dalam subscription plan sekolah.');
        }

        return DB::transaction(function () use ($module, $data) {
            $oldValues = $module->toArray();

            $module->update([
                'is_enabled' => $data['is_enabled'],
                'configuration' => $data['configuration'] ?? $module->configuration,
                'updated_by' => auth()->id(),
            ]);

            $this->logger->log('update_tenant_module', $module, $oldValues, $module->toArray());

            return $module;
        });
    }
}
