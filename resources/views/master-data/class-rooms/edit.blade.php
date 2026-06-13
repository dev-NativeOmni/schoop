@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h2 class="text-2xl font-bold">Edit Kelas</h2>
        <p class="text-sm text-slate-500">Ubah data kelas.</p>
    </div>

    <div class="max-w-xl rounded-2xl bg-white p-6 shadow-sm">
        <form method="POST" action="{{ route('master-data.class-rooms.update', $classRoom) }}" class="space-y-4">
            @csrf
            @method('PUT')

            @if(auth()->user()->isSuperAdmin())
                <div>
                    <label class="mb-2 block text-sm font-semibold">Sekolah</label>
                    <select name="school_id" class="w-full rounded-lg border border-slate-300 px-4 py-2" required>
                        <option value="">Pilih Sekolah</option>
                        @foreach ($schools as $school)
                            <option value="{{ $school->id }}" @selected(old('school_id', $classRoom->school_id) == $school->id)>
                                {{ $school->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('school_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            @else
                <input type="hidden" name="school_id" value="{{ $classRoom->school_id }}">
            @endif

            <div>
                <label class="mb-2 block text-sm font-semibold">Nama Kelas</label>
                <input type="text" name="name" value="{{ old('name', $classRoom->name) }}" class="w-full rounded-lg border border-slate-300 px-4 py-2" required>
                @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold">Tingkat</label>
                <input type="text" name="level" value="{{ old('level', $classRoom->level) }}" class="w-full rounded-lg border border-slate-300 px-4 py-2">
                @error('level') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold">Tahun Akademik</label>
                <input type="text" name="academic_year" value="{{ old('academic_year', $classRoom->academic_year) }}" class="w-full rounded-lg border border-slate-300 px-4 py-2">
                @error('academic_year') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold">Wali Kelas</label>
                <select name="homeroom_teacher_id" class="w-full rounded-lg border border-slate-300 px-4 py-2">
                    <option value="">Pilih Wali Kelas</option>
                    @foreach ($teachers as $teacher)
                        <option value="{{ $teacher->id }}" @selected(old('homeroom_teacher_id', $classRoom->homeroom_teacher_id) == $teacher->id)>
                            {{ $teacher->name }}
                        </option>
                    @endforeach
                </select>
                @error('homeroom_teacher_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-2 py-2">
                <input type="checkbox" name="is_active" value="1" id="is_active" @checked(old('is_active', $classRoom->is_active))>
                <label for="is_active" class="text-sm font-semibold">Status Aktif</label>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="rounded-lg bg-slate-900 px-4 py-2 font-semibold text-white hover:bg-slate-700">
                    Simpan Perubahan
                </button>
                <a href="{{ route('master-data.class-rooms.index') }}" class="rounded-lg border border-slate-300 px-4 py-2 font-semibold text-slate-700 hover:bg-slate-50">
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection
