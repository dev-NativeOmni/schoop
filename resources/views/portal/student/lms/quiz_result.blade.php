@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto space-y-6">
    <div class="bg-white rounded-2xl border p-8 shadow-lg dark:bg-slate-900 dark:border-slate-800 text-center space-y-6">
        <div class="text-6xl">
            @if($attempt->is_passed) 🎉 @else 😭 @endif
        </div>
        
        <div>
            <h1 class="text-3xl font-extrabold tracking-tight">Kuis Selesai Dikerjakan!</h1>
            <p class="text-slate-500 mt-1">Kuis: {{ $attempt->quiz->title }}</p>
        </div>

        <div class="inline-block p-6 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border dark:border-slate-800">
            <span class="block text-sm text-slate-400 uppercase font-medium">Skor Anda</span>
            <span class="text-5xl font-black block mt-2 text-blue-600">{{ $attempt->score }}%</span>
            <span class="block text-xs text-slate-500 mt-2">Nilai Kelulusan: {{ $attempt->quiz->passing_score }}%</span>
        </div>

        <div class="p-4 rounded-xl text-sm font-semibold {{ $attempt->is_passed ? 'bg-green-50 text-green-800' : 'bg-red-50 text-red-800' }}">
            Status Kelulusan: {{ $attempt->is_passed ? 'LULUS' : 'TIDAK LULUS' }}
        </div>

        <div class="flex flex-col gap-2">
            <a href="{{ route('portal.student.lms.lesson.show', $attempt->quiz->lesson_id) }}" class="btn-primary w-full py-2.5 text-white font-bold rounded-lg block">
                Kembali ke Materi
            </a>
            <a href="{{ route('portal.student.lms.course.show', $attempt->quiz->course_id) }}" class="bg-slate-200 text-slate-700 w-full py-2.5 font-bold rounded-lg block hover:bg-slate-300">
                Lihat Silabus Kelas
            </a>
        </div>
    </div>
</div>
@endsection
