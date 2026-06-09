@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h2 class="text-2xl font-bold">Edit Setoran Tahfizh</h2>
        <p class="text-sm text-slate-500">Perbarui data setoran hafalan.</p>
    </div>

    <form method="POST" action="{{ route('tahfizh.hafalan-records.update', $record) }}"
          class="space-y-6 rounded-2xl bg-white p-6 shadow-sm">
        @csrf
        @method('PUT')

        @include('tahfizh.hafalan-records._form', [
            'record' => $record,
            'schools' => $schools,
            'students' => $students,
            'teachers' => $teachers,
            'targets' => $targets,
            'surahs' => $surahs,
            'statuses' => $statuses,
            'defaultTeacherId' => $record->teacher_id,
        ])

        <div class="flex gap-3">
            <button type="submit"
                    class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">
                Simpan Perubahan
            </button>

            <a href="{{ route('tahfizh.hafalan-records.index') }}"
               class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                Batal
            </a>
        </div>
    </form>
@endsection
