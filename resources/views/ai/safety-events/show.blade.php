@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div>
        <a href="{{ route('ai-learning.safety-events.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 transition">
            &larr; Kembali ke Daftar Safety Alerts
        </a>
        <h1 class="text-3xl font-black tracking-tight text-slate-900 dark:text-slate-100 mt-2">Detail Peringatan Safety</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Laporan pemicu pelanggaran tata kebahasaan suportif asisten AI.</p>
    </div>

    <div class="rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm space-y-6">
        <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-850 pb-4">
            <div>
                <h2 class="text-lg font-bold text-slate-800 dark:text-slate-150">Tipe: {{ strtoupper(str_replace('_', ' ', $safetyEvent->event_type)) }}</h2>
                <p class="text-xs text-slate-400 mt-1">Dibuat: {{ $safetyEvent->created_at->translatedFormat('d M Y, H:i') }}</p>
            </div>
            <span class="inline-flex items-center rounded-lg px-2.5 py-0.5 text-xs font-bold bg-rose-50 text-rose-600">
                {{ strtoupper($safetyEvent->severity) }}
            </span>
        </div>

        <div class="space-y-1">
            <h3 class="text-xs font-bold text-slate-400 uppercase">Deskripsi</h3>
            <p class="text-sm text-slate-700 dark:text-slate-300 font-medium">{{ $safetyEvent->description }}</p>
        </div>

        @if($safetyEvent->metadata)
            <div class="space-y-4 pt-4 border-t border-slate-100 dark:border-slate-850">
                <h3 class="text-xs font-bold text-slate-400 uppercase">Metadata Deteksi</h3>
                
                @if(isset($safetyEvent->metadata['original_text']))
                    <div class="space-y-1">
                        <span class="text-xs font-bold text-rose-600">Original Draft Text:</span>
                        <div class="p-3 bg-rose-50/20 dark:bg-rose-950/10 rounded-xl border border-rose-100 dark:border-rose-950/40 text-xs text-slate-700 dark:text-slate-350 leading-relaxed">
                            {{ $safetyEvent->metadata['original_text'] }}
                        </div>
                    </div>
                @endif

                @if(isset($safetyEvent->metadata['sanitized_text']))
                    <div class="space-y-1">
                        <span class="text-xs font-bold text-emerald-600">Sanitized/Corrected Text:</span>
                        <div class="p-3 bg-emerald-50/20 dark:bg-emerald-950/10 rounded-xl border border-emerald-100 dark:border-emerald-950/40 text-xs text-slate-700 dark:text-slate-350 leading-relaxed">
                            {{ $safetyEvent->metadata['sanitized_text'] }}
                        </div>
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection
