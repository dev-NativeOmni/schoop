@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-slate-900 dark:text-slate-100">Desain & Tema Warna</h1>
            <p class="text-sm text-slate-500 mt-1">Sesuaikan warna utama, layout, dan sudut kelengkungan tombol sekolah.</p>
        </div>
        <a href="{{ route('white-label.themes.preview', ['school_id' => $school->id]) }}" target="_blank" class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 dark:border-slate-800 px-5 py-3 font-bold text-slate-700 dark:text-slate-300 transition-all hover:bg-slate-50 dark:hover:bg-slate-950 text-sm">
            Lihat Preview Tema
        </a>
    </div>

    <!-- Theme Editor Card -->
    <div class="rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm">
        <form action="{{ route('white-label.themes.update', ['school_id' => $school->id]) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="space-y-2">
                <label for="theme_name" class="text-sm font-bold text-slate-700 dark:text-slate-350">Nama Tema</label>
                <input type="text" id="theme_name" name="theme_name" value="{{ old('theme_name', $theme->theme_name) }}" required class="block w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950/40 px-4 py-3 text-slate-800 dark:text-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('theme_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <!-- Colors Grid -->
            <div class="pt-6 border-t border-slate-100 dark:border-slate-800">
                <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100 mb-4">Palet Warna Utama (HEX Format)</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Primary Color -->
                    <div class="space-y-2">
                        <label for="primary_color" class="text-sm font-bold text-slate-700 dark:text-slate-350">Warna Primer (Tombol/Judul)</label>
                        <div class="flex items-center space-x-3">
                            <input type="color" id="primary_picker" value="{{ old('primary_color', $theme->primary_color) }}" oninput="document.getElementById('primary_color').value = this.value" class="h-10 w-10 border border-slate-200 rounded-xl cursor-pointer">
                            <input type="text" id="primary_color" name="primary_color" value="{{ old('primary_color', $theme->primary_color) }}" oninput="document.getElementById('primary_picker').value = this.value" required class="block w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950/40 px-4 py-2.5 text-slate-800 dark:text-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        @error('primary_color') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Secondary Color -->
                    <div class="space-y-2">
                        <label for="secondary_color" class="text-sm font-bold text-slate-700 dark:text-slate-350">Warna Sekunder (Navbar/Sidebar)</label>
                        <div class="flex items-center space-x-3">
                            <input type="color" id="secondary_picker" value="{{ old('secondary_color', $theme->secondary_color) }}" oninput="document.getElementById('secondary_color').value = this.value" class="h-10 w-10 border border-slate-200 rounded-xl cursor-pointer">
                            <input type="text" id="secondary_color" name="secondary_color" value="{{ old('secondary_color', $theme->secondary_color) }}" oninput="document.getElementById('secondary_picker').value = this.value" required class="block w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950/40 px-4 py-2.5 text-slate-800 dark:text-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        @error('secondary_color') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Accent Color -->
                    <div class="space-y-2">
                        <label for="accent_color" class="text-sm font-bold text-slate-700 dark:text-slate-350">Warna Aksen (Glow/Notif)</label>
                        <div class="flex items-center space-x-3">
                            <input type="color" id="accent_picker" value="{{ old('accent_color', $theme->accent_color) }}" oninput="document.getElementById('accent_color').value = this.value" class="h-10 w-10 border border-slate-200 rounded-xl cursor-pointer">
                            <input type="text" id="accent_color" name="accent_color" value="{{ old('accent_color', $theme->accent_color) }}" oninput="document.getElementById('accent_picker').value = this.value" required class="block w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950/40 px-4 py-2.5 text-slate-800 dark:text-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        @error('accent_color') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <!-- Text Color -->
                    <div class="space-y-2">
                        <label for="text_color" class="text-sm font-bold text-slate-700 dark:text-slate-350">Warna Tulisan Utama</label>
                        <div class="flex items-center space-x-3">
                            <input type="color" id="text_picker" value="{{ old('text_color', $theme->text_color) }}" oninput="document.getElementById('text_color').value = this.value" class="h-10 w-10 border border-slate-200 rounded-xl cursor-pointer">
                            <input type="text" id="text_color" name="text_color" value="{{ old('text_color', $theme->text_color) }}" oninput="document.getElementById('text_picker').value = this.value" required class="block w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950/40 px-4 py-2.5 text-slate-800 dark:text-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        @error('text_color') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Background Color -->
                    <div class="space-y-2">
                        <label for="background_color" class="text-sm font-bold text-slate-700 dark:text-slate-350">Warna Latar Utama</label>
                        <div class="flex items-center space-x-3">
                            <input type="color" id="background_picker" value="{{ old('background_color', $theme->background_color) }}" oninput="document.getElementById('background_color').value = this.value" class="h-10 w-10 border border-slate-200 rounded-xl cursor-pointer">
                            <input type="text" id="background_color" name="background_color" value="{{ old('background_color', $theme->background_color) }}" oninput="document.getElementById('background_picker').value = this.value" required class="block w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950/40 px-4 py-2.5 text-slate-800 dark:text-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        @error('background_color') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Styles/Layout Options -->
            <div class="pt-6 border-t border-slate-100 dark:border-slate-800">
                <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100 mb-4">Gaya & Layout</h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Sidebar Style -->
                    <div class="space-y-2">
                        <label for="sidebar_style" class="text-sm font-bold text-slate-700 dark:text-slate-350">Tampilan Sidebar</label>
                        <select id="sidebar_style" name="sidebar_style" required class="block w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950/40 px-4 py-3 text-slate-800 dark:text-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="default" {{ $theme->sidebar_style === 'default' ? 'selected' : '' }}>Default (Charcoal / Gelap)</option>
                            <option value="compact" {{ $theme->sidebar_style === 'compact' ? 'selected' : '' }}>Compact (Sempit)</option>
                            <option value="expanded" {{ $theme->sidebar_style === 'expanded' ? 'selected' : '' }}>Expanded (Lebar)</option>
                        </select>
                        @error('sidebar_style') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Header Style -->
                    <div class="space-y-2">
                        <label for="header_style" class="text-sm font-bold text-slate-700 dark:text-slate-350">Tampilan Header</label>
                        <select id="header_style" name="header_style" required class="block w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950/40 px-4 py-3 text-slate-800 dark:text-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="default" {{ $theme->header_style === 'default' ? 'selected' : '' }}>Default (Transparan Blur)</option>
                            <option value="minimal" {{ $theme->header_style === 'minimal' ? 'selected' : '' }}>Minimalis (Tanpa Border)</option>
                            <option value="branded" {{ $theme->header_style === 'branded' ? 'selected' : '' }}>Branded (Sesuai Warna Primer)</option>
                        </select>
                        @error('header_style') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Login Layout -->
                    <div class="space-y-2">
                        <label for="login_layout" class="text-sm font-bold text-slate-700 dark:text-slate-350">Tata Letak Login</label>
                        <select id="login_layout" name="login_layout" required class="block w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950/40 px-4 py-3 text-slate-800 dark:text-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="centered" {{ $theme->login_layout === 'centered' ? 'selected' : '' }}>Centered (Kotak Tengah)</option>
                            <option value="split" {{ $theme->login_layout === 'split' ? 'selected' : '' }}>Split Screen (Kiri Formulir, Kanan Gambar)</option>
                            <option value="card" {{ $theme->login_layout === 'card' ? 'selected' : '' }}>Floating Card (Kartu Melayang)</option>
                        </select>
                        @error('login_layout') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <!-- Card Radius -->
                    <div class="space-y-2">
                        <label for="card_radius" class="text-sm font-bold text-slate-700 dark:text-slate-350">Sudut Kelengkungan Kartu (Radius)</label>
                        <select id="card_radius" name="card_radius" required class="block w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950/40 px-4 py-3 text-slate-800 dark:text-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="none" {{ $theme->card_radius === 'none' ? 'selected' : '' }}>Siku-siku (None)</option>
                            <option value="sm" {{ $theme->card_radius === 'sm' ? 'selected' : '' }}>Slight Rounded (SM)</option>
                            <option value="md" {{ $theme->card_radius === 'md' ? 'selected' : '' }}>Medium (MD)</option>
                            <option value="lg" {{ $theme->card_radius === 'lg' ? 'selected' : '' }}>Large (LG)</option>
                            <option value="xl" {{ $theme->card_radius === 'xl' ? 'selected' : '' }}>Extra Large (XL)</option>
                        </select>
                        @error('card_radius') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Button Radius -->
                    <div class="space-y-2">
                        <label for="button_radius" class="text-sm font-bold text-slate-700 dark:text-slate-350">Sudut Kelengkungan Tombol (Radius)</label>
                        <select id="button_radius" name="button_radius" required class="block w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950/40 px-4 py-3 text-slate-800 dark:text-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="none" {{ $theme->button_radius === 'none' ? 'selected' : '' }}>Siku-siku (None)</option>
                            <option value="sm" {{ $theme->button_radius === 'sm' ? 'selected' : '' }}>Rounded (SM)</option>
                            <option value="md" {{ $theme->button_radius === 'md' ? 'selected' : '' }}>Medium (MD)</option>
                            <option value="lg" {{ $theme->button_radius === 'lg' ? 'selected' : '' }}>Large (LG)</option>
                            <option value="xl" {{ $theme->button_radius === 'xl' ? 'selected' : '' }}>Extra Large (XL)</option>
                        </select>
                        @error('button_radius') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="pt-6 border-t border-slate-100 dark:border-slate-800 flex items-center gap-3">
                <button type="submit" class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-slate-900 to-indigo-950 px-5 py-3 font-bold text-white shadow-md transition-all hover:scale-[1.02] active:scale-[0.98] text-sm">
                    Simpan Perubahan Tema
                </button>
                <a href="{{ route('white-label.dashboard', ['school_id' => $school->id]) }}" class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 dark:border-slate-800 px-5 py-3 font-bold text-slate-700 dark:text-slate-300 transition-all hover:bg-slate-50 dark:hover:bg-slate-950 text-sm">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
