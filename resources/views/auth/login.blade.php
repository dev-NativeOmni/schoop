@extends('layouts.app')

@section('content')
@php
    $schoolId = app(\App\Services\Tenancy\TenantContextService::class)->activeSchoolId();
    $brand = null;
    $theme = null;
    
    if ($schoolId) {
        $settings = app(\App\Services\WhiteLabel\WhiteLabelPublicationService::class)->getActivePublishedSettings($schoolId);
        $brand = $settings['brand'];
        $theme = $settings['theme'];
    }
@endphp

<div class="min-h-[75vh] flex items-center justify-center py-8 px-4 sm:px-6 lg:px-8 bg-cover bg-center rounded-3xl" 
     style="{{ $brand && $brand->login_background_path ? 'background-image: url(' . Storage::disk('public')->url($brand->login_background_path) . ');' : '' }}">
    
    <div class="mx-auto max-w-md w-full rounded-2xl bg-white/95 dark:bg-slate-900/95 backdrop-blur-md p-8 border border-slate-200/80 dark:border-slate-800 shadow-xl space-y-6">
        <div class="text-center space-y-3">
            @if($brand && $brand->logo_path)
                <img src="{{ Storage::disk('public')->url($brand->logo_path) }}" alt="Logo {{ $brand->display_name }}" class="h-16 w-16 object-contain mx-auto rounded-2xl bg-white p-1 shadow-xs">
            @else
                <div class="h-14 w-14 bg-gradient-to-br from-emerald-600 to-teal-700 rounded-2xl flex items-center justify-center font-black text-white text-lg mx-auto shadow-md shadow-emerald-900/30">
                    <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
            @endif

            <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white">
                {{ $brand ? $brand->display_name : 'Log Masuk Portal' }}
            </h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 font-medium leading-relaxed">
                {{ $brand && $brand->tagline ? $brand->tagline : 'HafizPlus Islamic School & Tahfizh Platform' }}
            </p>
        </div>

        <form method="POST" action="{{ route('login.store') }}" class="space-y-4">
            @csrf

            <div class="space-y-1.5">
                <label for="login" class="text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                    Email atau Username
                </label>
                <input
                    id="login"
                    name="login"
                    type="text"
                    value="{{ old('login') }}"
                    required
                    autofocus
                    placeholder="nama@sekolah.sch.id / username"
                    class="block w-full rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-850 px-4 py-2.5 text-slate-900 dark:text-white text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none transition"
                >
                @error('login')
                    <p class="text-xs text-rose-500 font-semibold mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-1.5">
                <label for="password" class="text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                    Password
                </label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    required
                    placeholder="••••••••"
                    class="block w-full rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-850 px-4 py-2.5 text-slate-900 dark:text-white text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 focus:outline-none transition"
                >
                @error('password')
                    <p class="text-xs text-rose-500 font-semibold mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center gap-2 py-1">
                <input
                    id="remember"
                    name="remember"
                    type="checkbox"
                    value="1"
                    class="h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500"
                >
                <label for="remember" class="text-xs font-semibold text-slate-600 dark:text-slate-400">
                    Ingat saya di perangkat ini
                </label>
            </div>

            <button
                type="submit"
                class="w-full btn-natural-primary py-3 text-sm font-bold flex items-center justify-center space-x-2"
            >
                <span>Masuk Portal</span>
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </button>
        </form>
    </div>
</div>
@endsection
