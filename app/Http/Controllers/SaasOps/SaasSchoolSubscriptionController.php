<?php

namespace App\Http\Controllers\SaasOps;

use App\Http\Controllers\Controller;
use App\Http\Requests\SaasOps\StoreSaasSchoolSubscriptionRequest;
use App\Models\SaasSchoolSubscription;
use App\Models\SaasSubscriptionPlan;
use App\Models\School;
use App\Services\SaasOps\PlanModuleAccessService;
use App\Services\SaasOps\SaasOperationsAccessService;
use App\Services\SaasOps\SubscriptionStatusService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SaasSchoolSubscriptionController extends Controller
{
    public function __construct(
        private readonly SaasOperationsAccessService $access,
        private readonly SubscriptionStatusService $statuses,
        private readonly PlanModuleAccessService $planAccess,
    ) {}

    public function index(): View
    {
        abort_unless($this->access->canViewDashboard(auth()->user()), 403);

        return view('saas-ops.school-subscriptions.index', ['subscriptions' => SaasSchoolSubscription::query()->with(['school', 'plan'])->latest()->paginate(20)]);
    }

    public function create(): View
    {
        abort_unless($this->access->canManageSubscriptions(auth()->user()), 403);

        return view('saas-ops.school-subscriptions.create', ['schools' => School::query()->orderBy('name')->get(), 'plans' => SaasSubscriptionPlan::query()->where('status', 'active')->get()]);
    }

    public function store(StoreSaasSchoolSubscriptionRequest $request): RedirectResponse
    {
        $subscription = SaasSchoolSubscription::query()->create($request->validated());
        $this->planAccess->syncTenantModulesForSubscription($subscription);

        return redirect()->route('saas-ops.school-subscriptions.show', $subscription)->with('success', 'Subscription sekolah berhasil dibuat.');
    }

    public function show(SaasSchoolSubscription $subscription): View
    {
        $this->access->assertCanAccessSchool(auth()->user(), (int) $subscription->school_id);
        $subscription->load(['school', 'plan', 'invoices']);

        return view('saas-ops.school-subscriptions.show', compact('subscription'));
    }

    public function activate(SaasSchoolSubscription $subscription): RedirectResponse
    {
        $this->statuses->activate($subscription, auth()->user());
        $this->planAccess->syncTenantModulesForSubscription($subscription);

        return back()->with('success', 'Subscription diaktifkan.');
    }

    public function suspend(Request $request, SaasSchoolSubscription $subscription): RedirectResponse
    {
        $request->validate(['reason' => ['required', 'string', 'min:5', 'max:1000']]);
        $this->statuses->suspend($subscription, $request->reason, $request->user());

        return back()->with('success', 'Subscription disuspend manual.');
    }

    public function cancel(Request $request, SaasSchoolSubscription $subscription): RedirectResponse
    {
        $request->validate(['reason' => ['required', 'string', 'min:5', 'max:1000']]);
        $this->statuses->cancel($subscription, $request->reason, $request->user());

        return back()->with('success', 'Subscription dibatalkan.');
    }
}
