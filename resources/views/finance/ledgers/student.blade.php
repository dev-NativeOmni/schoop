@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <div class="mb-4">
        <a href="{{ route('finance.bills.index') }}" class="text-sm text-blue-600 hover:underline">&larr; Kembali ke daftar tagihan</a>
    </div>

    <div class="mb-6 bg-white rounded-xl shadow p-6">
        <h1 class="text-2xl font-bold text-gray-900">Buku Besar Pembantu (Ledger) Santri</h1>
        <div class="mt-2 text-sm text-gray-600 font-medium">
            Nama Santri: {{ $student->full_name }}
        </div>
        <div class="text-xs text-gray-500">
            Kelas: {{ $student->classRoom?->name ?? '-' }} | No. Induk: {{ $student->student_number ?? '-' }}
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow p-5 border-l-4 border-blue-500">
            <div class="text-xs text-gray-500 uppercase tracking-wider font-bold">Total Debit (Tagihan)</div>
            <div class="text-xl font-bold text-gray-900 mt-1">Rp {{ number_format($balance['debit'], 0, ',', '.') }}</div>
        </div>

        <div class="bg-white rounded-xl shadow p-5 border-l-4 border-green-500">
            <div class="text-xs text-gray-500 uppercase tracking-wider font-bold">Total Kredit (Pembayaran)</div>
            <div class="text-xl font-bold text-gray-900 mt-1">Rp {{ number_format($balance['credit'], 0, ',', '.') }}</div>
        </div>

        <div class="bg-white rounded-xl shadow p-5 border-l-4 border-red-500">
            <div class="text-xs text-gray-500 uppercase tracking-wider font-bold">Saldo Piutang / Tunggakan</div>
            <div class="text-xl font-bold text-red-600 mt-1">Rp {{ number_format($balance['balance'], 0, ',', '.') }}</div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <div class="px-6 py-4 border-b bg-gray-50">
            <h2 class="font-bold text-gray-900">Histori Mutasi Ledger</h2>
        </div>
        <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="px-4 py-3 text-left">Tanggal</th>
                    <th class="px-4 py-3 text-left">Keterangan</th>
                    <th class="px-4 py-3 text-right">Debit (+)</th>
                    <th class="px-4 py-3 text-right">Kredit (-)</th>
                    <th class="px-4 py-3 text-right">Saldo Kumulatif</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @php
                    $runningBalance = 0;
                @endphp
                @forelse($entries as $entry)
                    @php
                        if ($entry->direction === 'debit') {
                            $runningBalance += $entry->amount;
                        } else {
                            $runningBalance -= $entry->amount;
                        }
                    @endphp
                    <tr>
                        <td class="px-4 py-3 text-gray-650">{{ $entry->entry_date?->format('d M Y') }}</td>
                        <td class="px-4 py-3">
                            <span class="font-medium text-gray-900">{{ $entry->description ?? '-' }}</span>
                            <span class="text-xs text-gray-400 block">Sumber: {{ class_basename($entry->source_type) }} #{{ $entry->source_id }}</span>
                        </td>
                        <td class="px-4 py-3 text-right text-gray-900">
                            @if($entry->direction === 'debit')
                                Rp {{ number_format($entry->amount, 0, ',', '.') }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right text-green-700">
                            @if($entry->direction === 'credit')
                                Rp {{ number_format($entry->amount, 0, ',', '.') }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right font-semibold @if($runningBalance > 0) text-red-600 @else text-gray-900 @endif">
                            Rp {{ number_format($runningBalance, 0, ',', '.') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-gray-500">Belum ada ledger entry untuk santri ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>
</div>
@endsection
