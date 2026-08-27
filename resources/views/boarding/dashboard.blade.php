@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header Hero Section -->
    <div class="card-natural p-6 bg-gradient-to-br from-emerald-800 via-emerald-700 to-teal-800 text-white shadow-lg shadow-emerald-900/10">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div>
                <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-white/10 text-emerald-100 text-[11px] font-bold border border-white/15 mb-2 backdrop-blur-sm">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-300"></span>
                    Sistem Pengasuhan & Boarding School
                </div>
                <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">Manajemen Asrama Pesantren</h1>
                <p class="mt-1 text-xs sm:text-sm text-emerald-100/90 max-w-xl">
                    Monitoring penempatan kamar, perizinan keluar santri, absensi malam (*roll call*), serta log kesehatan UKS.
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-3 shrink-0">
                <div class="rounded-2xl bg-white/10 backdrop-blur-md px-4 py-3 border border-white/20 min-w-[130px]">
                    <span class="block text-[11px] font-medium text-emerald-200">Okupansi Asrama</span>
                    <div class="flex items-baseline gap-1 mt-0.5">
                        <span class="text-2xl font-black tracking-tight text-white">{{ $stats['occupancy_rate'] }}%</span>
                        <span class="text-xs text-emerald-200">terisi</span>
                    </div>
                </div>
                <div class="rounded-2xl bg-white/10 backdrop-blur-md px-4 py-3 border border-white/20 min-w-[130px]">
                    <span class="block text-[11px] font-medium text-emerald-200">Santri di Luar</span>
                    <div class="flex items-baseline gap-1 mt-0.5">
                        <span class="text-2xl font-black tracking-tight text-amber-300">{{ $stats['active_leaves'] }}</span>
                        <span class="text-xs text-emerald-200">orang</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Summary Section -->
    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Dormitories Count -->
        <div class="card-natural p-5">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Gedung Asrama</span>
                    <span class="block text-2xl font-black text-slate-900 dark:text-white mt-1">{{ number_format($stats['total_dormitories']) }} <span class="text-xs font-semibold text-slate-400">Gedung</span></span>
                </div>
                <div class="w-11 h-11 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Rooms Count -->
        <div class="card-natural p-5">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Kamar</span>
                    <span class="block text-2xl font-black text-slate-900 dark:text-white mt-1">{{ number_format($stats['total_rooms']) }} <span class="text-xs font-semibold text-slate-400">Kamar</span></span>
                </div>
                <div class="w-11 h-11 rounded-2xl bg-teal-50 dark:bg-teal-950/40 text-teal-600 dark:text-teal-400 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Beds Count -->
        <div class="card-natural p-5">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Kapasitas Ranjang</span>
                    <span class="block text-2xl font-black text-slate-900 dark:text-white mt-1">{{ $stats['occupied_beds'] }}<span class="text-sm font-bold text-slate-400">/{{ $stats['total_beds'] }}</span></span>
                </div>
                <div class="w-11 h-11 rounded-2xl bg-cyan-50 dark:bg-cyan-950/40 text-cyan-600 dark:text-cyan-400 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Active Leaves -->
        <div class="card-natural p-5">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Izin Keluar Aktif</span>
                    <span class="block text-2xl font-black text-amber-600 dark:text-amber-400 mt-1">{{ number_format($stats['active_leaves']) }} <span class="text-xs font-semibold text-slate-400">Santri</span></span>
                </div>
                <div class="w-11 h-11 rounded-2xl bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Action Navigation Hub -->
    <div class="card-natural p-6">
        <h2 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-4">Navigasi Operasional Asrama</h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3">
            @if(auth()->user()->isSuperAdmin() || auth()->user()->isAdmin())
                <a href="{{ route('boarding.dormitories.index') }}" class="flex flex-col items-center justify-center p-3.5 rounded-2xl border border-slate-200/70 hover:border-emerald-500 hover:bg-emerald-50/20 transition-all text-center dark:border-slate-800 group shadow-2xs">
                    <span class="text-lg mb-1">🏢</span>
                    <span class="text-xs font-bold text-slate-800 dark:text-slate-200 group-hover:text-emerald-600">Kelola Gedung</span>
                </a>
                <a href="{{ route('boarding.rooms.index') }}" class="flex flex-col items-center justify-center p-3.5 rounded-2xl border border-slate-200/70 hover:border-emerald-500 hover:bg-emerald-50/20 transition-all text-center dark:border-slate-800 group shadow-2xs">
                    <span class="text-lg mb-1">🚪</span>
                    <span class="text-xs font-bold text-slate-800 dark:text-slate-200 group-hover:text-emerald-600">Daftar Kamar</span>
                </a>
                <a href="{{ route('boarding.beds.index') }}" class="flex flex-col items-center justify-center p-3.5 rounded-2xl border border-slate-200/70 hover:border-emerald-500 hover:bg-emerald-50/20 transition-all text-center dark:border-slate-800 group shadow-2xs">
                    <span class="text-lg mb-1">🛏️</span>
                    <span class="text-xs font-bold text-slate-800 dark:text-slate-200 group-hover:text-emerald-600">Data Ranjang</span>
                </a>
                <a href="{{ route('boarding.supervisors.index') }}" class="flex flex-col items-center justify-center p-3.5 rounded-2xl border border-slate-200/70 hover:border-emerald-500 hover:bg-emerald-50/20 transition-all text-center dark:border-slate-800 group shadow-2xs">
                    <span class="text-lg mb-1">👳‍♂️</span>
                    <span class="text-xs font-bold text-slate-800 dark:text-slate-200 group-hover:text-emerald-600">Pembina Asrama</span>
                </a>
            @endif
            <a href="{{ route('boarding.assignments.index') }}" class="flex flex-col items-center justify-center p-3.5 rounded-2xl border border-slate-200/70 hover:border-emerald-500 hover:bg-emerald-50/20 transition-all text-center dark:border-slate-800 group shadow-2xs">
                <span class="text-lg mb-1">👥</span>
                <span class="text-xs font-bold text-slate-800 dark:text-slate-200 group-hover:text-emerald-600">Penempatan Santri</span>
            </a>
            <a href="{{ route('boarding.roll-calls.index') }}" class="flex flex-col items-center justify-center p-3.5 rounded-2xl border border-emerald-500/30 bg-emerald-50/30 dark:bg-emerald-950/20 hover:border-emerald-500 transition-all text-center dark:border-emerald-800 group shadow-2xs">
                <span class="text-lg mb-1">🌙</span>
                <span class="text-xs font-extrabold text-emerald-700 dark:text-emerald-300">Roll Call (Absen Malam)</span>
            </a>
            <a href="{{ route('boarding.leave-requests.index') }}" class="flex flex-col items-center justify-center p-3.5 rounded-2xl border border-slate-200/70 hover:border-emerald-500 hover:bg-emerald-50/20 transition-all text-center dark:border-slate-800 group shadow-2xs">
                <span class="text-lg mb-1">🎫</span>
                <span class="text-xs font-bold text-slate-800 dark:text-slate-200 group-hover:text-emerald-600">Izin Keluar (Gate Pass)</span>
            </a>
            <a href="{{ route('boarding.health-logs.index') }}" class="flex flex-col items-center justify-center p-3.5 rounded-2xl border border-slate-200/70 hover:border-emerald-500 hover:bg-emerald-50/20 transition-all text-center dark:border-slate-800 group shadow-2xs">
                <span class="text-lg mb-1">🏥</span>
                <span class="text-xs font-bold text-slate-800 dark:text-slate-200 group-hover:text-emerald-600">Kesehatan (UKS)</span>
            </a>
            <a href="{{ route('boarding.discipline-logs.index') }}" class="flex flex-col items-center justify-center p-3.5 rounded-2xl border border-slate-200/70 hover:border-emerald-500 hover:bg-emerald-50/20 transition-all text-center dark:border-slate-800 group shadow-2xs">
                <span class="text-lg mb-1">⭐</span>
                <span class="text-xs font-bold text-slate-800 dark:text-slate-200 group-hover:text-emerald-600">Log Kedisiplinan</span>
            </a>
            <a href="{{ route('boarding.reports.dashboard') }}" class="flex flex-col items-center justify-center p-3.5 rounded-2xl border border-slate-200/70 hover:border-emerald-500 hover:bg-emerald-50/20 transition-all text-center dark:border-slate-800 group shadow-2xs">
                <span class="text-lg mb-1">📊</span>
                <span class="text-xs font-bold text-slate-800 dark:text-slate-200 group-hover:text-emerald-600">Laporan & Rekap</span>
            </a>
        </div>
    </div>

    <!-- Recent Logs Bento Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Health Logs -->
        <div class="card-natural p-6">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100 dark:border-slate-800">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                    <h2 class="text-sm font-extrabold text-slate-900 dark:text-white">Log Kesehatan & UKS Terkini</h2>
                </div>
                <a href="{{ route('boarding.health-logs.index') }}" class="text-xs font-bold text-emerald-600 hover:underline">Lihat Semua &rarr;</a>
            </div>
            <div class="divide-y divide-slate-100 dark:divide-slate-800">
                @forelse($stats['recent_health_logs'] as $log)
                    <div class="py-3 flex justify-between items-start gap-3">
                        <div>
                            <span class="font-bold text-xs text-slate-900 dark:text-white block">{{ $log->student->full_name }}</span>
                            <p class="text-2xs text-slate-500 dark:text-slate-400 mt-0.5">{{ $log->condition_title }}</p>
                        </div>
                        <div class="text-right shrink-0">
                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-bold uppercase 
                                @if($log->severity === 'critical') bg-rose-50 text-rose-700 dark:bg-rose-950/30 dark:text-rose-300
                                @elseif($log->severity === 'high') bg-orange-50 text-orange-700 dark:bg-orange-950/30 dark:text-orange-300
                                @elseif($log->severity === 'medium') bg-amber-50 text-amber-700 dark:bg-amber-950/30 dark:text-amber-300
                                @else bg-emerald-50 text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-300 @endif">
                                {{ $log->severity }}
                            </span>
                            <p class="text-[10px] text-slate-400 mt-1">{{ $log->logged_at->format('d M H:i') }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 py-8 text-center">Belum ada catatan kesehatan tercatat hari ini.</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Discipline Logs -->
        <div class="card-natural p-6">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100 dark:border-slate-800">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                    <h2 class="text-sm font-extrabold text-slate-900 dark:text-white">Log Kedisiplinan & Sikap Terkini</h2>
                </div>
                <a href="{{ route('boarding.discipline-logs.index') }}" class="text-xs font-bold text-emerald-600 hover:underline">Lihat Semua &rarr;</a>
            </div>
            <div class="divide-y divide-slate-100 dark:divide-slate-800">
                @forelse($stats['recent_discipline_logs'] as $log)
                    <div class="py-3 flex justify-between items-start gap-3">
                        <div>
                            <span class="font-bold text-xs text-slate-900 dark:text-white block">{{ $log->student->full_name }}</span>
                            <p class="text-2xs text-slate-500 dark:text-slate-400 mt-0.5">{{ $log->category }}: {{ Str::limit($log->description, 45) }}</p>
                        </div>
                        <div class="text-right shrink-0">
                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-bold uppercase
                                @if($log->type === 'violation') bg-rose-50 text-rose-700 dark:bg-rose-950/30 dark:text-rose-300
                                @elseif($log->type === 'warning') bg-amber-50 text-amber-700 dark:bg-amber-950/30 dark:text-amber-300
                                @elseif($log->type === 'achievement') bg-emerald-50 text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-300
                                @else bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300 @endif">
                                {{ $log->type }} ({{ $log->points > 0 ? '+' : '' }}{{ $log->points }})
                            </span>
                            <p class="text-[10px] text-slate-400 mt-1">{{ $log->logged_at->format('d M H:i') }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 py-8 text-center">Belum ada catatan kedisiplinan tercatat hari ini.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
