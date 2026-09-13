@extends('layouts.app')

@section('content')
@php
    $schoolId = app(\App\Services\Tenancy\TenantContextService::class)->activeSchoolId();
    $brand = null;

    if ($schoolId) {
        $settings = app(\App\Services\WhiteLabel\WhiteLabelPublicationService::class)->getActivePublishedSettings($schoolId);
        $brand = $settings['brand'];
    }
@endphp

<div class="min-h-[70vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-md w-full rounded-3xl bg-white/95 dark:bg-slate-900/95 backdrop-blur-md p-8 border border-slate-200 dark:border-slate-800 shadow-xl space-y-6">
        <div class="text-center space-y-3">
            @if($brand && $brand->logo_path)
                <img src="{{ Storage::disk('public')->url($brand->logo_path) }}" alt="Logo {{ $brand->display_name }}" class="h-16 w-16 object-contain mx-auto rounded-2xl bg-white p-1 shadow-sm">
            @else
                <div class="h-16 w-16 bg-gradient-to-tr from-emerald-500 to-teal-700 rounded-2xl flex items-center justify-center font-bold text-white text-xl mx-auto shadow-md">SC</div>
            @endif

            <h1 class="text-2xl font-black text-slate-800 dark:text-slate-100">Lupa Password</h1>
            <p class="text-xs text-slate-400 font-semibold leading-relaxed">
                Masukkan email akun Anda, kami akan mengirimkan link untuk mereset password.
            </p>
        </div>

        <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
            @csrf

            <div class="space-y-1.5">
                <label for="email" class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase">
                    Email
                </label>
                <input
                    id="email"
                    name="email"
                    type="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    class="block w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/40 px-4 py-3 text-slate-800 dark:text-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500 focus:outline-none"
                >
                @error('email')
                    <p class="text-xs text-red-650 font-semibold mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button
                type="submit"
                class="w-full rounded-2xl bg-slate-900 dark:bg-slate-800 py-3.5 font-bold text-white shadow-md transition hover:bg-slate-850 dark:hover:bg-slate-950 text-sm"
            >
                Kirim Link Reset Password
            </button>

            <p class="text-center text-xs text-slate-400 font-semibold">
                <a href="{{ route('login') }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">Kembali ke halaman login</a>
            </p>
        </form>
    </div>
</div>
@endsection
