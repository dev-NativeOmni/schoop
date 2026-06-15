@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="border-b pb-4">
        <h1 class="text-3xl font-extrabold tracking-tight">Portal Belajar LMS</h1>
        <p class="text-slate-500">Akses semua materi, tugas, dan kuis kelas Anda.</p>
    </div>

    <!-- My Courses Grid -->
    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
        @forelse($courses as $course)
            <div class="rounded-2xl border bg-white overflow-hidden shadow-sm dark:bg-slate-900 dark:border-slate-800 flex flex-col">
                <div class="p-6 flex-1">
                    <span class="text-xs font-semibold px-2 py-0.5 rounded bg-blue-50 text-blue-700 dark:bg-blue-950/50 dark:text-blue-300 capitalize mb-2 inline-block">
                        {{ $course->type }}
                    </span>
                    <h2 class="text-xl font-bold mb-2">
                        <a href="{{ route('portal.student.lms.course.show', $course->id) }}" class="hover:text-blue-600 transition-colors">
                            {{ $course->title }}
                        </a>
                    </h2>
                    <p class="text-sm text-slate-500 line-clamp-3 mb-4">
                        {{ $course->description ?? 'Tidak ada deskripsi.' }}
                    </p>
                    
                    <!-- Progress bar -->
                    @php
                        $enroll = $course->pivot;
                        $progress = $enroll ? $enroll->progress_percentage : 0;
                        $status = $enroll ? $enroll->status : 'active';
                    @endphp
                    <div class="space-y-1">
                        <div class="flex justify-between text-xs font-medium text-slate-500">
                            <span>Progres Belajar</span>
                            <span>{{ $progress }}%</span>
                        </div>
                        <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-2">
                            <div class="bg-green-600 h-2 rounded-full" style="width: {{ $progress }}%"></div>
                        </div>
                    </div>
                </div>
                <div class="border-t bg-slate-50 px-6 py-4 dark:bg-slate-800/50 dark:border-slate-800 flex justify-between items-center">
                    <span class="text-xs font-semibold uppercase {{ $status === 'completed' ? 'text-green-600' : 'text-blue-600' }}">
                        {{ $status }}
                    </span>
                    <a href="{{ route('portal.student.lms.course.show', $course->id) }}" class="btn-primary px-4 py-1.5 text-xs text-white font-semibold rounded-lg">
                        Mulai Belajar
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12 bg-white rounded-2xl border dark:bg-slate-900 dark:border-slate-800">
                <p class="text-slate-500">Anda belum terdaftar di kelas pembelajaran apa pun.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
