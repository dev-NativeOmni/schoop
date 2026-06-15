@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-6">
    <x-developer-portal.shell title="Developer Portal" subtitle="Kelola API client, scope, partner integration, request log, dan webhook eksternal.">
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            @foreach($stats as $label => $value)
                <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <p class="text-xs font-black uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ str_replace('_', ' ', $label) }}</p>
                    <p class="mt-3 text-3xl font-black text-slate-950 dark:text-white">{{ number_format($value) }}</p>
                </div>
            @endforeach
        </div>

        <div class="grid gap-4 lg:grid-cols-3">
            <a href="{{ route('developer-portal.api-clients.create') }}" class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm transition hover:border-lime-300 dark:border-slate-800 dark:bg-slate-900 dark:hover:border-lime-500">
                <p class="text-sm font-black text-slate-950 dark:text-white">Buat API Client</p>
                <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">Terbitkan credential tenant, scope granular, dan rate limit per partner.</p>
            </a>
            <a href="{{ route('developer-portal.webhooks.create') }}" class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm transition hover:border-lime-300 dark:border-slate-800 dark:bg-slate-900 dark:hover:border-lime-500">
                <p class="text-sm font-black text-slate-950 dark:text-white">Daftarkan Webhook</p>
                <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">Atur event subscription dan endpoint delivery bertanda tangan HMAC.</p>
            </a>
            <a href="{{ route('developer-portal.docs.index') }}" class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm transition hover:border-lime-300 dark:border-slate-800 dark:bg-slate-900 dark:hover:border-lime-500">
                <p class="text-sm font-black text-slate-950 dark:text-white">Buka Dokumentasi</p>
                <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">Referensi endpoint, authentication, errors, rate limit, dan versioning.</p>
            </a>
        </div>
    </x-developer-portal.shell>
</div>
@endsection
