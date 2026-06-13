@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h2 class="text-2xl font-bold">Edit Guru Tahfidz</h2>
        <p class="text-sm text-slate-500">Ubah data guru tahfidz.</p>
    </div>

    <div class="max-w-xl rounded-2xl bg-white p-6 shadow-sm">
        <form method="POST" action="{{ route('master-data.teachers.update', $teacher) }}" class="space-y-4">
            @csrf
            @method('PUT')

            @if(auth()->user()->isSuperAdmin())
                <div>
                    <label class="mb-2 block text-sm font-semibold">Sekolah</label>
                    <select name="school_id" class="w-full rounded-lg border border-slate-300 px-4 py-2" required>
                        <option value="">Pilih Sekolah</option>
                        @foreach ($schools as $school)
                            <option value="{{ $school->id }}" @selected(old('school_id', $teacher->school_id) == $school->id)>
                                {{ $school->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('school_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            @else
                <input type="hidden" name="school_id" value="{{ $teacher->school_id }}">
            @endif

            <div class="border-t border-slate-100 pt-4">
                <h3 class="font-bold text-slate-700 mb-3 text-sm">Akun User</h3>

                <div class="space-y-4">
                    <div>
                        <label class="mb-2 block text-sm font-semibold">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', $teacher->user?->name) }}" class="w-full rounded-lg border border-slate-300 px-4 py-2" required>
                        @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold">Username (Unique)</label>
                        <input type="text" name="username" value="{{ old('username', $teacher->user?->username) }}" class="w-full rounded-lg border border-slate-300 px-4 py-2" required>
                        @error('username') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold">Email (Unique)</label>
                        <input type="email" name="email" value="{{ old('email', $teacher->user?->email) }}" class="w-full rounded-lg border border-slate-300 px-4 py-2" required>
                        @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold">Telepon / HP</label>
                        <input type="text" name="phone" value="{{ old('phone', $teacher->user?->phone) }}" class="w-full rounded-lg border border-slate-300 px-4 py-2">
                        @error('phone') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold">Password</label>
                        <input type="password" name="password" class="w-full rounded-lg border border-slate-300 px-4 py-2" placeholder="Kosongkan jika tidak ingin diubah">
                        @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div class="border-t border-slate-100 pt-4">
                <h3 class="font-bold text-slate-700 mb-3 text-sm">Profil Guru</h3>

                <div class="space-y-4">
                    <div>
                        <label class="mb-2 block text-sm font-semibold">NIP / No. Pegawai</label>
                        <input type="text" name="employee_number" value="{{ old('employee_number', $teacher->employee_number) }}" class="w-full rounded-lg border border-slate-300 px-4 py-2">
                        @error('employee_number') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold">Spesialisasi</label>
                        <input type="text" name="specialization" value="{{ old('specialization', $teacher->specialization) }}" class="w-full rounded-lg border border-slate-300 px-4 py-2">
                        @error('specialization') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold">Alamat</label>
                        <textarea name="address" class="w-full rounded-lg border border-slate-300 px-4 py-2">{{ old('address', $teacher->address) }}</textarea>
                        @error('address') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold">Tanggal Bergabung</label>
                        <input type="date" name="joined_at" value="{{ old('joined_at', $teacher->joined_at?->toDateString()) }}" class="w-full rounded-lg border border-slate-300 px-4 py-2">
                        @error('joined_at') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-2 py-2">
                <input type="checkbox" name="is_active" value="1" id="is_active" @checked(old('is_active', $teacher->is_active))>
                <label for="is_active" class="text-sm font-semibold">Status Aktif</label>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="rounded-lg bg-slate-900 px-4 py-2 font-semibold text-white hover:bg-slate-700">
                    Simpan Perubahan
                </button>
                <a href="{{ route('master-data.teachers.index') }}" class="rounded-lg border border-slate-300 px-4 py-2 font-semibold text-slate-700 hover:bg-slate-50">
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection
