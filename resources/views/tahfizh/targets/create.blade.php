@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <a href="{{ route('tahfizh.targets.index') }}" class="text-sm font-semibold text-slate-600 hover:text-slate-900">
            &larr; Kembali ke Daftar Target
        </a>
        <h2 class="text-2xl font-bold mt-2">Tambah Target Tahfizh</h2>
        <p class="text-sm text-slate-500">Buat target baru untuk sekolah, kelas, program, atau santri.</p>
    </div>

    <div class="rounded-2xl bg-white p-6 shadow-sm border border-slate-100 max-w-3xl">
        <form method="POST" action="{{ route('tahfizh.targets.store') }}" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <label for="school_id" class="block text-sm font-medium text-slate-700">Sekolah <span class="text-red-500">*</span></label>
                    <select name="school_id" id="school_id" class="mt-1 w-full rounded-lg border border-slate-200 p-2.5 text-sm @error('school_id') border-red-500 @enderror" required>
                        <option value="">Pilih Sekolah</option>
                        @foreach ($schools as $school)
                            <option value="{{ $school->id }}" {{ old('school_id') == $school->id ? 'selected' : '' }}>
                                {{ $school->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('school_id')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700">Nama Target <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="Contoh: Target Reguler Harian" class="mt-1 w-full rounded-lg border border-slate-200 p-2.5 text-sm @error('name') border-red-500 @enderror" required>
                    @error('name')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="class_room_id" class="block text-sm font-medium text-slate-700">Kelas <span class="text-xs text-slate-400">(Opsional)</span></label>
                    <select name="class_room_id" id="class_room_id" class="mt-1 w-full rounded-lg border border-slate-200 p-2.5 text-sm @error('class_room_id') border-red-500 @enderror">
                        <option value="">Pilih Kelas (Jika target khusus kelas)</option>
                        @foreach ($classRooms as $classRoom)
                            <option value="{{ $classRoom->id }}" {{ old('class_room_id') == $classRoom->id ? 'selected' : '' }}>
                                {{ $classRoom->name }} ({{ $classRoom->school?->name }})
                            </option>
                        @endforeach
                    </select>
                    @error('class_room_id')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="student_id" class="block text-sm font-medium text-slate-700">Santri <span class="text-xs text-slate-400">(Opsional)</span></label>
                    <select name="student_id" id="student_id" class="mt-1 w-full rounded-lg border border-slate-200 p-2.5 text-sm @error('student_id') border-red-500 @enderror">
                        <option value="">Pilih Santri (Jika target khusus individu)</option>
                        @foreach ($students as $student)
                            <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                {{ $student->full_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('student_id')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="program_type" class="block text-sm font-medium text-slate-700">Jenis Program <span class="text-xs text-slate-400">(Opsional)</span></label>
                    <select name="program_type" id="program_type" class="mt-1 w-full rounded-lg border border-slate-200 p-2.5 text-sm @error('program_type') border-red-500 @enderror">
                        <option value="">Pilih Program (Jika target khusus jenis program)</option>
                        <option value="reguler" {{ old('program_type') == 'reguler' ? 'selected' : '' }}>Reguler</option>
                        <option value="takhassus" {{ old('program_type') == 'takhassus' ? 'selected' : '' }}>Takhassus</option>
                    </select>
                    @error('program_type')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="daily_target_lines" class="block text-sm font-medium text-slate-700">Target Harian (Baris) <span class="text-red-500">*</span></label>
                    <input type="number" name="daily_target_lines" id="daily_target_lines" min="0" max="300" value="{{ old('daily_target_lines', 0) }}" class="mt-1 w-full rounded-lg border border-slate-200 p-2.5 text-sm @error('daily_target_lines') border-red-500 @enderror" required>
                    @error('daily_target_lines')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="weekly_target_lines" class="block text-sm font-medium text-slate-700">Target Mingguan (Baris) <span class="text-red-500">*</span></label>
                    <input type="number" name="weekly_target_lines" id="weekly_target_lines" min="0" max="1500" value="{{ old('weekly_target_lines', 0) }}" class="mt-1 w-full rounded-lg border border-slate-200 p-2.5 text-sm @error('weekly_target_lines') border-red-500 @enderror" required>
                    @error('weekly_target_lines')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="monthly_target_lines" class="block text-sm font-medium text-slate-700">Target Bulanan (Baris) <span class="text-red-500">*</span></label>
                    <input type="number" name="monthly_target_lines" id="monthly_target_lines" min="0" max="6000" value="{{ old('monthly_target_lines', 0) }}" class="mt-1 w-full rounded-lg border border-slate-200 p-2.5 text-sm @error('monthly_target_lines') border-red-500 @enderror" required>
                    @error('monthly_target_lines')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="effective_from" class="block text-sm font-medium text-slate-700">Tanggal Mulai Berlaku</label>
                    <input type="date" name="effective_from" id="effective_from" value="{{ old('effective_from') }}" class="mt-1 w-full rounded-lg border border-slate-200 p-2.5 text-sm @error('effective_from') border-red-500 @enderror">
                    @error('effective_from')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="effective_until" class="block text-sm font-medium text-slate-700">Tanggal Akhir Berlaku</label>
                    <input type="date" name="effective_until" id="effective_until" value="{{ old('effective_until') }}" class="mt-1 w-full rounded-lg border border-slate-200 p-2.5 text-sm @error('effective_until') border-red-500 @enderror">
                    @error('effective_until')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-900">
                <label for="is_active" class="ml-2 block text-sm text-slate-900 font-medium">Target Aktif</label>
            </div>

            <div class="flex justify-end gap-3 border-t pt-6">
                <a href="{{ route('tahfizh.targets.index') }}" class="rounded-lg border border-slate-200 py-2.5 px-4 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                    Batal
                </a>
                <button type="submit" class="rounded-lg bg-slate-900 py-2.5 px-4 text-sm font-semibold text-white hover:bg-slate-700">
                    Simpan Target
                </button>
            </div>
        </form>
    </div>
@endsection
