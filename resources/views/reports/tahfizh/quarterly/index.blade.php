@extends('layouts.app')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between border-b border-slate-200/80 pb-5 dark:border-slate-800 gap-4">
        <div class="flex items-center space-x-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 dark:bg-indigo-950/30 dark:text-indigo-400">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 3.055A9.003 9.003 0 1020.945 13H11V3.055z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                </svg>
            </div>
            <div>
                <h2 class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white">Laporan Triwulan Tahfizh</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400">
                    Periode {{ $periodStart->format('d/m/Y') }} sampai {{ $periodEnd->format('d/m/Y') }} ({{ $label }})
                </p>
            </div>
        </div>
    </div>

    {{-- Filter Form --}}
    <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <h3 class="text-sm font-bold text-slate-700 dark:text-slate-350 mb-4 uppercase tracking-wider">Filter Laporan</h3>
        <form method="GET" action="{{ route('reports.tahfizh.quarterly.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 items-end">
                <div>
                    <label for="year" class="mb-1.5 block text-xs font-bold text-slate-500 dark:text-slate-455 uppercase tracking-wider">Tahun</label>
                    <input type="number" name="year" id="year" value="{{ request('year', $year) }}" min="2020" max="2100" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500">
                </div>

                <div>
                    <label for="quarter" class="mb-1.5 block text-xs font-bold text-slate-500 dark:text-slate-455 uppercase tracking-wider">Triwulan</label>
                    <select name="quarter" id="quarter" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500">
                        <option value="1" @selected(request('quarter', $quarter) == 1)>Triwulan 1 (Jan-Mar)</option>
                        <option value="2" @selected(request('quarter', $quarter) == 2)>Triwulan 2 (Apr-Jun)</option>
                        <option value="3" @selected(request('quarter', $quarter) == 3)>Triwulan 3 (Jul-Sep)</option>
                        <option value="4" @selected(request('quarter', $quarter) == 4)>Triwulan 4 (Okt-Des)</option>
                    </select>
                </div>

                <div>
                    <label for="class_room_id" class="mb-1.5 block text-xs font-bold text-slate-500 dark:text-slate-455 uppercase tracking-wider">Kelas</label>
                    <select name="class_room_id" id="class_room_id" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500">
                        <option value="">Semua Kelas</option>
                        @foreach ($classRooms as $classRoom)
                            <option value="{{ $classRoom->id }}" @selected(request('class_room_id') == $classRoom->id)>
                                {{ $classRoom->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="student_id" class="mb-1.5 block text-xs font-bold text-slate-500 dark:text-slate-455 uppercase tracking-wider">Santri</label>
                    <select name="student_id" id="student_id" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500">
                        <option value="">Semua Santri</option>
                        @foreach ($students as $student)
                            <option value="{{ $student->id }}" @selected(request('student_id') == $student->id)>
                                {{ $student->full_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="teacher_id" class="mb-1.5 block text-xs font-bold text-slate-500 dark:text-slate-455 uppercase tracking-wider">Guru</label>
                    <select name="teacher_id" id="teacher_id" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500">
                        <option value="">Semua Guru</option>
                        @foreach ($teachers as $teacher)
                            <option value="{{ $teacher->id }}" @selected(request('teacher_id') == $teacher->id)>
                                {{ $teacher->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex items-center justify-end space-x-2 pt-2 border-t border-slate-100 dark:border-slate-800/60">
                <a href="{{ route('reports.tahfizh.quarterly.index') }}" class="inline-flex items-center space-x-1.5 rounded-full border border-slate-200 px-4 py-2 text-xs font-bold text-slate-600 hover:bg-slate-50 dark:border-slate-800 dark:text-slate-400 dark:hover:bg-slate-800 transition-all focus:outline-none">
                    Reset Filter
                </a>
                <button type="submit" class="inline-flex items-center space-x-1.5 rounded-full bg-slate-900 px-4 py-2 text-xs font-bold text-white hover:bg-slate-800 dark:bg-white dark:text-slate-900 dark:hover:bg-slate-100 transition-all focus:outline-none">
                    Tampilkan Laporan
                </button>
            </div>
        </form>
    </div>

    {{-- Data Table --}}
    <div class="rounded-2xl border border-slate-200/80 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50/40 dark:bg-slate-850/40 border-b border-slate-200/80 dark:border-slate-800">
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Santri</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Kelas</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Setoran</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Target</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Capaian</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Hutang</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Lebih</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 font-semibold">Akumulasi</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 text-center w-28">Status</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 text-center w-36">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse ($rows as $row)
                        <tr class="hover:bg-slate-50/40 dark:hover:bg-slate-850/10 transition-all">
                            <td class="px-6 py-4 font-bold text-slate-800 dark:text-slate-200">
                                {{ $row['student']->full_name }}
                            </td>
                            <td class="px-6 py-4 text-slate-600 dark:text-slate-450 font-medium">
                                {{ $row['class_room']?->name ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-slate-700 dark:text-slate-350 font-semibold">
                                {{ $row['record_count'] }} kali
                            </td>
                            <td class="px-6 py-4 text-slate-600 dark:text-slate-450 font-medium">
                                {{ $row['target_lines'] }} baris
                            </td>
                            <td class="px-6 py-4 text-emerald-600 dark:text-emerald-450 font-bold">
                                +{{ $row['actual_lines'] }} baris
                            </td>
                            <td class="px-6 py-4 text-rose-600 dark:text-rose-450 font-semibold">
                                {{ $row['debt_lines'] }} baris
                            </td>
                            <td class="px-6 py-4 text-blue-600 dark:text-blue-450 font-semibold">
                                {{ $row['surplus_lines'] }} baris
                            </td>
                            <td class="px-6 py-4 font-extrabold text-slate-900 dark:text-white">
                                @if ($row['cumulative_debt_lines'] > 0)
                                    <span class="text-rose-700 dark:text-rose-400 bg-rose-50/50 dark:bg-rose-950/20 px-2 py-0.5 rounded-md border border-rose-150/40 dark:border-rose-900/40">{{ $row['cumulative_debt_lines'] }} baris</span>
                                @else
                                    <span class="text-emerald-700 dark:text-emerald-400 bg-emerald-50/50 dark:bg-emerald-950/20 px-2 py-0.5 rounded-md border border-emerald-150/40 dark:border-emerald-900/40">Lunas</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if ($row['status'] === \App\Models\TahfizhDebt::STATUS_NO_TARGET)
                                    <span class="inline-flex items-center rounded-full bg-slate-50 px-2.5 py-0.5 text-[10px] font-bold text-slate-500 border border-slate-150 dark:bg-slate-800/40 dark:text-slate-450 dark:border-slate-750">No Target</span>
                                @elseif ($row['status'] === \App\Models\TahfizhDebt::STATUS_MET)
                                    <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-0.5 text-[10px] font-bold text-emerald-700 border border-emerald-150 dark:bg-emerald-950/20 dark:text-emerald-400 dark:border-emerald-900/50">Tercapai</span>
                                @elseif ($row['status'] === \App\Models\TahfizhDebt::STATUS_BEHIND)
                                    <span class="inline-flex items-center rounded-full bg-rose-50 px-2.5 py-0.5 text-[10px] font-bold text-rose-700 border border-rose-150 dark:bg-rose-950/20 dark:text-rose-400 dark:border-rose-900/50">Kurang</span>
                                @elseif ($row['status'] === \App\Models\TahfizhDebt::STATUS_AHEAD)
                                    <span class="inline-flex items-center rounded-full bg-blue-50 px-2.5 py-0.5 text-[10px] font-bold text-blue-700 border border-blue-150 dark:bg-blue-950/20 dark:text-blue-450 dark:border-blue-900/50">Lebih</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('reports.tahfizh.quarterly.show', ['student' => $row['student']->id, 'year' => $year, 'quarter' => $quarter]) }}" class="inline-flex h-8 px-3.5 items-center justify-center rounded-full border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-bold dark:border-slate-800 dark:text-slate-400 dark:hover:bg-slate-800 transition-all">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-6 py-8 text-center text-sm text-slate-450 dark:text-slate-500">Belum ada data laporan triwulan tahfizh.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
