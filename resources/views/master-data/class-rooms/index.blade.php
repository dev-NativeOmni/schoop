@extends('layouts.app')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold">Data Kelas</h2>
            <p class="text-sm text-slate-500">Kelola data kelas sekolah.</p>
        </div>

        <a href="{{ route('master-data.class-rooms.create') }}"
           class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">
            Tambah Kelas
        </a>
    </div>

    <div class="overflow-hidden rounded-2xl bg-white shadow-sm">
        <table class="w-full border-collapse text-left text-sm">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-4 py-3">Nama Kelas</th>
                    <th class="px-4 py-3">Sekolah</th>
                    <th class="px-4 py-3">Wali Kelas</th>
                    <th class="px-4 py-3">Tahun Akademik</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($classRooms as $classRoom)
                    <tr class="border-t">
                        <td class="px-4 py-3 font-semibold">{{ $classRoom->name }}</td>
                        <td class="px-4 py-3">{{ $classRoom->school?->name }}</td>
                        <td class="px-4 py-3">{{ $classRoom->homeroomTeacher?->name ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $classRoom->academic_year ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $classRoom->is_active ? 'Aktif' : 'Nonaktif' }}</td>
                        <td class="px-4 py-3">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('master-data.class-rooms.show', $classRoom) }}" class="text-slate-700 hover:underline">Lihat</a>
                                <a href="{{ route('master-data.class-rooms.edit', $classRoom) }}" class="text-blue-700 hover:underline">Edit</a>

                                <form method="POST" action="{{ route('master-data.class-rooms.destroy', $classRoom) }}"
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
        {{ $classRooms->links() }}
    </div>
@endsection
