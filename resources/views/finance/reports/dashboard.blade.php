@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-6 flex-wrap gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Laporan Finance</h1>
            <p class="text-sm text-gray-600">Ringkasan tagihan, pembayaran, dan tunggakan keuangan santri.</p>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('finance.fee-categories.index') }}" class="px-4 py-2 border bg-white text-gray-700 rounded-lg text-sm font-semibold hover:bg-gray-50">
                Kategori Biaya
            </a>
            <a href="{{ route('finance.fee-items.index') }}" class="px-4 py-2 border bg-white text-gray-700 rounded-lg text-sm font-semibold hover:bg-gray-50">
                Item Biaya
            </a>
        </div>
    </div>

    <!-- Filter Form -->
    <form method="GET" action="{{ route('finance.reports.dashboard') }}" class="mb-6 bg-white rounded-xl shadow p-4 grid grid-cols-1 md:grid-cols-5 gap-4">
        <div>
            <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500">Mulai Tanggal</label>
            <input type="date" name="start_date" value="{{ $filters['start_date'] ?? $report['period']['start_date'] }}" class="mt-1 w-full rounded-lg border-gray-300 text-sm">
        </div>

        <div>
            <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500">Sampai Tanggal</label>
            <input type="date" name="end_date" value="{{ $filters['end_date'] ?? $report['period']['end_date'] }}" class="mt-1 w-full rounded-lg border-gray-300 text-sm">
        </div>

        <div>
            <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500">Kelas</label>
            <select name="class_room_id" class="mt-1 w-full rounded-lg border-gray-300 text-sm">
                <option value="">Semua Kelas</option>
                @foreach($classRooms as $classRoom)
                    <option value="{{ $classRoom->id }}" @selected(($filters['class_room_id'] ?? null) == $classRoom->id)>
                        {{ $classRoom->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500">Santri</label>
            <select name="student_id" class="mt-1 w-full rounded-lg border-gray-300 text-sm">
                <option value="">Semua Santri</option>
                @foreach($students as $student)
                    <option value="{{ $student->id }}" @selected(($filters['student_id'] ?? null) == $student->id)>
                        {{ $student->full_name }} — {{ $student->classRoom?->name ?? '-' }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="flex items-end">
            <button class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-semibold w-full hover:bg-blue-700">Filter</button>
        </div>
    </form>

    <!-- Stat Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow p-4 border-l-4 border-blue-500">
            <div class="text-xs text-gray-500 uppercase font-bold tracking-wider">Total Tagihan (Billed)</div>
            <div class="text-xl font-bold text-gray-900 mt-1">Rp {{ number_format($report['summary']['total_billed'], 0, ',', '.') }}</div>
            <span class="text-xs text-gray-400 mt-1 block">Dari {{ $report['summary']['bill_count'] }} tagihan aktif</span>
        </div>

        <div class="bg-white rounded-xl shadow p-4 border-l-4 border-green-500">
            <div class="text-xs text-gray-500 uppercase font-bold tracking-wider">Total Terbayar (Paid)</div>
            <div class="text-xl font-bold text-gray-900 mt-1">Rp {{ number_format($report['summary']['total_paid'], 0, ',', '.') }}</div>
            <span class="text-xs text-gray-400 mt-1 block">Dari {{ $report['summary']['payment_count'] }} pencatatan</span>
        </div>

        <div class="bg-white rounded-xl shadow p-4 border-l-4 border-red-500">
            <div class="text-xs text-gray-500 uppercase font-bold tracking-wider">Sisa Piutang (Outstanding)</div>
            <div class="text-xl font-bold text-red-600 mt-1">Rp {{ number_format($report['summary']['total_outstanding'], 0, ',', '.') }}</div>
            <span class="text-xs text-gray-400 mt-1 block">Sisa tagihan belum lunas</span>
        </div>

        <div class="bg-white rounded-xl shadow p-4 border-l-4 border-purple-500">
            <div class="text-xs text-gray-500 uppercase font-bold tracking-wider">Tagihan Belum Lunas</div>
            <div class="text-xl font-bold text-gray-900 mt-1">{{ $report['summary']['unpaid_count'] }}</div>
            <span class="text-xs text-gray-400 mt-1 block">Tagihan status partial/unpaid/overdue</span>
        </div>
    </div>

    <!-- Details lists -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Invoices List -->
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <div class="px-6 py-4 border-b bg-gray-50 flex items-center justify-between">
                <h2 class="font-bold text-gray-900">Tagihan Terakhir</h2>
                <a href="{{ route('finance.bills.index') }}" class="text-xs text-blue-600 font-semibold hover:underline">Lihat Semua</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-xs">
                    <thead class="bg-gray-150">
                        <tr>
                            <th class="px-4 py-2.5 text-left">Invoice</th>
                            <th class="px-4 py-2.5 text-left">Santri</th>
                            <th class="px-4 py-2.5 text-right">Total</th>
                            <th class="px-4 py-2.5 text-right">Sisa</th>
                            <th class="px-4 py-2.5 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($report['bills']->take(10) as $bill)
                            <tr>
                                <td class="px-4 py-2.5 font-semibold">
                                    <a href="{{ route('finance.bills.show', $bill) }}" class="text-blue-600 hover:underline">
                                        {{ $bill->invoice_number }}
                                    </a>
                                </td>
                                <td class="px-4 py-2.5">
                                    {{ $bill->student?->full_name ?? '-' }}
                                    <span class="text-[10px] text-gray-450 block">{{ $bill->student?->classRoom?->name ?? '-' }}</span>
                                </td>
                                <td class="px-4 py-2.5 text-right">Rp {{ number_format($bill->total_amount, 0, ',', '.') }}</td>
                                <td class="px-4 py-2.5 text-right font-medium text-red-600">Rp {{ number_format($bill->outstanding_amount, 0, ',', '.') }}</td>
                                <td class="px-4 py-2.5 text-center">
                                    <span class="px-1.5 py-0.5 rounded text-[10px] font-bold uppercase
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
                                <td colspan="5" class="px-4 py-6 text-center text-gray-500">Tidak ada tagihan dalam periode ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Payments List -->
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <div class="px-6 py-4 border-b bg-gray-50 flex items-center justify-between">
                <h2 class="font-bold text-gray-900">Pembayaran Terakhir</h2>
                <a href="{{ route('finance.payments.index') }}" class="text-xs text-blue-600 font-semibold hover:underline">Lihat Semua</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-xs">
                    <thead class="bg-gray-150">
                        <tr>
                            <th class="px-4 py-2.5 text-left">Receipt</th>
                            <th class="px-4 py-2.5 text-left">Santri</th>
                            <th class="px-4 py-2.5 text-left">Metode</th>
                            <th class="px-4 py-2.5 text-right">Nominal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($report['payments']->take(10) as $payment)
                            <tr>
                                <td class="px-4 py-2.5 font-semibold">
                                    <a href="{{ route('finance.payments.show', $payment) }}" class="text-blue-600 hover:underline">
                                        {{ $payment->receipt_number }}
                                    </a>
                                </td>
                                <td class="px-4 py-2.5">
                                    {{ $payment->student?->full_name ?? '-' }}
                                    <span class="text-[10px] text-gray-450 block">{{ $payment->student?->classRoom?->name ?? '-' }}</span>
                                </td>
                                <td class="px-4 py-2.5 uppercase text-gray-600">{{ $payment->payment_method }}</td>
                                <td class="px-4 py-2.5 text-right font-bold text-green-600">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-6 text-center text-gray-500">Tidak ada pembayaran dalam periode ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
