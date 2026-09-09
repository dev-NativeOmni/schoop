@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Tahsin Anak</h1>
        <p class="text-sm text-gray-600">Pantau progress tahsin anak.</p>
    </div>

    @if($students->isEmpty())
        <div class="bg-white rounded-xl shadow p-6 text-gray-600">
            Belum ada data anak yang terhubung. Hubungi admin sekolah.
        </div>
    @else
        <form method="GET" action="{{ route('portal.parent.tahsin') }}" class="mb-6 bg-white rounded-xl shadow p-4 grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Anak</label>
                <select name="student_id" class="mt-1 w-full rounded-lg border-gray-300">
                    @foreach($students as $student)
                        <option value="{{ $student->id }}" @selected($selectedStudent?->id === $student->id)>
                            {{ $student->nama_lengkap ?? $student->name ?? 'Santri #' . $student->id }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Mulai</label>
                <input type="date" name="start_date" value="{{ $snapshot['period']['start_date'] ?? now()->startOfMonth()->toDateString() }}" class="mt-1 w-full rounded-lg border-gray-300">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Selesai</label>
                <input type="date" name="end_date" value="{{ $snapshot['period']['end_date'] ?? now()->toDateString() }}" class="mt-1 w-full rounded-lg border-gray-300">
            </div>

            <div class="flex items-end">
                <button class="px-4 py-2 bg-blue-600 text-white rounded-lg">Filter</button>
            </div>
        </form>

        @if($snapshot)
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-xl shadow p-4">
                    <div class="text-sm text-gray-500">Level</div>
                    <div class="font-bold">{{ $snapshot['profile']?->currentLevel?->name ?? '-' }}</div>
                </div>

                <div class="bg-white rounded-xl shadow p-4">
                    <div class="text-sm text-gray-500">Status</div>
                    <div class="font-bold">{{ $snapshot['profile']?->status ?? 'not_started' }}</div>
                </div>

                <div class="bg-white rounded-xl shadow p-4">
                    <div class="text-sm text-gray-500">Total Asesmen</div>
                    <div class="font-bold">{{ $snapshot['summary']['total_assessments'] }}</div>
                </div>

                <div class="bg-white rounded-xl shadow p-4">
                    <div class="text-sm text-gray-500">Rata-rata</div>
                    <div class="font-bold">{{ $snapshot['summary']['average_score'] }}</div>
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
                            <th class="px-4 py-3 text-left">Rekomendasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse($snapshot['assessments'] as $assessment)
                            <tr>
                                <td class="px-4 py-3">{{ $assessment->assessment_date?->format('d M Y') }}</td>
                                <td class="px-4 py-3">{{ $assessment->assessment_type }}</td>
                                <td class="px-4 py-3">{{ $assessment->level?->name ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $assessment->overall_score }}</td>
                                <td class="px-4 py-3">{{ $assessment->grade }}</td>
                                <td class="px-4 py-3">{{ $assessment->recommendation ?? '-' }}</td>
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
        @endif
    @endif
</div>
@endsection
