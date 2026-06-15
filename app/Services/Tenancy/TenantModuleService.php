<?php

namespace App\Services\Tenancy;

use App\Models\TenantModule;
use Illuminate\Support\Facades\DB;

class TenantModuleService
{
    protected TenantAuditLogger $logger;

    public function __construct(TenantAuditLogger $logger)
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
