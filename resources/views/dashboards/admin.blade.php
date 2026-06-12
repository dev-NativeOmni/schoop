@extends('layouts.app')

@section('content')
    <div class="space-y-8">
        <!-- Header Welcome -->
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 p-8 text-white shadow-lg shadow-indigo-950/20">
            <div class="absolute -right-10 -top-10 h-40 w-40 rounded-full bg-indigo-500/10 blur-xl"></div>
            <div class="relative z-10">
                <h1 class="text-3xl font-extrabold tracking-tight">Selamat Datang di Portal Admin!</h1>
                <p class="mt-2 text-slate-350 max-w-xl">
                    Kelola data kelas, siswa, guru pengajar, dan pantau perkembangan Halaqah Al-Qur'an secara real-time.
                </p>
            </div>
        </div>

        <!-- Metrics Grid -->
        <div class="grid gap-6 sm:grid-cols-3">
            <!-- Kelas -->
            <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 transition hover:shadow-md">
                <div class="flex items-center space-x-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 text-blue-600 dark:bg-blue-950/30 dark:text-blue-400">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-slate-500">Total Kelas</p>
                        <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $stats['total_classrooms'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Siswa -->
            <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 transition hover:shadow-md">
                <div class="flex items-center space-x-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 dark:bg-indigo-950/30 dark:text-indigo-400">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-slate-500">Total Siswa (Santri)</p>
                        <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $stats['total_students'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Guru -->
            <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 transition hover:shadow-md">
                <div class="flex items-center space-x-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 dark:bg-emerald-950/30 dark:text-emerald-400">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v12a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-slate-500">Total Guru</p>
                        <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $stats['total_teachers'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Access -->
        <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4">Akses Cepat Panel Kontrol</h3>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('tahfizh.hafalan-records.index') }}"
                   class="inline-flex items-center space-x-2 rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-700 shadow-sm transition dark:bg-slate-850 dark:hover:bg-slate-800">
                    <span>Lihat Setoran Tahfizh</span>
                </a>

                <a href="{{ route('reports.tahfizh.dashboard') }}"
                   class="inline-flex items-center space-x-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700 shadow-sm transition">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                    <span>Dashboard Tahfizh</span>
                </a>

                <a href="{{ route('reports.tahfizh.monthly.index') }}"
                   class="inline-flex items-center space-x-2 rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50 dark:border-slate-700 dark:text-slate-350 dark:hover:bg-slate-800 transition">
                    <span>Laporan Bulanan</span>
                </a>

                <a href="{{ route('reports.tahfizh.quarterly.index') }}"
                   class="inline-flex items-center space-x-2 rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50 dark:border-slate-700 dark:text-slate-350 dark:hover:bg-slate-800 transition">
                    <span>Laporan Triwulan</span>
                </a>
            </div>
        </div>
    </div>
@endsection
