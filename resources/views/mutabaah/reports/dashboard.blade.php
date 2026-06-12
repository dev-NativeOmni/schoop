@extends('layouts.app')

@section('title', 'Laporan Mutabaah')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between border-b border-slate-200/80 pb-5 dark:border-slate-800">
        <div>
            <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 dark:bg-indigo-950/30 dark:text-indigo-400">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z" />
                    </svg>
                </div>
                <span>Laporan Mutabaah Santri</span>
            </h1>
            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Ringkasan kemajuan dan rekapitulasi mutabaah santri berdasarkan periode.</p>
        </div>
    </div>

    {{-- Filter Form --}}
    <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <form method="GET" action="{{ route('mutabaah.reports.dashboard') }}" class="grid gap-4 sm:grid-cols-5 items-end">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">Tanggal Mulai</label>
                <input type="date" name="start_date" 
                       value="{{ $filters['start_date'] ?? $period['start_date'] }}" 
                       class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-sm text-slate-950 placeholder-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 dark:border-slate-800 dark:bg-slate-950 dark:text-white dark:focus:border-indigo-500">
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">Tanggal Selesai</label>
                <input type="date" name="end_date" 
                       value="{{ $filters['end_date'] ?? $period['end_date'] }}" 
                       class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-sm text-slate-950 placeholder-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 dark:border-slate-800 dark:bg-slate-950 dark:text-white dark:focus:border-indigo-500">
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">Kelas</label>
                <select name="class_room_id" 
                        class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-sm text-slate-950 focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 dark:border-slate-800 dark:bg-slate-950 dark:text-white dark:focus:border-indigo-500">
                    <option value="">Semua Kelas</option>
                    @foreach($classRooms as $classRoom)
                        <option value="{{ $classRoom->id }}" @selected(($filters['class_room_id'] ?? null) == $classRoom->id)>
                            {{ $classRoom->name ?? 'Kelas #' . $classRoom->id }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">Santri</label>
                <select name="student_id" 
                        class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-sm text-slate-950 focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 dark:border-slate-800 dark:bg-slate-950 dark:text-white dark:focus:border-indigo-500">
                    <option value="">Semua Santri</option>
                    {{-- Diisi dinamis jika diperlukan, atau filter class_room_id yang menyaring --}}
                </select>
            </div>
            <div>
                <button type="submit" 
                        class="flex w-full items-center justify-center space-x-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700 shadow-sm transition-all focus:outline-none focus:ring-4 focus:ring-indigo-500/20 active:scale-[0.98]">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <span>Cari Laporan</span>
                </button>
            </div>
        </form>
    </div>

    {{-- Summary Cards Grid --}}
    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Total Records -->
        <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 transition hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Total Laporan</p>
                    <p class="mt-2 text-3xl font-extrabold text-slate-900 dark:text-white">{{ number_format($summary['total_records']) }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 dark:bg-indigo-950/30 dark:text-indigo-400">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
            </div>
            <p class="mt-3 text-xs text-slate-450 dark:text-slate-500">Jumlah rekam aktivitas mutabaah</p>
        </div>

        <!-- Selesai -->
        <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 transition hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Terlaksana</p>
                    <p class="mt-2 text-3xl font-extrabold text-emerald-600 dark:text-emerald-450">{{ number_format($summary['done_records']) }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 dark:bg-emerald-950/30 dark:text-emerald-450">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <p class="mt-3 text-xs text-slate-450 dark:text-slate-500">Aktivitas ibadah selesai dilakukan</p>
        </div>

        <!-- Belum -->
        <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 transition hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Belum Terlaksana</p>
                    <p class="mt-2 text-3xl font-extrabold text-rose-600 dark:text-rose-450">{{ number_format($summary['not_done_records']) }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-rose-50 text-rose-600 dark:bg-rose-950/30 dark:text-rose-450">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <p class="mt-3 text-xs text-slate-450 dark:text-slate-500">Aktivitas yang belum dicentang/diisi</p>
        </div>

        <!-- Completion Rate -->
        <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 transition hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Rasio Keberhasilan</p>
                    <p class="mt-2 text-3xl font-extrabold text-indigo-600 dark:text-indigo-400">{{ $summary['completion_rate'] }}%</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 dark:bg-indigo-950/30 dark:text-indigo-400">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 3.055A9.003 9.003 0 1020.945 13H11V3.055z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                    </svg>
                </div>
            </div>
            <div class="mt-3 w-full bg-slate-100 rounded-full h-1.5 dark:bg-slate-800">
                <div class="bg-indigo-600 h-1.5 rounded-full dark:bg-indigo-505" style="width: {{ $summary['completion_rate'] }}%"></div>
            </div>
        </div>
    </div>

    {{-- Tables Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        {{-- Rekap Per Santri --}}
        <div class="rounded-2xl border border-slate-200/80 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900 overflow-hidden">
            <div class="bg-slate-50/80 dark:bg-slate-850 px-6 py-4 border-b border-slate-200/80 dark:border-slate-800 flex items-center justify-between">
                <h3 class="text-base font-bold text-slate-800 dark:text-white flex items-center gap-2">
                    <svg class="h-5 w-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span>Rekap per Santri</span>
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/40 dark:bg-slate-850/40 border-b border-slate-200/80 dark:border-slate-800">
                            <th class="px-6 py-3 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Nama Santri</th>
                            <th class="px-6 py-3 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 text-center w-28">Terlaksana</th>
                            <th class="px-6 py-3 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 text-center w-24">Total</th>
                            <th class="px-6 py-3 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 text-center w-32">Rasio</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($by_student as $row)
                            <tr class="hover:bg-slate-50/40 dark:hover:bg-slate-850/10 transition-all">
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-2">
                                        <span class="text-sm font-bold text-slate-800 dark:text-slate-250">
                                            {{ $row['student']?->full_name ?? $row['student']?->user?->name ?? 'Santri' }}
                                        </span>
                                    </div>
                                    <span class="text-[10px] text-slate-400 font-medium block">
                                        {{ $row['student']?->classRoom?->name ?? 'Belum ada kelas' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center text-sm font-bold text-emerald-600 dark:text-emerald-400">
                                    {{ $row['done'] }}
                                </td>
                                <td class="px-6 py-4 text-center text-sm font-medium text-slate-500 dark:text-slate-400">
                                    {{ $row['total'] }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @php
                                        $rate = $row['completion_rate'];
                                        $badgeClass = $rate >= 80 
                                            ? 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-950/20 dark:text-emerald-450 dark:border-emerald-900/50' 
                                            : ($rate >= 50 
                                                ? 'bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-950/20 dark:text-amber-450 dark:border-amber-900/50' 
                                                : 'bg-rose-50 text-rose-700 border-rose-200 dark:bg-rose-950/20 dark:text-rose-450 dark:border-rose-900/50');
                                    @endphp
                                    <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-bold {{ $badgeClass }}">
                                        {{ $rate }}%
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-sm text-slate-450 dark:text-slate-500">Belum ada data perekaman untuk periode ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Rekap Per Aktivitas --}}
        <div class="rounded-2xl border border-slate-200/80 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900 overflow-hidden">
            <div class="bg-slate-50/80 dark:bg-slate-850 px-6 py-4 border-b border-slate-200/80 dark:border-slate-800 flex items-center justify-between">
                <h3 class="text-base font-bold text-slate-800 dark:text-white flex items-center gap-2">
                    <svg class="h-5 w-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <span>Rekap per Aktivitas</span>
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/40 dark:bg-slate-850/40 border-b border-slate-200/80 dark:border-slate-800">
                            <th class="px-6 py-3 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Nama Aktivitas</th>
                            <th class="px-6 py-3 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 text-center w-28">Terlaksana</th>
                            <th class="px-6 py-3 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 text-center w-24">Total</th>
                            <th class="px-6 py-3 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 text-center w-32">Rasio</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($by_activity as $row)
                            <tr class="hover:bg-slate-50/40 dark:hover:bg-slate-850/10 transition-all">
                                <td class="px-6 py-4">
                                    <span class="text-sm font-bold text-slate-800 dark:text-slate-250 block">
                                        {{ $row['activity']?->name ?? 'Aktivitas' }}
                                    </span>
                                    <span class="text-[10px] text-slate-400 font-medium uppercase tracking-wider">
                                        {{ $row['activity']?->category?->name ?? 'Kategori' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center text-sm font-bold text-emerald-600 dark:text-emerald-400">
                                    {{ $row['done'] }}
                                </td>
                                <td class="px-6 py-4 text-center text-sm font-medium text-slate-500 dark:text-slate-400">
                                    {{ $row['total'] }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @php
                                        $rate = $row['completion_rate'];
                                        $badgeClass = $rate >= 80 
                                            ? 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-950/20 dark:text-emerald-450 dark:border-emerald-900/50' 
                                            : ($rate >= 50 
                                                ? 'bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-950/20 dark:text-amber-450 dark:border-amber-900/50' 
                                                : 'bg-rose-50 text-rose-700 border-rose-200 dark:bg-rose-950/20 dark:text-rose-450 dark:border-rose-900/50');
                                    @endphp
                                    <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-bold {{ $badgeClass }}">
                                        {{ $rate }}%
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-sm text-slate-450 dark:text-slate-500">Belum ada data perekaman untuk periode ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>
@endsection
