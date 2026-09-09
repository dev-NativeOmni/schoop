@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Profil Tahsin Santri</h1>
        <p class="text-sm text-gray-600">Monitoring level dan status tahsin santri.</p>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-lg bg-green-50 px-4 py-3 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left">Santri</th>
                    <th class="px-4 py-3 text-left">Kelas</th>
                    <th class="px-4 py-3 text-left">Level</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-left">Guru</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($students as $student)
                    <tr>
                        <td class="px-4 py-3">{{ $student->nama_lengkap ?? $student->name ?? 'Santri #' . $student->id }}</td>
                        <td class="px-4 py-3">{{ $student->classRoom?->name ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $student->tahsinProfile?->currentLevel?->name ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $student->tahsinProfile?->status ?? 'not_started' }}</td>
                        <td class="px-4 py-3">{{ $student->tahsinProfile?->assignedTeacher?->name ?? '-' }}</td>
                        <td class="px-4 py-3 text-right space-x-2">
                            <a href="{{ route('tahsin.profiles.show', $student) }}" class="text-blue-600">Detail</a>
                            <a href="{{ route('tahsin.profiles.edit', $student) }}" class="text-amber-600">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-gray-500">Belum ada santri.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>

    <div class="mt-4">{{ $students->links() }}</div>
</div>
@endsection
