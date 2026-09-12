@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col border-b pb-4 gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="flex items-center gap-2">
                <a href="{{ route('lms.courses.index') }}" class="text-sm text-slate-500 hover:text-blue-600">← Kembali ke Daftar Kelas</a>
            </div>
            <h1 class="text-3xl font-extrabold tracking-tight mt-1">{{ $course->title }}</h1>
            <p class="text-slate-500">Kode: {{ $course->course_code }} | Level: {{ $course->level ?? '-' }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('lms.enrollments.index', ['course_id' => $course->id]) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 font-medium rounded-lg">Pendaftaran Santri</a>
            <a href="{{ route('lms.courses.edit', $course->id) }}" class="bg-slate-700 hover:bg-slate-800 text-white px-4 py-2 font-medium rounded-lg">Edit Kelas</a>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        <!-- Syllabus / Curriculum Structure -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl border p-6 shadow-sm dark:bg-slate-900 dark:border-slate-800">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold">Silabus & Materi</h2>
                    <button type="button" onclick="document.getElementById('add-module-modal').classList.remove('hidden');" class="text-xs font-semibold bg-blue-50 text-blue-700 hover:bg-blue-100 px-3 py-1.5 rounded-lg dark:bg-blue-950/50 dark:text-blue-300">
                        + Tambah Bab/Modul
                    </button>
                </div>

                @if($course->modules->isEmpty())
                    <p class="text-slate-500 text-center py-8">Belum ada modul pembelajaran.</p>
                @else
                    <div class="space-y-6">
                        @foreach($course->modules as $module)
                            <div class="border rounded-xl overflow-hidden dark:border-slate-800">
                                <div class="bg-slate-50 px-4 py-3 flex items-center justify-between border-b dark:bg-slate-800/50 dark:border-slate-800">
                                    <div>
                                        <h3 class="font-bold text-slate-800 dark:text-slate-200">{{ $module->title }}</h3>
                                        <p class="text-xs text-slate-500">{{ $module->description }}</p>
                                    </div>
                                    <div class="flex gap-2">
                                        <button type="button" onclick="openAddLessonModal({{ $module->id }});" class="text-xs font-semibold text-blue-600 hover:text-blue-800">
                                            + Tambah Materi
                                        </button>
                                        <form action="{{ route('lms.modules.destroy', $module->id) }}" method="POST" onsubmit="return confirm('Hapus modul ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs text-red-600 hover:text-red-800 font-semibold">Hapus</button>
                                        </form>
                                    </div>
                                </div>
                                <div class="divide-y dark:divide-slate-800">
                                    @forelse($module->lessons as $lesson)
                                        <div class="px-4 py-3 flex items-center justify-between hover:bg-slate-50 dark:hover:bg-slate-800/30">
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
                                                    <a href="{{ route('lms.lessons.show', $lesson->id) }}" class="font-semibold text-slate-800 hover:text-blue-600 dark:text-slate-200">
                                                        {{ $lesson->title }}
                                                    </a>
                                                    <div class="flex items-center gap-2 mt-0.5 text-xs text-slate-500">
                                                        <span class="capitalize">{{ $lesson->lesson_type }}</span>
                                                        <span>•</span>
                                                        <span>{{ $lesson->estimated_minutes }} Menit</span>
                                                        <span>•</span>
                                                        <span class="capitalize px-1 rounded font-semibold text-[10px] {{ $lesson->visibility === 'published' ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800' }}">
                                                            {{ $lesson->visibility }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div>
                                                <form action="{{ route('lms.lessons.destroy', $lesson->id) }}" method="POST" onsubmit="return confirm('Hapus materi ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-xs text-red-500 hover:text-red-700 font-medium">Hapus</button>
                                                </form>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="p-4 text-xs text-slate-400 text-center">Belum ada materi pembelajaran dalam modul ini.</p>
                                    @endforelse
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar Details -->
        <div class="space-y-6">
            <div class="bg-white rounded-2xl border p-6 shadow-sm dark:bg-slate-900 dark:border-slate-800">
                <h2 class="text-lg font-bold mb-4 font-extrabold">Informasi Kelas</h2>
                <div class="space-y-3 text-sm">
                    <div>
                        <span class="block text-slate-400">Deskripsi</span>
                        <p class="mt-1 text-slate-700 dark:text-slate-300">{{ $course->description ?? 'Tidak ada deskripsi.' }}</p>
                    </div>
                    <hr class="dark:border-slate-800">
                    <div>
                        <span class="block text-slate-400">Guru Pengampu</span>
                        <div class="mt-1 space-y-1">
                            @forelse($course->instructors as $instructor)
                                <div class="flex items-center justify-between">
                                    <span class="font-medium">{{ $instructor->user?->name }}</span>
                                    @if($instructor->pivot->is_primary)
                                        <span class="text-[10px] bg-blue-100 text-blue-800 px-1.5 py-0.5 rounded font-bold uppercase">Utama</span>
                                    @endif
                                </div>
                            @empty
                                <span class="text-slate-400">Belum ditugaskan</span>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Module Modal -->
<div id="add-module-modal" class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl border w-full max-w-md p-6 dark:bg-slate-900 dark:border-slate-800 shadow-xl">
        <h3 class="text-xl font-bold mb-4">Tambah Bab/Modul Baru</h3>
        <form action="{{ route('lms.modules.store') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="course_id" value="{{ $course->id }}">
            
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Judul Bab/Modul <span class="text-red-500">*</span></label>
                <input type="text" name="title" required class="w-full rounded-lg border border-slate-300 px-3 py-2 dark:bg-slate-800 dark:border-slate-700">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Deskripsi Singkat</label>
                <textarea name="description" rows="3" class="w-full rounded-lg border border-slate-300 px-3 py-2 dark:bg-slate-800 dark:border-slate-700"></textarea>
            </div>

            <div class="flex gap-4 justify-end border-t pt-4">
                <button type="button" onclick="document.getElementById('add-module-modal').classList.add('hidden');" class="bg-slate-200 text-slate-700 px-4 py-2 font-medium rounded-lg">Batal</button>
                <button type="submit" class="btn-primary px-6 py-2 text-white font-semibold rounded-lg">Simpan Modul</button>
            </div>
        </form>
    </div>
</div>

<!-- Add Lesson Modal -->
<div id="add-lesson-modal" class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl border w-full max-w-lg p-6 dark:bg-slate-900 dark:border-slate-800 shadow-xl max-h-[90vh] overflow-y-auto">
        <h3 class="text-xl font-bold mb-4">Tambah Materi Baru</h3>
        <form action="{{ route('lms.lessons.store') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="course_id" value="{{ $course->id }}">
            <input type="hidden" name="module_id" id="modal-module-id">

            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Judul Materi <span class="text-red-500">*</span></label>
                <input type="text" name="title" required class="w-full rounded-lg border border-slate-300 px-3 py-2 dark:bg-slate-800 dark:border-slate-700">
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Tipe Materi <span class="text-red-500">*</span></label>
                    <select name="lesson_type" required class="w-full rounded-lg border border-slate-300 px-3 py-2 dark:bg-slate-800 dark:border-slate-700">
                        <option value="text">Teks Bacaan</option>
                        <option value="file">Dokumen / File</option>
                        <option value="link">Link Eksternal</option>
                        <option value="embed">Embed (Video/Lainnya)</option>
                        <option value="quiz">Kuis Pilihan Ganda</option>
                        <option value="assignment">Tugas Pengumpulan File</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Estimasi Durasi (Menit)</label>
                    <input type="number" name="estimated_minutes" value="10" min="0" class="w-full rounded-lg border border-slate-300 px-3 py-2 dark:bg-slate-800 dark:border-slate-700">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Isi Teks Materi</label>
                <textarea name="content" rows="4" class="w-full rounded-lg border border-slate-300 px-3 py-2 dark:bg-slate-800 dark:border-slate-700" placeholder="Ketik tulisan materi di sini jika bertipe teks..."></textarea>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Visibilitas</label>
                    <select name="visibility" class="w-full rounded-lg border border-slate-300 px-3 py-2 dark:bg-slate-800 dark:border-slate-700">
                        <option value="draft">Draft (Sembunyikan)</option>
                        <option value="published">Publikasikan</option>
                    </select>
                </div>
                <div class="flex items-center mt-6">
                    <input type="checkbox" name="is_required" value="1" checked id="lesson-required" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                    <label for="lesson-required" class="ml-2 text-sm text-slate-700 dark:text-slate-300">Wajib Selesai untuk Progres</label>
                </div>
            </div>

            <div class="flex gap-4 justify-end border-t pt-4">
                <button type="button" onclick="document.getElementById('add-lesson-modal').classList.add('hidden');" class="bg-slate-200 text-slate-700 px-4 py-2 font-medium rounded-lg">Batal</button>
                <button type="submit" class="btn-primary px-6 py-2 text-white font-semibold rounded-lg">Simpan Materi</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openAddLessonModal(moduleId) {
        document.getElementById('modal-module-id').value = moduleId;
        document.getElementById('add-lesson-modal').classList.remove('hidden');
    }
</script>
@endsection
