@extends('layouts.app')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between border-b border-slate-200/80 pb-5 dark:border-slate-800 gap-4">
        <div class="flex items-center space-x-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 dark:bg-indigo-950/30 dark:text-indigo-400">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white">Presensi Saya</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400">Lihat riwayat kehadiran pribadi Anda secara terperinci.</p>
            </div>
        </div>
    </div>

    @if(! $student)
        <div class="rounded-2xl border border-slate-200 bg-white p-8 text-center text-slate-500 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-400">
            <svg class="mx-auto h-12 w-12 text-slate-400 dark:text-slate-650 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <p class="text-base font-bold">Profil santri belum terhubung.</p>
            <p class="text-sm mt-1 text-slate-455">Hubungi administrator atau wali kelas Anda untuk mengaitkan akun Anda.</p>
        </div>
    @else
        {{-- Filters Form --}}
        <form method="GET" action="{{ route('portal.student.attendance') }}" class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900 grid grid-cols-1 gap-5 md:grid-cols-3">
            <div class="space-y-1.5">
                <label class="block text-xs font-bold text-slate-500 dark:text-slate-455 uppercase tracking-wider">Tanggal Mulai</label>
                <input type="date" name="start_date" value="{{ $snapshot['period']['start_date'] ?? now()->startOfMonth()->toDateString() }}" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-850 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500">
            </div>

            <div class="space-y-1.5">
                <label class="block text-xs font-bold text-slate-500 dark:text-slate-455 uppercase tracking-wider">Tanggal Selesai</label>
                <input type="date" name="end_date" value="{{ $snapshot['period']['end_date'] ?? now()->toDateString() }}" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-850 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500">
            </div>

            <div class="flex items-end">
                <button class="w-full inline-flex items-center justify-center rounded-full bg-indigo-600 px-5 py-2.5 text-xs font-bold text-white hover:bg-indigo-700 shadow-sm shadow-indigo-500/10 hover:shadow-indigo-500/20 active:scale-[0.98] transition-all focus:outline-none focus:ring-4 focus:ring-indigo-500/20">
                    Filter Riwayat
                </button>
            </div>
        </form>

        @if($snapshot)
            {{-- Summary Cards --}}
            <div class="grid grid-cols-2 gap-4 md:grid-cols-5">
                <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900 transition-all hover:translate-y-[-2px]">
                    <div class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Hadir</div>
                    <div class="mt-1 text-2xl font-extrabold text-emerald-600 dark:text-emerald-450">{{ $snapshot['summary']['present'] }}</div>
                </div>
                <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900 transition-all hover:translate-y-[-2px]">
                    <div class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Telat</div>
                    <div class="mt-1 text-2xl font-extrabold text-amber-600 dark:text-amber-450">{{ $snapshot['summary']['late'] }}</div>
                </div>
                <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900 transition-all hover:translate-y-[-2px]">
                    <div class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Sakit</div>
                    <div class="mt-1 text-2xl font-extrabold text-blue-600 dark:text-blue-450">{{ $snapshot['summary']['sick'] }}</div>
                </div>
                <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900 transition-all hover:translate-y-[-2px]">
                    <div class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Izin</div>
                    <div class="mt-1 text-2xl font-extrabold text-purple-600 dark:text-purple-450">{{ $snapshot['summary']['permission'] }}</div>
                </div>
                <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900 transition-all hover:translate-y-[-2px]">
                    <div class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Alpa</div>
                    <div class="mt-1 text-2xl font-extrabold text-rose-600 dark:text-rose-450">{{ $snapshot['summary']['absent'] }}</div>
                </div>
            </div>

            {{-- Table Logs --}}
            <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse text-left text-sm text-slate-500 dark:text-slate-400">
                        <thead class="bg-slate-50/75 text-xs font-bold uppercase tracking-wider text-slate-500 dark:bg-slate-850/50 dark:text-slate-400 border-b border-slate-200/85 dark:border-slate-800">
                            <tr>
                                <th scope="col" class="px-6 py-4">Tanggal</th>
                                <th scope="col" class="px-6 py-4">Session</th>
                                <th scope="col" class="px-6 py-4">Status</th>
                                <th scope="col" class="px-6 py-4">Masuk</th>
                                <th scope="col" class="px-6 py-4">Pulang</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200/70 dark:divide-slate-800/80">
                            @forelse($snapshot['records'] as $record)
                                @php
                                    $statusVal = strtolower($record->status);
                                    $badgeClass = 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-400';
                                    if ($statusVal === 'present') {
                                        $badgeClass = 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-450';
                                    } elseif ($statusVal === 'late') {
                                        $badgeClass = 'bg-amber-50 text-amber-700 dark:bg-amber-950/30 dark:text-amber-450';
                                    } elseif ($statusVal === 'sick') {
                                        $badgeClass = 'bg-blue-50 text-blue-700 dark:bg-blue-950/30 dark:text-blue-450';
                                    } elseif ($statusVal === 'permission') {
                                        $badgeClass = 'bg-purple-50 text-purple-700 dark:bg-purple-950/30 dark:text-purple-450';
                                    } elseif ($statusVal === 'absent') {
                                        $badgeClass = 'bg-rose-50 text-rose-700 dark:bg-rose-950/30 dark:text-rose-455';
                                    }
                                @endphp
                                <tr class="hover:bg-slate-50/40 dark:hover:bg-slate-800/20 transition-colors">
                                    <td class="px-6 py-4 font-bold text-slate-850 dark:text-slate-200">
                                        {{ $record->attendance_date?->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 font-medium text-slate-600 dark:text-slate-350">
                                        {{ $record->session?->name ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-bold capitalize {{ $badgeClass }}">
                                            {{ $record->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 font-semibold text-slate-700 dark:text-slate-300">
                                        {{ $record->check_in_at?->format('H:i') ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 font-semibold text-slate-700 dark:text-slate-300">
                                        {{ $record->check_out_at?->format('H:i') ?? '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center font-medium text-slate-400 dark:text-slate-500">
                                        Belum ada data presensi.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    @endif

</div>
@endsection
