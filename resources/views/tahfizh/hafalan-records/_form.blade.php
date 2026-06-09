<div class="grid gap-6 md:grid-cols-2">
    <div>
        <label class="mb-2 block text-sm font-semibold">Sekolah</label>
        <select name="school_id" class="w-full rounded-lg border border-slate-300 px-4 py-2" required>
            <option value="">Pilih sekolah</option>
            @foreach ($schools as $school)
                <option value="{{ $school->id }}" @selected(old('school_id', $record->school_id ?? null) == $school->id)>
                    {{ $school->name }}
                </option>
            @endforeach
        </select>
        @error('school_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="mb-2 block text-sm font-semibold">Tanggal Setoran</label>
        <input type="date" name="record_date"
               value="{{ old('record_date', isset($record) && $record ? $record->record_date?->format('Y-m-d') : now()->toDateString()) }}"
               class="w-full rounded-lg border border-slate-300 px-4 py-2" required>
        @error('record_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="mb-2 block text-sm font-semibold">Santri</label>
        <select name="student_id" class="w-full rounded-lg border border-slate-300 px-4 py-2" required>
            <option value="">Pilih santri</option>
            @foreach ($students as $student)
                <option value="{{ $student->id }}" @selected(old('student_id', $record->student_id ?? null) == $student->id)>
                    {{ $student->full_name }} — {{ $student->classRoom?->name ?? '-' }}
                </option>
            @endforeach
        </select>
        @error('student_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="mb-2 block text-sm font-semibold">Guru</label>
        <select name="teacher_id" class="w-full rounded-lg border border-slate-300 px-4 py-2" @disabled(auth()->user()->hasRole('teacher'))>
            <option value="">Pilih guru</option>
            @foreach ($teachers as $teacher)
                <option value="{{ $teacher->id }}" @selected(old('teacher_id', $defaultTeacherId ?? null) == $teacher->id)>
                    {{ $teacher->name }}
                </option>
            @endforeach
        </select>

        @if (auth()->user()->hasRole('teacher'))
            <input type="hidden" name="teacher_id" value="{{ auth()->id() }}">
        @endif

        @error('teacher_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="mb-2 block text-sm font-semibold">Target Tahfizh</label>
        <select name="tahfizh_target_id" class="w-full rounded-lg border border-slate-300 px-4 py-2">
            <option value="">Tanpa target khusus</option>
            @foreach ($targets as $target)
                <option value="{{ $target->id }}" @selected(old('tahfizh_target_id', $record->tahfizh_target_id ?? null) == $target->id)>
                    {{ $target->name }}
                </option>
            @endforeach
        </select>
        @error('tahfizh_target_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="mb-2 block text-sm font-semibold">Status</label>
        <select name="status" class="w-full rounded-lg border border-slate-300 px-4 py-2" required>
            @foreach ($statuses as $status)
                <option value="{{ $status }}" @selected(old('status', $record->status ?? 'kurang') === $status)>
                    {{ strtoupper(str_replace('_', ' ', $status)) }}
                </option>
            @endforeach
        </select>
        @error('status') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
</div>

<div class="mt-8">
    <h3 class="mb-4 text-lg font-bold">Rentang Hafalan</h3>

    <div class="grid gap-6 md:grid-cols-4">
        <div>
            <label class="mb-2 block text-sm font-semibold">Halaman Awal</label>
            <input type="number" name="start_page" min="1" max="604"
                   value="{{ old('start_page', $record->start_page ?? '') }}"
                   class="w-full rounded-lg border border-slate-300 px-4 py-2" required>
            @error('start_page') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="mb-2 block text-sm font-semibold">Baris Awal</label>
            <input type="number" name="start_line" min="1" max="15"
                   value="{{ old('start_line', $record->start_line ?? '') }}"
                   class="w-full rounded-lg border border-slate-300 px-4 py-2" required>
            @error('start_line') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="mb-2 block text-sm font-semibold">Halaman Akhir</label>
            <input type="number" name="end_page" min="1" max="604"
                   value="{{ old('end_page', $record->end_page ?? '') }}"
                   class="w-full rounded-lg border border-slate-300 px-4 py-2" required>
            @error('end_page') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="mb-2 block text-sm font-semibold">Baris Akhir</label>
            <input type="number" name="end_line" min="1" max="15"
                   value="{{ old('end_line', $record->end_line ?? '') }}"
                   class="w-full rounded-lg border border-slate-300 px-4 py-2" required>
            @error('end_line') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
    </div>

    <p class="mt-2 text-sm text-slate-500">
        Aturan mushaf: 1 halaman = 15 baris. Total baris dihitung otomatis oleh sistem.
    </p>
</div>

<div class="mt-8">
    <h3 class="mb-4 text-lg font-bold">Informasi Surah dan Ayat</h3>

    <div class="grid gap-6 md:grid-cols-4">
        <div>
            <label class="mb-2 block text-sm font-semibold">Surah Awal</label>
            <select name="start_surah_id" class="w-full rounded-lg border border-slate-300 px-4 py-2">
                <option value="">Opsional</option>
                @foreach ($surahs as $surah)
                    <option value="{{ $surah->id }}" @selected(old('start_surah_id', $record->start_surah_id ?? null) == $surah->id)>
                        {{ $surah->number }}. {{ $surah->name_latin }}
                    </option>
                @endforeach
            </select>
            @error('start_surah_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="mb-2 block text-sm font-semibold">Ayat Awal</label>
            <input type="number" name="start_ayah" min="1"
                   value="{{ old('start_ayah', $record->start_ayah ?? '') }}"
                   class="w-full rounded-lg border border-slate-300 px-4 py-2">
            @error('start_ayah') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="mb-2 block text-sm font-semibold">Surah Akhir</label>
            <select name="end_surah_id" class="w-full rounded-lg border border-slate-300 px-4 py-2">
                <option value="">Opsional</option>
                @foreach ($surahs as $surah)
                    <option value="{{ $surah->id }}" @selected(old('end_surah_id', $record->end_surah_id ?? null) == $surah->id)>
                        {{ $surah->number }}. {{ $surah->name_latin }}
                    </option>
                @endforeach
            </select>
            @error('end_surah_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="mb-2 block text-sm font-semibold">Ayat Akhir</label>
            <input type="number" name="end_ayah" min="1"
                   value="{{ old('end_ayah', $record->end_ayah ?? '') }}"
                   class="w-full rounded-lg border border-slate-300 px-4 py-2">
            @error('end_ayah') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
    </div>
</div>

<div class="mt-8 grid gap-6 md:grid-cols-2">
    <div>
        <label class="mb-2 block text-sm font-semibold">Nilai Kualitas</label>
        <input type="number" name="quality_score" min="0" max="100"
               value="{{ old('quality_score', $record->quality_score ?? '') }}"
               class="w-full rounded-lg border border-slate-300 px-4 py-2">
        @error('quality_score') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="mb-2 block text-sm font-semibold">Catatan Guru</label>
        <textarea name="notes" rows="3"
                  class="w-full rounded-lg border border-slate-300 px-4 py-2">{{ old('notes', $record->notes ?? '') }}</textarea>
        @error('notes') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
</div>
