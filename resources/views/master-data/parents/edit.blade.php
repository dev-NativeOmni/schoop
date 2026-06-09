@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h2 class="text-2xl font-bold">Edit Orang Tua / Wali</h2>
        <p class="text-sm text-slate-500">Ubah data orang tua.</p>
    </div>

    <div class="max-w-xl rounded-2xl bg-white p-6 shadow-sm">
        <form method="POST" action="{{ route('master-data.parents.update', $parent) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="mb-2 block text-sm font-semibold">Sekolah</label>
                <select name="school_id" class="w-full rounded-lg border border-slate-300 px-4 py-2" required>
                    <option value="">Pilih Sekolah</option>
                    @foreach ($schools as $school)
                        <option value="{{ $school->id }}" @selected(old('school_id', $parent->school_id) == $school->id)>
                            {{ $school->name }}
                        </option>
                    @endforeach
                </select>
                @error('school_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="border-t border-slate-100 pt-4">
                <h3 class="font-bold text-slate-700 mb-3 text-sm">Akun User</h3>

                <div class="space-y-4">
                    <div>
                        <label class="mb-2 block text-sm font-semibold">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', $parent->user?->name) }}" class="w-full rounded-lg border border-slate-300 px-4 py-2" required>
                        @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold">Username (Unique)</label>
                        <input type="text" name="username" value="{{ old('username', $parent->user?->username) }}" class="w-full rounded-lg border border-slate-300 px-4 py-2" required>
                        @error('username') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold">Email (Unique)</label>
                        <input type="email" name="email" value="{{ old('email', $parent->user?->email) }}" class="w-full rounded-lg border border-slate-300 px-4 py-2" required>
                        @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold">Telepon / HP</label>
                        <input type="text" name="phone" value="{{ old('phone', $parent->user?->phone) }}" class="w-full rounded-lg border border-slate-300 px-4 py-2">
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
                <h3 class="font-bold text-slate-700 mb-3 text-sm">Profil Orang Tua</h3>

                <div class="space-y-4">
                    <div>
                        <label class="mb-2 block text-sm font-semibold">Hubungan dengan Santri</label>
                        <input type="text" name="relationship" value="{{ old('relationship', $parent->relationship) }}" class="w-full rounded-lg border border-slate-300 px-4 py-2">
                        @error('relationship') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold">Pekerjaan</label>
                        <input type="text" name="occupation" value="{{ old('occupation', $parent->occupation) }}" class="w-full rounded-lg border border-slate-300 px-4 py-2">
                        @error('occupation') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold">Alamat</label>
                        <textarea name="address" class="w-full rounded-lg border border-slate-300 px-4 py-2">{{ old('address', $parent->address) }}</textarea>
                        @error('address') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold">Hubungkan dengan Anak (Santri)</label>
                        <select name="student_ids[]" class="w-full rounded-lg border border-slate-300 px-4 py-2 h-32" multiple>
                            @foreach ($students as $student)
                                <option value="{{ $student->id }}" @selected(in_array($student->id, old('student_ids', $selectedStudents)))>
                                    {{ $student->full_name }} ({{ $student->classRoom?->name ?? 'Tanpa Kelas' }})
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-slate-400 mt-1">Tekan Ctrl (Windows) / Cmd (Mac) untuk memilih lebih dari satu anak.</p>
                        @error('student_ids') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-2 py-2">
                <input type="checkbox" name="is_active" value="1" id="is_active" @checked(old('is_active', $parent->is_active))>
                <label for="is_active" class="text-sm font-semibold">Status Aktif</label>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="rounded-lg bg-slate-900 px-4 py-2 font-semibold text-white hover:bg-slate-700">
                    Simpan Perubahan
                </button>
                <a href="{{ route('master-data.parents.index') }}" class="rounded-lg border border-slate-300 px-4 py-2 font-semibold text-slate-700 hover:bg-slate-50">
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection
