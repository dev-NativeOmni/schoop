@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-6">
    <x-developer-portal.shell title="{{ $webhook->name }}" subtitle="{{ $webhook->url }}">
        <x-slot:action>
            <a href="{{ route('developer-portal.webhooks.edit', $webhook) }}" class="inline-flex rounded-lg border border-slate-200 px-4 py-2 text-sm font-black text-slate-700 dark:border-slate-700 dark:text-slate-200">Edit</a>
        </x-slot:action>

        <div class="grid gap-4 lg:grid-cols-3">
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <p class="text-xs font-black uppercase text-slate-500 dark:text-slate-400">Status</p>
                <p class="mt-1 text-lg font-black text-slate-950 dark:text-white">{{ $webhook->status }}</p>
                <p class="mt-4 text-xs text-slate-500 dark:text-slate-400">Client</p>
                <p class="font-bold text-slate-800 dark:text-slate-200">{{ $webhook->client?->name }}</p>
                <p class="mt-4 text-xs text-slate-500 dark:text-slate-400">Tenant</p>
                <p class="font-bold text-slate-800 dark:text-slate-200">{{ $webhook->school?->name ?? 'Global' }}</p>
            </div>
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900 lg:col-span-2">
                <p class="text-xs font-black uppercase text-slate-500 dark:text-slate-400">Subscribed Events</p>
                <div class="mt-3 flex flex-wrap gap-2">
                    @foreach($webhook->subscribed_events as $event)
                        <span class="rounded-full bg-lime-50 px-3 py-1 text-xs font-black text-lime-800 dark:bg-lime-950/40 dark:text-lime-200">{{ $event }}</span>
                    @endforeach
                </div>
                <div class="mt-5 grid gap-3 md:grid-cols-2">
                    <p class="text-sm text-slate-600 dark:text-slate-300">Last success: <span class="font-bold text-slate-900 dark:text-white">{{ $webhook->last_success_at?->format('d M Y H:i') ?? '-' }}</span></p>
                    <p class="text-sm text-slate-600 dark:text-slate-300">Last failure: <span class="font-bold text-slate-900 dark:text-white">{{ $webhook->last_failure_at?->format('d M Y H:i') ?? '-' }}</span></p>
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <h2 class="font-black text-slate-950 dark:text-white">Delivery Terbaru</h2>
            <div class="mt-4 divide-y divide-slate-200 dark:divide-slate-800">
                @forelse($webhook->deliveries as $delivery)
                    <a href="{{ route('developer-portal.webhook-deliveries.show', $delivery) }}" class="grid gap-2 py-3 text-sm md:grid-cols-5">
                        <span class="font-black text-slate-900 dark:text-white">{{ $delivery->event_type }}</span>
                        <span class="text-slate-600 dark:text-slate-300">{{ $delivery->status }}</span>
                        <span class="text-slate-600 dark:text-slate-300">{{ $delivery->response_status ?? '-' }}</span>
                        <span class="text-slate-600 dark:text-slate-300">{{ $delivery->attempt_count }} attempt</span>
                        <span class="text-right text-lime-700 dark:text-lime-300">{{ $delivery->created_at?->format('d M Y H:i') }}</span>
                    </a>
                @empty
                    <p class="py-4 text-sm text-slate-500 dark:text-slate-400">Belum ada delivery.</p>
                @endforelse
            </div>
        </div>

        <form action="{{ route('developer-portal.webhooks.destroy', $webhook) }}" method="POST">
            @csrf
            @method('DELETE')
            <button class="rounded-lg bg-red-600 px-4 py-2 text-sm font-black text-white">Nonaktifkan Endpoint</button>
        </form>
    </x-developer-portal.shell>
</div>
@endsection
