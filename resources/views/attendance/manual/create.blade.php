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
                <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white">Input Presensi Manual</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400">Gunakan untuk mencatat izin, sakit, absen, atau koreksi data secara manual.</p>
            </div>
        </div>
    </div>

    {{-- Messages --}}
    @if(session('success'))
        <div class="rounded-xl border border-emerald-100 bg-emerald-50/50 p-4 text-sm font-semibold text-emerald-700 dark:border-emerald-900/30 dark:bg-emerald-950/20 dark:text-emerald-450">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="rounded-xl border border-rose-100 bg-rose-50/50 p-4 text-sm font-semibold text-rose-700 dark:border-rose-900/30 dark:bg-rose-950/20 dark:text-rose-455">
            <ul class="list-inside list-disc space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form Container --}}
    <form action="{{ route('attendance.manual.store') }}" method="POST" class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Session --}}
            <div class="space-y-1.5">
                <label class="block text-xs font-bold text-slate-500 dark:text-slate-455 uppercase tracking-wider">Session Presensi</label>
                <select name="attendance_session_id" class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-850 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500" required>
                    @foreach($sessions as $session)
                        <option value="{{ $session->id }}">
                            {{ $session->name }} — {{ $session->attendance_date?->format('d M Y') }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Santri --}}
            <div class="space-y-1.5">
                <label class="block text-xs font-bold text-slate-500 dark:text-slate-455 uppercase tracking-wider">Santri</label>
                <select name="student_id" class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-850 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500" required>
                    @foreach($students as $student)
                        <option value="{{ $student->id }}">
                            {{ $student->full_name ?? $student->nama_lengkap ?? $student->name ?? 'Santri #' . $student->id }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Status --}}
            <div class="space-y-1.5">
                <label class="block text-xs font-bold text-slate-500 dark:text-slate-455 uppercase tracking-wider">Status Kehadiran</label>
                <select name="status" class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-850 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500" required>
                    <option value="present">Hadir</option>
                    <option value="late">Terlambat</option>
                    <option value="sick">Sakit</option>
                    <option value="permission">Izin</option>
                    <option value="absent">Tidak Hadir (Alpa)</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            {{-- Check In --}}
            <div class="space-y-1.5">
                <label class="block text-xs font-bold text-slate-500 dark:text-slate-455 uppercase tracking-wider">Waktu Check In</label>
                <input type="datetime-local" name="check_in_at" class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-850 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500">
            </div>

            {{-- Check Out --}}
            <div class="space-y-1.5">
                <label class="block text-xs font-bold text-slate-500 dark:text-slate-455 uppercase tracking-wider">Waktu Check Out</label>
                <input type="datetime-local" name="check_out_at" class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-850 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500">
            </div>
        </div>

        {{-- Note --}}
        <div class="space-y-1.5">
            <label class="block text-xs font-bold text-slate-500 dark:text-slate-455 uppercase tracking-wider">Catatan / Keterangan</label>
            <textarea name="note" rows="3" class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-850 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 placeholder-slate-400" placeholder="Contoh: Sakit demam dengan surat dokter, izin pulang kampung, dll..."></textarea>
        </div>

        {{-- Buttons --}}
        <div class="flex items-center justify-end gap-3 border-t border-slate-200/80 pt-4 dark:border-slate-800">
            <a href="{{ route('attendance.reports.dashboard') }}"
               class="inline-flex items-center justify-center rounded-full border border-slate-250/70 bg-white px-5 py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-350 dark:hover:bg-slate-900 transition-all focus:outline-none active:scale-[0.98]">
                Batal
            </a>
            <button class="inline-flex items-center justify-center rounded-full bg-indigo-600 px-6 py-2.5 text-xs font-bold text-white hover:bg-indigo-700 shadow-sm shadow-indigo-500/10 hover:shadow-indigo-500/20 active:scale-[0.98] transition-all focus:outline-none focus:ring-4 focus:ring-indigo-500/20">
                Simpan Presensi
            </button>
        </div>
    </form>

</div>
@endsection
