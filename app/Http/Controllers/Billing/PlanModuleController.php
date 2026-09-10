<?php

namespace App\Http\Controllers\Billing;

use App\Http\Controllers\Controller;
use App\Http\Requests\Billing\SyncPlanModulesRequest;
use App\Models\PlanModule;
use App\Models\SubscriptionPlan;
use App\Models\SystemModule;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PlanModuleController extends Controller
{
    public function edit(SubscriptionPlan $plan): View
    {
        $modules = SystemModule::query()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $planModules = PlanModule::query()
            ->where('subscription_plan_id', $plan->id)
            ->get()
            ->keyBy('system_module_id');

        return view('billing.plan-modules.edit', compact('plan', 'modules', 'planModules'));
    }

    public function update(SyncPlanModulesRequest $request, SubscriptionPlan $plan): RedirectResponse
    {
        $inputModules = $request->input('modules', []);

        $syncData = [];
        foreach ($inputModules as $systemModuleId => $data) {
            $isIncluded = isset($data['is_included']) && $data['is_included'] == '1';

            if ($isIncluded) {
                $limits = null;
                if (! empty($data['limits'])) {
                    $limits = json_decode($data['limits'], true);
                }

                $features = null;
                if (! empty($data['features'])) {
                    $features = json_decode($data['features'], true);
                }

                $syncData[$systemModuleId] = [
                    'is_included' => true,
                    'limits' => $limits,
                    'features' => $features,
                ];
            }
        }

        // Use sync with detaching so unselected modules are removed from this plan
        $plan->modules()->sync($syncData);

        return redirect()
            ->route('billing.plans.show', $plan->id)
            ->with('success', 'Modul paket berhasil disinkronisasi.');
    }
}
