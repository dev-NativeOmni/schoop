@extends('layouts.app')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Presensi Anak</h1>
    <p class="text-sm text-gray-600">Pantau kehadiran anak.</p>
</div>

@if($students->isEmpty())
    <div class="rounded-xl bg-white p-6 text-gray-600 shadow">
        Belum ada data anak yang terhubung. Hubungi admin sekolah.
    </div>
@else
    <form method="GET" action="{{ route('portal.parent.attendance') }}" class="mb-6 grid grid-cols-1 gap-4 rounded-xl bg-white p-4 shadow md:grid-cols-4">
        <div>
            <label class="block text-sm font-medium text-gray-700">Anak</label>
            <select name="student_id" class="mt-1 w-full rounded-lg border-gray-300">
                @foreach($students as $student)
                    <option value="{{ $student->id }}" @selected($selectedStudent?->id === $student->id)>
                        {{ $student->full_name ?? $student->nama_lengkap ?? $student->name ?? 'Santri #' . $student->id }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
            <input type="date" name="start_date" value="{{ $snapshot['period']['start_date'] ?? now()->startOfMonth()->toDateString() }}" class="mt-1 w-full rounded-lg border-gray-300">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Tanggal Selesai</label>
            <input type="date" name="end_date" value="{{ $snapshot['period']['end_date'] ?? now()->toDateString() }}" class="mt-1 w-full rounded-lg border-gray-300">
        </div>

        <div class="flex items-end">
            <button class="rounded-lg bg-blue-600 px-4 py-2 text-white">
                Filter
            </button>
        </div>
    </form>

    @if($snapshot)
        <div class="mb-6 grid grid-cols-2 gap-4 md:grid-cols-5">
            <div class="rounded-xl bg-white p-4 shadow">
                <div class="text-sm text-gray-500">Hadir</div>
                <div class="text-2xl font-bold text-green-700">{{ $snapshot['summary']['present'] }}</div>
            </div>
            <div class="rounded-xl bg-white p-4 shadow">
                <div class="text-sm text-gray-500">Telat</div>
                <div class="text-2xl font-bold text-amber-700">{{ $snapshot['summary']['late'] }}</div>
            </div>
            <div class="rounded-xl bg-white p-4 shadow">
                <div class="text-sm text-gray-500">Sakit</div>
                <div class="text-2xl font-bold text-blue-700">{{ $snapshot['summary']['sick'] }}</div>
            </div>
            <div class="rounded-xl bg-white p-4 shadow">
                <div class="text-sm text-gray-500">Izin</div>
                <div class="text-2xl font-bold text-purple-700">{{ $snapshot['summary']['permission'] }}</div>
            </div>
            <div class="rounded-xl bg-white p-4 shadow">
                <div class="text-sm text-gray-500">Alpa</div>
                <div class="text-2xl font-bold text-red-700">{{ $snapshot['summary']['absent'] }}</div>
            </div>
        </div>

        <div class="overflow-hidden rounded-xl bg-white shadow">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left">Tanggal</th>
                        <th class="px-4 py-3 text-left">Session</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-left">Masuk</th>
                        <th class="px-4 py-3 text-left">Pulang</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($snapshot['records'] as $record)
                        <tr>
                            <td class="px-4 py-3">{{ $record->attendance_date?->format('d M Y') }}</td>
                            <td class="px-4 py-3">{{ $record->session?->name ?? '-' }}</td>
                            <td class="px-4 py-3">{{ ucfirst($record->status) }}</td>
                            <td class="px-4 py-3">{{ $record->check_in_at?->format('H:i') ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $record->check_out_at?->format('H:i') ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-gray-500">
                                Belum ada data presensi.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif
@endif
@endsection
