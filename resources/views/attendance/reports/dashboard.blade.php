@extends('layouts.app')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between border-b border-slate-200/80 pb-5 dark:border-slate-800 gap-4">
        <div class="flex items-center space-x-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 dark:bg-indigo-950/30 dark:text-indigo-400">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
            <div>
                <h2 class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white">Laporan Presensi</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400">Ringkasan presensi harian santri.</p>
            </div>
        </div>
    </div>

    {{-- Filter Form --}}
    <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <h3 class="text-sm font-bold text-slate-700 dark:text-slate-350 mb-4 uppercase tracking-wider">Filter Laporan</h3>
        <form method="GET" action="{{ route('attendance.reports.dashboard') }}" class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-5 items-end">
            <div>
                <label class="mb-1.5 block text-xs font-bold text-slate-500 dark:text-slate-455 uppercase tracking-wider">Tanggal Mulai</label>
                <input type="date" name="start_date" value="{{ $filters['start_date'] ?? $report['period']['start_date'] }}" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500">
            </div>

            <div>
                <label class="mb-1.5 block text-xs font-bold text-slate-500 dark:text-slate-455 uppercase tracking-wider">Tanggal Selesai</label>
                <input type="date" name="end_date" value="{{ $filters['end_date'] ?? $report['period']['end_date'] }}" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500">
            </div>

            <div>
                <label class="mb-1.5 block text-xs font-bold text-slate-500 dark:text-slate-455 uppercase tracking-wider">Kelas</label>
                <select name="class_room_id" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500">
                    <option value="">Semua Kelas</option>
                    @foreach($classRooms as $classRoom)
                        <option value="{{ $classRoom->id }}" @selected(($filters['class_room_id'] ?? null) == $classRoom->id)>
                            {{ $classRoom->name ?? $classRoom->nama ?? 'Kelas #' . $classRoom->id }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="mb-1.5 block text-xs font-bold text-slate-500 dark:text-slate-455 uppercase tracking-wider">Santri</label>
                <select name="student_id" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500">
                    <option value="">Semua Santri</option>
                    @foreach($students as $student)
                        <option value="{{ $student->id }}" @selected(($filters['student_id'] ?? null) == $student->id)>
                            {{ $student->full_name ?? $student->nama_lengkap ?? $student->name ?? 'Santri #' . $student->id }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <button type="submit" class="w-full inline-flex items-center justify-center rounded-full bg-indigo-600 px-4 py-2 text-xs font-bold text-white hover:bg-indigo-700 shadow-sm transition-all focus:outline-none focus:ring-4 focus:ring-indigo-500/20 active:scale-[0.98]">
                    Filter Laporan
                </button>
            </div>
        </form>
    </div>

    {{-- Stats Cards Grid --}}
    <div class="grid grid-cols-2 gap-4 md:grid-cols-3 lg:grid-cols-6">
        <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="text-[10px] font-bold text-slate-455 dark:text-slate-500 uppercase tracking-wider">Total</div>
            <div class="text-2xl font-black text-slate-800 dark:text-slate-200 mt-1">{{ $report['summary']['total_records'] }}</div>
        </div>

        <div class="rounded-2xl border border-emerald-100 bg-emerald-50/20 p-4 shadow-sm dark:border-emerald-900/30 dark:bg-emerald-950/10">
            <div class="text-[10px] font-bold text-emerald-600 dark:text-emerald-450 uppercase tracking-wider">Hadir</div>
            <div class="text-2xl font-black text-emerald-700 dark:text-emerald-400 mt-1">{{ $report['summary']['present'] }}</div>
        </div>

        <div class="rounded-2xl border border-amber-100 bg-amber-50/20 p-4 shadow-sm dark:border-amber-900/30 dark:bg-amber-950/10">
            <div class="text-[10px] font-bold text-amber-600 dark:text-amber-450 uppercase tracking-wider">Terlambat</div>
            <div class="text-2xl font-black text-amber-700 dark:text-amber-400 mt-1">{{ $report['summary']['late'] }}</div>
        </div>

        <div class="rounded-2xl border border-blue-100 bg-blue-50/20 p-4 shadow-sm dark:border-blue-900/30 dark:bg-blue-950/10">
            <div class="text-[10px] font-bold text-blue-600 dark:text-blue-455 uppercase tracking-wider">Sakit</div>
            <div class="text-2xl font-black text-blue-700 dark:text-blue-400 mt-1">{{ $report['summary']['sick'] }}</div>
        </div>

        <div class="rounded-2xl border border-purple-100 bg-purple-50/20 p-4 shadow-sm dark:border-purple-900/30 dark:bg-purple-950/10">
            <div class="text-[10px] font-bold text-purple-600 dark:text-purple-450 uppercase tracking-wider">Izin</div>
            <div class="text-2xl font-black text-purple-700 dark:text-purple-400 mt-1">{{ $report['summary']['permission'] }}</div>
        </div>

        <div class="rounded-2xl border border-rose-100 bg-rose-50/20 p-4 shadow-sm dark:border-rose-900/30 dark:bg-rose-950/10">
            <div class="text-[10px] font-bold text-rose-600 dark:text-rose-450 uppercase tracking-wider">Alpa</div>
            <div class="text-2xl font-black text-rose-700 dark:text-rose-400 mt-1">{{ $report['summary']['absent'] }}</div>
        </div>
    </div>

    {{-- Data Table --}}
    <div class="rounded-2xl border border-slate-200/80 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50/40 dark:bg-slate-850/40 border-b border-slate-200/80 dark:border-slate-800">
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Santri</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 text-right">Total</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 text-right">Hadir</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 text-right">Telat</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 text-right">Sakit</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 text-right">Izin</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 text-right">Alpa</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($report['by_student'] as $row)
                        <tr class="hover:bg-slate-50/40 dark:hover:bg-slate-850/10 transition-all">
                            <td class="px-6 py-4 font-bold text-slate-800 dark:text-slate-200">
                                {{ $row['student']?->full_name ?? $row['student']?->nama_lengkap ?? $row['student']?->name ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-right text-slate-700 dark:text-slate-350 font-semibold">{{ $row['total'] }}</td>
                            <td class="px-6 py-4 text-right text-emerald-600 dark:text-emerald-400 font-bold">{{ $row['present'] }}</td>
                            <td class="px-6 py-4 text-right text-amber-600 dark:text-amber-400 font-bold">{{ $row['late'] }}</td>
                            <td class="px-6 py-4 text-right text-blue-600 dark:text-blue-400 font-semibold">{{ $row['sick'] }}</td>
                            <td class="px-6 py-4 text-right text-purple-600 dark:text-purple-400 font-semibold">{{ $row['permission'] }}</td>
                            <td class="px-6 py-4 text-right text-rose-600 dark:text-rose-400 font-bold">{{ $row['absent'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-sm text-slate-450 dark:text-slate-500">Belum ada data presensi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
