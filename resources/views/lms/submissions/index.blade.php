@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between border-b pb-4">
        <div>
            <div class="flex items-center gap-2">
                <a href="{{ route('lms.lessons.show', $assignment->lesson_id) }}" class="text-sm text-slate-500 hover:text-blue-600">← Kembali ke Detail Materi</a>
            </div>
            <h1 class="text-3xl font-extrabold tracking-tight mt-1">Pengumpulan Tugas</h1>
            <p class="text-slate-500">Tugas: {{ $assignment->title }}</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border overflow-hidden shadow-sm dark:bg-slate-900 dark:border-slate-800">
        <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 dark:bg-slate-800 border-b dark:border-slate-800 text-xs font-semibold text-slate-500 uppercase">
                    <th class="px-6 py-3">Nama Santri</th>
                    <th class="px-6 py-3">Tanggal Mengumpulkan</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3">Nilai</th>
                    <th class="px-6 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y dark:divide-slate-800 text-sm">
                @forelse($submissions as $sub)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30">
                        <td class="px-6 py-4 font-semibold text-slate-800 dark:text-slate-200">
                            {{ $sub->student->full_name }}
                        </td>
                        <td class="px-6 py-4 text-slate-500">
                            {{ $sub->created_at->toDateTimeString() }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="capitalize px-2 py-0.5 rounded text-xs font-semibold {{ $sub->status === 'graded' ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800' }}">
                                {{ $sub->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 font-bold text-slate-800 dark:text-slate-200">
                            {{ $sub->score !== null ? $sub->score : '-' }}
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('lms.submissions.show', $sub->id) }}" class="text-blue-600 hover:text-blue-800 font-semibold">
                                Review & Nilai
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-slate-400">
                            Belum ada santri yang mengumpulkan tugas ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>
</div>
@endsection
