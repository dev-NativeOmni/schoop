@extends('layouts.app')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">{{ $session->name }}</h1>
        <p class="text-sm text-gray-600">
            {{ $session->attendance_date?->format('d M Y') }} · {{ ucfirst($session->status) }}
        </p>
    </div>

    <a href="{{ route('attendance.scanner.index') }}" class="rounded-lg bg-blue-600 px-4 py-2 text-white">
        Buka Scanner
    </a>
</div>

<div class="overflow-hidden rounded-xl bg-white shadow">
    <table class="min-w-full text-sm">
        <thead class="bg-gray-50 text-gray-700">
            <tr>
                <th class="px-4 py-3 text-left">Santri</th>
                <th class="px-4 py-3 text-left">Status</th>
                <th class="px-4 py-3 text-left">Check In</th>
                <th class="px-4 py-3 text-left">Check Out</th>
                <th class="px-4 py-3 text-left">Source</th>
                <th class="px-4 py-3 text-left">Scanner</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($session->records as $record)
                <tr>
                    <td class="px-4 py-3">
                        {{ $record->student?->full_name ?? $record->student?->nama_lengkap ?? $record->student?->name ?? '-' }}
                    </td>
                    <td class="px-4 py-3">{{ ucfirst($record->status) }}</td>
                    <td class="px-4 py-3">{{ $record->check_in_at?->format('H:i:s') ?? '-' }}</td>
                    <td class="px-4 py-3">{{ $record->check_out_at?->format('H:i:s') ?? '-' }}</td>
                    <td class="px-4 py-3">{{ ucfirst($record->source) }}</td>
                    <td class="px-4 py-3">{{ $record->scanner?->name ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-6 text-center text-gray-500">
                        Belum ada data presensi pada session ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
