@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <!-- Header -->
    <div class="border-b pb-4 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-extrabold tracking-tight">{{ $attempt->quiz->title }}</h1>
            <p class="text-slate-500">Percobaan Ke-{{ $attempt->attempt_number }} | Durasi: {{ $attempt->quiz->time_limit_minutes }} Menit</p>
        </div>
        <!-- Simple client timer display -->
        @if($attempt->quiz->time_limit_minutes > 0)
            <div id="quiz-timer" class="bg-red-50 border border-red-200 text-red-700 font-bold px-4 py-2 rounded-xl text-sm">
                Sisa Waktu: --:--
            </div>
        @endif
    </div>

    <!-- Questions Form -->
    <form id="quiz-form" action="{{ route('portal.student.lms.quiz.attempt.submit', $attempt->id) }}" method="POST" class="space-y-8">
        @csrf

        @foreach($attempt->quiz->questions as $question)
            <div class="bg-white rounded-2xl border p-6 shadow-sm dark:bg-slate-900 dark:border-slate-800 space-y-4">
                <div class="font-semibold text-slate-800 dark:text-slate-200">
                    {{ $loop->iteration }}. {!! nl2br(e($question->question_text)) !!}
                </div>

                @if($question->question_type === 'multiple_choice' && $question->options)
                    <div class="space-y-2.5">
                        @foreach($question->options as $key => $option)
                            <label class="flex items-center gap-3 p-3 rounded-lg border hover:bg-slate-50 dark:hover:bg-slate-800/20 cursor-pointer transition-colors dark:border-slate-800">
                                <input type="radio" name="answers[{{ $question->id }}]" value="{{ $key }}" class="text-blue-600 focus:ring-blue-500">
                                <span><strong class="font-bold">{{ $key }}.</strong> {{ $option }}</span>
                            </label>
                        @endforeach
                    </div>
                @elseif($question->question_type === 'true_false')
                    <div class="flex gap-4">
                        <label class="flex items-center gap-3 p-3 rounded-lg border hover:bg-slate-50 dark:hover:bg-slate-800/20 cursor-pointer transition-colors w-full dark:border-slate-800">
                            <input type="radio" name="answers[{{ $question->id }}]" value="true" class="text-blue-600 focus:ring-blue-500">
                            <span>Benar</span>
                        </label>
                        <label class="flex items-center gap-3 p-3 rounded-lg border hover:bg-slate-50 dark:hover:bg-slate-800/20 cursor-pointer transition-colors w-full dark:border-slate-800">
                            <input type="radio" name="answers[{{ $question->id }}]" value="false" class="text-blue-600 focus:ring-blue-500">
                            <span>Salah</span>
                        </label>
                    </div>
                @elseif($question->question_type === 'short_answer')
                    <div>
                        <input type="text" name="answers[{{ $question->id }}]" placeholder="Ketik jawaban singkat Anda di sini..." class="w-full rounded-lg border border-slate-300 px-3 py-2 dark:bg-slate-800 dark:border-slate-700">
                    </div>
                @endif
            </div>
        @endforeach

        <div class="flex justify-end border-t pt-4">
            <button type="submit" class="btn-primary px-8 py-3 text-white font-bold rounded-lg shadow-lg hover:shadow-xl transition-all">
                Kirim & Selesaikan Kuis
            </button>
        </div>
    </form>
</div>

@if($attempt->quiz->time_limit_minutes > 0)
    <script>
        // Simple client-side timer helper
        let limitMinutes = {{ $attempt->quiz->time_limit_minutes }};
        let startedAt = new Date('{{ $attempt->started_at->toIso8601String() }}');
        let endTime = new Date(startedAt.getTime() + limitMinutes * 60 * 1000);

        function updateTimer() {
            let now = new Date();
            let distance = endTime - now;

            if (distance < 0) {
                clearInterval(timerInterval);
                document.getElementById('quiz-timer').innerText = "WAKTU HABIS";
                document.getElementById('quiz-form').submit();
                return;
            }

            let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            let seconds = Math.floor((distance % (1000 * 60)) / 1000);

            document.getElementById('quiz-timer').innerText = "Sisa Waktu: " + 
                (minutes < 10 ? "0" : "") + minutes + ":" + 
                (seconds < 10 ? "0" : "") + seconds;
        }

        let timerInterval = setInterval(updateTimer, 1000);
        updateTimer();
    </script>
@endif
@endsection
