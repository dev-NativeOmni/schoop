@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div>
        <h1 class="text-3xl font-black tracking-tight text-slate-900 dark:text-slate-100">Edit Profil Brand</h1>
        <p class="text-sm text-slate-500 mt-1">Ubah identitas visual dan informasi publik sekolah.</p>
    </div>

    <!-- Form Card -->
    <div class="rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm">
        <form action="{{ route('white-label.brand.update', ['school_id' => $school->id]) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid gap-6 md:grid-cols-2">
                <div class="space-y-2">
                    <label for="display_name" class="text-sm font-bold text-slate-700 dark:text-slate-350">Nama Tampilan Sekolah <span class="text-red-500">*</span></label>
                    <input type="text" id="display_name" name="display_name" value="{{ old('display_name', $profile->display_name) }}" required class="block w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950/40 px-4 py-3 text-slate-800 dark:text-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('display_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-2">
                    <label for="short_name" class="text-sm font-bold text-slate-700 dark:text-slate-350">Nama Pendek (Alias)</label>
                    <input type="text" id="short_name" name="short_name" value="{{ old('short_name', $profile->short_name) }}" class="block w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950/40 px-4 py-3 text-slate-800 dark:text-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('short_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="space-y-2">
                <label for="tagline" class="text-sm font-bold text-slate-700 dark:text-slate-350">Slogan / Tagline Sekolah</label>
                <input type="text" id="tagline" name="tagline" value="{{ old('tagline', $profile->tagline) }}" class="block w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950/40 px-4 py-3 text-slate-800 dark:text-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('tagline') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <div class="space-y-2">
                    <label for="public_contact_email" class="text-sm font-bold text-slate-700 dark:text-slate-350">Email Kontak Publik</label>
                    <input type="email" id="public_contact_email" name="public_contact_email" value="{{ old('public_contact_email', $profile->public_contact_email) }}" class="block w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950/40 px-4 py-3 text-slate-800 dark:text-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('public_contact_email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-2">
                    <label for="public_contact_phone" class="text-sm font-bold text-slate-700 dark:text-slate-350">Telepon Kontak Publik</label>
                    <input type="text" id="public_contact_phone" name="public_contact_phone" value="{{ old('public_contact_phone', $profile->public_contact_phone) }}" class="block w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950/40 px-4 py-3 text-slate-800 dark:text-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('public_contact_phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <div class="space-y-2">
                    <label for="public_website_url" class="text-sm font-bold text-slate-700 dark:text-slate-350">URL Situs Resmi</label>
                    <input type="url" id="public_website_url" name="public_website_url" value="{{ old('public_website_url', $profile->public_website_url) }}" class="block w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950/40 px-4 py-3 text-slate-800 dark:text-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('public_website_url') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-2">
                    <label for="public_address" class="text-sm font-bold text-slate-700 dark:text-slate-350">Alamat Lengkap Sekolah</label>
                    <textarea id="public_address" name="public_address" rows="3" class="block w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950/40 px-4 py-3 text-slate-800 dark:text-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('public_address', $profile->public_address) }}</textarea>
                    @error('public_address') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Visual File Inputs -->
            <div class="pt-6 border-t border-slate-100 dark:border-slate-800 space-y-6">
                <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100">Unggah Aset Gambar</h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Logo Upload -->
                    <div class="space-y-2">
                        <label for="logo" class="text-xs font-bold text-slate-500 uppercase">Logo Sekolah (PNG/JPG, maks 2MB)</label>
                        <input type="file" id="logo" name="logo" class="block w-full text-xs text-slate-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-100 dark:file:bg-slate-800 file:text-slate-700 dark:file:text-slate-300 hover:file:bg-slate-200">
                        @error('logo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        @if($profile->logo_path)
                            <div class="mt-2 text-xs text-slate-400">Berkas saat ini: <code class="bg-slate-50 p-1 rounded">{{ basename($profile->logo_path) }}</code></div>
                        @endif
                    </div>

                    <!-- Favicon Upload -->
                    <div class="space-y-2">
                        <label for="favicon" class="text-xs font-bold text-slate-500 uppercase">Favicon Tab (PNG/ICO, maks 512KB)</label>
                        <input type="file" id="favicon" name="favicon" class="block w-full text-xs text-slate-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-100 dark:file:bg-slate-800 file:text-slate-700 dark:file:text-slate-300 hover:file:bg-slate-200">
                        @error('favicon') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        @if($profile->favicon_path)
                            <div class="mt-2 text-xs text-slate-400">Berkas saat ini: <code class="bg-slate-50 p-1 rounded">{{ basename($profile->favicon_path) }}</code></div>
                        @endif
                    </div>

                    <!-- Background Upload -->
                    <div class="space-y-2">
                        <label for="login_background" class="text-xs font-bold text-slate-500 uppercase">Wallpaper Login (maks 4MB)</label>
                        <input type="file" id="login_background" name="login_background" class="block w-full text-xs text-slate-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-100 dark:file:bg-slate-800 file:text-slate-700 dark:file:text-slate-300 hover:file:bg-slate-200">
                        @error('login_background') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        @if($profile->login_background_path)
                            <div class="mt-2 text-xs text-slate-400">Berkas saat ini: <code class="bg-slate-50 p-1 rounded">{{ basename($profile->login_background_path) }}</code></div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="pt-6 border-t border-slate-100 dark:border-slate-800 flex items-center gap-3">
                <button type="submit" class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-slate-900 to-indigo-950 px-5 py-3 font-bold text-white shadow-md transition-all hover:scale-[1.02] active:scale-[0.98] text-sm">
                    Simpan Perubahan
                </button>
                <a href="{{ route('white-label.brand.show', ['school_id' => $school->id]) }}" class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 dark:border-slate-800 px-5 py-3 font-bold text-slate-700 dark:text-slate-300 transition-all hover:bg-slate-50 dark:hover:bg-slate-950 text-sm">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
