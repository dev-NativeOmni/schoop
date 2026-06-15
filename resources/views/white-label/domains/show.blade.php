@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-slate-900 dark:text-slate-100">Detail Domain</h1>
            <p class="text-sm text-slate-500 mt-1">Konfigurasi DNS dan informasi status mapping domain.</p>
        </div>
        <a href="{{ route('white-label.domains.index', ['school_id' => $school->id]) }}" class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 dark:border-slate-800 px-5 py-3 font-bold text-slate-700 dark:text-slate-300 transition-all hover:bg-slate-50 dark:hover:bg-slate-950 text-sm">
            Kembali ke Daftar
        </a>
    </div>

    <!-- Domain Specs Card -->
    <div class="rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm space-y-6">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <h3 class="text-xs font-bold text-slate-400 uppercase">Nama Domain</h3>
                <p class="text-lg font-black text-slate-850 dark:text-slate-150 mt-1">{{ $domain->domain }}</p>
            </div>
            <div>
                <h3 class="text-xs font-bold text-slate-400 uppercase">Tipe Mapping</h3>
                <p class="text-sm font-bold text-slate-700 dark:text-slate-300 mt-1 capitalize">{{ str_replace('_', ' ', $domain->type) }}</p>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <h3 class="text-xs font-bold text-slate-400 uppercase">Status Verifikasi</h3>
                <span class="inline-flex items-center rounded-lg px-2.5 py-0.5 text-xs font-bold mt-1.5
                    @if($domain->status === 'active') bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700
                    @elseif($domain->status === 'verified') bg-blue-50 dark:bg-blue-950/20 text-blue-700
                    @elseif($domain->status === 'pending') bg-amber-50 dark:bg-amber-950/20 text-amber-700
                    @else bg-slate-100 dark:bg-slate-850 text-slate-500 @endif">
                    {{ strtoupper($domain->status) }}
                </span>
            </div>
            <div>
                <h3 class="text-xs font-bold text-slate-400 uppercase">Didaftarkan Oleh</h3>
                <p class="text-sm font-semibold text-slate-700 dark:text-slate-300 mt-1">{{ $domain->creator?->name ?? 'System' }}</p>
            </div>
        </div>

        @if($domain->notes)
            <div>
                <h3 class="text-xs font-bold text-slate-400 uppercase">Catatan</h3>
                <p class="text-sm text-slate-600 dark:text-slate-400 mt-1 leading-relaxed">{{ $domain->notes }}</p>
            </div>
        @endif

        @if($domain->status === 'pending')
            <!-- DNS Verification Token Box -->
            <div class="p-5 rounded-2xl bg-amber-50/30 dark:bg-amber-950/20 border border-amber-200/50 space-y-3">
                <h3 class="text-xs font-bold text-amber-800 dark:text-amber-400 uppercase">Petunjuk Konfigurasi DNS</h3>
                <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
                    Untuk menyelesaikan proses verifikasi, tambahkan baris record **TXT** berikut pada penyedia DNS domain Anda:
                </p>
                <div class="grid grid-cols-3 gap-2 text-xs font-mono bg-white dark:bg-slate-950 p-3 rounded-xl border border-slate-100 dark:border-slate-850">
                    <div class="font-bold text-slate-500">Host/Name:</div>
                    <div class="col-span-2 text-slate-800 dark:text-slate-200">@ atau subdomain anda</div>
                    
                    <div class="font-bold text-slate-500">Record Type:</div>
                    <div class="col-span-2 text-slate-800 dark:text-slate-200">TXT</div>
                    
                    <div class="font-bold text-slate-500">Value/Text:</div>
                    <div class="col-span-2 text-indigo-600 dark:text-indigo-400 font-bold select-all">{{ $domain->verification_token }}</div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
