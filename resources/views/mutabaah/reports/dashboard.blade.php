@extends('layouts.app')

@section('title', 'Laporan Analitik Mutabaah')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-slate-200/80 pb-5 dark:border-slate-800">
        <div>
            <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-50 text-[11px] font-bold text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/40 mb-1">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                Analitik & Rekapitulasi Ibadah
            </div>
            <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-slate-900 dark:text-white">Laporan Mutabaah Santri</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400">Monitoring kemajuan dan kedisiplinan ibadah harian santri antar kelas & halaqah.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('mutabaah.daily.index') }}" class="btn-natural-primary text-xs px-4 py-2">
                ✍️ Input Mutabaah
            </a>
        </div>
    </div>

    {{-- Filter Form --}}
    <div class="card-natural p-5">
        <form method="GET" action="{{ route('mutabaah.reports.dashboard') }}" class="grid gap-4 sm:grid-cols-12 items-end">
            <div class="sm:col-span-3">
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">Tanggal Mulai</label>
                <input type="date" name="start_date" 
                       value="{{ $filters['start_date'] ?? $period['start_date'] }}" 
                       class="w-full rounded-xl border border-slate-200/80 bg-slate-50/50 px-3.5 py-2 text-xs font-semibold text-slate-950 focus:border-emerald-500 focus:bg-white focus:outline-none dark:border-slate-800 dark:bg-slate-950 dark:text-white">
            </div>
            <div class="sm:col-span-3">
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">Tanggal Selesai</label>
                <input type="date" name="end_date" 
                       value="{{ $filters['end_date'] ?? $period['end_date'] }}" 
                       class="w-full rounded-xl border border-slate-200/80 bg-slate-50/50 px-3.5 py-2 text-xs font-semibold text-slate-950 focus:border-emerald-500 focus:bg-white focus:outline-none dark:border-slate-800 dark:bg-slate-950 dark:text-white">
            </div>
            <div class="sm:col-span-4">
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">Pilih Kelas / Halaqah</label>
                <select name="class_room_id" 
                        class="w-full rounded-xl border border-slate-200/80 bg-slate-50/50 px-3 py-2 text-xs font-semibold text-slate-950 focus:border-emerald-500 focus:bg-white focus:outline-none dark:border-slate-800 dark:bg-slate-950 dark:text-white">
                    <option value="">Semua Kelas</option>
                    @foreach($classRooms as $classRoom)
                        <option value="{{ $classRoom->id }}" @selected(($filters['class_room_id'] ?? null) == $classRoom->id)>
                            {{ $classRoom->name ?? 'Kelas #' . $classRoom->id }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="sm:col-span-2">
                <button type="submit" class="w-full btn-natural-primary text-xs py-2.5 justify-center">
                    🔍 Terapkan
                </button>
            </div>
        </form>
    </div>

    {{-- Summary Cards Grid --}}
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Total Records -->
        <div class="card-natural p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Pencatatan</p>
                    <p class="mt-1 text-2xl font-black text-slate-900 dark:text-white">{{ number_format($summary['total_records']) }}</p>
                </div>
                <div class="w-10 h-10 rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 flex items-center justify-center font-bold">
                    📋
                </div>
            </div>
            <p class="mt-2 text-[11px] text-slate-400">Total aktivitas yang direkam</p>
        </div>

        <!-- Selesai -->
        <div class="card-natural p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Terlaksana (Done)</p>
                    <p class="mt-1 text-2xl font-black text-emerald-600 dark:text-emerald-400">{{ number_format($summary['done_records']) }}</p>
                </div>
                <div class="w-10 h-10 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-bold">
                    ✓
                </div>
            </div>
            <p class="mt-2 text-[11px] text-slate-400">Aktivitas sukses dijalankan</p>
        </div>

        <!-- Belum -->
        <div class="card-natural p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Belum Terlaksana</p>
                    <p class="mt-1 text-2xl font-black text-rose-600 dark:text-rose-400">{{ number_format($summary['not_done_records']) }}</p>
                </div>
                <div class="w-10 h-10 rounded-2xl bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 flex items-center justify-center font-bold">
                    ✕
                </div>
            </div>
            <p class="mt-2 text-[11px] text-slate-400">Aktivitas terlewat / belum diisi</p>
        </div>

        <!-- Completion Rate -->
        <div class="card-natural p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Rasio Capaian</p>
                    <p class="mt-1 text-2xl font-black text-teal-600 dark:text-teal-400">{{ $summary['completion_rate'] }}%</p>
                </div>
                <div class="w-10 h-10 rounded-2xl bg-teal-50 dark:bg-teal-950/40 text-teal-600 dark:text-teal-400 flex items-center justify-center font-bold">
                    📈
                </div>
            </div>
            <div class="mt-2 w-full bg-slate-100 rounded-full h-1.5 dark:bg-slate-800">
                <div class="bg-teal-600 h-1.5 rounded-full dark:bg-teal-500" style="width: {{ $summary['completion_rate'] }}%"></div>
            </div>
        </div>
    </div>

    {{-- Tables Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        {{-- Rekap Per Santri --}}
        <div class="card-natural overflow-hidden">
            <div class="p-4 bg-slate-50/70 dark:bg-slate-950/40 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 flex items-center gap-2">
                    <span>👥</span>
                    <span>Rekap Capaian per Santri</span>
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="border-b border-slate-100 text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:border-slate-800 bg-slate-50/40 dark:bg-slate-950/20">
                            <th class="py-3 px-5">Nama Santri</th>
                            <th class="py-3 px-3 text-center w-24">Terlaksana</th>
                            <th class="py-3 px-3 text-center w-20">Total</th>
                            <th class="py-3 px-4 text-center w-28">Rasio</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($by_student as $row)
                            <tr class="hover:bg-slate-50/40 dark:hover:bg-slate-950/10 transition-all">
                                <td class="py-3.5 px-5">
                                    <span class="font-extrabold text-slate-900 dark:text-white block">
                                        {{ $row['student']?->full_name ?? $row['student']?->user?->name ?? 'Santri' }}
                                    </span>
                                    <span class="text-[10px] text-slate-400 font-medium">
                                        {{ $row['student']?->classRoom?->name ?? 'Halaqah Umum' }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-3 text-center font-black text-emerald-600 dark:text-emerald-400">
                                    {{ $row['done'] }}
                                </td>
                                <td class="py-3.5 px-3 text-center font-semibold text-slate-500 dark:text-slate-400">
                                    {{ $row['total'] }}
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    @php
                                        $rate = $row['completion_rate'];
                                        $badgeClass = $rate >= 80 
                                            ? 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-950/30 dark:text-emerald-300 dark:border-emerald-800/40' 
                                            : ($rate >= 50 
                                                ? 'bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-950/30 dark:text-amber-300 dark:border-amber-800/40' 
                                                : 'bg-rose-50 text-rose-700 border-rose-200 dark:bg-rose-950/30 dark:text-rose-300 dark:border-rose-800/40');
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-extrabold border {{ $badgeClass }}">
                                        {{ $rate }}%
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-8 text-center text-xs text-slate-400">Belum ada data perekaman untuk periode ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Rekap Per Aktivitas --}}
        <div class="card-natural overflow-hidden">
            <div class="p-4 bg-slate-50/70 dark:bg-slate-950/40 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 flex items-center gap-2">
                    <span>🕌</span>
                    <span>Rekap Capaian per Aktivitas</span>
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="border-b border-slate-100 text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:border-slate-800 bg-slate-50/40 dark:bg-slate-950/20">
                            <th class="py-3 px-5">Nama Aktivitas</th>
                            <th class="py-3 px-3 text-center w-24">Terlaksana</th>
                            <th class="py-3 px-3 text-center w-20">Total</th>
                            <th class="py-3 px-4 text-center w-28">Rasio</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($by_activity as $row)
                            <tr class="hover:bg-slate-50/40 dark:hover:bg-slate-950/10 transition-all">
                                <td class="py-3.5 px-5">
                                    <span class="font-extrabold text-slate-900 dark:text-white block">
                                        {{ $row['activity']?->name ?? 'Aktivitas' }}
                                    </span>
                                    <span class="text-[10px] text-slate-400 font-medium uppercase tracking-wider">
                                        {{ $row['activity']?->category?->name ?? 'Umum' }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-3 text-center font-black text-emerald-600 dark:text-emerald-400">
                                    {{ $row['done'] }}
                                </td>
                                <td class="py-3.5 px-3 text-center font-semibold text-slate-500 dark:text-slate-400">
                                    {{ $row['total'] }}
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    @php
                                        $rate = $row['completion_rate'];
                                        $badgeClass = $rate >= 80 
                                            ? 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-950/30 dark:text-emerald-300 dark:border-emerald-800/40' 
                                            : ($rate >= 50 
                                                ? 'bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-950/30 dark:text-amber-300 dark:border-amber-800/40' 
                                                : 'bg-rose-50 text-rose-700 border-rose-200 dark:bg-rose-950/30 dark:text-rose-300 dark:border-rose-800/40');
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-extrabold border {{ $badgeClass }}">
                                        {{ $rate }}%
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-8 text-center text-xs text-slate-400">Belum ada data perekaman untuk periode ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>
@endsection

