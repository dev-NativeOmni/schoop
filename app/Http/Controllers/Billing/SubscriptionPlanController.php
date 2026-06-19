<?php

namespace App\Http\Controllers\Billing;

use App\Http\Controllers\Controller;
use App\Http\Requests\Billing\StoreSubscriptionPlanRequest;
use App\Http\Requests\Billing\UpdateSubscriptionPlanRequest;
use App\Models\SubscriptionPlan;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SubscriptionPlanController extends Controller
{
    public function index(): View
    {
        $plans = SubscriptionPlan::query()
            ->withCount('planModules')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->paginate(20);

        return view('billing.plans.index', compact('plans'));
    }

    public function create(): View
    {
        return view('billing.plans.create');
    }

    public function store(StoreSubscriptionPlanRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['is_active'] = $request->has('is_active') ? (bool) $request->input('is_active') : true;

        // Parse limits if provided as JSON string or key/value pairs
        if ($request->has('limits_raw')) {
            $data['limits'] = json_decode($request->input('limits_raw'), true) ?? [];
        }

        SubscriptionPlan::query()->create($data);

        return redirect()
            ->route('billing.plans.index')
            ->with('success', 'Subscription Plan berhasil dibuat.');
    }

    public function show(SubscriptionPlan $plan): View
    {
        $plan->load('modules');
        return view('billing.plans.show', compact('plan'));
    }

    public function edit(SubscriptionPlan $plan): View
    {
        return view('billing.plans.edit', compact('plan'));
    }

    public function update(UpdateSubscriptionPlanRequest $request, SubscriptionPlan $plan): RedirectResponse
    {
        $data = $request->validated();
        $data['is_active'] = $request->has('is_active') ? (bool) $request->input('is_active') : false;

        // Parse limits if provided as JSON string
        if ($request->has('limits_raw')) {
            $data['limits'] = json_decode($request->input('limits_raw'), true) ?? [];
        }

        $plan->update($data);

        return redirect()
            ->route('billing.plans.index')
            ->with('success', 'Subscription Plan berhasil diperbarui.');
    }

    public function destroy(SubscriptionPlan $plan): RedirectResponse
    {
        if ($plan->subscriptions()->exists()) {
            // Plan is in use, disable it instead of deleting
            $plan->update(['is_active' => false]);

            return redirect()
                ->route('billing.plans.index')
                ->with('success', 'Plan sedang digunakan oleh sekolah aktif. Status diubah menjadi Non-aktif.');
        }

        $plan->delete();

        return redirect()
            ->route('billing.plans.index')
            ->with('success', 'Subscription Plan berhasil dihapus.');
    }
}
