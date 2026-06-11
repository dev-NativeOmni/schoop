@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Asesmen Tahsin</h1>
            <p class="text-sm text-gray-600">Riwayat penilaian bacaan santri.</p>
        </div>

        <a href="{{ route('tahsin.assessments.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg">
            Tambah Asesmen
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-lg bg-green-50 px-4 py-3 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left">Tanggal</th>
                    <th class="px-4 py-3 text-left">Santri</th>
                    <th class="px-4 py-3 text-left">Guru</th>
                    <th class="px-4 py-3 text-left">Level</th>
                    <th class="px-4 py-3 text-left">Score</th>
                    <th class="px-4 py-3 text-left">Grade</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($assessments as $assessment)
                    <tr>
                        <td class="px-4 py-3">{{ $assessment->assessment_date?->format('d M Y') }}</td>
                        <td class="px-4 py-3">{{ $assessment->student?->nama_lengkap ?? $assessment->student?->name ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $assessment->teacher?->name ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $assessment->level?->name ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $assessment->overall_score }}</td>
                        <td class="px-4 py-3">{{ $assessment->grade }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('tahsin.assessments.show', $assessment) }}" class="text-blue-600">Detail</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-6 text-center text-gray-500">Belum ada asesmen.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $assessments->links() }}</div>
</div>
@endsection
