@extends('layouts.app')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between border-b border-slate-200/80 pb-5 dark:border-slate-800 gap-4">
        <div class="flex items-center space-x-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 dark:bg-indigo-950/30 dark:text-indigo-400">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white">Edit Session Presensi</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400">Mengubah konfigurasi session: {{ $session->name }}</p>
            </div>
        </div>
    </div>

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="rounded-xl border border-rose-100 bg-rose-50/50 p-4 text-sm font-semibold text-rose-700 dark:border-rose-900/30 dark:bg-rose-950/20 dark:text-rose-455">
            <ul class="list-inside list-disc space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form --}}
    <form action="{{ route('attendance.sessions.update', $session) }}" method="POST" class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 space-y-6">
        @csrf
        @method('PUT')

        @include('attendance.sessions._form', [
            'session' => $session,
            'classRooms' => $classRooms,
        ])

        {{-- Actions Buttons --}}
        <div class="flex items-center justify-end gap-3 border-t border-slate-200/80 pt-4 dark:border-slate-800">
            <a href="{{ route('attendance.sessions.index') }}"
               class="inline-flex items-center justify-center rounded-full border border-slate-250/70 bg-white px-5 py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-350 dark:hover:bg-slate-900 transition-all focus:outline-none active:scale-[0.98]">
                Batal
            </a>
            <button class="inline-flex items-center justify-center rounded-full bg-indigo-600 px-6 py-2.5 text-xs font-bold text-white hover:bg-indigo-700 shadow-sm shadow-indigo-500/10 hover:shadow-indigo-500/20 active:scale-[0.98] transition-all focus:outline-none focus:ring-4 focus:ring-indigo-500/20">
                Simpan Perubahan
            </button>
        </div>
    </form>

</div>
@endsection
