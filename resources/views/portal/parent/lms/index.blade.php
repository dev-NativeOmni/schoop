@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="border-b pb-4">
        <h1 class="text-3xl font-extrabold tracking-tight">Monitoring Pembelajaran Santri</h1>
        <p class="text-slate-500">Pantau aktivitas, progres belajar, dan nilai kuis/tugas anak Anda.</p>
    </div>

    <!-- Children Cards List -->
    <div class="space-y-8 max-w-4xl mx-auto">
        @forelse($children as $child)
            <div class="bg-white rounded-2xl border p-6 shadow-sm dark:bg-slate-900 dark:border-slate-800 space-y-4">
                <div class="flex items-center gap-3 border-b pb-3">
                    <span class="text-3xl">👦</span>
                    <div>
                        <h2 class="text-xl font-bold text-slate-800 dark:text-slate-200">{{ $child->full_name }}</h2>
                        <p class="text-xs text-slate-500">NIS: {{ $child->student_number ?? '-' }}</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <h3 class="font-bold text-sm text-slate-600 uppercase tracking-wider">Daftar Kelas Pembelajaran</h3>
                    <div class="grid gap-4 md:grid-cols-2">
                        @forelse($child->courses as $course)
                            @php
                                $progress = $course->pivot ? $course->pivot->progress_percentage : 0;
                            @endphp
                            <div class="border rounded-xl p-4 dark:border-slate-800 flex flex-col justify-between">
                                <div>
                                    <h4 class="font-bold text-slate-800 dark:text-slate-200">{{ $course->title }}</h4>
                                    <div class="flex items-center gap-3 mt-3">
                                        <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-2">
                                            <div class="bg-green-500 h-2 rounded-full" style="width: {{ $progress }}%"></div>
                                        </div>
                                        <span class="text-xs font-bold">{{ $progress }}%</span>
                                    </div>
                                </div>
                                <div class="mt-4 border-t pt-2.5 flex justify-end">
                                    <a href="{{ route('portal.parent.lms.child.course.show', ['student' => $child->id, 'course' => $course->id]) }}" class="text-xs font-semibold text-blue-600 hover:text-blue-850">
                                        Lihat Progres Detail →
                                    </a>
                                </div>
                            </div>
                        @empty
                            <p class="text-slate-400 text-xs py-2 col-span-full">Santri belum terdaftar di kelas mana pun.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-12 bg-white border rounded-2xl dark:bg-slate-900 dark:border-slate-800">
                <p class="text-slate-500">Tidak ada santri terhubung dengan akun Anda.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
