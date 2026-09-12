@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col border-b pb-4 gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-3xl font-extrabold tracking-tight">Kelola Kelas Pembelajaran</h1>
            <p class="text-slate-500">Buat, perbarui, dan lihat semua kelas pembelajaran aktif.</p>
        </div>
        @if(Auth::user()->isSuperAdmin() || Auth::user()->isAdmin() || Auth::user()->isTeacher())
            <a href="{{ route('lms.courses.create') }}" class="btn-primary px-4 py-2 text-white font-medium rounded-lg">Tambah Kelas</a>
        @endif
    </div>

    <!-- Course Grid -->
    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
        @forelse($courses as $course)
            <div class="rounded-2xl border bg-white overflow-hidden shadow-sm dark:bg-slate-900 dark:border-slate-800 flex flex-col">
                <div class="p-6 flex-1">
                    <div class="flex items-center justify-between gap-2 mb-2">
                        <span class="text-xs font-semibold px-2 py-0.5 rounded bg-blue-50 text-blue-700 dark:bg-blue-950/50 dark:text-blue-300">
                            {{ strtoupper($course->type) }}
                        </span>
                        <span class="text-xs font-medium text-slate-500">
                            Code: {{ $course->course_code }}
                        </span>
                    </div>
                    <h2 class="text-xl font-bold mb-2">
                        <a href="{{ route('lms.courses.show', $course->id) }}" class="hover:text-blue-600 transition-colors">
                            {{ $course->title }}
                        </a>
                    </h2>
                    <p class="text-sm text-slate-500 line-clamp-3 mb-4">
                        {{ $course->description ?? 'Tidak ada deskripsi.' }}
                    </p>
                    <div class="text-sm text-slate-600 dark:text-slate-400 space-y-1">
                        <div><strong>Pengajar:</strong> 
                            @forelse($course->instructors as $instructor)
                                {{ $instructor->user?->name }}{{ !$loop->last ? ', ' : '' }}
                            @empty
                                <span class="text-slate-400">Belum ditugaskan</span>
                            @endforelse
                        </div>
                        <div><strong>Level:</strong> {{ $course->level ?? '-' }}</div>
                        <div><strong>Aksesibilitas:</strong> 
                            <span class="capitalize px-1.5 py-0.5 rounded text-xs font-semibold {{ $course->visibility === 'published' ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800' }}">
                                {{ $course->visibility }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="border-t bg-slate-50 px-6 py-4 flex justify-between items-center dark:bg-slate-800/50 dark:border-slate-800">
                    <a href="{{ route('lms.courses.show', $course->id) }}" class="text-sm font-semibold text-blue-600 hover:text-blue-800">
                        Lihat Kurikulum →
                    </a>
                    @if(Auth::user()->isSuperAdmin() || Auth::user()->isAdmin() || $course->instructors->contains('user_id', Auth::id()))
                        <div class="flex gap-2">
                            <a href="{{ route('lms.courses.edit', $course->id) }}" class="text-xs font-medium bg-slate-200 hover:bg-slate-300 text-slate-700 px-2.5 py-1 rounded">
                                Edit
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12 bg-white rounded-2xl border dark:bg-slate-900 dark:border-slate-800">
                <p class="text-slate-500">Belum ada kelas pembelajaran yang dibuat.</p>
                <a href="{{ route('lms.courses.create') }}" class="inline-block mt-4 text-blue-600 font-semibold hover:underline">
                    Buat kelas pertama Anda sekarang.
                </a>
            </div>
        @endforelse
    </div>
</div>
@endsection
