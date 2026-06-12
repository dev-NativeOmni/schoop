@extends('layouts.app')

@section('content')
<div class="space-y-6">

    {{-- Back Link & Header --}}
    <div class="space-y-3">
        <a href="{{ route('tahfizh.debts.index') }}" class="inline-flex items-center space-x-1.5 text-xs font-bold text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200 transition-all">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
            <span>Kembali ke Daftar Hutang</span>
        </a>
        
        <div class="flex flex-col md:flex-row md:items-center md:justify-between border-b border-slate-200/80 pb-5 dark:border-slate-800 gap-4">
            <div class="flex items-center space-x-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 dark:bg-indigo-950/30 dark:text-indigo-400">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white">Detail Hutang Hafalan</h2>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Rincian perhitungan capaian dan hutang hafalan santri.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Main Stats & Calculations -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Summary Card -->
            <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between border-b border-slate-100 dark:border-slate-800/60 pb-6 gap-4">
                    <div>
                        <span class="text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest block mb-1">Periode Perhitungan</span>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
                            <span>{{ ucfirst($debt->period_type) }}</span>
                            <span class="inline-flex items-center rounded bg-indigo-50 px-1.5 py-0.5 text-[10px] font-semibold text-indigo-600 dark:bg-indigo-950/50 dark:text-indigo-400">
                                {{ $debt->period_start->format('d M Y') }} - {{ $debt->period_end->format('d M Y') }}
                            </span>
                        </h3>
                    </div>
                    <div>
                        @if ($debt->status === \App\Models\TahfizhDebt::STATUS_NO_TARGET)
                            <span class="inline-flex items-center rounded-full bg-slate-50 px-3 py-1 text-xs font-bold text-slate-500 border border-slate-150 dark:bg-slate-800/40 dark:text-slate-450 dark:border-slate-750">
                                Belum Ada Target
                            </span>
                        @elseif ($debt->status === \App\Models\TahfizhDebt::STATUS_MET)
                            <span class="inline-flex items-center rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700 border border-emerald-150 dark:bg-emerald-950/20 dark:text-emerald-400 dark:border-emerald-900/50">
                                Tercapai
                            </span>
                        @elseif ($debt->status === \App\Models\TahfizhDebt::STATUS_BEHIND)
                            <span class="inline-flex items-center rounded-full bg-rose-50 px-3 py-1 text-xs font-bold text-rose-700 border border-rose-150 dark:bg-rose-950/20 dark:text-rose-400 dark:border-rose-900/50">
                                Kurang
                            </span>
                        @elseif ($debt->status === \App\Models\TahfizhDebt::STATUS_AHEAD)
                            <span class="inline-flex items-center rounded-full bg-blue-50 px-3 py-1 text-xs font-bold text-blue-700 border border-blue-150 dark:bg-blue-950/20 dark:text-blue-450 dark:border-blue-900/50">
                                Lebih
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Metrics Grid --}}
                <div class="grid grid-cols-2 gap-4 sm:grid-cols-4 py-6 border-b border-slate-100 dark:border-slate-800/60">
                    <div class="p-4 rounded-2xl bg-slate-50/60 dark:bg-slate-850/20 border border-slate-100 dark:border-slate-800/50 text-center">
                        <span class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Target</span>
                        <span class="block text-lg font-black text-slate-800 dark:text-slate-200 mt-1">{{ $debt->target_lines }} baris</span>
                    </div>

                    <div class="p-4 rounded-2xl bg-emerald-50/40 dark:bg-emerald-950/10 border border-emerald-100/40 dark:border-emerald-900/30 text-center">
                        <span class="block text-[10px] font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider">Capaian</span>
                        <span class="block text-lg font-black text-emerald-700 dark:text-emerald-400 mt-1">+{{ $debt->actual_lines }} baris</span>
                    </div>

                    <div class="p-4 rounded-2xl bg-rose-50/40 dark:bg-rose-950/10 border border-rose-100/40 dark:border-rose-900/30 text-center">
                        <span class="block text-[10px] font-bold text-rose-600 dark:text-rose-455 uppercase tracking-wider">Hutang Baru</span>
                        <span class="block text-lg font-black text-rose-700 dark:text-rose-450 mt-1">{{ $debt->debt_lines }} baris</span>
                    </div>

                    <div class="p-4 rounded-2xl bg-blue-50/40 dark:bg-blue-950/10 border border-blue-100/40 dark:border-blue-900/30 text-center">
                        <span class="block text-[10px] font-bold text-blue-600 dark:text-blue-450 uppercase tracking-wider">Kelebihan</span>
                        <span class="block text-lg font-black text-blue-700 dark:text-blue-400 mt-1">{{ $debt->surplus_lines }} baris</span>
                    </div>
                </div>

                {{-- Accumulated Debt Box --}}
                <div class="mt-6 p-5 rounded-2xl bg-slate-900 dark:bg-slate-950 text-white flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border border-slate-800 shadow-lg relative overflow-hidden">
                    <div class="absolute right-0 bottom-0 opacity-10 pointer-events-none transform translate-y-1/4 translate-x-1/4">
                        <svg class="h-32 w-32 text-indigo-400" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="relative z-10">
                        <span class="block text-[10px] font-extrabold uppercase tracking-widest text-slate-400">Total Akumulasi Hutang</span>
                        <p class="text-xs text-slate-400 mt-1 max-w-md">Mengakumulasikan hutang periode sebelumnya dikurangi kelebihan hari ini.</p>
                    </div>
                    <div class="text-right relative z-10 w-full sm:w-auto">
                        @if ($debt->cumulative_debt_lines > 0)
                            <span class="inline-block px-4 py-2 rounded-xl bg-rose-500/20 text-rose-300 border border-rose-500/30 font-black text-xl">
                                {{ $debt->cumulative_debt_lines }} baris
                            </span>
                        @else
                            <span class="inline-block px-4 py-2 rounded-xl bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 font-black text-xl">
                                Lunas / 0
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Context Info -->
            <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h4 class="text-base font-bold text-slate-800 dark:text-slate-250 mb-5 flex items-center gap-2">
                    <svg class="h-5 w-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <span>Informasi Akademik & Target</span>
                </h4>
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div class="space-y-1.5 p-4 rounded-xl bg-slate-50/50 dark:bg-slate-850/10 border border-slate-100 dark:border-slate-800/40">
                        <span class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Santri</span>
                        <span class="text-sm font-extrabold text-slate-800 dark:text-slate-200 block">{{ $debt->student->full_name }}</span>
                        <span class="text-xs text-slate-450 dark:text-slate-500 block">No. Induk: {{ $debt->student->student_number ?? '-' }}</span>
                    </div>

                    <div class="space-y-1.5 p-4 rounded-xl bg-slate-50/50 dark:bg-slate-850/10 border border-slate-100 dark:border-slate-800/40">
                        <span class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Kelas / Sekolah</span>
                        <span class="text-sm font-extrabold text-slate-800 dark:text-slate-200 block">{{ $debt->classRoom?->name ?? '-' }}</span>
                        <span class="text-xs text-slate-450 dark:text-slate-500 block">{{ $debt->school->name }}</span>
                    </div>

                    <div class="space-y-1.5 p-4 rounded-xl bg-slate-50/50 dark:bg-slate-850/10 border border-slate-100 dark:border-slate-800/40">
                        <span class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Target Aktif Terpilih</span>
                        @if ($debt->tahfizhTarget)
                            <a href="{{ route('tahfizh.targets.show', $debt->tahfizhTarget) }}" class="text-sm font-extrabold text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300 inline-flex items-center gap-1 mt-1 transition-all">
                                <span>{{ $debt->tahfizhTarget->name }}</span>
                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                            </a>
                            <span class="text-xs text-slate-450 dark:text-slate-500 block">
                                Target: {{ $debt->tahfizhTarget->daily_target_lines }}/{{ $debt->tahfizhTarget->weekly_target_lines }}/{{ $debt->tahfizhTarget->monthly_target_lines }} baris (Harian/Mingguan/Bulanan)
                            </span>
                        @else
                            <span class="text-sm text-slate-400 dark:text-slate-500 block font-medium">-</span>
                            <span class="text-xs text-slate-450 dark:text-slate-500 block">Tidak ada target spesifik aktif, menggunakan fallback 0.</span>
                        @endif
                    </div>

                    <div class="space-y-1.5 p-4 rounded-xl bg-slate-50/50 dark:bg-slate-850/10 border border-slate-100 dark:border-slate-800/40">
                        <span class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Program Santri</span>
                        <span class="text-sm font-extrabold text-slate-800 dark:text-slate-200 block">{{ $debt->student->program_type ? ucfirst($debt->student->program_type) : 'Reguler' }}</span>
                        <span class="text-xs text-slate-450 dark:text-slate-500 block">Jenis pembinaan tahfizh santri</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Audit & Logs Column -->
        <div class="space-y-6">
            <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h4 class="text-base font-bold text-slate-800 dark:text-slate-250 mb-5 flex items-center gap-2">
                    <svg class="h-5 w-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Informasi Perhitungan</span>
                </h4>
                <div class="space-y-4">
                    <div class="pb-3 border-b border-slate-100 dark:border-slate-800/60">
                        <span class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Dihitung Oleh</span>
                        <span class="text-sm font-bold text-slate-800 dark:text-slate-200">{{ $debt->calculator?->name ?? 'Sistem / Otomatis' }}</span>
                    </div>

                    <div class="pb-3 border-b border-slate-100 dark:border-slate-800/60">
                        <span class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Tanggal Perhitungan</span>
                        <span class="text-sm font-bold text-slate-800 dark:text-slate-200">{{ $debt->calculation_date->format('d F Y') }}</span>
                    </div>

                    <div class="pb-3 border-b border-slate-100 dark:border-slate-800/60">
                        <span class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Terakhir Diperbarui</span>
                        <span class="text-sm font-bold text-slate-800 dark:text-slate-200">{{ $debt->updated_at->format('d F Y H:i') }}</span>
                    </div>

                    <div>
                        <span class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Catatan Kalkulator</span>
                        <div class="mt-2 p-3.5 rounded-xl bg-slate-50/80 dark:bg-slate-950 border border-slate-100 dark:border-slate-850/60 relative">
                            <svg class="h-5 w-5 text-indigo-500/20 absolute right-3 top-3" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z" />
                            </svg>
                            <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 italic leading-relaxed pr-6">
                                {{ $debt->notes ?? 'Tidak ada catatan.' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
