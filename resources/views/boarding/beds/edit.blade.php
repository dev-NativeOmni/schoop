@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between border-b border-slate-200 pb-4 dark:border-slate-800">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Edit Data Ranjang</h1>
            <p class="text-sm text-slate-500">Perbarui data ranjang yang terdaftar.</p>
        </div>
        <a href="{{ route('boarding.beds.index') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-350 dark:hover:bg-slate-950">
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
        <form action="{{ route('boarding.beds.update', $bed->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <!-- Room Selection -->
            <div>
                <label for="boarding_room_id" class="block text-sm font-semibold text-slate-700 dark:text-slate-350 mb-1">Pilih Kamar & Asrama <span class="text-rose-500">*</span></label>
                <select name="boarding_room_id" id="boarding_room_id" required class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-800 dark:bg-slate-950 dark:text-white">
                    @foreach($rooms as $room)
                        <option value="{{ $room->id }}" {{ old('boarding_room_id', $bed->boarding_room_id) == $room->id ? 'selected' : '' }}>
                            {{ $room->name }} (Asrama: {{ $room->dormitory->name }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Bed Code -->
            <div>
                <label for="code" class="block text-sm font-semibold text-slate-700 dark:text-slate-350 mb-1">Kode / Nomor Ranjang <span class="text-rose-500">*</span></label>
                <input type="text" name="code" id="code" value="{{ old('code', $bed->code) }}" required class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-800 dark:bg-slate-950 dark:text-white">
            </div>

            <!-- Status -->
            <div>
                <label for="status" class="block text-sm font-semibold text-slate-700 dark:text-slate-350 mb-1">Status Ranjang <span class="text-rose-500">*</span></label>
                <select name="status" id="status" required class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-800 dark:bg-slate-950 dark:text-white">
                    <option value="available" {{ old('status', $bed->status) === 'available' ? 'selected' : '' }}>Tersedia (Kosong)</option>
                    <option value="occupied" {{ old('status', $bed->status) === 'occupied' ? 'selected' : '' }}>Terisi</option>
                    <option value="maintenance" {{ old('status', $bed->status) === 'maintenance' ? 'selected' : '' }}>Dalam Perbaikan / Rusak</option>
                    <option value="inactive" {{ old('status', $bed->status) === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-semibold text-slate-700 dark:text-slate-350 mb-1">Keterangan Ranjang</label>
                <textarea name="description" id="description" rows="3" class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-800 dark:bg-slate-950 dark:text-white">{{ old('description', $bed->description) }}</textarea>
            </div>

            <div class="border-t border-slate-100 pt-4 dark:border-slate-800 flex justify-end gap-3">
                <a href="{{ route('boarding.beds.index') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-350 dark:hover:bg-slate-950">Batal</a>
                <button type="submit" class="rounded-xl bg-gradient-to-r from-emerald-500 to-teal-600 px-4 py-2 text-sm font-semibold text-white shadow-md hover:opacity-90 transition">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection
