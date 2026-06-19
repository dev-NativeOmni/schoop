@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Breadcrumbs -->
    <div class="flex items-center space-x-2 text-sm text-slate-500">
        <a href="{{ route('billing.plans.index') }}" class="hover:text-indigo-600">Plans</a>
        <span>/</span>
        <span class="text-slate-900 dark:text-white font-semibold">{{ $plan->name }}</span>
    </div>

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center space-x-3">
                <h1 class="text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">{{ $plan->name }}</h1>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold {{ $plan->is_active ? 'bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-300' : 'bg-rose-50 dark:bg-rose-950/20 text-rose-700 dark:text-rose-300' }}">
                    {{ $plan->is_active ? 'Aktif' : 'Non-aktif' }}
                </span>
            </div>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Detail paket dan modul-modul yang disertakan.</p>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('billing.plans.modules.edit', $plan->id) }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-bold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-950/30 dark:text-indigo-300 dark:hover:bg-indigo-950/50 rounded-xl transition duration-150 shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" /></svg>
                Kelola Modul
            </a>
            <a href="{{ route('billing.plans.edit', $plan->id) }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl transition duration-150 shadow-md">
                Edit Plan
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Rincian Plan -->
        <div class="md:col-span-1 bg-white dark:bg-slate-900 shadow-xl rounded-2xl border border-slate-100 dark:border-slate-800 p-6 space-y-4">
            <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">Rincian Paket</h3>
            <div class="space-y-3 text-sm">
                <div>
                    <span class="text-slate-400 block text-xs">Kode Paket</span>
                    <code class="font-mono text-slate-900 dark:text-slate-200">{{ $plan->code }}</code>
                </div>
                <div>
                    <span class="text-slate-400 block text-xs">Harga Bulanan</span>
                    <span class="font-bold text-slate-900 dark:text-slate-200">Rp {{ number_format($plan->monthly_price, 0, ',', '.') }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block text-xs">Harga Tahunan</span>
                    <span class="font-bold text-slate-900 dark:text-slate-200">Rp {{ number_format($plan->yearly_price, 0, ',', '.') }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block text-xs">Urutan Tampilan</span>
                    <span class="text-slate-900 dark:text-slate-200">{{ $plan->sort_order }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block text-xs">Deskripsi</span>
                    <span class="text-slate-600 dark:text-slate-350 block">{{ $plan->description ?? '-' }}</span>
                </div>
            </div>

            <hr class="border-slate-100 dark:border-slate-800">

            <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">Resource Limits</h3>
            <div class="space-y-3 text-sm">
                @if(is_array($plan->limits) && count($plan->limits) > 0)
                    @foreach($plan->limits as $key => $val)
                        <div class="flex justify-between items-center py-1">
                            <span class="text-slate-500 font-semibold capitalize">{{ str_replace('_', ' ', $key) }}</span>
                            <span class="font-bold text-slate-900 dark:text-slate-200">{{ is_numeric($val) ? number_format($val, 0, ',', '.') : $val }}</span>
                        </div>
                    @endforeach
                @else
                    <span class="text-slate-400 text-xs italic">Tidak ada batasan resource yang dikonfigurasi.</span>
                @endif
            </div>
        </div>

        <!-- Modul included -->
        <div class="md:col-span-2 bg-white dark:bg-slate-900 shadow-xl rounded-2xl border border-slate-100 dark:border-slate-800 p-6">
            <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider mb-4">Modul Yang Diaktifkan</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @forelse($plan->modules as $module)
                    <div class="p-4 bg-slate-50 dark:bg-slate-850/50 rounded-xl border border-slate-100 dark:border-slate-800 flex items-start space-x-3">
                        <div class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 flex items-center justify-center shrink-0">
                            @if($module->icon)
                                <span class="material-icons text-sm">{{ $module->icon }}</span>
                            @else
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                            @endif
                        </div>
                        <div class="min-w-0">
                            <span class="font-bold text-slate-900 dark:text-white block truncate">{{ $module->name }}</span>
                            <code class="text-[10px] text-slate-400 font-semibold">{{ $module->module_key }}</code>
                            @if($module->pivot->limits)
                                <div class="text-[10px] text-indigo-500 font-bold mt-1">Limits: {{ json_encode($module->pivot->limits) }}</div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-2 py-8 text-center text-slate-400 italic">
                        Belum ada modul yang diaktifkan untuk plan ini. Klik "Kelola Modul" untuk menambahkan.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
