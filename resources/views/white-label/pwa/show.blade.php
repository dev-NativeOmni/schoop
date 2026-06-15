@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-slate-900 dark:text-slate-100">Progressive Web App</h1>
            <p class="text-sm text-slate-500 mt-1">Konfigurasi instalasi aplikasi mandiri sekolah di handphone santri/wali.</p>
        </div>
        <a href="{{ route('white-label.pwa.edit', ['school_id' => $school->id]) }}" class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-slate-900 to-indigo-950 px-5 py-3 font-bold text-white shadow-md transition-all hover:scale-[1.02] active:scale-[0.98] text-sm">
            Edit Pengaturan PWA
        </a>
    </div>

    <!-- PWA Settings Card -->
    <div class="rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
                <div>
                    <h3 class="text-xs font-bold text-slate-400 uppercase">Nama Aplikasi</h3>
                    <p class="text-base font-bold text-slate-800 dark:text-slate-200 mt-0.5">{{ $pwa->app_name }}</p>
                </div>
                <div>
                    <h3 class="text-xs font-bold text-slate-400 uppercase">Nama Pendek (Short Name)</h3>
                    <p class="text-sm font-semibold text-slate-700 dark:text-slate-300 mt-0.5">{{ $pwa->short_name ?: '-' }}</p>
                </div>
                <div>
                    <h3 class="text-xs font-bold text-slate-400 uppercase">Status Aktif PWA</h3>
                    <span class="inline-flex items-center rounded-lg px-2.5 py-0.5 text-xs font-bold mt-1.5 {{ $pwa->is_enabled ? 'bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700' : 'bg-slate-100 dark:bg-slate-850 text-slate-500' }}">
                        {{ $pwa->is_enabled ? 'AKTIF' : 'NON-AKTIF' }}
                    </span>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <h3 class="text-xs font-bold text-slate-400 uppercase">Theme Color</h3>
                    <p class="text-sm font-semibold text-slate-700 dark:text-slate-300 mt-0.5 flex items-center gap-2">
                        <span class="h-4 w-4 rounded border border-slate-200 shadow-xs" style="background-color: {{ $pwa->theme_color }}"></span>
                        {{ $pwa->theme_color }}
                    </p>
                </div>
                <div>
                    <h3 class="text-xs font-bold text-slate-400 uppercase">Background Color</h3>
                    <p class="text-sm font-semibold text-slate-700 dark:text-slate-300 mt-0.5 flex items-center gap-2">
                        <span class="h-4 w-4 rounded border border-slate-200 shadow-xs" style="background-color: {{ $pwa->background_color }}"></span>
                        {{ $pwa->background_color }}
                    </p>
                </div>
                <div>
                    <h3 class="text-xs font-bold text-slate-400 uppercase">Start URL / Display Mode</h3>
                    <p class="text-sm font-semibold text-slate-700 dark:text-slate-300 mt-0.5">
                        <code>{{ $pwa->start_url }}</code> / <span class="capitalize">{{ $pwa->display_mode }}</span>
                    </p>
                </div>
            </div>
        </div>

        <!-- Icons previews -->
        <div class="pt-6 border-t border-slate-100 dark:border-slate-800 space-y-6">
            <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100 font-sans">Aset Ikon Manifest PWA</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Icon 192 -->
                <div class="rounded-2xl border border-slate-150 dark:border-slate-850 p-4 flex flex-col items-center justify-center bg-slate-50/20 dark:bg-slate-950/20">
                    <h3 class="text-xs font-bold text-slate-400 uppercase mb-4">Ikon Resolusi 192x192</h3>
                    @if($pwa->icon_192_path)
                        <img src="{{ Storage::disk('public')->url($pwa->icon_192_path) }}" alt="PWA Icon 192" class="h-24 w-24 object-contain rounded-xl shadow-sm bg-white p-2">
                    @else
                        <div class="h-24 w-24 flex items-center justify-center rounded-xl bg-slate-100 dark:bg-slate-950/40 text-slate-400 text-xs font-medium">Ikon Default</div>
                    @endif
                </div>

                <!-- Icon 512 -->
                <div class="rounded-2xl border border-slate-150 dark:border-slate-850 p-4 flex flex-col items-center justify-center bg-slate-50/20 dark:bg-slate-950/20">
                    <h3 class="text-xs font-bold text-slate-400 uppercase mb-4">Ikon Resolusi 512x512</h3>
                    @if($pwa->icon_512_path)
                        <img src="{{ Storage::disk('public')->url($pwa->icon_512_path) }}" alt="PWA Icon 512" class="h-32 w-32 object-contain rounded-xl shadow-sm bg-white p-2">
                    @else
                        <div class="h-32 w-32 flex items-center justify-center rounded-xl bg-slate-100 dark:bg-slate-950/40 text-slate-400 text-xs font-medium">Ikon Default</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
