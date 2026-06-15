@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-6">
    <x-analytics.shell title="School Analytics Dashboard" subtitle="Overview analitik sekolah terintegrasi untuk modular Tahfizh, Absensi, Mutabaah, dan Keuangan." :schoolId="$schoolId">
        <!-- Health Card -->
        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-lg font-black text-slate-950 dark:text-white">Skor Kesehatan Sekolah</h2>
                    <p class="mt-1 text-sm text-slate-500">Berdasarkan adopsi modul, pencapaian akademik, ketepatan absensi, dan interaksi orang tua.</p>
                </div>
                <div class="flex items-center gap-4">
                    <span class="text-4xl font-black text-slate-950 dark:text-white">{{ $stats['health_score'] }}/100</span>
                    <span class="rounded-lg px-3 py-1 text-xs font-bold uppercase tracking-wide
                        @if($stats['health_status'] === 'healthy') bg-lime-100 text-lime-800 dark:bg-lime-950/40 dark:text-lime-400
                        @elseif($stats['health_status'] === 'watch') bg-amber-100 text-amber-800 dark:bg-amber-950/40 dark:text-amber-400
                        @else bg-red-100 text-red-800 dark:bg-red-950/40 dark:text-red-400
                        @endif">
                        {{ $stats['health_status'] }}
                    </span>
                </div>
            </div>
            <div class="mt-4 rounded-lg bg-slate-50 p-4 dark:bg-slate-850">
                <p class="text-xs font-black uppercase text-slate-500">Rekomendasi Tindakan</p>
                <ul class="mt-2 list-disc pl-5 text-sm text-slate-700 dark:text-slate-350">
                    @foreach($stats['health_recommendations'] as $rec)
                        <li>{{ $rec }}</li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <p class="text-xs font-black uppercase text-slate-500">Tahfizh Achievement</p>
                <p class="mt-3 text-3xl font-black text-slate-950 dark:text-white">{{ $stats['tahfizh_achievement'] }}%</p>
                <p class="mt-1 text-xs text-slate-500">{{ $stats['students_behind_target'] }} santri tertinggal target</p>
            </div>
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <p class="text-xs font-black uppercase text-slate-500">Attendance Rate</p>
                <p class="mt-3 text-3xl font-black text-slate-950 dark:text-white">{{ $stats['attendance_rate'] }}%</p>
                <p class="mt-1 text-xs text-slate-500">Late rate: {{ $stats['late_rate'] }}%</p>
            </div>
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <p class="text-xs font-black uppercase text-slate-500">Mutabaah Completion</p>
                <p class="mt-3 text-3xl font-black text-slate-950 dark:text-white">{{ $stats['mutabaah_completion'] }}%</p>
                <p class="mt-1 text-xs text-slate-500">Pengisian harian santri aktif</p>
            </div>
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <p class="text-xs font-black uppercase text-slate-500">Outstanding Finance</p>
                <p class="mt-3 text-3xl font-black text-slate-950 dark:text-white">Rp {{ number_format($stats['outstanding_finance']) }}</p>
            </div>
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <p class="text-xs font-black uppercase text-slate-500">Cashless Purchases</p>
                <p class="mt-3 text-3xl font-black text-slate-950 dark:text-white">Rp {{ number_format($stats['cashless_purchases']) }}</p>
            </div>
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <p class="text-xs font-black uppercase text-slate-500">Average Tahsin Score</p>
                <p class="mt-3 text-3xl font-black text-slate-950 dark:text-white">{{ $stats['tahsin_score'] }}</p>
            </div>
        </div>
    </x-analytics.shell>
</div>
@endsection
