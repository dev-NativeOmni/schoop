@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Keuangan Saya</h1>
        <p class="text-sm text-gray-600">Rincian tagihan pribadi dan histori pembayaran Anda.</p>
    </div>

    @if(! $student)
        <div class="bg-white rounded-xl shadow p-6 text-gray-600 text-center">
            Profil santri Anda belum terhubung. Silakan hubungi admin sekolah.
        </div>
    @else
        @if($balance)
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-white rounded-xl shadow p-5 border-l-4 border-blue-500">
                    <div class="text-xs text-gray-500 uppercase tracking-wider font-bold">Total Kewajiban Tagihan</div>
                    <div class="text-lg font-bold text-gray-900 mt-1">Rp {{ number_format($balance['debit'], 0, ',', '.') }}</div>
                </div>

                <div class="bg-white rounded-xl shadow p-5 border-l-4 border-green-500">
                    <div class="text-xs text-gray-500 uppercase tracking-wider font-bold">Total Terbayar</div>
                    <div class="text-lg font-bold text-gray-900 mt-1">Rp {{ number_format($balance['credit'], 0, ',', '.') }}</div>
                </div>

                <div class="bg-white rounded-xl shadow p-5 border-l-4 border-red-500">
                    <div class="text-xs text-gray-500 uppercase tracking-wider font-bold">Sisa Tunggakan</div>
                    <div class="text-lg font-bold text-red-600 mt-1">Rp {{ number_format($balance['balance'], 0, ',', '.') }}</div>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left: Bills List -->
            <div class="lg:col-span-2 bg-white rounded-xl shadow overflow-hidden">
                <div class="px-6 py-4 border-b bg-gray-50">
                    <h2 class="font-bold text-gray-900">Daftar Tagihan Saya</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-100 text-gray-750">
                            <tr>
                                <th class="px-4 py-3 text-left">Invoice</th>
                                <th class="px-4 py-3 text-left">Judul Tagihan</th>
                                <th class="px-4 py-3 text-right">Total</th>
                                <th class="px-4 py-3 text-right">Sisa</th>
                                <th class="px-4 py-3 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($bills as $bill)
                                <tr>
                                    <td class="px-4 py-3 font-semibold text-gray-900">{{ $bill->invoice_number }}</td>
                                    <td class="px-4 py-3">
                                        <div class="font-medium text-gray-800">{{ $bill->title }}</div>
                                        <span class="text-xs text-gray-400 block">Jatuh Tempo: {{ $bill->due_date ? $bill->due_date?->format('d M Y') : 'Tanpa Jatuh Tempo' }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-right text-gray-900">Rp {{ number_format($bill->total_amount, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 text-right font-medium text-red-600">Rp {{ number_format($bill->outstanding_amount, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="px-2 py-0.5 rounded-full text-xs font-semibold uppercase
                                            @if($bill->status === 'paid') bg-green-50 text-green-700
                                            @elseif($bill->status === 'partial') bg-yellow-50 text-yellow-700
                                            @elseif($bill->status === 'void') bg-red-50 text-red-700
                                            @else bg-gray-50 text-gray-700 @endif">
                                            {{ $bill->status }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-6 text-center text-gray-500">Belum ada tagihan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Right: Payments List -->
            <div class="bg-white rounded-xl shadow overflow-hidden">
                <div class="px-6 py-4 border-b bg-gray-50">
                    <h2 class="font-bold text-gray-900">Histori Pembayaran</h2>
                </div>
                <div class="overflow-y-auto max-h-[500px]">
                    <div class="divide-y divide-gray-100">
                        @forelse($payments as $payment)
                            <div class="p-4 hover:bg-gray-50">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <span class="text-xs font-bold text-gray-400 uppercase block">{{ $payment->payment_date?->format('d M Y') }}</span>
                                        <span class="font-semibold text-gray-900 text-sm block mt-0.5">{{ $payment->receipt_number }}</span>
                                    </div>
                                    <span class="font-bold text-green-600 text-sm">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                                </div>
                                <div class="mt-2 flex items-center justify-between text-xs text-gray-500">
                                    <span class="uppercase font-semibold px-1.5 py-0.5 rounded bg-gray-100">{{ $payment->payment_method }}</span>
                                    @if($payment->status === 'void')
                                        <span class="text-red-650 font-bold uppercase">Batal (Void)</span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="p-6 text-center text-gray-500 text-sm">Belum ada histori pembayaran.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
