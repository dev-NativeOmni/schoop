@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-slate-900 dark:text-slate-100">White-Label School App Builder</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Sesuaikan branding, tema, manifest PWA, dan domain untuk sekolah Anda.</p>
        </div>
        
        @if(auth()->user()->isSuperAdmin())
            <div class="flex items-center gap-3">
                <form action="{{ route('white-label.dashboard') }}" method="GET" class="flex items-center gap-2">
                    <label for="school_selector" class="text-xs font-bold text-slate-500 uppercase">Sekolah:</label>
                    <select id="school_selector" name="school_id" onchange="this.form.submit()" class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 text-xs px-3 py-2 font-semibold">
                        @foreach($schools as $s)
                            <option value="{{ $s->id }}" {{ $s->id == $school->id ? 'selected' : '' }}>{{ $s->name }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
        @endif
    </div>

    <!-- Quick Status Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm flex items-center space-x-4">
            <div class="p-3 bg-indigo-50 dark:bg-indigo-950/30 rounded-2xl text-indigo-600 dark:text-indigo-400">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase">Branding Profile</p>
                <p class="text-sm font-bold text-slate-800 dark:text-slate-200 mt-0.5">
                    {{ $settings['brand']->logo_path ? 'Logo Terkonfigurasi' : 'Belum Ada Logo' }}
                </p>
            </div>
        </div>

        <div class="rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm flex items-center space-x-4">
            <div class="p-3 bg-amber-50 dark:bg-amber-950/30 rounded-2xl text-amber-600 dark:text-amber-400">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.344l2.122-2.122a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase">Tema Warna</p>
                <p class="text-sm font-bold text-slate-800 dark:text-slate-200 mt-0.5" style="color: {{ $settings['theme']->primary_color }}">
                    {{ $settings['theme']->theme_name }} ({{ $settings['theme']->primary_color }})
                </p>
            </div>
        </div>

        <div class="rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm flex items-center space-x-4">
            <div class="p-3 bg-emerald-50 dark:bg-emerald-950/30 rounded-2xl text-emerald-600 dark:text-emerald-400">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase">Domain Mappings</p>
                <p class="text-sm font-bold text-slate-800 dark:text-slate-200 mt-0.5">{{ $domainCount }} Domain Aktif</p>
            </div>
        </div>

        <div class="rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm flex items-center space-x-4">
            <div class="p-3 bg-rose-50 dark:bg-rose-950/30 rounded-2xl text-rose-600 dark:text-rose-400">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase">PWA Settings</p>
                <p class="text-sm font-bold text-slate-800 dark:text-slate-200 mt-0.5">
                    {{ $settings['pwa']->is_enabled ? 'Aktif' : 'Non-aktif' }}
                </p>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Configuration Columns -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Builder Options -->
            <div class="rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm">
                <h2 class="text-xl font-bold text-slate-900 dark:text-slate-100 mb-6">Menu Builder</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <a href="{{ route('white-label.brand.show', ['school_id' => $school->id]) }}" class="group block rounded-2xl border border-slate-100 dark:border-slate-800/60 bg-slate-50/50 dark:bg-slate-950/30 p-5 transition hover:bg-slate-100 dark:hover:bg-slate-950">
                        <h3 class="font-bold text-slate-850 dark:text-slate-200 group-hover:text-indigo-600 transition">1. Identitas & Brand</h3>
                        <p class="text-xs text-slate-450 dark:text-slate-400 mt-1">Ubah nama visual, deskripsi, logo, favicon, dan wallpaper login halaman sekolah.</p>
                    </a>

                    <a href="{{ route('white-label.themes.edit', ['school_id' => $school->id]) }}" class="group block rounded-2xl border border-slate-100 dark:border-slate-800/60 bg-slate-50/50 dark:bg-slate-950/30 p-5 transition hover:bg-slate-100 dark:hover:bg-slate-950">
                        <h3 class="font-bold text-slate-850 dark:text-slate-200 group-hover:text-indigo-600 transition">2. Desain & Tema Warna</h3>
                        <p class="text-xs text-slate-450 dark:text-slate-400 mt-1">Konfigurasi palet warna primer/sekunder, layout sidebar, dan sudut kelengkungan tombol.</p>
                    </a>

                    <a href="{{ route('white-label.domains.index', ['school_id' => $school->id]) }}" class="group block rounded-2xl border border-slate-100 dark:border-slate-800/60 bg-slate-50/50 dark:bg-slate-950/30 p-5 transition hover:bg-slate-100 dark:hover:bg-slate-950">
                        <h3 class="font-bold text-slate-850 dark:text-slate-200 group-hover:text-indigo-600 transition">3. Custom Domain Mapping</h3>
                        <p class="text-xs text-slate-450 dark:text-slate-400 mt-1">Hubungkan subdomain khusus atau domain kustom sekolah Anda ke server platform.</p>
                    </a>

                    <a href="{{ route('white-label.pwa.show', ['school_id' => $school->id]) }}" class="group block rounded-2xl border border-slate-100 dark:border-slate-800/60 bg-slate-50/50 dark:bg-slate-950/30 p-5 transition hover:bg-slate-100 dark:hover:bg-slate-950">
                        <h3 class="font-bold text-slate-850 dark:text-slate-200 group-hover:text-indigo-600 transition">4. Progressive Web App (PWA)</h3>
                        <p class="text-xs text-slate-450 dark:text-slate-400 mt-1">Sesuaikan detail manifest instalan handphone untuk aplikasi mandiri sekolah.</p>
                    </a>
                </div>
            </div>

            <!-- Publish Control Card -->
            <div class="rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm">
                <h2 class="text-xl font-bold text-slate-900 dark:text-slate-100 mb-2">Publish Branding</h2>
                <p class="text-xs text-slate-500 mb-6">Perubahan pada menu editor di atas disimpan sebagai **Draft**. Untuk menerapkannya secara live di web produksi, lakukan publikasi snapshot di bawah.</p>

                <form action="{{ route('white-label.publish', ['school_id' => $school->id]) }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="space-y-2">
                        <label for="pub_notes" class="text-xs font-bold text-slate-700 dark:text-slate-350">Catatan Perubahan (Opsional)</label>
                        <input type="text" id="pub_notes" name="notes" placeholder="Contoh: Mengganti warna utama ke merah marun..." class="block w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950/40 px-4 py-3 text-slate-800 dark:text-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div class="flex items-center gap-3">
                        <button type="submit" class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-emerald-600 to-teal-700 px-5 py-3 font-bold text-white shadow-md transition-all hover:scale-[1.02] active:scale-[0.98] text-sm">
                            Publish Perubahan Live
                        </button>
                        <a href="{{ route('white-label.preview.show', ['school_id' => $school->id]) }}" target="_blank" class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 dark:border-slate-800 px-5 py-3 font-bold text-slate-700 dark:text-slate-300 transition-all hover:bg-slate-50 dark:hover:bg-slate-950 text-sm">
                            Buka Preview Halaman
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Publication Snapshot Logs -->
        <div class="space-y-6">
            <div class="rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm">
                <h2 class="text-xl font-bold text-slate-900 dark:text-slate-100 mb-1">Riwayat Publikasi</h2>
                <p class="text-xs text-slate-400 mb-6">Daftar snapshot branding aktif dan log pemulihan rollback.</p>

                <div class="space-y-4">
                    @forelse($publications as $pub)
                        <div class="p-4 rounded-2xl border border-slate-150 dark:border-slate-800 bg-slate-50/30 dark:bg-slate-950/20 space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="inline-flex items-center rounded-lg px-2.5 py-0.5 text-xs font-bold {{ $pub->status === 'published' ? 'bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700' : 'bg-slate-100 dark:bg-slate-850 text-slate-500' }}">
                                    #{{ $pub->id }} - {{ strtoupper($pub->status) }}
                                </span>
                                <span class="text-[10px] text-slate-400 font-medium">{{ $pub->created_at->diffForHumans() }}</span>
                            </div>
                            
                            <p class="text-xs font-bold text-slate-700 dark:text-slate-300">
                                {{ $pub->notes ?: 'Tidak ada catatan publikasi.' }}
                            </p>

                            <div class="flex items-center justify-between pt-2 border-t border-slate-100 dark:border-slate-850">
                                <span class="text-[10px] text-slate-450">Oleh: {{ $pub->publisher?->name ?? 'System' }}</span>
                                @if($pub->status !== 'published')
                                    <form action="{{ route('white-label.rollback', ['id' => $pub->id, 'school_id' => $school->id]) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-[10px] font-black text-indigo-600 hover:text-indigo-800 transition">
                                            Rollback Ke Sini
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 py-6 text-center">Belum ada riwayat publikasi branding.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
