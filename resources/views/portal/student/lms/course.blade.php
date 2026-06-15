@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="border-b pb-4 flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2">
                <a href="{{ route('portal.student.lms.index') }}" class="text-sm text-slate-500 hover:text-blue-600">← Kembali ke Kelas Saya</a>
            </div>
            <h1 class="text-3xl font-extrabold tracking-tight mt-1">{{ $course->title }}</h1>
            <p class="text-slate-500">{{ $course->description }}</p>
        </div>
    </div>

    <!-- Syllabus Modules List -->
    <div class="max-w-4xl mx-auto space-y-6">
        @if($course->modules->isEmpty())
            <div class="bg-white rounded-2xl border p-6 text-center text-slate-500">
                Belum ada kurikulum yang ditambahkan untuk kelas ini.
            </div>
        @else
            @foreach($course->modules as $module)
                <div class="bg-white rounded-2xl border overflow-hidden shadow-sm dark:bg-slate-900 dark:border-slate-800">
                    <div class="bg-slate-50 px-6 py-4 border-b dark:bg-slate-800/50 dark:border-slate-800">
                        <h2 class="text-lg font-bold text-slate-800 dark:text-slate-200">{{ $module->title }}</h2>
                        @if($module->description)
                            <p class="text-xs text-slate-500 mt-1">{{ $module->description }}</p>
                        @endif
                    </div>
                    <div class="divide-y dark:divide-slate-800">
                        @forelse($module->lessons as $lesson)
                            @php
                                $isCompleted = in_array($lesson->id, $completedLessonIds, true);
                            @endphp
                            <div class="px-6 py-4 flex items-center justify-between hover:bg-slate-50/50 dark:hover:bg-slate-800/20">
                                <div class="flex items-center gap-3">
                                    <span class="text-2xl">
                                        @if($lesson->lesson_type === 'text') 📝
                                        @elseif($lesson->lesson_type === 'file') 📁
                                        @elseif($lesson->lesson_type === 'link') 🔗
                                        @elseif($lesson->lesson_type === 'embed') 🎥
                                        @elseif($lesson->lesson_type === 'quiz') ❓
                                        @elseif($lesson->lesson_type === 'assignment') 📥
                                        @endif
                                    </span>
                                    <div>
                                        <a href="{{ route('portal.student.lms.lesson.show', $lesson->id) }}" class="font-bold text-slate-800 hover:text-blue-600 dark:text-slate-200">
                                            {{ $lesson->title }}
                                        </a>
                                        <div class="flex items-center gap-2 mt-0.5 text-xs text-slate-500">
                                            <span class="capitalize">{{ $lesson->lesson_type }}</span>
                                            <span>•</span>
                                            <span>{{ $lesson->estimated_minutes }} Menit</span>
                                            @if($lesson->is_required)
                                                <span>•</span>
                                                <span class="text-red-500 text-[10px] uppercase font-bold">Wajib</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    @if($isCompleted)
                                        <span class="bg-green-100 text-green-800 dark:bg-green-950/50 dark:text-green-300 text-xs font-bold px-2.5 py-1 rounded-full">
                                            ✓ Selesai
                                        </span>
                                    @else
                                        <a href="{{ route('portal.student.lms.lesson.show', $lesson->id) }}" class="text-xs font-bold text-blue-600 hover:underline">
                                            Pelajari →
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="p-4 text-xs text-slate-400 text-center">Belum ada materi pembelajaran dalam bab ini.</p>
                        @endforelse
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>
@endsection
