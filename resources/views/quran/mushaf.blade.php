@extends('layouts.app')

@section('content')
    <!-- Header Section -->
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between w-full">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 dark:text-slate-200 leading-tight">
                {{ __('Mushaf Al-Qur\'an Interaktif') }}
            </h2>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                Baca, dengarkan lantunan qari, dan pelajari tafsir ayat Al-Qur'an secara interaktif.
            </p>
        </div>
        <!-- Tab selector for Admin Config & Custom PDF if needed -->
        @php
            $isAdmin = auth()->user()->hasRole('super_admin') || auth()->user()->hasRole('admin');
            $canRecord = $isAdmin || auth()->user()->hasRole('teacher');
        @endphp
        @if($isAdmin || !empty($config['google_drive_id']))
            <div class="inline-flex rounded-lg bg-slate-200/60 dark:bg-slate-800/80 p-0.5" x-data="{}">
                <button @click="$dispatch('set-tab', 'mushaf')" 
                        :class="activeTab === 'mushaf' ? 'bg-white dark:bg-gray-700 text-slate-900 dark:text-white shadow-sm' : 'text-slate-500 dark:text-slate-400 hover:text-slate-700'"
                        class="px-3 py-1.5 rounded-md text-xs font-semibold transition-all">
                    Mushaf Interaktif
                </button>
                <button @click="$dispatch('set-tab', 'pdf')" 
                        :class="activeTab === 'pdf' ? 'bg-white dark:bg-gray-700 text-slate-900 dark:text-white shadow-sm' : 'text-slate-500 dark:text-slate-400 hover:text-slate-700'"
                        class="px-3 py-1.5 rounded-md text-xs font-semibold transition-all">
                    PDF Dokumen Sekolah
                </button>
            </div>
        @endif
    </div>

    <!-- Google Fonts for premium Quran Typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Amiri:ital,wght@0,400;0,700;1,400;1,700&family=Scheherazade+New:wght@400;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <div x-data="mushafApp()" 
         x-init="init()"
         @set-tab.window="activeTab = $event.detail"
         :class="{ 
            'bg-white text-slate-900 border-slate-200': theme === 'light', 
            'bg-[#f4efe2] text-[#4a3c31] border-[#dfd6b9]': theme === 'sepia', 
            'bg-[#121214] text-[#e2e8f0] border-[#292931]': theme === 'dark' 
         }"
         class="rounded-3xl border p-4 sm:p-6 shadow-xl transition-colors duration-300 min-h-screen">
        
        <div class="space-y-6">
            
            <!-- ================= INTERACTIVE MUSHAF VIEW ================= -->
            <div x-show="activeTab === 'mushaf'" class="space-y-6">
                
                <!-- TOP CONTROL BAR -->
                <div :class="{
                        'bg-slate-50/50 border-slate-200': theme === 'light',
                        'bg-[#eae3cd] border-[#dcd2b5]': theme === 'sepia',
                        'bg-[#1e1e24] border-[#2e2e38]': theme === 'dark'
                     }"
                     class="rounded-2xl border p-4 sm:p-5 shadow-sm transition-all duration-300">
                    
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
                        
                        <!-- Search and Navigation Jump -->
                        <div class="md:col-span-5 grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-wider opacity-70 mb-1">Pilih Surah</label>
                                <select x-model="surah" 
                                        @change="surahChanged()"
                                        :class="{
                                            'bg-white border-slate-350 text-slate-900': theme === 'light',
                                            'bg-[#fdfbf7] border-[#d4caa7] text-[#4a3c31]': theme === 'sepia',
                                            'bg-[#282830] border-[#3e3e4a] text-white': theme === 'dark'
                                        }"
                                        class="block w-full text-xs rounded-xl py-2 px-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all font-semibold">
                                    <template x-for="s in surahList" :key="s.id">
                                        <option :value="s.id" x-text="`${s.id}. ${s.name} (${s.ar})`"></option>
                                    </template>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-wider opacity-70 mb-1">Pilih Juz</label>
                                <select x-model="juz" 
                                        @change="juzChanged()"
                                        :class="{
                                            'bg-white border-slate-350 text-slate-900': theme === 'light',
                                            'bg-[#fdfbf7] border-[#d4caa7] text-[#4a3c31]': theme === 'sepia',
                                            'bg-[#282830] border-[#3e3e4a] text-white': theme === 'dark'
                                        }"
                                        class="block w-full text-xs rounded-xl py-2 px-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all font-semibold">
                                    <template x-for="j in 30" :key="j">
                                        <option :value="j" x-text="`Juz ${j}`"></option>
                                    </template>
                                </select>
                            </div>
                        </div>
                        
                        <!-- Page Input and Arrow Controls -->
                        <div class="md:col-span-3 flex items-center justify-center gap-3">
                            <button @click="prevPage()" 
                                    :disabled="page <= 1"
                                    class="p-2.5 rounded-xl border hover:opacity-80 transition-all disabled:opacity-30 shrink-0"
                                    :class="{
                                        'bg-slate-100 border-slate-300 text-slate-700': theme === 'light',
                                        'bg-[#dfd7be] border-[#cbbfa1] text-[#4a3c31]': theme === 'sepia',
                                        'bg-[#2d2d38] border-[#3e3e4f] text-white': theme === 'dark'
                                    }">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                                </svg>
                            </button>
                            
                            <div class="flex items-center gap-2">
                                <span class="text-xs opacity-70">Hal.</span>
                                <input type="number" 
                                       x-model.number="page" 
                                       @change="pageInputChanged()"
                                       min="1" 
                                       max="604"
                                       :class="{
                                            'bg-white border-slate-300 text-slate-900': theme === 'light',
                                            'bg-[#fdfbf7] border-[#d4caa7] text-[#4a3c31]': theme === 'sepia',
                                            'bg-[#282830] border-[#3e3e4a] text-white font-bold': theme === 'dark'
                                       }"
                                       class="w-16 text-center text-xs py-1.5 px-2 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all font-bold" />
                                <span class="text-xs opacity-70">/ 604</span>
                            </div>

                            <button @click="nextPage()" 
                                    :disabled="page >= 604"
                                    class="p-2.5 rounded-xl border hover:opacity-80 transition-all disabled:opacity-30 shrink-0"
                                    :class="{
                                        'bg-slate-100 border-slate-300 text-slate-700': theme === 'light',
                                        'bg-[#dfd7be] border-[#cbbfa1] text-[#4a3c31]': theme === 'sepia',
                                        'bg-[#2d2d38] border-[#3e3e4f] text-white': theme === 'dark'
                                    }">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        </div>
                        
                        <!-- Settings: Reciter & Theme Toggles -->
                        <div class="md:col-span-4 flex flex-wrap items-center justify-end gap-3 w-full">
                            
                            <!-- Reciter -->
                            <div class="flex-grow sm:flex-grow-0 min-w-[150px]">
                                <select x-model="reciter" 
                                        @change="reciterChanged()"
                                        :class="{
                                            'bg-white border-slate-300 text-slate-900': theme === 'light',
                                            'bg-[#fdfbf7] border-[#d4caa7] text-[#4a3c31]': theme === 'sepia',
                                            'bg-[#282830] border-[#3e3e4a] text-white': theme === 'dark'
                                        }"
                                        class="block w-full text-[11px] rounded-xl py-2 px-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all font-semibold">
                                    <option value="7">Mishari Rashid al-`Afasy</option>
                                    <option value="2">AbdulBaset AbdulSamad (Murattal)</option>
                                    <option value="3">Abdur-Rahman as-Sudais</option>
                                    <option value="9">Mohamed Siddiq al-Minshawi</option>
                                </select>
                            </div>

                            <!-- Themes -->
                            <div class="flex items-center border rounded-xl overflow-hidden shadow-sm shrink-0"
                                 :class="{
                                     'border-slate-300': theme === 'light',
                                     'border-[#cbbfa1]': theme === 'sepia',
                                     'border-[#3e3e4f]': theme === 'dark'
                                 }">
                                <button @click="setTheme('light')" 
                                        :class="theme === 'light' ? 'bg-indigo-600 text-white font-bold' : 'bg-transparent text-slate-500 hover:text-slate-800'"
                                        class="p-2 text-xs transition-all flex items-center justify-center gap-1 font-semibold px-2.5">
                                    Bright
                                </button>
                                <button @click="setTheme('sepia')" 
                                        :class="theme === 'sepia' ? 'bg-[#9c6644] text-white font-bold' : 'bg-transparent text-slate-500 hover:text-slate-850'"
                                        class="p-2 text-xs transition-all flex items-center justify-center gap-1 font-semibold px-2.5">
                                    Sepia
                                </button>
                                <button @click="setTheme('dark')" 
                                        :class="theme === 'dark' ? 'bg-indigo-500 text-white font-bold' : 'bg-transparent text-slate-400 hover:text-slate-300'"
                                        class="p-2 text-xs transition-all flex items-center justify-center gap-1 font-semibold px-2.5">
                                    Dark
                                </button>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- AUTOPLAY PAGE PLAYER (GLOBAL BAR) -->
                <div class="rounded-xl border p-3 flex flex-wrap items-center justify-between gap-4 shadow-sm transition-all duration-300"
                     :class="{
                         'bg-indigo-50 border-indigo-100 text-indigo-900': theme === 'light',
                         'bg-[#eae0ca] border-[#dacfae] text-[#4a3c31]': theme === 'sepia',
                         'bg-[#19192c] border-[#292945] text-indigo-200': theme === 'dark'
                     }">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center animate-pulse shadow-inner shrink-0"
                             :class="{ 'bg-indigo-200 text-indigo-800': theme !== 'dark', 'bg-indigo-900/50 text-indigo-300': theme === 'dark' }">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold">Pemutar Halaman Otomatis (Autoplay)</p>
                            <p class="text-[10px] opacity-75">Putar berurutan dari ayat pertama hingga akhir halaman.</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
                        <!-- Play/Pause Autoplay -->
                        <button @click="toggleAutoplay()" 
                                class="inline-flex items-center justify-center px-4 py-1.5 rounded-lg text-xs font-bold transition-all shadow hover:scale-105 shrink-0"
                                :class="{
                                    'bg-indigo-600 text-white hover:bg-indigo-700': !autoplay,
                                    'bg-red-500 text-white hover:bg-red-600': autoplay
                                }">
                            <span x-show="!autoplay" class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg> Putar Satu Halaman
                            </span>
                            <span x-show="autoplay" class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/></svg> Hentikan Pemutaran
                            </span>
                        </button>
                    </div>
                </div>

                <!-- MAIN DISPLAY SPLIT LAYOUT -->
                <div>
                    <!-- Mobile view tabs toggler (Only visible on small screens) -->
                    <div class="flex md:hidden rounded-lg bg-slate-200/50 dark:bg-slate-800/40 p-1 mb-4">
                        <button @click="mobileTab = 'image'" 
                                :class="mobileTab === 'image' ? 'bg-indigo-600 text-white shadow font-bold' : 'text-slate-500 dark:text-slate-400'"
                                class="flex-1 py-2 text-xs rounded-md text-center transition-all font-semibold">
                            Halaman Mushaf (Gambar)
                        </button>
                        <button @click="mobileTab = 'text'" 
                                :class="mobileTab === 'text' ? 'bg-indigo-600 text-white shadow font-bold' : 'text-slate-500 dark:text-slate-400'"
                                class="flex-1 py-2 text-xs rounded-md text-center transition-all font-semibold">
                            Terjemahan & Tafsir
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-stretch">
                        
                        <!-- COLUMN 1: MUSHAF PAGE IMAGE (Left on desktop) -->
                        <div x-show="window.innerWidth >= 768 || mobileTab === 'image'" 
                             class="md:col-span-6 lg:col-span-5 flex flex-col justify-start">
                            
                            <!-- Elegant book representation wrapper -->
                            <div class="relative w-full rounded-2xl p-4 sm:p-6 shadow-md border transition-all duration-300 overflow-hidden flex items-center justify-center"
                                 :class="{
                                     'bg-white border-slate-200': theme === 'light',
                                     'bg-[#fcf7e6] border-[#dfd6bc]': theme === 'sepia',
                                     'bg-[#19191d] border-[#292931]': theme === 'dark'
                                 }">
                                
                                <!-- Arabic Header banner inside the book frame -->
                                <div class="absolute top-0 left-0 right-0 h-2 bg-gradient-to-r from-yellow-600/30 via-yellow-600/5 to-yellow-600/30"></div>
                                
                                <!-- Page Image with beautiful filters for Sepia and Dark mode -->
                                <div class="relative w-full max-w-[500px] border border-yellow-700/20 p-2 sm:p-4 rounded-xl bg-white shadow-sm select-none"
                                     style="min-height: 500px;">
                                    
                                    <!-- Dynamic Loading Overlay -->
                                    <div x-show="loading" class="absolute inset-0 bg-white/60 dark:bg-black/60 backdrop-blur-sm z-10 flex items-center justify-center rounded-xl transition-all">
                                        <div class="flex flex-col items-center gap-3">
                                            <div class="w-10 h-10 border-4 border-indigo-600 border-t-transparent rounded-full animate-spin"></div>
                                            <span class="text-xs font-bold text-indigo-700 dark:text-indigo-400">Memuat Halaman...</span>
                                        </div>
                                    </div>

                                    <!-- The actual Mushaf page image -->
                                    <img :src="`https://cdn.jsdelivr.net/gh/GovarJabbar/Quran-PNG@master/${pagePadded}.png`" 
                                         alt="Mushaf Page" 
                                         :style="imageFilterStyle"
                                         class="w-full h-auto object-contain rounded transition-all duration-300"
                                         loading="eager" />
                                         
                                    <!-- Bottom page label helper -->
                                    <div class="text-center mt-3 text-[10px] tracking-wider opacity-60 font-bold uppercase">
                                        Madinah Mushaf Layout — Halaman <span x-text="page"></span>
                                    </div>
                                </div>
                                
                                <!-- Book fold visual element -->
                                <div class="absolute top-0 bottom-0 w-4 pointer-events-none opacity-30"
                                     :class="{
                                         'right-0 bg-gradient-to-l from-black/20 to-transparent': page % 2 === 0,
                                         'left-0 bg-gradient-to-r from-black/20 to-transparent': page % 2 !== 0
                                     }"></div>
                            </div>
                        </div>

                        <!-- COLUMN 2: INTERACTIVE VERSES & TRANSLATIONS (Right on desktop) -->
                        <div x-show="window.innerWidth >= 768 || mobileTab === 'text'" 
                             class="md:col-span-6 lg:col-span-7 flex flex-col justify-start">
                             
                            <div class="rounded-2xl border p-4 sm:p-6 shadow-md transition-all duration-300 flex flex-col"
                                 :class="{
                                     'bg-white border-slate-200': theme === 'light',
                                     'bg-[#f5edd2] border-[#dfd5b2]': theme === 'sepia',
                                     'bg-[#19191d] border-[#292931]': theme === 'dark'
                                 }"
                                 style="height: 82vh; min-height: 600px;">
                                 
                                <div class="border-b pb-4 flex items-center justify-between shrink-0"
                                     :class="{ 'border-slate-150': theme === 'light', 'border-[#d3c7a0]': theme === 'sepia', 'border-[#2d2d38]': theme === 'dark' }">
                                    <div>
                                        <h4 class="font-bold text-sm tracking-wide">Terjemahan & Tafsir Halaman <span x-text="page"></span></h4>
                                        <p class="text-[10px] opacity-75 mt-0.5" x-text="pageRangeText"></p>
                                    </div>
                                    <div class="text-[10px] font-bold px-2 py-1 bg-indigo-500/10 text-indigo-500 rounded-lg">
                                        Indonesian Kemenag
                                    </div>
                                </div>

                                <!-- Dynamic Verse Cards Container -->
                                <div class="flex-grow overflow-y-auto mt-5 space-y-6 pr-1 scrollbar-thin" id="verse-list-container">
                                    <template x-show="!loading" x-for="(verse, index) in verses" :key="verse.id">
                                        <div :id="`verse-card-${verse.id}`"
                                             :class="{
                                                 'border-indigo-500 ring-2 ring-indigo-500/20 bg-indigo-500/5': playingVerseId === verse.id,
                                                 'border-slate-200 bg-slate-50/50 hover:bg-slate-50': theme === 'light' && playingVerseId !== verse.id,
                                                 'border-[#dfd3ad] bg-[#ece3c5]/30 hover:bg-[#ece3c5]/70': theme === 'sepia' && playingVerseId !== verse.id,
                                                 'border-[#2b2b38] bg-[#22222a]/40 hover:bg-[#22222a]/80': theme === 'dark' && playingVerseId !== verse.id
                                             }"
                                             class="rounded-xl border p-5 sm:p-6 transition-all duration-200">
                                            
                                            <!-- Verse Header -->
                                            <div class="flex items-center justify-between mb-5 text-xs opacity-75">
                                                <span class="font-semibold" x-text="getSurahNameAndAyah(verse.verse_key)"></span>
                                                
                                                <!-- Action links -->
                                                <div class="flex items-center gap-2" x-show="canRecord">
                                                    <!-- Catat Progress (Schoop Quick Integration) -->
                                                    <a :href="`{{ route('tahfizh.hafalan-records.create') }}?surah_id=${getSurahIdFromKey(verse.verse_key)}&ayah_start=${getAyahNumFromKey(verse.verse_key)}&ayah_end=${getAyahNumFromKey(verse.verse_key)}`"
                                                       class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-600 hover:text-emerald-750 bg-emerald-500/10 px-2 py-0.5 rounded transition-all">
                                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                                        </svg>
                                                        Catat Progress
                                                    </a>
                                                </div>
                                            </div>

                                            <!-- Uthmani Arabic Text -->
                                            <div class="quran-verse-text text-right mb-5 font-arabic"
                                                 style="direction: rtl;">
                                                <span :class="arabicFontClass" 
                                                      class="quran-verse-line inline-block text-slate-900 dark:text-slate-100 antialiased font-semibold select-all"
                                                      x-text="verse.text_uthmani"></span>
                                                <!-- Custom Arabic Ayah end symbol -->
                                                <span class="inline-flex items-center justify-center w-9 h-9 rounded-full border border-yellow-750/30 text-[10px] font-bold font-mono mx-2 relative top-[-6px] shrink-0"
                                                      :class="theme === 'dark' ? 'bg-[#2b2b38] text-yellow-500' : 'bg-yellow-550/10 text-yellow-800'"
                                                      x-text="getAyahNumFromKey(verse.verse_key)"></span>
                                            </div>

                                            <!-- Indonesian Translation -->
                                            <p class="quran-translation border-t pt-4 mb-5"
                                               :class="{ 'border-slate-150 text-slate-700': theme === 'light', 'border-[#dfd6b1] text-[#4a3c31]': theme === 'sepia', 'border-[#2d2d38] text-slate-300': theme === 'dark' }"
                                               x-html="getIndonesianTranslation(verse)"></p>

                                            <!-- Action Toolbar for Verse -->
                                            <div class="flex items-center justify-between border-t pt-3"
                                                 :class="{ 'border-slate-150': theme === 'light', 'border-[#dfd6b1]': theme === 'sepia', 'border-[#2d2d38]': theme === 'dark' }">
                                                
                                                <!-- Play Audio Button -->
                                                <button @click="playAudio(verse)" 
                                                        class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-lg transition-all"
                                                        :class="{
                                                            'bg-red-500 text-white hover:bg-red-600': playingVerseId === verse.id,
                                                            'bg-indigo-600 text-white hover:bg-indigo-700': playingVerseId !== verse.id
                                                        }">
                                                    <span x-show="playingVerseId !== verse.id" class="flex items-center gap-1">
                                                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg> Dengarkan
                                                    </span>
                                                    <span x-show="playingVerseId === verse.id" class="flex items-center gap-1">
                                                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 14H9V8h2v8zm4 0h-2V8h2v8z"/></svg> Berhenti
                                                    </span>
                                                </button>

                                                <!-- Tafsir Button -->
                                                <button @click="toggleTafsir(verse)" 
                                                        class="inline-flex items-center gap-1 text-xs font-semibold px-3 py-1.5 rounded-lg border transition-all hover:bg-black/5"
                                                        :class="{
                                                            'border-slate-300 text-slate-700': theme === 'light',
                                                            'border-[#c4b68e] text-[#4a3c31]': theme === 'sepia',
                                                            'border-[#3d3d4e] text-slate-300': theme === 'dark'
                                                        }">
                                                    <span x-text="expandedTafsir[verse.id] ? 'Tutup Tafsir' : 'Buka Tafsir'"></span>
                                                    <svg class="w-3.5 h-3.5 transform transition-transform" :class="{ 'rotate-180': expandedTafsir[verse.id] }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                                                    </svg>
                                                </button>
                                            </div>

                                            <!-- Tafsir Text Area -->
                                            <div x-show="expandedTafsir[verse.id]" 
                                                 x-collapse 
                                                 class="mt-3 p-3.5 rounded-lg text-xs leading-relaxed border transition-all"
                                                 :class="{
                                                     'bg-indigo-50/50 border-indigo-100 text-slate-700': theme === 'light',
                                                     'bg-[#eae0ca]/50 border-[#dacfae] text-[#4a3c31]': theme === 'sepia',
                                                     'bg-[#191924]/60 border-[#29293a] text-slate-350': theme === 'dark'
                                                 }">
                                                <div class="flex items-center justify-between mb-2 pb-1.5 border-b opacity-85"
                                                     :class="{ 'border-indigo-100': theme === 'light', 'border-[#dacfae]': theme === 'sepia', 'border-[#29293a]': theme === 'dark' }">
                                                    <span class="font-bold text-[10px] uppercase">Tafsir Kemenag (Wajiz)</span>
                                                </div>
                                                <div x-show="tafsirLoading[verse.id]" class="flex items-center justify-center py-4">
                                                    <div class="w-5 h-5 border-2 border-indigo-600 border-t-transparent rounded-full animate-spin"></div>
                                                </div>
                                                <div x-show="!tafsirLoading[verse.id]" x-text="tafsirText[verse.id] || 'Tafsir tidak ditemukan.'"></div>
                                            </div>

                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

            <!-- ================= BACKWARD COMPATIBLE PDF VIEW ================= -->
            <div x-show="activeTab === 'pdf'" class="space-y-6" x-cloak>
                @php
                    $driveId = $config['google_drive_id'] ?? null;
                    $driveLink = $config['google_drive_link'] ?? null;
                @endphp

                @if (!$driveId)
                    <!-- Empty State -->
                    @if ($isAdmin)
                        <!-- Admin Empty State with Setup Form -->
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start text-slate-900 dark:text-white">
                            <!-- Instructions Box -->
                            <div class="lg:col-span-7 bg-white dark:bg-slate-800 rounded-2xl shadow-md p-8 border border-slate-200 dark:border-slate-700 space-y-6">
                                <div>
                                    <span class="px-3 py-1 bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 text-xs font-bold uppercase tracking-wider rounded-full">Panduan Konfigurasi</span>
                                    <h3 class="text-xl font-bold mt-3 mb-2">Cara Menghubungkan Mushaf PDF dari Google Drive</h3>
                                    <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed font-medium">
                                        Agar seluruh pengguna (guru, santri, orangtua) dapat membaca Mushaf Al-Qur'an sekolah secara langsung tanpa terdownload otomatis oleh browser, Anda dapat menyimpannya di Google Drive dan membagikannya ke sistem ini.
                                    </p>
                                </div>
                                <div class="space-y-4">
                                    <div class="flex gap-4">
                                        <div class="w-8 h-8 rounded-full bg-indigo-600 text-white font-bold flex items-center justify-center shrink-0 shadow-md">1</div>
                                        <div>
                                            <h4 class="font-bold text-sm">Unggah Berkas ke Google Drive</h4>
                                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 leading-relaxed">
                                                Masuk ke Google Drive sekolah Anda, unggah file PDF Mushaf Al-Qur'an.
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex gap-4">
                                        <div class="w-8 h-8 rounded-full bg-indigo-600 text-white font-bold flex items-center justify-center shrink-0 shadow-md">2</div>
                                        <div>
                                            <h4 class="font-bold text-sm">Atur Izin Akses Menjadi Publik</h4>
                                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 leading-relaxed">
                                                Klik kanan file di Drive &rarr; <strong>Bagikan (Share)</strong> &rarr; Ubah akses umum dari "Dibatasi" menjadi <strong>"Siapa saja yang memiliki link"</strong> dengan peran sebagai <strong>Pelihat (Viewer)</strong>.
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex gap-4">
                                        <div class="w-8 h-8 rounded-full bg-indigo-600 text-white font-bold flex items-center justify-center shrink-0 shadow-md">3</div>
                                        <div>
                                            <h4 class="font-bold text-sm">Salin Link dan Tempel</h4>
                                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 leading-relaxed">
                                                Klik <strong>Salin link</strong>, lalu tempelkan link tersebut pada formulir konfigurasi di sebelah kanan.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Config Form Box -->
                            <div class="lg:col-span-5 bg-gradient-to-br from-indigo-900 to-indigo-950 text-white rounded-2xl shadow-xl p-8 border border-indigo-950 relative overflow-hidden flex flex-col justify-between min-h-[380px]">
                                <div class="absolute right-0 top-0 -mt-10 -mr-10 w-40 h-40 bg-white/5 rounded-full blur-2xl"></div>
                                <div class="relative z-10 space-y-4">
                                    <div class="w-14 h-14 bg-white/10 rounded-xl flex items-center justify-center text-indigo-300 mb-2">
                                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                        </svg>
                                    </div>
                                    <h3 class="text-xl font-bold">Sambungkan Mushaf PDF</h3>
                                    <p class="text-xs text-indigo-200 leading-relaxed">
                                        Tempelkan link berbagi Google Drive dari PDF Mushaf sekolah Anda.
                                    </p>
                                </div>
                                <form action="{{ route('quran.pdf.config') }}" method="POST" class="mt-6 space-y-4 relative z-10 font-sans">
                                    @csrf
                                    <div class="space-y-2">
                                        <label for="drive_link" class="block text-xs font-semibold text-indigo-200 uppercase tracking-wider">Link Berbagi Google Drive</label>
                                        <input type="text" id="drive_link" name="drive_link" placeholder="https://drive.google.com/file/d/.../view?usp=sharing" class="block w-full px-4 py-3 rounded-xl bg-white/10 border border-white/20 text-white placeholder-white/40 focus:bg-white/20 focus:border-white/50 focus:ring-0 text-sm transition-all" required />
                                    </div>
                                    <button type="submit" class="w-full inline-flex items-center justify-center px-5 py-3.5 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-bold rounded-xl shadow-lg hover:shadow-emerald-500/20 transform hover:-translate-y-0.5 transition-all focus:outline-none">
                                        Hubungkan Mushaf Digital
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <!-- Student/Parent Empty State -->
                        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-md p-12 border border-slate-200 dark:border-slate-700 text-center max-w-xl mx-auto my-12 text-slate-900 dark:text-white">
                            <div class="w-20 h-20 bg-indigo-50 dark:bg-indigo-950/50 rounded-full flex items-center justify-center mx-auto mb-6 text-indigo-500">
                                <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold mb-2">Mushaf Al-Qur'an Belum Tersedia</h3>
                            <p class="text-sm text-slate-500 dark:text-slate-400 max-w-md mx-auto leading-relaxed">
                                Berkas Mushaf Al-Qur'an PDF belum dihubungkan oleh administrator sekolah. Silakan hubungi admin sekolah Anda untuk mengaktifkan fitur ini.
                            </p>
                        </div>
                    @endif
                @else
                    <!-- Full State (PDF Configured) -->
                    @if ($isAdmin)
                        <!-- Collapsible Setup Form to Edit / Change Link -->
                        <div x-data="{ open: false }" class="bg-white dark:bg-slate-800 rounded-xl shadow-md border border-slate-200 dark:border-slate-700 overflow-hidden transition-all duration-300 mb-4 text-slate-900 dark:text-white">
                            <button @click="open = !open" class="w-full flex items-center justify-between p-5 text-left focus:outline-none">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 rounded-lg flex items-center justify-center shrink-0">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-sm">Pengaturan Mushaf (Administrator)</h4>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Mushaf saat ini aktif. Klik untuk melihat detail link atau menggantinya.</p>
                                    </div>
                                </div>
                                <svg class="w-5 h-5 text-slate-400 transform transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <div x-show="open" x-collapse class="border-t border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 p-5 space-y-4 font-sans">
                                <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-slate-200 dark:border-slate-700">
                                    <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider block mb-1">Link Google Drive Aktif</span>
                                    <a href="{{ $driveLink }}" target="_blank" class="text-xs font-mono text-indigo-600 dark:text-indigo-400 hover:underline break-all flex items-center gap-1.5">
                                        {{ $driveLink }}
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                        </svg>
                                    </a>
                                </div>
                                <form action="{{ route('quran.pdf.config') }}" method="POST" class="flex flex-col sm:flex-row items-stretch sm:items-end gap-3">
                                    @csrf
                                    <div class="flex-grow space-y-1">
                                        <label for="drive_link_edit" class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider">Hubungkan Link Google Drive Baru</label>
                                        <input type="text" id="drive_link_edit" name="drive_link" value="{{ $driveLink }}" placeholder="https://drive.google.com/file/d/.../view?usp=sharing" class="block w-full px-4 py-2.5 rounded-xl border-slate-200 dark:border-slate-750 dark:bg-slate-800 text-sm focus:border-indigo-500 focus:ring-indigo-500 text-slate-900 dark:text-white" required />
                                    </div>
                                    <button type="submit" class="inline-flex items-center justify-center px-5 py-2.5 bg-yellow-500 hover:bg-yellow-600 text-slate-900 text-sm font-bold rounded-xl shadow-md transition-all shrink-0">
                                        Ganti Link Mushaf
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif

                    <!-- PDF Viewer -->
                    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-md overflow-hidden border border-slate-200 dark:border-slate-700 transition-all duration-300 text-slate-900 dark:text-white">
                        <div class="px-6 py-4 bg-slate-50 dark:bg-slate-850 border-b border-slate-100 dark:border-slate-700 flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-3 h-3 rounded-full bg-red-400"></div>
                                <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
                                <div class="w-3 h-3 rounded-full bg-emerald-400"></div>
                                <span class="text-xs sm:text-sm font-semibold opacity-80 pl-2">Mushaf Al-Qur'an Digital (Google Drive PDF)</span>
                            </div>
                            <div>
                                <a href="{{ $driveLink }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-950 dark:hover:bg-indigo-900/80 text-xs font-semibold rounded-xl text-indigo-700 dark:text-indigo-300 transition-all shadow-sm border border-indigo-100 dark:border-indigo-900">
                                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                    Buka di Google Drive
                                </a>
                            </div>
                        </div>

                        <div class="relative w-full bg-slate-100 dark:bg-slate-900 shadow-inner rounded-xl overflow-hidden" style="height: 82vh; min-height: 700px;">
                            <iframe src="https://drive.google.com/file/d/{{ $driveId }}/preview" 
                                    class="w-full h-full border-0" 
                                    style="height: 82vh; min-height: 700px;"
                                    allow="autoplay"
                                    loading="lazy"></iframe>
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>

    <!-- Javascript application code for Mushaf -->
    <script>
        function mushafApp() {
            return {
                page: 1,
                surah: 1,
                juz: 1,
                verses: [],
                loading: false,
                theme: 'light',
                reciter: '7',
                playingVerseId: null,
                playingAudio: null,
                autoplay: false,
                activeTab: 'mushaf',
                mobileTab: 'image',
                tafsirText: {},
                expandedTafsir: {},
                tafsirLoading: {},
                canRecord: {{ $canRecord ? 'true' : 'false' }},
                
                // Mappings for fonts
                arabicFontClass: 'font-scheherazade',

                // Surah listing with Medina Mushaf starting page
                surahList: [
                    { id: 1, name: "Al-Fatihah", ar: "الفاتحة", page: 1 },
                    { id: 2, name: "Al-Baqarah", ar: "البقرة", page: 2 },
                    { id: 3, name: "Ali 'Imran", ar: "آl عمران", page: 50 },
                    { id: 4, name: "An-Nisa", ar: "النساء", page: 77 },
                    { id: 5, name: "Al-Ma'idah", ar: "المائدة", page: 106 },
                    { id: 6, name: "Al-An'am", ar: "الأنعام", page: 128 },
                    { id: 7, name: "Al-A'raf", ar: "الأعراف", page: 151 },
                    { id: 8, name: "Al-Anfal", ar: "الأنفال", page: 177 },
                    { id: 9, name: "At-Taubah", ar: "التوبة", page: 187 },
                    { id: 10, name: "Yunus", ar: "يونس", page: 208 },
                    { id: 11, name: "Hud", ar: "هود", page: 221 },
                    { id: 12, name: "Yusuf", ar: "يوسف", page: 235 },
                    { id: 13, name: "Ar-Ra'd", ar: "الرعد", page: 249 },
                    { id: 14, name: "Ibrahim", ar: "إبراهيم", page: 255 },
                    { id: 15, name: "Al-Hijr", ar: "الحجر", page: 262 },
                    { id: 16, name: "An-Nahl", ar: "النحل", page: 267 },
                    { id: 17, name: "Al-Isra", ar: "الإسراء", page: 282 },
                    { id: 18, name: "Al-Kahf", ar: "الكهف", page: 293 },
                    { id: 19, name: "Maryam", ar: "مريم", page: 305 },
                    { id: 20, name: "Ta-Ha", ar: "طه", page: 312 },
                    { id: 21, name: "Al-Anbiya", ar: "الأنبياء", page: 322 },
                    { id: 22, name: "Al-Hajj", ar: "الحج", page: 332 },
                    { id: 23, name: "Al-Mu'minun", ar: "المؤمنون", page: 342 },
                    { id: 24, name: "An-Nur", ar: "النور", page: 350 },
                    { id: 25, name: "Al-Furqan", ar: "الفرقان", page: 359 },
                    { id: 26, name: "Ash-Shu'ara", ar: "الشعراء", page: 367 },
                    { id: 27, name: "An-Naml", ar: "النمل", page: 377 },
                    { id: 28, name: "Al-Qasas", ar: "القصص", page: 385 },
                    { id: 29, name: "Al-'Ankabut", ar: "العنكبوت", page: 396 },
                    { id: 30, name: "Ar-Rum", ar: "الروم", page: 404 },
                    { id: 31, name: "Luqman", ar: "لقمان", page: 411 },
                    { id: 32, name: "As-Sajdah", ar: "السجدة", page: 415 },
                    { id: 33, name: "Al-Ahzab", ar: "الأحزاب", page: 418 },
                    { id: 34, name: "Saba", ar: "سبأ", page: 428 },
                    { id: 35, name: "Fatir", ar: "فاطر", page: 434 },
                    { id: 36, name: "Ya-Sin", ar: "يس", page: 440 },
                    { id: 37, name: "As-Saffat", ar: "الصافات", page: 446 },
                    { id: 38, name: "Sad", ar: "ص", page: 453 },
                    { id: 39, name: "Az-Zumar", ar: "الزمر", page: 458 },
                    { id: 40, name: "Ghafir", ar: "غافر", page: 467 },
                    { id: 41, name: "Fussilat", ar: "فصلت", page: 477 },
                    { id: 42, name: "Ash-Shura", ar: "الشورى", page: 483 },
                    { id: 43, name: "Az-Zukhruf", ar: "الزخرف", page: 489 },
                    { id: 44, name: "Ad-Dukhan", ar: "الدخان", page: 496 },
                    { id: 45, name: "Al-Jathiyah", ar: "الجاثية", page: 499 },
                    { id: 46, name: "Al-Ahqaf", ar: "الأحقاف", page: 502 },
                    { id: 47, name: "Muhammad", ar: "محمد", page: 507 },
                    { id: 48, name: "Al-Fath", ar: "الفتح", page: 511 },
                    { id: 49, name: "Al-Hujurat", ar: "الحجرات", page: 515 },
                    { id: 50, name: "Qaf", ar: "ق", page: 518 },
                    { id: 51, name: "Adh-Dhariyat", ar: "الذاريات", page: 520 },
                    { id: 52, name: "At-Tur", ar: "الطور", page: 523 },
                    { id: 53, name: "An-Najm", ar: "النجم", page: 526 },
                    { id: 54, name: "Al-Qamar", ar: "القمر", page: 528 },
                    { id: 55, name: "Ar-Rahman", ar: "الرحمن", page: 531 },
                    { id: 56, name: "Al-Waqi'ah", ar: "الواقعة", page: 534 },
                    { id: 57, name: "Al-Hadid", ar: "الحديد", page: 537 },
                    { id: 58, name: "Al-Mujadilah", ar: "المجادلة", page: 542 },
                    { id: 59, name: "Al-Hashr", ar: "الحشر", page: 545 },
                    { id: 60, name: "Al-Mumtahanah", ar: "الممتحنة", page: 549 },
                    { id: 61, name: "As-Saff", ar: "الصف", page: 551 },
                    { id: 62, name: "Al-Jumu'ah", ar: "الجمعة", page: 553 },
                    { id: 63, name: "Al-Munafiqun", ar: "المنافقon", page: 554 },
                    { id: 64, name: "At-Taghabun", ar: "التغابن", page: 556 },
                    { id: 65, name: "At-Talaq", ar: "الطلاق", page: 558 },
                    { id: 66, name: "At-Tahrim", ar: "التحريم", page: 560 },
                    { id: 67, name: "Al-Mulk", ar: "الملك", page: 562 },
                    { id: 68, name: "Al-Qalam", ar: "القلم", page: 564 },
                    { id: 69, name: "Al-Haqqah", ar: "الحاقة", page: 566 },
                    { id: 70, name: "Al-Ma'arij", ar: "المعارج", page: 568 },
                    { id: 71, name: "Nuh", ar: "نوح", page: 570 },
                    { id: 72, name: "Al-Jinn", ar: "الجن", page: 572 },
                    { id: 73, name: "Al-Muzzammil", ar: "المزمل", page: 574 },
                    { id: 74, name: "Al-Muddaththir", ar: "المدثر", page: 575 },
                    { id: 75, name: "Al-Qiyamah", ar: "القيامة", page: 577 },
                    { id: 76, name: "Al-Insan", ar: "الإنسان", page: 578 },
                    { id: 77, name: "Al-Mursalat", ar: "المرسلات", page: 580 },
                    { id: 78, name: "An-Naba", ar: "النبأ", page: 582 },
                    { id: 79, name: "An-Nazi'at", ar: "النازعات", page: 583 },
                    { id: 80, name: "Abasa", ar: "عبس", page: 585 },
                    { id: 81, name: "At-Takwir", ar: "التكوير", page: 586 },
                    { id: 82, name: "Al-Infitar", ar: "الانفطار", page: 587 },
                    { id: 83, name: "Al-Mutaffifin", ar: "المطففين", page: 587 },
                    { id: 84, name: "Al-Inshiqaq", ar: "الانشقاق", page: 589 },
                    { id: 85, name: "Al-Buruj", ar: "البروج", page: 590 },
                    { id: 86, name: "At-Tariq", ar: "الطارق", page: 591 },
                    { id: 87, name: "Al-A'la", ar: "الأعلى", page: 591 },
                    { id: 88, name: "Al-Ghashiyah", ar: "الغاشية", page: 592 },
                    { id: 89, name: "Al-Fajr", ar: "الفجر", page: 593 },
                    { id: 90, name: "Al-Balad", ar: "البلد", page: 594 },
                    { id: 91, name: "Ash-Shams", ar: "الشمس", page: 595 },
                    { id: 92, name: "Al-Lail", ar: "الليل", page: 595 },
                    { id: 93, name: "Ad-Duha", ar: "الضحى", page: 596 },
                    { id: 94, name: "Ash-Sharh", ar: "الشرح", page: 596 },
                    { id: 95, name: "At-Tin", ar: "التين", page: 597 },
                    { id: 96, name: "Al-'Alaq", ar: "العلق", page: 597 },
                    { id: 97, name: "Al-Qadr", ar: "القدر", page: 598 },
                    { id: 98, name: "Al-Bayyinah", ar: "البينة", page: 598 },
                    { id: 99, name: "Az-Zalzalah", ar: "الزلزلة", page: 599 },
                    { id: 100, name: "Al-'Adiyat", ar: "العاديات", page: 599 },
                    { id: 101, name: "Al-Qari'ah", ar: "القارعة", page: 600 },
                    { id: 102, name: "At-Takathur", ar: "التكاثر", page: 600 },
                    { id: 103, name: "Al-'Asr", ar: "العصر", page: 601 },
                    { id: 104, name: "Al-Humazah", ar: "الهمزة", page: 601 },
                    { id: 105, name: "Al-Fil", ar: "الفيل", page: 601 },
                    { id: 106, name: "Quraish", ar: "قريش", page: 602 },
                    { id: 107, name: "Al-Ma'un", ar: "الماعون", page: 602 },
                    { id: 108, name: "Al-Kawthar", ar: "الكوثر", page: 602 },
                    { id: 109, name: "Al-Kafirun", ar: "الكافرون", page: 603 },
                    { id: 110, name: "An-Nasr", ar: "النصر", page: 603 },
                    { id: 111, name: "Al-Masad", ar: "المسد", page: 603 },
                    { id: 112, name: "Al-Ikhlas", ar: "الإخلاص", page: 604 },
                    { id: 113, name: "Al-Falaq", ar: "الفلق", page: 604 },
                    { id: 114, name: "An-Nas", ar: "الناس", page: 604 }
                ],

                // Init method
                init() {
                    // Check localstorage for custom settings
                    const savedTheme = localStorage.getItem('mushaf-theme');
                    if (savedTheme) this.theme = savedTheme;

                    const savedReciter = localStorage.getItem('mushaf-reciter');
                    if (savedReciter) {
                        this.reciter = savedReciter;
                    } else {
                        this.reciter = '7';
                    }

                    const savedPage = localStorage.getItem('mushaf-page');
                    if (savedPage) {
                        this.page = parseInt(savedPage);
                    }

                    this.pageChanged(false);
                },

                // Getters
                get pagePadded() {
                    return String(this.page).padStart(3, '0');
                },

                get imageFilterStyle() {
                    if (this.theme === 'sepia') {
                        return 'filter: sepia(0.4) brightness(0.95) contrast(1.05);';
                    } else if (this.theme === 'dark') {
                        return 'filter: invert(0.92) hue-rotate(180deg) brightness(0.85) contrast(1.15);';
                    }
                    return 'filter: brightness(1.02);';
                },

                get pageRangeText() {
                    if (this.verses.length === 0) return 'Memuat ayat...';
                    const startKey = this.verses[0].verse_key;
                    const endKey = this.verses[this.verses.length - 1].verse_key;
                    return `Mencakup Ayat ${this.getSurahNameAndAyah(startKey)} s.d ${this.getSurahNameAndAyah(endKey)}`;
                },

                // Functions
                setTheme(theme) {
                    this.theme = theme;
                    localStorage.setItem('mushaf-theme', theme);
                },

                saveSettings() {
                    localStorage.setItem('mushaf-reciter', this.reciter);
                },

                reciterChanged() {
                    this.saveSettings();
                    this.pageChanged(false);
                },

                // Page changes
                pageChanged(autoPlayNextPage = false) {
                    this.loading = true;
                    localStorage.setItem('mushaf-page', this.page);
                    
                    if (this.playingAudio) {
                        this.playingAudio.pause();
                        this.playingVerseId = null;
                    }

                    this.juz = this.getJuzFromPage(this.page);

                    // Fetch Quran.com API v4
                    fetch(`https://api.quran.com/api/v4/verses/by_page/${this.page}?language=id&words=false&translations=33&fields=text_uthmani&audio=${this.reciter}`)
                        .then(res => {
                            if (!res.ok) throw new Error("Gagal mengambil data ayat");
                            return res.json();
                        })
                        .then(data => {
                            this.verses = data.verses || [];
                            
                            // Map Surah Select value
                            if (this.verses.length > 0) {
                                const firstVerse = this.verses[0];
                                const currentSurahId = parseInt(firstVerse.verse_key.split(':')[0]);
                                this.surah = currentSurahId;
                            }
                            
                            this.loading = false;
                            
                            // Scroll verse container back to top
                            const el = document.getElementById('verse-list-container');
                            if (el) el.scrollTop = 0;

                            // Handle Autoplay for next page if active
                            if (autoPlayNextPage && this.autoplay && this.verses.length > 0) {
                                this.$nextTick(() => {
                                    setTimeout(() => {
                                        this.playAudio(this.verses[0]);
                                        const card = document.getElementById(`verse-card-${this.verses[0].id}`);
                                        if (card) card.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                                    }, 500);
                                });
                            }
                        })
                        .catch(err => {
                            console.error(err);
                            this.loading = false;
                            this.verses = [];
                        });
                },

                prevPage() {
                    if (this.page > 1) {
                        this.page--;
                        this.pageChanged(false);
                    }
                },

                nextPage() {
                    if (this.page < 604) {
                        this.page++;
                        this.pageChanged(false);
                    }
                },

                pageInputChanged() {
                    let pageNum = parseInt(this.page);
                    if (isNaN(pageNum) || pageNum < 1) pageNum = 1;
                    if (pageNum > 604) pageNum = 604;
                    this.page = pageNum;
                    this.pageChanged(false);
                },

                surahChanged() {
                    const selected = this.surahList.find(s => s.id == this.surah);
                    if (selected) {
                        this.page = selected.page;
                        this.pageChanged(false);
                    }
                },

                juzChanged() {
                    const juzPages = [1, 22, 42, 62, 82, 102, 122, 142, 162, 182, 202, 222, 242, 262, 282, 302, 322, 342, 362, 382, 402, 422, 442, 462, 482, 502, 522, 542, 562, 582];
                    const selectedPage = juzPages[this.juz - 1];
                    this.page = selectedPage;
                    this.pageChanged(false);
                },

                getJuzFromPage(page) {
                    const juzPages = [1, 22, 42, 62, 82, 102, 122, 142, 162, 182, 202, 222, 242, 262, 282, 302, 322, 342, 362, 382, 402, 422, 442, 462, 482, 502, 522, 542, 562, 582];
                    for (let i = 29; i >= 0; i--) {
                        if (page >= juzPages[i]) return i + 1;
                    }
                    return 1;
                },

                // String parsing Helpers
                getSurahNameAndAyah(key) {
                    if (!key) return '';
                    const parts = key.split(':');
                    const surahNum = parseInt(parts[0]);
                    const ayahNum = parseInt(parts[1]);
                    const surahObj = this.surahList.find(s => s.id === surahNum);
                    const surahName = surahObj ? surahObj.name : 'Surah';
                    return `${surahName}: ${ayahNum}`;
                },

                getSurahIdFromKey(key) {
                    if (!key) return 1;
                    return parseInt(key.split(':')[0]);
                },

                getAyahNumFromKey(key) {
                    if (!key) return 1;
                    return parseInt(key.split(':')[1]);
                },

                getIndonesianTranslation(verse) {
                    if (verse.translations && verse.translations.length > 0) {
                        return verse.translations[0].text;
                    }
                    return 'Terjemahan tidak tersedia.';
                },

                // Play verse-by-verse recitation
                playAudio(verse) {
                    // If playing the same verse, toggle pause
                    if (this.playingAudio && this.playingVerseId === verse.id) {
                        this.playingAudio.pause();
                        this.playingVerseId = null;
                        return;
                    }

                    // Stop current audio if any
                    if (this.playingAudio) {
                        this.playingAudio.pause();
                    }

                    if (!verse.audio || !verse.audio.url) {
                        alert("Berkas audio tidak tersedia untuk ayat ini.");
                        return;
                    }

                    const audioUrl = verse.audio.url.startsWith('http') ? verse.audio.url : `https://audio.qurancdn.com/${verse.audio.url}`;
                    this.playingVerseId = verse.id;

                    this.playingAudio = new Audio(audioUrl);
                    this.playingAudio.play().catch(e => {
                        console.error(e);
                        this.playingVerseId = null;
                        alert("Gagal memutar audio. Coba qari lain atau cek koneksi internet Anda.");
                    });

                    this.playingAudio.onended = () => {
                        this.playingVerseId = null;
                        
                        // Handle global Autoplay Advance
                        if (this.autoplay) {
                            const index = this.verses.findIndex(v => v.id === verse.id);
                            if (index !== -1 && index < this.verses.length - 1) {
                                const nextVerse = this.verses[index + 1];
                                setTimeout(() => {
                                    this.playAudio(nextVerse);
                                    const nextEl = document.getElementById(`verse-card-${nextVerse.id}`);
                                    if (nextEl) nextEl.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                                }, 800);
                            } else {
                                // Turn to next page if autoplay is on-going
                                if (this.page < 604) {
                                    setTimeout(() => {
                                        this.page++;
                                        this.pageChanged(true);
                                    }, 1200);
                                } else {
                                    this.autoplay = false;
                                }
                            }
                        }
                    };
                },

                toggleAutoplay() {
                    this.autoplay = !this.autoplay;
                    if (this.autoplay && this.verses.length > 0) {
                        // Play the first verse of the page
                        this.playAudio(this.verses[0]);
                        const firstEl = document.getElementById(`verse-card-${this.verses[0].id}`);
                        if (firstEl) firstEl.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                    } else if (!this.autoplay && this.playingAudio) {
                        this.playingAudio.pause();
                        this.playingVerseId = null;
                    }
                },

                // Tafsir lookup
                toggleTafsir(verse) {
                    const isExpanded = !this.expandedTafsir[verse.id];
                    this.expandedTafsir[verse.id] = isExpanded;

                    if (isExpanded && !this.tafsirText[verse.id]) {
                        this.tafsirLoading[verse.id] = true;
                        
                        // Fetch Tafsir Kemenag (Wajiz) ID is 512
                        fetch(`https://api.quran.com/api/v4/tafsirs/512/by_ayah/${verse.verse_key}?language=id`)
                            .then(res => {
                                if (!res.ok) throw new Error("Gagal memuat tafsir");
                                return res.json();
                            })
                            .then(data => {
                                this.tafsirText[verse.id] = data.tafsir ? data.tafsir.text : 'Tafsir tidak ditemukan.';
                                this.tafsirLoading[verse.id] = false;
                            })
                            .catch(err => {
                                console.error(err);
                                this.tafsirText[verse.id] = 'Gagal memuat tafsir. Periksa koneksi internet Anda.';
                                this.tafsirLoading[verse.id] = false;
                            });
                    }
                }
            };
        }
    </script>

    <!-- Custom CSS styles -->
    <style>
        .font-arabic {
            font-family: 'Scheherazade New', 'Amiri', serif;
        }
        .quran-verse-text {
            line-height: 2.35;
            word-spacing: 0.12em;
        }
        .quran-verse-line {
            font-size: 1.9rem;
        }
        .quran-translation {
            font-size: 0.875rem;
            line-height: 2;
        }
        @media (min-width: 640px) {
            .quran-verse-text {
                line-height: 2.55;
            }
            .quran-verse-line {
                font-size: 2.35rem;
            }
            .quran-translation {
                font-size: 0.9375rem;
            }
        }
        /* Custom scrollbar styling */
        .scrollbar-thin::-webkit-scrollbar {
            width: 5px;
        }
        .scrollbar-thin::-webkit-scrollbar-track {
            background: transparent;
        }
        .scrollbar-thin::-webkit-scrollbar-thumb {
            background-color: rgba(156, 163, 175, 0.3);
            border-radius: 20px;
        }
        [x-cloak] { display: none !important; }
    </style>
@endsection
