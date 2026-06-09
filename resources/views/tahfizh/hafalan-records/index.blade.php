@extends('layouts.app')

@section('content')
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold">Setoran Tahfizh</h2>
            <p class="text-sm text-slate-500">Riwayat setoran hafalan santri.</p>
        </div>

        @if (auth()->user()->hasRole(['super_admin', 'admin', 'teacher']))
            <a href="{{ route('tahfizh.hafalan-records.create') }}"
               class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">
                Tambah Setoran
            </a>
        @endif
    </div>

    <form method="GET" action="{{ route('tahfizh.hafalan-records.index') }}"
          class="mb-6 grid gap-4 rounded-2xl bg-white p-4 shadow-sm md:grid-cols-6">
        <div>
            <label class="mb-1 block text-sm font-semibold">Kelas</label>
            <select name="class_room_id" class="w-full rounded-lg border border-slate-300 px-3 py-2">
                <option value="">Semua</option>
                @foreach ($classRooms as $classRoom)
                    <option value="{{ $classRoom->id }}" @selected(request('class_room_id') == $classRoom->id)>
                        {{ $classRoom->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="mb-1 block text-sm font-semibold">Santri</label>
            <select name="student_id" class="w-full rounded-lg border border-slate-300 px-3 py-2">
                <option value="">Semua</option>
                @foreach ($students as $student)
                    <option value="{{ $student->id }}" @selected(request('student_id') == $student->id)>
                        {{ $student->full_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="mb-1 block text-sm font-semibold">Guru</label>
            <select name="teacher_id" class="w-full rounded-lg border border-slate-300 px-3 py-2">
                <option value="">Semua</option>
                @foreach ($teachers as $teacher)
                    <option value="{{ $teacher->id }}" @selected(request('teacher_id') == $teacher->id)>
                        {{ $teacher->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="mb-1 block text-sm font-semibold">Status</label>
            <select name="status" class="w-full rounded-lg border border-slate-300 px-3 py-2">
                <option value="">Semua</option>
                @foreach ($statuses as $status)
                    <option value="{{ $status }}" @selected(request('status') === $status)>
                        {{ strtoupper(str_replace('_', ' ', $status)) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="mb-1 block text-sm font-semibold">Dari</label>
            <input type="date" name="date_from" value="{{ request('date_from') }}"
                   class="w-full rounded-lg border border-slate-300 px-3 py-2">
        </div>

        <div>
            <label class="mb-1 block text-sm font-semibold">Sampai</label>
            <input type="date" name="date_until" value="{{ request('date_until') }}"
                   class="w-full rounded-lg border border-slate-300 px-3 py-2">
        </div>

        <div class="md:col-span-6">
            <button type="submit"
                    class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">
                Filter
            </button>

            <a href="{{ route('tahfizh.hafalan-records.index') }}"
               class="ml-2 rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                Reset
            </a>
        </div>
    </form>

    <div class="overflow-hidden rounded-2xl bg-white shadow-sm">
        <table class="w-full border-collapse text-left text-sm">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-4 py-3">Tanggal</th>
                    <th class="px-4 py-3">Santri</th>
                    <th class="px-4 py-3">Guru</th>
                    <th class="px-4 py-3">Rentang</th>
                    <th class="px-4 py-3">Total</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($records as $record)
                    <tr class="border-t">
                        <td class="px-4 py-3">
                            {{ $record->record_date?->format('d/m/Y') }}
                        </td>
                        <td class="px-4 py-3">
                            <div class="font-semibold">{{ $record->student?->full_name }}</div>
                            <div class="text-xs text-slate-500">{{ $record->student?->classRoom?->name ?? '-' }}</div>
                        </td>
                        <td class="px-4 py-3">{{ $record->teacher?->name ?? '-' }}</td>
                        <td class="px-4 py-3">
                            Hlm {{ $record->start_page }}:{{ $record->start_line }}
                            —
                            Hlm {{ $record->end_page }}:{{ $record->end_line }}
                        </td>
                        <td class="px-4 py-3">{{ $record->total_lines }} baris</td>
                        <td class="px-4 py-3">
                            {{ strtoupper(str_replace('_', ' ', $record->status)) }}
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex justify-end gap-2 font-semibold">
                                <a href="{{ route('tahfizh.hafalan-records.show', $record) }}"
                                   class="text-slate-700 hover:underline">
                                    Lihat
                                </a>

                                @if (auth()->user()->hasRole(['super_admin', 'admin']) || (auth()->user()->hasRole('teacher') && $record->teacher_id === auth()->id()))
                                    <a href="{{ route('tahfizh.hafalan-records.edit', $record) }}"
                                       class="text-blue-700 hover:underline">
                                        Edit
                                    </a>

                                    <form method="POST"
                                          action="{{ route('tahfizh.hafalan-records.destroy', $record) }}"
                                          onsubmit="return confirm('Hapus setoran ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-700 hover:underline">
                                            Hapus
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-6 text-center text-slate-500">
                            Belum ada setoran.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $records->links() }}
    </div>
@endsection
