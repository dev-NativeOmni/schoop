<?php

namespace App\Http\Controllers\SaasOps;

use App\Http\Controllers\Controller;
use App\Http\Requests\SaasOps\StoreSaasSubscriptionPlanRequest;
use App\Http\Requests\SaasOps\UpdateSaasSubscriptionPlanRequest;
use App\Models\SaasSubscriptionPlan;
use App\Services\SaasOps\PlanModuleAccessService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SaasSubscriptionPlanController extends Controller
{
    public function __construct(private readonly PlanModuleAccessService $planAccess) {}

    public function index(): View
    {
        return view('saas-ops.subscription-plans.index', ['plans' => SaasSubscriptionPlan::query()->latest()->paginate(20)]);
    }

    public function create(): View
    {
        return view('saas-ops.subscription-plans.create', [
            'plan' => null,
            'modules' => $this->planAccess->tenantManagedSystemModules(),
        ]);
    }

    public function store(StoreSaasSubscriptionPlanRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['features'] = array_values(array_filter(array_map('trim', explode("\n", (string) ($data['features'] ?? '')))));
        $data['allowed_modules'] = array_values($data['allowed_modules'] ?? []);
        $plan = SaasSubscriptionPlan::query()->create($data);

        return redirect()->route('saas-ops.subscription-plans.show', $plan)->with('success', 'Plan berhasil dibuat.');
    }

    public function show(SaasSubscriptionPlan $subscriptionPlan): View
    {
        return view('saas-ops.subscription-plans.show', ['plan' => $subscriptionPlan]);
    }

    public function edit(SaasSubscriptionPlan $subscriptionPlan): View
    {
        return view('saas-ops.subscription-plans.edit', [
            'plan' => $subscriptionPlan,
            'modules' => $this->planAccess->tenantManagedSystemModules(),
        ]);
    }

    public function update(UpdateSaasSubscriptionPlanRequest $request, SaasSubscriptionPlan $subscriptionPlan): RedirectResponse
    {
        $data = $request->validated();
        $data['features'] = array_values(array_filter(array_map('trim', explode("\n", (string) ($data['features'] ?? '')))));
        $data['allowed_modules'] = array_values($data['allowed_modules'] ?? []);
        $subscriptionPlan->update($data);
        $this->planAccess->syncTenantModulesForPlan($subscriptionPlan);

        return redirect()->route('saas-ops.subscription-plans.show', $subscriptionPlan)->with('success', 'Plan berhasil diperbarui.');
    }

    public function destroy(SaasSubscriptionPlan $subscriptionPlan): RedirectResponse
    {
        $subscriptionPlan->update(['status' => 'inactive']);

        return redirect()->route('saas-ops.subscription-plans.index')->with('success', 'Plan dinonaktifkan.');
    }
}
