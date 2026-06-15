@extends('layouts.app')

@section('content')
<div class="space-y-8 animate-fade-in">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 rounded-2xl bg-gradient-to-r from-emerald-500 via-teal-500 to-cyan-500 p-6 text-white shadow-xl dark:shadow-none">
        <div>
            <h1 class="text-3xl font-extrabold tracking-tight">Boarding School System</h1>
            <p class="mt-1 text-emerald-100">Manajemen Asrama, Perizinan, Kesehatan, dan Kedisiplinan Santri</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <div class="rounded-xl bg-white/10 backdrop-blur-md px-4 py-2 border border-white/20">
                <span class="block text-xs text-emerald-100">Okupansi Ranjang</span>
                <span class="font-bold text-sm">{{ $stats['occupancy_rate'] }}%</span>
            </div>
            <div class="rounded-xl bg-white/10 backdrop-blur-md px-4 py-2 border border-white/20">
                <span class="block text-xs text-emerald-100">Santri Izin Keluar</span>
                <span class="font-bold text-sm">{{ $stats['active_leaves'] }} Orang</span>
            </div>
        </div>
    </div>

    <!-- Stats Summary Section -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Dormitories Count -->
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 hover:shadow-md transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-sm font-medium text-slate-500 dark:text-slate-400">Total Asrama</span>
                    <span class="block text-3xl font-extrabold text-slate-800 dark:text-slate-100 mt-1">{{ number_format($stats['total_dormitories']) }}</span>
                </div>
                <div class="rounded-xl bg-emerald-50 p-3 text-emerald-500 dark:bg-emerald-950/50">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Rooms Count -->
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 hover:shadow-md transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-sm font-medium text-slate-500 dark:text-slate-400">Total Kamar</span>
                    <span class="block text-3xl font-extrabold text-slate-800 dark:text-slate-100 mt-1">{{ number_format($stats['total_rooms']) }}</span>
                </div>
                <div class="rounded-xl bg-teal-50 p-3 text-teal-500 dark:bg-teal-950/50">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Beds Count -->
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 hover:shadow-md transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-sm font-medium text-slate-500 dark:text-slate-400">Okupansi Ranjang</span>
                    <span class="block text-xl font-extrabold text-slate-800 dark:text-slate-100 mt-1">{{ $stats['occupied_beds'] }} / {{ $stats['total_beds'] }} Ranjang</span>
                </div>
                <div class="rounded-xl bg-cyan-50 p-3 text-cyan-500 dark:bg-cyan-950/50">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Active Leaves -->
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 hover:shadow-md transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-sm font-medium text-slate-500 dark:text-slate-400">Santri di Luar</span>
                    <span class="block text-3xl font-extrabold text-slate-800 dark:text-slate-100 mt-1">{{ number_format($stats['active_leaves']) }}</span>
                </div>
                <div class="rounded-xl bg-amber-50 p-3 text-amber-500 dark:bg-amber-950/50">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Navigation Links -->
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <h2 class="text-xl font-bold text-slate-800 dark:text-slate-100 mb-6">Menu Boarding</h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
            @if(auth()->user()->isSuperAdmin() || auth()->user()->isAdmin())
                <a href="{{ route('boarding.dormitories.index') }}" class="flex flex-col items-center justify-center p-4 rounded-xl border border-slate-100 hover:border-emerald-500 hover:bg-emerald-50/10 transition group text-center dark:border-slate-800 dark:hover:bg-slate-950">
                    <span class="text-xs font-bold text-slate-700 dark:text-slate-300 group-hover:text-emerald-500">Kelola Asrama</span>
                </a>
                <a href="{{ route('boarding.rooms.index') }}" class="flex flex-col items-center justify-center p-4 rounded-xl border border-slate-100 hover:border-emerald-500 hover:bg-emerald-50/10 transition group text-center dark:border-slate-800 dark:hover:bg-slate-950">
                    <span class="text-xs font-bold text-slate-700 dark:text-slate-300 group-hover:text-emerald-500">Kelola Kamar</span>
                </a>
                <a href="{{ route('boarding.beds.index') }}" class="flex flex-col items-center justify-center p-4 rounded-xl border border-slate-100 hover:border-emerald-500 hover:bg-emerald-50/10 transition group text-center dark:border-slate-800 dark:hover:bg-slate-950">
                    <span class="text-xs font-bold text-slate-700 dark:text-slate-300 group-hover:text-emerald-500">Kelola Ranjang</span>
                </a>
                <a href="{{ route('boarding.supervisors.index') }}" class="flex flex-col items-center justify-center p-4 rounded-xl border border-slate-100 hover:border-emerald-500 hover:bg-emerald-50/10 transition group text-center dark:border-slate-800 dark:hover:bg-slate-950">
                    <span class="text-xs font-bold text-slate-700 dark:text-slate-300 group-hover:text-emerald-500">Pembina Asrama</span>
                </a>
            @endif
            <a href="{{ route('boarding.assignments.index') }}" class="flex flex-col items-center justify-center p-4 rounded-xl border border-slate-100 hover:border-emerald-500 hover:bg-emerald-50/10 transition group text-center dark:border-slate-800 dark:hover:bg-slate-950">
                <span class="text-xs font-bold text-slate-700 dark:text-slate-300 group-hover:text-emerald-500">Penempatan Santri</span>
            </a>
            <a href="{{ route('boarding.leave-requests.index') }}" class="flex flex-col items-center justify-center p-4 rounded-xl border border-slate-100 hover:border-emerald-500 hover:bg-emerald-50/10 transition group text-center dark:border-slate-800 dark:hover:bg-slate-950">
                <span class="text-xs font-bold text-slate-700 dark:text-slate-300 group-hover:text-emerald-500">Perizinan Keluar</span>
            </a>
            <a href="{{ route('boarding.health-logs.index') }}" class="flex flex-col items-center justify-center p-4 rounded-xl border border-slate-100 hover:border-emerald-500 hover:bg-emerald-50/10 transition group text-center dark:border-slate-800 dark:hover:bg-slate-950">
                <span class="text-xs font-bold text-slate-700 dark:text-slate-300 group-hover:text-emerald-500">Catatan Kesehatan</span>
            </a>
            <a href="{{ route('boarding.discipline-logs.index') }}" class="flex flex-col items-center justify-center p-4 rounded-xl border border-slate-100 hover:border-emerald-500 hover:bg-emerald-50/10 transition group text-center dark:border-slate-800 dark:hover:bg-slate-950">
                <span class="text-xs font-bold text-slate-700 dark:text-slate-300 group-hover:text-emerald-500">Discipline Log</span>
            </a>
            <a href="{{ route('boarding.roll-calls.index') }}" class="flex flex-col items-center justify-center p-4 rounded-xl border border-slate-100 hover:border-emerald-500 hover:bg-emerald-50/10 transition group text-center dark:border-slate-800 dark:hover:bg-slate-950">
                <span class="text-xs font-bold text-slate-700 dark:text-slate-300 group-hover:text-emerald-500">Roll Call (Absen)</span>
            </a>
            <a href="{{ route('boarding.reports.dashboard') }}" class="flex flex-col items-center justify-center p-4 rounded-xl border border-slate-100 hover:border-emerald-500 hover:bg-emerald-50/10 transition group text-center dark:border-slate-800 dark:hover:bg-slate-950">
                <span class="text-xs font-bold text-slate-700 dark:text-slate-300 group-hover:text-emerald-500">Laporan Boarding</span>
            </a>
        </div>
    </div>

    <!-- Recent Logs Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Health Logs -->
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-slate-800 dark:text-slate-100">Log Kesehatan Terbaru</h2>
                <a href="{{ route('boarding.health-logs.index') }}" class="text-xs font-bold text-emerald-500 hover:underline">Lihat Semua</a>
            </div>
            <div class="divide-y divide-slate-100 dark:divide-slate-800">
                @forelse($stats['recent_health_logs'] as $log)
                    <div class="py-3 flex justify-between items-start">
                        <div>
                            <span class="font-bold text-sm text-slate-800 dark:text-slate-250">{{ $log->student->full_name }}</span>
                            <p class="text-xs text-slate-500 mt-0.5">{{ $log->condition_title }}</p>
                        </div>
                        <div class="text-right">
                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-2xs font-medium uppercase 
                                @if($log->severity === 'critical') bg-rose-50 text-rose-700 dark:bg-rose-950/30 dark:text-rose-455
                                @elseif($log->severity === 'high') bg-orange-50 text-orange-700 dark:bg-orange-950/30 dark:text-orange-455
                                @elseif($log->severity === 'medium') bg-amber-50 text-amber-700 dark:bg-amber-950/30 dark:text-amber-455
                                @else bg-emerald-50 text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-455 @endif">
                                {{ $log->severity }}
                            </span>
                            <p class="text-2xs text-slate-400 mt-1">{{ $log->logged_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-slate-500 py-4 text-center">Tidak ada catatan kesehatan terbaru.</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Discipline Logs -->
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-slate-800 dark:text-slate-100">Log Kedisiplinan Terbaru</h2>
                <a href="{{ route('boarding.discipline-logs.index') }}" class="text-xs font-bold text-emerald-500 hover:underline">Lihat Semua</a>
            </div>
            <div class="divide-y divide-slate-100 dark:divide-slate-800">
                @forelse($stats['recent_discipline_logs'] as $log)
                    <div class="py-3 flex justify-between items-start">
                        <div>
                            <span class="font-bold text-sm text-slate-800 dark:text-slate-250">{{ $log->student->full_name }}</span>
                            <p class="text-xs text-slate-500 mt-0.5">{{ $log->category }}: {{ Str::limit($log->description, 50) }}</p>
                        </div>
                        <div class="text-right">
                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-2xs font-medium uppercase
                                @if($log->type === 'violation') bg-rose-50 text-rose-700 dark:bg-rose-950/30 dark:text-rose-455
                                @elseif($log->type === 'warning') bg-amber-50 text-amber-700 dark:bg-amber-950/30 dark:text-amber-455
                                @elseif($log->type === 'achievement') bg-emerald-50 text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-455
                                @else bg-slate-50 text-slate-700 dark:bg-slate-950/30 dark:text-slate-455 @endif">
                                {{ $log->type }} ({{ $log->points > 0 ? '+' : '' }}{{ $log->points }})
                            </span>
                            <p class="text-2xs text-slate-400 mt-1">{{ $log->logged_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-slate-500 py-4 text-center">Tidak ada catatan kedisiplinan terbaru.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
