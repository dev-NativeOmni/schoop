@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div>
        <a href="{{ route('ai-learning.recommendations.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 transition">
            &larr; Kembali ke Daftar Rekomendasi
        </a>
        <h1 class="text-3xl font-black tracking-tight text-slate-900 dark:text-slate-100 mt-2">Detail Rekomendasi Belajar</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Rekomendasi spesifik hasil pengelompokan sinyal belajar siswa.</p>
    </div>

    <div class="rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm space-y-6">
        <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-850 pb-4">
            <div>
                <h2 class="text-xl font-bold text-slate-850 dark:text-slate-150">{{ $recommendation->title }}</h2>
                <p class="text-xs text-slate-450 mt-1">
                    Siswa: <strong class="text-slate-650 dark:text-slate-300">{{ $recommendation->student->user?->name }}</strong>
                </p>
            </div>
            <span class="inline-flex items-center rounded-lg px-2.5 py-0.5 text-xs font-bold 
                @if($recommendation->status === 'published') bg-emerald-50 dark:bg-emerald-950/20 text-emerald-600
                @else bg-slate-100 dark:bg-slate-850 text-slate-500
                @endif">
                {{ strtoupper($recommendation->status) }}
            </span>
        </div>

        <div class="space-y-2">
            <h3 class="text-sm font-bold text-slate-400 uppercase">Deskripsi Masalah / Area Peningkatan</h3>
            <p class="text-sm text-slate-700 dark:text-slate-350 leading-relaxed font-medium">
                {{ $recommendation->description }}
            </p>
        </div>

        <div class="space-y-3">
            <h3 class="text-sm font-bold text-slate-400 uppercase">Rekomendasi Tindakan</h3>
            <ul class="space-y-2">
                @foreach($recommendation->recommended_actions ?? [] as $action)
                    <li class="flex items-start gap-2.5 text-sm text-slate-700 dark:text-slate-350">
                        <span class="text-indigo-600 font-black mt-0.5">&bull;</span>
                        <span>{{ $action }}</span>
                    </li>
                @endforeach
            </ul>
        </div>

        @if($recommendation->evidence)
            <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-850 space-y-2">
                <h4 class="text-xs font-bold text-slate-450 uppercase">Bukti Pendukung (Evidence)</h4>
                <pre class="text-xs text-slate-600 dark:text-slate-400 overflow-x-auto p-2 rounded-xl bg-white dark:bg-slate-900 border border-slate-150 dark:border-slate-800">{{ json_encode($recommendation->evidence, JSON_PRETTY_PRINT) }}</pre>
            </div>
        @endif
    </div>
</div>
@endsection
