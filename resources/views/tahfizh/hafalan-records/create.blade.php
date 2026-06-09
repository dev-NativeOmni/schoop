@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h2 class="text-2xl font-bold">Tambah Setoran Tahfizh</h2>
        <p class="text-sm text-slate-500">Input setoran hafalan santri.</p>
    </div>

    <form method="POST" action="{{ route('tahfizh.hafalan-records.store') }}"
          class="space-y-6 rounded-2xl bg-white p-6 shadow-sm">
        @csrf

        @include('tahfizh.hafalan-records._form', [
            'record' => null,
            'schools' => $schools,
            'students' => $students,
            'teachers' => $teachers,
            'targets' => $targets,
            'surahs' => $surahs,
            'statuses' => $statuses,
            'defaultTeacherId' => $defaultTeacherId,
        ])

        <div class="flex gap-3">
            <button type="submit"
                    class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">
                Simpan
            </button>

            <a href="{{ route('tahfizh.hafalan-records.index') }}"
               class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                Batal
            </a>
        </div>
    </form>
@endsection
