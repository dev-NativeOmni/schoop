<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview Tema - {{ $school->name }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        {!! $cssVariables !!}

        .preview-bg {
            background-color: var(--school-background) !important;
            color: var(--school-text) !important;
        }

        .preview-primary-btn {
            background-color: var(--school-primary) !important;
            border-radius: var(--school-radius-button) !important;
            color: #ffffff !important;
        }

        .preview-primary-btn:hover {
            opacity: 0.9;
        }

        .preview-card {
            border-radius: var(--school-radius-card) !important;
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
        }

        .preview-text-primary {
            color: var(--school-primary) !important;
        }

        .preview-navbar {
            background-color: var(--school-secondary) !important;
            color: #ffffff !important;
        }
    </style>
</head>
<body class="min-h-screen bg-slate-100 preview-bg p-8 font-sans">
    <div class="max-w-4xl mx-auto space-y-8">
        <!-- Back Navigation Header -->
        <div class="flex items-center justify-between border-b pb-4">
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase">Interactive Live Sandbox</span>
                <h1 class="text-2xl font-black">Pratinjau Tema: {{ $theme->theme_name }}</h1>
            </div>
            <button onclick="window.close()" class="bg-slate-900 text-white rounded-xl px-4 py-2 text-xs font-bold hover:bg-slate-800 transition">
                Tutup Preview
            </button>
        </div>

        <!-- Simulated Navbar -->
        <div class="preview-navbar rounded-2xl shadow-md p-4 flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <div class="h-8 w-8 rounded-lg bg-white/20 flex items-center justify-center font-black">H</div>
                <span class="font-bold text-sm">{{ $school->name }}</span>
            </div>
            <div class="flex items-center space-x-4 text-xs font-semibold">
                <span class="text-white">Dashboard</span>
                <span class="opacity-60">Setoran</span>
                <span class="opacity-60">Laporan</span>
                <span class="opacity-60">Profil</span>
            </div>
        </div>

        <!-- Visual Components Card -->
        <div class="preview-card p-6 shadow-sm space-y-6">
            <h2 class="text-xl font-bold preview-text-primary">Judul Komponen Utama</h2>
            <p class="text-sm opacity-80 leading-relaxed">Ini adalah pratinjau teks biasa di dalam kartu sekolah. Kartu ini menggunakan sudut kelengkungan (radius) yang diatur oleh konfigurasi tema Anda saat ini.</p>

            <div class="flex flex-wrap items-center gap-4">
                <button class="preview-primary-btn px-6 py-3 font-bold shadow-sm transition">
                    Tombol Utama (Primary)
                </button>

                <button class="border border-slate-200 text-slate-700 rounded-xl px-6 py-3 font-bold hover:bg-slate-50 transition text-sm">
                    Tombol Sekunder (Outline)
                </button>

                <span style="background-color: var(--school-accent)" class="text-white text-xs font-bold rounded-lg px-2.5 py-1">
                    Label Aksen (Status / Notif)
                </span>
            </div>
        </div>

        <!-- Color Palette Summary -->
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            <div class="rounded-2xl border p-4 bg-white text-center shadow-xs">
                <div class="h-10 rounded-xl mb-2" style="background-color: var(--school-primary)"></div>
                <span class="text-xs font-bold text-slate-500">Primer</span>
                <p class="text-[10px] font-mono mt-0.5">{{ $theme->primary_color }}</p>
            </div>

            <div class="rounded-2xl border p-4 bg-white text-center shadow-xs">
                <div class="h-10 rounded-xl mb-2" style="background-color: var(--school-secondary)"></div>
                <span class="text-xs font-bold text-slate-500">Sekunder</span>
                <p class="text-[10px] font-mono mt-0.5">{{ $theme->secondary_color }}</p>
            </div>

            <div class="rounded-2xl border p-4 bg-white text-center shadow-xs">
                <div class="h-10 rounded-xl mb-2" style="background-color: var(--school-accent)"></div>
                <span class="text-xs font-bold text-slate-500">Aksen</span>
                <p class="text-[10px] font-mono mt-0.5">{{ $theme->accent_color }}</p>
            </div>

            <div class="rounded-2xl border p-4 bg-white text-center shadow-xs">
                <div class="h-10 rounded-xl mb-2" style="background-color: var(--school-text)"></div>
                <span class="text-xs font-bold text-slate-500">Tulisan</span>
                <p class="text-[10px] font-mono mt-0.5">{{ $theme->text_color }}</p>
            </div>

            <div class="rounded-2xl border p-4 bg-white text-center shadow-xs">
                <div class="h-10 rounded-xl mb-2" style="background-color: var(--school-background)"></div>
                <span class="text-xs font-bold text-slate-500">Latar</span>
                <p class="text-[10px] font-mono mt-0.5">{{ $theme->background_color }}</p>
            </div>
        </div>
    </div>
</body>
</html>
