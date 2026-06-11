@extends('layouts.app')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Laporan Presensi</h1>
    <p class="text-sm text-gray-600">Ringkasan presensi santri.</p>
</div>

<form method="GET" action="{{ route('attendance.reports.dashboard') }}" class="mb-6 grid grid-cols-1 gap-4 rounded-xl bg-white p-4 shadow md:grid-cols-5">
    <div>
        <label class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
        <input type="date"
               name="start_date"
               value="{{ $filters['start_date'] ?? $report['period']['start_date'] }}"
               class="mt-1 w-full rounded-lg border-gray-300">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Tanggal Selesai</label>
        <input type="date"
               name="end_date"
               value="{{ $filters['end_date'] ?? $report['period']['end_date'] }}"
               class="mt-1 w-full rounded-lg border-gray-300">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Kelas</label>
        <select name="class_room_id" class="mt-1 w-full rounded-lg border-gray-300">
            <option value="">Semua kelas</option>
            @foreach($classRooms as $classRoom)
                <option value="{{ $classRoom->id }}" @selected(($filters['class_room_id'] ?? null) == $classRoom->id)>
                    {{ $classRoom->name ?? $classRoom->nama ?? 'Kelas #' . $classRoom->id }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Santri</label>
        <select name="student_id" class="mt-1 w-full rounded-lg border-gray-300">
            <option value="">Semua santri</option>
            @foreach($students as $student)
                <option value="{{ $student->id }}" @selected(($filters['student_id'] ?? null) == $student->id)>
                    {{ $student->full_name ?? $student->nama_lengkap ?? $student->name ?? 'Santri #' . $student->id }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="flex items-end">
        <button class="rounded-lg bg-blue-600 px-4 py-2 text-white">
            Filter
        </button>
    </div>
</form>

<div class="mb-6 grid grid-cols-2 gap-4 md:grid-cols-3 lg:grid-cols-6">
    <div class="rounded-xl bg-white p-4 shadow">
        <div class="text-sm text-gray-500">Total</div>
        <div class="text-2xl font-bold">{{ $report['summary']['total_records'] }}</div>
    </div>

    <div class="rounded-xl bg-white p-4 shadow">
        <div class="text-sm text-gray-500">Hadir</div>
        <div class="text-2xl font-bold text-green-700">{{ $report['summary']['present'] }}</div>
    </div>

    <div class="rounded-xl bg-white p-4 shadow">
        <div class="text-sm text-gray-500">Terlambat</div>
        <div class="text-2xl font-bold text-amber-700">{{ $report['summary']['late'] }}</div>
    </div>

    <div class="rounded-xl bg-white p-4 shadow">
        <div class="text-sm text-gray-500">Sakit</div>
        <div class="text-2xl font-bold text-blue-700">{{ $report['summary']['sick'] }}</div>
    </div>

    <div class="rounded-xl bg-white p-4 shadow">
        <div class="text-sm text-gray-500">Izin</div>
        <div class="text-2xl font-bold text-purple-700">{{ $report['summary']['permission'] }}</div>
    </div>

    <div class="rounded-xl bg-white p-4 shadow">
        <div class="text-sm text-gray-500">Alpa</div>
        <div class="text-2xl font-bold text-red-700">{{ $report['summary']['absent'] }}</div>
    </div>
</div>

<div class="overflow-hidden rounded-xl bg-white shadow">
    <table class="min-w-full text-sm">
        <thead class="bg-gray-50 text-gray-700">
            <tr>
                <th class="px-4 py-3 text-left">Santri</th>
                <th class="px-4 py-3 text-right">Total</th>
                <th class="px-4 py-3 text-right">Hadir</th>
                <th class="px-4 py-3 text-right">Telat</th>
                <th class="px-4 py-3 text-right">Sakit</th>
                <th class="px-4 py-3 text-right">Izin</th>
                <th class="px-4 py-3 text-right">Alpa</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($report['by_student'] as $row)
                <tr>
                    <td class="px-4 py-3">
                        {{ $row['student']?->full_name ?? $row['student']?->nama_lengkap ?? $row['student']?->name ?? '-' }}
                    </td>
                    <td class="px-4 py-3 text-right">{{ $row['total'] }}</td>
                    <td class="px-4 py-3 text-right">{{ $row['present'] }}</td>
                    <td class="px-4 py-3 text-right">{{ $row['late'] }}</td>
                    <td class="px-4 py-3 text-right">{{ $row['sick'] }}</td>
                    <td class="px-4 py-3 text-right">{{ $row['permission'] }}</td>
                    <td class="px-4 py-3 text-right">{{ $row['absent'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-4 py-6 text-center text-gray-500">
                        Belum ada data presensi.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
