@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-6">
    <x-developer-portal.shell title="Webhook Delivery #{{ $delivery->id }}" subtitle="{{ $delivery->event_type }}">
        <div class="grid gap-4 lg:grid-cols-3">
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <p class="text-xs font-black uppercase text-slate-500 dark:text-slate-400">Status</p>
                <p class="mt-1 text-lg font-black text-slate-950 dark:text-white">{{ $delivery->status }}</p>
                <p class="mt-4 text-xs text-slate-500 dark:text-slate-400">Endpoint</p>
                <p class="font-bold text-slate-800 dark:text-slate-200">{{ $delivery->endpoint?->name }}</p>
                <p class="mt-4 text-xs text-slate-500 dark:text-slate-400">Response</p>
                <p class="font-bold text-slate-800 dark:text-slate-200">{{ $delivery->response_status ?? '-' }}</p>
                <p class="mt-4 text-xs text-slate-500 dark:text-slate-400">Attempt</p>
                <p class="font-bold text-slate-800 dark:text-slate-200">{{ $delivery->attempt_count }}</p>
            </div>
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900 lg:col-span-2">
                <p class="text-xs font-black uppercase text-slate-500 dark:text-slate-400">Payload</p>
                <pre class="mt-3 overflow-x-auto rounded-lg bg-slate-950 p-4 text-xs text-lime-100">{{ json_encode($delivery->payload, JSON_PRETTY_PRINT) }}</pre>
                <p class="mt-5 text-xs font-black uppercase text-slate-500 dark:text-slate-400">Response / Error</p>
                <pre class="mt-3 whitespace-pre-wrap rounded-lg bg-slate-100 p-4 text-xs text-slate-800 dark:bg-slate-950 dark:text-slate-200">{{ $delivery->response_body_excerpt ?: $delivery->error_message ?: '-' }}</pre>
            </div>
        </div>

        <form action="{{ route('developer-portal.webhook-deliveries.retry', $delivery) }}" method="POST">
            @csrf
            <button class="rounded-lg bg-slate-950 px-4 py-2 text-sm font-black text-white dark:bg-lime-400 dark:text-slate-950">Retry Delivery</button>
        </form>
    </x-developer-portal.shell>
</div>
@endsection
