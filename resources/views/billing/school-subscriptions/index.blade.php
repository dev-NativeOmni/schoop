@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">School Subscriptions</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">Hubungkan sekolah ke subscription plan tertentu secara manual.</p>
        </div>
        <div>
            <a href="{{ route('billing.school-subscriptions.create') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl transition duration-150 shadow-md">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                Tambah Langganan Sekolah
            </a>
        </div>
    </div>

    <!-- Table Card -->
    <div class="bg-white dark:bg-slate-900 shadow-xl rounded-2xl overflow-hidden border border-slate-100 dark:border-slate-800">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-850 border-b border-slate-100 dark:border-slate-800">
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Sekolah</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Paket Aktif</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Status</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Mulai Langganan</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Akhir Periode</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($subscriptions as $sub)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-850/30 transition duration-150">
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-900 dark:text-white">{{ $sub->school?->name ?? 'Sekolah tidak ditemukan' }}</div>
                                <div class="text-xs text-slate-400">ID: {{ $sub->school_id }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-semibold text-slate-700 dark:text-slate-350">{{ $sub->plan?->name ?? 'Plan tidak ditemukan' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusClasses = match($sub->status) {
                                        'active' => 'bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-300',
                                        'trialing' => 'bg-blue-50 dark:bg-blue-950/20 text-blue-700 dark:text-blue-300',
                                        'suspended' => 'bg-amber-50 dark:bg-amber-950/20 text-amber-700 dark:text-amber-300',
                                        'expired', 'canceled' => 'bg-rose-50 dark:bg-rose-950/20 text-rose-700 dark:text-rose-300',
                                        default => 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300',
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold {{ $statusClasses }} capitalize">
                                    {{ $sub->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-500 dark:text-slate-400">
                                {{ $sub->starts_at ? $sub->starts_at->format('d M Y') : '-' }}
                            </td>
                            <td class="px-6 py-4 text-slate-500 dark:text-slate-400">
                                {{ $sub->current_period_ends_at ? $sub->current_period_ends_at->format('d M Y') : '-' }}
                                @if($sub->trial_ends_at && $sub->status == 'trialing')
                                    <span class="block text-xs text-blue-500">Trial s.d: {{ $sub->trial_ends_at->format('d M Y') }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('billing.school-subscriptions.show', $sub->id) }}" class="p-1 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition" title="Detail">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    </a>
                                    <a href="{{ route('billing.school-subscriptions.edit', $sub->id) }}" class="p-1 text-slate-400 hover:text-amber-600 dark:hover:text-amber-400 transition" title="Edit">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                    </a>
                                    <form action="{{ route('billing.school-subscriptions.destroy', $sub->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus subscription ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1 text-slate-400 hover:text-rose-600 transition" title="Hapus">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-slate-400">
                                Belum ada subscription sekolah. Klik "Tambah Langganan Sekolah" untuk mendaftarkan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($subscriptions->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-850/20">
                {{ $subscriptions->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
