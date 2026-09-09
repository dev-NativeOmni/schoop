@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Tagihan Santri</h1>
            <p class="text-sm text-gray-600">Kelola tagihan dan status pembayaran santri.</p>
        </div>

        <a href="{{ route('finance.bills.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg">
            Buat Tagihan
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
                    <th class="px-4 py-3 text-left">Invoice</th>
                    <th class="px-4 py-3 text-left">Santri</th>
                    <th class="px-4 py-3 text-left">Tanggal</th>
                    <th class="px-4 py-3 text-left">Total</th>
                    <th class="px-4 py-3 text-left">Terbayar</th>
                    <th class="px-4 py-3 text-left">Sisa</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($bills as $bill)
                    <tr>
                        <td class="px-4 py-3 font-semibold">{{ $bill->invoice_number }}</td>
                        <td class="px-4 py-3">
                            <a href="{{ route('finance.ledgers.student', $bill->student) }}" class="text-blue-600 hover:underline">
                                {{ $bill->student?->full_name ?? 'Santri #' . $bill->student_id }}
                            </a>
                            <span class="text-xs text-gray-500 block">
                                {{ $bill->student?->classRoom?->name ?? '-' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            {{ $bill->issued_date?->format('d M Y') }}
                            @if($bill->due_date)
                                <span class="text-xs text-gray-500 block">Jatuh Tempo: {{ $bill->due_date?->format('d M Y') }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">Rp {{ number_format($bill->total_amount, 0, ',', '.') }}</td>
                        <td class="px-4 py-3">Rp {{ number_format($bill->paid_amount, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 font-medium text-red-600">Rp {{ number_format($bill->outstanding_amount, 0, ',', '.') }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold uppercase
                                @if($bill->status === 'paid') bg-green-50 text-green-700
                                @elseif($bill->status === 'partial') bg-yellow-50 text-yellow-700
                                @elseif($bill->status === 'void') bg-red-50 text-red-700
                                @else bg-gray-50 text-gray-700 @endif">
                                {{ $bill->status }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('finance.bills.show', $bill) }}" class="text-blue-600 hover:text-blue-900 font-medium">Detail &rarr;</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-6 text-center text-gray-500">Belum ada tagihan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>

    <div class="mt-4">{{ $bills->links() }}</div>
</div>
@endsection
