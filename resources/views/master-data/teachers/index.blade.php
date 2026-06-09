@extends('layouts.app')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold">Data Guru</h2>
            <p class="text-sm text-slate-500">Kelola data guru tahfidz.</p>
        </div>

        <a href="{{ route('master-data.teachers.create') }}"
           class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">
            Tambah Guru
        </a>
    </div>

    <div class="overflow-hidden rounded-2xl bg-white shadow-sm">
        <table class="w-full border-collapse text-left text-sm">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-4 py-3">Nama</th>
                    <th class="px-4 py-3">NIP / No. Pegawai</th>
                    <th class="px-4 py-3">Spesialisasi</th>
                    <th class="px-4 py-3">Sekolah</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($teachers as $teacher)
                    <tr class="border-t">
                        <td class="px-4 py-3 font-semibold">{{ $teacher->user?->name }}</td>
                        <td class="px-4 py-3">{{ $teacher->employee_number ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $teacher->specialization ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $teacher->school?->name }}</td>
                        <td class="px-4 py-3">{{ $teacher->is_active ? 'Aktif' : 'Nonaktif' }}</td>
                        <td class="px-4 py-3">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('master-data.teachers.show', $teacher) }}" class="text-slate-700 hover:underline">Lihat</a>
                                <a href="{{ route('master-data.teachers.edit', $teacher) }}" class="text-blue-700 hover:underline">Edit</a>

                                <form method="POST" action="{{ route('master-data.teachers.destroy', $teacher) }}"
                                      onsubmit="return confirm('Hapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-700 hover:underline">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-slate-500">
                            Belum ada data.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $teachers->links() }}
    </div>
@endsection
