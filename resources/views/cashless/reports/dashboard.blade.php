@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <x-cashless.shell title="Dashboard Cashless" subtitle="Ringkasan saldo wallet, top-up, penjualan, dan refund hari ini.">
        <div class="grid gap-4 md:grid-cols-5">
            @foreach ([
                'Total Saldo' => $summary['wallet_balance'],
                'Wallet' => $summary['wallet_count'],
                'Sales Hari Ini' => $summary['sales_today'],
                'Refund Hari Ini' => $summary['refunds_today'],
                'Top-up Hari Ini' => $summary['topups_today'],
            ] as $label => $value)
                <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <p class="text-xs font-bold uppercase text-slate-500 dark:text-slate-400">{{ $label }}</p>
                    <p class="mt-2 text-xl font-black text-slate-900 dark:text-white">{{ is_numeric($value) && $label !== 'Wallet' ? 'Rp '.number_format($value, 0, ',', '.') : $value }}</p>
                </div>
            @endforeach
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-5 dark:border-slate-800 dark:bg-slate-900">
            <h2 class="font-black text-slate-900 dark:text-white">Transaksi Terbaru</h2>
            <div class="mt-4 overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="text-left text-xs uppercase text-slate-500 dark:text-slate-400">
                        <tr><th class="py-2">No</th><th>Santri</th><th>Merchant</th><th>Total</th><th>Status</th></tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse ($recentSales as $sale)
                            <tr class="text-slate-700 dark:text-slate-200">
                                <td class="py-2">{{ $sale->receipt_number }}</td>
                                <td>{{ $sale->student?->full_name }}</td>
                                <td>{{ $sale->merchant?->name }}</td>
                                <td>Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</td>
                                <td>{{ $sale->status }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="py-6 text-center text-slate-500">Belum ada transaksi.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </x-cashless.shell>
</div>
@endsection
