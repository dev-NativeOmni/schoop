@extends('layouts.app')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between border-b border-slate-200/80 pb-5 dark:border-slate-800 gap-4">
        <div class="flex items-center space-x-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 dark:bg-indigo-950/30 dark:text-indigo-400">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white">{{ $session->name }}</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400">
                    {{ $session->attendance_date?->format('d M Y') }} · <span class="capitalize font-bold">{{ $session->status }}</span>
                </p>
            </div>
        </div>

        <div class="flex items-center space-x-2">
            <a href="{{ route('attendance.sessions.index') }}"
               class="inline-flex items-center justify-center rounded-full border border-slate-250/70 bg-white px-5 py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-350 dark:hover:bg-slate-900 transition-all focus:outline-none active:scale-[0.98]">
                Kembali
            </a>
            <a href="{{ route('attendance.scanner.index') }}"
               class="inline-flex items-center justify-center rounded-full bg-indigo-600 px-5 py-2.5 text-xs font-bold text-white hover:bg-indigo-700 shadow-sm shadow-indigo-500/10 hover:shadow-indigo-500/20 active:scale-[0.98] transition-all focus:outline-none focus:ring-4 focus:ring-indigo-500/20 gap-1.5">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h3m-3-3H8m4 2a2 2 0 100-4 2 2 0 000 4z" />
                </svg>
                Buka Scanner QR
            </a>
        </div>
    </div>

    {{-- Session Details Box --}}
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Mulai Masuk</div>
            <div class="mt-1 text-lg font-extrabold text-slate-850 dark:text-slate-200">{{ $session->check_in_starts_at ? date('H:i', strtotime($session->check_in_starts_at)) : '-' }}</div>
        </div>
        <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Batas Terlambat</div>
            <div class="mt-1 text-lg font-extrabold text-amber-600 dark:text-amber-450">{{ $session->late_after_at ? date('H:i', strtotime($session->late_after_at)) : '-' }}</div>
        </div>
        <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Akhir Masuk</div>
            <div class="mt-1 text-lg font-extrabold text-slate-850 dark:text-slate-200">{{ $session->check_in_ends_at ? date('H:i', strtotime($session->check_in_ends_at)) : '-' }}</div>
        </div>
        <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Mulai Pulang</div>
            <div class="mt-1 text-lg font-extrabold text-slate-850 dark:text-slate-200">{{ $session->check_out_starts_at ? date('H:i', strtotime($session->check_out_starts_at)) : '-' }}</div>
        </div>
        <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Akhir Pulang</div>
            <div class="mt-1 text-lg font-extrabold text-slate-850 dark:text-slate-200">{{ $session->check_out_ends_at ? date('H:i', strtotime($session->check_out_ends_at)) : '-' }}</div>
        </div>
    </div>

    {{-- Session Log Records Table --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <div class="px-6 py-4 border-b border-slate-200/80 dark:border-slate-800 flex items-center justify-between">
            <h3 class="text-base font-extrabold text-slate-850 dark:text-white">Daftar Kehadiran Santri</h3>
            <span class="rounded-full bg-indigo-50 px-3 py-1 text-xs font-bold text-indigo-600 dark:bg-indigo-950/40 dark:text-indigo-400">
                {{ $session->records->count() }} Santri Tercatat
            </span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left text-sm text-slate-500 dark:text-slate-400">
                <thead class="bg-slate-50/75 text-xs font-bold uppercase tracking-wider text-slate-500 dark:bg-slate-850/50 dark:text-slate-400 border-b border-slate-200/85 dark:border-slate-800">
                    <tr>
                        <th scope="col" class="px-6 py-4">Santri</th>
                        <th scope="col" class="px-6 py-4">Status</th>
                        <th scope="col" class="px-6 py-4">Check In</th>
                        <th scope="col" class="px-6 py-4">Check Out</th>
                        <th scope="col" class="px-6 py-4">Source</th>
                        <th scope="col" class="px-6 py-4">Scanner</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200/70 dark:divide-slate-800/80">
                    @forelse($session->records as $record)
                        @php
                            $statusVal = strtolower($record->status);
                            $badgeClass = 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-400';
                            if ($statusVal === 'present') {
                                $badgeClass = 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-400';
                            } elseif ($statusVal === 'late') {
                                $badgeClass = 'bg-amber-50 text-amber-700 dark:bg-amber-950/30 dark:text-amber-400';
                            } elseif ($statusVal === 'sick') {
                                $badgeClass = 'bg-blue-50 text-blue-700 dark:bg-blue-950/30 dark:text-blue-400';
                            } elseif ($statusVal === 'permission') {
                                $badgeClass = 'bg-purple-50 text-purple-700 dark:bg-purple-950/30 dark:text-purple-400';
                            } elseif ($statusVal === 'absent') {
                                $badgeClass = 'bg-rose-50 text-rose-700 dark:bg-rose-950/30 dark:text-rose-450';
                            }
                        @endphp
                        <tr class="hover:bg-slate-50/40 dark:hover:bg-slate-800/20 transition-colors">
                            <td class="px-6 py-4 font-bold text-slate-850 dark:text-slate-200">
                                {{ $record->student?->full_name ?? $record->student?->nama_lengkap ?? $record->student?->name ?? '-' }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-bold capitalize {{ $badgeClass }}">
                                    {{ $record->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-semibold text-slate-700 dark:text-slate-300">
                                {{ $record->check_in_at?->format('H:i:s') ?? '-' }}
                            </td>
                            <td class="px-6 py-4 font-semibold text-slate-700 dark:text-slate-300">
                                {{ $record->check_out_at?->format('H:i:s') ?? '-' }}
                            </td>
                            <td class="px-6 py-4 capitalize font-semibold text-slate-500 dark:text-slate-400">
                                {{ $record->source }}
                            </td>
                            <td class="px-6 py-4 font-medium text-slate-500 dark:text-slate-400">
                                {{ $record->scanner?->name ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center font-medium text-slate-400 dark:text-slate-500">
                                Belum ada data presensi pada session ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
