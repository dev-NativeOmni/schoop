@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-6">
    <x-developer-portal.shell title="{{ $integration->name }}" subtitle="{{ $integration->integration_type }} / {{ $integration->provider_name ?? 'provider belum diisi' }}">
        <x-slot:action>
            <a href="{{ route('developer-portal.partner-integrations.edit', $integration) }}" class="inline-flex rounded-lg border border-slate-200 px-4 py-2 text-sm font-black text-slate-700 dark:border-slate-700 dark:text-slate-200">Edit</a>
        </x-slot:action>

        <div class="grid gap-4 lg:grid-cols-3">
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <p class="text-xs font-black uppercase text-slate-500 dark:text-slate-400">Status</p>
                <p class="mt-1 text-lg font-black text-slate-950 dark:text-white">{{ $integration->status }}</p>
                <p class="mt-4 text-xs text-slate-500 dark:text-slate-400">Tenant</p>
                <p class="font-bold text-slate-800 dark:text-slate-200">{{ $integration->school?->name ?? 'Global' }}</p>
                <p class="mt-4 text-xs text-slate-500 dark:text-slate-400">API Client</p>
                <p class="font-bold text-slate-800 dark:text-slate-200">{{ $integration->client?->name ?? '-' }}</p>
            </div>
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900 lg:col-span-2">
                <p class="text-xs font-black uppercase text-slate-500 dark:text-slate-400">Configuration</p>
                <pre class="mt-3 overflow-x-auto rounded-lg bg-slate-950 p-4 text-xs text-lime-100">{{ json_encode($integration->configuration, JSON_PRETTY_PRINT) }}</pre>
                <p class="mt-5 text-xs font-black uppercase text-slate-500 dark:text-slate-400">Catatan</p>
                <p class="mt-2 text-sm leading-6 text-slate-700 dark:text-slate-200">{{ $integration->notes ?: '-' }}</p>
            </div>
        </div>

        <div class="flex flex-wrap gap-2">
            @if($integration->status !== 'active')
                <form action="{{ route('developer-portal.partner-integrations.approve', $integration) }}" method="POST">
                    @csrf
                    <button class="rounded-lg bg-lime-500 px-4 py-2 text-sm font-black text-slate-950">Approve</button>
                </form>
            @endif
            <form action="{{ route('developer-portal.partner-integrations.destroy', $integration) }}" method="POST">
                @csrf
                @method('DELETE')
                <button class="rounded-lg bg-red-600 px-4 py-2 text-sm font-black text-white">Nonaktifkan</button>
            </form>
        </div>
    </x-developer-portal.shell>
</div>
@endsection
