@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-slate-900 dark:text-slate-100">Identitas & Brand</h1>
            <p class="text-sm text-slate-500 mt-1">Profil visual dan identitas publik sekolah Anda.</p>
        </div>
        <a href="{{ route('white-label.brand.edit', ['school_id' => $school->id]) }}" class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-slate-900 to-indigo-950 px-5 py-3 font-bold text-white shadow-md transition-all hover:scale-[1.02] active:scale-[0.98] text-sm">
            Edit Profil Brand
        </a>
    </div>

    <!-- Details Card -->
    <div class="rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
                <div>
                    <h3 class="text-xs font-bold text-slate-400 uppercase">Nama Tampilan Sekolah</h3>
                    <p class="text-base font-bold text-slate-800 dark:text-slate-200 mt-0.5">{{ $profile->display_name }}</p>
                </div>
                <div>
                    <h3 class="text-xs font-bold text-slate-400 uppercase">Nama Pendek</h3>
                    <p class="text-sm font-semibold text-slate-700 dark:text-slate-300 mt-0.5">{{ $profile->short_name ?: '-' }}</p>
                </div>
                <div>
                    <h3 class="text-xs font-bold text-slate-400 uppercase">Tagline Sekolah</h3>
                    <p class="text-sm font-semibold text-slate-700 dark:text-slate-300 mt-0.5">{{ $profile->tagline ?: '-' }}</p>
                </div>
                <div>
                    <h3 class="text-xs font-bold text-slate-400 uppercase">Website Resmi</h3>
                    <p class="text-sm font-semibold text-slate-700 dark:text-slate-300 mt-0.5">
                        @if($profile->public_website_url)
                            <a href="{{ $profile->public_website_url }}" target="_blank" class="text-indigo-600 hover:underline">{{ $profile->public_website_url }}</a>
                        @else
                            -
                        @endif
                    </p>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <h3 class="text-xs font-bold text-slate-400 uppercase">Email Publik</h3>
                    <p class="text-sm font-semibold text-slate-700 dark:text-slate-300 mt-0.5">{{ $profile->public_contact_email ?: '-' }}</p>
                </div>
                <div>
                    <h3 class="text-xs font-bold text-slate-400 uppercase">Telepon Publik</h3>
                    <p class="text-sm font-semibold text-slate-700 dark:text-slate-300 mt-0.5">{{ $profile->public_contact_phone ?: '-' }}</p>
                </div>
                <div>
                    <h3 class="text-xs font-bold text-slate-400 uppercase">Alamat Lengkap</h3>
                    <p class="text-sm font-semibold text-slate-700 dark:text-slate-300 mt-0.5">{{ $profile->public_address ?: '-' }}</p>
                </div>
            </div>
        </div>

        <!-- Asset Previews -->
        <div class="pt-6 border-t border-slate-100 dark:border-slate-800 space-y-6">
            <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100">Berkas Visual & Aset</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Logo Card -->
                <div class="rounded-2xl border border-slate-150 dark:border-slate-850 p-4 flex flex-col items-center justify-center bg-slate-50/20 dark:bg-slate-950/20">
                    <h3 class="text-xs font-bold text-slate-400 uppercase mb-4">Logo Sekolah</h3>
                    @if($profile->logo_path)
                        <img src="{{ Storage::disk('public')->url($profile->logo_path) }}" alt="Logo" class="max-h-24 object-contain rounded-xl shadow-sm bg-slate-900/10 p-2">
                    @else
                        <div class="h-24 w-24 flex items-center justify-center rounded-xl bg-slate-100 dark:bg-slate-950/40 text-slate-400 text-xs font-medium">Belum Ada Logo</div>
                    @endif
                </div>

                <!-- Favicon Card -->
                <div class="rounded-2xl border border-slate-150 dark:border-slate-850 p-4 flex flex-col items-center justify-center bg-slate-50/20 dark:bg-slate-950/20">
                    <h3 class="text-xs font-bold text-slate-400 uppercase mb-4">Favicon (Tab Icon)</h3>
                    @if($profile->favicon_path)
                        <img src="{{ Storage::disk('public')->url($profile->favicon_path) }}" alt="Favicon" class="h-12 w-12 object-contain rounded-xl shadow-sm p-1">
                    @else
                        <div class="h-12 w-12 flex items-center justify-center rounded-xl bg-slate-100 dark:bg-slate-950/40 text-slate-400 text-xs font-medium">Belum Ada Favicon</div>
                    @endif
                </div>

                <!-- Login Wallpaper Card -->
                <div class="rounded-2xl border border-slate-150 dark:border-slate-850 p-4 flex flex-col items-center justify-center bg-slate-50/20 dark:bg-slate-950/20">
                    <h3 class="text-xs font-bold text-slate-400 uppercase mb-4">Wallpaper Login</h3>
                    @if($profile->login_background_path)
                        <img src="{{ Storage::disk('public')->url($profile->login_background_path) }}" alt="Login Wallpaper" class="max-h-24 w-full object-cover rounded-xl shadow-sm">
                    @else
                        <div class="h-24 w-full flex items-center justify-center rounded-xl bg-slate-100 dark:bg-slate-950/40 text-slate-400 text-xs font-medium">Wallpaper Default</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
