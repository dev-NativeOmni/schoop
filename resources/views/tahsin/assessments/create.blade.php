@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Tambah Asesmen Tahsin</h1>

    @if($errors->any())
        <div class="mb-4 rounded-lg bg-red-50 px-4 py-3 text-red-700">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('tahsin.assessments.store') }}" method="POST" class="bg-white rounded-xl shadow p-6 space-y-5">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Santri</label>
                <select name="student_id" class="mt-1 w-full rounded-lg border-gray-300" required>
                    @foreach($students as $student)
                        <option value="{{ $student->id }}">
                            {{ $student->nama_lengkap ?? $student->name ?? 'Santri #' . $student->id }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Level</label>
                <select name="tahsin_level_id" class="mt-1 w-full rounded-lg border-gray-300">
                    <option value="">Tidak spesifik</option>
                    @foreach($levels as $level)
                        <option value="{{ $level->id }}">{{ $level->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Tanggal</label>
                <input type="date" name="assessment_date" value="{{ now()->toDateString() }}" class="mt-1 w-full rounded-lg border-gray-300" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Tipe</label>
                <select name="assessment_type" class="mt-1 w-full rounded-lg border-gray-300" required>
                    <option value="placement">Placement</option>
                    <option value="daily">Daily</option>
                    <option value="weekly">Weekly</option>
                    <option value="monthly">Monthly</option>
                    <option value="final">Final</option>
                </select>
            </div>
        </div>

        <div class="bg-gray-50 rounded-lg p-4">
            <h2 class="font-semibold text-gray-900 mb-3">Penilaian Skill</h2>

            <div class="space-y-3">
                @foreach($skills as $index => $skill)
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-3 bg-white rounded-lg p-3 border">
                        <input type="hidden" name="items[{{ $index }}][tahsin_skill_id]" value="{{ $skill->id }}">

                        <div class="md:col-span-2">
                            <div class="font-medium text-gray-900">{{ $skill->name }}</div>
                            <div class="text-xs text-gray-500">{{ $skill->level?->name ?? '-' }}</div>
                        </div>

                        <div>
                            <label class="block text-xs text-gray-500">Score</label>
                            <input type="number" name="items[{{ $index }}][score]" value="0" min="0" max="100" step="0.01" class="mt-1 w-full rounded-lg border-gray-300">
                        </div>

                        <div>
                            <label class="block text-xs text-gray-500">Status</label>
                            <select name="items[{{ $index }}][status]" class="mt-1 w-full rounded-lg border-gray-300">
                                <option value="mastered">Mastered</option>
                                <option value="progress">Progress</option>
                                <option value="weak">Weak</option>
                                <option value="not_tested" selected>Not Tested</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs text-gray-500">Catatan</label>
                            <input type="text" name="items[{{ $index }}][note]" class="mt-1 w-full rounded-lg border-gray-300">
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Catatan Umum</label>
            <textarea name="note" rows="3" class="mt-1 w-full rounded-lg border-gray-300"></textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Rekomendasi</label>
            <textarea name="recommendation" rows="3" class="mt-1 w-full rounded-lg border-gray-300"></textarea>
        </div>

        <div class="text-right">
            <button class="px-4 py-2 bg-blue-600 text-white rounded-lg">Simpan Asesmen</button>
        </div>
    </form>
</div>
@endsection
