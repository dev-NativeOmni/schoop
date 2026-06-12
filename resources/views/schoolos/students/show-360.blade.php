@extends('layouts.app')

@section('content')
<div class="space-y-8 animate-fade-in">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center gap-6 rounded-2xl bg-gradient-to-r from-violet-600 via-purple-500 to-indigo-500 p-6 text-white shadow-xl">
        <div class="flex h-20 w-20 shrink-0 items-center justify-center rounded-2xl bg-white/20 backdrop-blur-sm text-4xl font-extrabold shadow-inner">
            {{ mb_strtoupper(mb_substr($student->full_name ?? $student->nickname ?? 'S', 0, 1)) }}
        </div>
        <div class="flex-1">
            <span class="text-xs font-semibold uppercase tracking-widest text-violet-200">Student 360 Profile</span>
            <h1 class="mt-0.5 text-2xl font-extrabold tracking-tight">
                {{ $student->full_name ?? $student->nickname ?? 'Santri #'.$student->id }}
            </h1>
            <div class="mt-2 flex flex-wrap gap-3 text-sm">
                <span class="rounded-full bg-white/15 px-3 py-1">
                    🎓 {{ $student->classRoom?->name ?? '-' }}
                </span>
                @if($student->nisn)
                    <span class="rounded-full bg-white/15 px-3 py-1">NISN: {{ $student->nisn }}</span>
                @endif
                @if($student->student_number)
                    <span class="rounded-full bg-white/15 px-3 py-1">No. Santri: {{ $student->student_number }}</span>
                @endif
            </div>
        </div>
        <a href="{{ route('schoolos.dashboard') }}" class="shrink-0 rounded-xl bg-white/15 px-4 py-2 text-sm font-semibold hover:bg-white/25 transition-all">
            ← Kembali
        </a>
    </div>

    {{-- Module Snapshots Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

        {{-- Tahfizh --}}
        <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm hover:shadow-md transition-all dark:border-slate-800 dark:bg-slate-900">
            <div class="flex items-center gap-3 mb-4">
                <div class="rounded-xl bg-emerald-50 p-2.5 text-emerald-500 dark:bg-emerald-950/50">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253" />
                    </svg>
                </div>
                <h2 class="font-bold text-slate-800 dark:text-slate-100">Tahfizh</h2>
            </div>
            <dl class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <dt class="text-slate-500">Total Setoran</dt>
                    <dd class="font-semibold text-slate-800 dark:text-slate-100">{{ number_format($snapshot['tahfizh']['records_count']) }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-slate-500">Total Baris</dt>
                    <dd class="font-semibold text-slate-800 dark:text-slate-100">{{ number_format($snapshot['tahfizh']['total_lines']) }}</dd>
                </div>
                @if($snapshot['tahfizh']['latest_record'])
                    <div class="flex justify-between">
                        <dt class="text-slate-500">Terakhir</dt>
                        <dd class="font-semibold text-slate-800 dark:text-slate-100">
                            {{ $snapshot['tahfizh']['latest_record']->record_date?->format('d M Y') ?? '-' }}
                        </dd>
                    </div>
                @endif
            </dl>
        </div>

        {{-- Mutabaah --}}
        <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm hover:shadow-md transition-all dark:border-slate-800 dark:bg-slate-900">
            <div class="flex items-center gap-3 mb-4">
                <div class="rounded-xl bg-sky-50 p-2.5 text-sky-500 dark:bg-sky-950/50">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                </div>
                <h2 class="font-bold text-slate-800 dark:text-slate-100">Mutabaah Hari Ini</h2>
            </div>
            <dl class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <dt class="text-slate-500">Aktivitas Hari Ini</dt>
                    <dd class="font-semibold text-slate-800 dark:text-slate-100">{{ $snapshot['mutabaah']['records_today'] }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-slate-500">Selesai</dt>
                    <dd class="font-semibold text-emerald-600 dark:text-emerald-400">{{ $snapshot['mutabaah']['done_today'] }}</dd>
                </div>
            </dl>
            @if($snapshot['mutabaah']['records_today'] > 0)
                <div class="mt-3">
                    <div class="h-2 rounded-full bg-slate-100 dark:bg-slate-800">
                        <div class="h-2 rounded-full bg-emerald-500 transition-all"
                            style="width: {{ min(100, round($snapshot['mutabaah']['done_today'] / max(1, $snapshot['mutabaah']['records_today']) * 100)) }}%">
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Attendance --}}
        <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm hover:shadow-md transition-all dark:border-slate-800 dark:bg-slate-900">
            <div class="flex items-center gap-3 mb-4">
                <div class="rounded-xl bg-violet-50 p-2.5 text-violet-500 dark:bg-violet-950/50">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <h2 class="font-bold text-slate-800 dark:text-slate-100">Presensi Bulan Ini</h2>
            </div>
            <dl class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <dt class="text-slate-500">Tercatat</dt>
                    <dd class="font-semibold text-slate-800 dark:text-slate-100">{{ $snapshot['attendance']['records_this_month'] }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-slate-500">Terlambat</dt>
                    <dd class="font-semibold text-amber-600 dark:text-amber-400">{{ $snapshot['attendance']['late_this_month'] }}</dd>
                </div>
            </dl>
        </div>

        {{-- Tahsin --}}
        <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm hover:shadow-md transition-all dark:border-slate-800 dark:bg-slate-900">
            <div class="flex items-center gap-3 mb-4">
                <div class="rounded-xl bg-amber-50 p-2.5 text-amber-500 dark:bg-amber-950/50">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l.707-.707m2.828 9.9a5 5 0 113.62 0h-3.62z" />
                    </svg>
                </div>
                <h2 class="font-bold text-slate-800 dark:text-slate-100">Tahsin</h2>
            </div>
            <dl class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <dt class="text-slate-500">Total Asesmen</dt>
                    <dd class="font-semibold text-slate-800 dark:text-slate-100">{{ $snapshot['tahsin']['assessments_count'] }}</dd>
                </div>
                @if($snapshot['tahsin']['latest_assessment'])
                    <div class="flex justify-between">
                        <dt class="text-slate-500">Terakhir Dinilai</dt>
                        <dd class="font-semibold text-slate-800 dark:text-slate-100">
                            {{ $snapshot['tahsin']['latest_assessment']->assessment_date?->format('d M Y') ?? '-' }}
                        </dd>
                    </div>
                @endif
            </dl>
        </div>

        {{-- Finance --}}
        <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm hover:shadow-md transition-all dark:border-slate-800 dark:bg-slate-900 sm:col-span-2 lg:col-span-1">
            <div class="flex items-center gap-3 mb-4">
                <div class="rounded-xl bg-rose-50 p-2.5 text-rose-500 dark:bg-rose-950/50">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h2 class="font-bold text-slate-800 dark:text-slate-100">Keuangan</h2>
            </div>
            <dl class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <dt class="text-slate-500">Total Tagihan</dt>
                    <dd class="font-semibold text-rose-600 dark:text-rose-400">Rp {{ number_format($snapshot['finance']['debit'], 0, ',', '.') }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-slate-500">Total Bayar</dt>
                    <dd class="font-semibold text-emerald-600 dark:text-emerald-400">Rp {{ number_format($snapshot['finance']['credit'], 0, ',', '.') }}</dd>
                </div>
                <div class="flex justify-between border-t border-slate-100 pt-2 dark:border-slate-800">
                    <dt class="font-semibold text-slate-700 dark:text-slate-300">Sisa Tagihan</dt>
                    <dd class="font-bold {{ $snapshot['finance']['balance'] > 0 ? 'text-rose-600 dark:text-rose-400' : 'text-emerald-600 dark:text-emerald-400' }}">
                        Rp {{ number_format($snapshot['finance']['balance'], 0, ',', '.') }}
                    </dd>
                </div>
            </dl>
        </div>

    </div>

    {{-- Student Info Card --}}
    <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <h2 class="text-lg font-bold text-slate-800 dark:text-slate-100 mb-4">Informasi Santri</h2>
        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div>
                <dt class="text-slate-500">Nama Lengkap</dt>
                <dd class="mt-0.5 font-semibold text-slate-800 dark:text-slate-100">{{ $student->full_name ?? '-' }}</dd>
            </div>
            <div>
                <dt class="text-slate-500">Nama Panggilan</dt>
                <dd class="mt-0.5 font-semibold text-slate-800 dark:text-slate-100">{{ $student->nickname ?? '-' }}</dd>
            </div>
            <div>
                <dt class="text-slate-500">Kelas</dt>
                <dd class="mt-0.5 font-semibold text-slate-800 dark:text-slate-100">{{ $student->classRoom?->name ?? '-' }}</dd>
            </div>
            <div>
                <dt class="text-slate-500">Jenis Kelamin</dt>
                <dd class="mt-0.5 font-semibold text-slate-800 dark:text-slate-100">
                    {{ $student->gender === 'male' ? 'Laki-laki' : ($student->gender === 'female' ? 'Perempuan' : '-') }}
                </dd>
            </div>
            <div>
                <dt class="text-slate-500">Nomor Santri</dt>
                <dd class="mt-0.5 font-semibold text-slate-800 dark:text-slate-100">{{ $student->student_number ?? '-' }}</dd>
            </div>
            <div>
                <dt class="text-slate-500">NISN</dt>
                <dd class="mt-0.5 font-semibold text-slate-800 dark:text-slate-100">{{ $student->nisn ?? '-' }}</dd>
            </div>
            @if($student->parents->isNotEmpty())
                <div class="md:col-span-2">
                    <dt class="text-slate-500">Orang Tua / Wali</dt>
                    <dd class="mt-1 flex flex-wrap gap-2">
                        @foreach($student->parents as $parent)
                            <span class="rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-700 dark:bg-indigo-950/50 dark:text-indigo-300">
                                {{ $parent->user?->name ?? 'Wali #'.$parent->id }}
                                @if($pivot = $parent->pivot)
                                    ({{ ucfirst($pivot->relationship ?? 'wali') }})
                                @endif
                            </span>
                        @endforeach
                    </dd>
                </div>
            @endif
        </dl>
    </div>

</div>
@endsection
