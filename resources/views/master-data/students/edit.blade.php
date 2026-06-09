@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h2 class="text-2xl font-bold">Edit Santri</h2>
        <p class="text-sm text-slate-500">Ubah data santri.</p>
    </div>

    <div class="max-w-xl rounded-2xl bg-white p-6 shadow-sm">
        <form method="POST" action="{{ route('master-data.students.update', $student) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="mb-2 block text-sm font-semibold">Sekolah</label>
                <select name="school_id" class="w-full rounded-lg border border-slate-300 px-4 py-2" required>
                    <option value="">Pilih Sekolah</option>
                    @foreach ($schools as $school)
                        <option value="{{ $school->id }}" @selected(old('school_id', $student->school_id) == $school->id)>
                            {{ $school->name }}
                        </option>
                    @endforeach
                </select>
                @error('school_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold">Kelas</label>
                <select name="class_room_id" class="w-full rounded-lg border border-slate-300 px-4 py-2">
                    <option value="">Pilih Kelas</option>
                    @foreach ($classRooms as $classRoom)
                        <option value="{{ $classRoom->id }}" @selected(old('class_room_id', $student->class_room_id) == $classRoom->id)>
                            {{ $classRoom->name }} ({{ $classRoom->school?->name }})
                        </option>
                    @endforeach
                </select>
                @error('class_room_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="border-t border-slate-100 pt-4 space-y-4">
                <h3 class="font-bold text-slate-700 text-sm">Biodata Santri</h3>

                <div>
                    <label class="mb-2 block text-sm font-semibold">Nama Lengkap</label>
                    <input type="text" name="full_name" value="{{ old('full_name', $student->full_name) }}" class="w-full rounded-lg border border-slate-300 px-4 py-2" required>
                    @error('full_name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold">Nama Panggilan</label>
                    <input type="text" name="nickname" value="{{ old('nickname', $student->nickname) }}" class="w-full rounded-lg border border-slate-300 px-4 py-2">
                    @error('nickname') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold">Nomor Induk Santri</label>
                    <input type="text" name="student_number" value="{{ old('student_number', $student->student_number) }}" class="w-full rounded-lg border border-slate-300 px-4 py-2">
                    @error('student_number') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold">NISN</label>
                    <input type="text" name="nisn" value="{{ old('nisn', $student->nisn) }}" class="w-full rounded-lg border border-slate-300 px-4 py-2">
                    @error('nisn') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold">Jenis Kelamin</label>
                    <select name="gender" class="w-full rounded-lg border border-slate-300 px-4 py-2">
                        <option value="">Pilih</option>
                        <option value="L" @selected(old('gender', $student->gender) == 'L')>Laki-laki</option>
                        <option value="P" @selected(old('gender', $student->gender) == 'P')>Perempuan</option>
                    </select>
                    @error('gender') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="mb-2 block text-sm font-semibold">Tempat Lahir</label>
                        <input type="text" name="birth_place" value="{{ old('birth_place', $student->birth_place) }}" class="w-full rounded-lg border border-slate-300 px-4 py-2">
                        @error('birth_place') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold">Tanggal Lahir</label>
                        <input type="date" name="birth_date" value="{{ old('birth_date', $student->birth_date?->toDateString()) }}" class="w-full rounded-lg border border-slate-300 px-4 py-2">
                        @error('birth_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold">Alamat</label>
                    <textarea name="address" class="w-full rounded-lg border border-slate-300 px-4 py-2">{{ old('address', $student->address) }}</textarea>
                    @error('address') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold">Telepon / HP Santri</label>
                    <input type="text" name="phone" value="{{ old('phone', $student->phone) }}" class="w-full rounded-lg border border-slate-300 px-4 py-2">
                    @error('phone') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold">Tipe Program</label>
                    <input type="text" name="program_type" value="{{ old('program_type', $student->program_type) }}" class="w-full rounded-lg border border-slate-300 px-4 py-2">
                    @error('program_type') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold">Wali / Orang Tua</label>
                    <select name="parent_profile_ids[]" class="w-full rounded-lg border border-slate-300 px-4 py-2 h-24" multiple>
                        @foreach ($parents as $parent)
                            <option value="{{ $parent->id }}" @selected(in_array($parent->id, old('parent_profile_ids', $selectedParents)))>
                                {{ $parent->user?->name }} ({{ $parent->relationship ?? 'Wali' }})
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-slate-400 mt-1">Tekan Ctrl (Windows) / Cmd (Mac) untuk memilih wali murid.</p>
                    @error('parent_profile_ids') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            @if ($student->user)
                <div class="border-t border-slate-100 pt-4 space-y-4">
                    <h3 class="font-bold text-slate-700 text-sm">Akun Login</h3>

                    <div>
                        <label class="mb-2 block text-sm font-semibold">Username (Unique)</label>
                        <input type="text" name="username" value="{{ old('username', $student->user->username) }}" class="w-full rounded-lg border border-slate-300 px-4 py-2">
                        @error('username') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold">Email (Unique / Opsional)</label>
                        <input type="email" name="email" value="{{ old('email', $student->user->email) }}" class="w-full rounded-lg border border-slate-300 px-4 py-2">
                        @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold">Password</label>
                        <input type="password" name="password" class="w-full rounded-lg border border-slate-300 px-4 py-2" placeholder="Kosongkan jika tidak ingin diubah">
                        @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            @endif

            <div class="flex items-center gap-2 py-2">
                <input type="checkbox" name="is_active" value="1" id="is_active" @checked(old('is_active', $student->is_active))>
                <label for="is_active" class="text-sm font-semibold">Status Aktif</label>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="rounded-lg bg-slate-900 px-4 py-2 font-semibold text-white hover:bg-slate-700">
                    Simpan Perubahan
                </button>
                <a href="{{ route('master-data.students.index') }}" class="rounded-lg border border-slate-300 px-4 py-2 font-semibold text-slate-700 hover:bg-slate-50">
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection
