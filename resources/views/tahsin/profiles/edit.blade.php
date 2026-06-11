@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold text-gray-900 mb-1">Edit Profil Tahsin</h1>
    <p class="text-sm text-gray-600 mb-6">{{ $student->nama_lengkap ?? $student->name ?? 'Santri #' . $student->id }}</p>

    @if($errors->any())
        <div class="mb-4 rounded-lg bg-red-50 px-4 py-3 text-red-700">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('tahsin.profiles.update', $student) }}" method="POST" class="bg-white rounded-xl shadow p-6 space-y-5">
        @csrf
        @method('PUT')

        <input type="hidden" name="student_id" value="{{ $student->id }}">

        <div>
            <label class="block text-sm font-medium text-gray-700">Level Saat Ini</label>
            <select name="current_tahsin_level_id" class="mt-1 w-full rounded-lg border-gray-300">
                <option value="">Belum ada level</option>
                @foreach($levels as $level)
                    <option value="{{ $level->id }}" @selected(old('current_tahsin_level_id', $profile?->current_tahsin_level_id) == $level->id)>
                        {{ $level->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Guru Pembimbing</label>
            <select name="assigned_teacher_id" class="mt-1 w-full rounded-lg border-gray-300">
                <option value="">Belum ditentukan</option>
                @foreach($teachers as $teacher)
                    <option value="{{ $teacher->id }}" @selected(old('assigned_teacher_id', $profile?->assigned_teacher_id) == $teacher->id)>
                        {{ $teacher->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Status</label>
            <select name="status" class="mt-1 w-full rounded-lg border-gray-300" required>
                @foreach(['not_started' => 'Belum mulai', 'in_progress' => 'Berjalan', 'passed' => 'Lulus', 'needs_attention' => 'Butuh perhatian'] as $value => $label)
                    <option value="{{ $value }}" @selected(old('status', $profile?->status ?? 'not_started') === $value)>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Placement Score</label>
                <input type="number" name="placement_score" value="{{ old('placement_score', $profile?->placement_score) }}" min="0" max="100" step="0.01" class="mt-1 w-full rounded-lg border-gray-300">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Mulai</label>
                <input type="date" name="started_at" value="{{ old('started_at', $profile?->started_at?->toDateString()) }}" class="mt-1 w-full rounded-lg border-gray-300">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Selesai</label>
                <input type="date" name="completed_at" value="{{ old('completed_at', $profile?->completed_at?->toDateString()) }}" class="mt-1 w-full rounded-lg border-gray-300">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Catatan</label>
            <textarea name="note" rows="3" class="mt-1 w-full rounded-lg border-gray-300">{{ old('note', $profile?->note) }}</textarea>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('tahsin.profiles.index') }}" class="px-4 py-2 border rounded-lg">Batal</a>
            <button class="px-4 py-2 bg-blue-600 text-white rounded-lg">Simpan</button>
        </div>
    </form>
</div>
@endsection
