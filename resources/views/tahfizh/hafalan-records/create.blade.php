@extends('layouts.app')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">
    {{-- Header --}}
    <div class="flex items-center space-x-3.5 border-b border-slate-200/80 pb-5 dark:border-slate-800">
        <a href="{{ route('tahfizh.hafalan-records.index') }}" 
           class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 hover:bg-slate-50 hover:text-slate-900 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-400 dark:hover:bg-slate-800 transition-all shadow-2xs" 
           title="Kembali ke Daftar">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <div>
            <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-50 text-[11px] font-bold text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/40 mb-1">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                Talaqqi Setoran Harian
            </div>
            <h2 class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white">Input Setoran Tahfizh Santri</h2>
            <p class="text-xs text-slate-500 dark:text-slate-400">Catat dan hitung otomatis baris hafalan baru dengan sistem sequence guard.</p>
        </div>
    </div>

    {{-- Form --}}
    <form method="POST" action="{{ route('tahfizh.hafalan-records.store') }}" class="space-y-6">
        @csrf

        @include('tahfizh.hafalan-records._form', [
            'record' => null,
            'schools' => $schools,
            'classRooms' => $classRooms,
            'students' => $students,
            'studentsData' => $studentsData,
            'teachers' => $teachers,
            'targets' => $targets,
            'surahs' => $surahs,
            'statuses' => $statuses,
            'defaultTeacherId' => $defaultTeacherId,
        ])

        <div class="card-natural p-5 flex items-center justify-between">
            <a href="{{ route('tahfizh.hafalan-records.index') }}"
               class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-xs font-bold text-slate-600 hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-slate-800 transition-all">
                &larr; Batal & Kembali
            </a>
            <button type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 px-6 py-2.5 text-sm font-bold text-white hover:bg-emerald-700 shadow-md shadow-emerald-600/20 active:scale-[0.98] transition-all">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                <span>Simpan Setoran Santri</span>
            </button>
        </div>
    </form>
</div>
@endsection

