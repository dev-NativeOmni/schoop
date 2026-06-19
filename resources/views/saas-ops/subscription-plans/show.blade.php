@php
    $planAccess = app(\App\Services\SaasOps\PlanModuleAccessService::class);
    $allowedModules = $planAccess->allowedModulesForPlan($plan);
    $modules = $planAccess->tenantManagedSystemModules();
@endphp

@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <x-saas-ops.shell title="{{ $plan->name }}" subtitle="{{ $plan->code }}">
        <a class="rounded-lg bg-lime-500 px-4 py-2 text-sm font-black text-white" href="{{ route('saas-ops.subscription-plans.edit', $plan) }}">Edit</a>

        <div class="grid gap-4 md:grid-cols-2">
            <div class="rounded-xl border bg-white p-5 text-sm dark:border-slate-800 dark:bg-slate-900 dark:text-slate-200">
                <dl class="space-y-2">
                    <div class="flex justify-between gap-4"><dt class="text-slate-500">Cycle</dt><dd class="font-bold">{{ $plan->billing_cycle }}</dd></div>
                    <div class="flex justify-between gap-4"><dt class="text-slate-500">Status</dt><dd class="font-bold">{{ $plan->status }}</dd></div>
                    <div class="flex justify-between gap-4"><dt class="text-slate-500">Monthly</dt><dd class="font-bold">Rp {{ number_format($plan->monthly_price, 0, ',', '.') }}</dd></div>
                    <div class="flex justify-between gap-4"><dt class="text-slate-500">Yearly</dt><dd class="font-bold">Rp {{ number_format($plan->yearly_price, 0, ',', '.') }}</dd></div>
                </dl>
            </div>

            <div class="rounded-xl border bg-white p-5 dark:border-slate-800 dark:bg-slate-900">
                <h3 class="text-sm font-black text-slate-900 dark:text-white">Allowed Modules</h3>
                <div class="mt-3 flex flex-wrap gap-2">
                    @foreach($modules as $module)
                        @if(in_array($module->module_key, $allowedModules, true))
                            <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700">{{ $module->name }}</span>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </x-saas-ops.shell>
</div>
@endsection
