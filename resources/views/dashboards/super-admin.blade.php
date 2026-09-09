@extends('layouts.app')

@section('content')
<div class="space-y-8 animate-fade-in">
    <!-- Super Admin Bento Hero: Command Center Header -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-slate-900 via-slate-900 to-slate-950 border border-slate-800 p-8 sm:p-10 text-white shadow-2xl shadow-slate-950/40">
        <!-- Ambient Decorative Glows -->
        <div class="absolute -right-16 -top-16 h-64 w-64 rounded-full bg-emerald-500/15 blur-3xl pointer-events-none"></div>
        <div class="absolute -left-16 -bottom-16 h-64 w-64 rounded-full bg-teal-500/10 blur-3xl pointer-events-none"></div>
        
        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div class="space-y-3 max-w-2xl">
                <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-bold tracking-wide backdrop-blur-md">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>Schoop Multi-Tenant Platform Active</span>
                </div>
                <h1 class="text-3xl sm:text-4xl font-black tracking-tight text-white">
                    Super Admin <span class="bg-gradient-to-r from-emerald-400 to-teal-300 bg-clip-text text-transparent">Command Center</span>
                </h1>
                <p class="text-sm sm:text-base text-slate-300 leading-relaxed">
                    Pusat orkestrasi institusi, isolasi tenant, pengelolaan master data santri & guru, serta pengawasan operasional seluruh ekosistem sekolah.
                </p>
            </div>

            <!-- Quick Action Hub in Hero -->
            <div class="flex flex-wrap items-center gap-3 shrink-0">
                <a href="{{ route('master-data.schools.index') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-500 text-slate-950 font-bold text-sm shadow-lg shadow-emerald-500/25 hover:bg-emerald-400 hover:scale-[1.02] active:scale-[0.98] transition duration-200">
                    <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Tambah Sekolah</span>
                </a>
                <a href="{{ route('tenancy.switcher') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-800/90 text-white font-semibold text-sm border border-slate-700 hover:bg-slate-700/80 hover:border-slate-600 transition duration-200">
                    <svg class="w-4.5 h-4.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                    </svg>
                    <span>Ganti Tenant</span>
                </a>
            </div>
        </div>
    </div>

    <!-- BENTO GRID SECTION -->
    <div class="grid grid-cols-1 md:grid-cols-12 gap-6">

        <!-- Bento Card 1: Total Sekolah & Tenant Landscape (Span 7) -->
        <div class="md:col-span-7 rounded-3xl border border-slate-200/80 bg-white p-7 shadow-xs dark:border-slate-800 dark:bg-slate-900 transition duration-200 hover:shadow-md flex flex-col justify-between relative overflow-hidden">
            <div class="absolute -right-8 -bottom-8 w-44 h-44 rounded-full bg-emerald-500/5 dark:bg-emerald-500/10 blur-2xl pointer-events-none"></div>

            <div>
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 dark:bg-emerald-950/50 dark:text-emerald-400 flex items-center justify-center font-bold">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-slate-900 dark:text-white">Ekosistem Sekolah & Lembaga</h2>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Total institusi dan pesantren yang terhubung</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/60">
                        Multi-Tenant
                    </span>
                </div>

                <div class="mt-6 mb-4">
                    <div class="flex items-baseline gap-3">
                        <span class="text-4xl sm:text-5xl font-black text-slate-900 dark:text-white tracking-tight">
                            {{ number_format($stats['total_schools']) }}
                        </span>
                        <span class="text-sm font-semibold text-slate-500 dark:text-slate-400">sekolah aktif</span>
                    </div>
                    <p class="mt-2 text-xs sm:text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                        Setiap sekolah beroperasi dengan basis data terisolasi, subdomain kustom, branding mandiri (White-Label), dan konfigurasi kurikulum otonom.
                    </p>
                </div>
            </div>

            <div class="pt-5 border-t border-slate-100 dark:border-slate-800 flex flex-wrap items-center justify-between gap-3">
                <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span>Database Isolation Engine: Active</span>
                </div>
                <a href="{{ route('master-data.schools.index') }}" class="text-xs font-bold text-emerald-600 hover:text-emerald-500 dark:text-emerald-400 dark:hover:text-emerald-300 inline-flex items-center gap-1 group">
                    <span>Buka Master Sekolah</span>
                    <svg class="w-3.5 h-3.5 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                </a>
            </div>
        </div>

        <!-- Bento Card 2: Pengguna, Santri & Asatidzah (Span 5) -->
        <div class="md:col-span-5 rounded-3xl border border-slate-200/80 bg-white p-7 shadow-xs dark:border-slate-800 dark:bg-slate-900 transition duration-200 hover:shadow-md flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-5">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-600 dark:bg-teal-950/50 dark:text-teal-400 flex items-center justify-center font-bold">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-slate-900 dark:text-white">Populasi Pengguna</h2>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Total entitas aktif di platform</p>
                        </div>
                    </div>
                    <span class="text-2xl font-black text-slate-900 dark:text-white">
                        {{ number_format($stats['total_users']) }}
                    </span>
                </div>

                <!-- Mini Breakdown Stats inside Bento -->
                <div class="space-y-3 mt-4">
                    <a href="{{ route('master-data.students.index') }}" class="flex items-center justify-between p-3.5 rounded-2xl bg-slate-50 hover:bg-slate-100 dark:bg-slate-800/60 dark:hover:bg-slate-800 border border-slate-200/60 dark:border-slate-700/60 transition group">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 dark:bg-emerald-950/80 dark:text-emerald-300 flex items-center justify-center text-xs font-black">
                                ST
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-800 dark:text-slate-200">Santri Terdaftar</p>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400">Siswa aktif semua sekolah</p>
                            </div>
                        </div>
                        <span class="text-lg font-black text-slate-900 dark:text-white group-hover:text-emerald-500 transition">
                            {{ number_format($stats['total_students']) }}
                        </span>
                    </a>

                    <a href="{{ route('master-data.teachers.index') }}" class="flex items-center justify-between p-3.5 rounded-2xl bg-slate-50 hover:bg-slate-100 dark:bg-slate-800/60 dark:hover:bg-slate-800 border border-slate-200/60 dark:border-slate-700/60 transition group">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-teal-100 text-teal-700 dark:bg-teal-950/80 dark:text-teal-300 flex items-center justify-center text-xs font-black">
                                TC
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-800 dark:text-slate-200">Pengajar / Asatidzah</p>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400">Guru tahfizh & pembina</p>
                            </div>
                        </div>
                        <span class="text-lg font-black text-slate-900 dark:text-white group-hover:text-teal-500 transition">
                            {{ number_format($stats['total_teachers']) }}
                        </span>
                    </a>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 dark:border-slate-800 text-right mt-4">
                <a href="{{ route('master-data.users.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white inline-flex items-center gap-1">
                    <span>Lihat Semua Akun Pengguna</span>
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                </a>
            </div>
        </div>

        <!-- Bento Card 3: Executive Reporting Hub (Span 6) -->
        <div class="md:col-span-6 rounded-3xl border border-slate-200/80 bg-white p-7 shadow-xs dark:border-slate-800 dark:bg-slate-900 transition duration-200 hover:shadow-md flex flex-col justify-between">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 dark:bg-indigo-950/50 dark:text-indigo-400 flex items-center justify-center font-bold">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-slate-900 dark:text-white">Pusat Laporan & Monitoring Global</h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Pantau setoran tahfizh, mutabaah & progres berkala</p>
                    </div>
                </div>

                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mt-3 mb-5 leading-relaxed">
                    Akses ringkasan lintas lembaga untuk monitoring hafalan juz, ketuntasan target santri, dan laporan performa triwulan secara konsolidasi.
                </p>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <a href="{{ route('reports.tahfizh.dashboard') }}"
                       class="flex flex-col p-4 rounded-2xl bg-emerald-50/60 hover:bg-emerald-50 border border-emerald-100 text-emerald-900 dark:bg-emerald-950/30 dark:hover:bg-emerald-950/50 dark:border-emerald-800/40 dark:text-emerald-200 transition group">
                        <span class="text-xs font-semibold text-emerald-600 dark:text-emerald-400 mb-1">Tahfizh</span>
                        <span class="text-sm font-bold group-hover:underline">Dashboard Utama</span>
                        <span class="text-[11px] text-emerald-600/70 dark:text-emerald-400/70 mt-2">Ringkasan harian &rarr;</span>
                    </a>

                    <a href="{{ route('reports.tahfizh.monthly.index') }}"
                       class="flex flex-col p-4 rounded-2xl bg-slate-50 hover:bg-slate-100 border border-slate-200/70 text-slate-800 dark:bg-slate-800/40 dark:hover:bg-slate-800/70 dark:border-slate-700/60 dark:text-slate-200 transition group">
                        <span class="text-xs font-semibold text-slate-400 mb-1">Bulanan</span>
                        <span class="text-sm font-bold group-hover:underline">Laporan Rekap</span>
                        <span class="text-[11px] text-slate-400 mt-2">Arsip per bulan &rarr;</span>
                    </a>

                    <a href="{{ route('reports.tahfizh.quarterly.index') }}"
                       class="flex flex-col p-4 rounded-2xl bg-slate-50 hover:bg-slate-100 border border-slate-200/70 text-slate-800 dark:bg-slate-800/40 dark:hover:bg-slate-800/70 dark:border-slate-700/60 dark:text-slate-200 transition group">
                        <span class="text-xs font-semibold text-slate-400 mb-1">Triwulan</span>
                        <span class="text-sm font-bold group-hover:underline">Evaluasi Santri</span>
                        <span class="text-[11px] text-slate-400 mt-2">Progres semester &rarr;</span>
                    </a>
                </div>
            </div>

            <div class="pt-5 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between text-xs text-slate-500 dark:text-slate-400 mt-5">
                <span>Ekspor format: PDF & Cetak A4</span>
                <span class="font-semibold text-emerald-600 dark:text-emerald-400">Siap Cetak</span>
            </div>
        </div>

        <!-- Bento Card 4: Global Brand Logo Customizer (Span 6) -->
        <div class="md:col-span-6 rounded-3xl border border-slate-200/80 bg-white p-7 shadow-xs dark:border-slate-800 dark:bg-slate-900 transition duration-200 hover:shadow-md flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 dark:bg-purple-950/50 dark:text-purple-400 flex items-center justify-center font-bold">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-slate-900 dark:text-white">Branding & Logo Global</h2>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Digunakan sebagai default fallback platform</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300">
                        System Level
                    </span>
                </div>

                @php
                    $globalLogoExists = \Illuminate\Support\Facades\Storage::disk('public')->exists('system/logo.png');
                    $globalLogoUrl = $globalLogoExists ? asset('storage/system/logo.png') : null;
                @endphp

                <form action="{{ route('super-admin.update-logo') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div class="flex items-center gap-5 p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/40 border border-slate-200/60 dark:border-slate-700/60">
                        <div class="shrink-0">
                            @if ($globalLogoUrl)
                                <div class="p-2.5 bg-slate-800 rounded-2xl border border-slate-700 shadow-inner">
                                    <img id="logo-preview" class="h-14 w-14 object-contain" src="{{ $globalLogoUrl }}" alt="Global Logo">
                                </div>
                            @else
                                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-400 to-teal-600 text-white shadow-md shadow-emerald-500/25 font-black text-lg">
                                    SC
                                </div>
                            @endif
                        </div>
                        
                        <div class="flex-1 min-w-0">
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                                Unggah File Logo
                            </label>
                            <input type="file" 
                                   name="logo" 
                                   id="logo_input"
                                   accept="image/*" 
                                   class="block w-full text-xs text-slate-500 dark:text-slate-400
                                          file:mr-3 file:py-1.5 file:px-3.5
                                          file:rounded-xl file:border-0
                                          file:text-xs file:font-bold
                                          file:bg-emerald-50 file:text-emerald-700
                                          hover:file:bg-emerald-100
                                          dark:file:bg-emerald-950/60 dark:file:text-emerald-300
                                          cursor-pointer" />
                            <p class="text-[11px] text-slate-400 mt-1 truncate">PNG, JPG, WEBP, atau SVG (Maks. 2MB)</p>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-3">
                        <div>
                            @if ($globalLogoExists)
                                <button type="submit" name="delete_logo" value="1" 
                                        class="text-xs text-rose-500 hover:text-rose-600 font-bold transition inline-flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Hapus Logo Kustom
                                </button>
                            @endif
                        </div>
                        
                        <button type="submit" 
                                class="px-5 py-2.5 text-xs font-bold text-slate-950 bg-emerald-400 hover:bg-emerald-300 rounded-xl shadow-md shadow-emerald-500/20 active:scale-[0.98] transition">
                            Simpan Logo
                        </button>
                    </div>
                </form>
            </div>

            <div class="pt-4 border-t border-slate-100 dark:border-slate-800 text-xs text-slate-400 mt-2">
                Logo ini tampil pada layar Login default dan berkas cetak yang tidak di-override oleh sekolah.
            </div>
        </div>

        <!-- Bento Card 5: Platform Health Status Bar (Span 12) -->
        <div class="md:col-span-12 rounded-2xl border border-slate-200/80 bg-white dark:border-slate-800 dark:bg-slate-900 px-6 py-4 flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <span class="flex h-3 w-3 relative">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                </span>
                <span class="text-xs font-bold text-slate-800 dark:text-slate-200">Kesehatan Platform: Seluruh Layanan Beroperasi Normal</span>
            </div>
            <div class="flex flex-wrap items-center gap-4 text-xs font-medium text-slate-500 dark:text-slate-400">
                <span class="inline-flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    Tenancy Engine v2.0
                </span>
                <span class="inline-flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    Cache Optimized
                </span>
                <span class="inline-flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    Role-Based Access Control
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Instant Logo Preview Script -->
<script>
    const logoInput = document.getElementById('logo_input');
    if (logoInput) {
        logoInput.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (event) {
                    const preview = document.getElementById('logo-preview');
                    if (preview) {
                        preview.src = event.target.result;
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    }
</script>
@endsection
