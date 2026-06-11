@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h2 class="text-2xl font-bold">Laporan Bulanan Tahfizh</h2>
        <p class="text-sm text-slate-500">
            Periode {{ $periodStart->format('d/m/Y') }} sampai {{ $periodEnd->format('d/m/Y') }}
        </p>
    </div>

    <!-- Filter Form -->
    <div class="mb-6 rounded-2xl bg-white p-4 shadow-sm border border-slate-100">
        <form method="GET" action="{{ route('reports.tahfizh.monthly.index') }}"
              class="grid gap-4 md:grid-cols-6 items-end">
            <div>
                <label for="month" class="mb-1 block text-xs font-semibold text-slate-600">Bulan</label>
                <input type="month" name="month" id="month" value="{{ request('month', $month) }}"
                       class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm">
            </div>

            <div>
                <label for="class_room_id" class="mb-1 block text-xs font-semibold text-slate-600">Kelas</label>
                <select name="class_room_id" id="class_room_id" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm">
                    <option value="">Semua</option>
                    @foreach ($classRooms as $classRoom)
                        <option value="{{ $classRoom->id }}" @selected(request('class_room_id') == $classRoom->id)>
                            {{ $classRoom->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="student_id" class="mb-1 block text-xs font-semibold text-slate-600">Santri</label>
                <select name="student_id" id="student_id" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm">
                    <option value="">Semua</option>
                    @foreach ($students as $student)
                        <option value="{{ $student->id }}" @selected(request('student_id') == $student->id)>
                            {{ $student->full_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="teacher_id" class="mb-1 block text-xs font-semibold text-slate-600">Guru</label>
                <select name="teacher_id" id="teacher_id" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm">
                    <option value="">Semua</option>
                    @foreach ($teachers as $teacher)
                        <option value="{{ $teacher->id }}" @selected(request('teacher_id') == $teacher->id)>
                            {{ $teacher->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="status" class="mb-1 block text-xs font-semibold text-slate-600">Status</label>
                <select name="status" id="status" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm">
                    <option value="">Semua</option>
                    @foreach ($statuses as $status)
                        <option value="{{ $status }}" @selected(request('status') === $status)>
                            {{ strtoupper(str_replace('_', ' ', $status)) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit"
                        class="flex-1 rounded-lg bg-slate-900 py-2 px-3 text-sm font-semibold text-white hover:bg-slate-700">
                    Tampilkan
                </button>
                <a href="{{ route('reports.tahfizh.monthly.index') }}"
                   class="rounded-lg bg-slate-100 py-2 px-3 text-sm font-semibold text-slate-700 hover:bg-slate-200 text-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Data Table -->
    <div class="overflow-hidden rounded-2xl bg-white shadow-sm border border-slate-100">
        <table class="w-full border-collapse text-left text-sm">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-4 py-3">Santri</th>
                    <th class="px-4 py-3">Kelas</th>
                    <th class="px-4 py-3">Setoran</th>
                    <th class="px-4 py-3">Target</th>
                    <th class="px-4 py-3">Capaian</th>
                    <th class="px-4 py-3">Hutang</th>
                    <th class="px-4 py-3">Lebih</th>
                    <th class="px-4 py-3 font-semibold">Akumulasi</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($rows as $row)
                    <tr class="border-t hover:bg-slate-50">
                        <td class="px-4 py-3 font-semibold text-slate-900">{{ $row['student']->full_name }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $row['class_room']?->name ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $row['record_count'] }} kali</td>
                        <td class="px-4 py-3 text-slate-600">{{ $row['target_lines'] }} baris</td>
                        <td class="px-4 py-3 text-emerald-700 font-semibold">+{{ $row['actual_lines'] }} baris</td>
                        <td class="px-4 py-3 text-red-600">{{ $row['debt_lines'] }} baris</td>
                        <td class="px-4 py-3 text-blue-600">{{ $row['surplus_lines'] }} baris</td>
                        <td class="px-4 py-3 font-bold">
                            @if ($row['cumulative_debt_lines'] > 0)
                                <span class="text-red-700">{{ $row['cumulative_debt_lines'] }} baris</span>
                            @else
                                <span class="text-green-700">Lunas</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-xs font-semibold">
                            @if ($row['status'] === \App\Models\TahfizhDebt::STATUS_NO_TARGET)
                                <span class="inline-block bg-slate-100 text-slate-700 px-2 py-0.5 rounded">
                                    Belum Ada Target
                                </span>
                            @elseif ($row['status'] === \App\Models\TahfizhDebt::STATUS_MET)
                                <span class="inline-block bg-green-100 text-green-800 px-2 py-0.5 rounded">
                                    Tercapai
                                </span>
                            @elseif ($row['status'] === \App\Models\TahfizhDebt::STATUS_BEHIND)
                                <span class="inline-block bg-red-100 text-red-800 px-2 py-0.5 rounded">
                                    Kurang
                                </span>
                            @elseif ($row['status'] === \App\Models\TahfizhDebt::STATUS_AHEAD)
                                <span class="inline-block bg-blue-100 text-blue-800 px-2 py-0.5 rounded">
                                    Lebih
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('reports.tahfizh.monthly.show', ['student' => $row['student']->id, 'month' => $month]) }}"
                               class="text-indigo-600 hover:underline font-semibold">
                                Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="px-4 py-6 text-center text-slate-500">
                            Belum ada data laporan bulanan tahfizh.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
