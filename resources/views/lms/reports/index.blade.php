@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="border-b pb-4">
        <h1 class="text-3xl font-extrabold tracking-tight">Laporan Progres Belajar Santri</h1>
        <p class="text-slate-500">Pantau tingkat kelulusan kelas dan persentase progres masing-masing santri.</p>
    </div>

    <!-- Filter Form -->
    <div class="bg-white rounded-2xl border p-6 shadow-sm dark:bg-slate-900 dark:border-slate-800">
        <form action="{{ route('lms.reports.index') }}" method="GET" class="grid gap-4 md:grid-cols-3 items-end">
            <div>
                <label class="block text-sm font-medium mb-1">Pilih Kelas LMS</label>
                <select name="course_id" class="w-full rounded-lg border border-slate-300 px-3 py-2 dark:bg-slate-800 dark:border-slate-700">
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ (int)$selectedCourseId === (int)$course->id ? 'selected' : '' }}>
                            {{ $course->title }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Filter Kelas Halaqah / Rombel</label>
                <select name="class_room_id" class="w-full rounded-lg border border-slate-300 px-3 py-2 dark:bg-slate-800 dark:border-slate-700">
                    <option value="">-- Semua Rombel --</option>
                    @foreach($classRooms as $cr)
                        <option value="{{ $cr->id }}" {{ (int)$selectedClassRoomId === (int)$cr->id ? 'selected' : '' }}>
                            {{ $cr->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <button type="submit" class="w-full btn-primary px-6 py-2.5 text-white font-semibold rounded-lg">Filter Laporan</button>
            </div>
        </form>
    </div>

    @if($selectedCourseId)
        <!-- Stats summary -->
        @if(!empty($completionStats))
            <div class="grid gap-6 md:grid-cols-3">
                <div class="bg-white border rounded-2xl p-6 dark:bg-slate-900 dark:border-slate-800 shadow-sm">
                    <span class="block text-xs font-semibold text-slate-400 uppercase">Santri Terdaftar</span>
                    <span class="text-3xl font-extrabold block mt-2">{{ $completionStats['total_enrolled'] }}</span>
                </div>
                <div class="bg-white border rounded-2xl p-6 dark:bg-slate-900 dark:border-slate-800 shadow-sm">
                    <span class="block text-xs font-semibold text-slate-400 uppercase">Santri Lulus / Selesai</span>
                    <span class="text-3xl font-extrabold block mt-2 text-green-600">{{ $completionStats['completed_count'] }}</span>
                </div>
                <div class="bg-white border rounded-2xl p-6 dark:bg-slate-900 dark:border-slate-800 shadow-sm">
                    <span class="block text-xs font-semibold text-slate-400 uppercase">Tingkat Kelulusan</span>
                    <span class="text-3xl font-extrabold block mt-2 text-blue-600">{{ $completionStats['completion_rate'] }}%</span>
                </div>
            </div>
        @endif

        <!-- Progress Table -->
        <div class="bg-white border rounded-2xl overflow-hidden shadow-sm dark:bg-slate-900 dark:border-slate-800">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b dark:bg-slate-800 dark:border-slate-800 text-xs font-semibold uppercase text-slate-500">
                        <th class="px-6 py-3">Nama Santri</th>
                        <th class="px-6 py-3">Kelas Halaqah</th>
                        <th class="px-6 py-3">Progres Persentase</th>
                        <th class="px-6 py-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y dark:divide-slate-800 text-sm">
                    @forelse($enrollments as $enrollment)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30">
                            <td class="px-6 py-4 font-semibold text-slate-800 dark:text-slate-200">
                                {{ $enrollment->student->full_name }}
                            </td>
                            <td class="px-6 py-4 text-slate-500">
                                {{ $enrollment->student->classRoom ? $enrollment->student->classRoom->name : '-' }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-2.5 max-w-48">
                                        <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $enrollment->progress_percentage }}%"></div>
                                    </div>
                                    <span class="font-bold text-slate-700 dark:text-slate-300 text-xs">{{ $enrollment->progress_percentage }}%</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="capitalize px-2 py-0.5 rounded text-xs font-semibold {{ $enrollment->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ $enrollment->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-slate-400">
                                Belum ada santri terdaftar pada rombel/kelas yang dicari.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
