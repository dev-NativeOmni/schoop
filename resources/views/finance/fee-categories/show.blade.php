@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-6">
    <div class="flex flex-col mb-6 gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Detail Kategori: {{ $feeCategory->name }}</h1>
            <a href="{{ route('finance.fee-categories.index') }}" class="text-sm text-blue-600">&larr; Kembali ke daftar</a>
        </div>
        <a href="{{ route('finance.fee-categories.edit', $feeCategory) }}" class="px-4 py-2 bg-amber-600 text-white rounded-lg text-sm">Edit Kategori</a>
    </div>

    <div class="bg-white rounded-xl shadow p-6 mb-6 space-y-4">
        <div>
            <div class="text-xs text-gray-500 uppercase font-bold tracking-wider">Nama Kategori</div>
            <div class="text-base text-gray-900 font-semibold">{{ $feeCategory->name }}</div>
        </div>

        <div>
            <div class="text-xs text-gray-500 uppercase font-bold tracking-wider">Deskripsi</div>
            <div class="text-sm text-gray-905">{{ $feeCategory->description ?? '-' }}</div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <div class="text-xs text-gray-500 uppercase font-bold tracking-wider">Urutan</div>
                <div class="text-sm text-gray-900">{{ $feeCategory->sort_order }}</div>
            </div>
            <div>
                <div class="text-xs text-gray-500 uppercase font-bold tracking-wider">Status Aktif</div>
                <div class="text-sm text-gray-900">{{ $feeCategory->is_active ? 'Aktif' : 'Tidak Aktif' }}</div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow p-6">
        <h2 class="text-lg font-bold text-gray-950 mb-4">Item Tarif di Kategori Ini</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left">Nama Item</th>
                        <th class="px-4 py-2 text-left">Kode</th>
                        <th class="px-4 py-2 text-left">Nominal Default</th>
                        <th class="px-4 py-2 text-left">Siklus</th>
                        <th class="px-4 py-2 text-left">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($feeCategory->feeItems as $item)
                        <tr>
                            <td class="px-4 py-2 font-medium">{{ $item->name }}</td>
                            <td class="px-4 py-2">{{ $item->code ?? '-' }}</td>
                            <td class="px-4 py-2">Rp {{ number_format($item->default_amount, 0, ',', '.') }}</td>
                            <td class="px-4 py-2">{{ $item->billing_cycle }}</td>
                            <td class="px-4 py-2">{{ $item->is_active ? 'Aktif' : 'Tidak' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-4 text-center text-gray-500">Belum ada item tarif untuk kategori ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
