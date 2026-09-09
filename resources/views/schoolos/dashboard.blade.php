@extends('layouts.app')

@section('content')
<div class="space-y-8 animate-fade-in">
    <!-- SchoolOS Bento Hero Header -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-slate-900 via-slate-900 to-slate-950 border border-slate-800 p-8 text-white shadow-xl shadow-slate-950/30">
        <div class="absolute -right-16 -top-16 h-64 w-64 rounded-full bg-emerald-500/15 blur-3xl pointer-events-none"></div>
        <div class="absolute -left-16 -bottom-16 h-64 w-64 rounded-full bg-teal-500/10 blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div class="space-y-2 max-w-xl">
                <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-bold tracking-wide">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>SchoolOS Academic Core</span>
                </div>
                <h1 class="text-3xl sm:text-4xl font-black tracking-tight text-white">
                    School<span class="text-emerald-400">OS</span> Mini
                </h1>
                <p class="text-sm text-slate-300 leading-relaxed">
                    Pusat integrasi akademik, monitoring setoran hafalan, pencatatan presensi harian, dan registry modul sekolah terpadu.
                </p>
            </div>

            <!-- Academic State Chips -->
            <div class="flex flex-wrap items-center gap-3 shrink-0">
                <div class="rounded-2xl bg-slate-800/80 backdrop-blur-md px-4 py-2.5 border border-slate-700/80">
                    <span class="block text-[10px] font-semibold uppercase tracking-wider text-slate-400">Tahun Ajaran</span>
                    <span class="font-bold text-sm text-white">{{ $activeAcademicYear?->name ?? 'Belum Diatur' }}</span>
                </div>
                <div class="rounded-2xl bg-slate-800/80 backdrop-blur-md px-4 py-2.5 border border-slate-700/80">
                    <span class="block text-[10px] font-semibold uppercase tracking-wider text-slate-400">Semester</span>
                    <span class="font-bold text-sm text-white">{{ $activeTerm?->name ?? 'Belum Diatur' }}</span>
                </div>
                <div class="rounded-2xl bg-emerald-950/40 backdrop-blur-md px-4 py-2.5 border border-emerald-800/50">
                    <span class="block text-[10px] font-semibold uppercase tracking-wider text-emerald-400">Hak Akses</span>
                    <span class="font-bold text-sm text-emerald-300 uppercase">{{ str_replace('_', ' ', $roleName) }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Search Section -->
    <div class="rounded-3xl border border-slate-200/80 bg-white p-6 shadow-xs dark:border-slate-800 dark:bg-slate-900">
        <div class="flex items-center justify-between mb-3">
            <h2 class="text-sm font-bold text-slate-800 dark:text-slate-200 flex items-center gap-2">
                <svg class="w-4.5 h-4.5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                Pencarian Santri Cepat
            </h2>
            <span class="text-xs text-slate-400 hidden sm:inline">Cari berdasarkan Nama, NISN, atau ID Siswa</span>
        </div>
        <form method="GET" action="{{ route('schoolos.search') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <input
                    type="text"
                    name="q"
                    minlength="2"
                    placeholder="Ketik nama santri atau NISN..."
                    class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 pl-11 text-sm text-slate-900 placeholder:text-slate-400 focus:border-emerald-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 transition-all dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100 dark:focus:bg-slate-900"
                    required
                >
                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>
            <button class="rounded-2xl bg-emerald-500 hover:bg-emerald-400 px-6 py-3 font-bold text-sm text-slate-950 shadow-md shadow-emerald-500/20 active:scale-[0.98] transition-all">
                Cari Santri
            </button>
        </form>
    </div>

    <!-- Bento Stats Summary Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Students Count -->
        <div class="rounded-3xl border border-slate-200/80 bg-white p-6 shadow-xs dark:border-slate-800 dark:bg-slate-900 hover:shadow-md transition duration-200 flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total Santri</span>
                    <span class="block text-3xl font-black text-slate-900 dark:text-white mt-1">{{ number_format($summary['students_count']) }}</span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 dark:bg-emerald-950/50 dark:text-emerald-400 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
            <span class="text-xs text-slate-500 dark:text-slate-400 mt-4 block">Terdaftar pada database aktif</span>
        </div>

        <!-- Hafalan Today -->
        <div class="rounded-3xl border border-slate-200/80 bg-white p-6 shadow-xs dark:border-slate-800 dark:bg-slate-900 hover:shadow-md transition duration-200 flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">Setoran Hari Ini</span>
                    <span class="block text-3xl font-black text-slate-900 dark:text-white mt-1">{{ number_format($summary['hafalan_records_today']) }}</span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 dark:bg-emerald-950/50 dark:text-emerald-400 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
            </div>
            <span class="text-xs text-slate-500 dark:text-slate-400 mt-4 block">Aktivitas halaqah terkini</span>
        </div>

        <!-- Attendance Today -->
        <div class="rounded-3xl border border-slate-200/80 bg-white p-6 shadow-xs dark:border-slate-800 dark:bg-slate-900 hover:shadow-md transition duration-200 flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold uppercase tracking-wider text-teal-600 dark:text-teal-400">Presensi Hari Ini</span>
                    <span class="block text-3xl font-black text-slate-900 dark:text-white mt-1">{{ number_format($summary['attendance_records_today']) }}</span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-600 dark:bg-teal-950/50 dark:text-teal-400 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                </div>
            </div>
            <span class="text-xs text-slate-500 dark:text-slate-400 mt-4 block">Santri hadir tercatat</span>
        </div>

        <!-- Open Bills -->
        <div class="rounded-3xl border border-slate-200/80 bg-white p-6 shadow-xs dark:border-slate-800 dark:bg-slate-900 hover:shadow-md transition duration-200 flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold uppercase tracking-wider text-amber-600 dark:text-amber-400">Tagihan Terbuka</span>
                    <span class="block text-3xl font-black text-slate-900 dark:text-white mt-1">{{ number_format($summary['open_finance_bills']) }}</span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 dark:bg-amber-950/50 dark:text-amber-400 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <span class="text-xs text-slate-500 dark:text-slate-400 mt-4 block">Menunggu penyelesaian</span>
        </div>
    </div>

    <!-- Active Modules Grid -->
    <div class="rounded-3xl border border-slate-200/80 bg-white p-7 shadow-xs dark:border-slate-800 dark:bg-slate-900">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-6">
            <div>
                <h2 class="text-lg font-black text-slate-900 dark:text-white">Registry Modul Platform</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400">Modul yang diaktifkan untuk lingkungan institusi Anda</p>
            </div>
            <span class="text-xs font-semibold text-emerald-600 dark:text-emerald-400 self-start sm:self-auto">
                {{ count($modules) }} Modul Aktif
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($modules as $module)
                <div class="group relative rounded-2xl border border-slate-200/70 bg-slate-50/50 p-5 hover:bg-white hover:shadow-md hover:border-emerald-300 dark:border-slate-800 dark:bg-slate-950/40 dark:hover:bg-slate-900 dark:hover:border-emerald-800/80 transition-all duration-300 flex flex-col justify-between">
                    <div>
                        <div class="flex items-start justify-between gap-3">
                            <div class="space-y-1">
                                <span class="inline-flex items-center rounded-md bg-emerald-50 px-2 py-0.5 text-[10px] font-bold text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-200/50 dark:border-emerald-800/50">
                                    Urutan {{ $module->sort_order }}
                                </span>
                                <h3 class="text-base font-bold text-slate-900 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">
                                    {{ $module->name }}
                                </h3>
                            </div>
                            <div class="rounded-xl bg-slate-100 p-2.5 text-slate-700 dark:bg-slate-800 dark:text-slate-300 group-hover:bg-emerald-50 group-hover:text-emerald-600 dark:group-hover:bg-emerald-950/50 dark:group-hover:text-emerald-400 transition-colors">
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
                        <p class="mt-3 text-xs text-slate-500 dark:text-slate-400 line-clamp-2 leading-relaxed">
                            {{ $module->description }}
                        </p>
                    </div>

                    <div class="mt-4 pt-3 border-t border-slate-200/60 dark:border-slate-800 flex items-center justify-between">
                        @if($module->route_exists && $module->route_name)
                            <a href="{{ route($module->route_name) }}" class="inline-flex items-center text-xs font-bold text-emerald-600 hover:text-emerald-500 dark:text-emerald-400 dark:hover:text-emerald-300 group-hover:underline">
                                Buka Modul
                                <svg class="ml-1 w-3.5 h-3.5 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                            </a>
                        @else
                            <span class="text-[11px] text-slate-400 font-medium">Standby</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
