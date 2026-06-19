@php
    $selectedModules = old('allowed_modules', $plan?->allowed_modules ?? []);
@endphp

<form method="POST" action="{{ $plan ? route('saas-ops.subscription-plans.update', $plan) : route('saas-ops.subscription-plans.store') }}" class="space-y-6 rounded-xl border bg-white p-5 dark:border-slate-800 dark:bg-slate-900">
    @csrf
    @if($plan)
        @method('PUT')
    @endif

    <div class="grid gap-4 md:grid-cols-2">
        <label class="space-y-1 text-sm font-semibold text-slate-700 dark:text-slate-200">
            <span>Code</span>
            <input name="code" value="{{ old('code', $plan?->code) }}" class="w-full rounded-lg dark:bg-slate-950" required>
        </label>

        <label class="space-y-1 text-sm font-semibold text-slate-700 dark:text-slate-200">
            <span>Name</span>
            <input name="name" value="{{ old('name', $plan?->name) }}" class="w-full rounded-lg dark:bg-slate-950" required>
        </label>

        <label class="space-y-1 text-sm font-semibold text-slate-700 dark:text-slate-200">
            <span>Billing Cycle</span>
            <select name="billing_cycle" class="w-full rounded-lg dark:bg-slate-950">
                <option value="monthly" @selected(old('billing_cycle', $plan?->billing_cycle) === 'monthly')>monthly</option>
                <option value="yearly" @selected(old('billing_cycle', $plan?->billing_cycle) === 'yearly')>yearly</option>
            </select>
        </label>

        <label class="space-y-1 text-sm font-semibold text-slate-700 dark:text-slate-200">
            <span>Status</span>
            <select name="status" class="w-full rounded-lg dark:bg-slate-950">
                <option value="active" @selected(old('status', $plan?->status ?? 'active') === 'active')>active</option>
                <option value="inactive" @selected(old('status', $plan?->status) === 'inactive')>inactive</option>
            </select>
        </label>

        <label class="space-y-1 text-sm font-semibold text-slate-700 dark:text-slate-200">
            <span>Monthly Price</span>
            <input type="number" name="monthly_price" value="{{ old('monthly_price', $plan?->monthly_price ?? 0) }}" class="w-full rounded-lg dark:bg-slate-950">
        </label>

        <label class="space-y-1 text-sm font-semibold text-slate-700 dark:text-slate-200">
            <span>Yearly Price</span>
            <input type="number" name="yearly_price" value="{{ old('yearly_price', $plan?->yearly_price ?? 0) }}" class="w-full rounded-lg dark:bg-slate-950">
        </label>
    </div>

    <div class="space-y-3">
        <div>
            <h3 class="text-sm font-black text-slate-900 dark:text-white">Allowed Modules</h3>
            <p class="text-xs text-slate-500">Modul yang tidak dipilih akan terkunci untuk sekolah pada plan ini.</p>
        </div>

        <div class="grid gap-3 md:grid-cols-2">
            @foreach($modules as $module)
                <label class="flex items-start gap-3 rounded-lg border border-slate-200 p-3 text-sm dark:border-slate-800">
                    <input type="checkbox" name="allowed_modules[]" value="{{ $module->module_key }}" class="mt-1 rounded" @checked(in_array($module->module_key, $selectedModules, true))>
                    <span>
                        <span class="block font-bold text-slate-900 dark:text-white">{{ $module->name }}</span>
                        <span class="block text-xs text-slate-500">{{ $module->module_key }}</span>
                    </span>
                </label>
            @endforeach
        </div>
    </div>

    <label class="block space-y-1 text-sm font-semibold text-slate-700 dark:text-slate-200">
        <span>Features</span>
        <textarea name="features" class="w-full rounded-lg dark:bg-slate-950" rows="5" placeholder="features per line">{{ old('features', implode("\n", $plan?->features ?? [])) }}</textarea>
    </label>

    <label class="block space-y-1 text-sm font-semibold text-slate-700 dark:text-slate-200">
        <span>Notes</span>
        <textarea name="notes" class="w-full rounded-lg dark:bg-slate-950" rows="4">{{ old('notes', $plan?->notes) }}</textarea>
    </label>

    <button class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-black text-white dark:bg-lime-400 dark:text-slate-950">
        Simpan
    </button>
</form>
