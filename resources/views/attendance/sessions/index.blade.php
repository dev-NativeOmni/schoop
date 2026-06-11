@extends('layouts.app')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Session Presensi</h1>
        <p class="text-sm text-gray-600">Kelola session presensi harian.</p>
    </div>

    <a href="{{ route('attendance.sessions.create') }}"
       class="rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">
        Buat Session
    </a>
</div>

@if(session('success'))
    <div class="mb-4 rounded-lg bg-green-50 px-4 py-3 text-green-700">
        {{ session('success') }}
    </div>
@endif

<div class="overflow-hidden rounded-xl bg-white shadow">
    <table class="min-w-full text-sm">
        <thead class="bg-gray-50 text-gray-700">
            <tr>
                <th class="px-4 py-3 text-left">Nama</th>
                <th class="px-4 py-3 text-left">Tanggal</th>
                <th class="px-4 py-3 text-left">Kelas</th>
                <th class="px-4 py-3 text-left">Status</th>
                <th class="px-4 py-3 text-right">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($sessions as $session)
                <tr>
                    <td class="px-4 py-3 font-medium text-gray-900">{{ $session->name }}</td>
                    <td class="px-4 py-3">{{ $session->attendance_date?->format('d M Y') }}</td>
                    <td class="px-4 py-3">{{ $session->classRoom?->name ?? '-' }}</td>
                    <td class="px-4 py-3">{{ ucfirst($session->status) }}</td>
                    <td class="space-x-2 px-4 py-3 text-right">
                        <a href="{{ route('attendance.sessions.show', $session) }}" class="text-blue-600 hover:underline">Detail</a>
                        <a href="{{ route('attendance.sessions.edit', $session) }}" class="text-amber-600 hover:underline">Edit</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-4 py-6 text-center text-gray-500">
                        Belum ada session presensi.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $sessions->links() }}
</div>
@endsection
