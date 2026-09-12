@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-6">
    <div class="flex flex-col mb-6 gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Detail Item Tarif: {{ $feeItem->name }}</h1>
            <a href="{{ route('finance.fee-items.index') }}" class="text-sm text-blue-600">&larr; Kembali ke daftar</a>
        </div>
        <a href="{{ route('finance.fee-items.edit', $feeItem) }}" class="px-4 py-2 bg-amber-600 text-white rounded-lg text-sm">Edit Item</a>
    </div>

    <div class="bg-white rounded-xl shadow p-6 space-y-5">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <div class="text-xs text-gray-500 uppercase font-bold tracking-wider">Nama Item</div>
                <div class="text-base text-gray-900 font-semibold">{{ $feeItem->name }}</div>
            </div>

            <div>
                <div class="text-xs text-gray-500 uppercase font-bold tracking-wider">Kode Item</div>
                <div class="text-base text-gray-900 font-semibold">{{ $feeItem->code ?? '-' }}</div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <div class="text-xs text-gray-500 uppercase font-bold tracking-wider">Kategori</div>
                <div class="text-sm text-gray-900">{{ $feeItem->category?->name ?? '-' }}</div>
            </div>

            <div>
                <div class="text-xs text-gray-500 uppercase font-bold tracking-wider">Nominal Default</div>
                <div class="text-sm text-gray-900 font-bold text-blue-600">Rp {{ number_format($feeItem->default_amount, 0, ',', '.') }}</div>
            </div>

            <div>
                <div class="text-xs text-gray-500 uppercase font-bold tracking-wider">Siklus Penagihan</div>
                <div class="text-sm text-gray-900 capitalize">{{ $feeItem->billing_cycle }}</div>
            </div>
        </div>

        <div>
            <div class="text-xs text-gray-500 uppercase font-bold tracking-wider">Deskripsi</div>
            <div class="text-sm text-gray-900">{{ $feeItem->description ?? '-' }}</div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 border-t pt-4">
            <div>
                <div class="text-xs text-gray-500 uppercase font-bold tracking-wider">Urutan Tampil</div>
                <div class="text-sm text-gray-900">{{ $feeItem->sort_order }}</div>
            </div>

            <div>
                <div class="text-xs text-gray-500 uppercase font-bold tracking-wider">Wajib Diambil</div>
                <div class="text-sm text-gray-900">{{ $feeItem->is_required ? 'Ya' : 'Tidak' }}</div>
            </div>

            <div>
                <div class="text-xs text-gray-500 uppercase font-bold tracking-wider">Status Aktif</div>
                <div class="text-sm text-gray-900">{{ $feeItem->is_active ? 'Aktif' : 'Tidak Aktif' }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
