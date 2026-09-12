@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex flex-col border-b border-slate-200 pb-4 dark:border-slate-800 gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Tambah Kamar Baru</h1>
            <p class="text-sm text-slate-500">Tambahkan unit kamar ke dalam asrama yang sudah didaftarkan.</p>
        </div>
        <a href="{{ route('boarding.rooms.index') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-350 dark:hover:bg-slate-950">
            Kembali
        </a>
    </div>

    <!-- Alert Error -->
    @if($errors->any())
        <div class="rounded-xl bg-rose-50 p-4 text-sm text-rose-850 dark:bg-rose-950/30 dark:text-rose-400">
            <ul class="list-disc pl-5 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <form action="{{ route('boarding.rooms.store') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Dormitory Selection -->
            <div>
                <label for="boarding_dormitory_id" class="block text-sm font-semibold text-slate-700 dark:text-slate-350 mb-1">Pilih Asrama <span class="text-rose-500">*</span></label>
                <select name="boarding_dormitory_id" id="boarding_dormitory_id" required class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-800 dark:bg-slate-950 dark:text-white">
                    <option value="" disabled selected>-- Pilih Asrama --</option>
                    @foreach($dormitories as $dormitory)
                        <option value="{{ $dormitory->id }}" {{ old('boarding_dormitory_id') == $dormitory->id ? 'selected' : '' }}>
                            {{ $dormitory->name }} ({{ $dormitory->gender === 'male' ? 'Putra' : ($dormitory->gender === 'female' ? 'Putri' : 'Campuran') }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Room Name -->
            <div>
                <label for="name" class="block text-sm font-semibold text-slate-700 dark:text-slate-350 mb-1">Nama Kamar <span class="text-rose-500">*</span></label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required placeholder="Contoh: Kamar Abu Bakar 01" class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-800 dark:bg-slate-950 dark:text-white">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Floor -->
                <div>
                    <label for="floor" class="block text-sm font-semibold text-slate-700 dark:text-slate-350 mb-1">Lantai</label>
                    <input type="text" name="floor" id="floor" value="{{ old('floor') }}" placeholder="Contoh: 1, 2, atau Basemen" class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-800 dark:bg-slate-950 dark:text-white">
                </div>

                <!-- Capacity -->
                <div>
                    <label for="capacity" class="block text-sm font-semibold text-slate-700 dark:text-slate-350 mb-1">Kapasitas Maksimal Ranjang <span class="text-rose-500">*</span></label>
                    <input type="number" name="capacity" id="capacity" value="{{ old('capacity', 4) }}" min="1" required class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-800 dark:bg-slate-950 dark:text-white">
                </div>
            </div>

            <!-- Status -->
            <div>
                <label class="inline-flex items-center cursor-pointer mt-2">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="sr-only peer">
                    <div class="relative w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-slate-600 peer-checked:bg-emerald-500"></div>
                    <span class="ms-3 text-sm font-semibold text-slate-700 dark:text-slate-350">Aktif & Dapat Digunakan</span>
                </label>
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-semibold text-slate-700 dark:text-slate-350 mb-1">Catatan / Keterangan</label>
                <textarea name="description" id="description" rows="3" placeholder="Masukkan keterangan tambahan jika ada..." class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-800 dark:bg-slate-950 dark:text-white">{{ old('description') }}</textarea>
            </div>

            <div class="border-t border-slate-100 pt-4 dark:border-slate-800 flex justify-end gap-3">
                <a href="{{ route('boarding.rooms.index') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-350 dark:hover:bg-slate-950">Batal</a>
                <button type="submit" class="rounded-xl bg-gradient-to-r from-emerald-500 to-teal-600 px-4 py-2 text-sm font-semibold text-white shadow-md hover:opacity-90 transition">Simpan Kamar</button>
            </div>
        </form>
    </div>
</div>
@endsection
