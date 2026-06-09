@extends('layouts.app')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold">Data Sekolah</h2>
            <p class="text-sm text-slate-500">Kelola data sekolah di platform.</p>
        </div>

        <a href="{{ route('master-data.schools.create') }}"
           class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">
            Tambah Sekolah
        </a>
    </div>

    <div class="overflow-hidden rounded-2xl bg-white shadow-sm">
        <table class="w-full border-collapse text-left text-sm">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-4 py-3">Nama</th>
                    <th class="px-4 py-3">Kode</th>
                    <th class="px-4 py-3">Email</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($schools as $school)
                    <tr class="border-t">
                        <td class="px-4 py-3 font-semibold">{{ $school->name }}</td>
                        <td class="px-4 py-3">{{ $school->code }}</td>
                        <td class="px-4 py-3">{{ $school->email ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $school->is_active ? 'Aktif' : 'Nonaktif' }}</td>
                        <td class="px-4 py-3">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('master-data.schools.show', $school) }}" class="text-slate-700 hover:underline">Lihat</a>
                                <a href="{{ route('master-data.schools.edit', $school) }}" class="text-blue-700 hover:underline">Edit</a>

                                <form method="POST" action="{{ route('master-data.schools.destroy', $school) }}"
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
                        <td colspan="5" class="px-4 py-6 text-center text-slate-500">
                            Belum ada data.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $schools->links() }}
    </div>
@endsection
