@extends('layouts.app')

@section('content')
<div class="space-y-8 animate-fade-in">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 rounded-2xl bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 p-6 text-white shadow-xl dark:shadow-none">
        <div>
            <h1 class="text-3xl font-extrabold tracking-tight">SchoolOS Mini</h1>
            <p class="mt-1 text-indigo-100">Pusat Integrasi HafizPlus School Platform</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <div class="rounded-xl bg-white/10 backdrop-blur-md px-4 py-2 border border-white/20">
                <span class="block text-xs text-indigo-100">Tahun Ajaran</span>
                <span class="font-bold text-sm">{{ $activeAcademicYear?->name ?? 'Belum Diatur' }}</span>
            </div>
            <div class="rounded-xl bg-white/10 backdrop-blur-md px-4 py-2 border border-white/20">
                <span class="block text-xs text-indigo-100">Semester</span>
                <span class="font-bold text-sm">{{ $activeTerm?->name ?? 'Belum Diatur' }}</span>
            </div>
            <div class="rounded-xl bg-white/10 backdrop-blur-md px-4 py-2 border border-white/20">
                <span class="block text-xs text-indigo-100">Hak Akses</span>
                <span class="font-bold text-sm uppercase">{{ str_replace('_', ' ', $roleName) }}</span>
            </div>
        </div>
    </div>

    <!-- Search Section -->
    <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm dark:border-slate-800/80 dark:bg-slate-900">
        <h2 class="text-lg font-bold text-slate-800 dark:text-slate-100 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            Pencarian Santri Global
        </h2>
        <form method="GET" action="{{ route('schoolos.search') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <input
                    type="text"
                    name="q"
                    minlength="2"
                    placeholder="Masukkan nama, NISN, atau nomor santri..."
                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 pl-11 text-slate-900 placeholder:text-slate-400 focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 transition-all dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100 dark:focus:bg-slate-900"
                    required
                >
                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>
            <button class="rounded-xl bg-gradient-to-r from-indigo-500 to-purple-600 px-6 py-3 font-semibold text-white shadow-md shadow-indigo-200 hover:opacity-90 active:scale-95 transition-all dark:shadow-none">
                Cari Santri
            </button>
        </form>
    </div>

    <!-- Stats Summary Section -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Students Count -->
        <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm dark:border-slate-800/80 dark:bg-slate-900 hover:shadow-md transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-sm font-medium text-slate-500 dark:text-slate-400">Total Santri</span>
                    <span class="block text-3xl font-extrabold text-slate-800 dark:text-slate-100 mt-1">{{ number_format($summary['students_count']) }}</span>
                </div>
                <div class="rounded-xl bg-indigo-50 p-3 text-indigo-500 dark:bg-indigo-950/50">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Hafalan Today -->
        <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm dark:border-slate-800/80 dark:bg-slate-900 hover:shadow-md transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-sm font-medium text-slate-500 dark:text-slate-400">Setoran Hari Ini</span>
                    <span class="block text-3xl font-extrabold text-slate-800 dark:text-slate-100 mt-1">{{ number_format($summary['hafalan_records_today']) }}</span>
                </div>
                <div class="rounded-xl bg-emerald-50 p-3 text-emerald-500 dark:bg-emerald-950/50">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Attendance Today -->
        <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm dark:border-slate-800/80 dark:bg-slate-900 hover:shadow-md transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-sm font-medium text-slate-500 dark:text-slate-400">Presensi Hari Ini</span>
                    <span class="block text-3xl font-extrabold text-slate-800 dark:text-slate-100 mt-1">{{ number_format($summary['attendance_records_today']) }}</span>
                </div>
                <div class="rounded-xl bg-purple-50 p-3 text-purple-500 dark:bg-purple-950/50">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Bills unpaid -->
        <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm dark:border-slate-800/80 dark:bg-slate-900 hover:shadow-md transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-sm font-medium text-slate-500 dark:text-slate-400">Tagihan Terbuka</span>
                    <span class="block text-3xl font-extrabold text-slate-800 dark:text-slate-100 mt-1">{{ number_format($summary['open_finance_bills']) }}</span>
                </div>
                <div class="rounded-xl bg-amber-50 p-3 text-amber-500 dark:bg-amber-950/50">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Modules -->
    <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm dark:border-slate-800/80 dark:bg-slate-900">
        <h2 class="text-xl font-bold text-slate-800 dark:text-slate-100 mb-6">Modul Platform Aktif</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($modules as $module)
                <div class="group relative rounded-xl border border-slate-100 bg-slate-50/50 p-5 hover:bg-white hover:shadow-md hover:border-indigo-150 transition-all duration-300 dark:border-slate-800 dark:bg-slate-950/50 dark:hover:bg-slate-900">
                    <div class="flex items-start justify-between">
                        <div class="space-y-1">
                            <span class="inline-flex items-center rounded-full bg-indigo-50 px-2 py-1 text-xs font-semibold text-indigo-600 dark:bg-indigo-950/50 dark:text-indigo-400">
                                Modul {{ $module->sort_order }}
                            </span>
                            <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                                {{ $module->name }}
                            </h3>
                        </div>
                        <div class="rounded-lg bg-indigo-50/50 p-2 text-indigo-500 dark:bg-indigo-950/30">
                            <!-- Fallback generic icon representation based on key -->
                            @if($module->module_key === 'tahfizh')
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13M12 6.253C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13" /></svg>
                            @elseif($module->module_key === 'mutabaah')
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2" /></svg>
                            @elseif($module->module_key === 'attendance')
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-16v3m9 9h-1M4 12H3" /></svg>
                            @elseif($module->module_key === 'tahsin')
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l.707-.707m2.828 9.9a5 5 0 113.62 0h-3.62z" /></svg>
                            @elseif($module->module_key === 'finance')
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1" /></svg>
                            @else
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            @endif
                        </div>
                    </div>
                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400 line-clamp-2">
                        {{ $module->description }}
                    </p>
                    <div class="mt-4 flex items-center justify-between border-t border-slate-100 pt-3 dark:border-slate-800">
                        @if($module->route_exists && $module->route_name)
                            <a href="{{ route($module->route_name) }}" class="inline-flex items-center text-sm font-semibold text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300">
                                Buka Modul
                                <svg class="ml-1 w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                            </a>
                        @else
                            <span class="text-xs text-rose-500 font-medium">Route Belum Siap</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
