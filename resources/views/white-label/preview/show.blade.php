<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview Platform - {{ $brand->display_name }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        {!! $cssVariables !!}

        .preview-theme-bg {
            background-color: var(--school-background) !important;
            color: var(--school-text) !important;
        }

        .preview-theme-primary {
            background-color: var(--school-primary) !important;
            border-radius: var(--school-radius-button) !important;
            color: #ffffff !important;
        }

        .preview-theme-card {
            border-radius: var(--school-radius-card) !important;
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
        }

        .preview-theme-text-primary {
            color: var(--school-primary) !important;
        }

        .preview-theme-sidebar {
            background-color: var(--school-secondary) !important;
            color: #ffffff !important;
        }
    </style>
</head>
<body class="min-h-screen bg-slate-100 preview-theme-bg font-sans">
    <div class="flex min-h-screen">
        <!-- Sidebar Mock -->
        <aside class="preview-theme-sidebar w-64 shadow-lg p-6 space-y-6 shrink-0 flex flex-col justify-between">
            <div class="space-y-6">
                <!-- Branding Logo -->
                <div class="flex items-center space-x-3 pb-4 border-b border-white/10">
                    @if($brand->logo_path)
                        <img src="{{ Storage::disk('public')->url($brand->logo_path) }}" alt="Logo" class="h-9 w-9 object-contain bg-white/10 p-1 rounded-xl">
                    @else
                        <div class="h-9 w-9 rounded-xl bg-white/20 flex items-center justify-center font-bold text-white">Logo</div>
                    @endif
                    <div>
                        <span class="font-extrabold text-sm block leading-none text-white">{{ $brand->display_name }}</span>
                        <span class="text-[10px] opacity-60 mt-1 block font-medium">{{ $brand->short_name ?: 'Platform Sekolah' }}</span>
                    </div>
                </div>

                <!-- Navigation List -->
                <nav class="space-y-1">
                    <a href="#" class="flex items-center space-x-3 px-4 py-2.5 rounded-xl bg-white/10 font-bold text-xs">
                        <span>Dashboard Utama</span>
                    </a>
                    <a href="#" class="flex items-center space-x-3 px-4 py-2.5 rounded-xl opacity-60 hover:opacity-100 font-bold text-xs transition">
                        <span>Setoran Halaqah</span>
                    </a>
                    <a href="#" class="flex items-center space-x-3 px-4 py-2.5 rounded-xl opacity-60 hover:opacity-100 font-bold text-xs transition">
                        <span>Laporan Mutabaah</span>
                    </a>
                    <a href="#" class="flex items-center space-x-3 px-4 py-2.5 rounded-xl opacity-60 hover:opacity-100 font-bold text-xs transition">
                        <span>Manajemen Asrama</span>
                    </a>
                </nav>
            </div>

            <!-- Footer Sidebar -->
            <div class="text-[10px] opacity-50 font-medium">
                &copy; {{ date('Y') }} {{ $brand->display_name }}
            </div>
        </aside>

        <!-- Main Body -->
        <div class="flex-1 flex flex-col">
            <!-- Simulated Header Bar -->
            <header class="bg-white border-b px-8 py-4 flex items-center justify-between shadow-xs">
                <div class="flex items-center space-x-2">
                    <span class="text-xs font-bold text-slate-400 uppercase">Status:</span>
                    <span class="inline-flex items-center rounded bg-amber-50 px-2 py-0.5 text-xs font-bold text-amber-700">DRAFT PREVIEW</span>
                </div>
                <div class="flex items-center space-x-4">
                    <button onclick="window.close()" class="bg-slate-900 text-white rounded-xl px-4 py-2 text-xs font-bold hover:bg-slate-800 transition">
                        Tutup Sandbox
                    </button>
                </div>
            </header>

            <!-- Page Content -->
            <main class="p-8 space-y-6">
                <!-- Banner tag -->
                <div class="p-6 rounded-3xl bg-indigo-50 border border-indigo-150 text-slate-800 space-y-2 shadow-xs">
                    <h2 class="text-lg font-bold text-indigo-900 flex items-center gap-2">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Slogan Sekolah
                    </h2>
                    <p class="text-sm opacity-90">"{{ $brand->tagline }}"</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Component 1 -->
                    <div class="preview-theme-card p-6 shadow-sm space-y-4 col-span-2">
                        <h2 class="text-xl font-bold preview-text-primary">Selamat Datang di Portal Sekolah</h2>
                        <p class="text-sm leading-relaxed opacity-75">Visualisasi layout ini menampilkan penggabungan warna primer sekolah, border radius kustom untuk kartu, serta gaya font Sora. Silakan verifikasi keserasian warna sebelum dipublikasikan secara live.</p>
                        
                        <div class="flex items-center gap-3 pt-2">
                            <button class="preview-theme-primary px-6 py-2.5 font-bold text-sm transition">
                                Tombol Utama
                            </button>
                            <span style="background-color: var(--school-accent)" class="text-white text-xs font-bold rounded-lg px-2.5 py-1.5 shadow-xs">
                                Label Aksen Aktif
                            </span>
                        </div>
                    </div>

                    <!-- School Info Contact -->
                    <div class="preview-theme-card p-6 shadow-sm space-y-4">
                        <h3 class="text-base font-bold text-slate-800">Hubungi Kami</h3>
                        <div class="space-y-3 text-xs leading-relaxed text-slate-650">
                            <div>
                                <span class="font-bold text-slate-400 block uppercase tracking-wider">Email</span>
                                <span class="font-medium text-slate-700 mt-0.5 block">{{ $brand->public_contact_email ?: '-' }}</span>
                            </div>
                            <div>
                                <span class="font-bold text-slate-400 block uppercase tracking-wider">Telepon</span>
                                <span class="font-medium text-slate-700 mt-0.5 block">{{ $brand->public_contact_phone ?: '-' }}</span>
                            </div>
                            <div>
                                <span class="font-bold text-slate-400 block uppercase tracking-wider">Alamat</span>
                                <span class="font-medium text-slate-700 mt-0.5 block">{{ $brand->public_address ?: '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
