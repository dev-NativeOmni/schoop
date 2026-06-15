@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-6">
    <x-developer-portal.shell title="API Request Logs" subtitle="Audit trail request eksternal, status, durasi, scope, dan error.">
        <form method="GET" class="grid gap-3 rounded-xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900 md:grid-cols-4">
            <input name="api_client_id" value="{{ request('api_client_id') }}" placeholder="Client ID" class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-white">
            <input name="status" value="{{ request('status') }}" placeholder="HTTP status" class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-white">
            <input name="date_from" value="{{ request('date_from') }}" type="date" class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-white">
            <button class="rounded-lg bg-slate-950 px-4 py-2 text-sm font-black text-white dark:bg-lime-400 dark:text-slate-950">Filter</button>
        </form>

        <div class="overflow-x-auto rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50 text-xs uppercase text-slate-500 dark:bg-slate-950 dark:text-slate-400">
                    <tr>
                        <th class="px-4 py-3 text-left">Request</th>
                        <th class="px-4 py-3 text-left">Client</th>
                        <th class="px-4 py-3 text-left">Scope</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-left">Durasi</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                    @forelse($logs as $log)
                        <tr class="text-slate-700 dark:text-slate-200">
                            <td class="px-4 py-3">
                                <p class="font-black text-slate-950 dark:text-white">{{ $log->method }} {{ $log->path }}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400">{{ $log->request_id }} / {{ $log->created_at?->format('d M Y H:i:s') }}</p>
                            </td>
                            <td class="px-4 py-3">{{ $log->client?->name ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $log->scope_checked ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $log->response_status ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $log->duration_ms ?? '-' }} ms</td>
                            <td class="px-4 py-3 text-right"><a href="{{ route('developer-portal.request-logs.show', $log) }}" class="font-black text-lime-700 dark:text-lime-300">Detail</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-10 text-center text-sm text-slate-500 dark:text-slate-400">Belum ada request log.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $logs->links() }}
    </x-developer-portal.shell>
</div>
@endsection
