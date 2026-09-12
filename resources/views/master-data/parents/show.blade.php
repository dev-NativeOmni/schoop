@extends('layouts.app')

@section('content')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold">Detail Orang Tua / Wali</h2>
            <p class="text-sm text-slate-500">Detail data orang tua wali murid.</p>
        </div>

        <a href="{{ route('master-data.parents.index') }}"
           class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
            Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-1 bg-white p-6 rounded-2xl shadow-sm space-y-4 h-fit">
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Nama Lengkap</h3>
                <p class="text-lg font-semibold text-slate-900">{{ $parent->user?->name }}</p>
            </div>

            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Username</h3>
                <p class="text-base text-slate-700">{{ $parent->user?->username }}</p>
            </div>

            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Email</h3>
                <p class="text-base text-slate-700">{{ $parent->user?->email }}</p>
            </div>

            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Telepon / HP</h3>
                <p class="text-base text-slate-700">{{ $parent->user?->phone ?? '-' }}</p>
            </div>

            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Hubungan</h3>
                <p class="text-base text-slate-700">{{ $parent->relationship ?? '-' }}</p>
            </div>

            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Pekerjaan</h3>
                <p class="text-base text-slate-700">{{ $parent->occupation ?? '-' }}</p>
            </div>

            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Sekolah</h3>
                <p class="text-base text-slate-700">{{ $parent->school?->name }}</p>
            </div>

            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Alamat</h3>
                <p class="text-base text-slate-700">{{ $parent->address ?? '-' }}</p>
            </div>

            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Status</h3>
                <p class="text-base mt-1">
                    <span class="inline-block rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $parent->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $parent->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </p>
            </div>
        </div>

        <div class="md:col-span-2 bg-white p-6 rounded-2xl shadow-sm">
            <h3 class="text-lg font-bold mb-4">Daftar Anak Terhubung</h3>

            <div class="overflow-hidden border border-slate-100 rounded-lg">
                <div class="overflow-x-auto">
                <table class="w-full border-collapse text-left text-sm">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-3">Nama Santri</th>
                            <th class="px-4 py-3">Kelas</th>
                            <th class="px-4 py-3">No. Induk</th>
                            <th class="px-4 py-3">Program</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($parent->students as $student)
                            <tr class="border-t">
                                <td class="px-4 py-3 font-semibold text-slate-800">{{ $student->full_name }}</td>
                                <td class="px-4 py-3">{{ $student->classRoom?->name ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $student->student_number ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $student->program_type ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-6 text-center text-slate-500">
                                    Belum ada anak yang dihubungkan ke wali ini.
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
