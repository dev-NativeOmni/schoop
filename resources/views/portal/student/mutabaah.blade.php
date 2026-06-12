@extends('layouts.app')

@section('title', 'Mutabaah Saya')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between border-b border-slate-200/80 pb-5 dark:border-slate-800">
        <div>
            <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 dark:bg-indigo-950/30 dark:text-indigo-400">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <span>Mutabaah Saya</span>
            </h1>
            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Pantau catatan ibadah harian dan pembentukan karakter Anda.</p>
        </div>
    </div>

    @if(isset($student))
        {{-- Date Filter --}}
        <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <form method="GET" action="{{ route('portal.student.mutabaah') }}" class="grid gap-4 sm:grid-cols-3 items-end">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">Tanggal Mulai</label>
                    <input type="date" name="start_date" 
                           value="{{ $period['start_date'] ?? now()->startOfWeek()->toDateString() }}" 
                           class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-sm text-slate-950 placeholder-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 dark:border-slate-800 dark:bg-slate-950 dark:text-white dark:focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">Tanggal Selesai</label>
                    <input type="date" name="end_date" 
                           value="{{ $period['end_date'] ?? now()->endOfWeek()->toDateString() }}" 
                           class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-sm text-slate-950 placeholder-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 dark:border-slate-800 dark:bg-slate-950 dark:text-white dark:focus:border-indigo-500">
                </div>
                <div>
                    <button type="submit" 
                            class="flex w-full items-center justify-center space-x-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700 shadow-sm transition-all focus:outline-none focus:ring-4 focus:ring-indigo-500/20 active:scale-[0.98]">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <span>Terapkan Periode</span>
                    </button>
                </div>
            </form>
        </div>

        {{-- Summary Cards --}}
        <div class="grid gap-6 sm:grid-cols-3">
            <!-- Total Records -->
            <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900 transition hover:shadow-md">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Total Kegiatan</p>
                <p class="mt-2 text-2xl font-extrabold text-slate-900 dark:text-white">{{ $summary['total_records'] }}</p>
                <p class="mt-1 text-xs text-slate-400">Pencatatan aktivitas Anda</p>
            </div>

            <!-- Selesai -->
            <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900 transition hover:shadow-md">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Terlaksana</p>
                <p class="mt-2 text-2xl font-extrabold text-emerald-600 dark:text-emerald-450">{{ $summary['done_records'] }}</p>
                <p class="mt-1 text-xs text-slate-400">Kegiatan sukses terlaksana</p>
            </div>

            <!-- Completion Rate -->
            <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900 transition hover:shadow-md">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Tingkat Capaian</p>
                <p class="mt-2 text-2xl font-extrabold text-indigo-600 dark:text-indigo-400">{{ $summary['completion_rate'] }}%</p>
                <div class="mt-2.5 w-full bg-slate-100 rounded-full h-1.5 dark:bg-slate-800">
                    <div class="bg-indigo-600 h-1.5 rounded-full dark:bg-indigo-500" style="width: {{ $summary['completion_rate'] }}%"></div>
                </div>
            </div>
        </div>

        {{-- Records Table --}}
        <div class="rounded-2xl border border-slate-200/80 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900 overflow-hidden">
            <div class="bg-slate-50/80 dark:bg-slate-850 px-6 py-4 border-b border-slate-200/80 dark:border-slate-800">
                <h3 class="text-base font-bold text-slate-800 dark:text-white flex items-center gap-2">
                    <svg class="h-5 w-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <span>Detail Aktivitas Saya</span>
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/40 dark:bg-slate-850/40 border-b border-slate-200/80 dark:border-slate-800">
                            <th class="px-6 py-3.5 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Tanggal</th>
                            <th class="px-6 py-3.5 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Aktivitas</th>
                            <th class="px-6 py-3.5 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 w-36">Status</th>
                            <th class="px-6 py-3.5 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 w-36">Nilai/Jumlah</th>
                            <th class="px-6 py-3.5 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Catatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($records as $record)
                            <tr class="hover:bg-slate-50/40 dark:hover:bg-slate-850/10 transition-all">
                                <td class="px-6 py-4 text-sm font-medium text-slate-700 dark:text-slate-350">
                                    {{ $record->record_date?->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm font-bold text-slate-800 dark:text-slate-250 block">{{ $record->activity?->name ?? '-' }}</span>
                                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">{{ $record->activity?->category?->name ?? 'Umum' }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($record->status === 'done')
                                        <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-bold text-emerald-700 border border-emerald-200 dark:bg-emerald-950/20 dark:text-emerald-400 dark:border-emerald-900/50">Selesai</span>
                                    @elseif($record->status === 'excused')
                                        <span class="inline-flex items-center rounded-full bg-amber-50 px-2.5 py-0.5 text-xs font-bold text-amber-700 border border-amber-200 dark:bg-amber-950/20 dark:text-amber-400 dark:border-amber-900/50">Izin/Uzur</span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-rose-50 px-2.5 py-0.5 text-xs font-bold text-rose-700 border border-rose-200 dark:bg-rose-950/20 dark:text-rose-450 dark:border-rose-900/50">Belum</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm font-semibold text-slate-700 dark:text-slate-300">
                                    @if($record->score !== null)
                                        {{ $record->score }} <span class="text-[10px] text-slate-400 font-medium">skor</span>
                                    @elseif($record->count_value !== null)
                                        {{ $record->count_value }} <span class="text-[10px] text-slate-400 font-medium">{{ $record->activity?->target_unit ?? 'kali' }}</span>
                                    @else
                                        <span class="text-slate-400 dark:text-slate-650">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-xs text-slate-600 dark:text-slate-400 italic">
                                    {{ $record->note ?? 'Tidak ada catatan.' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-sm text-slate-450 dark:text-slate-500">Belum ada mutabaah pada periode ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="rounded-2xl border border-dashed border-slate-300 p-12 text-center dark:border-slate-700">
            <svg class="mx-auto h-12 w-12 text-slate-400 dark:text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
            </svg>
            <h3 class="mt-4 text-sm font-bold text-slate-900 dark:text-white">Data santri belum terhubung</h3>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Hubungi admin sekolah untuk menghubungkan akun pengguna Anda ke profil santri.</p>
        </div>
    @endif

</div>
@endsection
