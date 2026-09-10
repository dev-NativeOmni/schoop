<?php

namespace App\Http\Controllers\Tenancy;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenancy\UpdateTenantModuleRequest;
use App\Models\TenantModule;
use App\Services\SaasOps\PlanModuleAccessService;
use App\Services\Tenancy\TenantContextService;
use App\Services\Tenancy\TenantModuleService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TenantModuleController extends Controller
{
    protected TenantModuleService $moduleService;

    protected TenantContextService $contextService;

    public function __construct(
        TenantModuleService $moduleService,
        TenantContextService $contextService,
        private readonly PlanModuleAccessService $planAccess,
    ) {
        $this->moduleService = $moduleService;
        $this->contextService = $contextService;
    }

    public function index(): View
    {
        $schoolId = $this->contextService->activeSchoolId();
        $modules = TenantModule::query()
            ->where('school_id', $schoolId)
            ->orderBy('module_key')
            ->get();

        $subscription = $this->planAccess->currentSubscription((int) $schoolId);
        $allowedModules = $this->planAccess->allowedModulesForPlan($subscription?->plan);

        return view('tenancy.modules.index', compact('modules', 'allowedModules'));
    }

    public function update(UpdateTenantModuleRequest $request, TenantModule $tenantModule): RedirectResponse
    {
        $schoolId = $this->contextService->activeSchoolId();
        if (! auth()->user()->hasRole('super_admin') && (int) $tenantModule->school_id !== $schoolId) {
            abort(403, 'Akses tidak sah.');
        }

        $this->moduleService->updateModule($tenantModule, $request->validated());

        return redirect()->route('tenancy.modules.index')
            ->with('success', "Module {$tenantModule->module_name} berhasil diperbarui.");
    }
}
