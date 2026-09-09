@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-6">
    <div class="mb-4">
        <a href="{{ route('finance.payments.index') }}" class="text-sm text-blue-600 hover:underline">&larr; Kembali ke daftar pembayaran</a>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-lg bg-green-50 px-4 py-3 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow p-6 mb-6">
        <div class="flex justify-between gap-4 flex-wrap">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $payment->receipt_number }}</h1>
                <p class="text-sm text-gray-500 mt-1">
                    Santri:
                    <a href="{{ route('finance.ledgers.student', $payment->student) }}" class="text-blue-600 hover:underline font-semibold">
                        {{ $payment->student?->full_name ?? '-' }}
                    </a>
                    — Kelas: {{ $payment->student?->classRoom?->name ?? '-' }}
                </p>
            </div>

            <div class="text-right">
                <div class="text-xs text-gray-500 uppercase tracking-wider font-bold">Status</div>
                <div class="mt-1">
                    <span class="px-3 py-1 rounded-full text-xs font-semibold uppercase
                        @if($payment->status === 'posted') bg-green-100 text-green-800
                        @elseif($payment->status === 'void') bg-red-100 text-red-800
                        @else bg-gray-100 text-gray-800 @endif">
                        {{ $payment->status }}
                    </span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-6 border-t pt-6">
            <div>
                <div class="text-xs text-gray-500 uppercase font-bold tracking-wider">Tanggal Pembayaran</div>
                <div class="text-sm font-semibold mt-1 text-gray-900">{{ $payment->payment_date?->format('d M Y') }}</div>
            </div>

            <div>
                <div class="text-xs text-gray-500 uppercase font-bold tracking-wider">Metode Pembayaran</div>
                <div class="text-sm font-semibold mt-1 text-gray-900 uppercase">{{ $payment->payment_method }}</div>
            </div>

            <div>
                <div class="text-xs text-gray-500 uppercase font-bold tracking-wider">Nomor Referensi</div>
                <div class="text-sm font-semibold mt-1 text-gray-900">{{ $payment->reference_number ?? '-' }}</div>
            </div>

            <div>
                <div class="text-xs text-gray-500 uppercase font-bold tracking-wider">Total Nominal</div>
                <div class="text-base font-bold mt-1 text-green-600">Rp {{ number_format($payment->amount, 0, ',', '.') }}</div>
            </div>
        </div>

        @if($payment->note)
            <div class="mt-4 border-t pt-4">
                <div class="text-xs text-gray-500 uppercase font-bold tracking-wider">Catatan</div>
                <div class="text-sm mt-1 text-gray-800">{{ $payment->note }}</div>
            </div>
        @endif

        @if($payment->status === 'void')
            <div class="mt-4 border-t border-red-200 bg-red-50 rounded-lg p-3 text-red-800 text-sm">
                <div class="font-bold">Informasi Void/Batal:</div>
                <p>Alasan: {{ $payment->void_reason }}</p>
                <p class="text-xs text-red-600 mt-1">Dibatalkan oleh User ID: {{ $payment->voided_by }} pada {{ $payment->voided_at?->format('d M Y H:i') }}</p>
            </div>
        @endif
    </div>

    <div class="bg-white rounded-xl shadow overflow-hidden mb-6">
        <div class="px-6 py-4 border-b">
            <h2 class="font-bold text-gray-900">Alokasi ke Tagihan</h2>
        </div>
        <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left">Nomor Invoice</th>
                    <th class="px-6 py-3 text-left">Judul Tagihan</th>
                    <th class="px-6 py-3 text-right">Alokasi Nominal</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($payment->allocations as $allocation)
                    <tr>
                        <td class="px-6 py-3 font-medium">
                            <a href="{{ route('finance.bills.show', $allocation->bill) }}" class="text-blue-600 hover:underline">
                                {{ $allocation->bill?->invoice_number ?? '-' }}
                            </a>
                        </td>
                        <td class="px-6 py-3">{{ $allocation->bill?->title ?? '-' }}</td>
                        <td class="px-6 py-3 text-right font-bold text-green-600">Rp {{ number_format($allocation->amount, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-6 py-6 text-center text-gray-500">
                            Pembayaran belum dialokasikan ke tagihan tertentu.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>

    @if($payment->status !== 'void')
        <form action="{{ route('finance.payments.void', $payment) }}" method="POST" class="bg-red-50 rounded-xl p-4 border border-red-200" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pembayaran ini? Tindakan ini tidak bisa dibatalkan.')">
            @csrf
            @method('PATCH')

            <label class="block text-sm font-semibold text-red-800">Batalkan Pembayaran ini (Void)</label>
            <p class="text-xs text-red-600 mb-2">Pembayaran akan divoid, saldo tagihan akan kembali ditagihkan, dan ledger credit akan dibalikkan oleh ledger debit penyesuaian.</p>
            <textarea name="void_reason" rows="2" class="mt-1 w-full rounded-lg border-red-300 focus:border-red-500 focus:ring focus:ring-red-200 text-sm" placeholder="Sebutkan alasan pembatalan pembayaran ini..." required></textarea>

            <button class="mt-3 px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-semibold hover:bg-red-700">
                Void Pembayaran
            </button>
        </form>
    @endif
</div>
@endsection
