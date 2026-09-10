<?php

namespace App\Http\Controllers\Billing;

use App\Http\Controllers\Controller;
use App\Http\Requests\Billing\StoreSchoolSubscriptionRequest;
use App\Http\Requests\Billing\UpdateSchoolSubscriptionRequest;
use App\Models\School;
use App\Models\SchoolSubscription;
use App\Models\SubscriptionPlan;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SchoolSubscriptionController extends Controller
{
    public function index(): View
    {
        $subscriptions = SchoolSubscription::query()
            ->with(['school', 'plan'])
            ->latest('id')
            ->paginate(20);

        return view('billing.school-subscriptions.index', compact('subscriptions'));
    }

    public function create(): View
    {
        $schools = School::query()->orderBy('name')->get();
        $plans = SubscriptionPlan::query()->where('is_active', true)->orderBy('sort_order')->get();

        return view('billing.school-subscriptions.create', compact('schools', 'plans'));
    }

    public function store(StoreSchoolSubscriptionRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->has('metadata_raw')) {
            $data['metadata'] = json_decode($request->input('metadata_raw'), true) ?? [];
        }

        SchoolSubscription::query()->create($data);

        return redirect()
            ->route('billing.school-subscriptions.index')
            ->with('success', 'Subscription sekolah berhasil ditambahkan.');
    }

    public function show(SchoolSubscription $schoolSubscription): View
    {
        $schoolSubscription->load(['school', 'plan']);

        return view('billing.school-subscriptions.show', compact('schoolSubscription'));
    }

    public function edit(SchoolSubscription $schoolSubscription): View
    {
        $schools = School::query()->orderBy('name')->get();
        $plans = SubscriptionPlan::query()->where('is_active', true)->orderBy('sort_order')->get();

        return view('billing.school-subscriptions.edit', compact('schoolSubscription', 'schools', 'plans'));
    }

    public function update(UpdateSchoolSubscriptionRequest $request, SchoolSubscription $schoolSubscription): RedirectResponse
    {
        $data = $request->validated();

        if ($request->has('metadata_raw')) {
            $data['metadata'] = json_decode($request->input('metadata_raw'), true) ?? [];
        }

        $schoolSubscription->update($data);

        return redirect()
            ->route('billing.school-subscriptions.index')
            ->with('success', 'Subscription sekolah berhasil diperbarui.');
    }

    public function destroy(SchoolSubscription $schoolSubscription): RedirectResponse
    {
        $schoolSubscription->delete();

        return redirect()
            ->route('billing.school-subscriptions.index')
            ->with('success', 'Subscription sekolah berhasil dihapus.');
    }
}
