@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <x-saas-ops.shell title="SaaS Operations Command Center" subtitle="Monitoring skala platform, subscription MRR/ARR, kesehatan tenant, dan tiket bantuan.">
        
        {{-- High-Level Metrics Bento --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4">
            @foreach($summary as $label => $value)
                <div class="card-natural p-4 sm:p-5 flex flex-col justify-between">
                    <p class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                        {{ ucwords(str_replace('_', ' ', $label)) }}
                    </p>
                    <p class="mt-2 text-xl sm:text-3xl font-black text-slate-900 dark:text-white truncate">
                        {{ is_numeric($value) && str_contains($label, 'balance') ? 'Rp ' . number_format($value, 0, ',', '.') : $value }}
                    </p>
                </div>
            @endforeach
        </div>

        {{-- Dual Column: Tenant Health & Recent Support Tickets --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            {{-- Tenant Health Card --}}
            <div class="card-natural p-5 sm:p-6 space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-3">
                    <h2 class="font-black text-sm sm:text-base text-slate-900 dark:text-white flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <span>Tenant Health Monitoring</span>
                    </h2>
                    <a href="{{ route('analytics.tenant-health.index') }}" class="text-xs font-bold text-emerald-600 dark:text-emerald-400 hover:underline">
                        Lihat Semua &rarr;
                    </a>
                </div>

                <div class="space-y-2.5">
                    @forelse($snapshots as $snapshot)
                        <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-900/60 border border-slate-200/80 dark:border-slate-800 flex items-center justify-between gap-3 text-xs">
                            <div class="min-w-0 flex-1">
                                <p class="font-bold text-slate-900 dark:text-white truncate">{{ $snapshot->school?->name ?? 'Sekolah' }}</p>
                                <p class="text-[11px] text-slate-400">Skor: <strong class="text-slate-700 dark:text-slate-300">{{ $snapshot->health_score }} / 100</strong></p>
                            </div>
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-black border uppercase {{ strtolower($snapshot->health_status) === 'healthy' ? 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-800/60' : 'bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-950/40 dark:text-amber-300 dark:border-amber-800/60' }}">
                                {{ $snapshot->health_status }}
                            </span>
                        </div>
                    @empty
                        <p class="py-6 text-center text-xs text-slate-400">Belum ada snapshot kesehatan tenant.</p>
                    @endforelse
                </div>
            </div>

            {{-- Recent Tickets Card --}}
            <div class="card-natural p-5 sm:p-6 space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-3">
                    <h2 class="font-black text-sm sm:text-base text-slate-900 dark:text-white flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-teal-500"></span>
                        <span>Tiket Bantuan Terkini</span>
                    </h2>
                    <a href="{{ route('saas-ops.support-tickets.index') }}" class="text-xs font-bold text-teal-600 dark:text-teal-400 hover:underline">
                        Lihat Semua &rarr;
                    </a>
                </div>

                <div class="space-y-2.5">
                    @forelse($tickets as $ticket)
                        <a href="{{ route('saas-ops.support-tickets.show', $ticket) }}" class="p-3 rounded-xl bg-slate-50 dark:bg-slate-900/60 border border-slate-200/80 dark:border-slate-800 flex items-center justify-between gap-3 text-xs hover:border-emerald-300 transition-colors block">
                            <div class="min-w-0 flex-1">
                                <p class="font-bold text-slate-900 dark:text-white truncate">{{ $ticket->subject }}</p>
                                <p class="text-[11px] text-slate-400 font-mono mt-0.5">{{ $ticket->ticket_number }}</p>
                            </div>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold border uppercase shrink-0 {{ strtolower($ticket->status) === 'open' ? 'bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-950/40 dark:text-amber-300 dark:border-amber-800/60' : 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-800/60' }}">
                                {{ $ticket->status }}
                            </span>
                        </a>
                    @empty
                        <p class="py-6 text-center text-xs text-slate-400">Belum ada tiket bantuan terkini.</p>
                    @endforelse
                </div>
            </div>

        </div>

    </x-saas-ops.shell>
</div>
@endsection
