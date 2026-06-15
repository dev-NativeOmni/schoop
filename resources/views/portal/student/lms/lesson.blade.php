@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="border-b pb-4 flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2">
                <a href="{{ route('portal.student.lms.course.show', $lesson->course_id) }}" class="text-sm text-slate-500 hover:text-blue-600">← Kembali ke Silabus Kelas</a>
            </div>
            <h1 class="text-3xl font-extrabold tracking-tight mt-1">{{ $lesson->title }}</h1>
            <p class="text-slate-500">Bab: {{ $lesson->module->title }}</p>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        <!-- Main Panel -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Text Content -->
            @if($lesson->lesson_type === 'text')
                <div class="bg-white rounded-2xl border p-6 shadow-sm dark:bg-slate-900 dark:border-slate-800">
                    <div class="prose dark:prose-invert max-w-none text-slate-800 dark:text-slate-200">
                        {!! nl2br(e($lesson->content)) !!}
                    </div>
                </div>
            @endif

            <!-- Embed Content -->
            @if($lesson->lesson_type === 'embed')
                <div class="bg-white rounded-2xl border p-6 shadow-sm dark:bg-slate-900 dark:border-slate-800">
                    <h2 class="text-lg font-bold mb-4 font-extrabold">Media Pembelajaran</h2>
                    <div class="aspect-video w-full rounded-xl overflow-hidden bg-black flex items-center justify-center text-slate-400">
                        {!! $lesson->embed_code !!}
                    </div>
                    @if($lesson->content)
                        <p class="text-sm text-slate-500 mt-4">{{ $lesson->content }}</p>
                    @endif
                </div>
            @endif

            <!-- Assignment Submission Workspace -->
            @if($lesson->lesson_type === 'assignment')
                <div class="bg-white rounded-2xl border p-6 shadow-sm dark:bg-slate-900 dark:border-slate-800">
                    <h2 class="text-xl font-bold font-extrabold mb-2">Tugas Mandiri</h2>
                    <p class="text-slate-600 dark:text-slate-400 mb-4 whitespace-pre-line bg-slate-50 dark:bg-slate-800/40 p-4 rounded-xl">
                        {{ $lesson->assignment->instructions }}
                    </p>

                    @php
                        $sub = $lesson->assignment->submissions->first(); // Eager loaded in controller for this student
                    @endphp

                    @if($sub)
                        <div class="border rounded-xl p-4 bg-slate-50/50 dark:bg-slate-800/20 space-y-3">
                            <h3 class="font-bold">Status Pengumpulan Tugas Anda</h3>
                            <div class="text-sm space-y-1">
                                <div>Status: <span class="capitalize font-semibold px-2 py-0.5 rounded text-xs bg-blue-100 text-blue-800">{{ $sub->status }}</span></div>
                                @if($sub->score !== null)
                                    <div>Nilai Diperoleh: <strong class="text-lg text-blue-600">{{ $sub->score }}</strong> / {{ $lesson->assignment->max_score }}</div>
                                @endif
                                @if($sub->teacher_feedback)
                                    <div class="mt-2 bg-white dark:bg-slate-900 p-3 rounded-lg border dark:border-slate-800">
                                        <strong>Catatan Guru:</strong> <p class="text-slate-600 mt-1">{{ $sub->teacher_feedback }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @else
                        <!-- Form to submit -->
                        <form action="{{ route('portal.student.lms.assignment.submit', $lesson->assignment->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4 border-t pt-4">
                            @csrf
                            <div>
                                <label class="block text-sm font-medium mb-1">Teks Jawaban / Keterangan</label>
                                <textarea name="submitted_text" rows="4" class="w-full rounded-lg border border-slate-300 px-3 py-2 dark:bg-slate-800 dark:border-slate-700" placeholder="Ketik jawaban Anda di sini..."></textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Unggah File Tugas (Max 10MB)</label>
                                <input type="file" name="file" class="w-full">
                                <p class="text-xs text-slate-500 mt-1">Ekstensi yang diblokir demi keamanan: .exe, .bat, .cmd, .sh, .php, .js, .zip.</p>
                            </div>
                            <button type="submit" class="btn-primary px-6 py-2 text-white font-semibold rounded-lg">Kumpulkan Tugas</button>
                        </form>
                    @endif
                </div>
            @endif

            <!-- Quiz Workspace -->
            @if($lesson->lesson_type === 'quiz')
                <div class="bg-white rounded-2xl border p-6 shadow-sm dark:bg-slate-900 dark:border-slate-800">
                    <h2 class="text-xl font-bold font-extrabold mb-2">Kuis Pembelajaran</h2>
                    <p class="text-slate-500 mb-4">{{ $lesson->quiz->description }}</p>

                    @php
                        $attempts = $lesson->quiz->attempts; // Eager loaded for student
                        $bestAttempt = $attempts->where('status', 'completed')->sortByDesc('score')->first();
                    @endphp

                    <div class="grid gap-4 md:grid-cols-3 bg-slate-50 p-4 rounded-xl dark:bg-slate-800/50 mb-6 text-sm">
                        <div><strong>Batas Waktu:</strong> {{ $lesson->quiz->time_limit_minutes }} Menit</div>
                        <div><strong>Passing Score:</strong> {{ $lesson->quiz->passing_score }}%</div>
                        <div><strong>Kesempatan:</strong> {{ $attempts->count() }} / {{ $lesson->quiz->max_attempts > 0 ? $lesson->quiz->max_attempts : 'Tak Terbatas' }}</div>
                    </div>

                    @if($bestAttempt)
                        <div class="mb-6 border rounded-xl p-4 bg-green-50 text-green-800 dark:bg-green-950/20 dark:text-green-300">
                            <strong>Nilai Tertinggi Anda:</strong> {{ $bestAttempt->score }}% ({{ $bestAttempt->is_passed ? 'LULUS' : 'TIDAK LULUS' }})
                        </div>
                    @endif

                    @if($lesson->quiz->max_attempts == 0 || $attempts->count() < $lesson->quiz->max_attempts)
                        <form action="{{ route('portal.student.lms.quiz.start', $lesson->quiz->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-primary px-6 py-2.5 text-white font-bold rounded-lg w-full md:w-auto">
                                Mulai Kerjakan Kuis
                            </button>
                        </form>
                    @else
                        <p class="text-red-500 text-sm font-semibold">Anda telah menghabiskan seluruh kesempatan percobaan untuk kuis ini.</p>
                    @endif
                </div>
            @endif

            <!-- Progress marking button (only for reading materials: text, embed, link, file) -->
            @if(in_array($lesson->lesson_type, ['text', 'embed', 'link', 'file'], true) && (!$progress || $progress->status !== 'completed'))
                <form action="{{ route('portal.student.lms.lesson.complete', $lesson->id) }}" method="POST" class="flex justify-end mt-4">
                    @csrf
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold px-6 py-2 rounded-lg">
                        Selesai Pelajari Materi ✓
                    </button>
                </form>
            @endif
        </div>

        <!-- Sidebar Attachments -->
        <div class="space-y-6">
            <div class="bg-white rounded-2xl border p-6 shadow-sm dark:bg-slate-900 dark:border-slate-800">
                <h2 class="text-lg font-bold font-extrabold mb-4">Materi Pendukung</h2>
                <div class="divide-y dark:divide-slate-800">
                    @forelse($lesson->resources as $res)
                        <div class="py-3 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="text-xl">{{ $res->resource_type === 'file' ? '📁' : '🔗' }}</span>
                                <div>
                                    @if($res->resource_type === 'file')
                                        <a href="{{ route('lms.private-file.download', ['type' => 'resource', 'id' => $res->id]) }}" class="font-semibold hover:text-blue-600 text-sm">
                                            {{ $res->title }}
                                        </a>
                                    @else
                                        <a href="{{ $res->external_url }}" target="_blank" class="font-semibold hover:text-blue-600 text-sm">
                                            {{ $res->title }} ↗
                                        </a>
                                    @endif
                                    <span class="block text-[10px] text-slate-400 uppercase">{{ $res->resource_type }}</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-slate-400 text-xs py-2">Tidak ada materi pendukung.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
