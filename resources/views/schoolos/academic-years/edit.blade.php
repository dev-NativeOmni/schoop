@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-3xl space-y-6">
    <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Edit Tahun Ajaran</h1>

    @if($errors->any())
        <div class="rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('schoolos.academic-years.update', $academicYear) }}" method="POST" class="space-y-5 rounded-xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
        @csrf
        @method('PATCH')

        <div>
            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Nama Tahun Ajaran</label>
            <input type="text" name="name" value="{{ old('name', $academicYear->name) }}" class="mt-1 w-full rounded-lg border-slate-300 text-sm" required>
        </div>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Tanggal Mulai</label>
                <input type="date" name="start_date" value="{{ old('start_date', $academicYear->start_date?->toDateString()) }}" class="mt-1 w-full rounded-lg border-slate-300 text-sm" required>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Tanggal Selesai</label>
                <input type="date" name="end_date" value="{{ old('end_date', $academicYear->end_date?->toDateString()) }}" class="mt-1 w-full rounded-lg border-slate-300 text-sm" required>
            </div>
        </div>

        <label class="inline-flex items-center gap-2 text-sm text-slate-700 dark:text-slate-300">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $academicYear->is_active))>
            Jadikan aktif
        </label>

        <div class="flex justify-end gap-2">
            <a href="{{ route('schoolos.academic-years.index') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700">Batal</a>
            <button class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white">Simpan</button>
        </div>
    </form>
</div>
@endsection
