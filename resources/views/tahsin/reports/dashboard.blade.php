@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Laporan Tahsin</h1>
        <p class="text-sm text-gray-600">Ringkasan progress dan asesmen tahsin santri.</p>
    </div>

    <form method="GET" action="{{ route('tahsin.reports.dashboard') }}" class="mb-6 bg-white rounded-xl shadow p-4 grid grid-cols-1 md:grid-cols-6 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700">Mulai</label>
            <input type="date" name="start_date" value="{{ $filters['start_date'] ?? $report['period']['start_date'] }}" class="mt-1 w-full rounded-lg border-gray-300">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Selesai</label>
            <input type="date" name="end_date" value="{{ $filters['end_date'] ?? $report['period']['end_date'] }}" class="mt-1 w-full rounded-lg border-gray-300">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Kelas</label>
            <select name="class_room_id" class="mt-1 w-full rounded-lg border-gray-300">
                <option value="">Semua</option>
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
                <option value="">Semua</option>
                @foreach($students as $student)
                    <option value="{{ $student->id }}" @selected(($filters['student_id'] ?? null) == $student->id)>
                        {{ $student->nama_lengkap ?? $student->name ?? 'Santri #' . $student->id }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Level</label>
            <select name="tahsin_level_id" class="mt-1 w-full rounded-lg border-gray-300">
                <option value="">Semua</option>
                @foreach($levels as $level)
                    <option value="{{ $level->id }}" @selected(($filters['tahsin_level_id'] ?? null) == $level->id)>
                        {{ $level->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="flex items-end">
            <button class="px-4 py-2 bg-blue-600 text-white rounded-lg">Filter</button>
        </div>
    </form>

    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow p-4">
            <div class="text-sm text-gray-500">Total Asesmen</div>
            <div class="text-2xl font-bold">{{ $report['summary']['total_assessments'] }}</div>
        </div>

        <div class="bg-white rounded-xl shadow p-4">
            <div class="text-sm text-gray-500">Rata-rata</div>
            <div class="text-2xl font-bold">{{ $report['summary']['average_score'] }}</div>
        </div>

        <div class="bg-white rounded-xl shadow p-4">
            <div class="text-sm text-gray-500">Excellent</div>
            <div class="text-2xl font-bold text-green-700">{{ $report['summary']['excellent'] }}</div>
        </div>

        <div class="bg-white rounded-xl shadow p-4">
            <div class="text-sm text-gray-500">Good/Fair</div>
            <div class="text-2xl font-bold text-blue-700">{{ $report['summary']['good'] + $report['summary']['fair'] }}</div>
        </div>

        <div class="bg-white rounded-xl shadow p-4">
            <div class="text-sm text-gray-500">Need Improvement</div>
            <div class="text-2xl font-bold text-red-700">{{ $report['summary']['needs_improvement'] }}</div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left">Santri</th>
                    <th class="px-4 py-3 text-left">Total Asesmen</th>
                    <th class="px-4 py-3 text-left">Rata-rata</th>
                    <th class="px-4 py-3 text-left">Asesmen Terakhir</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($report['by_student'] as $row)
                    <tr>
                        <td class="px-4 py-3">{{ $row['student']?->nama_lengkap ?? $row['student']?->name ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $row['total'] }}</td>
                        <td class="px-4 py-3">{{ $row['average_score'] }}</td>
                        <td class="px-4 py-3">
                            {{ $row['latest_assessment']?->assessment_date?->format('d M Y') ?? '-' }}
                            —
                            {{ $row['latest_assessment']?->overall_score ?? '-' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-6 text-center text-gray-500">Belum ada data.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
