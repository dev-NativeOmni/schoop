@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <div class="flex flex-col mb-6 gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Pembayaran Santri</h1>
            <p class="text-sm text-gray-600">Daftar kwitansi pencatatan pembayaran manual.</p>
        </div>

        <a href="{{ route('finance.payments.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg">
            Catat Pembayaran
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-lg bg-green-50 px-4 py-3 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left">Receipt</th>
                    <th class="px-4 py-3 text-left">Santri</th>
                    <th class="px-4 py-3 text-left">Tanggal</th>
                    <th class="px-4 py-3 text-left">Metode</th>
                    <th class="px-4 py-3 text-left">Referensi</th>
                    <th class="px-4 py-3 text-left">Nominal</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($payments as $payment)
                    <tr>
                        <td class="px-4 py-3 font-semibold">{{ $payment->receipt_number }}</td>
                        <td class="px-4 py-3">
                            {{ $payment->student?->full_name ?? '-' }}
                            <span class="text-xs text-gray-500 block">
                                {{ $payment->student?->classRoom?->name ?? '-' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">{{ $payment->payment_date?->format('d M Y') }}</td>
                        <td class="px-4 py-3 uppercase text-xs font-semibold">{{ $payment->payment_method }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $payment->reference_number ?? '-' }}</td>
                        <td class="px-4 py-3 font-bold text-green-600">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-0.5 rounded-full text-xs font-semibold uppercase
                                @if($payment->status === 'posted') bg-green-50 text-green-700
                                @elseif($payment->status === 'void') bg-red-50 text-red-700
                                @else bg-gray-50 text-gray-700 @endif">
                                {{ $payment->status }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('finance.payments.show', $payment) }}" class="text-blue-600 font-semibold hover:underline">Detail</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-6 text-center text-gray-500">Belum ada pembayaran.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>

    <div class="mt-4">{{ $payments->links() }}</div>
</div>
@endsection
