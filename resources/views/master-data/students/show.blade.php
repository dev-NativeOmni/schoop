@extends('layouts.app')

@section('content')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold">Detail Santri</h2>
            <p class="text-sm text-slate-500">Detail data biodata santri.</p>
        </div>

        <a href="{{ route('master-data.students.index') }}"
           class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
            Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-1 bg-white p-6 rounded-2xl shadow-sm space-y-4 h-fit">
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Nama Lengkap</h3>
                <p class="text-lg font-semibold text-slate-900">{{ $student->full_name }}</p>
            </div>

            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Nama Panggilan</h3>
                <p class="text-base text-slate-700">{{ $student->nickname ?? '-' }}</p>
            </div>

            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">No. Induk Santri</h3>
                <p class="text-base text-slate-700">{{ $student->student_number ?? '-' }}</p>
            </div>

            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">NISN</h3>
                <p class="text-base text-slate-700">{{ $student->nisn ?? '-' }}</p>
            </div>

            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Kelas</h3>
                <p class="text-base text-slate-700">{{ $student->classRoom?->name ?? 'Belum ada kelas' }}</p>
            </div>

            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Program</h3>
                <p class="text-base text-slate-700 uppercase">{{ $student->program_type ?? '-' }}</p>
            </div>

            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Jenis Kelamin</h3>
                <p class="text-base text-slate-700">
                    {{ $student->gender == 'L' ? 'Laki-laki' : ($student->gender == 'P' ? 'Perempuan' : '-') }}
                </p>
            </div>

            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">TTL</h3>
                <p class="text-base text-slate-700">
                    {{ $student->birth_place ?? '-' }}, {{ $student->birth_date ? $student->birth_date->format('d M Y') : '-' }}
                </p>
            </div>

            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Telepon / HP</h3>
                <p class="text-base text-slate-700">{{ $student->phone ?? '-' }}</p>
            </div>

            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Alamat</h3>
                <p class="text-base text-slate-700">{{ $student->address ?? '-' }}</p>
            </div>

            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Sekolah</h3>
                <p class="text-base text-slate-700">{{ $student->school?->name }}</p>
            </div>

            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Status</h3>
                <p class="text-base mt-1">
                    <span class="inline-block rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $student->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $student->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </p>
            </div>
        </div>

        <div class="md:col-span-2 space-y-6">
            <div class="bg-white p-6 rounded-2xl shadow-sm space-y-4">
                <h3 class="text-lg font-bold">Akun Login Santri</h3>

                @if ($student->user)
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <h4 class="text-xs font-bold text-slate-400">Username</h4>
                            <p class="text-sm font-semibold text-slate-800">{{ $student->user->username }}</p>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-slate-400">Email</h4>
                            <p class="text-sm font-semibold text-slate-800">{{ $student->user->email ?? '-' }}</p>
                        </div>
                    </div>
                @else
                    <p class="text-sm text-slate-500 italic">Santri ini belum memiliki akun login.</p>
                @endif
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm">
                <h3 class="text-lg font-bold mb-4">Orang Tua / Wali Terhubung</h3>

                <div class="overflow-hidden border border-slate-100 rounded-lg">
                    <div class="overflow-x-auto">
                    <table class="w-full border-collapse text-left text-sm">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-4 py-3">Nama</th>
                                <th class="px-4 py-3">Hubungan</th>
                                <th class="px-4 py-3">Pekerjaan</th>
                                <th class="px-4 py-3">Telepon</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($student->parents as $parent)
                                <tr class="border-t">
                                    <td class="px-4 py-3 font-semibold text-slate-800">{{ $parent->user?->name }}</td>
                                    <td class="px-4 py-3">{{ $parent->relationship ?? '-' }}</td>
                                    <td class="px-4 py-3">{{ $parent->occupation ?? '-' }}</td>
                                    <td class="px-4 py-3">{{ $parent->user?->phone ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-6 text-center text-slate-500">
                                        Belum ada wali yang terhubung.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
