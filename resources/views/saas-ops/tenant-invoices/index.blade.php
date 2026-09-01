@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <x-saas-ops.shell title="Tenant Invoices" subtitle="Daftar tagihan langganan SaaS, status pembayaran sekolah, dan riwayat mutasi.">
        
        {{-- Header Actions --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center space-x-2">
                <span class="text-xs font-bold text-slate-500 dark:text-slate-400">Total Invoice:</span>
                <span class="px-2.5 py-0.5 rounded-full bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 font-black text-xs">
                    {{ $invoices->total() }} Tagihan
                </span>
            </div>
            <a href="{{ route('saas-ops.tenant-invoices.create') }}" class="btn-natural-primary text-xs px-4 py-2.5 shadow-sm inline-flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                <span>Buat Invoice Baru</span>
            </a>
        </div>

        {{-- Invoices Table Card --}}
        <div class="card-natural overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 dark:bg-slate-900/60 border-b border-slate-200/80 dark:border-slate-800 text-slate-500 dark:text-slate-400 uppercase tracking-wider font-extrabold text-[11px]">
                        <tr>
                            <th scope="col" class="px-5 py-3.5">Nomor Invoice</th>
                            <th scope="col" class="px-5 py-3.5">Nama Sekolah</th>
                            <th scope="col" class="px-5 py-3.5">Total Tagihan</th>
                            <th scope="col" class="px-5 py-3.5">Status Bayar</th>
                            <th scope="col" class="px-5 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80">
                        @forelse($invoices as $invoice)
                            <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-900/40 transition-colors">
                                <td class="px-5 py-4">
                                    <a href="{{ route('saas-ops.tenant-invoices.show', $invoice) }}" class="font-mono font-bold text-slate-900 dark:text-white hover:text-emerald-600 dark:hover:text-emerald-400 text-xs block">
                                        {{ $invoice->invoice_number }}
                                    </a>
                                </td>
                                <td class="px-5 py-4">
                                    <span class="font-bold text-slate-800 dark:text-slate-200 text-sm block">
                                        {{ $invoice->school?->name ?? 'Sekolah Tidak Terdaftar' }}
                                    </span>
                                </td>
                                <td class="px-5 py-4">
                                    <span class="font-extrabold text-slate-900 dark:text-white text-sm">
                                        Rp {{ number_format($invoice->balance_amount ?? $invoice->total_amount ?? 0, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td class="px-5 py-4">
                                    @php
                                        $status = strtolower($invoice->status ?? 'unpaid');
                                        $statusStyles = match($status) {
                                            'paid', 'settled' => 'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800/60',
                                            'unpaid', 'pending' => 'bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-300 border-amber-200 dark:border-amber-800/60',
                                            default => 'bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-300 border-rose-200 dark:border-rose-800/60',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-black border uppercase tracking-wider {{ $statusStyles }}">
                                        {{ $invoice->status }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <a href="{{ route('saas-ops.tenant-invoices.show', $invoice) }}" class="btn-natural-secondary text-xs px-3 py-1.5 shadow-2xs">
                                        <span>Detail</span>
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-10 text-center text-slate-400">
                                    <p class="font-bold text-sm text-slate-600 dark:text-slate-300">Belum ada data invoice.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($invoices->hasPages())
                <div class="px-5 py-4 border-t border-slate-100 dark:border-slate-800">
                    {{ $invoices->links() }}
                </div>
            @endif
        </div>

    </x-saas-ops.shell>
</div>
@endsection
