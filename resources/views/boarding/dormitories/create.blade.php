@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between border-b border-slate-200 pb-4 dark:border-slate-800">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Tambah Asrama</h1>
            <p class="text-sm text-slate-500">Buat asrama baru untuk penempatan santri.</p>
        </div>
        <a href="{{ route('boarding.dormitories.index') }}" class="text-sm font-bold text-slate-600 hover:underline dark:text-slate-400">Kembali ke Daftar</a>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <form action="{{ route('boarding.dormitories.store') }}" method="POST" class="space-y-4">
            @csrf
            
            <div>
                <label for="name" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Nama Asrama <span class="text-rose-500">*</span></label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="Contoh: Asrama Al-Farabi" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-emerald-500 focus:outline-none dark:border-slate-800 dark:bg-slate-950 dark:text-white" required>
                @error('name') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="gender" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Kategori Gender <span class="text-rose-500">*</span></label>
                    <select name="gender" id="gender" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-emerald-500 focus:outline-none dark:border-slate-800 dark:bg-slate-950 dark:text-white" required>
                        <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>Putra (Male)</option>
                        <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Putri (Female)</option>
                        <option value="mixed" {{ old('gender') === 'mixed' ? 'selected' : '' }}>Campuran (Mixed)</option>
                    </select>
                    @error('gender') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="capacity" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Kapasitas Asrama (Total Ranjang) <span class="text-rose-500">*</span></label>
                    <input type="number" name="capacity" id="capacity" value="{{ old('capacity', 0) }}" min="1" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-emerald-500 focus:outline-none dark:border-slate-800 dark:bg-slate-950 dark:text-white" required>
                    @error('capacity') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label for="description" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Keterangan / Catatan</label>
                <textarea name="description" id="description" rows="3" placeholder="Informasi detail mengenai asrama..." class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-emerald-500 focus:outline-none dark:border-slate-800 dark:bg-slate-950 dark:text-white">{{ old('description') }}</textarea>
                @error('description') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="rounded border-slate-300 text-emerald-500 focus:ring-emerald-500">
                <label for="is_active" class="ml-2 text-sm font-semibold text-slate-700 dark:text-slate-300">Asrama ini aktif dan dapat digunakan</label>
            </div>

            <div class="pt-4 flex justify-end gap-2 border-t border-slate-100 dark:border-slate-800">
                <a href="{{ route('boarding.dormitories.index') }}" class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition dark:border-slate-800 dark:text-slate-300 dark:hover:bg-slate-950">Batal</a>
                <button type="submit" class="rounded-xl bg-gradient-to-r from-emerald-500 to-teal-600 px-6 py-2.5 text-sm font-semibold text-white shadow-md hover:opacity-90 transition">Simpan Asrama</button>
            </div>
        </form>
    </div>
</div>
@endsection
