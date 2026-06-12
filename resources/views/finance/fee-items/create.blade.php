@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Tambah Item Biaya / Tarif</h1>

    @if($errors->any())
        <div class="mb-4 rounded-lg bg-red-50 px-4 py-3 text-red-700">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('finance.fee-items.store') }}" method="POST" class="bg-white rounded-xl shadow p-6 space-y-5">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Nama Item</label>
                <input type="text"
                       name="name"
                       value="{{ old('name') }}"
                       class="mt-1 w-full rounded-lg border-gray-300"
                       required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Kode Item</label>
                <input type="text"
                       name="code"
                       value="{{ old('code') }}"
                       class="mt-1 w-full rounded-lg border-gray-300">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Kategori</label>
                <select name="finance_fee_category_id" class="mt-1 w-full rounded-lg border-gray-300">
                    <option value="">Pilih Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" @selected(old('finance_fee_category_id') == $category->id)>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Nominal Default (Rp)</label>
                <input type="number"
                       name="default_amount"
                       value="{{ old('default_amount', 0) }}"
                       min="0"
                       class="mt-1 w-full rounded-lg border-gray-300"
                       required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Siklus Penagihan</label>
                <select name="billing_cycle" class="mt-1 w-full rounded-lg border-gray-300" required>
                    <option value="once" @selected(old('billing_cycle') == 'once')>Sekali Bayar (Once)</option>
                    <option value="daily" @selected(old('billing_cycle') == 'daily')>Harian (Daily)</option>
                    <option value="weekly" @selected(old('billing_cycle') == 'weekly')>Mingguan (Weekly)</option>
                    <option value="monthly" @selected(old('billing_cycle', 'monthly') == 'monthly')>Bulanan (Monthly)</option>
                    <option value="quarterly" @selected(old('billing_cycle') == 'quarterly')>Triwulan (Quarterly)</option>
                    <option value="semester" @selected(old('billing_cycle') == 'semester')>Semester</option>
                    <option value="yearly" @selected(old('billing_cycle') == 'yearly')>Tahunan (Yearly)</option>
                </select>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
            <textarea name="description"
                      rows="3"
                      class="mt-1 w-full rounded-lg border-gray-300">{{ old('description') }}</textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Urutan Tampil</label>
                <input type="number"
                       name="sort_order"
                       value="{{ old('sort_order', 0) }}"
                       min="0"
                       class="mt-1 w-full rounded-lg border-gray-300">
            </div>

            <div class="flex items-center pt-6">
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="is_required" value="1" @checked(old('is_required', false))>
                    <span class="text-sm text-gray-700">Wajib untuk Santri</span>
                </label>
            </div>

            <div class="flex items-center pt-6">
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', true))>
                    <span class="text-sm text-gray-700">Status Aktif</span>
                </label>
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('finance.fee-items.index') }}" class="px-4 py-2 border rounded-lg">Batal</a>
            <button class="px-4 py-2 bg-blue-600 text-white rounded-lg">Simpan</button>
        </div>
    </form>
</div>
@endsection
