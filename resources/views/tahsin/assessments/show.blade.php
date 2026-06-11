@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Detail Asesmen Tahsin</h1>
        <p class="text-sm text-gray-600">
            {{ $assessment->student?->nama_lengkap ?? $assessment->student?->name ?? '-' }} ·
            {{ $assessment->assessment_date?->format('d M Y') }}
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow p-4">
            <div class="text-sm text-gray-500">Score</div>
            <div class="text-2xl font-bold">{{ $assessment->overall_score }}</div>
        </div>
        <div class="bg-white rounded-xl shadow p-4">
            <div class="text-sm text-gray-500">Grade</div>
            <div class="text-2xl font-bold">{{ $assessment->grade }}</div>
        </div>
        <div class="bg-white rounded-xl shadow p-4">
            <div class="text-sm text-gray-500">Level</div>
            <div class="font-bold">{{ $assessment->level?->name ?? '-' }}</div>
        </div>
        <div class="bg-white rounded-xl shadow p-4">
            <div class="text-sm text-gray-500">Guru</div>
            <div class="font-bold">{{ $assessment->teacher?->name ?? '-' }}</div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow overflow-hidden mb-6">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left">Skill</th>
                    <th class="px-4 py-3 text-left">Score</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-left">Catatan</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($assessment->items as $item)
                    <tr>
                        <td class="px-4 py-3">{{ $item->skill?->name ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $item->score }}</td>
                        <td class="px-4 py-3">{{ $item->status }}</td>
                        <td class="px-4 py-3">{{ $item->note ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-white rounded-xl shadow p-4">
            <div class="font-semibold mb-2">Catatan</div>
            <div class="text-sm text-gray-700 whitespace-pre-line">{{ $assessment->note ?? '-' }}</div>
        </div>

        <div class="bg-white rounded-xl shadow p-4">
            <div class="font-semibold mb-2">Rekomendasi</div>
            <div class="text-sm text-gray-700 whitespace-pre-line">{{ $assessment->recommendation ?? '-' }}</div>
        </div>
    </div>
</div>
@endsection
