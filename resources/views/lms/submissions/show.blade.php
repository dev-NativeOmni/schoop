@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="border-b pb-4">
        <div class="flex items-center gap-2">
            <a href="{{ route('lms.submissions.index', ['assignment_id' => $submission->assignment_id]) }}" class="text-sm text-slate-500 hover:text-blue-600">← Kembali ke Pengumpulan Tugas</a>
        </div>
        <h1 class="text-3xl font-extrabold tracking-tight mt-1">Evaluasi Tugas Santri</h1>
        <p class="text-slate-500">Oleh: {{ $submission->student->full_name }}</p>
    </div>

    <div class="bg-white rounded-2xl border p-6 shadow-sm dark:bg-slate-900 dark:border-slate-800 space-y-6">
        <div>
            <h2 class="text-lg font-bold font-extrabold mb-2">Pekerjaan Santri</h2>
            @if($submission->submitted_text)
                <div class="bg-slate-50 dark:bg-slate-800 p-4 rounded-xl text-slate-700 dark:text-slate-300 whitespace-pre-wrap">
                    {{ $submission->submitted_text }}
                </div>
            @endif

            @if($submission->file_path)
                <div class="mt-4 flex items-center justify-between p-4 bg-slate-100 rounded-xl dark:bg-slate-800/50">
                    <div class="flex items-center gap-3">
                        <span class="text-2xl">📁</span>
                        <div>
                            <span class="font-semibold block text-sm">{{ $submission->file_name }}</span>
                            <span class="text-xs text-slate-400">Ukuran: {{ number_format($submission->file_size / 1024, 1) }} KB</span>
                        </div>
                    </div>
                    <!-- Download link -->
                    <a href="{{ route('lms.private-file.download', ['type' => 'submission', 'id' => $submission->id]) }}" class="bg-blue-600 text-white font-medium px-4 py-2 rounded-lg text-xs hover:bg-blue-700">
                        Unduh Berkas
                    </a>
                </div>
            @endif
        </div>

        <hr class="dark:border-slate-800">

        <!-- Grading Form -->
        <div>
            <h2 class="text-lg font-bold font-extrabold mb-4">Penilaian & Umpan Balik</h2>
            <form action="{{ route('lms.submissions.grade', $submission->id) }}" method="POST" class="space-y-4">
                @csrf
                
                <div>
                    <label class="block text-sm font-medium mb-1">Skor Penilaian (Maks {{ $submission->assignment->max_score }}) <span class="text-red-500">*</span></label>
                    <input type="number" name="score" value="{{ old('score', $submission->score) }}" step="0.1" max="{{ $submission->assignment->max_score }}" required class="w-32 rounded-lg border border-slate-300 px-3 py-2 dark:bg-slate-800 dark:border-slate-700">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Umpan Balik / Catatan Guru</label>
                    <textarea name="teacher_feedback" rows="4" class="w-full rounded-lg border border-slate-300 px-3 py-2 dark:bg-slate-800 dark:border-slate-700" placeholder="Berikan saran atau catatan evaluasi pengerjaan tugas..."></textarea>
                </div>

                <div class="flex justify-end gap-4">
                    <a href="{{ route('lms.submissions.index', ['assignment_id' => $submission->assignment_id]) }}" class="bg-slate-200 text-slate-700 px-4 py-2 font-medium rounded-lg">Batal</a>
                    <button type="submit" class="btn-primary px-6 py-2 text-white font-semibold rounded-lg">Kirim Penilaian</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
