@extends('layouts.app')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold">Data Orang Tua</h2>
            <p class="text-sm text-slate-500">Kelola data orang tua / wali santri.</p>
        </div>

        <a href="{{ route('master-data.parents.create') }}"
           class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">
            Tambah Orang Tua
        </a>
    </div>

    <div class="overflow-hidden rounded-2xl bg-white shadow-sm">
        <table class="w-full border-collapse text-left text-sm">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-4 py-3">Nama</th>
                    <th class="px-4 py-3">Hubungan</th>
                    <th class="px-4 py-3">Pekerjaan</th>
                    <th class="px-4 py-3">Daftar Anak</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($parents as $parent)
                    <tr class="border-t">
                        <td class="px-4 py-3 font-semibold">{{ $parent->user?->name }}</td>
                        <td class="px-4 py-3">{{ $parent->relationship ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $parent->occupation ?? '-' }}</td>
                        <td class="px-4 py-3">
                            <div class="flex flex-wrap gap-1">
                                @forelse ($parent->students as $child)
                                    <span class="inline-block bg-slate-100 text-slate-700 px-2 py-0.5 rounded text-xs font-semibold">
                                        {{ $child->full_name }}
                                    </span>
                                @empty
                                    <span class="text-slate-400 text-xs">-</span>
                                @endforelse
                            </div>
                        </td>
                        <td class="px-4 py-3">{{ $parent->is_active ? 'Aktif' : 'Nonaktif' }}</td>
                        <td class="px-4 py-3">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('master-data.parents.show', $parent) }}" class="text-slate-700 hover:underline">Lihat</a>
                                <a href="{{ route('master-data.parents.edit', $parent) }}" class="text-blue-700 hover:underline">Edit</a>

                                <form method="POST" action="{{ route('master-data.parents.destroy', $parent) }}"
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
        {{ $parents->links() }}
    </div>
@endsection
