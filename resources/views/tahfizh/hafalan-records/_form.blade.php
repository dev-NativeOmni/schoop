@php
    $studentIdInitial = old('student_id', $record->student_id ?? request('student_id', ''));
    $startPageInitial = old('start_page', $record->start_page ?? '');
    $startLineInitial = old('start_line', $record->start_line ?? 1);
    $endPageInitial = old('end_page', $record->end_page ?? '');
    $endLineInitial = old('end_line', $record->end_line ?? 15);
    $qualityScoreInitial = old('quality_score', $record->quality_score ?? 95);
    $statusInitial = old('status', $record->status ?? 'lunas');
@endphp

<div x-data="talaqqiForm({
        students: {{ json_encode($studentsData ?? []) }},
        initialStudentId: '{{ $studentIdInitial }}',
        initialStartPage: '{{ $startPageInitial }}',
        initialStartLine: '{{ $startLineInitial }}',
        initialEndPage: '{{ $endPageInitial }}',
        initialEndLine: '{{ $endLineInitial }}',
        initialQualityScore: '{{ $qualityScoreInitial }}',
        initialStatus: '{{ $statusInitial }}'
    })" 
    class="space-y-8">

    {{-- Section 1: Pemilihan Santri & Deteksi Setoran Terakhir --}}
    <div class="card-natural p-6">
        <div class="flex items-center justify-between mb-4">
            <h4 class="text-xs font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                1. Data Santri & Titik Lanjutan
            </h4>
            <span class="text-xs text-slate-400 font-medium">Talaqqi Input Engine</span>
        </div>

        <div class="grid gap-5 md:grid-cols-3">
            {{-- Filter Kelas --}}
            <div>
                <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Filter Halaqah / Kelas</label>
                <select x-model="filterClassId" 
                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 focus:outline-none transition">
                    <option value="">Semua Kelas</option>
                    @foreach ($classRooms as $cr)
                        <option value="{{ $cr->id }}">{{ $cr->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Pilih Santri --}}
            <div class="md:col-span-2">
                <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                    Pilih Santri Talaqqi <span class="text-rose-500">*</span>
                </label>
                <select name="student_id" 
                        x-model="studentId" 
                        @change="onStudentChange($event.target.value)"
                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 focus:outline-none font-medium transition" 
                        required>
                    <option value="">-- Pilih Santri yang Sedang Setoran --</option>
                    @foreach ($students as $student)
                        <option value="{{ $student->id }}" 
                                x-show="!filterClassId || filterClassId == '{{ $student->class_room_id }}'"
                                @selected($studentIdInitial == $student->id)>
                            {{ $student->full_name }} — {{ $student->classRoom?->name ?? 'Tanpa Kelas' }} (NIS: {{ $student->nis ?? '-' }})
                        </option>
                    @endforeach
                </select>
                @error('student_id') <p class="mt-1.5 text-xs text-rose-600 dark:text-rose-450">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Live Student Info Card & Auto-Fill Trigger --}}
        <div x-show="selectedStudent" 
             x-transition 
             class="mt-5 p-4 rounded-2xl bg-emerald-50/70 border border-emerald-200/60 dark:bg-emerald-950/30 dark:border-emerald-900/40 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-center space-x-3.5">
                <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white font-bold flex items-center justify-center text-sm shadow-sm shrink-0">
                    <span x-text="selectedStudent ? selectedStudent.name.substring(0,2).toUpperCase() : 'HP'"></span>
                </div>
                <div>
                    <h5 class="text-sm font-bold text-slate-900 dark:text-white" x-text="selectedStudent ? selectedStudent.name : ''"></h5>
                    <div class="flex flex-wrap items-center gap-2 mt-0.5 text-xs text-slate-500 dark:text-slate-400">
                        <span x-text="selectedStudent ? selectedStudent.class_room_name : ''"></span>
                        <span>&bull;</span>
                        <template x-if="selectedStudent && selectedStudent.has_previous">
                            <span class="text-emerald-700 dark:text-emerald-300 font-semibold">
                                Terakhir: Halaman <b x-text="selectedStudent.last_end_page"></b> Baris <b x-text="selectedStudent.last_end_line"></b> (<span x-text="selectedStudent.last_record_date"></span>)
                            </span>
                        </template>
                        <template x-if="selectedStudent && !selectedStudent.has_previous">
                            <span class="text-slate-500 font-medium">Belum ada riwayat (Mulai dari Hlm 1:1)</span>
                        </template>
                    </div>
                </div>
            </div>

            {{-- 1-Click Fast Auto Fill Button --}}
            <button type="button" 
                    @click="applyAutoStart()"
                    class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-sm active:scale-95 transition-all flex items-center gap-1.5 shrink-0">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                <span>Mulai dari Titik Terakhir (Hlm <span x-text="selectedStudent ? selectedStudent.expected_page : 1"></span>:<span x-text="selectedStudent ? selectedStudent.expected_line : 1"></span>)</span>
            </button>
        </div>

        {{-- Hidden School ID sync --}}
        <input type="hidden" name="school_id" :value="selectedStudent ? selectedStudent.school_id : '{{ $schools->first()?->id ?? 1 }}'">
    </div>

    {{-- Section 2: Rentang Halaman & Kalkulator Cerdas Real-time --}}
    <div class="card-natural p-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-4">
            <h4 class="text-xs font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                2. Rentang Halaman & Baris (Mushaf Standar 15 Baris)
            </h4>
            <span class="text-xs text-slate-400">Total baris dikalkulasi otomatis</span>
        </div>

        {{-- Quick Add Preset Buttons --}}
        <div class="mb-5 p-3 rounded-xl bg-slate-50 dark:bg-slate-850/60 border border-slate-200/60 dark:border-slate-800 flex flex-wrap items-center gap-2">
            <span class="text-xs font-bold text-slate-500 dark:text-slate-400 mr-1 flex items-center gap-1">
                <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                Shortcut Tambah Cepat:
            </span>
            <button type="button" @click="addLines(5)" class="px-2.5 py-1 rounded-lg bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-xs font-semibold text-slate-700 dark:text-slate-200 hover:border-emerald-500 hover:text-emerald-600 transition shadow-2xs active:scale-95">+5 Baris</button>
            <button type="button" @click="addLines(7)" class="px-2.5 py-1 rounded-lg bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-xs font-semibold text-slate-700 dark:text-slate-200 hover:border-emerald-500 hover:text-emerald-600 transition shadow-2xs active:scale-95">+½ Halaman (7 Baris)</button>
            <button type="button" @click="addLines(10)" class="px-2.5 py-1 rounded-lg bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-xs font-semibold text-slate-700 dark:text-slate-200 hover:border-emerald-500 hover:text-emerald-600 transition shadow-2xs active:scale-95">+10 Baris</button>
            <button type="button" @click="addLines(15)" class="px-2.5 py-1 rounded-lg bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-300 dark:border-emerald-800 text-xs font-bold text-emerald-700 dark:text-emerald-300 hover:bg-emerald-100 transition shadow-2xs active:scale-95">+1 Halaman Penuh (15 Baris)</button>
            <button type="button" @click="addLines(30)" class="px-2.5 py-1 rounded-lg bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-xs font-semibold text-slate-700 dark:text-slate-200 hover:border-emerald-500 hover:text-emerald-600 transition shadow-2xs active:scale-95">+2 Halaman (30 Baris)</button>
        </div>

        {{-- 4-Col Range Inputs --}}
        <div class="grid gap-4 sm:grid-cols-2 md:grid-cols-4">
            <div>
                <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Halaman Awal <span class="text-rose-500">*</span></label>
                <input type="number" name="start_page" min="1" max="604"
                       x-model="startPage"
                       @input="updateCalculations()"
                       placeholder="1-604"
                       class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-base font-bold text-slate-900 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100 focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 focus:outline-none transition" required>
                @error('start_page') <p class="mt-1.5 text-xs text-rose-600 dark:text-rose-450">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Baris Awal <span class="text-rose-500">*</span></label>
                <input type="number" name="start_line" min="1" max="15"
                       x-model="startLine"
                       @input="updateCalculations()"
                       placeholder="1-15"
                       class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-base font-bold text-slate-900 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100 focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 focus:outline-none transition" required>
                @error('start_line') <p class="mt-1.5 text-xs text-rose-600 dark:text-rose-450">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Halaman Akhir <span class="text-rose-500">*</span></label>
                <input type="number" name="end_page" min="1" max="604"
                       x-model="endPage"
                       @input="updateCalculations()"
                       placeholder="1-604"
                       class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-base font-bold text-slate-900 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100 focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 focus:outline-none transition" required>
                @error('end_page') <p class="mt-1.5 text-xs text-rose-600 dark:text-rose-450">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Baris Akhir <span class="text-rose-500">*</span></label>
                <input type="number" name="end_line" min="1" max="15"
                       x-model="endLine"
                       @input="updateCalculations()"
                       placeholder="1-15"
                       class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-base font-bold text-slate-900 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100 focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 focus:outline-none transition" required>
                @error('end_line') <p class="mt-1.5 text-xs text-rose-600 dark:text-rose-450">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Live Calculation Result Hero Box --}}
        <div class="mt-6 p-5 rounded-2xl bg-gradient-to-br transition-all duration-300 border"
             :class="calculatedLines >= dailyTarget ? 'from-emerald-950/80 via-slate-900 to-emerald-950/60 border-emerald-800/60 text-white' : 'from-amber-950/80 via-slate-900 to-slate-950 border-amber-800/60 text-white'">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center font-bold text-lg"
                         :class="calculatedLines >= dailyTarget ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' : 'bg-amber-500/20 text-amber-300 border border-amber-500/30'">
                        <span x-text="calculatedLines"></span>
                    </div>
                    <div>
                        <div class="text-xs font-semibold uppercase tracking-wider text-slate-300">Hasil Kalkulasi Baris</div>
                        <div class="text-2xl font-black tracking-tight mt-0.5">
                            <span x-text="calculatedLines"></span> Baris Disetor
                            <span class="text-xs font-normal text-slate-400" x-text="`(${(calculatedLines / 15).toFixed(1)} Halaman)`"></span>
                        </div>
                    </div>
                </div>

                <div class="text-left sm:text-right">
                    <div class="text-xs text-slate-300 font-medium">Target Sesi: <b x-text="dailyTarget"></b> Baris</div>
                    <div class="mt-1 inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold"
                         :class="calculatedLines >= dailyTarget ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/40' : 'bg-amber-500/20 text-amber-300 border border-amber-500/40'">
                        <template x-if="calculatedLines >= dailyTarget">
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-emerald-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                                Memenuhi Target (<span x-text="`+${calculatedLines - dailyTarget}`"></span> baris)
                            </span>
                        </template>
                        <template x-if="calculatedLines < dailyTarget">
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                                Kurang Target (<span x-text="`-${dailyTarget - calculatedLines}`"></span> baris masuk hutang)
                            </span>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Section 3: Status Kelancaran, Tanggal & Guru Penerima --}}
    <div class="card-natural p-6">
        <h4 class="text-xs font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400 mb-4 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            3. Verifikasi Status Kelancaran & Guru Pengampu
        </h4>

        <div class="grid gap-6 md:grid-cols-3">
            <div>
                <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Tanggal Setoran <span class="text-rose-500">*</span></label>
                <input type="date" name="record_date"
                       value="{{ old('record_date', isset($record) && $record ? $record->record_date?->format('Y-m-d') : now()->toDateString()) }}"
                       class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 focus:outline-none" required>
                @error('record_date') <p class="mt-1.5 text-xs text-rose-600 dark:text-rose-450">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Status Kelancaran <span class="text-rose-500">*</span></label>
                <select name="status" 
                        x-model="status"
                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-bold text-slate-800 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 focus:outline-none" required>
                    @foreach ($statuses as $st)
                        <option value="{{ $st }}">
                            {{ strtoupper(str_replace('_', ' ', $st)) }}
                        </option>
                    @endforeach
                </select>
                @error('status') <p class="mt-1.5 text-xs text-rose-600 dark:text-rose-450">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Ustadz Penerima</label>
                <select name="teacher_id" 
                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 focus:outline-none" 
                        @disabled(auth()->user()->hasRole('teacher'))>
                    <option value="">Pilih Guru</option>
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
        </div>
    </div>

    {{-- Section 4: Informasi Surah dan Ayat (Opsional) --}}
    <div class="card-natural p-6">
        <h4 class="text-xs font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400 mb-4 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" /></svg>
            4. Surah & Ayat (Opsional)
        </h4>

        <div class="grid gap-4 sm:grid-cols-2 md:grid-cols-4">
            <div>
                <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Surah Awal</label>
                <select name="start_surah_id" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 focus:outline-none">
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
                       placeholder="No. Ayat"
                       class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 focus:outline-none">
                @error('start_ayah') <p class="mt-1.5 text-xs text-rose-600 dark:text-rose-450">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Surah Akhir</label>
                <select name="end_surah_id" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 focus:outline-none">
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
                       placeholder="No. Ayat"
                       class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 focus:outline-none">
                @error('end_ayah') <p class="mt-1.5 text-xs text-rose-600 dark:text-rose-450">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>

    {{-- Section 5: Penilaian Kualitas & Catatan Guru --}}
    <div class="card-natural p-6">
        <h4 class="text-xs font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400 mb-4 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" /></svg>
            5. Penilaian Kualitas Tajwid & Catatan Talaqqi
        </h4>

        <div class="grid gap-6 md:grid-cols-2">
            <div>
                <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Nilai Kualitas (0-100)</label>
                
                {{-- Quick Rating Preset Buttons --}}
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 mb-3">
                    <button type="button" @click="qualityScore = 95" 
                            class="p-2 rounded-xl text-xs font-bold border transition text-center"
                            :class="qualityScore == 95 ? 'bg-emerald-600 text-white border-emerald-600 shadow-sm' : 'bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:border-emerald-500'">
                        ⭐ 95 Mumtaz
                    </button>
                    <button type="button" @click="qualityScore = 85" 
                            class="p-2 rounded-xl text-xs font-bold border transition text-center"
                            :class="qualityScore == 85 ? 'bg-emerald-600 text-white border-emerald-600 shadow-sm' : 'bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:border-emerald-500'">
                        🌟 85 Jayyid Jiddan
                    </button>
                    <button type="button" @click="qualityScore = 75" 
                            class="p-2 rounded-xl text-xs font-bold border transition text-center"
                            :class="qualityScore == 75 ? 'bg-emerald-600 text-white border-emerald-600 shadow-sm' : 'bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:border-emerald-500'">
                        👍 75 Jayyid
                    </button>
                    <button type="button" @click="qualityScore = 65" 
                            class="p-2 rounded-xl text-xs font-bold border transition text-center"
                            :class="qualityScore == 65 ? 'bg-amber-600 text-white border-amber-600 shadow-sm' : 'bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:border-amber-500'">
                        📖 65 Maqbul
                    </button>
                </div>

                <input type="number" name="quality_score" min="0" max="100"
                       x-model="qualityScore"
                       placeholder="Contoh: 95"
                       class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-bold text-slate-800 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 focus:outline-none">
                @error('quality_score') <p class="mt-1.5 text-xs text-rose-600 dark:text-rose-450">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Catatan Ustadz</label>
                
                {{-- Quick Notes Appenders --}}
                <div class="flex flex-wrap gap-1.5 mb-2">
                    <button type="button" @click="appendNote('Alhamdulillah lancar & tartil.')" class="px-2 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-800">+ Lancar</button>
                    <button type="button" @click="appendNote('Perbaiki ghunnah & makhraj huruf.')" class="px-2 py-0.5 rounded-full text-[11px] font-semibold bg-slate-100 text-slate-700 hover:bg-slate-200 border border-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700">+ Tajwid</button>
                    <button type="button" @click="appendNote('Perlu muraja\'ah lebih giat di rumah.')" class="px-2 py-0.5 rounded-full text-[11px] font-semibold bg-slate-100 text-slate-700 hover:bg-slate-200 border border-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700">+ Muraja\'ah</button>
                </div>

                <textarea name="notes" rows="3" x-model="notes"
                          placeholder="Catatan perkembangan tahfizh santri..."
                          class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 focus:outline-none">{{ old('notes', $record->notes ?? '') }}</textarea>
                @error('notes') <p class="mt-1.5 text-xs text-rose-600 dark:text-rose-450">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>
</div>

<script>
function talaqqiForm(config) {
    return {
        students: config.students || {},
        studentId: config.initialStudentId || '',
        filterClassId: '',
        startPage: config.initialStartPage || '',
        startLine: config.initialStartLine || 1,
        endPage: config.initialEndPage || '',
        endLine: config.initialEndLine || 15,
        qualityScore: config.initialQualityScore || 95,
        status: config.initialStatus || 'lunas',
        notes: `{!! addslashes(old('notes', $record->notes ?? '')) !!}`,
        dailyTarget: 15,

        get selectedStudent() {
            return this.students[this.studentId] || null;
        },

        init() {
            if (this.studentId && this.students[this.studentId]) {
                if (!this.startPage) {
                    this.applyAutoStart();
                }
            }
            this.updateCalculations();
        },

        onStudentChange(id) {
            if (this.students[id]) {
                const s = this.students[id];
                this.startPage = s.expected_page;
                this.startLine = s.expected_line;
                this.addLines(15);
            }
        },

        applyAutoStart() {
            if (this.selectedStudent) {
                this.startPage = this.selectedStudent.expected_page;
                this.startLine = this.selectedStudent.expected_line;
                this.addLines(15);
            }
        },

        addLines(totalLines) {
            const startP = parseInt(this.startPage) || 1;
            const startL = parseInt(this.startLine) || 1;
            const count = parseInt(totalLines) || 15;

            // Absolute position index from page 1:line 1
            const startPos = (startP - 1) * 15 + startL;
            const targetEndPos = startPos + count - 1;

            this.endPage = Math.min(604, Math.floor((targetEndPos - 1) / 15) + 1);
            this.endLine = ((targetEndPos - 1) % 15) + 1;
            this.updateCalculations();
        },

        get calculatedLines() {
            const sP = parseInt(this.startPage) || 0;
            const sL = parseInt(this.startLine) || 0;
            const eP = parseInt(this.endPage) || 0;
            const eL = parseInt(this.endLine) || 0;

            if (sP <= 0 || sL <= 0 || eP <= 0 || eL <= 0) return 0;
            if (eP < sP) return 0;
            if (eP === sP && eL < sL) return 0;

            const startPos = (sP - 1) * 15 + sL;
            const endPos = (eP - 1) * 15 + eL;
            return Math.max(0, endPos - startPos + 1);
        },

        updateCalculations() {
            const lines = this.calculatedLines;
            if (lines >= this.dailyTarget) {
                this.status = lines > (this.dailyTarget + 5) ? 'lebih' : 'lunas';
            } else if (lines > 0) {
                this.status = 'kurang';
            }
        },

        appendNote(text) {
            if (this.notes.length > 0) {
                this.notes += ' ' + text;
            } else {
                this.notes = text;
            }
        }
    };
}
</script>

