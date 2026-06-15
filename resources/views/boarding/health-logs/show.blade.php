@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between border-b border-slate-200 pb-4 dark:border-slate-800">
        <div>
            <div class="flex items-center gap-2">
                <a href="{{ route('boarding.health-logs.index') }}" class="text-sm font-semibold text-emerald-500 hover:underline">Daftar Log Kesehatan</a>
                <span class="text-slate-400">/</span>
                <span class="text-sm text-slate-500 dark:text-slate-400">Detail Catatan Kesehatan</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white mt-1">{{ $healthLog->student->full_name }}</h1>
            <p class="text-sm text-slate-500">NIS: {{ $healthLog->student->student_number ?? '-' }} | Kelas: {{ $healthLog->student->classRoom->name ?? '-' }}</p>
        </div>
        <a href="{{ route('boarding.health-logs.index') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-350 dark:hover:bg-slate-950">
            Kembali
        </a>
    </div>

    <!-- Details Card -->
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 space-y-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 border-b border-slate-100 pb-4 dark:border-slate-800">
            <div>
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Kondisi / Keluhan</span>
                <span class="text-sm font-semibold text-slate-800 dark:text-white block mt-1">{{ $healthLog->condition_title }}</span>
            </div>
            <div>
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Tingkat Keparahan</span>
                <div class="mt-1">
                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold uppercase
                        @if($healthLog->severity === 'critical') bg-rose-50 text-rose-700 dark:bg-rose-950/30 dark:text-rose-455
                        @elseif($healthLog->severity === 'high') bg-orange-50 text-orange-700 dark:bg-orange-950/30 dark:text-orange-455
                        @elseif($healthLog->severity === 'medium') bg-amber-50 text-amber-700 dark:bg-amber-950/30 dark:text-amber-455
                        @else bg-emerald-50 text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-455 @endif">
                        @if($healthLog->severity === 'critical') Gawat / Darurat
                        @elseif($healthLog->severity === 'high') Parah
                        @elseif($healthLog->severity === 'medium') Sedang
                        @else Ringan
                        @endif
                    </span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 border-b border-slate-100 pb-4 dark:border-slate-800">
            <div>
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Waktu Kejadian</span>
                <span class="text-sm font-semibold text-slate-800 dark:text-white block mt-1">{{ \Carbon\Carbon::parse($healthLog->logged_at)->format('d M Y H:i') }}</span>
            </div>
            <div>
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Dicatat Oleh</span>
                <span class="text-sm font-semibold text-slate-800 dark:text-white block mt-1">{{ $healthLog->recordedBy->name ?? '-' }}</span>
            </div>
        </div>

        @if($healthLog->description)
            <div class="border-b border-slate-100 pb-4 dark:border-slate-800">
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Deskripsi Gejala</span>
                <p class="text-sm text-slate-600 dark:text-slate-400 mt-1 whitespace-pre-wrap">{{ $healthLog->description }}</p>
            </div>
        @endif

        @if($healthLog->action_taken)
            <div>
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Tindakan / Penanganan yang Diberikan</span>
                <p class="text-sm text-slate-700 dark:text-slate-300 font-medium mt-1 whitespace-pre-wrap bg-slate-50 dark:bg-slate-950 p-4 rounded-xl border border-slate-150 dark:border-slate-800">{{ $healthLog->action_taken }}</p>
            </div>
        @endif
    </div>
</div>
@endsection
