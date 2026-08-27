@extends('layouts.app')

@section('content')
    <div class="space-y-8">
        <!-- Header Welcome -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-slate-900 via-emerald-950/70 to-slate-900 p-8 text-white shadow-xl shadow-emerald-950/15 border border-emerald-900/30">
            <div class="absolute -right-10 -top-10 h-48 w-48 rounded-full bg-emerald-500/10 blur-3xl"></div>
            <div class="relative z-10">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-xs font-semibold text-emerald-300 mb-3">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                    Platform Control Hub
                </div>
                <h1 class="text-3xl font-extrabold tracking-tight">Selamat Datang, Super Admin!</h1>
                <p class="mt-2 text-slate-300 max-w-xl text-sm leading-relaxed">
                    Kelola seluruh konfigurasi sekolah, master data, dan pantau performa aktivitas secara global di HafizPlus.
                </p>
            </div>
        </div>

        <!-- Metrics Grid -->
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            <!-- Sekolah -->
            <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 transition hover:shadow-md">
                <div class="flex items-center space-x-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 text-blue-600 dark:bg-blue-950/30 dark:text-blue-400">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-slate-500">Total Sekolah</p>
                        <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $stats['total_schools'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Users -->
            <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 transition hover:shadow-md">
                <div class="flex items-center space-x-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-purple-50 text-purple-600 dark:bg-purple-950/30 dark:text-purple-400">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-slate-500">Total Pengguna</p>
                        <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $stats['total_users'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Students -->
            <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 transition hover:shadow-md">
                <div class="flex items-center space-x-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 dark:bg-indigo-950/30 dark:text-indigo-400">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-slate-500">Total Santri</p>
                        <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $stats['total_students'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Teachers -->
            <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 transition hover:shadow-md">
                <div class="flex items-center space-x-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 dark:bg-emerald-950/30 dark:text-emerald-400">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v12a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-slate-500">Total Pengajar</p>
                        <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $stats['total_teachers'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Access -->
        <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4">Akses Cepat Monitoring Global</h3>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('reports.tahfizh.dashboard') }}"
                   class="inline-flex items-center space-x-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700 shadow-sm transition">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                    <span>Dashboard Laporan Tahfizh</span>
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

        <!-- Logo & Branding settings (Super Admin) -->
        <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">Pengaturan Logo Global</h3>
            <p class="text-sm text-slate-500 mb-6">Logo kustom global ini digunakan sebagai logo default jika brand sekolah belum mengunggah logo kustom mereka.</p>
            
            <form action="{{ route('super-admin.update-logo') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <div class="flex flex-col sm:flex-row items-center gap-6">
                    <div class="shrink-0" id="logo-preview-container">
                        @php
                            $globalLogoExists = \Illuminate\Support\Facades\Storage::disk('public')->exists('system/logo.png')
                                || \App\Models\SystemAsset::has('system/logo.png');
                            $globalLogoUrl = $globalLogoExists ? \App\Models\SystemAsset::url('system/logo.png') : null;
                        @endphp
                        @if ($globalLogoUrl)
                            <div class="p-3 bg-slate-800/80 rounded-2xl border border-slate-700/50 shadow-inner">
                                <img id="logo-preview" class="h-16 w-16 object-contain" src="{{ $globalLogoUrl }}" alt="Global Logo">
                            </div>
                        @else
                            <div id="logo-default-icon" class="flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-700 text-white shadow-md shadow-emerald-900/30">
                                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                        @endif
                    </div>
                    
                    <div class="flex-1 space-y-2 w-full">
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-350">
                            Pilih File Gambar Logo Baru
                        </label>
                        <input type="file" 
                               name="logo" 
                               id="logo_input"
                               accept="image/*" 
                               class="block w-full text-sm text-slate-500
                                      file:mr-4 file:py-2.5 file:px-5
                                      file:rounded-2xl file:border-0
                                      file:text-xs file:font-bold
                                      file:bg-indigo-50 file:text-indigo-700
                                      hover:file:bg-indigo-100
                                      dark:file:bg-slate-800 dark:file:text-indigo-400
                                      cursor-pointer" />
                        <p class="text-xs text-slate-400 dark:text-slate-500">
                            Mendukung PNG, JPG, WEBP atau SVG (Max 2MB).
                        </p>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row justify-between items-center gap-3 pt-4 border-t border-slate-100 dark:border-slate-800/60">
                    <div>
                        @if ($globalLogoExists)
                            <button type="submit" name="delete_logo" value="1" 
                                    class="text-sm text-red-600 hover:text-red-500 font-bold transition flex items-center gap-1.5 py-2">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Hapus Logo Kustom
                            </button>
                        @endif
                    </div>
                    
                    <button type="submit" 
                            class="w-full sm:w-auto px-6 py-2.5 text-center text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-750 rounded-2xl shadow-md shadow-indigo-650/15 hover:shadow-lg transition">
                        Simpan Logo
                    </button>
                </div>
            </form>
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
                        const container = document.getElementById('logo-preview-container');
                        if (container) {
                            container.innerHTML = `
                                <div class="p-3 bg-slate-800/80 rounded-2xl border border-slate-700/50 shadow-inner">
                                    <img id="logo-preview" class="h-16 w-16 object-contain" src="${event.target.result}" alt="Global Logo">
                                </div>
                            `;
                        }
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
    </script>
@endsection
