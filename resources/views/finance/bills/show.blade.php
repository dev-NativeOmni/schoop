@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-6">
    <div class="mb-4">
        <a href="{{ route('finance.bills.index') }}" class="text-sm text-blue-600 hover:underline">&larr; Kembali ke daftar tagihan</a>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-lg bg-green-50 px-4 py-3 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow p-6 mb-6">
        <div class="flex justify-between gap-4 flex-wrap">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $bill->invoice_number }}</h1>
                <p class="text-sm text-gray-600 font-semibold mt-1">{{ $bill->title }}</p>
                <p class="text-sm text-gray-500 mt-1">
                    Santri:
                    <a href="{{ route('finance.ledgers.student', $bill->student) }}" class="text-blue-600 hover:underline font-medium">
                        {{ $bill->student?->full_name ?? '-' }}
                    </a>
                    — Kelas: {{ $bill->student?->classRoom?->name ?? '-' }}
                </p>
            </div>

            <div class="text-right">
                <div class="text-xs text-gray-500 uppercase tracking-wider font-bold">Status</div>
                <div class="mt-1">
                    <span class="px-3 py-1 rounded-full text-xs font-semibold uppercase
                        @if($bill->status === 'paid') bg-green-100 text-green-800
                        @elseif($bill->status === 'partial') bg-yellow-100 text-yellow-800
                        @elseif($bill->status === 'void') bg-red-100 text-red-800
                        @else bg-gray-100 text-gray-800 @endif">
                        {{ $bill->status }}
                    </span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-6 border-t pt-6">
            <div>
                <div class="text-xs text-gray-500 uppercase font-bold tracking-wider">Tanggal Terbit</div>
                <div class="text-sm font-semibold mt-1 text-gray-900">{{ $bill->issued_date?->format('d M Y') }}</div>
            </div>
            <div>
                <div class="text-xs text-gray-500 uppercase font-bold tracking-wider">Jatuh Tempo</div>
                <div class="text-sm font-semibold mt-1 text-gray-900">{{ $bill->due_date ? $bill->due_date?->format('d M Y') : 'Tanpa Jatuh Tempo' }}</div>
            </div>
            <div>
                <div class="text-xs text-gray-500 uppercase font-bold tracking-wider">Total Tagihan</div>
                <div class="text-base font-bold mt-1 text-gray-950">Rp {{ number_format($bill->total_amount, 0, ',', '.') }}</div>
            </div>
            <div>
                <div class="text-xs text-gray-500 uppercase font-bold tracking-wider">Sisa Tunggakan</div>
                <div class="text-base font-bold mt-1 text-red-600">Rp {{ number_format($bill->outstanding_amount, 0, ',', '.') }}</div>
            </div>
        </div>

        @if($bill->description)
            <div class="mt-4 border-t pt-4">
                <div class="text-xs text-gray-500 uppercase font-bold tracking-wider">Catatan/Keterangan</div>
                <div class="text-sm mt-1 text-gray-750">{{ $bill->description }}</div>
            </div>
        @endif

        @if($bill->status === 'void')
            <div class="mt-4 border-t border-red-200 bg-red-50 rounded-lg p-3 text-red-800 text-sm">
                <div class="font-bold">Informasi Void/Batal:</div>
                <p>Alasan: {{ $bill->void_reason }}</p>
                <p class="text-xs text-red-600 mt-1">Dibatalkan oleh User ID: {{ $bill->voided_by }} pada {{ $bill->voided_at?->format('d M Y H:i') }}</p>
            </div>
        @endif
    </div>

    <div class="bg-white rounded-xl shadow overflow-hidden mb-6">
        <div class="px-6 py-4 border-b">
            <h2 class="font-bold text-gray-900">Rincian Item Tagihan</h2>
        </div>
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left">Nama Item</th>
                    <th class="px-6 py-3 text-left">Deskripsi</th>
                    <th class="px-6 py-3 text-center">Qty</th>
                    <th class="px-6 py-3 text-right">Nominal Satuan</th>
                    <th class="px-6 py-3 text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($bill->items as $item)
                    <tr>
                        <td class="px-6 py-3 font-medium">{{ $item->name }}</td>
                        <td class="px-6 py-3 text-gray-500">{{ $item->description ?? '-' }}</td>
                        <td class="px-6 py-3 text-center">{{ $item->quantity }}</td>
                        <td class="px-6 py-3 text-right">Rp {{ number_format($item->unit_amount, 0, ',', '.') }}</td>
                        <td class="px-6 py-3 text-right font-semibold">Rp {{ number_format($item->total_amount, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($bill->allocations->isNotEmpty())
        <div class="bg-white rounded-xl shadow overflow-hidden mb-6">
            <div class="px-6 py-4 border-b">
                <h2 class="font-bold text-gray-900">Riwayat Alokasi Pembayaran</h2>
            </div>
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left">Nomor Kwitansi</th>
                        <th class="px-6 py-3 text-left">Tanggal Bayar</th>
                        <th class="px-6 py-3 text-right">Nominal Dialokasikan</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($bill->allocations as $allocation)
                        <tr>
                            <td class="px-6 py-3">
                                <a href="{{ route('finance.payments.show', $allocation->payment) }}" class="text-blue-600 hover:underline font-semibold">
                                    {{ $allocation->payment?->receipt_number }}
                                </a>
                            </td>
                            <td class="px-6 py-3">{{ $allocation->payment?->payment_date?->format('d M Y') }}</td>
                            <td class="px-6 py-3 text-right font-semibold text-green-600">Rp {{ number_format($allocation->amount, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    @if($bill->status !== 'void' && $bill->paid_amount == 0)
        <form action="{{ route('finance.bills.void', $bill) }}" method="POST" class="bg-red-50 rounded-xl p-4 border border-red-200" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan tagihan ini? Tindakan ini tidak bisa dibatalkan.')">
            @csrf
            @method('PATCH')

            <label class="block text-sm font-semibold text-red-800">Batalkan Tagihan ini (Void)</label>
            <p class="text-xs text-red-600 mb-2">Tagihan akan dibatalkan, dan jika sudah ter-posting, ledger debit akan dibalikkan oleh ledger credit penyesuaian.</p>
            <textarea name="void_reason" rows="2" class="mt-1 w-full rounded-lg border-red-300 focus:border-red-500 focus:ring focus:ring-red-200 text-sm" placeholder="Sebutkan alasan pembatalan tagihan ini..." required></textarea>

            <button class="mt-3 px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-semibold hover:bg-red-700">
                Void Tagihan
            </button>
        </form>
    @endif
</div>
@endsection
