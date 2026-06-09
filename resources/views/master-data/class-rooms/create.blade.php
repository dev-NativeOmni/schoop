@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h2 class="text-2xl font-bold">Tambah Kelas</h2>
        <p class="text-sm text-slate-500">Buat kelas baru.</p>
    </div>

    <div class="max-w-xl rounded-2xl bg-white p-6 shadow-sm">
        <form method="POST" action="{{ route('master-data.class-rooms.store') }}" class="space-y-4">
            @csrf

            <div>
                <label class="mb-2 block text-sm font-semibold">Sekolah</label>
                <select name="school_id" class="w-full rounded-lg border border-slate-300 px-4 py-2" required>
                    <option value="">Pilih Sekolah</option>
                    @foreach ($schools as $school)
                        <option value="{{ $school->id }}" @selected(old('school_id') == $school->id)>
                            {{ $school->name }}
                        </option>
                    @endforeach
                </select>
                @error('school_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold">Nama Kelas</label>
                <input type="text" name="name" value="{{ old('name') }}" class="w-full rounded-lg border border-slate-300 px-4 py-2" required placeholder="Contoh: X IPA 1">
                @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold">Tingkat</label>
                <input type="text" name="level" value="{{ old('level') }}" class="w-full rounded-lg border border-slate-300 px-4 py-2" placeholder="Contoh: 10">
                @error('level') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold">Tahun Akademik</label>
                <input type="text" name="academic_year" value="{{ old('academic_year', '2025/2026') }}" class="w-full rounded-lg border border-slate-300 px-4 py-2" placeholder="Contoh: 2025/2026">
                @error('academic_year') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold">Wali Kelas</label>
                <select name="homeroom_teacher_id" class="w-full rounded-lg border border-slate-300 px-4 py-2">
                    <option value="">Pilih Wali Kelas</option>
                    @foreach ($teachers as $teacher)
                        <option value="{{ $teacher->id }}" @selected(old('homeroom_teacher_id') == $teacher->id)>
                            {{ $teacher->name }}
                        </option>
                    @endforeach
                </select>
                @error('homeroom_teacher_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-2 py-2">
                <input type="checkbox" name="is_active" value="1" id="is_active" @checked(old('is_active', true))>
                <label for="is_active" class="text-sm font-semibold">Status Aktif</label>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="rounded-lg bg-slate-900 px-4 py-2 font-semibold text-white hover:bg-slate-700">
                    Simpan
                </button>
                <a href="{{ route('master-data.class-rooms.index') }}" class="rounded-lg border border-slate-300 px-4 py-2 font-semibold text-slate-700 hover:bg-slate-50">
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection
