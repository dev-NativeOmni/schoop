@extends('layouts.app')

@section('content')
<div class="space-y-6">

    {{-- Back Link & Header --}}
    <div class="space-y-3">
        <a href="{{ route('reports.tahfizh.monthly.index', ['month' => $month]) }}" class="inline-flex items-center space-x-1.5 text-xs font-bold text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200 transition-all">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
            <span>Kembali ke Laporan Bulanan</span>
        </a>
        
        <div class="flex flex-col md:flex-row md:items-center md:justify-between border-b border-slate-200/80 pb-5 dark:border-slate-800 gap-4">
            <div class="flex items-center space-x-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 dark:bg-indigo-950/30 dark:text-indigo-400">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white">Detail Laporan Bulanan</h2>
                    <p class="text-sm text-slate-500 dark:text-slate-400">
                        {{ $student->full_name }} — {{ $periodStart->format('d/m/Y') }} sampai {{ $periodEnd->format('d/m/Y') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Student and Class Summary -->
        <div class="lg:col-span-1">
            <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 space-y-5">
                <h3 class="text-sm font-bold text-slate-700 dark:text-slate-350 uppercase tracking-wider border-b border-slate-100 dark:border-slate-800/60 pb-3">Informasi Akademik</h3>
                
                <div class="space-y-4">
                    <div>
                        <span class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Nama Lengkap</span>
                        <span class="text-sm font-extrabold text-slate-800 dark:text-slate-200 block mt-0.5">{{ $student->full_name }}</span>
                    </div>

                    <div>
                        <span class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Kelas</span>
                        <span class="text-sm font-extrabold text-slate-800 dark:text-slate-200 block mt-0.5">{{ $student->classRoom?->name ?? '-' }}</span>
                    </div>

                    <div>
                        <span class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">No. Induk / NISN</span>
                        <span class="text-sm font-bold text-slate-700 dark:text-slate-300 block mt-0.5">
                            {{ $student->student_number ?? '-' }} / {{ $student->nisn ?? '-' }}
                        </span>
                    </div>

                    <div>
                        <span class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Sekolah</span>
                        <span class="text-sm font-semibold text-slate-600 dark:text-slate-400 block mt-0.5">{{ $student->school->name }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Records Table -->
        <div class="lg:col-span-2">
            <div class="rounded-2xl border border-slate-200/80 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900 overflow-hidden">
                <div class="p-5 border-b border-slate-100 dark:border-slate-800/60">
                    <h3 class="text-sm font-bold text-slate-700 dark:text-slate-350 uppercase tracking-wider">Riwayat Setoran Periode Ini</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="bg-slate-50/40 dark:bg-slate-850/40 border-b border-slate-200/80 dark:border-slate-800">
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 w-28">Tanggal</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 w-32">Guru</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Rentang Ayat</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 w-24">Total</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 text-center w-24">Status</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Catatan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @forelse ($records as $record)
                                <tr class="hover:bg-slate-50/40 dark:hover:bg-slate-850/10 transition-all">
                                    <td class="px-6 py-4 font-bold text-slate-700 dark:text-slate-300">
                                        {{ $record->record_date?->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-slate-800 dark:text-slate-200 font-semibold">
                                        {{ $record->teacher?->name ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-extrabold text-slate-900 dark:text-white">
                                            {{ $record->startSurah?->name_latin ?? '-' }} <span class="font-normal text-slate-500">(Ayat {{ $record->start_ayah }})</span>
                                        </div>
                                        <div class="text-[10px] text-slate-400 dark:text-slate-500 mt-1">
                                            Hlm {{ $record->start_page }}:{{ $record->start_line }} &rarr; Hlm {{ $record->end_page }}:{{ $record->end_line }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-indigo-600 dark:text-indigo-400 font-black">
                                        +{{ $record->total_lines }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if ($record->status === \App\Models\HafalanRecord::STATUS_LUNAS)
                                            <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-0.5 text-[10px] font-bold text-emerald-700 border border-emerald-150 dark:bg-emerald-950/20 dark:text-emerald-400 dark:border-emerald-900/50">Lunas</span>
                                        @elseif ($record->status === \App\Models\HafalanRecord::STATUS_KURANG)
                                            <span class="inline-flex items-center rounded-full bg-rose-50 px-2.5 py-0.5 text-[10px] font-bold text-rose-700 border border-rose-150 dark:bg-rose-950/20 dark:text-rose-400 dark:border-rose-900/50">Kurang</span>
                                        @elseif ($record->status === \App\Models\HafalanRecord::STATUS_LEBIH)
                                            <span class="inline-flex items-center rounded-full bg-blue-50 px-2.5 py-0.5 text-[10px] font-bold text-blue-700 border border-blue-150 dark:bg-blue-950/20 dark:text-blue-450 dark:border-blue-900/50">Lebih</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-slate-600 dark:text-slate-400 italic text-xs">
                                        "{{ $record->notes ?? '-' }}"
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-sm text-slate-450 dark:text-slate-500">Belum ada setoran pada periode ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
