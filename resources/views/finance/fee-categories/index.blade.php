@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <div class="flex flex-col mb-6 gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Kategori Biaya</h1>
            <p class="text-sm text-gray-600">Kelola kategori biaya/tarif sekolah.</p>
        </div>

        <a href="{{ route('finance.fee-categories.create') }}"
           class="px-4 py-2 bg-blue-600 text-white rounded-lg">
            Tambah Kategori
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-lg bg-green-50 px-4 py-3 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 text-gray-700">
                <tr>
                    <th class="px-4 py-3 text-left">Nama</th>
                    <th class="px-4 py-3 text-left">Deskripsi</th>
                    <th class="px-4 py-3 text-left">Urutan</th>
                    <th class="px-4 py-3 text-left">Aktif</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($categories as $category)
                    <tr>
                        <td class="px-4 py-3 font-medium">{{ $category->name }}</td>
                        <td class="px-4 py-3">{{ $category->description ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $category->sort_order }}</td>
                        <td class="px-4 py-3">{{ $category->is_active ? 'Ya' : 'Tidak' }}</td>
                        <td class="px-4 py-3 text-right space-x-2">
                            <a href="{{ route('finance.fee-categories.show', $category) }}" class="text-blue-600">Detail</a>
                            <a href="{{ route('finance.fee-categories.edit', $category) }}" class="text-amber-600">Edit</a>
                            <form action="{{ route('finance.fee-categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('Hapus kategori ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-gray-500">
                            Belum ada kategori biaya.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $categories->links() }}
    </div>
</div>
@endsection
