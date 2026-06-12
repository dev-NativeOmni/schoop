@extends('layouts.app')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">
    {{-- Header --}}
    <div class="flex items-center space-x-3 border-b border-slate-200/80 pb-5 dark:border-slate-800">
        <a href="{{ route('tahfizh.hafalan-records.index') }}" 
           class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-slate-200 text-slate-500 hover:bg-slate-50 dark:border-slate-800 dark:text-slate-400 dark:hover:bg-slate-800 transition-all" 
           title="Kembali ke Daftar">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <div>
            <h2 class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white">Tambah Setoran Tahfizh</h2>
            <p class="text-sm text-slate-500 dark:text-slate-400">Input setoran hafalan baru santri.</p>
        </div>
    </div>

    {{-- Form --}}
    <form method="POST" action="{{ route('tahfizh.hafalan-records.store') }}"
          class="space-y-6 rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
        @csrf

        @include('tahfizh.hafalan-records._form', [
            'record' => null,
            'schools' => $schools,
            'students' => $students,
            'teachers' => $teachers,
            'targets' => $targets,
            'surahs' => $surahs,
            'statuses' => $statuses,
            'defaultTeacherId' => $defaultTeacherId,
        ])

        <div class="flex items-center justify-end space-x-3 border-t border-slate-100 pt-5 dark:border-slate-800/60">
            <a href="{{ route('tahfizh.hafalan-records.index') }}"
               class="inline-flex items-center justify-center rounded-full border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-400 dark:hover:bg-slate-800 transition-all focus:outline-none">
                Batal
            </a>
            <button type="submit"
                    class="inline-flex items-center justify-center rounded-full bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700 shadow-sm transition-all focus:outline-none focus:ring-4 focus:ring-indigo-500/20 active:scale-[0.98]">
                Simpan Setoran
            </button>
        </div>
    </form>
</div>
@endsection

