@extends('layouts.app')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">
    {{-- Header --}}
    <div class="flex items-center space-x-3 border-b border-slate-200/80 pb-5 dark:border-slate-800">
        <a href="{{ route('tahfizh.targets.index') }}" 
           class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-slate-200 text-slate-500 hover:bg-slate-50 dark:border-slate-800 dark:text-slate-400 dark:hover:bg-slate-800 transition-all" 
           title="Kembali ke Daftar">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <div>
            <h2 class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white">Tambah Target Tahfizh</h2>
            <p class="text-sm text-slate-500 dark:text-slate-400">Buat target baru untuk sekolah, kelas, program, atau santri.</p>
        </div>
    </div>

    {{-- Form Card --}}
    <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <form method="POST" action="{{ route('tahfizh.targets.store') }}" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <label for="school_id" class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">Sekolah <span class="text-rose-500">*</span></label>
                    <select name="school_id" id="school_id" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none @error('school_id') border-red-500 @enderror" required>
                        <option value="">Pilih Sekolah</option>
                        @foreach ($schools as $school)
                            <option value="{{ $school->id }}" {{ old('school_id') == $school->id ? 'selected' : '' }}>
                                {{ $school->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('school_id')
                        <p class="mt-1.5 text-xs text-rose-600 dark:text-rose-450">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">Nama Target <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="Contoh: Target Reguler Harian" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none @error('name') border-red-500 @enderror" required>
                    @error('name')
                        <p class="mt-1.5 text-xs text-rose-600 dark:text-rose-450">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="class_room_id" class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">Kelas <span class="text-slate-400 font-medium">(Opsional)</span></label>
                    <select name="class_room_id" id="class_room_id" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none @error('class_room_id') border-red-500 @enderror">
                        <option value="">Pilih Kelas (Jika target khusus kelas)</option>
                        @foreach ($classRooms as $classRoom)
                            <option value="{{ $classRoom->id }}" {{ old('class_room_id') == $classRoom->id ? 'selected' : '' }}>
                                {{ $classRoom->name }} ({{ $classRoom->school?->name }})
                            </option>
                        @endforeach
                    </select>
                    @error('class_room_id')
                        <p class="mt-1.5 text-xs text-rose-600 dark:text-rose-450">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="student_id" class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">Santri <span class="text-slate-400 font-medium">(Opsional)</span></label>
                    <select name="student_id" id="student_id" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none @error('student_id') border-red-500 @enderror">
                        <option value="">Pilih Santri (Jika target khusus individu)</option>
                        @foreach ($students as $student)
                            <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                {{ $student->full_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('student_id')
                        <p class="mt-1.5 text-xs text-rose-600 dark:text-rose-450">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="program_type" class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">Jenis Program <span class="text-slate-400 font-medium">(Opsional)</span></label>
                    <select name="program_type" id="program_type" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none @error('program_type') border-red-500 @enderror">
                        <option value="">Pilih Program (Jika target khusus jenis program)</option>
                        <option value="reguler" {{ old('program_type') == 'reguler' ? 'selected' : '' }}>Reguler</option>
                        <option value="takhassus" {{ old('program_type') == 'takhassus' ? 'selected' : '' }}>Takhassus</option>
                    </select>
                    @error('program_type')
                        <p class="mt-1.5 text-xs text-rose-600 dark:text-rose-450">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="daily_target_lines" class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">Target Harian (Baris) <span class="text-rose-500">*</span></label>
                    <input type="number" name="daily_target_lines" id="daily_target_lines" min="0" max="300" value="{{ old('daily_target_lines', 0) }}" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none @error('daily_target_lines') border-red-500 @enderror" required>
                    @error('daily_target_lines')
                        <p class="mt-1.5 text-xs text-rose-600 dark:text-rose-450">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="weekly_target_lines" class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">Target Mingguan (Baris) <span class="text-rose-500">*</span></label>
                    <input type="number" name="weekly_target_lines" id="weekly_target_lines" min="0" max="1500" value="{{ old('weekly_target_lines', 0) }}" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none @error('weekly_target_lines') border-red-500 @enderror" required>
                    @error('weekly_target_lines')
                        <p class="mt-1.5 text-xs text-rose-600 dark:text-rose-450">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="monthly_target_lines" class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">Target Bulanan (Baris) <span class="text-rose-500">*</span></label>
                    <input type="number" name="monthly_target_lines" id="monthly_target_lines" min="0" max="6000" value="{{ old('monthly_target_lines', 0) }}" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none @error('monthly_target_lines') border-red-500 @enderror" required>
                    @error('monthly_target_lines')
                        <p class="mt-1.5 text-xs text-rose-600 dark:text-rose-450">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="effective_from" class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">Tanggal Mulai Berlaku</label>
                    <input type="date" name="effective_from" id="effective_from" value="{{ old('effective_from') }}" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none @error('effective_from') border-red-500 @enderror">
                    @error('effective_from')
                        <p class="mt-1.5 text-xs text-rose-600 dark:text-rose-450">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="effective_until" class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">Tanggal Akhir Berlaku</label>
                    <input type="date" name="effective_until" id="effective_until" value="{{ old('effective_until') }}" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none @error('effective_until') border-red-500 @enderror">
                    @error('effective_until')
                        <p class="mt-1.5 text-xs text-rose-600 dark:text-rose-450">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center p-3 bg-slate-50 dark:bg-slate-950 rounded-xl border border-slate-150 dark:border-slate-850">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 dark:bg-slate-950 dark:border-slate-800">
                <label for="is_active" class="ml-2 block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-350">Target Aktif</label>
            </div>

            <div class="flex items-center justify-end space-x-3 border-t border-slate-100 pt-5 dark:border-slate-800/60">
                <a href="{{ route('tahfizh.targets.index') }}" class="inline-flex items-center justify-center rounded-full border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-400 dark:hover:bg-slate-800 transition-all focus:outline-none">
                    Batal
                </a>
                <button type="submit" class="inline-flex items-center justify-center rounded-full bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700 shadow-sm transition-all focus:outline-none focus:ring-4 focus:ring-indigo-500/20 active:scale-[0.98]">
                    Simpan Target
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

