@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-6">
    <x-developer-portal.shell title="Partner Integrations" subtitle="Registry integrasi partner, provider, tenant, dan approval operasional.">
        <x-slot:action>
            <a href="{{ route('developer-portal.partner-integrations.create') }}" class="inline-flex rounded-lg bg-slate-950 px-4 py-2 text-sm font-black text-white dark:bg-lime-400 dark:text-slate-950">Integrasi Baru</a>
        </x-slot:action>

        <div class="overflow-x-auto rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50 text-xs uppercase text-slate-500 dark:bg-slate-950 dark:text-slate-400">
                    <tr>
                        <th class="px-4 py-3 text-left">Integrasi</th>
                        <th class="px-4 py-3 text-left">Tenant</th>
                        <th class="px-4 py-3 text-left">Client</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                    @forelse($integrations as $integration)
                        <tr class="text-slate-700 dark:text-slate-200">
                            <td class="px-4 py-3">
                                <p class="font-black text-slate-950 dark:text-white">{{ $integration->name }}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400">{{ $integration->integration_type }} / {{ $integration->provider_name ?? '-' }}</p>
                            </td>
                            <td class="px-4 py-3">{{ $integration->school?->name ?? 'Global' }}</td>
                            <td class="px-4 py-3">{{ $integration->client?->name ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $integration->status }}</td>
                            <td class="px-4 py-3 text-right"><a href="{{ route('developer-portal.partner-integrations.show', $integration) }}" class="font-black text-lime-700 dark:text-lime-300">Detail</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-4 py-10 text-center text-sm text-slate-500 dark:text-slate-400">Belum ada partner integration.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $integrations->links() }}
    </x-developer-portal.shell>
</div>
@endsection
