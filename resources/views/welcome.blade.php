<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5, viewport-fit=cover">
    <title>HafizPlus — Platform Manajemen Pesantren & Sekolah Islam Terpadu Modern</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800,900" rel="stylesheet" />

    <!-- GSAP for Smooth Choreography -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>

    <!-- Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Custom Ambient & Interactive Shader Layer */
        .glass-panel {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(226, 232, 240, 0.9);
        }
        .dark .glass-panel {
            background: rgba(10, 15, 29, 0.85);
            border: 1px solid rgba(30, 41, 59, 0.9);
        }
        .spotlight-card {
            position: relative;
            overflow: hidden;
        }
        @media (hover: hover) and (pointer: fine) {
            .spotlight-card::before {
                content: '';
                position: absolute;
                top: var(--mouse-y, 50%);
                left: var(--mouse-x, 50%);
                transform: translate(-50%, -50%);
                width: 320px;
                height: 320px;
                background: radial-gradient(circle, rgba(16, 185, 129, 0.14), transparent 70%);
                pointer-events: none;
                opacity: 0;
                transition: opacity 0.3s ease;
            }
            .spotlight-card:hover::before {
                opacity: 1;
            }
        }
        @keyframes float-slow {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-8px); }
        }
        .animate-float {
            animation: float-slow 5s ease-in-out infinite;
        }
        .mesh-grid {
            background-size: 24px 24px;
            background-image: 
                linear-gradient(to right, rgba(16, 185, 129, 0.05) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(16, 185, 129, 0.05) 1px, transparent 1px);
        }
        .dark .mesh-grid {
            background-image: 
                linear-gradient(to right, rgba(16, 185, 129, 0.08) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(16, 185, 129, 0.08) 1px, transparent 1px);
        }
        /* Mobile Touch Optimization */
        button, a {
            touch-action: manipulation;
        }
    </style>
</head>
<body class="min-h-full flex flex-col bg-slate-50 text-slate-900 dark:bg-[#060b14] dark:text-slate-100 antialiased selection:bg-emerald-500 selection:text-white font-sans overflow-x-hidden w-full relative"
      x-data="{
          activeTab: 'mutabaah',
          walletBalance: 450000,
          txHistory: [
              { name: 'Kantin (Makan Siang)', amount: -15000, time: '12:30 WIB' },
              { name: 'Koperasi (Kitab Tajwid)', amount: -35000, time: '09:15 WIB' }
          ],
          qrScanned: false,
          scanStudentName: 'Muhammad Fatih Robbani',
          scanStatus: 'Tepat Waktu (04:45 WIB)',
          mutabaahItems: [
              { id: 1, name: 'Sholat Tahajjud & Witir', done: true, points: 15 },
              { id: 2, name: 'Sholat Shubuh Berjamaah', done: true, points: 20 },
              { id: 3, name: 'Zikir Pagi Al-Matsurat', done: true, points: 10 },
              { id: 4, name: 'Setoran Ziyadah 1 Halaman', done: false, points: 25 },
              { id: 5, name: 'Murajaah Mandiri 1 Juz', done: false, points: 30 }
          ],
          toggleMutabaah(item) {
              item.done = !item.done;
          },
          get mutabaahPercent() {
              const completed = this.mutabaahItems.filter(i => i.done).length;
              return Math.round((completed / this.mutabaahItems.length) * 100);
          },
          simulateTopUp() {
              this.walletBalance += 50000;
              this.txHistory.unshift({ name: 'Top-Up Otomatis (Wali)', amount: 50000, time: 'Baru saja' });
          },
          simulateSpend() {
              if (this.walletBalance >= 10000) {
                  this.walletBalance -= 10000;
                  this.txHistory.unshift({ name: 'Snack Sehat Kantin', amount: -10000, time: 'Baru saja' });
              }
          },
          simulateScan() {
              this.qrScanned = true;
              setTimeout(() => { this.qrScanned = false; }, 3000);
          }
      }">

    <!-- Interactive 60fps Constellation Canvas Background -->
    <canvas id="constellationCanvas" class="fixed inset-0 pointer-events-none z-0 opacity-40 dark:opacity-30"></canvas>

    <!-- Mesh Grid Background -->
    <div class="fixed inset-0 mesh-grid pointer-events-none z-0"></div>

    <!-- Soft Ambient Light Halos -->
    <div class="fixed top-8 left-1/4 w-72 sm:w-[450px] h-72 sm:h-[450px] bg-emerald-500/10 dark:bg-emerald-500/5 rounded-full blur-3xl pointer-events-none -z-10 animate-pulse"></div>
    <div class="fixed top-80 right-1/4 w-72 sm:w-[400px] h-72 sm:h-[400px] bg-amber-500/10 dark:bg-amber-500/5 rounded-full blur-3xl pointer-events-none -z-10"></div>

    <!-- Top Navigation Header -->
    <header class="sticky top-0 z-40 border-b border-slate-200/80 bg-white/85 dark:border-slate-800/80 dark:bg-[#060b14]/85 backdrop-blur-md transition-all duration-200">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 h-16 sm:h-18 flex items-center justify-between gap-2">
            
            <!-- Brand Logo -->
            <a href="{{ url('/') }}" class="flex items-center space-x-2.5 shrink-0 group">
                <div class="flex h-9 w-9 sm:h-10 sm:w-10 items-center justify-center rounded-xl bg-gradient-to-br from-emerald-600 to-teal-700 text-white shadow-md shadow-emerald-950/20 group-hover:scale-105 transition-transform">
                    <svg class="h-4.5 w-4.5 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <div class="flex items-center space-x-1.5">
                    <span class="text-lg sm:text-xl font-black tracking-tight text-slate-900 dark:text-white">Hafiz<span class="text-emerald-500">Plus</span></span>
                    <span class="hidden xs:inline-block text-[9px] sm:text-[10px] uppercase font-black tracking-widest px-1.5 sm:px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-300/60 dark:border-emerald-800/60">SchoolOS</span>
                </div>
            </a>

            <!-- Quick Links Desktop -->
            <nav class="hidden md:flex items-center space-x-6 lg:space-x-8 text-xs font-bold text-slate-600 dark:text-slate-300">
                <a href="#simulator" class="hover:text-emerald-500 transition-colors">Demo Simulator</a>
                <a href="#features" class="hover:text-emerald-500 transition-colors">Fitur Unggulan</a>
                <a href="#faq" class="hover:text-emerald-500 transition-colors">FAQ</a>
                <a href="{{ route('quran.mushaf') }}" class="flex items-center space-x-1 text-emerald-600 dark:text-emerald-400 hover:text-emerald-500 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    <span>Mushaf Digital</span>
                </a>
            </nav>

            <!-- Action CTA -->
            <div class="flex items-center space-x-2 shrink-0">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn-natural-primary text-xs px-3 sm:px-4 py-2 sm:py-2.5 shadow-sm whitespace-nowrap">
                        <span>Dashboard</span>
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn-natural-primary text-xs px-3 sm:px-4 py-2 sm:py-2.5 shadow-sm whitespace-nowrap">
                        <span>Masuk Portal</span>
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Main Landing Container -->
    <main class="flex-1 z-10 w-full">
        
        <!-- HERO SECTION -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-10 sm:pt-14 pb-12 sm:pb-16 text-center relative">
            
            <!-- Floating Badge -->
            <div class="inline-flex items-center space-x-2 px-3 sm:px-4 py-1.5 rounded-full bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800/80 text-emerald-800 dark:text-emerald-300 text-[11px] sm:text-xs font-extrabold mb-5 sm:mb-6 shadow-xs animate-float max-w-full">
                <span class="flex h-2 w-2 rounded-full bg-emerald-500 animate-ping shrink-0"></span>
                <span class="truncate">All-in-One Islamic School & Pesantren Ecosystem v2.0</span>
            </div>

            <!-- Headline -->
            <h1 class="text-3xl xs:text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-black tracking-tight text-slate-900 dark:text-white max-w-5xl mx-auto leading-tight sm:leading-[1.1] mb-5 sm:mb-6 break-words">
                Pendidikan Islami Terpadu dengan <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 via-teal-600 to-emerald-500">Teknologi Modern</span>
            </h1>

            <p class="text-sm sm:text-lg md:text-xl text-slate-600 dark:text-slate-300 max-w-3xl mx-auto mb-8 sm:mb-10 leading-relaxed font-medium px-1 sm:px-0">
                Satu platform terintegrasi untuk mencatat <span class="text-emerald-600 dark:text-emerald-400 font-bold">Setoran Tahfizh</span>, <span class="text-emerald-600 dark:text-emerald-400 font-bold">Mutabaah Yaumiyyah</span>, <span class="text-emerald-600 dark:text-emerald-400 font-bold">Presensi QR Cepat</span>, <span class="text-emerald-600 dark:text-emerald-400 font-bold">SPP & Keuangan</span>, hingga <span class="text-emerald-600 dark:text-emerald-400 font-bold">Dompet Digital Cashless</span> santri.
            </p>

            <!-- Hero Action Buttons -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-3 sm:gap-4 mb-12 sm:mb-16 w-full max-w-md sm:max-w-none mx-auto">
                <a href="{{ route('login') }}" class="w-full sm:w-auto btn-natural-primary text-sm px-6 py-3.5 shadow-lg shadow-emerald-700/20 group justify-center">
                    <span>Masuk ke Akun Anda</span>
                    <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
                <a href="#simulator" class="w-full sm:w-auto btn-natural-secondary text-sm px-6 py-3.5 justify-center">
                    <span class="flex h-2 w-2 rounded-full bg-emerald-500 mr-2 shrink-0"></span>
                    <span>Coba Demo Simulator Interaktif</span>
                </a>
            </div>

            <!-- Key Live Metrics Bar -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-2.5 sm:gap-4 max-w-4xl mx-auto mb-8 sm:mb-12">
                <div class="glass-panel p-3 sm:p-4 rounded-2xl text-center shadow-xs">
                    <p class="text-xl sm:text-3xl font-black text-emerald-600 dark:text-emerald-400">30 Juz</p>
                    <p class="text-[10px] sm:text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mt-0.5">Mushaf & Setoran</p>
                </div>
                <div class="glass-panel p-3 sm:p-4 rounded-2xl text-center shadow-xs">
                    <p class="text-xl sm:text-3xl font-black text-teal-600 dark:text-teal-400">&lt; 0.8s</p>
                    <p class="text-[10px] sm:text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mt-0.5">Kecepatan Scan</p>
                </div>
                <div class="glass-panel p-3 sm:p-4 rounded-2xl text-center shadow-xs">
                    <p class="text-xl sm:text-3xl font-black text-amber-600 dark:text-amber-400">100%</p>
                    <p class="text-[10px] sm:text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mt-0.5">Transparansi Wali</p>
                </div>
                <div class="glass-panel p-3 sm:p-4 rounded-2xl text-center shadow-xs">
                    <p class="text-xl sm:text-3xl font-black text-slate-800 dark:text-slate-200">15+ Modul</p>
                    <p class="text-[10px] sm:text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mt-0.5">Ekosistem</p>
                </div>
            </div>

        </section>

        <!-- INTERACTIVE PRODUCT SIMULATOR (Live Demo Bento) -->
        <section id="simulator" class="max-w-6xl mx-auto px-3 sm:px-6 lg:px-8 py-8 sm:py-12">
            
            <div class="text-center mb-6 sm:mb-8">
                <span class="text-[10px] sm:text-xs font-black uppercase tracking-widest text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/40 px-3 py-1 rounded-full border border-emerald-200 dark:border-emerald-800/60">Live Product Experience</span>
                <h2 class="text-2xl sm:text-4xl font-black text-slate-900 dark:text-white tracking-tight mt-2">Uji Coba Interaktif Fitur HafizPlus</h2>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1.5 max-w-xl mx-auto px-2">Klik tombol-tombol pada widget di bawah ini untuk mencoba alur kerja nyata sistem kami secara langsung!</p>
            </div>

            <!-- Tab Switcher Navigation -->
            <div class="flex flex-wrap items-center justify-center gap-1.5 sm:gap-2 mb-4 sm:mb-6">
                <button @click="activeTab = 'mutabaah'"
                        :class="activeTab === 'mutabaah' ? 'bg-emerald-600 text-white shadow-md shadow-emerald-700/20 font-black' : 'glass-panel text-slate-600 dark:text-slate-300 hover:text-emerald-500 font-bold'"
                        class="px-3 sm:px-4 py-2 sm:py-2.5 rounded-xl text-[11px] sm:text-xs flex items-center space-x-1.5 sm:space-x-2 transition-all cursor-pointer">
                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>1. Mutabaah</span>
                </button>
                <button @click="activeTab = 'qr'"
                        :class="activeTab === 'qr' ? 'bg-teal-600 text-white shadow-md shadow-teal-700/20 font-black' : 'glass-panel text-slate-600 dark:text-slate-300 hover:text-teal-500 font-bold'"
                        class="px-3 sm:px-4 py-2 sm:py-2.5 rounded-xl text-[11px] sm:text-xs flex items-center space-x-1.5 sm:space-x-2 transition-all cursor-pointer">
                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                    <span>2. Scanner QR</span>
                </button>
                <button @click="activeTab = 'cashless'"
                        :class="activeTab === 'cashless' ? 'bg-amber-600 text-white shadow-md shadow-amber-700/20 font-black' : 'glass-panel text-slate-600 dark:text-slate-300 hover:text-amber-500 font-bold'"
                        class="px-3 sm:px-4 py-2 sm:py-2.5 rounded-xl text-[11px] sm:text-xs flex items-center space-x-1.5 sm:space-x-2 transition-all cursor-pointer">
                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    <span>3. Cashless POS</span>
                </button>
            </div>

            <!-- Interactive Stage Area -->
            <div class="glass-panel p-4 sm:p-6 md:p-8 rounded-2xl sm:rounded-3xl shadow-2xl relative overflow-hidden border border-slate-200/90 dark:border-slate-800">
                
                <!-- TAB 1: MUTABAAH INTERACTIVE SIMULATOR -->
                <div x-show="activeTab === 'mutabaah'" x-transition class="space-y-4 sm:space-y-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-200/80 dark:border-slate-800 pb-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-2xl bg-emerald-600 text-white flex items-center justify-center font-bold text-xs sm:text-sm shadow-md shadow-emerald-700/20 shrink-0">
                                <span>🔥 14</span>
                            </div>
                            <div class="min-w-0">
                                <h3 class="text-sm sm:text-base font-extrabold text-slate-900 dark:text-white truncate">Checklist Ibadah Santri</h3>
                                <p class="text-[11px] sm:text-xs text-slate-500 dark:text-slate-400 truncate">Istiqamah Streak 14 Hari Berturut-turut!</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between sm:justify-end space-x-3 pt-1 sm:pt-0">
                            <span class="text-xs font-bold text-slate-600 dark:text-slate-300">Capaian Hari Ini:</span>
                            <div class="flex items-center space-x-2 bg-emerald-50 dark:bg-emerald-950/40 px-2.5 py-1 rounded-xl border border-emerald-200 dark:border-emerald-800/60">
                                <span class="text-xs sm:text-sm font-black text-emerald-600 dark:text-emerald-400" x-text="mutabaahPercent + '%'"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Interactive Checklist Items -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2.5 sm:gap-3">
                        <template x-for="item in mutabaahItems" :key="item.id">
                            <div @click="toggleMutabaah(item)" 
                                 :class="item.done ? 'bg-emerald-50/80 dark:bg-emerald-950/30 border-emerald-300 dark:border-emerald-800/80' : 'bg-slate-50 dark:bg-slate-850/50 border-slate-200 dark:border-slate-800 hover:border-emerald-300'"
                                 class="p-3 sm:p-4 rounded-xl sm:rounded-2xl border flex items-center justify-between cursor-pointer transition-all duration-150 active:scale-[0.98] gap-2">
                                <div class="flex items-center space-x-2.5 min-w-0 flex-1">
                                    <div :class="item.done ? 'bg-emerald-600 text-white' : 'border-2 border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800'"
                                         class="w-5 h-5 sm:w-6 sm:h-6 rounded-lg flex items-center justify-center transition-colors shrink-0">
                                        <svg x-show="item.done" class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    </div>
                                    <span :class="item.done ? 'text-slate-900 dark:text-white font-bold line-through opacity-75' : 'text-slate-700 dark:text-slate-200 font-semibold'" 
                                          class="text-xs leading-snug break-words" x-text="item.name"></span>
                                </div>
                                <span class="text-[9px] sm:text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-100 dark:bg-emerald-900/40 text-emerald-800 dark:text-emerald-300 shrink-0 whitespace-nowrap" x-text="'+' + item.points + ' Poin'"></span>
                            </div>
                        </template>
                    </div>

                    <div class="p-2.5 sm:p-3 bg-slate-100/70 dark:bg-slate-850/60 rounded-xl text-center text-[11px] sm:text-xs text-slate-500 dark:text-slate-400">
                        💡 <em>Tips: Coba klik kotak di atas untuk melihat bagaimana progress ring ter-update secara real-time.</em>
                    </div>
                </div>

                <!-- TAB 2: QR SCANNER INTERACTIVE SIMULATOR -->
                <div x-show="activeTab === 'qr'" x-transition class="space-y-4 sm:space-y-6" style="display: none;">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-200/80 dark:border-slate-800 pb-4">
                        <div>
                            <h3 class="text-sm sm:text-base font-extrabold text-slate-900 dark:text-white">Simulator Scanner Kartu QR Santri</h3>
                            <p class="text-[11px] sm:text-xs text-slate-500 dark:text-slate-400">Presensi instan berkecepatan tinggi dengan auto-notifikasi ke WhatsApp Wali.</p>
                        </div>
                        <button @click="simulateScan()" class="btn-natural-primary text-xs px-3.5 py-2 flex items-center justify-center space-x-2 w-full sm:w-auto">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                            <span>Simulasikan Scan Kartu</span>
                        </button>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 items-center">
                        <!-- Digital Card Graphic -->
                        <div class="p-4 sm:p-6 rounded-2xl bg-gradient-to-br from-emerald-700 via-teal-800 to-slate-900 text-white shadow-xl relative overflow-hidden">
                            <div class="flex justify-between items-start mb-5 sm:mb-6 gap-2">
                                <div class="min-w-0 flex-1">
                                    <p class="text-[9px] sm:text-[10px] uppercase font-bold tracking-widest text-emerald-300">KARTU SANTRI DIGITAL</p>
                                    <p class="text-xs sm:text-sm font-black mt-1 truncate" x-text="scanStudentName"></p>
                                    <p class="text-[11px] text-emerald-200 truncate">NIS: 20260801 • Kelas 8-A</p>
                                </div>
                                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-white p-1 shadow-sm shrink-0">
                                    <div class="w-full h-full bg-slate-900 rounded-lg flex items-center justify-center text-[7px] sm:text-[8px] font-mono text-white">QR</div>
                                </div>
                            </div>
                            <div class="flex justify-between items-end text-xs">
                                <div>
                                    <p class="text-[9px] text-emerald-300 uppercase">Status Kehadiran</p>
                                    <p class="font-bold text-xs" x-text="scanStatus"></p>
                                </div>
                                <span class="px-2 py-0.5 rounded-full bg-emerald-500/30 text-emerald-200 font-bold text-[9px] sm:text-[10px]">Aktif</span>
                            </div>
                        </div>

                        <!-- Scanner Feedback Log -->
                        <div class="space-y-3">
                            <div :class="qrScanned ? 'bg-emerald-50 dark:bg-emerald-950/40 border-emerald-400' : 'bg-slate-50 dark:bg-slate-850/50 border-slate-200 dark:border-slate-800'"
                                 class="p-3.5 sm:p-4 rounded-2xl border transition-all duration-300">
                                <div class="flex items-center space-x-3">
                                    <div :class="qrScanned ? 'bg-emerald-600 text-white animate-bounce' : 'bg-slate-300 dark:bg-slate-700 text-slate-500'" 
                                         class="w-7 h-7 sm:w-8 sm:h-8 rounded-xl flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs font-bold text-slate-900 dark:text-white" x-text="qrScanned ? '✓ Berhasil Diverifikasi!' : 'Menunggu Pemindaian...'"></p>
                                        <p class="text-[10px] sm:text-[11px] text-slate-500 dark:text-slate-400" x-text="qrScanned ? 'Data presensi & notifikasi wali terkirim otomatis.' : 'Tekan tombol Simulasikan Scan untuk mencoba.'"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TAB 3: CASHLESS SANTRI POS SIMULATOR -->
                <div x-show="activeTab === 'cashless'" x-transition class="space-y-4 sm:space-y-6" style="display: none;">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-200/80 dark:border-slate-800 pb-4">
                        <div>
                            <h3 class="text-sm sm:text-base font-extrabold text-slate-900 dark:text-white">Simulator Dompet Digital & POS Kantin</h3>
                            <p class="text-[11px] sm:text-xs text-slate-500 dark:text-slate-400">Transaksi cashless higienis dengan limit belanja harian yang dikontrol wali.</p>
                        </div>
                        <div class="flex flex-wrap items-center gap-2">
                            <button @click="simulateSpend()" class="btn-natural-secondary text-xs px-2.5 py-1.5 cursor-pointer flex-1 sm:flex-none justify-center">
                                <span>- Belanja Rp 10.000</span>
                            </button>
                            <button @click="simulateTopUp()" class="btn-natural-primary text-xs px-2.5 py-1.5 cursor-pointer flex-1 sm:flex-none justify-center">
                                <span>+ Top-Up Rp 50.000</span>
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                        <!-- Digital Wallet Card -->
                        <div class="p-4 sm:p-6 rounded-2xl bg-gradient-to-br from-amber-600 via-orange-700 to-slate-900 text-white shadow-xl flex flex-col justify-between">
                            <div>
                                <div class="flex justify-between items-center mb-3">
                                    <span class="text-[9px] sm:text-[10px] uppercase font-bold tracking-widest text-amber-200">HafizPay E-Wallet</span>
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-white/20">Santri Reguler</span>
                                </div>
                                <p class="text-[11px] text-amber-200 font-semibold">Saldo Tersedia:</p>
                                <p class="text-xl sm:text-3xl font-black mt-0.5 truncate" x-text="'Rp ' + walletBalance.toLocaleString('id-ID')"></p>
                            </div>
                            <div class="pt-3 mt-3 border-t border-white/20 flex justify-between text-[11px] text-amber-100">
                                <span>Limit Harian: Rp 30.000</span>
                                <span>Status: Aman ✓</span>
                            </div>
                        </div>

                        <!-- Live Transaction List -->
                        <div class="space-y-2">
                            <p class="text-[11px] font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Riwayat Transaksi:</p>
                            <div class="space-y-1.5 max-h-40 overflow-y-auto pr-1">
                                <template x-for="(tx, idx) in txHistory" :key="idx">
                                    <div class="p-2 sm:p-2.5 rounded-xl bg-slate-50 dark:bg-slate-850/50 border border-slate-200 dark:border-slate-800 flex items-center justify-between text-xs gap-2">
                                        <div class="min-w-0 flex-1">
                                            <p class="font-bold text-slate-800 dark:text-slate-200 text-xs truncate" x-text="tx.name"></p>
                                            <p class="text-[10px] text-slate-400" x-text="tx.time"></p>
                                        </div>
                                        <span :class="tx.amount > 0 ? 'text-emerald-600 font-bold' : 'text-rose-500 font-bold'" 
                                              class="text-xs shrink-0 whitespace-nowrap"
                                              x-text="(tx.amount > 0 ? '+Rp ' : '-Rp ') + Math.abs(tx.amount).toLocaleString('id-ID')"></span>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>

        <!-- BENTO GRID ECOSYSTEM SHOWCASE -->
        <section id="features" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
            <div class="text-center mb-10 sm:mb-14">
                <span class="text-[10px] sm:text-xs font-black uppercase tracking-widest text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/40 px-3 py-1 rounded-full border border-emerald-200 dark:border-emerald-800/60">Arsitektur Modular</span>
                <h2 class="text-2xl sm:text-4xl font-black text-slate-900 dark:text-white tracking-tight mt-2">Semua yang Dibutuhkan Lembaga Pendidikan</h2>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1.5 max-w-xl mx-auto px-2">Dirancang spesifik untuk mendukung target kurikulum Al-Qur'an, kepengasuhan asrama, hingga tata kelola keuangan modern.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                
                <!-- Bento 1: Tahfizh & Mutabaah -->
                <div class="spotlight-card card-natural p-5 sm:p-7 flex flex-col justify-between group hover:border-emerald-400/80 transition-all duration-200">
                    <div>
                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center mb-4 sm:mb-5 border border-emerald-200 dark:border-emerald-800/50 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/></svg>
                        </div>
                        <h3 class="text-base sm:text-lg font-black text-slate-900 dark:text-white mb-2">Tahfizh & Mutabaah Harian</h3>
                        <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed font-medium">Input setoran hafalan sekali klik untuk asatidz, target juz terukur, tracking hutang hafalan, serta rekapitulasi ibadah harian santri secara otomatis.</p>
                    </div>
                    <div class="mt-5 pt-3.5 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between text-xs font-bold text-emerald-600 dark:text-emerald-400">
                        <span>Fitur Tahfizh Terintegrasi</span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </div>
                </div>

                <!-- Bento 2: Asrama & Boarding Management -->
                <div class="spotlight-card card-natural p-5 sm:p-7 flex flex-col justify-between group hover:border-teal-400/80 transition-all duration-200">
                    <div>
                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-2xl bg-teal-50 dark:bg-teal-950/40 text-teal-600 dark:text-teal-400 flex items-center justify-center mb-4 sm:mb-5 border border-teal-200 dark:border-teal-800/50 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <h3 class="text-base sm:text-lg font-black text-slate-900 dark:text-white mb-2">Asrama & Kepengasuhan</h3>
                        <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed font-medium">Manajemen gedung asrama, pembagian kamar & ranjang santri, perizinan pulang/keluar digital dengan verifikasi wali via portal.</p>
                    </div>
                    <div class="mt-5 pt-3.5 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between text-xs font-bold text-teal-600 dark:text-teal-400">
                        <span>Manajemen Asrama & Izin</span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </div>
                </div>

                <!-- Bento 3: SPP, Tagihan & POS Cashless -->
                <div class="spotlight-card card-natural p-5 sm:p-7 flex flex-col justify-between group hover:border-amber-400/80 transition-all duration-200">
                    <div>
                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-2xl bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 flex items-center justify-center mb-4 sm:mb-5 border border-amber-200 dark:border-amber-800/50 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        </div>
                        <h3 class="text-base sm:text-lg font-black text-slate-900 dark:text-white mb-2">Keuangan & Cashless POS</h3>
                        <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed font-medium">Generate tagihan SPP bulanan otomatis, rekonsiliasi kas masuk-keluar, dompet digital santri, serta sistem kasir kantin tanpa uang fisik.</p>
                    </div>
                    <div class="mt-5 pt-3.5 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between text-xs font-bold text-amber-600 dark:text-amber-400">
                        <span>Laporan Keuangan Otomatis</span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </div>
                </div>

                <!-- Bento 4: Multi-Tenant White-Label -->
                <div class="spotlight-card card-natural p-5 sm:p-7 flex flex-col justify-between group hover:border-emerald-400/80 transition-all duration-200">
                    <div>
                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center mb-4 sm:mb-5 border border-emerald-200 dark:border-emerald-800/50 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572 1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <h3 class="text-base sm:text-lg font-black text-slate-900 dark:text-white mb-2">White-Label & Domain Kustom</h3>
                        <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed font-medium">Gunakan logo, warna tema, favicon, dan domain sekolah Anda sendiri (misal: <em>portal.pesantrenanda.sch.id</em>) dengan identitas brand eksklusif.</p>
                    </div>
                    <div class="mt-5 pt-3.5 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between text-xs font-bold text-emerald-600 dark:text-emerald-400">
                        <span>Kustomisasi Brand Sekolah</span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </div>
                </div>

                <!-- Bento 5: Portal Wali & Santri Realtime -->
                <div class="spotlight-card card-natural p-5 sm:p-7 flex flex-col justify-between group hover:border-teal-400/80 transition-all duration-200">
                    <div>
                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-2xl bg-teal-50 dark:bg-teal-950/40 text-teal-600 dark:text-teal-400 flex items-center justify-center mb-4 sm:mb-5 border border-teal-200 dark:border-teal-800/50 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        </div>
                        <h3 class="text-base sm:text-lg font-black text-slate-900 dark:text-white mb-2">Portal Wali & Santri Real-time</h3>
                        <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed font-medium">Orang tua dapat memantau perkembangan hafalan, mutabaah, kehadiran harian, tagihan SPP, dan saldo saku anak langsung dari smartphone.</p>
                    </div>
                    <div class="mt-5 pt-3.5 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between text-xs font-bold text-teal-600 dark:text-teal-400">
                        <span>Akses Portal Wali & Santri</span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </div>
                </div>

                <!-- Bento 6: Mushaf Al-Qur'an Interaktif -->
                <div class="spotlight-card card-natural p-5 sm:p-7 flex flex-col justify-between group hover:border-amber-400/80 transition-all duration-200">
                    <div>
                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-2xl bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 flex items-center justify-center mb-4 sm:mb-5 border border-amber-200 dark:border-amber-800/50 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        </div>
                        <h3 class="text-base sm:text-lg font-black text-slate-900 dark:text-white mb-2">Mushaf Al-Qur'an 30 Juz</h3>
                        <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed font-medium">Dilengkapi teks Arab standar Kemenag RI, terjemahan bahasa Indonesia, navigasi surah & juz cepat untuk kemudahan santri menghafal.</p>
                    </div>
                    <div class="mt-5 pt-3.5 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between text-xs font-bold text-amber-600 dark:text-amber-400">
                        <span>Buka Mushaf Digital</span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </div>
                </div>

            </div>
        </section>

        <!-- INTERACTIVE FAQ ACCORDION -->
        <section id="faq" class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16" x-data="{ openFaq: null }">
            <div class="text-center mb-8 sm:mb-10">
                <span class="text-[10px] sm:text-xs font-black uppercase tracking-widest text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/40 px-3 py-1 rounded-full border border-emerald-200 dark:border-emerald-800/60">Tanya Jawab</span>
                <h2 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white tracking-tight mt-2">Pertanyaan yang Sering Diajukan</h2>
            </div>

            <div class="space-y-3">
                
                <!-- FAQ 1 -->
                <div class="card-natural overflow-hidden">
                    <button @click="openFaq = openFaq === 1 ? null : 1" class="w-full p-4 sm:p-5 text-left flex items-center justify-between font-bold text-xs sm:text-sm text-slate-900 dark:text-white cursor-pointer gap-2">
                        <span class="leading-snug">Apakah HafizPlus mendukung banyak sekolah sekaligus (Multi-Tenant)?</span>
                        <svg class="w-4 h-4 transition-transform duration-200 shrink-0" :class="{ 'rotate-180': openFaq === 1 }" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="openFaq === 1" x-collapse class="px-4 sm:px-5 pb-4 sm:pb-5 text-xs text-slate-600 dark:text-slate-300 leading-relaxed border-t border-slate-100 dark:border-slate-800 pt-3" style="display: none;">
                        Ya, HafizPlus dibangun dengan arsitektur Multi-Tenant SaaS. Setiap yayasan atau sekolah dapat mengelola beberapa cabang sekolah dengan database terisolasi aman dan pengaturan tema masing-masing.
                    </div>
                </div>

                <!-- FAQ 2 -->
                <div class="card-natural overflow-hidden">
                    <button @click="openFaq = openFaq === 2 ? null : 2" class="w-full p-4 sm:p-5 text-left flex items-center justify-between font-bold text-xs sm:text-sm text-slate-900 dark:text-white cursor-pointer gap-2">
                        <span class="leading-snug">Bagaimana cara kerja pencatatan Mutabaah Yaumiyyah bagi Guru?</span>
                        <svg class="w-4 h-4 transition-transform duration-200 shrink-0" :class="{ 'rotate-180': openFaq === 2 }" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="openFaq === 2" x-collapse class="px-4 sm:px-5 pb-4 sm:pb-5 text-xs text-slate-600 dark:text-slate-300 leading-relaxed border-t border-slate-100 dark:border-slate-800 pt-3" style="display: none;">
                        Guru atau Musyriif dapat menggunakan fitur <em>Bulk Fast Entry</em> dengan 1-klik "Tandai Semua Selesai" atau toggle per santri (Selesai, Belum, Uzur), sehingga pencatatan puluhan santri selesai dalam hitungan detik.
                    </div>
                </div>

                <!-- FAQ 3 -->
                <div class="card-natural overflow-hidden">
                    <button @click="openFaq = openFaq === 3 ? null : 3" class="w-full p-4 sm:p-5 text-left flex items-center justify-between font-bold text-xs sm:text-sm text-slate-900 dark:text-white cursor-pointer gap-2">
                        <span class="leading-snug">Apakah sistem Cashless POS bisa dibatasi belanjanya per hari?</span>
                        <svg class="w-4 h-4 transition-transform duration-200 shrink-0" :class="{ 'rotate-180': openFaq === 3 }" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="openFaq === 3" x-collapse class="px-4 sm:px-5 pb-4 sm:pb-5 text-xs text-slate-600 dark:text-slate-300 leading-relaxed border-t border-slate-100 dark:border-slate-800 pt-3" style="display: none;">
                        Tentu saja. Wali santri dapat mengatur batas maksimal pengeluaran harian melalui Portal Wali, sehingga santri belajar mengelola uang saku dengan bijak dan terhindar dari pemborosan.
                    </div>
                </div>

            </div>
        </section>

        <!-- CTA SECTION -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
            <div class="rounded-2xl sm:rounded-3xl bg-gradient-to-br from-emerald-800 via-teal-900 to-slate-950 p-6 sm:p-10 md:p-14 text-white text-center shadow-2xl relative overflow-hidden">
                <div class="absolute -top-24 -left-24 w-72 sm:w-96 h-72 sm:h-96 bg-emerald-500/20 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 max-w-2xl mx-auto space-y-4 sm:space-y-6">
                    <h2 class="text-2xl sm:text-4xl font-black tracking-tight">Mulai Transformasi Digital Pesantren Anda</h2>
                    <p class="text-xs sm:text-base text-emerald-100 font-medium leading-relaxed">
                        Tingkatkan produktivitas pengajar, permudah monitoring wali santri, dan kelola seluruh operasional sekolah dalam satu genggaman.
                    </p>
                    <div class="pt-2 sm:pt-4 flex flex-col sm:flex-row items-center justify-center gap-3 sm:gap-4">
                        <a href="{{ route('login') }}" class="w-full sm:w-auto bg-white text-slate-900 hover:bg-emerald-50 font-black text-xs sm:text-sm px-7 py-3 sm:py-3.5 rounded-xl shadow-lg transition-all active:scale-[0.98] justify-center text-center">
                            Masuk Portal Sekarang
                        </a>
                        <a href="{{ route('quran.mushaf') }}" class="w-full sm:w-auto bg-emerald-700/40 hover:bg-emerald-700/60 border border-emerald-500/40 text-white font-bold text-xs sm:text-sm px-7 py-3 sm:py-3.5 rounded-xl transition-all justify-center text-center">
                            Buka Mushaf Al-Qur'an
                        </a>
                    </div>
                </div>
            </div>
        </section>

    </main>

    <!-- Footer -->
    <footer class="border-t border-slate-200/80 dark:border-slate-800/80 bg-white/60 dark:bg-[#060b14]/60 backdrop-blur-md py-8 text-xs text-slate-500 dark:text-slate-400 z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-center sm:text-left">
            <div class="flex items-center space-x-2.5">
                <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-emerald-600 text-white font-black text-xs shrink-0">HP</div>
                <span class="font-extrabold text-slate-900 dark:text-white">HafizPlus SchoolOS</span>
            </div>
            <div class="flex items-center space-x-4 sm:space-x-6 font-semibold text-[11px] sm:text-xs">
                <a href="{{ route('quran.mushaf') }}" class="hover:text-emerald-500 transition-colors">Mushaf</a>
                <a href="{{ route('login') }}" class="hover:text-emerald-500 transition-colors">Login</a>
                <span>&copy; {{ date('Y') }} Hak Cipta Dilindungi.</span>
            </div>
        </div>
    </footer>

    <!-- Interactive Particle Mesh Canvas Script -->
    <script>
        (function() {
            const canvas = document.getElementById('constellationCanvas');
            if (!canvas) return;
            const ctx = canvas.getContext('2d');
            let width, height;
            let particles = [];
            const isMobile = window.innerWidth < 768;
            const particleCount = isMobile ? 22 : 45;
            const maxDistance = isMobile ? 90 : 140;
            let mouse = { x: -1000, y: -1000 };

            function resize() {
                width = canvas.width = window.innerWidth;
                height = canvas.height = window.innerHeight;
            }
            window.addEventListener('resize', resize);
            resize();

            // Desktop Mouse Move Handler
            if (!isMobile) {
                window.addEventListener('mousemove', (e) => {
                    mouse.x = e.clientX;
                    mouse.y = e.clientY;

                    document.querySelectorAll('.spotlight-card').forEach(card => {
                        const rect = card.getBoundingClientRect();
                        const x = e.clientX - rect.left;
                        const y = e.clientY - rect.top;
                        card.style.setProperty('--mouse-x', `${x}px`);
                        card.style.setProperty('--mouse-y', `${y}px`);
                    });
                });
            }

            class Particle {
                constructor() {
                    this.x = Math.random() * width;
                    this.y = Math.random() * height;
                    this.vx = (Math.random() - 0.5) * (isMobile ? 0.25 : 0.4);
                    this.vy = (Math.random() - 0.5) * (isMobile ? 0.25 : 0.4);
                    this.radius = Math.random() * 1.5 + 1;
                }
                update() {
                    this.x += this.vx;
                    this.y += this.vy;
                    if (this.x < 0 || this.x > width) this.vx *= -1;
                    if (this.y < 0 || this.y > height) this.vy *= -1;

                    if (!isMobile) {
                        const dx = mouse.x - this.x;
                        const dy = mouse.y - this.y;
                        const dist = Math.sqrt(dx * dx + dy * dy);
                        if (dist < 100) {
                            this.x -= (dx / dist) * 1.2;
                            this.y -= (dy / dist) * 1.2;
                        }
                    }
                }
                draw() {
                    ctx.beginPath();
                    ctx.arc(this.x, this.y, this.radius, 0, Math.PI * 2);
                    ctx.fillStyle = '#10b981';
                    ctx.fill();
                }
            }

            for (let i = 0; i < particleCount; i++) {
                particles.push(new Particle());
            }

            function animate() {
                ctx.clearRect(0, 0, width, height);
                for (let i = 0; i < particles.length; i++) {
                    particles[i].update();
                    particles[i].draw();

                    for (let j = i + 1; j < particles.length; j++) {
                        const dx = particles[i].x - particles[j].x;
                        const dy = particles[i].y - particles[j].y;
                        const dist = Math.sqrt(dx * dx + dy * dy);
                        if (dist < maxDistance) {
                            ctx.beginPath();
                            ctx.moveTo(particles[i].x, particles[i].y);
                            ctx.lineTo(particles[j].x, particles[j].y);
                            ctx.strokeStyle = `rgba(16, 185, 129, ${1 - dist / maxDistance * 0.8})`;
                            ctx.lineWidth = 0.5;
                            ctx.stroke();
                        }
                    }
                }
                requestAnimationFrame(animate);
            }
            animate();
        })();

        // Service Worker Registration for PWA
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(reg => console.log('[PWA] Service Worker registered:', reg.scope))
                    .catch(err => console.log('[PWA] Service Worker registration failed:', err));
            });
        }
    </script>
</body>
</html>
