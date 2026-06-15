@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="border-b pb-4">
        <div class="flex items-center gap-2">
            <a href="{{ route('lms.lessons.show', $attempt->quiz->lesson_id) }}" class="text-sm text-slate-500 hover:text-blue-600">← Kembali ke Detail Materi</a>
        </div>
        <h1 class="text-3xl font-extrabold tracking-tight mt-1">Review Percobaan Kuis</h1>
        <p class="text-slate-500">Santri: {{ $attempt->student->full_name }} | Percobaan #{{ $attempt->attempt_number }}</p>
    </div>

    <div class="bg-white rounded-2xl border p-6 shadow-sm dark:bg-slate-900 dark:border-slate-800 space-y-6">
        <!-- Summary Info -->
        <div class="grid gap-4 md:grid-cols-3 bg-slate-50 p-4 rounded-xl dark:bg-slate-800/50">
            <div>
                <span class="block text-xs text-slate-400">Skor Diperoleh</span>
                <span class="text-2xl font-extrabold">{{ $attempt->score }}</span>
            </div>
            <div>
                <span class="block text-xs text-slate-400">Status Kelulusan</span>
                <span class="capitalize px-2 py-0.5 rounded text-xs font-semibold {{ $attempt->is_passed ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $attempt->is_passed ? 'Lulus' : 'Tidak Lulus' }}
                </span>
            </div>
            <div>
                <span class="block text-xs text-slate-400">Waktu Mulai / Selesai</span>
                <span class="text-xs text-slate-600 dark:text-slate-300 block">{{ $attempt->started_at->toDateTimeString() }}</span>
                <span class="text-xs text-slate-600 dark:text-slate-300 block">{{ $attempt->completed_at ? $attempt->completed_at->toDateTimeString() : '-' }}</span>
            </div>
        </div>

        <hr class="dark:border-slate-800">

        <!-- Question review -->
        <div>
            <h2 class="text-lg font-bold font-extrabold mb-4">Daftar Pertanyaan & Jawaban</h2>
            <div class="space-y-6">
                @foreach($attempt->answers as $ans)
                    <div class="border rounded-xl p-4 dark:border-slate-800 space-y-2 bg-slate-50/20">
                        <div class="font-semibold">
                            Pertanyaan {{ $loop->iteration }}: {{ $ans->question?->question_text }}
                        </div>
                        <div class="text-sm">
                            <span class="block">Jawaban Santri: <strong class="{{ $ans->is_correct ? 'text-green-600' : 'text-red-600' }}">{{ $ans->student_answer ?? '(Tidak menjawab)' }}</strong></span>
                            <span class="block text-slate-500 text-xs">Kunci Jawaban Benar: <strong class="text-green-600">{{ $ans->question?->correct_answer }}</strong></span>
                            @if($ans->question?->explanation)
                                <p class="text-xs text-slate-400 mt-2 bg-white dark:bg-slate-900 p-2.5 rounded-lg border dark:border-slate-800">
                                    <strong>Penjelasan:</strong> {{ $ans->question->explanation }}
                                </p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
