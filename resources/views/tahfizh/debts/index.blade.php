@extends('layouts.app')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between border-b border-slate-200/80 pb-5 dark:border-slate-800 gap-4">
        <div class="flex items-center space-x-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 dark:bg-indigo-950/30 dark:text-indigo-400">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <h2 class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white">Hutang Hafalan</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400">Pantau target, capaian, dan akumulasi hutang hafalan santri.</p>
            </div>
        </div>
    </div>

    @if (auth()->user()->hasRole(['super_admin', 'admin']))
        {{-- Calculation Form for Admins --}}
        <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <h3 class="text-sm font-bold text-slate-700 dark:text-slate-350 mb-4 uppercase tracking-wider">Kalkulator Hutang Hafalan</h3>
            <form method="POST" action="{{ route('tahfizh.debts.calculate') }}" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-5 items-end">
                    <div>
                        <label for="calc_date" class="mb-1.5 block text-xs font-bold text-slate-500 dark:text-slate-455 uppercase tracking-wider">Tanggal Perhitungan <span class="text-rose-500">*</span></label>
                        <input type="date" name="calculation_date" id="calc_date" value="{{ old('calculation_date', date('Y-m-d')) }}" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 @error('calculation_date') border-rose-500 @enderror" required>
                        @error('calculation_date')
                            <p class="mt-1.5 text-xs text-rose-600 dark:text-rose-450">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="calc_period" class="mb-1.5 block text-xs font-bold text-slate-500 dark:text-slate-455 uppercase tracking-wider">Jenis Periode <span class="text-rose-500">*</span></label>
                        <select name="period_type" id="calc_period" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 @error('period_type') border-rose-500 @enderror" required>
                            @foreach ($periodTypes as $type)
                                <option value="{{ $type }}" {{ old('period_type', 'daily') == $type ? 'selected' : '' }}>
                                    {{ ucfirst($type) }}
                                </option>
                            @endforeach
                        </select>
                        @error('period_type')
                            <p class="mt-1.5 text-xs text-rose-600 dark:text-rose-450">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="calc_class" class="mb-1.5 block text-xs font-bold text-slate-500 dark:text-slate-455 uppercase tracking-wider">Kelas <span class="text-slate-400 font-medium">(Opsional)</span></label>
                        <select name="class_room_id" id="calc_class" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 @error('class_room_id') border-rose-500 @enderror">
                            <option value="">Semua Kelas</option>
                            @foreach ($classRooms as $classRoom)
                                <option value="{{ $classRoom->id }}" {{ old('class_room_id') == $classRoom->id ? 'selected' : '' }}>
                                    {{ $classRoom->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('class_room_id')
                            <p class="mt-1.5 text-xs text-rose-600 dark:text-rose-450">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="calc_student" class="mb-1.5 block text-xs font-bold text-slate-500 dark:text-slate-455 uppercase tracking-wider">Santri <span class="text-slate-400 font-medium">(Opsional)</span></label>
                        <select name="student_id" id="calc_student" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 @error('student_id') border-rose-500 @enderror">
                            <option value="">Semua Santri</option>
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
                        <button type="submit" class="w-full inline-flex items-center justify-center rounded-full bg-indigo-600 px-4 py-2 text-xs font-bold text-white hover:bg-indigo-700 shadow-sm transition-all focus:outline-none focus:ring-4 focus:ring-indigo-500/20 active:scale-[0.98]">
                            Hitung Hutang
                        </button>
                    </div>
                </div>
            </form>
        </div>
    @endif

    {{-- Filter Table Form --}}
    <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <h3 class="text-sm font-bold text-slate-700 dark:text-slate-350 mb-4 uppercase tracking-wider">Filter Tabel</h3>
        <form method="GET" action="{{ route('tahfizh.debts.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6">
                <div>
                    <label for="filter_period" class="mb-1.5 block text-xs font-bold text-slate-500 dark:text-slate-455 uppercase tracking-wider">Periode</label>
                    <select name="period_type" id="filter_period" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500">
                        <option value="">Semua Periode</option>
                        @foreach ($periodTypes as $type)
                            <option value="{{ $type }}" {{ request('period_type') == $type ? 'selected' : '' }}>
                                {{ ucfirst($type) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="filter_class" class="mb-1.5 block text-xs font-bold text-slate-500 dark:text-slate-455 uppercase tracking-wider">Kelas</label>
                    <select name="class_room_id" id="filter_class" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500">
                        <option value="">Semua Kelas</option>
                        @foreach ($classRooms as $classRoom)
                            <option value="{{ $classRoom->id }}" {{ request('class_room_id') == $classRoom->id ? 'selected' : '' }}>
                                {{ $classRoom->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="filter_student" class="mb-1.5 block text-xs font-bold text-slate-500 dark:text-slate-455 uppercase tracking-wider">Santri</label>
                    <select name="student_id" id="filter_student" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500">
                        <option value="">Semua Santri</option>
                        @foreach ($students as $student)
                            <option value="{{ $student->id }}" {{ request('student_id') == $student->id ? 'selected' : '' }}>
                                {{ $student->full_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="filter_status" class="mb-1.5 block text-xs font-bold text-slate-500 dark:text-slate-455 uppercase tracking-wider">Status</label>
                    <select name="status" id="filter_status" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500">
                        <option value="">Semua Status</option>
                        <option value="no_target" {{ request('status') == 'no_target' ? 'selected' : '' }}>Belum Ada Target</option>
                        <option value="met" {{ request('status') == 'met' ? 'selected' : '' }}>Tercapai</option>
                        <option value="behind" {{ request('status') == 'behind' ? 'selected' : '' }}>Kurang</option>
                        <option value="ahead" {{ request('status') == 'ahead' ? 'selected' : '' }}>Lebih</option>
                    </select>
                </div>

                <div>
                    <label for="date_from" class="mb-1.5 block text-xs font-bold text-slate-500 dark:text-slate-455 uppercase tracking-wider">Tanggal Mulai</label>
                    <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500">
                </div>

                <div>
                    <label for="date_until" class="mb-1.5 block text-xs font-bold text-slate-500 dark:text-slate-455 uppercase tracking-wider">Tanggal Akhir</label>
                    <input type="date" name="date_until" id="date_until" value="{{ request('date_until') }}" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500">
                </div>
            </div>

            <div class="flex items-center justify-end space-x-2 pt-2 border-t border-slate-100 dark:border-slate-800/60">
                <a href="{{ route('tahfizh.debts.index') }}" class="inline-flex items-center space-x-1.5 rounded-full border border-slate-200 px-4 py-2 text-xs font-bold text-slate-600 hover:bg-slate-50 dark:border-slate-800 dark:text-slate-400 dark:hover:bg-slate-800 transition-all focus:outline-none">
                    Reset Filter
                </a>
                <button type="submit" class="inline-flex items-center space-x-1.5 rounded-full bg-slate-900 px-4 py-2 text-xs font-bold text-white hover:bg-slate-800 dark:bg-white dark:text-slate-900 dark:hover:bg-slate-100 transition-all focus:outline-none">
                    Filter Tabel
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
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Periode</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Santri</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Kelas</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Target</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Capaian</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Hutang</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 font-semibold">Akumulasi</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 text-center w-28">Status</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 text-center w-36">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse ($debts as $debt)
                        <tr class="hover:bg-slate-50/40 dark:hover:bg-slate-850/10 transition-all">
                            <td class="px-6 py-4">
                                <span class="font-bold text-slate-800 dark:text-slate-200">{{ ucfirst($debt->period_type) }}</span>
                                <div class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">
                                    {{ $debt->period_start->format('d/m/Y') }} - {{ $debt->period_end->format('d/m/Y') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-800 dark:text-slate-200">
                                {{ $debt->student->full_name }}
                            </td>
                            <td class="px-6 py-4 text-slate-600 dark:text-slate-450 font-medium">
                                {{ $debt->classRoom?->name ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-slate-700 dark:text-slate-350 font-semibold">
                                {{ $debt->target_lines }} baris
                            </td>
                            <td class="px-6 py-4 text-emerald-600 dark:text-emerald-450 font-bold">
                                +{{ $debt->actual_lines }} baris
                            </td>
                            <td class="px-6 py-4">
                                @if($debt->debt_lines > 0)
                                    <span class="text-rose-600 dark:text-rose-450 font-semibold">{{ $debt->debt_lines }} baris</span>
                                @elseif($debt->surplus_lines > 0)
                                    <span class="text-blue-600 dark:text-blue-450 font-semibold">+{{ $debt->surplus_lines }} lebih</span>
                                @else
                                    <span class="text-slate-400 dark:text-slate-500 font-medium">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-extrabold text-slate-900 dark:text-white">
                                @if ($debt->cumulative_debt_lines > 0)
                                    <span class="text-rose-700 dark:text-rose-400 bg-rose-50/50 dark:bg-rose-950/20 px-2 py-0.5 rounded-md border border-rose-150/40 dark:border-rose-900/40">{{ $debt->cumulative_debt_lines }} baris</span>
                                @else
                                    <span class="text-emerald-700 dark:text-emerald-400 bg-emerald-50/50 dark:bg-emerald-950/20 px-2 py-0.5 rounded-md border border-emerald-150/40 dark:border-emerald-900/40">Lunas</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if ($debt->status === \App\Models\TahfizhDebt::STATUS_NO_TARGET)
                                    <span class="inline-flex items-center rounded-full bg-slate-50 px-2.5 py-0.5 text-[10px] font-bold text-slate-500 border border-slate-150 dark:bg-slate-800/40 dark:text-slate-450 dark:border-slate-750">No Target</span>
                                @elseif ($debt->status === \App\Models\TahfizhDebt::STATUS_MET)
                                    <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-0.5 text-[10px] font-bold text-emerald-700 border border-emerald-150 dark:bg-emerald-950/20 dark:text-emerald-400 dark:border-emerald-900/50">Tercapai</span>
                                @elseif ($debt->status === \App\Models\TahfizhDebt::STATUS_BEHIND)
                                    <span class="inline-flex items-center rounded-full bg-rose-50 px-2.5 py-0.5 text-[10px] font-bold text-rose-700 border border-rose-150 dark:bg-rose-950/20 dark:text-rose-400 dark:border-rose-900/50">Kurang</span>
                                @elseif ($debt->status === \App\Models\TahfizhDebt::STATUS_AHEAD)
                                    <span class="inline-flex items-center rounded-full bg-blue-50 px-2.5 py-0.5 text-[10px] font-bold text-blue-700 border border-blue-150 dark:bg-blue-950/20 dark:text-blue-450 dark:border-blue-900/50">Lebih</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('tahfizh.debts.show', $debt) }}" class="inline-flex h-8 px-3.5 items-center justify-center rounded-full border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-bold dark:border-slate-800 dark:text-slate-400 dark:hover:bg-slate-800 transition-all">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-8 text-center text-sm text-slate-450 dark:text-slate-500">Belum ada perhitungan hutang hafalan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    @if($debts->hasPages())
        <div class="pt-4">
            {{ $debts->links() }}
        </div>
    @endif

</div>
@endsection

