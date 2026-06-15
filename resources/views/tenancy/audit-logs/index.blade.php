@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-3xl font-black tracking-tight text-slate-900">Tenant Audit Logs</h1>
        <p class="text-sm text-slate-500 mt-1">Lacak dan pantau semua tindakan konfigurasi penting di sekolah ini.</p>
    </div>

    <!-- Filters Card -->
    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <form action="{{ route('tenancy.audit-logs.index') }}" method="GET" class="grid gap-4 sm:grid-cols-2 md:grid-cols-4 items-end">
            <!-- Search -->
            <div class="space-y-1.5 col-span-2">
                <label for="search" class="text-xs font-bold text-slate-500 uppercase">Cari Tindakan / Deskripsi</label>
                <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="Contoh: update_membership, user_id, dll..." class="block w-full rounded-2xl border border-slate-200 px-4 py-2.5 text-xs text-slate-800 focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <!-- Start Date -->
            <div class="space-y-1.5">
                <label for="start_date" class="text-xs font-bold text-slate-500 uppercase">Dari Tanggal</label>
                <input type="date" id="start_date" name="start_date" value="{{ request('start_date') }}" class="block w-full rounded-2xl border border-slate-200 px-4 py-2.5 text-xs text-slate-800 focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <!-- End Date -->
            <div class="space-y-1.5">
                <label for="end_date" class="text-xs font-bold text-slate-500 uppercase">Sampai Tanggal</label>
                <input type="date" id="end_date" name="end_date" value="{{ request('end_date') }}" class="block w-full rounded-2xl border border-slate-200 px-4 py-2.5 text-xs text-slate-800 focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <!-- Action buttons -->
            <div class="col-span-4 flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
                <a href="{{ route('tenancy.audit-logs.index') }}" class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-xs font-bold text-slate-650 hover:bg-slate-100 transition-colors">
                    Reset Filter
                </a>
                <button type="submit" class="rounded-xl bg-gradient-to-r from-slate-900 to-indigo-950 px-5 py-2.5 text-xs font-bold text-white shadow-sm transition-all hover:scale-[1.02] active:scale-[0.98]">
                    Terapkan Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Logs Table Card -->
    <div class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-200 text-xs font-bold text-slate-500 uppercase tracking-wider">
                        <th class="px-6 py-4">Waktu</th>
                        <th class="px-6 py-4">Tindakan</th>
                        <th class="px-6 py-4">Oleh</th>
                        <th class="px-6 py-4">IP Address</th>
                        <th class="px-6 py-4">Metadata Perubahan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($logs as $log)
                        <tr class="hover:bg-slate-50/20 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-slate-500">
                                {{ $log->created_at->format('d M Y, H:i:s') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center rounded-md bg-slate-150 px-2 py-1 font-mono text-[10px] font-bold text-slate-700">
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="font-bold text-slate-800">{{ $log->user?->name ?? 'System' }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-slate-400 font-mono">
                                {{ $log->ip_address }}
                            </td>
                            <td class="px-6 py-4">
                                @if($log->old_values || $log->new_values)
                                    <div class="max-w-md space-y-1 text-[10px] text-slate-500 overflow-x-auto bg-slate-50/60 p-2.5 rounded-xl border border-slate-100 font-mono">
                                        @if($log->old_values)
                                            <div class="pb-1.5 mb-1.5 border-b border-slate-105">
                                                <strong class="text-red-750 block text-[9px] uppercase tracking-wider mb-0.5">Sebelum:</strong>
                                                {{ json_encode($log->old_values, JSON_PRETTY_PRINT) }}
                                            </div>
                                        @endif
                                        @if($log->new_values)
                                            <div>
                                                <strong class="text-emerald-700 block text-[9px] uppercase tracking-wider mb-0.5">Sesudah:</strong>
                                                {{ json_encode($log->new_values, JSON_PRETTY_PRINT) }}
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-slate-400 font-italic">Tidak ada detail.</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                                <svg class="mx-auto h-12 w-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                <p class="mt-4 text-sm font-bold">Belum ada audit logs terekam</p>
                                <p class="text-xs text-slate-400 mt-1">Logs akan otomatis muncul saat tindakan konfigurasi penting terjadi.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($logs->hasPages())
            <div class="border-t border-slate-100 px-6 py-4">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
