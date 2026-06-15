@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between border-b border-slate-200 pb-4 dark:border-slate-800">
        <div>
            <div class="flex items-center gap-2">
                <a href="{{ route('boarding.discipline-logs.index') }}" class="text-sm font-semibold text-emerald-500 hover:underline">Daftar Log Kedisiplinan</a>
                <span class="text-slate-400">/</span>
                <span class="text-sm text-slate-500 dark:text-slate-400">Detail Catatan Kedisiplinan</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white mt-1">{{ $disciplineLog->student->full_name }}</h1>
            <p class="text-sm text-slate-500">NIS: {{ $disciplineLog->student->student_number ?? '-' }} | Kelas: {{ $disciplineLog->student->classRoom->name ?? '-' }}</p>
        </div>
        <a href="{{ route('boarding.discipline-logs.index') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-350 dark:hover:bg-slate-950">
            Kembali
        </a>
    </div>

    <!-- Details Card -->
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 space-y-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 border-b border-slate-100 pb-4 dark:border-slate-800">
            <div>
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Jenis Log</span>
                <div class="mt-1">
                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold uppercase
                        @if($disciplineLog->type === 'violation') bg-rose-50 text-rose-700 dark:bg-rose-950/30 dark:text-rose-455
                        @elseif($disciplineLog->type === 'warning') bg-amber-50 text-amber-700 dark:bg-amber-950/30 dark:text-amber-455
                        @elseif($disciplineLog->type === 'achievement') bg-emerald-50 text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-455
                        @else bg-slate-50 text-slate-700 dark:bg-slate-950/30 dark:text-slate-455 @endif">
                        @if($disciplineLog->type === 'violation') Pelanggaran
                        @elseif($disciplineLog->type === 'warning') Peringatan
                        @elseif($disciplineLog->type === 'achievement') Prestasi
                        @else Catatan Umum
                        @endif
                    </span>
                </div>
            </div>
            <div>
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Poin Perubahan</span>
                <span class="text-sm font-bold block mt-1 
                    @if($disciplineLog->points > 0) text-emerald-555
                    @elseif($disciplineLog->points < 0) text-rose-555
                    @else text-slate-600 dark:text-slate-400 @endif">
                    {{ $disciplineLog->points > 0 ? '+' : '' }}{{ $disciplineLog->points }} Poin
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 border-b border-slate-100 pb-4 dark:border-slate-800">
            <div>
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Kategori</span>
                <span class="text-sm font-semibold text-slate-800 dark:text-white block mt-1">{{ $disciplineLog->category ?? '-' }}</span>
            </div>
            <div>
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Waktu Kejadian</span>
                <span class="text-sm font-semibold text-slate-800 dark:text-white block mt-1">{{ \Carbon\Carbon::parse($disciplineLog->logged_at)->format('d M Y H:i') }}</span>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 border-b border-slate-100 pb-4 dark:border-slate-800">
            <div>
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Dicatat Oleh</span>
                <span class="text-sm font-semibold text-slate-800 dark:text-white block mt-1">{{ $disciplineLog->recordedBy->name ?? '-' }}</span>
            </div>
        </div>

        <div class="border-b border-slate-100 pb-4 dark:border-slate-800">
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Deskripsi / Detail Kejadian</span>
            <p class="text-sm text-slate-600 dark:text-slate-400 mt-1 whitespace-pre-wrap">{{ $disciplineLog->description }}</p>
        </div>

        @if($disciplineLog->action_taken)
            <div>
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Tindakan / Sanksi / Tindak Lanjut</span>
                <p class="text-sm text-slate-700 dark:text-slate-350 font-medium mt-1 whitespace-pre-wrap bg-slate-50 dark:bg-slate-950 p-4 rounded-xl border border-slate-150 dark:border-slate-800">{{ $disciplineLog->action_taken }}</p>
            </div>
        @endif
    </div>
</div>
@endsection
