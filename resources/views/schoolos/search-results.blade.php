@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Hasil Pencarian</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">Keyword: {{ $keyword }}</p>
        </div>

        <form method="GET" action="{{ route('schoolos.search') }}" class="flex gap-2">
            <input type="text" name="q" value="{{ $keyword }}" minlength="2" class="w-64 rounded-lg border-slate-300 text-sm" required>
            <button class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white">Cari</button>
        </form>
    </div>

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200 text-sm dark:divide-slate-800">
            <thead class="bg-slate-50 dark:bg-slate-950">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold text-slate-600 dark:text-slate-300">Santri</th>
                    <th class="px-4 py-3 text-left font-semibold text-slate-600 dark:text-slate-300">Kelas</th>
                    <th class="px-4 py-3 text-left font-semibold text-slate-600 dark:text-slate-300">NISN</th>
                    <th class="px-4 py-3 text-right font-semibold text-slate-600 dark:text-slate-300">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                @forelse($results['students'] as $student)
                    <tr>
                        <td class="px-4 py-3">
                            <div class="font-semibold text-slate-900 dark:text-slate-100">{{ $student->full_name }}</div>
                            <div class="text-xs text-slate-500">{{ $student->student_number ?? '-' }}</div>
                        </td>
                        <td class="px-4 py-3 text-slate-600 dark:text-slate-300">{{ $student->classRoom?->name ?? '-' }}</td>
                        <td class="px-4 py-3 text-slate-600 dark:text-slate-300">{{ $student->nisn ?? '-' }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('schoolos.students.show', $student) }}" class="font-semibold text-indigo-600 hover:text-indigo-800">Student 360</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-8 text-center text-slate-500">Tidak ada santri yang cocok.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>
</div>
@endsection
