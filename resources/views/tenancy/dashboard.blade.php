@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <!-- Header banner -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-900 to-indigo-950 p-8 text-white shadow-2xl">
        <div class="relative z-10 md:flex md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl font-black tracking-tight md:text-4xl">Tenant Dashboard</h1>
                <p class="mt-2 text-indigo-200">Konfigurasi, status module, dan log aktivitas untuk <strong class="text-white">{{ $school->name }}</strong></p>
            </div>
            <div class="mt-6 flex flex-wrap gap-3 md:mt-0">
                <a href="{{ route('tenancy.switcher') }}" class="inline-flex items-center gap-2 rounded-2xl bg-white/10 px-5 py-3 font-bold text-white backdrop-blur-md transition-all hover:bg-white/20 hover:scale-[1.02] active:scale-[0.98]">
                    <svg class="h-5 w-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                    </svg>
                    Ganti Sekolah
                </a>
            </div>
        </div>
        <!-- Decorative subtle pattern overlay -->
        <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#10b981_1px,transparent_1px)] [background-size:16px_16px]"></div>
    </div>

    <!-- Stats Grid -->
    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Stat Cards -->
        <div class="group rounded-3xl border border-slate-200 bg-white p-6 shadow-sm transition-all hover:shadow-md hover:scale-[1.01] dark:border-slate-800 dark:bg-slate-900">
            <div class="flex items-center justify-between">
                <span class="text-sm font-bold text-slate-500 dark:text-slate-400">Total Siswa</span>
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-50 text-indigo-600 transition-colors group-hover:bg-indigo-100 dark:bg-indigo-950/40 dark:text-indigo-400 dark:group-hover:bg-indigo-950/70">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-3xl font-black text-slate-900 dark:text-white">{{ $stats['students_count'] }}</span>
                <span class="block text-xs text-slate-400 mt-1 dark:text-slate-500">Siswa aktif terdaftar</span>
            </div>
        </div>

        <div class="group rounded-3xl border border-slate-200 bg-white p-6 shadow-sm transition-all hover:shadow-md hover:scale-[1.01] dark:border-slate-800 dark:bg-slate-900">
            <div class="flex items-center justify-between">
                <span class="text-sm font-bold text-slate-500 dark:text-slate-400">Kelas Terdaftar</span>
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600 transition-colors group-hover:bg-emerald-100 dark:bg-emerald-950/40 dark:text-emerald-400 dark:group-hover:bg-emerald-950/70">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-3xl font-black text-slate-900 dark:text-white">{{ $stats['classrooms_count'] }}</span>
                <span class="block text-xs text-slate-400 mt-1 dark:text-slate-500">Kelas aktif terdaftar</span>
            </div>
        </div>

        <div class="group rounded-3xl border border-slate-200 bg-white p-6 shadow-sm transition-all hover:shadow-md hover:scale-[1.01] dark:border-slate-800 dark:bg-slate-900">
            <div class="flex items-center justify-between">
                <span class="text-sm font-bold text-slate-500 dark:text-slate-400">User Memberships</span>
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-50 text-amber-600 transition-colors group-hover:bg-amber-100 dark:bg-amber-950/40 dark:text-amber-400 dark:group-hover:bg-amber-950/70">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-3xl font-black text-slate-900 dark:text-white">{{ $stats['memberships_count'] }}</span>
                <span class="block text-xs text-slate-400 mt-1 dark:text-slate-500">Staf & Guru terhubung</span>
            </div>
        </div>

        <div class="group rounded-3xl border border-slate-200 bg-white p-6 shadow-sm transition-all hover:shadow-md hover:scale-[1.01] dark:border-slate-800 dark:bg-slate-900">
            <div class="flex items-center justify-between">
                <span class="text-sm font-bold text-slate-500 dark:text-slate-400">Module Aktif</span>
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-lime-50 text-lime-700 transition-colors group-hover:bg-lime-100 dark:bg-lime-950/40 dark:text-lime-400 dark:group-hover:bg-lime-950/70">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364.364l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" /></svg>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-3xl font-black text-slate-900 dark:text-white">{{ $stats['modules_enabled_count'] }}</span>
                <span class="block text-xs text-slate-400 mt-1 dark:text-slate-500">Module diaktifkan</span>
            </div>
        </div>
    </div>

    <!-- Quick Actions and Audit Logs -->
    <div class="grid gap-8 lg:grid-cols-3">
        <!-- Quick Configuration Cards -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Active Modules -->
            <div class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-center justify-between border-b border-slate-100 px-6 py-5 bg-slate-50/50 dark:border-slate-800 dark:bg-slate-850">
                    <h2 class="text-lg font-black text-slate-900 dark:text-white">Module Configuration</h2>
                    <a href="{{ route('tenancy.modules.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 flex items-center gap-1 transition-colors">
                        Kelola
                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                    </a>
                </div>
                <div class="p-6">
                    <div class="grid gap-3 sm:grid-cols-2">
                        @forelse($modules as $module)
                            <div class="flex items-center justify-between rounded-2xl border border-slate-100 p-4 transition-all hover:border-slate-200 hover:bg-slate-50/40 dark:border-slate-800 dark:hover:border-slate-700 dark:hover:bg-slate-850/70">
                                <span class="text-sm font-bold text-slate-800 dark:text-slate-200">{{ $module->module_name }}</span>
                                @if($module->is_enabled)
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-800 dark:bg-emerald-950/30 dark:text-emerald-300">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-600"></span>
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600 dark:bg-slate-800 dark:text-slate-300">
                                        <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                                        Nonaktif
                                    </span>
                                @endif
                            </div>
                        @empty
                            <p class="text-sm text-slate-500 col-span-2 text-center py-4 dark:text-slate-400">Belum ada module terdaftar.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Settings Summary -->
            <div class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-center justify-between border-b border-slate-100 px-6 py-5 bg-slate-50/50 dark:border-slate-800 dark:bg-slate-850">
                    <h2 class="text-lg font-black text-slate-900 dark:text-white">Tenant Settings</h2>
                    <a href="{{ route('tenancy.settings.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 flex items-center gap-1 transition-colors">
                        Ubah
                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                    </a>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @forelse($settings as $setting)
                            <div class="flex items-center justify-between border-b border-slate-100 pb-3 last:border-0 last:pb-0 dark:border-slate-800">
                                <div>
                                    <span class="block text-sm font-bold text-slate-800 dark:text-slate-200">{{ $setting->setting_key }}</span>
                                    <span class="block text-xs text-slate-400 mt-0.5 dark:text-slate-500">{{ $setting->description ?? 'Tidak ada deskripsi' }}</span>
                                </div>
                                <span class="text-sm font-bold text-slate-600 dark:text-slate-300">{{ Str::limit($setting->setting_value, 40) }}</span>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500 text-center py-4 dark:text-slate-400">Belum ada settings yang diset.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Audit Logs Column -->
        <div class="space-y-6">
            <div class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden h-full flex flex-col dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-center justify-between border-b border-slate-100 px-6 py-5 bg-slate-50/50 dark:border-slate-800 dark:bg-slate-850">
                    <h2 class="text-lg font-black text-slate-900 dark:text-white">Recent Activity</h2>
                    <a href="{{ route('tenancy.audit-logs.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 flex items-center gap-1 transition-colors">
                        Semua
                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                    </a>
                </div>
                <div class="p-6 flex-1 overflow-y-auto">
                    <div class="flow-root">
                        <ul role="list" class="-mb-8">
                            @forelse($auditLogs as $log)
                                <li>
                                    <div class="relative pb-8">
                                        @if(!$loop->last)
                                            <span class="absolute left-5 top-5 -ml-px h-full w-0.5 bg-slate-200 dark:bg-slate-800" aria-hidden="true"></span>
                                        @endif
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-100 ring-8 ring-white text-slate-600 dark:bg-slate-800 dark:ring-slate-900 dark:text-slate-300">
                                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 00-2 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                                                </span>
                                            </div>
                                            <div class="flex-1 min-w-0 pt-1.5">
                                                <p class="text-sm font-bold text-slate-800 dark:text-slate-200">
                                                    {{ $log->action }}
                                                </p>
                                                <p class="text-xs text-slate-400 mt-1 dark:text-slate-500">
                                                    Oleh: <strong class="text-slate-600 dark:text-slate-300">{{ $log->user?->name ?? 'System' }}</strong>
                                                </p>
                                                <time class="block text-[10px] text-slate-400 mt-0.5 dark:text-slate-500">
                                                    {{ $log->created_at->diffForHumans() }}
                                                </time>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @empty
                                <p class="text-sm text-slate-500 text-center py-4 dark:text-slate-400">Belum ada log aktivitas.</p>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
