@extends('layouts.app')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">QR Santri</h1>
        <p class="text-sm text-gray-600">Kelola QR presensi santri.</p>
    </div>

    <a href="{{ route('attendance.qr-cards.print') }}"
       target="_blank"
       class="rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">
        Cetak QR
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
                <th class="px-4 py-3 text-left">Santri</th>
                <th class="px-4 py-3 text-left">QR Aktif</th>
                <th class="px-4 py-3 text-left">Terakhir Dipakai</th>
                <th class="px-4 py-3 text-right">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($students as $student)
                <tr>
                    <td class="px-4 py-3 font-medium text-gray-900">
                        {{ $student->full_name ?? $student->nama_lengkap ?? $student->name ?? 'Santri #' . $student->id }}
                    </td>
                    <td class="px-4 py-3">
                        {{ $student->attendanceQrToken ? 'Ya' : 'Belum' }}
                    </td>
                    <td class="px-4 py-3">
                        {{ $student->attendanceQrToken?->last_used_at?->format('d M Y H:i') ?? '-' }}
                    </td>
                    <td class="px-4 py-3 text-right">
                        <form action="{{ route('attendance.qr-cards.rotate', $student) }}"
                              method="POST"
                              onsubmit="return confirm('Ganti QR santri ini? QR lama akan tidak aktif.')">
                            @csrf
                            <button class="text-red-600 hover:underline">
                                Rotate QR
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-4 py-6 text-center text-gray-500">
                        Belum ada data santri.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $students->links() }}
</div>
@endsection
