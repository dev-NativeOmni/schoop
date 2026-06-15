@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="border-b pb-4 flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2">
                <a href="{{ route('portal.parent.lms.index') }}" class="text-sm text-slate-500 hover:text-blue-600">← Kembali ke Daftar Santri</a>
            </div>
            <h1 class="text-3xl font-extrabold tracking-tight mt-1">Detail Progres: {{ $student->full_name }}</h1>
            <p class="text-slate-500">Kelas: {{ $course->title }}</p>
        </div>
    </div>

    <!-- Summary Metrics -->
    @if(!empty($summary))
        <div class="grid gap-6 md:grid-cols-4">
            <div class="bg-white border rounded-2xl p-6 dark:bg-slate-900 dark:border-slate-800 shadow-sm">
                <span class="block text-xs font-semibold text-slate-400 uppercase">Progres Keseluruhan</span>
                <span class="text-3xl font-extrabold block mt-2 text-blue-600">{{ $summary['progress_percentage'] }}%</span>
                <span class="text-xs text-slate-500 mt-1 capitalize">Status: {{ $summary['status'] }}</span>
            </div>
            <div class="bg-white border rounded-2xl p-6 dark:bg-slate-900 dark:border-slate-800 shadow-sm">
                <span class="block text-xs font-semibold text-slate-400 uppercase">Materi Selesai</span>
                <span class="text-3xl font-extrabold block mt-2">{{ $summary['completed_lessons'] }} / {{ $summary['total_lessons'] }}</span>
            </div>
            <div class="bg-white border rounded-2xl p-6 dark:bg-slate-900 dark:border-slate-800 shadow-sm">
                <span class="block text-xs font-semibold text-slate-400 uppercase">Rata-rata Nilai Tugas</span>
                <span class="text-3xl font-extrabold block mt-2 text-green-600">{{ number_format($summary['assignments_average_score'], 1) }}</span>
                <span class="text-xs text-slate-500 mt-1">Mengumpulkan {{ $summary['assignments_submitted'] }} tugas</span>
            </div>
            <div class="bg-white border rounded-2xl p-6 dark:bg-slate-900 dark:border-slate-800 shadow-sm">
                <span class="block text-xs font-semibold text-slate-400 uppercase">Rata-rata Nilai Kuis</span>
                <span class="text-3xl font-extrabold block mt-2 text-indigo-600">{{ number_format($summary['quizzes_average_score'], 1) }}%</span>
                <span class="text-xs text-slate-500 mt-1">Mengerjakan {{ $summary['quizzes_attempted'] }} kuis</span>
            </div>
        </div>
    @endif

    <!-- Syllabus Read-Only View -->
    <div class="max-w-4xl mx-auto space-y-6">
        @foreach($course->modules as $module)
            <div class="bg-white rounded-2xl border overflow-hidden shadow-sm dark:bg-slate-900 dark:border-slate-800">
                <div class="bg-slate-50 px-6 py-4 border-b dark:bg-slate-800/50 dark:border-slate-800">
                    <h2 class="text-lg font-bold text-slate-800 dark:text-slate-200">{{ $module->title }}</h2>
                </div>
                <div class="divide-y dark:divide-slate-800">
                    @forelse($module->lessons as $lesson)
                        @php
                            $isCompleted = in_array($lesson->id, $completedLessonIds, true);
                        @endphp
                        <div class="px-6 py-4 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <span class="text-xl">
                                    @if($lesson->lesson_type === 'text') 📝
                                    @elseif($lesson->lesson_type === 'file') 📁
                                    @elseif($lesson->lesson_type === 'link') 🔗
                                    @elseif($lesson->lesson_type === 'embed') 🎥
                                    @elseif($lesson->lesson_type === 'quiz') ❓
                                    @elseif($lesson->lesson_type === 'assignment') 📥
                                    @endif
                                </span>
                                <div>
                                    <span class="font-bold text-slate-800 dark:text-slate-200">{{ $lesson->title }}</span>
                                    <div class="flex items-center gap-2 mt-0.5 text-xs text-slate-500">
                                        <span class="capitalize">{{ $lesson->lesson_type }}</span>
                                        <span>•</span>
                                        <span>{{ $lesson->estimated_minutes }} Menit</span>
                                    </div>
                                </div>
                            </div>
                            <div>
                                @if($isCompleted)
                                    <span class="bg-green-100 text-green-800 dark:bg-green-950/50 dark:text-green-300 text-xs font-bold px-2.5 py-1 rounded-full">
                                        ✓ Selesai
                                    </span>
                                @else
                                    <span class="bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400 text-xs font-semibold px-2.5 py-1 rounded-full">
                                        Belum Selesai
                                    </span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="p-4 text-xs text-slate-400 text-center">Belum ada materi pembelajaran.</p>
                    @endforelse
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
