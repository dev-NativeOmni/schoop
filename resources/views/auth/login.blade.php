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

<div class="min-h-[70vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-cover bg-center rounded-3xl" 
     style="{{ $brand && $brand->login_background_path ? 'background-image: url(' . Storage::disk('public')->url($brand->login_background_path) . ');' : '' }}">
    
    <div class="mx-auto max-w-md w-full rounded-3xl bg-white/95 dark:bg-slate-900/95 backdrop-blur-md p-8 border border-slate-200 dark:border-slate-800 shadow-xl space-y-6">
        <div class="text-center space-y-3">
            @if($brand && $brand->logo_path)
                <img src="{{ Storage::disk('public')->url($brand->logo_path) }}" alt="Logo {{ $brand->display_name }}" class="h-20 w-20 object-contain mx-auto rounded-2xl bg-white p-1 shadow-sm">
            @else
                <div class="h-16 w-16 bg-gradient-to-tr from-indigo-500 to-indigo-700 rounded-2xl flex items-center justify-center font-bold text-white text-xl mx-auto shadow-md">HP</div>
            @endif

            <h1 class="text-2xl font-black text-slate-800 dark:text-slate-100">
                {{ $brand ? $brand->display_name : 'Log Masuk Portal' }}
            </h1>
            <p class="text-xs text-slate-400 font-semibold leading-relaxed">
                {{ $brand && $brand->tagline ? $brand->tagline : 'HafizPlus School Platform' }}
            </p>
        </div>

        <form method="POST" action="{{ route('login.store') }}" class="space-y-4">
            @csrf

            <div class="space-y-1.5">
                <label for="login" class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase">
                    Email atau Username
                </label>
                <input
                    id="login"
                    name="login"
                    type="text"
                    value="{{ old('login') }}"
                    required
                    autofocus
                    class="block w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/40 px-4 py-3 text-slate-800 dark:text-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500 focus:outline-none"
                >
                @error('login')
                    <p class="text-xs text-red-650 font-semibold mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-1.5">
                <label for="password" class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase">
                    Password
                </label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    required
                    class="block w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/40 px-4 py-3 text-slate-800 dark:text-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500 focus:outline-none"
                >
                @error('password')
                    <p class="text-xs text-red-650 font-semibold mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center gap-2 py-1">
                <input
                    id="remember"
                    name="remember"
                    type="checkbox"
                    value="1"
                    class="h-4.5 w-4.5 rounded border-slate-350 text-indigo-600 focus:ring-indigo-500"
                >
                <label for="remember" class="text-xs font-bold text-slate-500">
                    Ingat saya di perangkat ini
                </label>
            </div>

            <button
                type="submit"
                class="w-full rounded-2xl bg-slate-900 dark:bg-slate-800 py-3.5 font-bold text-white shadow-md transition hover:bg-slate-850 dark:hover:bg-slate-950 text-sm"
            >
                Masuk Portal
            </button>
        </form>
    </div>
</div>
@endsection
