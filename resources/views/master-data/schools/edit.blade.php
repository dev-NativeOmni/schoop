@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h2 class="text-2xl font-bold">Edit Sekolah</h2>
        <p class="text-sm text-slate-500">Ubah data sekolah.</p>
    </div>

    <div class="max-w-xl rounded-2xl bg-white p-6 shadow-sm">
        <form method="POST" action="{{ route('master-data.schools.update', $school) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="mb-2 block text-sm font-semibold">Nama Sekolah</label>
                <input type="text" name="name" value="{{ old('name', $school->name) }}" class="w-full rounded-lg border border-slate-300 px-4 py-2" required>
                @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold">Kode Sekolah (Unique)</label>
                <input type="text" name="code" value="{{ old('code', $school->code) }}" class="w-full rounded-lg border border-slate-300 px-4 py-2" required>
                @error('code') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold">NPSN</label>
                <input type="text" name="npsn" value="{{ old('npsn', $school->npsn) }}" class="w-full rounded-lg border border-slate-300 px-4 py-2">
                @error('npsn') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold">Email</label>
                <input type="email" name="email" value="{{ old('email', $school->email) }}" class="w-full rounded-lg border border-slate-300 px-4 py-2">
                @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold">Telepon</label>
                <input type="text" name="phone" value="{{ old('phone', $school->phone) }}" class="w-full rounded-lg border border-slate-300 px-4 py-2">
                @error('phone') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold">Alamat</label>
                <textarea name="address" class="w-full rounded-lg border border-slate-300 px-4 py-2">{{ old('address', $school->address) }}</textarea>
                @error('address') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold">Warna Utama (HEX)</label>
                <input type="text" name="primary_color" value="{{ old('primary_color', $school->primary_color) }}" class="w-full rounded-lg border border-slate-300 px-4 py-2">
                @error('primary_color') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold">Warna Sekunder (HEX)</label>
                <input type="text" name="secondary_color" value="{{ old('secondary_color', $school->secondary_color) }}" class="w-full rounded-lg border border-slate-300 px-4 py-2">
                @error('secondary_color') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-2 py-2">
                <input type="checkbox" name="is_active" value="1" id="is_active" @checked(old('is_active', $school->is_active))>
                <label for="is_active" class="text-sm font-semibold">Status Aktif</label>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="rounded-lg bg-slate-900 px-4 py-2 font-semibold text-white hover:bg-slate-700">
                    Simpan Perubahan
                </button>
                <a href="{{ route('master-data.schools.index') }}" class="rounded-lg border border-slate-300 px-4 py-2 font-semibold text-slate-700 hover:bg-slate-50">
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection
