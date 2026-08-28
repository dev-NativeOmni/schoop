<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>HafizPlus — Platform Manajemen Pesantren & Sekolah Islam Terpadu</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet" />

    <!-- Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full flex flex-col bg-slate-50 text-slate-900 dark:bg-[#060b14] dark:text-slate-100 antialiased selection:bg-emerald-500 selection:text-white font-sans">

    <!-- Ambient Calm Glow -->
    <div class="fixed top-0 left-1/3 w-[600px] h-[600px] bg-emerald-500/5 rounded-full blur-3xl pointer-events-none -z-10"></div>
    <div class="fixed bottom-0 right-1/4 w-[500px] h-[500px] bg-amber-500/5 rounded-full blur-3xl pointer-events-none -z-10"></div>

    <!-- Navigation Header -->
    <header class="sticky top-0 z-30 border-b border-slate-200/80 bg-white/80 dark:border-slate-800/80 dark:bg-[#060b14]/80 backdrop-blur-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-18 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-emerald-600 to-teal-700 text-white shadow-md shadow-emerald-900/20">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <div>
                    <span class="text-xl font-black tracking-tight text-slate-900 dark:text-white">Hafiz<span class="text-emerald-500">Plus</span></span>
                    <span class="hidden sm:inline-block ml-2 text-[10px] uppercase font-bold tracking-widest px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/50">SchoolOS</span>
                </div>
            </div>

            <div class="flex items-center space-x-3">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn-natural-primary text-xs">
                        <span>Buka Dashboard</span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn-natural-primary text-xs">
                        <span>Masuk Portal</span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <main class="flex-1">
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-20 text-center relative">
            <div class="inline-flex items-center space-x-2 px-3.5 py-1.5 rounded-full bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800/60 text-emerald-800 dark:text-emerald-300 text-xs font-bold mb-6">
                <span class="flex h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>All-in-One Islamic School & Pesantren Ecosystem</span>
            </div>

            <h1 class="text-4xl sm:text-6xl font-black tracking-tight text-slate-900 dark:text-white max-w-4xl mx-auto leading-tight sm:leading-none mb-6">
                Pendidikan Islami Terpadu dengan <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 via-teal-600 to-emerald-500">Teknologi Modern</span>
            </h1>

            <p class="text-base sm:text-lg text-slate-600 dark:text-slate-300 max-w-2xl mx-auto mb-10 leading-relaxed font-medium">
                Platform terintegrasi untuk manajemen Tahfizh, Mutabaah Yaumiyyah, Kehadiran QR, SPP & Keuangan, Asrama Boarding, hingga Cashless POS Santri.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('login') }}" class="btn-natural-primary text-sm px-6 py-3 shadow-lg shadow-emerald-700/20">
                    <span>Masuk ke Akun Anda</span>
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
                <a href="{{ route('quran.mushaf') }}" class="btn-natural-secondary text-sm px-6 py-3">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    <span>Baca Al-Qur'an Mushaf</span>
                </a>
            </div>
        </section>

        <!-- Bento Grid Feature Showcase -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="text-center mb-12">
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">Fitur Unggulan HafizPlus</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-2">Didesain khusus untuk kebutuhan pesantren, sekolah Islam terpadu, dan madrasah modern.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Card 1: Tahfizh & Mutabaah -->
                <div class="card-natural p-6 flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center mb-4 border border-emerald-200 dark:border-emerald-800/50">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/></svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">Tahfizh & Mutabaah Yaumiyyah</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed font-medium">Pencatatan setoran hafalan Al-Qur'an, target hafalan, hutang juz, dan checklist ibadah harian santri secara real-time.</p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center text-xs font-bold text-emerald-600 dark:text-emerald-400">
                        <span>Monitoring Berkelanjutan</span>
                    </div>
                </div>

                <!-- Card 2: Kehadiran & Kartu QR -->
                <div class="card-natural p-6 flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 rounded-xl bg-teal-50 dark:bg-teal-950/40 text-teal-600 dark:text-teal-400 flex items-center justify-center mb-4 border border-teal-200 dark:border-teal-800/50">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">Presensi QR & Notifikasi Otomatis</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed font-medium">Pemindai kartu santri presisi tinggi untuk sholat berjamaah, jam kelas, dan kegiatan asrama dengan riwayat akurat.</p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center text-xs font-bold text-teal-600 dark:text-teal-400">
                        <span>Pemindaian Instan & Cepat</span>
                    </div>
                </div>

                <!-- Card 3: Keuangan & Cashless -->
                <div class="card-natural p-6 flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 rounded-xl bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 flex items-center justify-center mb-4 border border-amber-200 dark:border-amber-800/50">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">SPP & Cashless Santri POS</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed font-medium">Kelola tagihan SPP, pembukuan kas madrasah, dompet digital santri dan transaksi kantin tanpa uang tunai.</p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center text-xs font-bold text-amber-600 dark:text-amber-400">
                        <span>Aman & Transparan</span>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="border-t border-slate-200/80 dark:border-slate-800/80 bg-white dark:bg-[#060b14] py-8 text-center text-xs text-slate-500 dark:text-slate-400">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <p>&copy; {{ date('Y') }} HafizPlus SchoolOS. Hak Cipta Dilindungi.</p>
        </div>
    </footer>

</body>
</html>
