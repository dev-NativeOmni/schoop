@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <!-- Breadcrumbs -->
    <div class="flex items-center space-x-2 text-sm text-slate-500">
        <a href="{{ route('billing.plans.index') }}" class="hover:text-indigo-600">Plans</a>
        <span>/</span>
        <span class="text-slate-900 dark:text-white font-semibold">Tambah Plan Baru</span>
    </div>

    <!-- Header -->
    <div>
        <h1 class="text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">Tambah Plan Baru</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400">Buat paket langganan SaaS baru untuk sekolah.</p>
    </div>

    <!-- Form Card -->
    <div class="bg-white dark:bg-slate-900 shadow-xl rounded-2xl border border-slate-100 dark:border-slate-800 p-6 md:p-8">
        <form action="{{ route('billing.plans.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Code -->
                <div>
                    <label for="code" class="block text-sm font-bold text-slate-700 dark:text-slate-350 mb-2">Kode Paket (Unique)</label>
                    <input type="text" id="code" name="code" value="{{ old('code') }}" required placeholder="e.g. basic, pro, premium" class="w-full rounded-xl border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition">
                    @error('code')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-bold text-slate-700 dark:text-slate-350 mb-2">Nama Paket</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required placeholder="e.g. Paket Pro Lengkap" class="w-full rounded-xl border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition">
                    @error('name')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Monthly Price -->
                <div>
                    <label for="monthly_price" class="block text-sm font-bold text-slate-700 dark:text-slate-350 mb-2">Harga Bulanan (IDR)</label>
                    <input type="number" id="monthly_price" name="monthly_price" value="{{ old('monthly_price', 0) }}" min="0" required class="w-full rounded-xl border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition">
                    @error('monthly_price')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Yearly Price -->
                <div>
                    <label for="yearly_price" class="block text-sm font-bold text-slate-700 dark:text-slate-350 mb-2">Harga Tahunan (IDR)</label>
                    <input type="number" id="yearly_price" name="yearly_price" value="{{ old('yearly_price', 0) }}" min="0" required class="w-full rounded-xl border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition">
                    @error('yearly_price')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Sort Order -->
                <div>
                    <label for="sort_order" class="block text-sm font-bold text-slate-700 dark:text-slate-350 mb-2">Urutan Tampilan</label>
                    <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', 10) }}" min="0" required class="w-full rounded-xl border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition">
                    @error('sort_order')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status Aktif -->
                <div class="flex items-center pt-8">
                    <input type="checkbox" id="is_active" name="is_active" value="1" checked class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                    <label for="is_active" class="ml-2 block text-sm font-bold text-slate-700 dark:text-slate-300">Aktifkan paket agar bisa dipilih</label>
                </div>
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-bold text-slate-700 dark:text-slate-350 mb-2">Deskripsi Paket</label>
                <textarea id="description" name="description" rows="3" placeholder="Tulis deskripsi paket langganan..." class="w-full rounded-xl border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Limits JSON -->
            <div>
                <div class="flex justify-between items-center mb-2">
                    <label for="limits_raw" class="block text-sm font-bold text-slate-700 dark:text-slate-350">Konfigurasi Limits (JSON)</label>
                    <span class="text-xs text-slate-400">Harus berupa format JSON valid</span>
                </div>
                <textarea id="limits_raw" name="limits_raw" rows="6" class="w-full font-mono text-xs rounded-xl border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition">{{ old('limits_raw', "{\n  \"max_students\": 300,\n  \"max_teachers\": 30,\n  \"max_parents\": 600,\n  \"max_exports_per_month\": 100,\n  \"storage_mb\": 1024\n}") }}</textarea>
                @error('limits')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Action buttons -->
            <div class="flex items-center justify-end space-x-3 border-t border-slate-100 dark:border-slate-800 pt-6">
                <a href="{{ route('billing.plans.index') }}" class="px-4 py-2 text-sm font-bold text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 rounded-xl transition">Batal</a>
                <button type="submit" class="px-5 py-2 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl transition shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Simpan Plan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
