@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div>
        <a href="{{ route('ai-learning.review-queue.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 transition">
            &larr; Kembali ke Antrean Verifikasi
        </a>
        <h1 class="text-3xl font-black tracking-tight text-slate-900 dark:text-slate-100 mt-2">Tinjau Draf Output</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Verifikasi isi draf di bawah sebelum diterbitkan secara resmi.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main details -->
        <div class="lg:col-span-2 space-y-6">
            <div class="rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-850 pb-3">
                    <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100">Isi Draf Rekomendasi</h2>
                    <span class="inline-flex items-center rounded-lg px-2.5 py-0.5 text-xs font-bold bg-amber-50 text-amber-600">
                        {{ strtoupper($item->status) }}
                    </span>
                </div>

                <div class="space-y-2">
                    <h3 class="text-sm font-bold text-slate-400 uppercase">Judul</h3>
                    <p class="text-sm font-bold text-slate-800 dark:text-slate-200">{{ $item->output?->title }}</p>
                </div>

                <div class="space-y-2">
                    <h3 class="text-sm font-bold text-slate-400 uppercase">Isi Konten</h3>
                    <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-850 text-sm font-medium text-slate-700 dark:text-slate-300 leading-relaxed whitespace-pre-line">
                        {{ $item->output?->body }}
                    </div>
                </div>

                <!-- Structured output info -->
                @if($item->output?->structured_output)
                    <div class="p-3 bg-indigo-50/20 rounded-2xl border border-indigo-50">
                        <p class="text-xs font-bold text-indigo-700">Metadata Output:</p>
                        <pre class="text-[10px] text-slate-600 mt-1">{{ json_encode($item->output->structured_output, JSON_PRETTY_PRINT) }}</pre>
                    </div>
                @endif
            </div>
        </div>

        <!-- Review action column -->
        <div class="space-y-6">
            <div class="rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm space-y-4">
                <h3 class="font-bold text-slate-900 dark:text-slate-100 border-b border-slate-100 dark:border-slate-850 pb-3">Keputusan</h3>
                
                <form action="{{ route('ai-learning.review-queue.approve', $item) }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="space-y-2">
                        <label for="review_note" class="text-xs font-bold text-slate-700 dark:text-slate-350">Catatan Review (Opsional)</label>
                        <textarea id="review_note" name="review_note" placeholder="Contoh: Bacaan tajwid mad ananda sudah disetujui..." class="block w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950/40 px-3 py-2 text-slate-800 dark:text-slate-200 text-xs focus:border-indigo-500 focus:ring-indigo-500" rows="3"></textarea>
                    </div>

                    @if($item->status === 'pending')
                        <button type="submit" class="w-full inline-flex items-center justify-center rounded-2xl bg-indigo-600 px-4 py-2.5 font-bold text-white shadow hover:bg-indigo-700 transition text-xs">
                            Setujui Draf (Approve)
                        </button>
                        
                        <button type="submit" formaction="{{ route('ai-learning.review-queue.publish', $item) }}" class="w-full inline-flex items-center justify-center rounded-2xl bg-emerald-600 px-4 py-2.5 font-bold text-white shadow hover:bg-emerald-700 transition text-xs">
                            Setujui & Publikasikan
                        </button>

                        <button type="submit" formaction="{{ route('ai-learning.review-queue.reject', $item) }}" class="w-full inline-flex items-center justify-center rounded-2xl bg-rose-600 px-4 py-2.5 font-bold text-white shadow hover:bg-rose-700 transition text-xs">
                            Tolak (Reject)
                        </button>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
