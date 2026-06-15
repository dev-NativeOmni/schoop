@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-6">
    <x-developer-portal.shell title="API Clients" subtitle="Akses eksternal per tenant dengan token, scope, allowlist IP, dan rate limit.">
        <x-slot:action>
            <a href="{{ route('developer-portal.api-clients.create') }}" class="inline-flex rounded-lg bg-slate-950 px-4 py-2 text-sm font-black text-white dark:bg-lime-400 dark:text-slate-950">Client Baru</a>
        </x-slot:action>

        <div class="overflow-x-auto rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50 text-xs uppercase text-slate-500 dark:bg-slate-950 dark:text-slate-400">
                    <tr>
                        <th class="px-4 py-3 text-left">Client</th>
                        <th class="px-4 py-3 text-left">Tenant</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-left">Scopes</th>
                        <th class="px-4 py-3 text-left">Rate</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                    @forelse($clients as $client)
                        <tr class="text-slate-700 dark:text-slate-200">
                            <td class="px-4 py-3">
                                <p class="font-black text-slate-950 dark:text-white">{{ $client->name }}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400">{{ $client->client_code }}</p>
                            </td>
                            <td class="px-4 py-3">{{ $client->school?->name ?? 'Global' }}</td>
                            <td class="px-4 py-3"><span class="rounded-full bg-slate-100 px-2 py-1 text-xs font-bold dark:bg-slate-800">{{ $client->status }}</span></td>
                            <td class="px-4 py-3">{{ $client->scopes->pluck('code')->take(3)->join(', ') ?: '-' }}</td>
                            <td class="px-4 py-3">{{ $client->rate_limit_per_minute }}/min</td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('developer-portal.api-clients.show', $client) }}" class="font-black text-lime-700 dark:text-lime-300">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-10 text-center text-sm text-slate-500 dark:text-slate-400">Belum ada API client.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $clients->links() }}
    </x-developer-portal.shell>
</div>
@endsection
