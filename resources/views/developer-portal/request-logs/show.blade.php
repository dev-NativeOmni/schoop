@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-6">
    <x-developer-portal.shell title="Request Log #{{ $log->id }}" subtitle="{{ $log->request_id }}">
        <div class="grid gap-4 lg:grid-cols-3">
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <p class="text-xs font-black uppercase text-slate-500 dark:text-slate-400">Request</p>
                <p class="mt-1 font-black text-slate-950 dark:text-white">{{ $log->method }} {{ $log->path }}</p>
                <p class="mt-4 text-xs text-slate-500 dark:text-slate-400">Status / Duration</p>
                <p class="font-bold text-slate-800 dark:text-slate-200">{{ $log->response_status ?? '-' }} / {{ $log->duration_ms ?? '-' }} ms</p>
                <p class="mt-4 text-xs text-slate-500 dark:text-slate-400">Scope</p>
                <p class="font-bold text-slate-800 dark:text-slate-200">{{ $log->scope_checked ?? '-' }}</p>
            </div>
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900 lg:col-span-2">
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <p class="text-xs font-black uppercase text-slate-500 dark:text-slate-400">Client</p>
                        <p class="font-bold text-slate-800 dark:text-slate-200">{{ $log->client?->name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-black uppercase text-slate-500 dark:text-slate-400">Tenant</p>
                        <p class="font-bold text-slate-800 dark:text-slate-200">{{ $log->school?->name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-black uppercase text-slate-500 dark:text-slate-400">IP</p>
                        <p class="font-bold text-slate-800 dark:text-slate-200">{{ $log->ip_address ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-black uppercase text-slate-500 dark:text-slate-400">User Agent</p>
                        <p class="break-words text-sm text-slate-700 dark:text-slate-200">{{ $log->user_agent ?? '-' }}</p>
                    </div>
                </div>
                <p class="mt-5 text-xs font-black uppercase text-slate-500 dark:text-slate-400">Error</p>
                <pre class="mt-3 whitespace-pre-wrap rounded-lg bg-slate-100 p-4 text-xs text-slate-800 dark:bg-slate-950 dark:text-slate-200">{{ $log->error_message ?? '-' }}</pre>
            </div>
        </div>
    </x-developer-portal.shell>
</div>
@endsection
