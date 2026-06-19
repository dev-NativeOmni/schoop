@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto my-12 text-center space-y-6">
    <!-- Icon Locked Wrapper -->
    <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-rose-50 dark:bg-rose-950/20 text-rose-600 dark:text-rose-400 border-4 border-rose-100 dark:border-rose-950/50 shadow-md">
        <svg class="w-12 h-12 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
        </svg>
    </div>

    <!-- Main Message -->
    <div class="space-y-2">
        <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">Fitur Terkunci</h1>
        <p class="text-slate-500 dark:text-slate-400 text-sm">Modul <span class="font-extrabold text-slate-800 dark:text-slate-200">"{{ $moduleName }}"</span> tidak aktif atau belum termasuk dalam paket berlangganan sekolah Anda.</p>
    </div>

    <!-- Details Card -->
    <div class="bg-white dark:bg-slate-900 shadow-xl rounded-2xl border border-slate-100 dark:border-slate-800 p-6 text-left space-y-4">
        <div>
            <span class="text-xs text-slate-400 block uppercase font-bold tracking-wider">Sekolah</span>
            <span class="text-sm font-bold text-slate-800 dark:text-slate-200">{{ $school?->name ?? '-' }}</span>
        </div>
        <div>
            <span class="text-xs text-slate-400 block uppercase font-bold tracking-wider">Paket Aktif</span>
            <span class="text-sm font-bold text-slate-800 dark:text-slate-200">
                {{ $activeSubscription?->plan?->name ?? 'Tidak Ada / Inactive' }}
            </span>
        </div>
        <div>
            <span class="text-xs text-slate-400 block uppercase font-bold tracking-wider">Status Berlangganan</span>
            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-rose-50 dark:bg-rose-950/20 text-rose-700 dark:text-rose-300 capitalize mt-1">
                {{ $activeSubscription?->status ?? 'Inactive' }}
            </span>
        </div>
        <hr class="border-slate-100 dark:border-slate-800">
        <div>
            <span class="text-xs text-slate-400 block uppercase font-bold tracking-wider mb-1">Alasan Penolakan</span>
            <p class="text-sm text-slate-700 dark:text-slate-300 font-semibold italic">"{{ $reason }}"</p>
        </div>
    </div>

    <!-- CTA & Action buttons -->
    <div class="space-y-3 pt-4">
        @if(auth()->user()->hasRole(['super_admin', 'operations_manager']))
            <a href="{{ route('billing.school-subscriptions.index') }}" class="w-full inline-flex items-center justify-center px-4 py-2.5 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl transition duration-150 shadow-md">
                Ubah Plan Sekolah ini
            </a>
        @else
            <div class="text-xs text-slate-400">
                Silakan hubungi Super Admin sekolah Anda atau platform customer support untuk melakukan upgrade paket.
            </div>
        @endif
        <a href="{{ route('dashboard') }}" class="w-full inline-flex items-center justify-center px-4 py-2 text-sm font-bold text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 transition">
            Kembali ke Dashboard
        </a>
    </div>
</div>
@endsection
