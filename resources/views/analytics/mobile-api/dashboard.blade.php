@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-6">
    <x-analytics.shell title="Mobile & API Usage Analytics" subtitle="Kelemetri perangkat mobile yang terhubung, aktivitas pemanggilan API, dan status delivery webhook." :schoolId="$schoolId">
        <!-- Date Filter & Range -->
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <form action="{{ route('analytics.mobile-api.dashboard') }}" method="GET" class="flex flex-wrap gap-4">
                <input type="hidden" name="school_id" value="{{ $schoolId }}">
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300">Dari Tanggal</label>
                    <input type="date" name="date_from" value="{{ $dateFrom }}" class="mt-1 rounded-lg border border-slate-200 bg-white p-2 text-sm dark:border-slate-800 dark:bg-slate-950">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300">Sampai Tanggal</label>
                    <input type="date" name="date_until" value="{{ $dateUntil }}" class="mt-1 rounded-lg border border-slate-200 bg-white p-2 text-sm dark:border-slate-800 dark:bg-slate-950">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="rounded-lg bg-slate-950 px-4 py-2 text-sm font-bold text-white transition hover:bg-slate-800 dark:bg-lime-400 dark:text-slate-950">Filter</button>
                </div>
            </form>
        </div>

        <!-- Metric Summary Cards -->
        <div class="grid gap-4 md:grid-cols-4">
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <p class="text-xs font-black uppercase text-slate-500">Total API Requests</p>
                <p class="mt-3 text-3xl font-black text-slate-950 dark:text-white">{{ number_format($total_api_requests) }}</p>
            </div>
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <p class="text-xs font-black uppercase text-slate-500">Total API Errors</p>
                <p class="mt-3 text-3xl font-black text-slate-950 dark:text-white text-red-600 dark:text-red-400">{{ number_format($total_api_errors) }}</p>
            </div>
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <p class="text-xs font-black uppercase text-slate-500">Webhook Delivered</p>
                <p class="mt-3 text-3xl font-black text-slate-950 dark:text-white">{{ number_format($total_webhook_success) }}</p>
            </div>
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <p class="text-xs font-black uppercase text-slate-500">Webhook Failed</p>
                <p class="mt-3 text-3xl font-black text-slate-950 dark:text-white text-red-600 dark:text-red-400">{{ number_format($total_webhook_failed) }}</p>
            </div>
        </div>

        <!-- Table -->
        <div class="rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="p-6">
                <h3 class="text-lg font-black text-slate-950 dark:text-white">Trend Data Harian</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50 text-xs font-black uppercase text-slate-500 dark:border-slate-800 dark:bg-slate-850">
                            <th class="px-6 py-4">Tanggal</th>
                            <th class="px-6 py-4">Devices Registered</th>
                            <th class="px-6 py-4">Devices Active</th>
                            <th class="px-6 py-4">Android / iOS</th>
                            <th class="px-6 py-4">API Requests</th>
                            <th class="px-6 py-4">API Errors</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($snapshots as $snap)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-850">
                                <td class="px-6 py-4 font-bold text-slate-900 dark:text-white">{{ $snap['snapshot_date'] }}</td>
                                <td class="px-6 py-4">{{ number_format($snap['mobile_devices_count']) }}</td>
                                <td class="px-6 py-4">{{ number_format($snap['active_mobile_devices_count']) }}</td>
                                <td class="px-6 py-4">{{ number_format($snap['android_devices_count']) }} / {{ number_format($snap['ios_devices_count']) }}</td>
                                <td class="px-6 py-4">{{ number_format($snap['api_requests_count']) }}</td>
                                <td class="px-6 py-4 text-red-600 dark:text-red-450">{{ number_format($snap['api_error_count']) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-slate-500">Belum ada data snapshot untuk tanggal terpilih.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </x-analytics.shell>
</div>
@endsection
