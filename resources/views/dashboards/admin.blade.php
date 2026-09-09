@extends('layouts.app')

@section('content')
    <div class="space-y-8">
        <!-- Header Welcome -->
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-slate-900 via-slate-850 to-indigo-950 p-8 text-white shadow-xl shadow-slate-900/20">
            <!-- Decorative Glow Bubbles -->
            <div class="absolute -right-10 -top-10 h-40 w-40 rounded-full bg-indigo-500/10 blur-2xl"></div>
            <div class="absolute -left-10 -bottom-10 h-40 w-40 rounded-full bg-emerald-500/5 blur-2xl"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div>
                    <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-white/10 text-xs font-semibold text-indigo-200 backdrop-blur-md mb-3 border border-white/5">
                        <span class="flex h-2 w-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span>Sistem Aktif</span>
                    </div>
                    <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight bg-gradient-to-r from-white via-slate-100 to-indigo-200 bg-clip-text text-transparent">Selamat Datang di Portal Admin!</h1>
                    <p class="mt-2 text-slate-300 max-w-xl leading-relaxed text-sm">
                        Kelola data kelas, siswa, guru pengajar, dan pantau perkembangan Halaqah Al-Qur'an secara real-time melalui panel kendali utama.
                    </p>
                </div>
                
                <!-- System Quick Clock/Date -->
                <div class="flex items-center space-x-4 bg-white/5 border border-white/10 p-4 rounded-2xl backdrop-blur-md self-start md:self-auto">
                    <div class="p-3 bg-indigo-500/10 text-indigo-300 rounded-xl">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-medium">Hari Ini</p>
                        <p class="text-sm font-bold text-white">{{ now()->isoFormat('D MMMM YYYY') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Metrics Grid -->
        <div class="grid gap-6 sm:grid-cols-3">
            <!-- Kelas -->
            <div class="group relative overflow-hidden rounded-2xl border border-slate-100 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
                <div class="absolute top-0 left-0 w-full h-1 bg-blue-500"></div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 text-blue-600 dark:bg-blue-950/30 dark:text-blue-400 group-hover:scale-110 transition-transform duration-300">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Total Kelas</p>
                            <p class="text-3xl font-extrabold text-slate-900 dark:text-white mt-1">{{ $stats['total_classrooms'] }}</p>
                        </div>
                    </div>
                    <span class="text-xs font-medium text-slate-400 bg-slate-50 dark:bg-slate-850 px-2.5 py-1 rounded-full">Halaqah</span>
                </div>
            </div>

            <!-- Siswa -->
            <div class="group relative overflow-hidden rounded-2xl border border-slate-100 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
                <div class="absolute top-0 left-0 w-full h-1 bg-indigo-500"></div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 dark:bg-indigo-950/30 dark:text-indigo-400 group-hover:scale-110 transition-transform duration-300">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Total Santri</p>
                            <p class="text-3xl font-extrabold text-slate-900 dark:text-white mt-1">{{ $stats['total_students'] }}</p>
                        </div>
                    </div>
                    <span class="text-xs font-medium text-slate-400 bg-slate-50 dark:bg-slate-850 px-2.5 py-1 rounded-full">Siswa aktif</span>
                </div>
            </div>

            <!-- Guru -->
            <div class="group relative overflow-hidden rounded-2xl border border-slate-100 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
                <div class="absolute top-0 left-0 w-full h-1 bg-emerald-500"></div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 dark:bg-emerald-950/30 dark:text-emerald-400 group-hover:scale-110 transition-transform duration-300">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v12a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Total Guru</p>
                            <p class="text-3xl font-extrabold text-slate-900 dark:text-white mt-1">{{ $stats['total_teachers'] }}</p>
                        </div>
                    </div>
                    <span class="text-xs font-medium text-slate-400 bg-slate-50 dark:bg-slate-850 px-2.5 py-1 rounded-full">Asatidzah</span>
                </div>
            </div>
        </div>

        <!-- Quick Access Section -->
        <div>
            <h3 class="text-lg font-bold text-slate-800 dark:text-slate-200 mb-5 flex items-center space-x-2">
                <svg class="h-5 w-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
                <span>Akses Cepat Panel Kontrol</span>
            </h3>
            
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                <!-- Setoran Tahfizh -->
                <a href="{{ route('tahfizh.hafalan-records.index') }}"
                   class="group flex flex-col justify-between p-5 bg-white border border-gray-100 rounded-2xl shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300 dark:bg-gray-900 dark:border-gray-800">
                    <div>
                        <div class="inline-flex p-3 bg-blue-50 text-blue-600 rounded-xl group-hover:bg-blue-500 group-hover:text-white transition-colors duration-300 dark:bg-blue-950/30 dark:text-blue-400">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                            </svg>
                        </div>
                        <h4 class="font-bold text-slate-900 dark:text-white mt-4 group-hover:text-indigo-650 dark:group-hover:text-indigo-400 transition-colors">Setoran Tahfizh</h4>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1.5 leading-relaxed">
                            Pantau riwayat setoran hafalan santri secara komprehensif.
                        </p>
                    </div>
                    <div class="mt-4 flex items-center text-xs font-bold text-indigo-500 group-hover:translate-x-1 transition-transform duration-300">
                        <span>Buka Riwayat</span>
                        <svg class="h-3.5 w-3.5 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                    </div>
                </a>

                <!-- Dashboard Tahfizh -->
                <a href="{{ route('reports.tahfizh.dashboard') }}"
                   class="group flex flex-col justify-between p-5 bg-white border border-gray-100 rounded-2xl shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300 dark:bg-gray-900 dark:border-gray-800">
                    <div>
                        <div class="inline-flex p-3 bg-indigo-50 text-indigo-600 rounded-xl group-hover:bg-indigo-500 group-hover:text-white transition-colors duration-300 dark:bg-indigo-950/30 dark:text-indigo-400">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <h4 class="font-bold text-slate-900 dark:text-white mt-4 group-hover:text-indigo-650 dark:group-hover:text-indigo-400 transition-colors">Dasbor Tahfizh</h4>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1.5 leading-relaxed">
                            Analisis grafik & statistik pencapaian hafalan seluruh Halaqah.
                        </p>
                    </div>
                    <div class="mt-4 flex items-center text-xs font-bold text-indigo-500 group-hover:translate-x-1 transition-transform duration-300">
                        <span>Buka Analisis</span>
                        <svg class="h-3.5 w-3.5 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                    </div>
                </a>

                <!-- Laporan Bulanan -->
                <a href="{{ route('reports.tahfizh.monthly.index') }}"
                   class="group flex flex-col justify-between p-5 bg-white border border-gray-100 rounded-2xl shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300 dark:bg-gray-900 dark:border-gray-800">
                    <div>
                        <div class="inline-flex p-3 bg-emerald-50 text-emerald-600 rounded-xl group-hover:bg-emerald-500 group-hover:text-white transition-colors duration-300 dark:bg-emerald-950/30 dark:text-emerald-400">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h4 class="font-bold text-slate-900 dark:text-white mt-4 group-hover:text-indigo-650 dark:group-hover:text-indigo-400 transition-colors">Laporan Bulanan</h4>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1.5 leading-relaxed">
                            Lihat dan ekspor rangkuman laporan capaian bulanan siswa.
                        </p>
                    </div>
                    <div class="mt-4 flex items-center text-xs font-bold text-indigo-500 group-hover:translate-x-1 transition-transform duration-300">
                        <span>Lihat Laporan</span>
                        <svg class="h-3.5 w-3.5 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                    </div>
                </a>

                <!-- Laporan Triwulan -->
                <a href="{{ route('reports.tahfizh.quarterly.index') }}"
                   class="group flex flex-col justify-between p-5 bg-white border border-gray-100 rounded-2xl shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300 dark:bg-gray-900 dark:border-gray-800">
                    <div>
                        <div class="inline-flex p-3 bg-amber-50 text-amber-600 rounded-xl group-hover:bg-amber-500 group-hover:text-white transition-colors duration-300 dark:bg-amber-950/30 dark:text-amber-400">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 3.055A9.003 9.003 0 1020.945 13H11V3.055z" />
                            </svg>
                        </div>
                        <h4 class="font-bold text-slate-900 dark:text-white mt-4 group-hover:text-indigo-650 dark:group-hover:text-indigo-400 transition-colors">Laporan Triwulan</h4>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1.5 leading-relaxed">
                            Lihat dan ekspor rangkuman laporan capaian triwulan siswa.
                        </p>
                    </div>
                    <div class="mt-4 flex items-center text-xs font-bold text-indigo-500 group-hover:translate-x-1 transition-transform duration-300">
                        <span>Lihat Laporan</span>
                        <svg class="h-3.5 w-3.5 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection
