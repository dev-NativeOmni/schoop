@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto space-y-6">
    <div>
        <a href="{{ route('ai-learning.dashboard') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 transition">
            &larr; Kembali ke Dashboard AI
        </a>
        <h1 class="text-3xl font-black tracking-tight text-slate-900 dark:text-slate-100 mt-2">Draf Catatan Umpan Balik</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Generasikan draf umpan balik guru yang suportif untuk dikirimkan kepada orang tua.</p>
    </div>

    <div class="rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm">
        <form action="{{ route('ai-learning.feedback-drafts.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Select Student -->
            <div class="space-y-2">
                <label for="student_id" class="text-xs font-bold text-slate-700 dark:text-slate-300">Pilih Siswa</label>
                <select id="student_id" name="student_id" required class="block w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950/40 px-4 py-3 text-slate-800 dark:text-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="" disabled selected>-- Pilih Siswa --</option>
                    @foreach($students as $st)
                        <option value="{{ $st->id }}">{{ $st->user?->name }} (NIS: {{ $st->nis ?? '-' }})</option>
                    @endforeach
                </select>
            </div>

            <!-- Select Template -->
            <div class="space-y-2">
                <label for="template_key" class="text-xs font-bold text-slate-700 dark:text-slate-300">Pilih Template Kalimat</label>
                <select id="template_key" name="template_key" required class="block w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950/40 px-4 py-3 text-slate-800 dark:text-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="" disabled selected>-- Pilih Template --</option>
                    @foreach($templates as $tmpl)
                        <option value="{{ $tmpl->template_key }}">{{ $tmpl->title }} (Tone: {{ $tmpl->tone }})</option>
                    @endforeach
                </select>
                <p class="text-[10px] text-slate-450 mt-1">Template di atas sepenuhnya mematuhi batasan kebahasaan suportif, mencegah penggunaan pelabelan negatif.</p>
            </div>

            <div class="pt-2 border-t border-slate-100 dark:border-slate-850">
                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-2xl bg-indigo-600 px-5 py-3 font-bold text-white shadow-md hover:bg-indigo-700 transition text-sm">
                    Generasikan Draf Catatan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
