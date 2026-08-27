@extends('layouts.app')

@section('title', 'Mutabaah & Ibadah Harian Santri')

@section('content')
<div class="space-y-6" x-data="{ activeTab: 'today' }">

    @if(isset($student))
        {{-- Hero Bento Banner --}}
        <div class="card-natural p-6 bg-gradient-to-br from-emerald-800 via-emerald-700 to-teal-800 text-white shadow-lg shadow-emerald-900/10">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-white/10 text-emerald-100 text-[11px] font-bold border border-white/15 mb-2 backdrop-blur-sm">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-300 animate-pulse"></span>
                        Portal Ibadah & Karakter Santri
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">Mutabaah Yaumiyyah</h1>
                    <p class="mt-1 text-xs sm:text-sm text-emerald-100/90 max-w-xl">
                        Ahlan, <strong>{{ $student->full_name }}</strong>! Rawat kebiasaan ibadah harianmu untuk menggapai ridha Allah ﷻ.
                    </p>
                    <p class="mt-2 text-[11px] italic text-emerald-200/80 bg-black/10 px-3 py-1 rounded-xl inline-block">
                        "Amalan yang paling dicintai Allah adalah yang terus-menerus (istiqamah) meskipun sedikit." (HR. Bukhari)
                    </p>
                </div>
                
                {{-- Streak & Today Progress Pills --}}
                <div class="flex flex-wrap items-center gap-3 shrink-0">
                    {{-- Istiqamah Streak --}}
                    <div class="rounded-2xl bg-white/10 backdrop-blur-md px-4 py-3 border border-white/20 min-w-[130px] text-center">
                        <span class="block text-[11px] font-medium text-emerald-200">Istiqamah Streak</span>
                        <div class="flex items-center justify-center gap-1.5 mt-0.5">
                            <span class="text-2xl">🔥</span>
                            <span class="text-2xl font-black tracking-tight text-amber-300">{{ $streak ?? 0 }}</span>
                            <span class="text-xs text-emerald-200">Hari</span>
                        </div>
                    </div>

                    {{-- Today Rate --}}
                    <div class="rounded-2xl bg-white/10 backdrop-blur-md px-4 py-3 border border-white/20 min-w-[130px] text-center">
                        <span class="block text-[11px] font-medium text-emerald-200">Capaian Hari Ini</span>
                        <div class="flex items-center justify-center gap-1 mt-0.5">
                            <span class="text-2xl font-black tracking-tight text-white">{{ $dayCompletionRate ?? 0 }}%</span>
                            <span class="text-xs text-emerald-200 font-semibold">({{ $doneDay ?? 0 }}/{{ $totalDay ?? 0 }})</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Flash Messages --}}
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

        {{-- Navigation Tabs --}}
        <div class="flex items-center gap-2 border-b border-slate-200 dark:border-slate-800 pb-2">
            <button type="button" @click="activeTab = 'today'"
                    :class="activeTab === 'today' ? 'bg-emerald-600 text-white font-extrabold shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300 font-bold'"
                    class="px-4 py-2 rounded-xl text-xs transition-all flex items-center gap-2">
                <span>📝</span>
                <span>Checklist Ibadah Hari Ini</span>
            </button>
            <button type="button" @click="activeTab = 'history'"
                    :class="activeTab === 'history' ? 'bg-emerald-600 text-white font-extrabold shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300 font-bold'"
                    class="px-4 py-2 rounded-xl text-xs transition-all flex items-center gap-2">
                <span>📊</span>
                <span>Riwayat & Rekap Mutabaah</span>
            </button>
        </div>

        {{-- TAB 1: DAILY INTERACTIVE CHECKLIST --}}
        <div x-show="activeTab === 'today'" class="space-y-6">
            {{-- Date Selector Bar --}}
            <div class="card-natural p-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Tanggal Pengisian:</span>
                    <form method="GET" action="{{ route('portal.student.mutabaah') }}" id="studentDateForm" class="flex items-center gap-2">
                        <input type="date" name="date" value="{{ $targetDate }}" onchange="document.getElementById('studentDateForm').submit()"
                               class="rounded-xl border border-slate-200/80 bg-slate-50 px-3 py-1 text-xs font-bold text-slate-900 focus:border-emerald-500 focus:bg-white focus:outline-none dark:border-slate-800 dark:bg-slate-950 dark:text-white">
                    </form>
                </div>
                <div class="flex items-center gap-2 text-xs font-semibold text-slate-500">
                    <span>Target: {{ $totalDay }} Aktivitas</span>
                    <span class="text-slate-300">&bull;</span>
                    <span class="text-emerald-600 font-bold">{{ $doneDay }} Selesai</span>
                </div>
            </div>

            {{-- Checklist Form --}}
            <form method="POST" action="{{ route('portal.student.mutabaah.store') }}">
                @csrf
                <input type="hidden" name="record_date" value="{{ $targetDate }}">

                <div class="space-y-6">
                    @foreach($categorizedActivities as $categoryName => $catActivities)
                        <div class="card-natural p-5 space-y-4">
                            <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
                                <div class="flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                                    <h2 class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-wider">{{ $categoryName }}</h2>
                                </div>
                                <span class="text-[11px] font-bold text-slate-400">{{ $catActivities->count() }} Kegiatan</span>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                                @foreach($catActivities as $actIdx => $act)
                                    @php
                                        $rec = $dayRecords->get($act->id);
                                        $isDone = ($rec?->status === 'done');
                                        $uniqueIdx = $act->id;
                                    @endphp
                                    <div class="p-3.5 rounded-2xl border transition-all relative group {{ $isDone ? 'bg-emerald-50/40 border-emerald-300/80 dark:bg-emerald-950/20 dark:border-emerald-800/60' : 'bg-slate-50/40 border-slate-200/70 dark:bg-slate-900 dark:border-slate-800' }}">
                                        <input type="hidden" name="records[{{ $uniqueIdx }}][mutabaah_activity_id]" value="{{ $act->id }}">
                                        
                                        <div class="flex items-start justify-between gap-3">
                                            <div class="flex-1">
                                                <div class="font-extrabold text-xs text-slate-900 dark:text-white">{{ $act->name }}</div>
                                                <div class="text-[11px] text-slate-400 mt-0.5">
                                                    @if($act->input_type === 'score')
                                                        Skor: {{ $act->target_score ?? 100 }}
                                                    @elseif($act->input_type === 'count')
                                                        Target: {{ $act->target_count ?? 1 }} {{ $act->target_unit ?? 'Kali' }}
                                                    @else
                                                        Wajib / Rutin
                                                    @endif
                                                </div>
                                            </div>

                                            {{-- Status Checkbox / Switch --}}
                                            <select name="records[{{ $uniqueIdx }}][status]" 
                                                    class="rounded-xl border border-slate-200 bg-white px-2 py-1 text-[11px] font-bold text-slate-800 focus:border-emerald-500 focus:outline-none dark:border-slate-800 dark:bg-slate-950 dark:text-white">
                                                <option value="done" @selected(($rec?->status ?? '') === 'done')>✅ Selesai</option>
                                                <option value="not_done" @selected(($rec?->status ?? 'not_done') === 'not_done')>❌ Belum</option>
                                                <option value="excused" @selected(($rec?->status ?? '') === 'excused')>⚠️ Uzur</option>
                                            </select>
                                        </div>

                                        {{-- Score / Count Input if applicable --}}
                                        @if($act->input_type === 'score')
                                            <div class="mt-2.5 pt-2 border-t border-slate-100 dark:border-slate-800">
                                                <input type="number" name="records[{{ $uniqueIdx }}][score]" value="{{ $rec?->score }}" min="0" max="100" placeholder="Skor 0-100"
                                                       class="w-full rounded-xl border border-slate-200 bg-white px-2.5 py-1 text-xs font-bold text-slate-900 dark:border-slate-800 dark:bg-slate-950 dark:text-white">
                                            </div>
                                        @elseif($act->input_type === 'count')
                                            <div class="mt-2.5 pt-2 border-t border-slate-100 dark:border-slate-800 flex items-center gap-1.5">
                                                <input type="number" name="records[{{ $uniqueIdx }}][count_value]" value="{{ $rec?->count_value }}" min="0" placeholder="Jumlah"
                                                       class="w-20 rounded-xl border border-slate-200 bg-white px-2.5 py-1 text-xs font-bold text-slate-900 dark:border-slate-800 dark:bg-slate-950 dark:text-white">
                                                <span class="text-[10px] text-slate-400 font-semibold">{{ $act->target_unit ?? 'Kali' }}</span>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Bottom Submit Action --}}
                <div class="mt-6 flex justify-end">
                    <button type="submit" class="btn-natural-primary text-xs py-3 px-8 shadow-md flex items-center gap-2">
                        <span>⚡</span>
                        <span>Simpan & Perbarui Mutabaah Hari Ini</span>
                    </button>
                </div>
            </form>
        </div>

        {{-- TAB 2: HISTORY & RECAP --}}
        <div x-show="activeTab === 'history'" class="space-y-6">
            {{-- Date Filter Form --}}
            <div class="card-natural p-5">
                <form method="GET" action="{{ route('portal.student.mutabaah') }}" class="grid gap-4 sm:grid-cols-3 items-end">
                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">Tanggal Mulai</label>
                        <input type="date" name="start_date" 
                               value="{{ $period['start_date'] ?? now()->startOfWeek()->toDateString() }}" 
                               class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-950 focus:border-emerald-500 focus:outline-none dark:border-slate-800 dark:bg-slate-950 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">Tanggal Selesai</label>
                        <input type="date" name="end_date" 
                               value="{{ $period['end_date'] ?? now()->endOfWeek()->toDateString() }}" 
                               class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-950 focus:border-emerald-500 focus:outline-none dark:border-slate-800 dark:bg-slate-950 dark:text-white">
                    </div>
                    <div>
                        <button type="submit" class="w-full btn-natural-primary text-xs py-2.5 justify-center">
                            🔍 Terapkan Periode
                        </button>
                    </div>
                </form>
            </div>

            {{-- Summary Cards --}}
            <div class="grid gap-4 sm:grid-cols-3">
                <div class="card-natural p-5">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Aktivitas</p>
                    <p class="mt-1 text-2xl font-black text-slate-900 dark:text-white">{{ $summary['total_records'] }}</p>
                    <p class="mt-1 text-[11px] text-slate-400">Pencatatan pada periode ini</p>
                </div>

                <div class="card-natural p-5">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Terlaksana (Done)</p>
                    <p class="mt-1 text-2xl font-black text-emerald-600 dark:text-emerald-400">{{ $summary['done_records'] }}</p>
                    <p class="mt-1 text-[11px] text-slate-400">Ibadah sukses terlaksana</p>
                </div>

                <div class="card-natural p-5">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Tingkat Capaian</p>
                    <p class="mt-1 text-2xl font-black text-teal-600 dark:text-teal-400">{{ $summary['completion_rate'] }}%</p>
                    <div class="mt-2 w-full bg-slate-100 rounded-full h-1.5 dark:bg-slate-800">
                        <div class="bg-teal-600 h-1.5 rounded-full dark:bg-teal-500" style="width: {{ $summary['completion_rate'] }}%"></div>
                    </div>
                </div>
            </div>

            {{-- Records Table --}}
            <div class="card-natural overflow-hidden">
                <div class="p-4 bg-slate-50/70 dark:bg-slate-950/40 border-b border-slate-100 dark:border-slate-800">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Detail Rekap Mutabaah Santri</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-slate-100 text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:border-slate-800 bg-slate-50/40 dark:bg-slate-950/20">
                                <th class="py-3 px-5">Tanggal</th>
                                <th class="py-3 px-5">Aktivitas</th>
                                <th class="py-3 px-4 w-36">Status</th>
                                <th class="py-3 px-4 w-36">Nilai / Jumlah</th>
                                <th class="py-3 px-5">Catatan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @forelse($records as $record)
                                <tr class="hover:bg-slate-50/40 dark:hover:bg-slate-950/10 transition-all">
                                    <td class="py-3 px-5 font-semibold text-slate-700 dark:text-slate-300">
                                        {{ $record->record_date?->format('d M Y') }}
                                    </td>
                                    <td class="py-3 px-5">
                                        <span class="font-bold text-slate-900 dark:text-white block">{{ $record->activity?->name ?? '-' }}</span>
                                        <span class="text-[10px] text-slate-400 font-semibold uppercase">{{ $record->activity?->category?->name ?? 'Umum' }}</span>
                                    </td>
                                    <td class="py-3 px-4">
                                        @if($record->status === 'done')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/80 dark:bg-emerald-950/30 dark:text-emerald-300 dark:border-emerald-800/40">✓ Selesai</span>
                                        @elseif($record->status === 'excused')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200/80 dark:bg-amber-950/30 dark:text-amber-300 dark:border-amber-800/40">⚠ Uzur</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200/80 dark:bg-rose-950/30 dark:text-rose-300 dark:border-rose-800/40">✕ Belum</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 font-semibold text-slate-700 dark:text-slate-300">
                                        @if($record->score !== null)
                                            {{ $record->score }} <span class="text-[10px] text-slate-400 font-medium">skor</span>
                                        @elseif($record->count_value !== null)
                                            {{ $record->count_value }} <span class="text-[10px] text-slate-400 font-medium">{{ $record->activity?->target_unit ?? 'kali' }}</span>
                                        @else
                                            <span class="text-slate-300">—</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-5 text-slate-500 dark:text-slate-400 italic">
                                        {{ $record->note ?? '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-xs text-slate-400">Belum ada catatan mutabaah pada periode ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    @else
        <div class="card-natural p-12 text-center text-slate-400">
            <h3 class="text-sm font-bold text-slate-900 dark:text-white">Data santri belum terhubung</h3>
            <p class="text-xs text-slate-500 mt-1">Hubungi admin sekolah untuk menghubungkan akun pengguna Anda ke profil santri.</p>
        </div>
    @endif

</div>
@endsection

