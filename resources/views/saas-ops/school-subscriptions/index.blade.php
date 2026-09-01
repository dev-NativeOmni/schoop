@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <x-saas-ops.shell title="School Subscriptions" subtitle="Manajemen langganan aktif, paket sekolah, dan periode tagihan.">
        
        {{-- Header Actions --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center space-x-2">
                <span class="text-xs font-bold text-slate-500 dark:text-slate-400">Total Langganan:</span>
                <span class="px-2.5 py-0.5 rounded-full bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 font-black text-xs">
                    {{ $subscriptions->total() }} Sekolah
                </span>
            </div>
            <a href="{{ route('saas-ops.school-subscriptions.create') }}" class="btn-natural-primary text-xs px-4 py-2.5 shadow-sm inline-flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                <span>Tambah Subscription</span>
            </a>
        </div>

        {{-- Subscriptions Table Card --}}
        <div class="card-natural overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 dark:bg-slate-900/60 border-b border-slate-200/80 dark:border-slate-800 text-slate-500 dark:text-slate-400 uppercase tracking-wider font-extrabold text-[11px]">
                        <tr>
                            <th scope="col" class="px-5 py-3.5">Nama Sekolah</th>
                            <th scope="col" class="px-5 py-3.5">Paket Langganan</th>
                            <th scope="col" class="px-5 py-3.5">Siklus Tagihan</th>
                            <th scope="col" class="px-5 py-3.5">Status</th>
                            <th scope="col" class="px-5 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80">
                        @forelse($subscriptions as $subscription)
                            <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-900/40 transition-colors">
                                <td class="px-5 py-4">
                                    <a href="{{ route('saas-ops.school-subscriptions.show', $subscription) }}" class="font-black text-slate-900 dark:text-white hover:text-emerald-600 dark:hover:text-emerald-400 text-sm block">
                                        {{ $subscription->school?->name ?? 'Sekolah Tidak Terdaftar' }}
                                    </a>
                                    <span class="text-[11px] text-slate-400 font-semibold block mt-0.5">
                                        NPSN: {{ $subscription->school?->npsn ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-5 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-200 font-extrabold text-xs">
                                        {{ $subscription->plan?->name ?? 'Custom Plan' }}
                                    </span>
                                </td>
                                <td class="px-5 py-4">
                                    <span class="font-bold text-slate-600 dark:text-slate-300 capitalize">
                                        {{ $subscription->billing_cycle ?? 'Monthly' }}
                                    </span>
                                </td>
                                <td class="px-5 py-4">
                                    @php
                                        $status = strtolower($subscription->status ?? 'active');
                                        $statusStyles = match($status) {
                                            'active' => 'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800/60',
                                            'trial' => 'bg-teal-50 dark:bg-teal-950/40 text-teal-700 dark:text-teal-300 border-teal-200 dark:border-teal-800/60',
                                            'past_due', 'suspended' => 'bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-300 border-rose-200 dark:border-rose-800/60',
                                            default => 'bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-300 border-amber-200 dark:border-amber-800/60',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-black border uppercase tracking-wider {{ $statusStyles }}">
                                        {{ $subscription->status }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <a href="{{ route('saas-ops.school-subscriptions.show', $subscription) }}" class="btn-natural-secondary text-xs px-3 py-1.5 shadow-2xs">
                                        <span>Detail</span>
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-10 text-center text-slate-400">
                                    <div class="max-w-xs mx-auto space-y-2">
                                        <p class="font-bold text-sm text-slate-600 dark:text-slate-300">Belum ada subscription sekolah.</p>
                                        <p class="text-xs text-slate-400">Tambahkan langganan pertama untuk mengaktifkan modul sekolah.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($subscriptions->hasPages())
                <div class="px-5 py-4 border-t border-slate-100 dark:border-slate-800">
                    {{ $subscriptions->links() }}
                </div>
            @endif
        </div>

    </x-saas-ops.shell>
</div>
@endsection
