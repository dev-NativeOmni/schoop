@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Tahun Ajaran</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">Kelola tahun ajaran aktif.</p>
        </div>

        <a href="{{ route('schoolos.academic-years.create') }}" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white">Tambah Tahun Ajaran</a>
    </div>

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200 text-sm dark:divide-slate-800">
            <thead class="bg-slate-50 dark:bg-slate-950">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold text-slate-600 dark:text-slate-300">Nama</th>
                    <th class="px-4 py-3 text-left font-semibold text-slate-600 dark:text-slate-300">Mulai</th>
                    <th class="px-4 py-3 text-left font-semibold text-slate-600 dark:text-slate-300">Selesai</th>
                    <th class="px-4 py-3 text-left font-semibold text-slate-600 dark:text-slate-300">Aktif</th>
                    <th class="px-4 py-3 text-right font-semibold text-slate-600 dark:text-slate-300">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                @forelse($academicYears as $academicYear)
                    <tr>
                        <td class="px-4 py-3 font-semibold text-slate-900 dark:text-slate-100">{{ $academicYear->name }}</td>
                        <td class="px-4 py-3 text-slate-600 dark:text-slate-300">{{ $academicYear->start_date?->format('d M Y') }}</td>
                        <td class="px-4 py-3 text-slate-600 dark:text-slate-300">{{ $academicYear->end_date?->format('d M Y') }}</td>
                        <td class="px-4 py-3">{{ $academicYear->is_active ? 'Ya' : 'Tidak' }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('schoolos.academic-years.edit', $academicYear) }}" class="font-semibold text-indigo-600 hover:text-indigo-800">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-slate-500">Belum ada tahun ajaran.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>

    {{ $academicYears->links() }}
</div>
@endsection
