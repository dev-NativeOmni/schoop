@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto space-y-6">
    <div>
        <a href="{{ route('ai-learning.practice-plans.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 transition">
            &larr; Kembali ke Daftar Rencana Latihan
        </a>
        <h1 class="text-3xl font-black tracking-tight text-slate-900 dark:text-slate-100 mt-2">Buat Rencana Latihan</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Generasikan practice plan terstruktur untuk mendukung latihan mandiri Qur'an siswa.</p>
    </div>

    <div class="rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm">
        <form action="{{ route('ai-learning.practice-plans.store') }}" method="POST" class="space-y-6">
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

            <!-- Select Duration -->
            <div class="space-y-2">
                <label for="days" class="text-xs font-bold text-slate-700 dark:text-slate-300">Durasi Rencana Latihan (Hari)</label>
                <select id="days" name="days" required class="block w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950/40 px-4 py-3 text-slate-800 dark:text-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="3">3 Hari</option>
                    <option value="7" selected>7 Hari (Rekomendasi)</option>
                    <option value="10">10 Hari</option>
                    <option value="14">14 Hari</option>
                </select>
                <p class="text-[10px] text-slate-450 mt-1">Practice plan akan digenerasikan dengan membagi rekomendasi tindakan ke dalam jumlah hari yang dipilih secara seimbang (maksimal 3 item per hari).</p>
            </div>

            <div class="pt-2 border-t border-slate-100 dark:border-slate-850">
                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-2xl bg-indigo-600 px-5 py-3 font-bold text-white shadow-md hover:bg-indigo-700 transition text-sm">
                    Generasikan Practice Plan Draft
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
