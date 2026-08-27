@extends('layouts.app')

@section('title', 'Mutabaah & Ibadah Harian Ananda')

@section('content')
<div class="space-y-6">

    {{-- Children Selector Hub --}}
    @if(isset($children) && $children->count() > 1)
        <div class="card-natural p-4">
            <div class="flex items-center gap-2 mb-3">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Pilih Ananda:</span>
            </div>
            <div class="flex flex-wrap items-center gap-2.5">
                @foreach($children as $child)
                    @php $isActive = ($student && $student->id === $child->id); @endphp
                    <a href="{{ route('portal.parent.mutabaah', $child->id) }}" 
                       class="flex items-center gap-2.5 px-3.5 py-2 rounded-2xl border transition-all shadow-2xs {{ $isActive ? 'bg-emerald-50 text-emerald-800 border-emerald-500 font-extrabold dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-600' : 'bg-white text-slate-700 border-slate-200/80 hover:border-emerald-300 hover:bg-slate-50 dark:bg-slate-900 dark:text-slate-300 dark:border-slate-800' }}">
                        <div class="w-7 h-7 rounded-xl flex items-center justify-center font-black text-xs {{ $isActive ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-300' }}">
                            {{ strtoupper(substr($child->full_name, 0, 2)) }}
                        </div>
                        <span class="text-xs">{{ $child->full_name }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    @if(isset($student))
        {{-- Hero Header --}}
        <div class="card-natural p-6 bg-gradient-to-br from-emerald-800 via-emerald-700 to-teal-800 text-white shadow-lg shadow-emerald-900/10">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-white/10 text-emerald-100 text-[11px] font-bold border border-white/15 mb-2 backdrop-blur-sm">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-300"></span>
                        Monitoring Ibadah & Karakter Ananda
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">Mutabaah Yaumiyyah Ananda</h1>
                    <p class="mt-1 text-xs sm:text-sm text-emerald-100/90 max-w-xl">
                        Memantau pelaksanaan ibadah wajib, shalat sunnah, zikir, tilawah, dan pembiasaan adab ananda <strong>{{ $student->full_name }}</strong> ({{ $student->classRoom?->name ?? 'Santri' }}).
                    </p>
                </div>

                {{-- Summary Rate Pill --}}
                <div class="flex flex-wrap items-center gap-3 shrink-0">
                    <div class="rounded-2xl bg-white/10 backdrop-blur-md px-5 py-3.5 border border-white/20 text-center min-w-[140px]">
                        <span class="block text-[11px] font-medium text-emerald-200">Tingkat Capaian</span>
                        <div class="flex items-center justify-center gap-1.5 mt-0.5">
                            <span class="text-2xl font-black tracking-tight text-white">{{ $summary['completion_rate'] }}%</span>
                            <span class="text-xs text-emerald-200">Terlaksana</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Date Filter Form --}}
        <div class="card-natural p-5">
            <form method="GET" action="{{ route('portal.parent.mutabaah', $student->id) }}" class="grid gap-4 sm:grid-cols-3 items-end">
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

        {{-- Summary Cards Grid --}}
        <div class="grid gap-4 sm:grid-cols-3">
            <div class="card-natural p-5">
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Aktivitas</p>
                <p class="mt-1 text-2xl font-black text-slate-900 dark:text-white">{{ $summary['total_records'] }}</p>
                <p class="mt-1 text-[11px] text-slate-400">Total pencatatan periode ini</p>
            </div>

            <div class="card-natural p-5">
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Terlaksana (Done)</p>
                <p class="mt-1 text-2xl font-black text-emerald-600 dark:text-emerald-400">{{ $summary['done_records'] }}</p>
                <p class="mt-1 text-[11px] text-slate-400">Aktivitas terlaksana dengan baik</p>
            </div>

            <div class="card-natural p-5">
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Rasio Capaian</p>
                <p class="mt-1 text-2xl font-black text-teal-600 dark:text-teal-400">{{ $summary['completion_rate'] }}%</p>
                <div class="mt-2 w-full bg-slate-100 rounded-full h-1.5 dark:bg-slate-800">
                    <div class="bg-teal-600 h-1.5 rounded-full dark:bg-teal-500" style="width: {{ $summary['completion_rate'] }}%"></div>
                </div>
            </div>
        </div>

        {{-- Detail Records Table --}}
        <div class="card-natural overflow-hidden">
            <div class="p-4 bg-slate-50/70 dark:bg-slate-950/40 border-b border-slate-100 dark:border-slate-800">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Detail Aktivitas Ibadah Harian Ananda</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="border-b border-slate-100 text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:border-slate-800 bg-slate-50/40 dark:bg-slate-950/20">
                            <th class="py-3 px-5">Tanggal</th>
                            <th class="py-3 px-5">Aktivitas</th>
                            <th class="py-3 px-4 w-36">Status</th>
                            <th class="py-3 px-4 w-36">Nilai / Jumlah</th>
                            <th class="py-3 px-5">Catatan Ustadz / Pembina</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($records as $record)
                            <tr class="hover:bg-slate-50/40 dark:hover:bg-slate-950/10 transition-all">
                                <td class="py-3 px-5 font-semibold text-slate-700 dark:text-slate-350">
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
    @else
        <div class="card-natural p-12 text-center text-slate-400">
            <h3 class="text-sm font-bold text-slate-900 dark:text-white">Belum ada data anak</h3>
            <p class="text-xs text-slate-500 mt-1">Hubungi admin sekolah untuk menghubungkan data anak ke akun Anda.</p>
        </div>
    @endif

</div>
@endsection

