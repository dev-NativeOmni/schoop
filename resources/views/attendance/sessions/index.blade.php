@extends('layouts.app')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between border-b border-slate-200/80 pb-5 dark:border-slate-800 gap-4">
        <div class="flex items-center space-x-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 dark:bg-indigo-950/30 dark:text-indigo-400">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white">Session Presensi</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400">Kelola dan atur jadwal session presensi harian santri.</p>
            </div>
        </div>

        <a href="{{ route('attendance.sessions.create') }}"
           class="inline-flex items-center justify-center rounded-full bg-indigo-600 px-5 py-2.5 text-xs font-bold text-white hover:bg-indigo-700 shadow-sm shadow-indigo-500/10 hover:shadow-indigo-500/20 active:scale-[0.98] transition-all focus:outline-none focus:ring-4 focus:ring-indigo-500/20 gap-1.5">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v8m0 0v8m0-8h8m-8 0H4" />
            </svg>
            Buat Session Baru
        </a>
    </div>

    {{-- Messages --}}
    @if(session('success'))
        <div class="rounded-xl border border-emerald-100 bg-emerald-50/50 p-4 text-sm font-semibold text-emerald-700 dark:border-emerald-900/30 dark:bg-emerald-950/20 dark:text-emerald-450">
            {{ session('success') }}
        </div>
    @endif

    {{-- Sessions Table Card --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left text-sm text-slate-500 dark:text-slate-400">
                <thead class="bg-slate-50/75 text-xs font-bold uppercase tracking-wider text-slate-500 dark:bg-slate-850/50 dark:text-slate-400 border-b border-slate-200/85 dark:border-slate-800">
                    <tr>
                        <th scope="col" class="px-6 py-4">Nama Session</th>
                        <th scope="col" class="px-6 py-4">Tanggal</th>
                        <th scope="col" class="px-6 py-4">Kelas</th>
                        <th scope="col" class="px-6 py-4">Status</th>
                        <th scope="col" class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200/70 dark:divide-slate-800/80">
                    @forelse($sessions as $session)
                        <tr class="hover:bg-slate-50/40 dark:hover:bg-slate-800/20 transition-colors">
                            <td class="px-6 py-4 font-bold text-slate-850 dark:text-slate-200">
                                {{ $session->name }}
                            </td>
                            <td class="px-6 py-4 font-medium text-slate-600 dark:text-slate-350">
                                {{ $session->attendance_date?->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 font-medium text-slate-600 dark:text-slate-350">
                                {{ $session->classRoom?->name ?? 'Semua Kelas' }}
                            </td>
                            <td class="px-6 py-4">
                                @if(strtolower($session->status) === 'active')
                                    <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-bold text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-400">
                                        Active
                                    </span>
                                @elseif(strtolower($session->status) === 'closed')
                                    <span class="inline-flex items-center rounded-full bg-rose-50 px-2.5 py-0.5 text-xs font-bold text-rose-700 dark:bg-rose-950/30 dark:text-rose-400">
                                        Closed
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-bold text-slate-700 dark:bg-slate-800 dark:text-slate-400">
                                        Draft
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <a href="{{ route('attendance.sessions.show', $session) }}"
                                   class="inline-flex items-center justify-center rounded-full bg-indigo-50 px-3.5 py-1.5 text-xs font-bold text-indigo-600 hover:bg-indigo-100 transition-all dark:bg-indigo-950/30 dark:text-indigo-400 dark:hover:bg-indigo-900/30">
                                    Detail
                                </a>
                                <a href="{{ route('attendance.sessions.edit', $session) }}"
                                   class="inline-flex items-center justify-center rounded-full bg-amber-50 px-3.5 py-1.5 text-xs font-bold text-amber-700 hover:bg-amber-100 transition-all dark:bg-amber-950/20 dark:text-amber-400 dark:hover:bg-amber-900/30">
                                    Edit
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center font-medium text-slate-400 dark:text-slate-500">
                                Belum ada session presensi.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    @if($sessions->hasPages())
        <div class="mt-4">
            {{ $sessions->links() }}
        </div>
    @endif

</div>
@endsection
