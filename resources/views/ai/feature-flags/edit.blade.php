@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto space-y-6">
    <div>
        <a href="{{ route('ai-learning.feature-flags.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 transition">
            &larr; Kembali ke Daftar Konfigurasi
        </a>
        <h1 class="text-3xl font-black tracking-tight text-slate-900 dark:text-slate-100 mt-2">Edit Pengaturan Fitur AI</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Konfigurasi pengaturan detail untuk flag: {{ $featureFlag->label }}</p>
    </div>

    <div class="rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm">
        <form action="{{ route('ai-learning.feature-flags.update', $featureFlag) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Is Enabled Toggle -->
            <div class="flex items-start justify-between p-4 rounded-2xl bg-slate-50/50 dark:bg-slate-950/20 border border-slate-100 dark:border-slate-850">
                <div class="space-y-0.5">
                    <label for="is_enabled" class="text-sm font-bold text-slate-800 dark:text-slate-200">Aktifkan Fitur</label>
                    <p class="text-xs text-slate-400">Aktifkan atau nonaktifkan penggunaan sistem cerdas ini secara keseluruhan.</p>
                </div>
                <input type="checkbox" id="is_enabled" name="is_enabled" value="1" {{ $featureFlag->is_enabled ? 'checked' : '' }} class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 h-5 w-5">
            </div>

            <!-- Requires Teacher Review Toggle -->
            <div class="flex items-start justify-between p-4 rounded-2xl bg-slate-50/50 dark:bg-slate-950/20 border border-slate-100 dark:border-slate-850">
                <div class="space-y-0.5">
                    <label for="requires_teacher_review" class="text-sm font-bold text-slate-800 dark:text-slate-200">Wajib Review Guru</label>
                    <p class="text-xs text-slate-400">Semua keluaran/rekomendasi asisten harus divalidasi pembimbing terlebih dahulu sebelum dipublikasikan.</p>
                </div>
                <input type="checkbox" id="requires_teacher_review" name="requires_teacher_review" value="1" {{ $featureFlag->requires_teacher_review ? 'checked' : '' }} class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 h-5 w-5">
            </div>

            <!-- Visible to Parent Toggle -->
            <div class="flex items-start justify-between p-4 rounded-2xl bg-slate-50/50 dark:bg-slate-950/20 border border-slate-100 dark:border-slate-850">
                <div class="space-y-0.5">
                    <label for="visible_to_parent" class="text-sm font-bold text-slate-800 dark:text-slate-200">Terlihat oleh Wali Murid</label>
                    <p class="text-xs text-slate-400">Izinkan orang tua mengakses ringkasan laporan asisten belajar ini di portal wali murid.</p>
                </div>
                <input type="checkbox" id="visible_to_parent" name="visible_to_parent" value="1" {{ $featureFlag->visible_to_parent ? 'checked' : '' }} class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 h-5 w-5">
            </div>

            <!-- Visible to Student Toggle -->
            <div class="flex items-start justify-between p-4 rounded-2xl bg-slate-50/50 dark:bg-slate-950/20 border border-slate-100 dark:border-slate-850">
                <div class="space-y-0.5">
                    <label for="visible_to_student" class="text-sm font-bold text-slate-800 dark:text-slate-200">Terlihat oleh Siswa</label>
                    <p class="text-xs text-slate-400">Izinkan santri mengakses rencana latihan dan checklist rekomendasi di portal mandiri santri.</p>
                </div>
                <input type="checkbox" id="visible_to_student" name="visible_to_student" value="1" {{ $featureFlag->visible_to_student ? 'checked' : '' }} class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 h-5 w-5">
            </div>

            <div class="pt-2 border-t border-slate-100 dark:border-slate-850">
                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-2xl bg-indigo-600 px-5 py-3 font-bold text-white shadow-md hover:bg-indigo-700 transition text-sm">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
