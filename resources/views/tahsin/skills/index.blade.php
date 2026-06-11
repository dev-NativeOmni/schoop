@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Skill Tahsin</h1>
            <p class="text-sm text-gray-600">Kelola kompetensi penilaian tahsin.</p>
        </div>

        <a href="{{ route('tahsin.skills.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg">
            Tambah Skill
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-lg bg-green-50 px-4 py-3 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left">Skill</th>
                    <th class="px-4 py-3 text-left">Kode</th>
                    <th class="px-4 py-3 text-left">Level</th>
                    <th class="px-4 py-3 text-left">Aktif</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($skills as $skill)
                    <tr>
                        <td class="px-4 py-3">{{ $skill->name }}</td>
                        <td class="px-4 py-3">{{ $skill->code ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $skill->level?->name ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $skill->is_active ? 'Ya' : 'Tidak' }}</td>
                        <td class="px-4 py-3 text-right space-x-2">
                            <a href="{{ route('tahsin.skills.show', $skill) }}" class="text-blue-600">Detail</a>
                            <a href="{{ route('tahsin.skills.edit', $skill) }}" class="text-amber-600">Edit</a>
                            <form action="{{ route('tahsin.skills.destroy', $skill) }}" method="POST" class="inline" onsubmit="return confirm('Hapus skill ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-gray-500">Belum ada skill tahsin.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $skills->links() }}</div>
</div>
@endsection
