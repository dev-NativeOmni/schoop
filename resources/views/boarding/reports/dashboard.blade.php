@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between border-b border-slate-200 pb-4 dark:border-slate-800">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Laporan & Statistik Boarding</h1>
            <p class="text-sm text-slate-500">Analisis tingkat kehadiran, kedisiplinan, dan data kesehatan santri.</p>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <form action="{{ route('boarding.reports.dashboard') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
            <!-- Start Date -->
            <div>
                <label for="start_date" class="block text-xs font-bold uppercase text-slate-400 dark:text-slate-500 mb-1">Tanggal Mulai</label>
                <input type="date" name="start_date" id="start_date" value="{{ $startDate }}" class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-800 dark:bg-slate-950 dark:text-white">
            </div>

            <!-- End Date -->
            <div>
                <label for="end_date" class="block text-xs font-bold uppercase text-slate-400 dark:text-slate-500 mb-1">Tanggal Akhir</label>
                <input type="date" name="end_date" id="end_date" value="{{ $endDate }}" class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-800 dark:bg-slate-950 dark:text-white">
            </div>

            <!-- Dormitory Filter -->
            <div>
                <label for="boarding_dormitory_id" class="block text-xs font-bold uppercase text-slate-400 dark:text-slate-500 mb-1">Asrama</label>
                <select name="boarding_dormitory_id" id="boarding_dormitory_id" class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-800 dark:bg-slate-950 dark:text-white">
                    <option value="">Semua Asrama</option>
                    @foreach($dormitories as $dorm)
                        <option value="{{ $dorm->id }}" {{ $dormitoryId == $dorm->id ? 'selected' : '' }}>
                            {{ $dorm->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Buttons -->
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 rounded-xl bg-slate-800 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700 transition dark:bg-slate-750 dark:hover:bg-slate-700">Filter Laporan</button>
                @if(request()->anyFilled(['start_date', 'end_date', 'boarding_dormitory_id']))
                    <a href="{{ route('boarding.reports.dashboard') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-350 dark:hover:bg-slate-950">Reset</a>
                @endif
            </div>
        </form>
    </div>

    <!-- Attendance Rate and Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Attendance Performance -->
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 space-y-6">
            <h2 class="text-lg font-bold text-slate-800 dark:text-white border-b border-slate-100 pb-2 dark:border-slate-800">Tingkat Kehadiran Roll Call</h2>
            
            <div class="text-center py-6">
                <span class="text-xs text-slate-400 font-semibold uppercase tracking-wider block">Persentase Kehadiran</span>
                <span class="text-5xl font-extrabold text-emerald-500 mt-2 block">{{ $rollCallStats['attendance_rate'] }}%</span>
                <span class="text-2xs text-slate-400 mt-1 block">Berdasarkan data sesi absen antara {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</span>
            </div>

            <div class="space-y-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                <div class="flex justify-between text-xs font-semibold text-slate-650 dark:text-slate-400">
                    <span>Hadir (Present):</span>
                    <span class="text-slate-800 dark:text-white">{{ $rollCallStats['present'] }} ({{ $rollCallStats['total_records'] > 0 ? round(($rollCallStats['present'] / $rollCallStats['total_records']) * 100, 1) : 0 }}%)</span>
                </div>
                <div class="flex justify-between text-xs font-semibold text-slate-650 dark:text-slate-400">
                    <span>Terlambat (Late):</span>
                    <span class="text-slate-800 dark:text-white">{{ $rollCallStats['late'] }} ({{ $rollCallStats['total_records'] > 0 ? round(($rollCallStats['late'] / $rollCallStats['total_records']) * 100, 1) : 0 }}%)</span>
                </div>
                <div class="flex justify-between text-xs font-semibold text-slate-650 dark:text-slate-400">
                    <span>Izin (Permission):</span>
                    <span class="text-slate-800 dark:text-white">{{ $rollCallStats['permission'] }} ({{ $rollCallStats['total_records'] > 0 ? round(($rollCallStats['permission'] / $rollCallStats['total_records']) * 100, 1) : 0 }}%)</span>
                </div>
                <div class="flex justify-between text-xs font-semibold text-slate-650 dark:text-slate-400">
                    <span>Sakit (Sick):</span>
                    <span class="text-slate-800 dark:text-white">{{ $rollCallStats['sick'] }} ({{ $rollCallStats['total_records'] > 0 ? round(($rollCallStats['sick'] / $rollCallStats['total_records']) * 100, 1) : 0 }}%)</span>
                </div>
                <div class="flex justify-between text-xs font-semibold text-slate-650 dark:text-slate-400">
                    <span>Alpha (Absent):</span>
                    <span class="text-rose-500 font-bold">{{ $rollCallStats['absent'] }} ({{ $rollCallStats['total_records'] > 0 ? round(($rollCallStats['absent'] / $rollCallStats['total_records']) * 100, 1) : 0 }}%)</span>
                </div>
            </div>
        </div>

        <!-- Leaderboard Pelanggaran (Top Violators) -->
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 space-y-4">
            <h2 class="text-lg font-bold text-slate-800 dark:text-white border-b border-slate-100 pb-2 dark:border-slate-800">Catatan Pelanggaran Terbanyak</h2>
            
            <div class="divide-y divide-slate-50 dark:divide-slate-800">
                @forelse($disciplineStats['top_violators'] as $index => $violator)
                    <div class="py-3 flex justify-between items-center text-xs">
                        <div class="flex items-center gap-2">
                            <span class="w-5 h-5 rounded-full bg-rose-50 text-rose-600 dark:bg-rose-950/30 flex items-center justify-center font-bold text-2xs">{{ $index + 1 }}</span>
                            <div>
                                <span class="font-bold text-slate-850 dark:text-slate-200 block">{{ $violator->student->full_name }}</span>
                                <span class="text-2xs text-slate-400">{{ $violator->violations_count }} Kali Pelanggaran</span>
                            </div>
                        </div>
                        <span class="font-bold text-rose-550">{{ $violator->total_points }} Poin</span>
                    </div>
                @empty
                    <p class="text-xs text-slate-500 py-8 text-center">Tidak ada catatan pelanggaran.</p>
                @endforelse
            </div>
        </div>

        <!-- Leaderboard Prestasi (Top Achievers) -->
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 space-y-4">
            <h2 class="text-lg font-bold text-slate-800 dark:text-white border-b border-slate-100 pb-2 dark:border-slate-800">Sikap Terbaik & Prestasi</h2>
            
            <div class="divide-y divide-slate-50 dark:divide-slate-800">
                @forelse($disciplineStats['top_achievers'] as $index => $achiever)
                    <div class="py-3 flex justify-between items-center text-xs">
                        <div class="flex items-center gap-2">
                            <span class="w-5 h-5 rounded-full bg-emerald-50 text-emerald-600 dark:bg-emerald-950/30 flex items-center justify-center font-bold text-2xs">{{ $index + 1 }}</span>
                            <div>
                                <span class="font-bold text-slate-850 dark:text-slate-200 block">{{ $achiever->student->full_name }}</span>
                                <span class="text-2xs text-slate-400">{{ $achiever->achievements_count }} Kali Prestasi</span>
                            </div>
                        </div>
                        <span class="font-bold text-emerald-550">+{{ $achiever->total_points }} Poin</span>
                    </div>
                @empty
                    <p class="text-xs text-slate-500 py-8 text-center">Tidak ada catatan prestasi.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
