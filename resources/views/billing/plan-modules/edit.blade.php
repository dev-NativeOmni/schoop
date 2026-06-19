@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <!-- Breadcrumbs -->
    <div class="flex items-center space-x-2 text-sm text-slate-500">
        <a href="{{ route('billing.plans.index') }}" class="hover:text-indigo-600">Plans</a>
        <span>/</span>
        <a href="{{ route('billing.plans.show', $plan->id) }}" class="hover:text-indigo-600">{{ $plan->name }}</a>
        <span>/</span>
        <span class="text-slate-900 dark:text-white font-semibold">Kelola Modul</span>
    </div>

    <!-- Header -->
    <div>
        <h1 class="text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">Kelola Modul Plan: {{ $plan->name }}</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400">Aktifkan atau nonaktifkan modul, serta atur limit & fitur khusus untuk plan ini.</p>
    </div>

    <!-- Sync Form -->
    <form action="{{ route('billing.plans.modules.update', $plan->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="bg-white dark:bg-slate-900 shadow-xl rounded-2xl border border-slate-100 dark:border-slate-800 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-850 border-b border-slate-100 dark:border-slate-800">
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 w-12 text-center">Aktif?</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 w-64">Nama Modul</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Limits JSON (Optional)</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Features JSON (Optional)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @foreach($modules as $module)
                            @php
                                $planModule = $planModules->get($module->id);
                                $isIncluded = $planModule ? $planModule->is_included : false;
                                $limitsVal = $planModule && $planModule->limits ? json_encode($planModule->limits) : '';
                                $featuresVal = $planModule && $planModule->features ? json_encode($planModule->features) : '';
                            @endphp
                            <tr class="hover:bg-slate-50/30 dark:hover:bg-slate-850/20 transition duration-150">
                                <td class="px-6 py-4 text-center">
                                    <input type="hidden" name="modules[{{ $module->id }}][system_module_id]" value="{{ $module->id }}">
                                    <input type="checkbox" name="modules[{{ $module->id }}][is_included]" value="1" {{ $isIncluded ? 'checked' : '' }} class="h-5 w-5 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 mx-auto">
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-bold text-slate-900 dark:text-white block">{{ $module->name }}</span>
                                    <code class="text-[10px] text-slate-400 font-semibold bg-slate-50 dark:bg-slate-800 px-1.5 py-0.5 rounded">{{ $module->module_key }}</code>
                                    <span class="text-xs text-slate-400 block mt-1 truncate max-w-xs">{{ $module->description }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <textarea name="modules[{{ $module->id }}][limits]" rows="1" placeholder='e.g. {"max_items": 10}' class="w-full font-mono text-xs rounded-xl border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition">{{ old("modules.$module->id.limits", $limitsVal) }}</textarea>
                                </td>
                                <td class="px-6 py-4">
                                    <textarea name="modules[{{ $module->id }}][features]" rows="1" placeholder='e.g. {"can_export": true}' class="w-full font-mono text-xs rounded-xl border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition">{{ old("modules.$module->id.features", $featuresVal) }}</textarea>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-850/20 flex items-center justify-end space-x-3">
                <a href="{{ route('billing.plans.show', $plan->id) }}" class="px-4 py-2 text-sm font-bold text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 rounded-xl transition">Batal</a>
                <button type="submit" class="px-5 py-2 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl transition shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Simpan Sinkronisasi
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
