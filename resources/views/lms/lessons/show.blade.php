@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col border-b pb-4 gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="flex items-center gap-2">
                <a href="{{ route('lms.courses.show', $lesson->course_id) }}" class="text-sm text-slate-500 hover:text-blue-600">← Kembali ke Silabus Kelas</a>
            </div>
            <h1 class="text-3xl font-extrabold tracking-tight mt-1">{{ $lesson->title }}</h1>
            <p class="text-slate-500">Tipe: <span class="capitalize font-semibold">{{ $lesson->lesson_type }}</span> | Bab: {{ $lesson->module->title }}</p>
        </div>
        <div class="flex gap-2">
            <!-- Trigger editing -->
            <button type="button" onclick="document.getElementById('edit-lesson-modal').classList.remove('hidden');" class="bg-slate-700 hover:bg-slate-800 text-white px-4 py-2 font-medium rounded-lg">Edit Detail Materi</button>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        <!-- Content & Configuration -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Text Content (if text/embed/link) -->
            @if($lesson->lesson_type === 'text')
                <div class="bg-white rounded-2xl border p-6 shadow-sm dark:bg-slate-900 dark:border-slate-800">
                    <h2 class="text-lg font-bold mb-4 font-extrabold">Isi Bacaan Materi</h2>
                    <div class="prose dark:prose-invert max-w-none text-slate-800 dark:text-slate-200">
                        {!! nl2br(e($lesson->content)) !!}
                    </div>
                </div>
            @endif

            <!-- Assignment Configuration -->
            @if($lesson->lesson_type === 'assignment')
                <div class="bg-white rounded-2xl border p-6 shadow-sm dark:bg-slate-900 dark:border-slate-800">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-bold font-extrabold">Konfigurasi Tugas</h2>
                        @if($lesson->assignment)
                            <a href="{{ route('lms.submissions.index', ['assignment_id' => $lesson->assignment->id]) }}" class="text-sm font-semibold text-blue-600 hover:underline">Lihat Hasil Pengumpulan Tugas</a>
                        @endif
                    </div>
                    
                    @if(!$lesson->assignment)
                        <div class="bg-blue-50 border border-blue-200 text-blue-800 rounded-xl p-4 mb-4 dark:bg-blue-950/30 dark:border-blue-900/50">
                            Konfigurasikan detail petunjuk pengerjaan tugas, batas waktu, dan kriteria penilaian.
                        </div>
                        <form action="{{ route('lms.assignments.store') }}" method="POST" class="space-y-4">
                            @csrf
                            <input type="hidden" name="course_id" value="{{ $lesson->course_id }}">
                            <input type="hidden" name="lesson_id" value="{{ $lesson->id }}">
                            <input type="hidden" name="title" value="Tugas: {{ $lesson->title }}">
                            
                            <div>
                                <label class="block text-sm font-medium mb-1">Instruksi Pengerjaan <span class="text-red-500">*</span></label>
                                <textarea name="instructions" required rows="4" class="w-full rounded-lg border border-slate-300 px-3 py-2 dark:bg-slate-800 dark:border-slate-700" placeholder="Jelaskan detail instruksi pengerjaan tugas..."></textarea>
                            </div>
                            <div class="grid gap-4 md:grid-cols-3">
                                <div>
                                    <label class="block text-sm font-medium mb-1">Nilai Maksimum</label>
                                    <input type="number" name="max_score" value="100" class="w-full rounded-lg border border-slate-300 px-3 py-2 dark:bg-slate-800 dark:border-slate-700">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">Nilai Kelulusan</label>
                                    <input type="number" name="passing_score" value="70" class="w-full rounded-lg border border-slate-300 px-3 py-2 dark:bg-slate-800 dark:border-slate-700">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">Batas Waktu (Due Date)</label>
                                    <input type="datetime-local" name="due_date" class="w-full rounded-lg border border-slate-300 px-3 py-2 dark:bg-slate-800 dark:border-slate-700">
                                </div>
                            </div>
                            <div class="grid gap-4 md:grid-cols-2">
                                <div>
                                    <label class="block text-sm font-medium mb-1">Format File yang Diizinkan</label>
                                    <input type="text" name="allowed_file_types" value="pdf,doc,docx,jpg,png" placeholder="Misal: pdf,doc,jpg" class="w-full rounded-lg border border-slate-300 px-3 py-2 dark:bg-slate-800 dark:border-slate-700">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">Ukuran Maksimum (KB)</label>
                                    <input type="number" name="max_file_size_kb" value="10240" class="w-full rounded-lg border border-slate-300 px-3 py-2 dark:bg-slate-800 dark:border-slate-700">
                                </div>
                            </div>
                            <button type="submit" class="btn-primary px-6 py-2 text-white font-semibold rounded-lg">Simpan Konfigurasi</button>
                        </form>
                    @else
                        <!-- Show current config -->
                        <div class="space-y-4">
                            <div class="grid gap-4 md:grid-cols-2">
                                <div><strong>Instruksi:</strong> <p class="text-slate-600 dark:text-slate-400 whitespace-pre-line mt-1">{{ $lesson->assignment->instructions }}</p></div>
                                <div class="space-y-2">
                                    <div><strong>Nilai Maks/Lulus:</strong> {{ $lesson->assignment->max_score }} / {{ $lesson->assignment->passing_score }}</div>
                                    <div><strong>Batas Waktu:</strong> {{ $lesson->assignment->due_date ? $lesson->assignment->due_date->toDateTimeString() : 'Tanpa batas waktu' }}</div>
                                    <div><strong>Jenis Berkas:</strong> {{ $lesson->assignment->allowed_file_types }} (Max {{ number_format($lesson->assignment->max_file_size_kb / 1024, 1) }} MB)</div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Quiz Configuration -->
            @if($lesson->lesson_type === 'quiz')
                <div class="bg-white rounded-2xl border p-6 shadow-sm dark:bg-slate-900 dark:border-slate-800">
                    <h2 class="text-lg font-bold mb-4 font-extrabold">Konfigurasi Kuis</h2>
                    
                    @if(!$lesson->quiz)
                        <form action="{{ route('lms.quizzes.store') }}" method="POST" class="space-y-4">
                            @csrf
                            <input type="hidden" name="course_id" value="{{ $lesson->course_id }}">
                            <input type="hidden" name="lesson_id" value="{{ $lesson->id }}">
                            <input type="hidden" name="title" value="Kuis: {{ $lesson->title }}">
                            
                            <div>
                                <label class="block text-sm font-medium mb-1">Deskripsi/Instruksi Kuis</label>
                                <textarea name="description" rows="3" class="w-full rounded-lg border border-slate-300 px-3 py-2 dark:bg-slate-800 dark:border-slate-700"></textarea>
                            </div>
                            <div class="grid gap-4 md:grid-cols-3">
                                <div>
                                    <label class="block text-sm font-medium mb-1">Batas Waktu Pengerjaan (Menit)</label>
                                    <input type="number" name="time_limit_minutes" value="15" class="w-full rounded-lg border border-slate-300 px-3 py-2 dark:bg-slate-800 dark:border-slate-700">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">Maksimum Percobaan</label>
                                    <input type="number" name="max_attempts" value="1" class="w-full rounded-lg border border-slate-300 px-3 py-2 dark:bg-slate-800 dark:border-slate-700">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">Nilai Kelulusan (%)</label>
                                    <input type="number" name="passing_score" value="70" class="w-full rounded-lg border border-slate-300 px-3 py-2 dark:bg-slate-800 dark:border-slate-700">
                                </div>
                            </div>
                            <button type="submit" class="btn-primary px-6 py-2 text-white font-semibold rounded-lg">Aktifkan Kuis</button>
                        </form>
                    @else
                        <!-- Manage Quiz Questions -->
                        <div class="space-y-6">
                            <div class="flex items-center justify-between border-b pb-2">
                                <h3 class="font-bold text-md text-slate-800 dark:text-slate-200">Daftar Pertanyaan Kuis</h3>
                                <button type="button" onclick="document.getElementById('add-question-modal').classList.remove('hidden');" class="text-xs font-semibold bg-blue-50 text-blue-700 hover:bg-blue-100 px-3 py-1.5 rounded-lg dark:bg-blue-950/50 dark:text-blue-300">
                                    + Tambah Pertanyaan
                                </button>
                            </div>
                            
                            <div class="space-y-4">
                                @forelse($lesson->quiz->questions as $question)
                                    <div class="border rounded-xl p-4 dark:border-slate-800 space-y-2 bg-slate-50/50 dark:bg-slate-800/20">
                                        <div class="flex justify-between items-start gap-4">
                                            <div class="font-semibold text-slate-800 dark:text-slate-200">
                                                No. {{ $loop->iteration }}: {{ $question->question_text }}
                                            </div>
                                            <form action="{{ route('lms.quizzes.questions.destroy', [$lesson->quiz->id, $question->id]) }}" method="POST" onsubmit="return confirm('Hapus pertanyaan ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-xs text-red-500 font-medium">Hapus</button>
                                            </form>
                                        </div>
                                        <div class="text-xs text-slate-500 space-y-1">
                                            <div>Tipe: <span class="capitalize">{{ $question->question_type }}</span> | Bobot Skor: {{ $question->score_weight }}</div>
                                            @if($question->options)
                                                <div class="grid grid-cols-2 gap-2 mt-1">
                                                    @foreach($question->options as $key => $opt)
                                                        <div class="{{ $question->correct_answer === $key ? 'text-green-600 font-bold' : '' }}">
                                                            {{ $key }}. {{ $opt }}
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <div class="mt-1">Jawaban Benar: <strong class="text-green-600">{{ $question->correct_answer }}</strong></div>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-slate-500 text-center py-4">Belum ada pertanyaan dibuat.</p>
                                @endforelse
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <!-- Sidebar Resource Attachments -->
        <div class="space-y-6">
            <div class="bg-white rounded-2xl border p-6 shadow-sm dark:bg-slate-900 dark:border-slate-800">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-bold font-extrabold">File Lampiran / Link</h2>
                    <button type="button" onclick="document.getElementById('add-resource-modal').classList.remove('hidden');" class="text-xs font-semibold text-blue-600 hover:underline">
                        + Tambah
                    </button>
                </div>

                <div class="divide-y dark:divide-slate-800">
                    @forelse($lesson->resources as $res)
                        <div class="py-2.5 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="text-md">{{ $res->resource_type === 'file' ? '📁' : '🔗' }}</span>
                                <div>
                                    @if($res->resource_type === 'file')
                                        <a href="{{ route('lms.private-file.download', ['type' => 'resource', 'id' => $res->id]) }}" class="font-medium hover:text-blue-600 text-sm">
                                            {{ $res->title }}
                                        </a>
                                    @else
                                        <a href="{{ $res->external_url }}" target="_blank" class="font-medium hover:text-blue-600 text-sm">
                                            {{ $res->title }} ↗
                                        </a>
                                    @endif
                                    <span class="block text-[10px] text-slate-400 uppercase">{{ $res->resource_type }}</span>
                                </div>
                            </div>
                            <form action="{{ route('lms.resources.destroy', $res->id) }}" method="POST" onsubmit="return confirm('Hapus lampiran ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs text-red-500">Hapus</button>
                            </form>
                        </div>
                    @empty
                        <p class="text-slate-400 text-xs py-2">Belum ada lampiran pendukung.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Resource Modal -->
<div id="add-resource-modal" class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl border w-full max-w-md p-6 dark:bg-slate-900 dark:border-slate-800 shadow-xl">
        <h3 class="text-xl font-bold mb-4">Tambah Lampiran Baru</h3>
        <form action="{{ route('lms.resources.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <input type="hidden" name="lesson_id" value="{{ $lesson->id }}">

            <div>
                <label class="block text-sm font-medium mb-1">Judul Lampiran <span class="text-red-500">*</span></label>
                <input type="text" name="title" required class="w-full rounded-lg border border-slate-300 px-3 py-2 dark:bg-slate-800 dark:border-slate-700">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Tipe Lampiran <span class="text-red-500">*</span></label>
                <select name="resource_type" id="res-type-select" onchange="toggleResourceFields(this.value);" required class="w-full rounded-lg border border-slate-300 px-3 py-2 dark:bg-slate-800 dark:border-slate-700">
                    <option value="file">Unggah File</option>
                    <option value="link">Link Web Eksternal</option>
                </select>
            </div>

            <div id="file-field">
                <label class="block text-sm font-medium mb-1">Pilih Berkas File (Max 10MB) <span class="text-red-500">*</span></label>
                <input type="file" name="file" class="w-full">
            </div>

            <div id="link-field" class="hidden">
                <label class="block text-sm font-medium mb-1">URL Link Eksternal <span class="text-red-500">*</span></label>
                <input type="url" name="external_url" class="w-full rounded-lg border border-slate-300 px-3 py-2 dark:bg-slate-800 dark:border-slate-700" placeholder="https://example.com/file">
            </div>

            <div class="flex gap-4 justify-end border-t pt-4">
                <button type="button" onclick="document.getElementById('add-resource-modal').classList.add('hidden');" class="bg-slate-200 text-slate-700 px-4 py-2 font-medium rounded-lg">Batal</button>
                <button type="submit" class="btn-primary px-6 py-2 text-white font-semibold rounded-lg">Simpan Lampiran</button>
            </div>
        </form>
    </div>
</div>

<!-- Add Question Modal (only for quizzes) -->
@if($lesson->quiz)
    <div id="add-question-modal" class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-2xl border w-full max-w-md p-6 dark:bg-slate-900 dark:border-slate-800 shadow-xl max-h-[90vh] overflow-y-auto">
            <h3 class="text-xl font-bold mb-4">Tambah Pertanyaan Baru</h3>
            <form action="{{ route('lms.quizzes.questions.store', $lesson->quiz->id) }}" method="POST" class="space-y-4">
                @csrf
                
                <div>
                    <label class="block text-sm font-medium mb-1">Teks Pertanyaan <span class="text-red-500">*</span></label>
                    <textarea name="question_text" required rows="3" class="w-full rounded-lg border border-slate-300 px-3 py-2 dark:bg-slate-800 dark:border-slate-700"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Tipe Pertanyaan <span class="text-red-500">*</span></label>
                    <select name="question_type" id="question-type" onchange="toggleQuestionFields(this.value);" required class="w-full rounded-lg border border-slate-300 px-3 py-2 dark:bg-slate-800 dark:border-slate-700">
                        <option value="multiple_choice">Pilihan Ganda (A, B, C, D)</option>
                        <option value="true_false">Benar / Salah</option>
                        <option value="short_answer">Isian Singkat</option>
                    </select>
                </div>

                <!-- MC Fields -->
                <div id="mc-fields" class="space-y-2">
                    <label class="block text-sm font-medium">Pilihan Jawaban</label>
                    <div class="flex items-center gap-2">
                        <span class="font-bold">A:</span>
                        <input type="text" name="options[A]" class="w-full rounded-lg border border-slate-300 px-3 py-1.5 dark:bg-slate-800 dark:border-slate-700">
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="font-bold">B:</span>
                        <input type="text" name="options[B]" class="w-full rounded-lg border border-slate-300 px-3 py-1.5 dark:bg-slate-800 dark:border-slate-700">
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="font-bold">C:</span>
                        <input type="text" name="options[C]" class="w-full rounded-lg border border-slate-300 px-3 py-1.5 dark:bg-slate-800 dark:border-slate-700">
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="font-bold">D:</span>
                        <input type="text" name="options[D]" class="w-full rounded-lg border border-slate-300 px-3 py-1.5 dark:bg-slate-800 dark:border-slate-700">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Kunci Jawaban Benar <span class="text-red-500">*</span></label>
                    <input type="text" name="correct_answer" required placeholder="Contoh: A, atau true, atau jawaban isian singkat" class="w-full rounded-lg border border-slate-300 px-3 py-2 dark:bg-slate-800 dark:border-slate-700">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Bobot Nilai Pertanyaan</label>
                    <input type="number" name="score_weight" value="10" min="1" class="w-full rounded-lg border border-slate-300 px-3 py-2 dark:bg-slate-800 dark:border-slate-700">
                </div>

                <div class="flex gap-4 justify-end border-t pt-4">
                    <button type="button" onclick="document.getElementById('add-question-modal').classList.add('hidden');" class="bg-slate-200 text-slate-700 px-4 py-2 font-medium rounded-lg">Batal</button>
                    <button type="submit" class="btn-primary px-6 py-2 text-white font-semibold rounded-lg">Simpan Pertanyaan</button>
                </div>
            </form>
        </div>
    </div>
@endif

<script>
    function toggleResourceFields(val) {
        if (val === 'file') {
            document.getElementById('file-field').classList.remove('hidden');
            document.getElementById('link-field').classList.add('hidden');
        } else {
            document.getElementById('file-field').classList.add('hidden');
            document.getElementById('link-field').classList.remove('hidden');
        }
    }
    
    function toggleQuestionFields(val) {
        if (val === 'multiple_choice') {
            document.getElementById('mc-fields').classList.remove('hidden');
        } else {
            document.getElementById('mc-fields').classList.add('hidden');
        }
    }
</script>
@endsection
