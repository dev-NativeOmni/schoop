@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="border-b pb-4">
        <h1 class="text-3xl font-extrabold tracking-tight">Tambah Kelas Baru</h1>
        <p class="text-slate-500">Buat kelas pembelajaran baru dan tugaskan guru pengampu.</p>
    </div>

    <div class="bg-white rounded-2xl border p-6 shadow-sm dark:bg-slate-900 dark:border-slate-800">
        <form action="{{ route('lms.courses.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Judul Kelas <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title') }}" required class="w-full rounded-lg border border-slate-300 px-3 py-2 dark:bg-slate-800 dark:border-slate-700" placeholder="Contoh: Bahasa Arab Dasar">
                    @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Kode Kelas <span class="text-red-500">*</span></label>
                    <input type="text" name="course_code" value="{{ old('course_code') }}" required class="w-full rounded-lg border border-slate-300 px-3 py-2 dark:bg-slate-800 dark:border-slate-700" placeholder="Contoh: ARA-01">
                    @error('course_code') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Tipe / Kategori</label>
                    <input type="text" name="type" value="{{ old('type', 'general') }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 dark:bg-slate-800 dark:border-slate-700">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Tingkat / Level</label>
                    <input type="text" name="level" value="{{ old('level') }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 dark:bg-slate-800 dark:border-slate-700" placeholder="Contoh: Dasar, Menengah">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Metode Pendaftaran</label>
                    <select name="enrollment_mode" class="w-full rounded-lg border border-slate-300 px-3 py-2 dark:bg-slate-800 dark:border-slate-700">
                        <option value="manual" {{ old('enrollment_mode') === 'manual' ? 'selected' : '' }}>Manual</option>
                        <option value="class_room" {{ old('enrollment_mode') === 'class_room' ? 'selected' : '' }}>Per Kelas</option>
                        <option value="school_wide" {{ old('enrollment_mode') === 'school_wide' ? 'selected' : '' }}>Seluruh Sekolah</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Visibilitas</label>
                    <select name="visibility" class="w-full rounded-lg border border-slate-300 px-3 py-2 dark:bg-slate-800 dark:border-slate-700">
                        <option value="draft" {{ old('visibility') === 'draft' ? 'selected' : '' }}>Draft (Disembunyikan)</option>
                        <option value="published" {{ old('visibility') === 'published' ? 'selected' : '' }}>Dipublikasikan</option>
                        <option value="archived" {{ old('visibility') === 'archived' ? 'selected' : '' }}>Diarsipkan</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Tanggal Mulai</label>
                    <input type="date" name="start_date" value="{{ old('start_date') }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 dark:bg-slate-800 dark:border-slate-700">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Tanggal Selesai</label>
                    <input type="date" name="end_date" value="{{ old('end_date') }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 dark:bg-slate-800 dark:border-slate-700">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Deskripsi Kelas</label>
                <textarea name="description" rows="4" class="w-full rounded-lg border border-slate-300 px-3 py-2 dark:bg-slate-800 dark:border-slate-700" placeholder="Ketik deskripsi atau silabus singkat kelas..."></textarea>
            </div>

            <div class="border-t pt-4">
                <h3 class="text-lg font-bold mb-4">Penugasan Guru Pengampu</h3>
                <div class="grid gap-6 md:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Daftar Guru</label>
                        <select name="instructor_ids[]" multiple class="w-full rounded-lg border border-slate-300 px-3 py-2 dark:bg-slate-800 dark:border-slate-700 min-h-24">
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}">{{ $teacher->user?->name }}</option>
                            @endforeach
                        </select>
                        <p class="text-xs text-slate-500 mt-1">Tahan tombol Ctrl (Windows) / Cmd (Mac) untuk memilih lebih dari satu guru.</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Guru Utama</label>
                        <select name="primary_instructor_id" class="w-full rounded-lg border border-slate-300 px-3 py-2 dark:bg-slate-800 dark:border-slate-700">
                            <option value="">-- Pilih Guru Utama --</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}">{{ $teacher->user?->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="flex gap-4 border-t pt-4 justify-end">
                <a href="{{ route('lms.courses.index') }}" class="bg-slate-200 hover:bg-slate-300 text-slate-700 px-4 py-2 font-medium rounded-lg">Batal</a>
                <button type="submit" class="btn-primary px-6 py-2 text-white font-semibold rounded-lg">Simpan Kelas</button>
            </div>
        </form>
    </div>
</div>
@endsection
