@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-6">
    <x-analytics.shell title="Internal Executive Analytics" subtitle="Ringkasan kinerja bisnis, status kesehatan tenant, tiket support, dan tren operasional.">
        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <p class="text-xs font-black uppercase tracking-wide text-slate-500">Active Tenants</p>
                <p class="mt-3 text-3xl font-black text-slate-950 dark:text-white">{{ number_format($stats['active_tenants']) }}</p>
            </div>
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <p class="text-xs font-black uppercase tracking-wide text-slate-500">Average Health Score</p>
                <p class="mt-3 text-3xl font-black text-slate-950 dark:text-white">{{ $stats['average_health_score'] }}/100</p>
            </div>
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <p class="text-xs font-black uppercase tracking-wide text-slate-500">Risk Tenants</p>
                <p class="mt-3 text-3xl font-black text-slate-950 dark:text-white">{{ number_format($stats['risk_tenants']) }}</p>
            </div>
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <p class="text-xs font-black uppercase tracking-wide text-slate-500">Open Tickets</p>
                <p class="mt-3 text-3xl font-black text-slate-950 dark:text-white">{{ number_format($stats['open_support_tickets']) }}</p>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-2">
            <!-- Top Tenants -->
            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h2 class="text-lg font-black text-slate-950 dark:text-white">Top Performing Tenants</h2>
                <div class="mt-4 divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($topTenants as $t)
                        <div class="flex items-center justify-between py-3">
                            <div>
                                <p class="text-sm font-bold text-slate-900 dark:text-white">{{ $t['school']['name'] ?? 'Tenant ID '.$t['school_id'] }}</p>
                                <p class="text-xs text-slate-500">Score Date: {{ $t['score_date'] }}</p>
                            </div>
                            <span class="rounded bg-lime-100 px-2 py-1 text-xs font-bold text-lime-800 dark:bg-lime-950/40 dark:text-lime-400">{{ $t['score'] }}</span>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500 py-3">Belum ada data tenant terkalkulasi.</p>
                    @endforelse
                </div>
            </div>

            <!-- Risk Tenants -->
            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h2 class="text-lg font-black text-red-600 dark:text-red-400">Risk / Critical Tenants</h2>
                <div class="mt-4 divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($riskTenants as $t)
                        <div class="flex items-center justify-between py-3">
                            <div>
                                <p class="text-sm font-bold text-slate-900 dark:text-white">{{ $t['school']['name'] ?? 'Tenant ID '.$t['school_id'] }}</p>
                                <p class="text-xs text-slate-500">Status: {{ strtoupper($t['status']) }}</p>
                            </div>
                            <span class="rounded bg-red-100 px-2 py-1 text-xs font-bold text-red-800 dark:bg-red-950/40 dark:text-red-400">{{ $t['score'] }}</span>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500 py-3">Semua tenant dalam keadaan sehat (Healthy/Watch).</p>
                    @endforelse
                </div>
            </div>
        </div>
    </x-analytics.shell>
</div>
@endsection
