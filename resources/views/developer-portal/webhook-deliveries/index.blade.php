@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-6">
    <x-developer-portal.shell title="Webhook Deliveries" subtitle="Queue delivery, response, retry, dan error webhook partner.">
        <div class="overflow-x-auto rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50 text-xs uppercase text-slate-500 dark:bg-slate-950 dark:text-slate-400">
                    <tr>
                        <th class="px-4 py-3 text-left">Event</th>
                        <th class="px-4 py-3 text-left">Endpoint</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-left">Response</th>
                        <th class="px-4 py-3 text-left">Attempt</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                    @forelse($deliveries as $delivery)
                        <tr class="text-slate-700 dark:text-slate-200">
                            <td class="px-4 py-3 font-black text-slate-950 dark:text-white">{{ $delivery->event_type }}</td>
                            <td class="px-4 py-3">{{ $delivery->endpoint?->name ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $delivery->status }}</td>
                            <td class="px-4 py-3">{{ $delivery->response_status ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $delivery->attempt_count }}</td>
                            <td class="px-4 py-3 text-right"><a href="{{ route('developer-portal.webhook-deliveries.show', $delivery) }}" class="font-black text-lime-700 dark:text-lime-300">Detail</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-10 text-center text-sm text-slate-500 dark:text-slate-400">Belum ada webhook delivery.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $deliveries->links() }}
    </x-developer-portal.shell>
</div>
@endsection
