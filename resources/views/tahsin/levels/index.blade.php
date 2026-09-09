@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Level Tahsin</h1>
            <p class="text-sm text-gray-600">Kelola level pembinaan tahsin.</p>
        </div>

        <a href="{{ route('tahsin.levels.create') }}"
           class="px-4 py-2 bg-blue-600 text-white rounded-lg">
            Tambah Level
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
                    <th class="px-4 py-3 text-left">Minimum Score</th>
                    <th class="px-4 py-3 text-left">Aktif</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($levels as $level)
                    <tr>
                        <td class="px-4 py-3 font-medium">{{ $level->name }}</td>
                        <td class="px-4 py-3">{{ $level->minimum_score }}</td>
                        <td class="px-4 py-3">{{ $level->is_active ? 'Ya' : 'Tidak' }}</td>
                        <td class="px-4 py-3 text-right space-x-2">
                            <a href="{{ route('tahsin.levels.show', $level) }}" class="text-blue-600">Detail</a>
                            <a href="{{ route('tahsin.levels.edit', $level) }}" class="text-amber-600">Edit</a>
                            <form action="{{ route('tahsin.levels.destroy', $level) }}" method="POST" class="inline" onsubmit="return confirm('Hapus level ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-6 text-center text-gray-500">
                            Belum ada level tahsin.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $levels->links() }}
    </div>
</div>
@endsection
