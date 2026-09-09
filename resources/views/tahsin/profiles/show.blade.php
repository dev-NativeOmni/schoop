@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">
            {{ $student->nama_lengkap ?? $student->name ?? 'Santri #' . $student->id }}
        </h1>
        <p class="text-sm text-gray-600">Detail profil dan riwayat asesmen tahsin.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow p-4">
            <div class="text-sm text-gray-500">Kelas</div>
            <div class="font-bold">{{ $student->classRoom?->name ?? '-' }}</div>
        </div>
        <div class="bg-white rounded-xl shadow p-4">
            <div class="text-sm text-gray-500">Level</div>
            <div class="font-bold">{{ $student->tahsinProfile?->currentLevel?->name ?? '-' }}</div>
        </div>
        <div class="bg-white rounded-xl shadow p-4">
            <div class="text-sm text-gray-500">Status</div>
            <div class="font-bold">{{ $student->tahsinProfile?->status ?? 'not_started' }}</div>
        </div>
        <div class="bg-white rounded-xl shadow p-4">
            <div class="text-sm text-gray-500">Guru</div>
            <div class="font-bold">{{ $student->tahsinProfile?->assignedTeacher?->name ?? '-' }}</div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left">Tanggal</th>
                    <th class="px-4 py-3 text-left">Tipe</th>
                    <th class="px-4 py-3 text-left">Level</th>
                    <th class="px-4 py-3 text-left">Score</th>
                    <th class="px-4 py-3 text-left">Grade</th>
                    <th class="px-4 py-3 text-left">Guru</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($student->tahsinAssessments as $assessment)
                    <tr>
                        <td class="px-4 py-3">{{ $assessment->assessment_date?->format('d M Y') }}</td>
                        <td class="px-4 py-3">{{ $assessment->assessment_type }}</td>
                        <td class="px-4 py-3">{{ $assessment->level?->name ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $assessment->overall_score }}</td>
                        <td class="px-4 py-3">{{ $assessment->grade }}</td>
                        <td class="px-4 py-3">{{ $assessment->teacher?->name ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-gray-500">
                            Belum ada asesmen tahsin.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>
</div>
@endsection
