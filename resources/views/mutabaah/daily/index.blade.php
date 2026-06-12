@extends('layouts.app')

@section('title', 'Input Mutabaah Harian')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between border-b border-slate-200/80 pb-5 dark:border-slate-800">
        <div>
            <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 dark:bg-indigo-950/30 dark:text-indigo-400">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </div>
                <span>Input Mutabaah Harian</span>
            </h1>
            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Input aktivitas ibadah dan pembentukan karakter harian santri.</p>
        </div>
    </div>

    {{-- Flash & Validation Messages --}}
    @if(session('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50/80 p-4 text-emerald-800 backdrop-blur-sm dark:border-emerald-900/50 dark:bg-emerald-950/30 dark:text-emerald-350">
            <div class="flex">
                <svg class="h-5 w-5 text-emerald-500 me-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-sm font-semibold">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="rounded-xl border border-rose-200 bg-rose-50/80 p-4 text-rose-800 backdrop-blur-sm dark:border-rose-900/50 dark:bg-rose-950/30 dark:text-rose-350">
            <div class="flex flex-col">
                <div class="flex items-center mb-2">
                    <svg class="h-5 w-5 text-rose-500 me-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <span class="text-sm font-semibold">Terdapat kesalahan input:</span>
                </div>
                <ul class="list-disc pl-5 text-xs space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    {{-- Filter Form --}}
    <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <form method="GET" action="{{ route('mutabaah.daily.index') }}" class="grid gap-4 sm:grid-cols-4 items-end">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">Tanggal</label>
                <input type="date" name="record_date" value="{{ $selectedDate }}" 
                       class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-sm text-slate-950 placeholder-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 dark:border-slate-800 dark:bg-slate-950 dark:text-white dark:focus:border-indigo-500">
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">Kelas</label>
                <select name="class_room_id" 
                        class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-sm text-slate-950 focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 dark:border-slate-800 dark:bg-slate-950 dark:text-white dark:focus:border-indigo-500">
                    <option value="">Semua Kelas</option>
                    @foreach($classRooms as $classRoom)
                        <option value="{{ $classRoom->id }}" @selected(request('class_room_id') == $classRoom->id)>
                            {{ $classRoom->name ?? 'Kelas #' . $classRoom->id }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">Santri</label>
                <select name="student_id" 
                        class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-sm text-slate-950 focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 dark:border-slate-800 dark:bg-slate-950 dark:text-white dark:focus:border-indigo-500">
                    <option value="">Semua Santri</option>
                    @foreach($students as $s)
                        <option value="{{ $s->id }}" @selected(request('student_id') == $s->id)>
                            {{ $s->full_name ?? 'Santri #' . $s->id }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <button type="submit" 
                        class="flex w-full items-center justify-center space-x-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700 shadow-sm transition-all focus:outline-none focus:ring-4 focus:ring-indigo-500/20 active:scale-[0.98]">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <span>Terapkan Filter</span>
                </button>
            </div>
        </form>
    </div>

    {{-- Students Loop --}}
    @forelse($students as $student)
        @php
            $studentRecords = $existingRecords->get($student->id) ?? collect();
        @endphp
        <div class="rounded-2xl border border-slate-200/80 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900 overflow-hidden">
            <form method="POST" action="{{ route('mutabaah.daily.store') }}">
                @csrf
                <input type="hidden" name="student_id" value="{{ $student->id }}">
                <input type="hidden" name="record_date" value="{{ $selectedDate }}">

                {{-- Header Siswa --}}
                <div class="bg-slate-50/80 dark:bg-slate-850 px-6 py-4 border-b border-slate-200/80 dark:border-slate-800 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="flex items-center space-x-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-indigo-100 text-indigo-700 dark:bg-indigo-950/50 dark:text-indigo-400 font-bold text-sm tracking-wide">
                            {{ strtoupper(substr($student->full_name ?? 'S', 0, 2)) }}
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-slate-800 dark:text-white">{{ $student->full_name }}</h2>
                            <p class="text-xs text-slate-500 dark:text-slate-400">{{ $student->classRoom?->name ?? 'Belum ada kelas' }} · Tanggal Input: <span class="font-semibold text-slate-700 dark:text-slate-350">{{ $selectedDate }}</span></p>
                        </div>
                    </div>
                    <div>
                        <button type="submit" 
                                class="inline-flex items-center space-x-2 rounded-xl bg-indigo-600 px-4 py-2 text-xs font-semibold text-white hover:bg-indigo-700 shadow-sm transition-all focus:outline-none focus:ring-4 focus:ring-indigo-500/20 active:scale-[0.98]">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-1-1m1 1V3" />
                            </svg>
                            <span>Simpan Mutabaah</span>
                        </button>
                    </div>
                </div>

                {{-- Tabel Input Aktivitas --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/40 dark:bg-slate-850/40 border-b border-slate-200/80 dark:border-slate-800">
                                <th class="px-6 py-3.5 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Aktivitas</th>
                                <th class="px-6 py-3.5 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 w-44">Status</th>
                                <th class="px-6 py-3.5 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 w-32">Skor (0-100)</th>
                                <th class="px-6 py-3.5 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 w-32">Jumlah</th>
                                <th class="px-6 py-3.5 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Catatan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @foreach($activities as $index => $activity)
                                @php
                                    $record = $studentRecords->get($activity->id);
                                @endphp
                                <tr class="hover:bg-slate-50/40 dark:hover:bg-slate-850/20 transition-all">
                                    {{-- Aktivitas --}}
                                    <td class="px-6 py-4">
                                        <input type="hidden" name="records[{{ $index }}][mutabaah_activity_id]" value="{{ $activity->id }}">
                                        <span class="block text-sm font-bold text-slate-800 dark:text-slate-250">{{ $activity->name }}</span>
                                        <span class="inline-flex mt-1 items-center space-x-1.5">
                                            <span class="text-[10px] uppercase font-bold tracking-wider text-slate-400">
                                                {{ $activity->category?->name ?? 'Umum' }}
                                            </span>
                                            <span class="text-slate-300 dark:text-slate-700 font-bold text-xs">·</span>
                                            @php
                                                $typeColors = [
                                                    'checklist' => 'bg-emerald-50 text-emerald-700 border-emerald-150 dark:bg-emerald-950/20 dark:text-emerald-400 dark:border-emerald-900/50',
                                                    'score' => 'bg-sky-50 text-sky-700 border-sky-150 dark:bg-sky-950/20 dark:text-sky-400 dark:border-sky-900/50',
                                                    'count' => 'bg-amber-50 text-amber-700 border-amber-150 dark:bg-amber-950/20 dark:text-amber-400 dark:border-amber-900/50',
                                                    'text' => 'bg-slate-50 text-slate-700 border-slate-150 dark:bg-slate-800/20 dark:text-slate-400 dark:border-slate-750',
                                                ];
                                                $colorClass = $typeColors[$activity->input_type] ?? 'bg-slate-50 text-slate-700 border-slate-150';
                                            @endphp
                                            <span class="inline-flex items-center rounded-md px-1.5 py-0.5 text-[9px] font-bold uppercase tracking-wide border {{ $colorClass }}">
                                                {{ $activity->input_type }}
                                            </span>
                                        </span>
                                    </td>

                                    {{-- Status Dropdown --}}
                                    <td class="px-6 py-4">
                                        <select name="records[{{ $index }}][status]" 
                                                class="w-full rounded-xl border border-slate-200 bg-white px-2.5 py-1.5 text-xs text-slate-900 focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 dark:border-slate-800 dark:bg-slate-950 dark:text-white dark:focus:border-indigo-500">
                                            <option value="done" @selected(($record?->status ?? 'not_done') === 'done')>✅ Selesai</option>
                                            <option value="not_done" @selected(($record?->status ?? 'not_done') === 'not_done')>❌ Belum</option>
                                            <option value="excused" @selected(($record?->status ?? 'not_done') === 'excused')>⚠️ Izin/Uzur</option>
                                        </select>
                                    </td>

                                    {{-- Skor --}}
                                    <td class="px-6 py-4">
                                        <input type="number" name="records[{{ $index }}][score]"
                                               value="{{ $record?->score }}" min="0" max="100"
                                               class="w-full rounded-xl border border-slate-200 bg-white px-2.5 py-1.5 text-xs text-slate-900 placeholder-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 disabled:bg-slate-50 disabled:text-slate-350 disabled:border-slate-100 dark:border-slate-800 dark:bg-slate-950 dark:text-white dark:focus:border-indigo-500 dark:disabled:bg-slate-900 dark:disabled:border-slate-850"
                                               @disabled($activity->input_type !== 'score')
                                               placeholder="0-100">
                                    </td>

                                    {{-- Jumlah --}}
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-1.5">
                                            <input type="number" name="records[{{ $index }}][count_value]"
                                                   value="{{ $record?->count_value }}" min="0"
                                                   class="w-full rounded-xl border border-slate-200 bg-white px-2.5 py-1.5 text-xs text-slate-900 placeholder-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 disabled:bg-slate-50 disabled:text-slate-350 disabled:border-slate-100 dark:border-slate-800 dark:bg-slate-950 dark:text-white dark:focus:border-indigo-500 dark:disabled:bg-slate-900 dark:disabled:border-slate-850"
                                                   @disabled($activity->input_type !== 'count')
                                                   placeholder="Jumlah">
                                            @if($activity->input_type === 'count' && $activity->target_unit)
                                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">{{ $activity->target_unit }}</span>
                                            @endif
                                        </div>
                                    </td>

                                    {{-- Catatan --}}
                                    <td class="px-6 py-4">
                                        <input type="text" name="records[{{ $index }}][note]"
                                               value="{{ $record?->note }}"
                                               class="w-full rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-xs text-slate-900 placeholder-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 dark:border-slate-800 dark:bg-slate-950 dark:text-white dark:focus:border-indigo-500"
                                               placeholder="Tambahkan catatan khusus...">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Footer Card --}}
                <div class="bg-slate-50/50 dark:bg-slate-850/50 px-6 py-4 border-t border-slate-200/80 dark:border-slate-800 flex justify-end">
                    <button type="submit" 
                            class="inline-flex items-center space-x-2 rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700 shadow-sm transition-all focus:outline-none focus:ring-4 focus:ring-indigo-500/20 active:scale-[0.98]">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-1-1m1 1V3" />
                        </svg>
                        <span>Simpan Data Mutabaah</span>
                    </button>
                </div>
            </form>
        </div>
    @empty
        <div class="rounded-2xl border border-dashed border-slate-300 p-12 text-center dark:border-slate-700">
            <svg class="mx-auto h-12 w-12 text-slate-400 dark:text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
            </svg>
            <h3 class="mt-4 text-sm font-bold text-slate-900 dark:text-white">Tidak ada data santri</h3>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Silakan sesuaikan filter pencarian kelas atau nama santri Anda.</p>
        </div>
    @endforelse

</div>
@endsection
