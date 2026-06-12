<div class="space-y-8">
    {{-- Section 1: Data Utama --}}
    <div class="bg-slate-50/50 dark:bg-slate-850/20 rounded-2xl p-5 border border-slate-200/50 dark:border-slate-800/60">
        <h4 class="text-xs font-bold uppercase tracking-wider text-indigo-600 dark:text-indigo-400 mb-4">Informasi Utama Setoran</h4>
        <div class="grid gap-6 md:grid-cols-2">
            <div>
                <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Sekolah <span class="text-rose-500">*</span></label>
                <select name="school_id" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none" required>
                    <option value="">Pilih sekolah</option>
                    @foreach ($schools as $school)
                        <option value="{{ $school->id }}" @selected(old('school_id', $record->school_id ?? null) == $school->id)>
                            {{ $school->name }}
                        </option>
                    @endforeach
                </select>
                @error('school_id') <p class="mt-1.5 text-xs text-rose-600 dark:text-rose-450">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Tanggal Setoran <span class="text-rose-500">*</span></label>
                <input type="date" name="record_date"
                       value="{{ old('record_date', isset($record) && $record ? $record->record_date?->format('Y-m-d') : now()->toDateString()) }}"
                       class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none" required>
                @error('record_date') <p class="mt-1.5 text-xs text-rose-600 dark:text-rose-450">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Santri <span class="text-rose-500">*</span></label>
                <select name="student_id" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none" required>
                    <option value="">Pilih santri</option>
                    @foreach ($students as $student)
                        <option value="{{ $student->id }}" @selected(old('student_id', $record->student_id ?? null) == $student->id)>
                            {{ $student->full_name }} — {{ $student->classRoom?->name ?? '-' }}
                        </option>
                    @endforeach
                </select>
                @error('student_id') <p class="mt-1.5 text-xs text-rose-600 dark:text-rose-450">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Guru Penerima</label>
                <select name="teacher_id" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none" @disabled(auth()->user()->hasRole('teacher'))>
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

                @error('teacher_id') <p class="mt-1.5 text-xs text-rose-600 dark:text-rose-450">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Target Tahfizh</label>
                <select name="tahfizh_target_id" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none">
                    <option value="">Tanpa target khusus</option>
                    @foreach ($targets as $target)
                        <option value="{{ $target->id }}" @selected(old('tahfizh_target_id', $record->tahfizh_target_id ?? null) == $target->id)>
                            {{ $target->name }}
                        </option>
                    @endforeach
                </select>
                @error('tahfizh_target_id') <p class="mt-1.5 text-xs text-rose-600 dark:text-rose-450">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Status Kelancaran <span class="text-rose-500">*</span></label>
                <select name="status" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none" required>
                    @foreach ($statuses as $status)
                        <option value="{{ $status }}" @selected(old('status', $record->status ?? 'kurang') === $status)>
                            {{ strtoupper(str_replace('_', ' ', $status)) }}
                        </option>
                    @endforeach
                </select>
                @error('status') <p class="mt-1.5 text-xs text-rose-600 dark:text-rose-450">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>

    {{-- Section 2: Rentang Hafalan --}}
    <div class="bg-slate-50/50 dark:bg-slate-850/20 rounded-2xl p-5 border border-slate-200/50 dark:border-slate-800/60">
        <h4 class="text-xs font-bold uppercase tracking-wider text-indigo-600 dark:text-indigo-400 mb-2">Rentang Halaman & Baris</h4>
        <p class="text-xs text-slate-400 dark:text-slate-500 mb-4">Aturan mushaf standard: 1 halaman = 15 baris. Jumlah baris terhitung otomatis oleh sistem.</p>
        <div class="grid gap-6 sm:grid-cols-2 md:grid-cols-4">
            <div>
                <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Halaman Awal <span class="text-rose-500">*</span></label>
                <input type="number" name="start_page" min="1" max="604"
                       value="{{ old('start_page', $record->start_page ?? '') }}"
                       class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none" required>
                @error('start_page') <p class="mt-1.5 text-xs text-rose-600 dark:text-rose-450">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Baris Awal <span class="text-rose-500">*</span></label>
                <input type="number" name="start_line" min="1" max="15"
                       value="{{ old('start_line', $record->start_line ?? '') }}"
                       class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none" required>
                @error('start_line') <p class="mt-1.5 text-xs text-rose-600 dark:text-rose-450">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Halaman Akhir <span class="text-rose-500">*</span></label>
                <input type="number" name="end_page" min="1" max="604"
                       value="{{ old('end_page', $record->end_page ?? '') }}"
                       class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none" required>
                @error('end_page') <p class="mt-1.5 text-xs text-rose-600 dark:text-rose-450">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Baris Akhir <span class="text-rose-500">*</span></label>
                <input type="number" name="end_line" min="1" max="15"
                       value="{{ old('end_line', $record->end_line ?? '') }}"
                       class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none" required>
                @error('end_line') <p class="mt-1.5 text-xs text-rose-600 dark:text-rose-450">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>

    {{-- Section 3: Informasi Surah dan Ayat --}}
    <div class="bg-slate-50/50 dark:bg-slate-850/20 rounded-2xl p-5 border border-slate-200/50 dark:border-slate-800/60">
        <h4 class="text-xs font-bold uppercase tracking-wider text-indigo-600 dark:text-indigo-400 mb-4">Surah & Ayat (Opsional)</h4>
        <div class="grid gap-6 sm:grid-cols-2 md:grid-cols-4">
            <div>
                <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Surah Awal</label>
                <select name="start_surah_id" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none">
                    <option value="">Pilih Surah Awal</option>
                    @foreach ($surahs as $surah)
                        <option value="{{ $surah->id }}" @selected(old('start_surah_id', $record->start_surah_id ?? null) == $surah->id)>
                            {{ $surah->number }}. {{ $surah->name_latin }}
                        </option>
                    @endforeach
                </select>
                @error('start_surah_id') <p class="mt-1.5 text-xs text-rose-600 dark:text-rose-450">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Ayat Awal</label>
                <input type="number" name="start_ayah" min="1"
                       value="{{ old('start_ayah', $record->start_ayah ?? '') }}"
                       class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none">
                @error('start_ayah') <p class="mt-1.5 text-xs text-rose-600 dark:text-rose-450">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Surah Akhir</label>
                <select name="end_surah_id" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none">
                    <option value="">Pilih Surah Akhir</option>
                    @foreach ($surahs as $surah)
                        <option value="{{ $surah->id }}" @selected(old('end_surah_id', $record->end_surah_id ?? null) == $surah->id)>
                            {{ $surah->number }}. {{ $surah->name_latin }}
                        </option>
                    @endforeach
                </select>
                @error('end_surah_id') <p class="mt-1.5 text-xs text-rose-600 dark:text-rose-450">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Ayat Akhir</label>
                <input type="number" name="end_ayah" min="1"
                       value="{{ old('end_ayah', $record->end_ayah ?? '') }}"
                       class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none">
                @error('end_ayah') <p class="mt-1.5 text-xs text-rose-600 dark:text-rose-450">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>

    {{-- Section 4: Penilaian dan Catatan --}}
    <div class="bg-slate-50/50 dark:bg-slate-850/20 rounded-2xl p-5 border border-slate-200/50 dark:border-slate-800/60">
        <h4 class="text-xs font-bold uppercase tracking-wider text-indigo-600 dark:text-indigo-400 mb-4">Penilaian & Catatan Tambahan</h4>
        <div class="grid gap-6 md:grid-cols-2">
            <div>
                <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Nilai Kualitas (0-100)</label>
                <input type="number" name="quality_score" min="0" max="100"
                       value="{{ old('quality_score', $record->quality_score ?? '') }}"
                       class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none">
                @error('quality_score') <p class="mt-1.5 text-xs text-rose-600 dark:text-rose-450">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Catatan Guru</label>
                <textarea name="notes" rows="3"
                          class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none">{{ old('notes', $record->notes ?? '') }}</textarea>
                @error('notes') <p class="mt-1.5 text-xs text-rose-600 dark:text-rose-450">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>
</div>

