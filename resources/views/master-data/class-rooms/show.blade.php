@extends('layouts.app')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold">Detail Kelas</h2>
            <p class="text-sm text-slate-500">Detail data kelas sekolah.</p>
        </div>

        <a href="{{ route('master-data.class-rooms.index') }}"
           class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
            Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-1 bg-white p-6 rounded-2xl shadow-sm space-y-4 h-fit">
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Nama Kelas</h3>
                <p class="text-lg font-semibold text-slate-900">{{ $classRoom->name }}</p>
            </div>

            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Sekolah</h3>
                <p class="text-base text-slate-700">{{ $classRoom->school?->name }}</p>
            </div>

            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Tingkat</h3>
                <p class="text-base text-slate-700">{{ $classRoom->level ?? '-' }}</p>
            </div>

            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Tahun Akademik</h3>
                <p class="text-base text-slate-700">{{ $classRoom->academic_year ?? '-' }}</p>
            </div>

            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Wali Kelas</h3>
                <p class="text-base text-slate-700">{{ $classRoom->homeroomTeacher?->name ?? '-' }}</p>
            </div>

            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Status</h3>
                <p class="text-base mt-1">
                    <span class="inline-block rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $classRoom->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $classRoom->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </p>
            </div>
        </div>

        <div class="md:col-span-2 bg-white p-6 rounded-2xl shadow-sm">
            <h3 class="text-lg font-bold mb-4">Daftar Santri di Kelas Ini</h3>

            <div class="overflow-hidden border border-slate-100 rounded-lg">
                <div class="overflow-x-auto">
                <table class="w-full border-collapse text-left text-sm">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-3">Nama Lengkap</th>
                            <th class="px-4 py-3">Nomor Induk</th>
                            <th class="px-4 py-3">Program</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($classRoom->students as $student)
                            <tr class="border-t">
                                <td class="px-4 py-3 font-semibold text-slate-800">{{ $student->full_name }}</td>
                                <td class="px-4 py-3">{{ $student->student_number ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $student->program_type ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-6 text-center text-slate-500">
                                    Belum ada santri terdaftar di kelas ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
@endsection
