@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Breadcrumbs -->
    <div class="flex items-center space-x-2 text-sm text-slate-500">
        <a href="{{ route('billing.school-subscriptions.index') }}" class="hover:text-indigo-600">Subscriptions</a>
        <span>/</span>
        <span class="text-slate-900 dark:text-white font-semibold">Detail Langganan Sekolah</span>
    </div>

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">{{ $schoolSubscription->school?->name }}</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Detail status langganan sekolah aktif saat ini.</p>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('billing.school-subscriptions.edit', $schoolSubscription->id) }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl transition duration-150 shadow-md">
                Edit Langganan
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Rincian Langganan -->
        <div class="bg-white dark:bg-slate-900 shadow-xl rounded-2xl border border-slate-100 dark:border-slate-800 p-6 space-y-4">
            <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">Status Langganan</h3>
            <div class="space-y-3 text-sm">
                <div>
                    <span class="text-slate-400 block text-xs">Sekolah</span>
                    <span class="font-bold text-slate-900 dark:text-slate-200 block">{{ $schoolSubscription->school?->name }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block text-xs">Paket Dipilih</span>
                    <span class="font-bold text-slate-900 dark:text-slate-200 block">{{ $schoolSubscription->plan?->name }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block text-xs">Status</span>
                    @php
                        $statusClasses = match($schoolSubscription->status) {
                            'active' => 'bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-300',
                            'trialing' => 'bg-blue-50 dark:bg-blue-950/20 text-blue-700 dark:text-blue-300',
                            'suspended' => 'bg-amber-50 dark:bg-amber-950/20 text-amber-700 dark:text-amber-300',
                            'expired', 'canceled' => 'bg-rose-50 dark:bg-rose-950/20 text-rose-700 dark:text-rose-300',
                            default => 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300',
                        };
                    @endphp
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold {{ $statusClasses }} capitalize mt-1">
                        {{ $schoolSubscription->status }}
                    </span>
                </div>
                <div>
                    <span class="text-slate-400 block text-xs">Status Engine Keaktifan</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold {{ $schoolSubscription->isActive() ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }} mt-1">
                        {{ $schoolSubscription->isActive() ? 'ACTIVE (Akses Terbuka)' : 'INACTIVE (Akses Terkunci)' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Masa Berlaku -->
        <div class="bg-white dark:bg-slate-900 shadow-xl rounded-2xl border border-slate-100 dark:border-slate-800 p-6 space-y-4">
            <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">Masa Berlaku</h3>
            <div class="space-y-3 text-sm">
                <div>
                    <span class="text-slate-400 block text-xs">Mulai Berlangganan</span>
                    <span class="font-bold text-slate-900 dark:text-slate-200 block">{{ $schoolSubscription->starts_at ? $schoolSubscription->starts_at->format('d M Y') : '-' }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block text-xs">Trial Berakhir s.d.</span>
                    <span class="font-bold text-slate-900 dark:text-slate-200 block">{{ $schoolSubscription->trial_ends_at ? $schoolSubscription->trial_ends_at->format('d M Y') : '-' }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block text-xs">Mulai Siklus Terakhir</span>
                    <span class="font-bold text-slate-900 dark:text-slate-200 block">{{ $schoolSubscription->current_period_starts_at ? $schoolSubscription->current_period_starts_at->format('d M Y') : '-' }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block text-xs">Berakhir Siklus Terakhir</span>
                    <span class="font-bold text-slate-900 dark:text-slate-200 block">{{ $schoolSubscription->current_period_ends_at ? $schoolSubscription->current_period_ends_at->format('d M Y') : '-' }}</span>
                </div>
            </div>
        </div>

        <!-- Metadata -->
        <div class="bg-white dark:bg-slate-900 shadow-xl rounded-2xl border border-slate-100 dark:border-slate-800 p-6 space-y-4">
            <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">Metadata Tambahan</h3>
            @if(is_array($schoolSubscription->metadata) && count($schoolSubscription->metadata) > 0)
                <pre class="font-mono text-xs p-3 bg-slate-50 dark:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-xl overflow-x-auto">{{ json_encode($schoolSubscription->metadata, JSON_PRETTY_PRINT) }}</pre>
            @else
                <span class="text-slate-400 text-xs italic">Tidak ada metadata tambahan.</span>
            @endif
        </div>
    </div>
</div>
@endsection
