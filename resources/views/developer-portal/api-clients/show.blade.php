@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-6">
    <x-developer-portal.shell title="{{ $client->name }}" subtitle="Client code: {{ $client->client_code }}">
        <x-slot:action>
            <a href="{{ route('developer-portal.api-clients.edit', $client) }}" class="inline-flex rounded-lg border border-slate-200 px-4 py-2 text-sm font-black text-slate-700 dark:border-slate-700 dark:text-slate-200">Edit</a>
        </x-slot:action>

        @if($plainToken)
            <div class="rounded-xl border border-amber-200 bg-amber-50 p-4 dark:border-amber-900/60 dark:bg-amber-950/30">
                <p class="text-sm font-black text-amber-900 dark:text-amber-100">Token baru hanya tampil sekali.</p>
                <code class="mt-3 block overflow-x-auto rounded-lg bg-white p-3 text-sm text-slate-950 dark:bg-slate-950 dark:text-lime-200">{{ $plainToken }}</code>
            </div>
        @endif

        <div class="grid gap-4 lg:grid-cols-3">
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <p class="text-xs font-black uppercase text-slate-500 dark:text-slate-400">Status</p>
                <p class="mt-2 text-lg font-black text-slate-950 dark:text-white">{{ $client->status }}</p>
                <p class="mt-4 text-xs text-slate-500 dark:text-slate-400">Tenant</p>
                <p class="text-sm font-bold text-slate-800 dark:text-slate-200">{{ $client->school?->name ?? 'Global / Internal' }}</p>
                <p class="mt-4 text-xs text-slate-500 dark:text-slate-400">Owner</p>
                <p class="text-sm font-bold text-slate-800 dark:text-slate-200">{{ $client->owner_name ?? '-' }} {{ $client->owner_email ? '('.$client->owner_email.')' : '' }}</p>
            </div>
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900 lg:col-span-2">
                <p class="text-xs font-black uppercase text-slate-500 dark:text-slate-400">Scopes</p>
                <div class="mt-3 flex flex-wrap gap-2">
                    @forelse($client->scopes as $scope)
                        <span class="rounded-full bg-lime-50 px-3 py-1 text-xs font-black text-lime-800 dark:bg-lime-950/40 dark:text-lime-200">{{ $scope->code }}</span>
                    @empty
                        <span class="text-sm text-slate-500 dark:text-slate-400">Belum ada scope.</span>
                    @endforelse
                </div>
                <p class="mt-5 text-xs font-black uppercase text-slate-500 dark:text-slate-400">Allowed IPs</p>
                <p class="mt-2 text-sm text-slate-700 dark:text-slate-200">{{ $client->allowed_ips ? implode(', ', $client->allowed_ips) : 'Semua IP' }}</p>
            </div>
        </div>

        <div class="grid gap-4 lg:grid-cols-2">
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h2 class="font-black text-slate-950 dark:text-white">Token</h2>
                <form action="{{ route('developer-portal.api-clients.tokens.generate', $client) }}" method="POST" class="mt-4 grid gap-3 md:grid-cols-3">
                    @csrf
                    <input name="token_name" placeholder="Nama token" required class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                    <input name="expires_at" type="date" class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                    <button class="rounded-lg bg-slate-950 px-3 py-2 text-sm font-black text-white dark:bg-lime-400 dark:text-slate-950">Generate</button>
                </form>
                <div class="mt-4 divide-y divide-slate-200 dark:divide-slate-800">
                    @forelse($client->tokens as $token)
                        <div class="flex flex-wrap items-center justify-between gap-3 py-3 text-sm">
                            <div>
                                <p class="font-black text-slate-900 dark:text-white">{{ $token->token_name }}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400">{{ $token->token_prefix }}... / {{ $token->status }} / exp {{ $token->expires_at?->format('d M Y') ?? '-' }}</p>
                            </div>
                            <form action="{{ route('developer-portal.api-clients.tokens.revoke', [$client, $token]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="rounded-lg border border-red-200 px-3 py-1 text-xs font-black text-red-700 dark:border-red-900 dark:text-red-300">Revoke</button>
                            </form>
                        </div>
                    @empty
                        <p class="py-4 text-sm text-slate-500 dark:text-slate-400">Belum ada token.</p>
                    @endforelse
                </div>
                <form action="{{ route('developer-portal.api-clients.tokens.rotate', $client) }}" method="POST" class="mt-4">
                    @csrf
                    <button class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-black text-slate-700 dark:border-slate-700 dark:text-slate-200">Rotate Semua Token Aktif</button>
                </form>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h2 class="font-black text-slate-950 dark:text-white">Webhook Endpoint</h2>
                <div class="mt-4 divide-y divide-slate-200 dark:divide-slate-800">
                    @forelse($client->webhooks as $webhook)
                        <a href="{{ route('developer-portal.webhooks.show', $webhook) }}" class="block py-3 text-sm">
                            <span class="font-black text-slate-900 dark:text-white">{{ $webhook->name }}</span>
                            <span class="block text-xs text-slate-500 dark:text-slate-400">{{ $webhook->status }} - {{ $webhook->url }}</span>
                        </a>
                    @empty
                        <p class="py-4 text-sm text-slate-500 dark:text-slate-400">Belum ada webhook.</p>
                    @endforelse
                </div>
                <form action="{{ route('developer-portal.api-clients.revoke', $client) }}" method="POST" class="mt-6">
                    @csrf
                    @method('DELETE')
                    <button class="rounded-lg bg-red-600 px-4 py-2 text-sm font-black text-white">Revoke Client</button>
                </form>
            </div>
        </div>
    </x-developer-portal.shell>
</div>
@endsection
