@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-slate-900 dark:text-white">Dashboard Tahfizh</h2>
        <p class="text-sm text-slate-500 dark:text-slate-400">
            Ringkasan setoran, target, dan hutang hafalan.
        </p>
    </div>

    <!-- Filter Form -->
    <div class="mb-6 rounded-2xl bg-white p-4 shadow-sm border border-slate-100 dark:border-slate-800 dark:bg-slate-900">
        <form method="GET" action="{{ route('reports.tahfizh.dashboard') }}"
              class="grid gap-4 md:grid-cols-6 items-end">
            <div>
                <label for="date_from" class="mb-1 block text-xs font-semibold text-slate-600 dark:text-slate-400">Dari</label>
                <input type="date" name="date_from" id="date_from" value="{{ request('date_from', $dateFrom->format('Y-m-d')) }}"
                       class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100">
            </div>

            <div>
                <label for="date_until" class="mb-1 block text-xs font-semibold text-slate-600 dark:text-slate-400">Sampai</label>
                <input type="date" name="date_until" id="date_until" value="{{ request('date_until', $dateUntil->format('Y-m-d')) }}"
                       class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100">
            </div>

            <div>
                <label for="class_room_id" class="mb-1 block text-xs font-semibold text-slate-600 dark:text-slate-400">Kelas</label>
                <select name="class_room_id" id="class_room_id" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100">
                    <option value="">Semua</option>
                    @foreach ($classRooms as $classRoom)
                        <option value="{{ $classRoom->id }}" @selected(request('class_room_id') == $classRoom->id)>
                            {{ $classRoom->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="student_id" class="mb-1 block text-xs font-semibold text-slate-600 dark:text-slate-400">Santri</label>
                <select name="student_id" id="student_id" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100">
                    <option value="">Semua</option>
                    @foreach ($students as $student)
                        <option value="{{ $student->id }}" @selected(request('student_id') == $student->id)>
                            {{ $student->full_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="teacher_id" class="mb-1 block text-xs font-semibold text-slate-600 dark:text-slate-400">Guru</label>
                <select name="teacher_id" id="teacher_id" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100">
                    <option value="">Semua</option>
                    @foreach ($teachers as $teacher)
                        <option value="{{ $teacher->id }}" @selected(request('teacher_id') == $teacher->id)>
                            {{ $teacher->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit"
                        class="flex-1 rounded-lg bg-slate-900 py-2 px-3 text-sm font-semibold text-white hover:bg-slate-700">
                    Terapkan
                </button>
                <a href="{{ route('reports.tahfizh.dashboard') }}"
                   class="rounded-lg bg-slate-100 py-2 px-3 text-sm font-semibold text-slate-700 hover:bg-slate-200 text-center dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Stats Cards -->
    <div class="grid gap-4 md:grid-cols-4">
        <div class="rounded-2xl bg-white p-5 shadow-sm border border-slate-100 dark:border-slate-800 dark:bg-slate-900">
            <div class="text-sm text-slate-500 font-semibold dark:text-slate-400">Total Santri</div>
            <div class="mt-2 text-3xl font-extrabold text-slate-900 dark:text-white">{{ $summary['total_students'] }}</div>
        </div>

        <div class="rounded-2xl bg-white p-5 shadow-sm border border-slate-100 dark:border-slate-800 dark:bg-slate-900">
            <div class="text-sm text-slate-500 font-semibold dark:text-slate-400">Total Setoran</div>
            <div class="mt-2 text-3xl font-extrabold text-slate-900 dark:text-white">{{ $summary['total_records'] }}</div>
        </div>

        <div class="rounded-2xl bg-white p-5 shadow-sm border border-slate-100 dark:border-slate-800 dark:bg-slate-900">
            <div class="text-sm text-slate-500 font-semibold dark:text-slate-400">Total Baris</div>
            <div class="mt-2 text-3xl font-extrabold text-indigo-700 dark:text-indigo-400">+{{ $summary['total_lines'] }}</div>
        </div>

        <div class="rounded-2xl bg-white p-5 shadow-sm border border-slate-100 dark:border-slate-800 dark:bg-slate-900">
            <div class="text-sm text-slate-500 font-semibold dark:text-slate-400">Akumulasi Hutang</div>
            <div class="mt-2 text-3xl font-extrabold text-red-600 dark:text-red-400">{{ $summary['total_cumulative_debt_lines'] }} baris</div>
        </div>
    </div>

    <!-- Achievement Status Cards -->
    <div class="mt-6 grid gap-4 md:grid-cols-4">
        <div class="rounded-2xl bg-white p-5 shadow-sm border border-slate-100 dark:border-slate-800 dark:bg-slate-900">
            <div class="text-sm text-slate-500 font-semibold dark:text-slate-400">Tercapai</div>
            <div class="mt-2 text-2xl font-bold text-green-700 dark:text-green-400">{{ $summary['met_count'] }}</div>
        </div>

        <div class="rounded-2xl bg-white p-5 shadow-sm border border-slate-100 dark:border-slate-800 dark:bg-slate-900">
            <div class="text-sm text-slate-500 font-semibold dark:text-slate-400">Kurang</div>
            <div class="mt-2 text-2xl font-bold text-red-600 dark:text-red-400">{{ $summary['behind_count'] }}</div>
        </div>

        <div class="rounded-2xl bg-white p-5 shadow-sm border border-slate-100 dark:border-slate-800 dark:bg-slate-900">
            <div class="text-sm text-slate-500 font-semibold dark:text-slate-400">Lebih</div>
            <div class="mt-2 text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $summary['ahead_count'] }}</div>
        </div>

        <div class="rounded-2xl bg-white p-5 shadow-sm border border-slate-100 dark:border-slate-800 dark:bg-slate-900">
            <div class="text-sm text-slate-500 font-semibold dark:text-slate-400">Belum Ada Target</div>
            <div class="mt-2 text-2xl font-bold text-slate-500 dark:text-slate-300">{{ $summary['no_target_count'] }}</div>
        </div>
    </div>

    <!-- Detailed Listings -->
    <div class="mt-8 grid gap-6 md:grid-cols-2">
        <!-- Teacher Activities -->
        <div class="rounded-2xl bg-white p-6 shadow-sm border border-slate-100 dark:border-slate-800 dark:bg-slate-900">
            <h3 class="mb-4 text-lg font-bold text-slate-900 dark:text-white">Aktivitas Guru</h3>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm border-collapse">
                    <thead>
                        <tr class="border-b bg-slate-50 dark:border-slate-800 dark:bg-slate-850">
                            <th class="py-2.5 px-3">Guru</th>
                            <th class="py-2.5 px-3">Setoran</th>
                            <th class="py-2.5 px-3">Baris</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($summary['teacher_activity'] as $activity)
                            <tr class="border-b hover:bg-slate-50 dark:border-slate-800 dark:hover:bg-slate-850/60">
                                <td class="py-2.5 px-3 font-medium">{{ $activity->teacher?->name ?? '-' }}</td>
                                <td class="py-2.5 px-3">{{ $activity->total_records }}</td>
                                <td class="py-2.5 px-3 text-indigo-700 font-semibold dark:text-indigo-400">+{{ $activity->total_lines }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-4 px-3 text-center text-slate-500">
                                    Belum ada aktivitas guru pada periode ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- At Risk Students -->
        <div class="rounded-2xl bg-white p-6 shadow-sm border border-slate-100 dark:border-slate-800 dark:bg-slate-900">
            <h3 class="mb-4 text-lg font-bold text-slate-900 dark:text-white">Santri Perlu Perhatian</h3>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm border-collapse">
                    <thead>
                        <tr class="border-b bg-slate-50 dark:border-slate-800 dark:bg-slate-850">
                            <th class="py-2.5 px-3">Santri</th>
                            <th class="py-2.5 px-3">Kelas</th>
                            <th class="py-2.5 px-3">Hutang</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($summary['at_risk_students'] as $debt)
                            <tr class="border-b hover:bg-slate-50 dark:border-slate-800 dark:hover:bg-slate-850/60">
                                <td class="py-2.5 px-3 font-semibold text-slate-900 dark:text-white">{{ $debt->student?->full_name ?? '-' }}</td>
                                <td class="py-2.5 px-3">{{ $debt->student?->classRoom?->name ?? '-' }}</td>
                                <td class="py-2.5 px-3 font-bold text-red-600 dark:text-red-400">{{ $debt->cumulative_debt_lines }} baris</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-4 px-3 text-center text-slate-500">
                                    Belum ada santri tertinggal pada periode ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
