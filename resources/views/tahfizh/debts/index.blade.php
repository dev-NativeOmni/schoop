@extends('layouts.app')

@section('content')
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold">Hutang Hafalan</h2>
            <p class="text-sm text-slate-500">Pantau target, capaian, dan akumulasi hutang hafalan santri.</p>
        </div>
    </div>

    @if (auth()->user()->hasRole(['super_admin', 'admin']))
        <!-- Calculation Form for Admins -->
        <div class="mb-6 rounded-2xl bg-white p-6 shadow-sm border border-slate-100">
            <h3 class="text-lg font-bold text-slate-900 mb-4">Kalkulator Hutang Hafalan</h3>
            <form method="POST" action="{{ route('tahfizh.debts.calculate') }}" class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-5 items-end">
                @csrf
                <div>
                    <label for="calc_date" class="block text-xs font-semibold text-slate-600 mb-1">Tanggal Perhitungan <span class="text-red-500">*</span></label>
                    <input type="date" name="calculation_date" id="calc_date" value="{{ old('calculation_date', date('Y-m-d')) }}" class="w-full rounded-lg border border-slate-200 p-2 text-sm @error('calculation_date') border-red-500 @enderror" required>
                    @error('calculation_date')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="calc_period" class="block text-xs font-semibold text-slate-600 mb-1">Jenis Periode <span class="text-red-500">*</span></label>
                    <select name="period_type" id="calc_period" class="w-full rounded-lg border border-slate-200 p-2 text-sm @error('period_type') border-red-500 @enderror" required>
                        @foreach ($periodTypes as $type)
                            <option value="{{ $type }}" {{ old('period_type', 'daily') == $type ? 'selected' : '' }}>
                                {{ ucfirst($type) }}
                            </option>
                        @endforeach
                    </select>
                    @error('period_type')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="calc_class" class="block text-xs font-semibold text-slate-600 mb-1">Kelas <span class="text-xs text-slate-400">(Opsional)</span></label>
                    <select name="class_room_id" id="calc_class" class="w-full rounded-lg border border-slate-200 p-2 text-sm @error('class_room_id') border-red-500 @enderror">
                        <option value="">Semua Kelas</option>
                        @foreach ($classRooms as $classRoom)
                            <option value="{{ $classRoom->id }}" {{ old('class_room_id') == $classRoom->id ? 'selected' : '' }}>
                                {{ $classRoom->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('class_room_id')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="calc_student" class="block text-xs font-semibold text-slate-600 mb-1">Santri <span class="text-xs text-slate-400">(Opsional)</span></label>
                    <select name="student_id" id="calc_student" class="w-full rounded-lg border border-slate-200 p-2 text-sm @error('student_id') border-red-500 @enderror">
                        <option value="">Semua Santri</option>
                        @foreach ($students as $student)
                            <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                {{ $student->full_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('student_id')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <button type="submit" class="w-full rounded-lg bg-indigo-600 py-2 px-3 text-sm font-semibold text-white hover:bg-indigo-700">
                        Hitung Hutang
                    </button>
                </div>
            </form>
        </div>
    @endif

    <!-- Filter Table Form -->
    <div class="mb-6 rounded-2xl bg-white p-4 shadow-sm border border-slate-100">
        <form method="GET" action="{{ route('tahfizh.debts.index') }}" class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-6 items-end">
            <div>
                <label for="filter_period" class="block text-xs font-semibold text-slate-600 mb-1">Periode</label>
                <select name="period_type" id="filter_period" class="w-full rounded-lg border border-slate-200 p-2 text-sm">
                    <option value="">Semua Periode</option>
                    @foreach ($periodTypes as $type)
                        <option value="{{ $type }}" {{ request('period_type') == $type ? 'selected' : '' }}>
                            {{ ucfirst($type) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="filter_class" class="block text-xs font-semibold text-slate-600 mb-1">Kelas</label>
                <select name="class_room_id" id="filter_class" class="w-full rounded-lg border border-slate-200 p-2 text-sm">
                    <option value="">Semua Kelas</option>
                    @foreach ($classRooms as $classRoom)
                        <option value="{{ $classRoom->id }}" {{ request('class_room_id') == $classRoom->id ? 'selected' : '' }}>
                            {{ $classRoom->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="filter_student" class="block text-xs font-semibold text-slate-600 mb-1">Santri</label>
                <select name="student_id" id="filter_student" class="w-full rounded-lg border border-slate-200 p-2 text-sm">
                    <option value="">Semua Santri</option>
                    @foreach ($students as $student)
                        <option value="{{ $student->id }}" {{ request('student_id') == $student->id ? 'selected' : '' }}>
                            {{ $student->full_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="filter_status" class="block text-xs font-semibold text-slate-600 mb-1">Status</label>
                <select name="status" id="filter_status" class="w-full rounded-lg border border-slate-200 p-2 text-sm">
                    <option value="">Semua Status</option>
                    <option value="no_target" {{ request('status') == 'no_target' ? 'selected' : '' }}>Belum Ada Target</option>
                    <option value="met" {{ request('status') == 'met' ? 'selected' : '' }}>Tercapai</option>
                    <option value="behind" {{ request('status') == 'behind' ? 'selected' : '' }}>Kurang</option>
                    <option value="ahead" {{ request('status') == 'ahead' ? 'selected' : '' }}>Lebih</option>
                </select>
            </div>

            <div>
                <label for="date_from" class="block text-xs font-semibold text-slate-600 mb-1">Tanggal Mulai</label>
                <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" class="w-full rounded-lg border border-slate-200 p-2 text-sm">
            </div>

            <div>
                <label for="date_until" class="block text-xs font-semibold text-slate-600 mb-1">Tanggal Akhir</label>
                <input type="date" name="date_until" id="date_until" value="{{ request('date_until') }}" class="w-full rounded-lg border border-slate-200 p-2 text-sm">
            </div>

            <div class="md:col-span-6 flex justify-end gap-2 mt-2">
                <button type="submit" class="rounded-lg bg-slate-900 py-2 px-4 text-sm font-semibold text-white hover:bg-slate-700">
                    Filter Tabel
                </button>
                <a href="{{ route('tahfizh.debts.index') }}" class="rounded-lg bg-slate-100 py-2 px-4 text-sm font-semibold text-slate-700 hover:bg-slate-200 text-center">
                    Reset Filter
                </a>
            </div>
        </form>
    </div>

    <!-- Data Table -->
    <div class="overflow-hidden rounded-2xl bg-white shadow-sm border border-slate-100">
        <table class="w-full border-collapse text-left text-sm">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-4 py-3">Periode</th>
                    <th class="px-4 py-3">Santri</th>
                    <th class="px-4 py-3">Kelas</th>
                    <th class="px-4 py-3">Target</th>
                    <th class="px-4 py-3">Capaian</th>
                    <th class="px-4 py-3">Hutang</th>
                    <th class="px-4 py-3">Lebih</th>
                    <th class="px-4 py-3 font-semibold">Akumulasi Hutang</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($debts as $debt)
                    <tr class="border-t">
                        <td class="px-4 py-3">
                            <span class="font-semibold text-slate-700">{{ ucfirst($debt->period_type) }}</span>
                            <div class="text-xs text-slate-400 mt-0.5">
                                {{ $debt->period_start->format('d/m/Y') }} - {{ $debt->period_end->format('d/m/Y') }}
                            </div>
                        </td>
                        <td class="px-4 py-3 font-semibold text-slate-900">
                            {{ $debt->student->full_name }}
                        </td>
                        <td class="px-4 py-3 text-slate-600">
                            {{ $debt->classRoom?->name ?? '-' }}
                        </td>
                        <td class="px-4 py-3">{{ $debt->target_lines }} baris</td>
                        <td class="px-4 py-3 text-emerald-700 font-medium">+{{ $debt->actual_lines }} baris</td>
                        <td class="px-4 py-3 text-red-600">{{ $debt->debt_lines }} baris</td>
                        <td class="px-4 py-3 text-blue-600">{{ $debt->surplus_lines }} baris</td>
                        <td class="px-4 py-3 font-bold text-slate-900">
                            @if ($debt->cumulative_debt_lines > 0)
                                <span class="text-red-700">{{ $debt->cumulative_debt_lines }} baris</span>
                            @else
                                <span class="text-green-700">Lunas</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @if ($debt->status === \App\Models\TahfizhDebt::STATUS_NO_TARGET)
                                <span class="inline-block bg-slate-100 text-slate-700 px-2 py-0.5 rounded text-xs font-semibold">
                                    Belum Ada Target
                                </span>
                            @elseif ($debt->status === \App\Models\TahfizhDebt::STATUS_MET)
                                <span class="inline-block bg-green-100 text-green-800 px-2 py-0.5 rounded text-xs font-semibold">
                                    Tercapai
                                </span>
                            @elseif ($debt->status === \App\Models\TahfizhDebt::STATUS_BEHIND)
                                <span class="inline-block bg-red-100 text-red-800 px-2 py-0.5 rounded text-xs font-semibold">
                                    Kurang
                                </span>
                            @elseif ($debt->status === \App\Models\TahfizhDebt::STATUS_AHEAD)
                                <span class="inline-block bg-blue-100 text-blue-800 px-2 py-0.5 rounded text-xs font-semibold">
                                    Lebih
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('tahfizh.debts.show', $debt) }}" class="text-slate-700 hover:underline text-xs font-semibold">
                                Lihat Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="px-4 py-6 text-center text-slate-500">
                            Belum ada perhitungan hutang hafalan untuk kriteria ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $debts->links() }}
    </div>
@endsection
