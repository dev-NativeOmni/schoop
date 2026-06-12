@extends('layouts.app')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between border-b border-slate-200/80 pb-5 dark:border-slate-800 gap-4">
        <div class="flex items-center space-x-3">
            <a href="{{ route('tahfizh.hafalan-records.index') }}" 
               class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-slate-200 text-slate-500 hover:bg-slate-50 dark:border-slate-800 dark:text-slate-400 dark:hover:bg-slate-800 transition-all" 
               title="Kembali ke Daftar">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h2 class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white">Detail Setoran Tahfizh</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400">Informasi lengkap setoran hafalan santri.</p>
            </div>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('tahfizh.hafalan-records.index') }}"
               class="inline-flex items-center justify-center rounded-full border border-slate-200 bg-white px-4 py-2 text-xs font-bold text-slate-600 hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-400 dark:hover:bg-slate-800 transition-all">
                Kembali
            </a>
            @if (auth()->user()->hasRole(['super_admin', 'admin']) || (auth()->user()->hasRole('teacher') && $record->teacher_id === auth()->id()))
                <a href="{{ route('tahfizh.hafalan-records.edit', $record) }}"
                   class="inline-flex items-center justify-center rounded-full bg-indigo-600 px-4 py-2 text-xs font-bold text-white hover:bg-indigo-700 shadow-sm transition-all focus:outline-none focus:ring-4 focus:ring-indigo-500/20 active:scale-[0.98]">
                    Edit Setoran
                </a>
            @endif
        </div>
    </div>

    <div class="grid gap-6 md:grid-cols-2">
        {{-- Data Utama --}}
        <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <h3 class="mb-5 text-sm font-bold uppercase tracking-wider text-indigo-600 dark:text-indigo-400 border-b border-slate-100 dark:border-slate-800/60 pb-3">Data Utama Setoran</h3>

            <dl class="space-y-4">
                <div>
                    <dt class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Tanggal Setoran</dt>
                    <dd class="mt-1 text-sm font-semibold text-slate-800 dark:text-slate-200">{{ $record->record_date?->format('d F Y') }}</dd>
                </div>

                <div>
                    <dt class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Santri</dt>
                    <dd class="mt-1 text-sm font-semibold text-slate-800 dark:text-slate-200">{{ $record->student?->full_name }}</dd>
                </div>

                <div>
                    <dt class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Kelas</dt>
                    <dd class="mt-1 text-sm font-semibold text-slate-850 dark:text-slate-300">{{ $record->student?->classRoom?->name ?? '-' }}</dd>
                </div>

                <div>
                    <dt class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Guru Penerima</dt>
                    <dd class="mt-1 text-sm font-semibold text-slate-800 dark:text-slate-200">{{ $record->teacher?->name ?? '-' }}</dd>
                </div>

                <div>
                    <dt class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Target Tahfizh</dt>
                    <dd class="mt-1 text-sm font-semibold text-indigo-600 dark:text-indigo-400">
                        {{ $record->tahfizhTarget?->name ?? 'Tanpa target khusus' }}
                    </dd>
                </div>

                <div>
                    <dt class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Status Kelancaran</dt>
                    <dd class="mt-1.5">
                        @php
                            $statusClass = match(strtolower($record->status)) {
                                'lancar', 'lunas', 'met', 'tahsin_lancar' => 'bg-emerald-50 text-emerald-700 border-emerald-150 dark:bg-emerald-950/20 dark:text-emerald-400 dark:border-emerald-900/50',
                                'sedang', 'behind', 'tahsin_sedang' => 'bg-amber-50 text-amber-700 border-amber-150 dark:bg-amber-950/20 dark:text-amber-400 dark:border-amber-900/50',
                                default => 'bg-rose-50 text-rose-700 border-rose-150 dark:bg-rose-950/20 dark:text-rose-400 dark:border-rose-900/50',
                            };
                        @endphp
                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[10px] font-bold border {{ $statusClass }}">
                            {{ strtoupper(str_replace('_', ' ', $record->status)) }}
                        </span>
                    </dd>
                </div>
            </dl>
        </div>

        {{-- Detail Rentang Hafalan --}}
        <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <h3 class="mb-5 text-sm font-bold uppercase tracking-wider text-indigo-600 dark:text-indigo-400 border-b border-slate-100 dark:border-slate-800/60 pb-3">Rentang Hafalan & Urutan</h3>

            <dl class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <dt class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Mulai</dt>
                        <dd class="mt-1 text-sm font-semibold text-slate-800 dark:text-slate-200">Halaman {{ $record->start_page }}, Baris {{ $record->start_line }}</dd>
                    </div>

                    <div>
                        <dt class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Selesai</dt>
                        <dd class="mt-1 text-sm font-semibold text-slate-800 dark:text-slate-200">Halaman {{ $record->end_page }}, Baris {{ $record->end_line }}</dd>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <dt class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Surah Awal</dt>
                        <dd class="mt-1 text-sm font-semibold text-slate-850 dark:text-slate-300">
                            {{ $record->startSurah?->name_latin ?? '-' }} 
                            @if($record->start_ayah)
                                <span class="text-xs text-slate-400 font-medium">(ayat {{ $record->start_ayah }})</span>
                            @endif
                        </dd>
                    </div>

                    <div>
                        <dt class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Surah Akhir</dt>
                        <dd class="mt-1 text-sm font-semibold text-slate-850 dark:text-slate-300">
                            {{ $record->endSurah?->name_latin ?? '-' }} 
                            @if($record->end_ayah)
                                <span class="text-xs text-slate-400 font-medium">(ayat {{ $record->end_ayah }})</span>
                            @endif
                        </dd>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <dt class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Baris</dt>
                        <dd class="mt-1 text-sm font-extrabold text-indigo-600 dark:text-indigo-400">{{ $record->total_lines }} baris</dd>
                    </div>

                    <div>
                        <dt class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Kesesuaian Urutan</dt>
                        <dd class="mt-1">
                            @if($record->is_sequence_valid)
                                <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-0.5 text-[10px] font-bold text-emerald-700 border border-emerald-150 dark:bg-emerald-950/20 dark:text-emerald-400 dark:border-emerald-900/50">Berurutan</span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-rose-50 px-2.5 py-0.5 text-[10px] font-bold text-rose-700 border border-rose-150 dark:bg-rose-950/20 dark:text-rose-400 dark:border-rose-900/50">Tidak Berurutan</span>
                            @endif
                        </dd>
                    </div>
                </div>

                @if($record->sequence_note)
                    <div>
                        <dt class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Catatan Sequence</dt>
                        <dd class="mt-1 text-xs text-slate-600 dark:text-slate-400 italic bg-slate-50 dark:bg-slate-950 p-2.5 rounded-lg border border-slate-100 dark:border-slate-800/60">{{ $record->sequence_note }}</dd>
                    </div>
                @endif
            </dl>
        </div>
    </div>

    {{-- Catatan & Penilaian --}}
    <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <h3 class="mb-4 text-sm font-bold uppercase tracking-wider text-indigo-600 dark:text-indigo-400 pb-2 border-b border-slate-100 dark:border-slate-800/60">Penilaian & Catatan Guru</h3>
        <div class="grid gap-6 md:grid-cols-3">
            <div class="md:col-span-1 flex flex-col justify-center items-center p-4 bg-slate-50 dark:bg-slate-950 rounded-2xl border border-slate-100 dark:border-slate-800/60">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Nilai Kualitas</span>
                <span class="text-4xl font-black text-slate-800 dark:text-white">{{ $record->quality_score ?? '-' }}</span>
                <span class="text-[10px] text-slate-400 mt-1">Skala 0-100</span>
            </div>
            <div class="md:col-span-2">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1.5">Catatan Tambahan Guru</span>
                <p class="text-sm text-slate-700 dark:text-slate-300 font-medium leading-relaxed bg-slate-50/50 dark:bg-slate-950/20 p-4 rounded-2xl border border-slate-100 dark:border-slate-850 italic">
                    "{{ $record->notes ?? 'Tidak ada catatan tambahan untuk setoran ini.' }}"
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

