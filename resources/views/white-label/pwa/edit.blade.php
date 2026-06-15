@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div>
        <h1 class="text-3xl font-black tracking-tight text-slate-900 dark:text-slate-100">Edit Pengaturan PWA</h1>
        <p class="text-sm text-slate-500 mt-1">Sesuaikan manifest Progressive Web App sekolah Anda.</p>
    </div>

    <!-- Edit Form Card -->
    <div class="rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm">
        <form action="{{ route('white-label.pwa.update', ['school_id' => $school->id]) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid gap-6 md:grid-cols-2">
                <div class="space-y-2">
                    <label for="app_name" class="text-sm font-bold text-slate-700 dark:text-slate-350">Nama Aplikasi PWA <span class="text-red-500">*</span></label>
                    <input type="text" id="app_name" name="app_name" value="{{ old('app_name', $pwa->app_name) }}" required class="block w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950/40 px-4 py-3 text-slate-800 dark:text-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('app_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-2">
                    <label for="short_name" class="text-sm font-bold text-slate-700 dark:text-slate-350">Nama Pendek (Short Name - maks 12 karakter)</label>
                    <input type="text" id="short_name" name="short_name" value="{{ old('short_name', $pwa->short_name) }}" class="block w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950/40 px-4 py-3 text-slate-800 dark:text-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('short_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <!-- Theme Color -->
                <div class="space-y-2">
                    <label for="theme_color" class="text-sm font-bold text-slate-700 dark:text-slate-350">Warna Tema PWA (Tab Browser)</label>
                    <div class="flex items-center space-x-3">
                        <input type="color" id="theme_picker" value="{{ old('theme_color', $pwa->theme_color) }}" oninput="document.getElementById('theme_color').value = this.value" class="h-10 w-10 border border-slate-200 rounded-xl cursor-pointer">
                        <input type="text" id="theme_color" name="theme_color" value="{{ old('theme_color', $pwa->theme_color) }}" oninput="document.getElementById('theme_picker').value = this.value" required class="block w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950/40 px-4 py-2.5 text-slate-800 dark:text-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    @error('theme_color') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Background Color -->
                <div class="space-y-2">
                    <label for="background_color" class="text-sm font-bold text-slate-700 dark:text-slate-350">Warna Latar Splash Screen</label>
                    <div class="flex items-center space-x-3">
                        <input type="color" id="background_picker" value="{{ old('background_color', $pwa->background_color) }}" oninput="document.getElementById('background_color').value = this.value" class="h-10 w-10 border border-slate-200 rounded-xl cursor-pointer">
                        <input type="text" id="background_color" name="background_color" value="{{ old('background_color', $pwa->background_color) }}" oninput="document.getElementById('background_picker').value = this.value" required class="block w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950/40 px-4 py-2.5 text-slate-800 dark:text-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    @error('background_color') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <div class="space-y-2">
                    <label for="start_url" class="text-sm font-bold text-slate-700 dark:text-slate-350">Start URL</label>
                    <input type="text" id="start_url" name="start_url" value="{{ old('start_url', $pwa->start_url) }}" required class="block w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950/40 px-4 py-3 text-slate-800 dark:text-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('start_url') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-2">
                    <label for="display_mode" class="text-sm font-bold text-slate-700 dark:text-slate-350">Display Mode</label>
                    <select id="display_mode" name="display_mode" required class="block w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950/40 px-4 py-3 text-slate-800 dark:text-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="standalone" {{ $pwa->display_mode === 'standalone' ? 'selected' : '' }}>Standalone (Aplikasi Sendiri)</option>
                        <option value="fullscreen" {{ $pwa->display_mode === 'fullscreen' ? 'selected' : '' }}>Fullscreen (Layar Penuh)</option>
                        <option value="minimal-ui" {{ $pwa->display_mode === 'minimal-ui' ? 'selected' : '' }}>Minimal UI (Sederhana)</option>
                        <option value="browser" {{ $pwa->display_mode === 'browser' ? 'selected' : '' }}>Browser (Tampilan Web Biasa)</option>
                    </select>
                    @error('display_mode') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Icon Uploads -->
            <div class="pt-6 border-t border-slate-100 dark:border-slate-800 space-y-6">
                <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100">Ubah Berkas Ikon PWA</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Icon 192 -->
                    <div class="space-y-2">
                        <label for="icon_192" class="text-xs font-bold text-slate-500 uppercase">Ikon PWA 192x192 (PNG/JPG, maks 1MB)</label>
                        <input type="file" id="icon_192" name="icon_192" class="block w-full text-xs text-slate-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-100 dark:file:bg-slate-800 file:text-slate-700 dark:file:text-slate-300 hover:file:bg-slate-200">
                        @error('icon_192') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        @if($pwa->icon_192_path)
                            <div class="mt-2 text-xs text-slate-400">Berkas saat ini: <code class="bg-slate-50 p-1 rounded">{{ basename($pwa->icon_192_path) }}</code></div>
                        @endif
                    </div>

                    <!-- Icon 512 -->
                    <div class="space-y-2">
                        <label for="icon_512" class="text-xs font-bold text-slate-500 uppercase">Ikon PWA 512x512 (PNG/JPG, maks 2MB)</label>
                        <input type="file" id="icon_512" name="icon_512" class="block w-full text-xs text-slate-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-100 dark:file:bg-slate-800 file:text-slate-700 dark:file:text-slate-300 hover:file:bg-slate-200">
                        @error('icon_512') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        @if($pwa->icon_512_path)
                            <div class="mt-2 text-xs text-slate-400">Berkas saat ini: <code class="bg-slate-50 p-1 rounded">{{ basename($pwa->icon_512_path) }}</code></div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Activation Checkbox -->
            <div class="flex items-center space-x-3 py-2 border-t pt-6 border-slate-100 dark:border-slate-800">
                <input type="checkbox" id="is_enabled" name="is_enabled" value="1" {{ old('is_enabled', $pwa->is_enabled) ? 'checked' : '' }} class="h-4.5 w-4.5 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                <label for="is_enabled" class="text-sm font-bold text-slate-700 dark:text-slate-300">Aktifkan Progressive Web App untuk Domain Sekolah</label>
            </div>

            <!-- Submit -->
            <div class="pt-6 border-t border-slate-100 dark:border-slate-800 flex items-center gap-3">
                <button type="submit" class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-slate-900 to-indigo-950 px-5 py-3 font-bold text-white shadow-md transition-all hover:scale-[1.02] active:scale-[0.98] text-sm">
                    Simpan Perubahan PWA
                </button>
                <a href="{{ route('white-label.pwa.show', ['school_id' => $school->id]) }}" class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 dark:border-slate-800 px-5 py-3 font-bold text-slate-700 dark:text-slate-300 transition-all hover:bg-slate-50 dark:hover:bg-slate-950 text-sm">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
