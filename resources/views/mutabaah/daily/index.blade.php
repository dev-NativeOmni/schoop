@extends('layouts.app')

@section('title', 'Input Mutabaah Harian')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-slate-200/80 pb-5 dark:border-slate-800">
        <div>
            <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-50 text-[11px] font-bold text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/40 mb-1">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                Fast Mutabaah Yaumiyyah Engine
            </div>
            <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-slate-900 dark:text-white">Input Mutabaah & Karakter Harian</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400">Pencatatan cepat ibadah wajib, sunnah, zikir, dan pembiasaan adab harian santri.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('mutabaah.reports.dashboard') }}" class="btn-natural-secondary text-xs px-3.5 py-2">
                📊 Lihat Laporan
            </a>
            @if(auth()->user()->hasRole(['super_admin', 'admin']))
                <a href="{{ route('mutabaah.activities.index') }}" class="btn-natural-secondary text-xs px-3.5 py-2">
                    ⚙️ Template Aktivitas
                </a>
            @endif
        </div>
    </div>

    {{-- Flash & Validation Messages --}}
    @if(session('success'))
        <div class="card-natural p-4 bg-emerald-50/80 border-emerald-200 text-emerald-800 dark:bg-emerald-950/30 dark:border-emerald-900/50 dark:text-emerald-300 flex items-center gap-3">
            <div class="w-8 h-8 rounded-full bg-emerald-100 dark:bg-emerald-900/50 flex items-center justify-center text-emerald-600 shrink-0">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <span class="text-xs font-bold">{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="card-natural p-4 bg-rose-50/80 border-rose-200 text-rose-800 dark:bg-rose-950/30 dark:border-rose-900/50 dark:text-rose-300">
            <div class="flex items-center gap-2 font-bold text-xs mb-1">
                <svg class="w-4 h-4 text-rose-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <span>Terdapat kendala pada input:</span>
            </div>
            <ul class="list-disc pl-6 text-xs space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Filter Panel with Quick Date Switches --}}
    <div class="card-natural p-5 space-y-4">
        <form method="GET" action="{{ route('mutabaah.daily.index') }}" class="grid gap-4 sm:grid-cols-12 items-end">
            <div class="sm:col-span-4">
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">Tanggal Mutabaah</label>
                <div class="space-y-2">
                    <input type="date" name="record_date" value="{{ $selectedDate }}" id="dateInput"
                           class="w-full rounded-xl border border-slate-200/80 bg-slate-50/50 px-3.5 py-2 text-xs font-semibold text-slate-900 focus:border-emerald-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-emerald-500/10 dark:border-slate-800 dark:bg-slate-950 dark:text-white">
                    <div class="flex items-center gap-1.5">
                        <button type="button" onclick="setDate('{{ today()->toDateString() }}')" class="px-2.5 py-1 rounded-lg text-[10px] font-bold transition-all @if($selectedDate === today()->toDateString()) bg-emerald-600 text-white @else bg-slate-100 text-slate-600 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300 @endif">Hari Ini</button>
                        <button type="button" onclick="setDate('{{ today()->subDay()->toDateString() }}')" class="px-2.5 py-1 rounded-lg text-[10px] font-bold transition-all @if($selectedDate === today()->subDay()->toDateString()) bg-emerald-600 text-white @else bg-slate-100 text-slate-600 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300 @endif">Kemarin</button>
                        <button type="button" onclick="setDate('{{ today()->subDays(2)->toDateString() }}')" class="px-2.5 py-1 rounded-lg text-[10px] font-bold transition-all @if($selectedDate === today()->subDays(2)->toDateString()) bg-emerald-600 text-white @else bg-slate-100 text-slate-600 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300 @endif">2 Hari Lalu</button>
                    </div>
                </div>
            </div>
            <div class="sm:col-span-3">
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">Pilih Kelas / Halaqah</label>
                <select name="class_room_id" 
                        class="w-full rounded-xl border border-slate-200/80 bg-slate-50/50 px-3 py-2 text-xs font-semibold text-slate-900 focus:border-emerald-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-emerald-500/10 dark:border-slate-800 dark:bg-slate-950 dark:text-white">
                    <option value="">Semua Kelas</option>
                    @foreach($classRooms as $classRoom)
                        <option value="{{ $classRoom->id }}" @selected(request('class_room_id') == $classRoom->id)>
                            {{ $classRoom->name ?? 'Kelas #' . $classRoom->id }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="sm:col-span-3">
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">Pilih Santri Spesifik</label>
                <select name="student_id" 
                        class="w-full rounded-xl border border-slate-200/80 bg-slate-50/50 px-3 py-2 text-xs font-semibold text-slate-900 focus:border-emerald-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-emerald-500/10 dark:border-slate-800 dark:bg-slate-950 dark:text-white">
                    <option value="">Semua Santri ({{ $students->count() }})</option>
                    @foreach($students as $s)
                        <option value="{{ $s->id }}" @selected(request('student_id') == $s->id)>
                            {{ $s->full_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="sm:col-span-2">
                <button type="submit" class="w-full btn-natural-primary text-xs py-2.5 justify-center">
                    🔍 Terapkan
                </button>
            </div>
        </form>
    </div>

    {{-- Student Cards List --}}
    @forelse($students as $student)
        @php
            $studentRecords = $existingRecords->get($student->id) ?? collect();
            $initialRecords = [];
            foreach ($activities as $idx => $act) {
                $rec = $studentRecords->get($act->id);
                $initialRecords[$act->id] = [
                    'status' => $rec?->status ?? 'not_done',
                    'score' => $rec?->score ?? '',
                    'count_value' => $rec?->count_value ?? '',
                    'note' => $rec?->note ?? '',
                ];
            }
        @endphp

        <div x-data="mutabaahStudentForm({{ json_encode($initialRecords) }}, {{ $activities->count() }})" 
             class="card-natural overflow-hidden">
            <form method="POST" action="{{ route('mutabaah.daily.store') }}">
                @csrf
                <input type="hidden" name="student_id" value="{{ $student->id }}">
                <input type="hidden" name="record_date" value="{{ $selectedDate }}">

                {{-- Student Header Bar --}}
                <div class="p-5 bg-slate-50/70 dark:bg-slate-950/40 border-b border-slate-100 dark:border-slate-800 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 rounded-2xl bg-emerald-100 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300 flex items-center justify-center font-black text-base shadow-2xs">
                            {{ strtoupper(substr($student->full_name, 0, 2)) }}
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h2 class="text-base font-extrabold text-slate-900 dark:text-white">{{ $student->full_name }}</h2>
                                <span class="text-[11px] font-semibold text-slate-400">NIS: {{ $student->student_number ?? '-' }}</span>
                            </div>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                {{ $student->classRoom?->name ?? 'Halaqah Umum' }} &bull; Tanggal: <span class="font-bold text-slate-700 dark:text-slate-300">{{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('l, d F Y') }}</span>
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center flex-wrap gap-2.5">
                        {{-- Live Completion Progress Badge --}}
                        <div class="px-3.5 py-1.5 rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 flex items-center gap-2 shadow-2xs">
                            <span class="text-[11px] font-bold text-slate-400 uppercase">Capaian:</span>
                            <span class="text-xs font-black text-emerald-600 dark:text-emerald-400" x-text="completionPercent + '%'"></span>
                            <span class="text-[11px] text-slate-400" x-text="'(' + countDone + '/' + totalActivities + ')'"></span>
                        </div>

                        {{-- 1-Click Mark All Done Button --}}
                        <button type="button" @click="markAllDone()" 
                                class="px-3 py-1.5 rounded-xl text-xs font-extrabold bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200/60 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-800/40 transition-all flex items-center gap-1.5 active:scale-95">
                            <span>⚡</span>
                            <span>Tandai Semua Selesai</span>
                        </button>

                        {{-- Save Button --}}
                        <button type="submit" class="btn-natural-primary text-xs py-1.5 px-4 shadow-sm">
                            💾 Simpan
                        </button>
                    </div>
                </div>

                {{-- Activity Checklist Rows --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-slate-100 text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:border-slate-800 bg-slate-50/40 dark:bg-slate-950/20">
                                <th class="py-3 px-5">Aktivitas Ibadah / Karakter</th>
                                <th class="py-3 px-4 w-60">Status Keberhasilan</th>
                                <th class="py-3 px-4 w-40">Nilai / Jumlah</th>
                                <th class="py-3 px-5">Catatan Musyriif / Guru</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @foreach($activities as $index => $activity)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-950/20 transition-all">
                                    {{-- Activity info --}}
                                    <td class="py-3.5 px-5">
                                        <input type="hidden" name="records[{{ $index }}][mutabaah_activity_id]" value="{{ $activity->id }}">
                                        <div class="font-extrabold text-slate-900 dark:text-white">{{ $activity->name }}</div>
                                        <div class="flex items-center gap-1.5 mt-1">
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400">
                                                {{ $activity->category?->name ?? 'Umum' }}
                                            </span>
                                            @if($activity->input_type === 'score')
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-sky-50 text-sky-700 dark:bg-sky-950/30 dark:text-sky-300">Skor (0-100)</span>
                                            @elseif($activity->input_type === 'count')
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-amber-50 text-amber-700 dark:bg-amber-950/30 dark:text-amber-300">Target: {{ $activity->target_count ?? 1 }} {{ $activity->target_unit ?? 'Kali' }}</span>
                                            @endif
                                        </div>
                                    </td>

                                    {{-- Segmented Status Toggle --}}
                                    <td class="py-3.5 px-4">
                                        <input type="hidden" name="records[{{ $index }}][status]" :value="records[{{ $activity->id }}].status">
                                        <div class="inline-flex p-1 rounded-xl bg-slate-100 dark:bg-slate-950 border border-slate-200/60 dark:border-slate-800 gap-1">
                                            <button type="button" @click="setStatus({{ $activity->id }}, 'done')" 
                                                    :class="records[{{ $activity->id }}].status === 'done' ? 'bg-emerald-600 text-white font-black shadow-xs' : 'text-slate-500 hover:text-slate-800 dark:text-slate-400'"
                                                    class="px-2.5 py-1 rounded-lg text-[11px] font-bold transition-all flex items-center gap-1">
                                                <span>✓</span> Selesai
                                            </button>
                                            <button type="button" @click="setStatus({{ $activity->id }}, 'not_done')" 
                                                    :class="records[{{ $activity->id }}].status === 'not_done' ? 'bg-rose-600 text-white font-black shadow-xs' : 'text-slate-500 hover:text-slate-800 dark:text-slate-400'"
                                                    class="px-2 py-1 rounded-lg text-[11px] font-bold transition-all flex items-center gap-1">
                                                <span>✕</span> Belum
                                            </button>
                                            <button type="button" @click="setStatus({{ $activity->id }}, 'excused')" 
                                                    :class="records[{{ $activity->id }}].status === 'excused' ? 'bg-amber-600 text-white font-black shadow-xs' : 'text-slate-500 hover:text-slate-800 dark:text-slate-400'"
                                                    class="px-2 py-1 rounded-lg text-[11px] font-bold transition-all flex items-center gap-1">
                                                <span>⚠</span> Uzur
                                            </button>
                                        </div>
                                    </td>

                                    {{-- Value/Score input --}}
                                    <td class="py-3.5 px-4">
                                        @if($activity->input_type === 'score')
                                            <div class="relative">
                                                <input type="number" name="records[{{ $index }}][score]" x-model="records[{{ $activity->id }}].score" min="0" max="100" placeholder="0 - 100"
                                                       class="w-full rounded-xl border border-slate-200/80 bg-slate-50/50 px-3 py-1.5 text-xs font-bold text-slate-900 focus:border-emerald-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-emerald-500/10 dark:border-slate-800 dark:bg-slate-950 dark:text-white">
                                            </div>
                                        @elseif($activity->input_type === 'count')
                                            <div class="flex items-center gap-1.5">
                                                <input type="number" name="records[{{ $index }}][count_value]" x-model="records[{{ $activity->id }}].count_value" min="0" placeholder="0"
                                                       class="w-20 rounded-xl border border-slate-200/80 bg-slate-50/50 px-3 py-1.5 text-xs font-bold text-slate-900 focus:border-emerald-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-emerald-500/10 dark:border-slate-800 dark:bg-slate-950 dark:text-white">
                                                <span class="text-[11px] text-slate-400 font-semibold">{{ $activity->target_unit ?? 'Kali' }}</span>
                                            </div>
                                        @else
                                            <span class="text-xs text-slate-400 font-medium">-</span>
                                        @endif
                                    </td>

                                    {{-- Note --}}
                                    <td class="py-3.5 px-5">
                                        <input type="text" name="records[{{ $index }}][note]" x-model="records[{{ $activity->id }}].note" placeholder="Catatan opsional..."
                                               class="w-full rounded-xl border border-slate-200/80 bg-slate-50/50 px-3 py-1.5 text-xs text-slate-900 placeholder:text-slate-400 focus:border-emerald-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-emerald-500/10 dark:border-slate-800 dark:bg-slate-950 dark:text-white">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Card Bottom Action --}}
                <div class="p-4 bg-slate-50/50 dark:bg-slate-950/30 border-t border-slate-100 dark:border-slate-800 flex justify-end">
                    <button type="submit" class="btn-natural-primary text-xs py-2 px-5">
                        💾 Simpan Mutabaah {{ $student->full_name }}
                    </button>
                </div>
            </form>
        </div>
    @empty
        <div class="card-natural p-12 text-center text-slate-400">
            <p class="text-sm font-medium">Tidak ada santri ditemukan pada filter ini.</p>
        </div>
    @endforelse

</div>

<script>
    function setDate(dateStr) {
        document.getElementById('dateInput').value = dateStr;
        document.getElementById('dateInput').form.submit();
    }

    function mutabaahStudentForm(initialData, totalCount) {
        return {
            records: initialData,
            totalActivities: totalCount,
            get countDone() {
                return Object.values(this.records).filter(r => r.status === 'done').length;
            },
            get completionPercent() {
                if (this.totalActivities === 0) return 0;
                return Math.round((this.countDone / this.totalActivities) * 100);
            },
            setStatus(activityId, status) {
                if (this.records[activityId]) {
                    this.records[activityId].status = status;
                }
            },
            markAllDone() {
                Object.keys(this.records).forEach(key => {
                    this.records[key].status = 'done';
                });
            }
        };
    }
</script>
@endsection
