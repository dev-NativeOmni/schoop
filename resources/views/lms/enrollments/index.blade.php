@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between border-b pb-4">
        <div>
            <div class="flex items-center gap-2">
                <a href="{{ route('lms.courses.show', $course->id) }}" class="text-sm text-slate-500 hover:text-blue-600">← Kembali ke Silabus Kelas</a>
            </div>
            <h1 class="text-3xl font-extrabold tracking-tight mt-1">Kelola Pendaftaran Santri</h1>
            <p class="text-slate-500">Kelas: {{ $course->title }}</p>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        <!-- Enrolled List -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl border overflow-hidden shadow-sm dark:bg-slate-900 dark:border-slate-800">
                <div class="px-6 py-4 border-b">
                    <h2 class="text-lg font-bold">Daftar Santri Terdaftar</h2>
                </div>
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b dark:bg-slate-800 dark:border-slate-800 text-xs font-semibold uppercase text-slate-500">
                            <th class="px-6 py-3">Nama Santri</th>
                            <th class="px-6 py-3">Progres</th>
                            <th class="px-6 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y dark:divide-slate-800 text-sm">
                        @forelse($enrollments as $enroll)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30">
                                <td class="px-6 py-4 font-semibold text-slate-800 dark:text-slate-200">
                                    {{ $enroll->student->full_name }}
                                </td>
                                <td class="px-6 py-4 text-slate-600 dark:text-slate-400">
                                    {{ $enroll->progress_percentage }}% ({{ $enroll->status }})
                                </td>
                                <td class="px-6 py-4">
                                    <form action="{{ route('lms.enrollments.destroy', $enroll->id) }}" method="POST" onsubmit="return confirm('Keluarkan santri ini dari kelas?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs text-red-500 hover:text-red-700 font-medium">Batalkan</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-8 text-center text-slate-400">
                                    Belum ada santri terdaftar di kelas ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Enroll Form -->
        <div class="space-y-6">
            <div class="bg-white rounded-2xl border p-6 shadow-sm dark:bg-slate-900 dark:border-slate-800">
                <h2 class="text-lg font-bold mb-4 font-extrabold">Daftarkan Santri Baru</h2>
                <form action="{{ route('lms.enrollments.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="course_id" value="{{ $course->id }}">

                    <div>
                        <label class="block text-sm font-medium mb-1">Metode Pendaftaran</label>
                        <select name="enrollment_type" id="enroll-type" onchange="toggleEnrollFields(this.value);" class="w-full rounded-lg border border-slate-300 px-3 py-2 dark:bg-slate-800 dark:border-slate-700">
                            <option value="student">Santri Individu</option>
                            <option value="class_room">Seluruh Rombel / Kelas</option>
                        </select>
                    </div>

                    <div id="student-field">
                        <label class="block text-sm font-medium mb-1">Pilih Santri</label>
                        <select name="student_id" class="w-full rounded-lg border border-slate-300 px-3 py-2 dark:bg-slate-800 dark:border-slate-700">
                            @foreach($students as $st)
                                <option value="{{ $st->id }}">{{ $st->full_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div id="class-field" class="hidden">
                        <label class="block text-sm font-medium mb-1">Pilih Rombel / Kelas</label>
                        <select name="class_room_id" class="w-full rounded-lg border border-slate-300 px-3 py-2 dark:bg-slate-800 dark:border-slate-700">
                            @foreach($classRooms as $cr)
                                <option value="{{ $cr->id }}">{{ $cr->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="w-full btn-primary px-4 py-2 text-white font-semibold rounded-lg">Daftarkan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleEnrollFields(val) {
        if (val === 'student') {
            document.getElementById('student-field').classList.remove('hidden');
            document.getElementById('class-field').classList.add('hidden');
        } else {
            document.getElementById('student-field').classList.add('hidden');
            document.getElementById('class-field').classList.remove('hidden');
        }
    }
</script>
@endsection
