@extends('layouts.app')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between border-b border-slate-200/80 pb-5 dark:border-slate-800 gap-4">
        <div class="flex items-center space-x-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 dark:bg-indigo-950/30 dark:text-indigo-400">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
            </div>
            <div>
                <h2 class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white">Setoran Tahfizh</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400">Riwayat setoran hafalan santri.</p>
            </div>
        </div>
        @if (auth()->user()->hasRole(['super_admin', 'admin', 'teacher']))
            <div>
                <a href="{{ route('tahfizh.hafalan-records.create') }}"
                   class="inline-flex items-center space-x-2 rounded-full bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700 shadow-sm transition-all focus:outline-none focus:ring-4 focus:ring-indigo-500/20 active:scale-[0.98]">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Tambah Setoran</span>
                </a>
            </div>
        @endif
    </div>

    {{-- Filter Form --}}
    <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <h3 class="text-sm font-bold text-slate-700 dark:text-slate-350 mb-4 uppercase tracking-wider">Filter Pencarian</h3>
        <form method="GET" action="{{ route('tahfizh.hafalan-records.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6">
                <div>
                    <label class="mb-1.5 block text-xs font-bold text-slate-500 dark:text-slate-450 uppercase tracking-wider">Kelas</label>
                    <select name="class_room_id" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500">
                        <option value="">Semua Kelas</option>
                        @foreach ($classRooms as $classRoom)
                            <option value="{{ $classRoom->id }}" @selected(request('class_room_id') == $classRoom->id)>
                                {{ $classRoom->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-1.5 block text-xs font-bold text-slate-500 dark:text-slate-455 uppercase tracking-wider">Santri</label>
                    <select name="student_id" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500">
                        <option value="">Semua Santri</option>
                        @foreach ($students as $student)
                            <option value="{{ $student->id }}" @selected(request('student_id') == $student->id)>
                                {{ $student->full_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-1.5 block text-xs font-bold text-slate-500 dark:text-slate-455 uppercase tracking-wider">Guru</label>
                    <select name="teacher_id" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500">
                        <option value="">Semua Guru</option>
                        @foreach ($teachers as $teacher)
                            <option value="{{ $teacher->id }}" @selected(request('teacher_id') == $teacher->id)>
                                {{ $teacher->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-1.5 block text-xs font-bold text-slate-500 dark:text-slate-455 uppercase tracking-wider">Status</label>
                    <select name="status" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500">
                        <option value="">Semua Status</option>
                        @foreach ($statuses as $status)
                            <option value="{{ $status }}" @selected(request('status') === $status)>
                                {{ strtoupper(str_replace('_', ' ', $status)) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-1.5 block text-xs font-bold text-slate-500 dark:text-slate-455 uppercase tracking-wider">Dari Tanggal</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}"
                           class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500">
                </div>

                <div>
                    <label class="mb-1.5 block text-xs font-bold text-slate-500 dark:text-slate-455 uppercase tracking-wider">Sampai Tanggal</label>
                    <input type="date" name="date_until" value="{{ request('date_until') }}"
                           class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500">
                </div>
            </div>

            <div class="flex items-center justify-end space-x-2 pt-2 border-t border-slate-100 dark:border-slate-800/60">
                <a href="{{ route('tahfizh.hafalan-records.index') }}"
                   class="inline-flex items-center space-x-1.5 rounded-full border border-slate-200 px-4 py-2 text-xs font-bold text-slate-600 hover:bg-slate-50 dark:border-slate-800 dark:text-slate-400 dark:hover:bg-slate-800 transition-all focus:outline-none">
                    Reset
                </a>
                <button type="submit"
                        class="inline-flex items-center space-x-1.5 rounded-full bg-slate-900 px-4 py-2 text-xs font-bold text-white hover:bg-slate-800 dark:bg-white dark:text-slate-900 dark:hover:bg-slate-100 transition-all focus:outline-none">
                    Filter
                </button>
            </div>
        </form>
    </div>

    {{-- Table Card --}}
    <div class="rounded-2xl border border-slate-200/80 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50/40 dark:bg-slate-850/40 border-b border-slate-200/80 dark:border-slate-800">
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Tanggal</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Santri</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Guru Penerima</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Rentang Hafalan</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Total Baris</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 text-center w-28">Status</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 text-center w-36">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse ($records as $record)
                        <tr class="hover:bg-slate-50/40 dark:hover:bg-slate-850/10 transition-all">
                            <td class="px-6 py-4 text-slate-600 dark:text-slate-400 font-semibold">
                                {{ $record->record_date?->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-800 dark:text-slate-200">{{ $record->student?->full_name }}</div>
                                <div class="text-xs font-medium text-slate-400 mt-0.5">{{ $record->student?->classRoom?->name ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 text-slate-700 dark:text-slate-350 font-semibold">
                                {{ $record->teacher?->name ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-slate-600 dark:text-slate-400 font-medium">
                                Hlm {{ $record->start_page }}:{{ $record->start_line }}
                                <span class="mx-1 text-slate-300 dark:text-slate-700">—</span>
                                Hlm {{ $record->end_page }}:{{ $record->end_line }}
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-800 dark:text-slate-200">
                                {{ $record->total_lines }} baris
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $statusClass = match(strtolower($record->status)) {
                                        'lancar', 'lunas', 'met', 'tahsin_lancar' => 'bg-emerald-50 text-emerald-700 border-emerald-150 dark:bg-emerald-950/20 dark:text-emerald-400 dark:border-emerald-900/50',
                                        'sedang', 'behind', 'tahsin_sedang' => 'bg-amber-50 text-amber-700 border-amber-150 dark:bg-amber-950/20 dark:text-amber-400 dark:border-amber-900/50',
                                        default => 'bg-rose-50 text-rose-700 border-rose-150 dark:bg-rose-950/20 dark:text-rose-400 dark:border-rose-900/50',
                                    };
                                @endphp
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[10px] font-bold border {{ $statusClass }}">
                                    {{ strtoupper(str_replace('_', ' ', $record->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="inline-flex items-center space-x-1.5">
                                    {{-- Detail --}}
                                    <a href="{{ route('tahfizh.hafalan-records.show', $record) }}" 
                                       class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 text-slate-500 hover:bg-slate-50 dark:border-slate-800 dark:text-slate-400 dark:hover:bg-slate-800 transition-all" 
                                       title="Detail">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>

                                    @if (auth()->user()->hasRole(['super_admin', 'admin']) || (auth()->user()->hasRole('teacher') && $record->teacher_id === auth()->id()))
                                        {{-- Edit --}}
                                        <a href="{{ route('tahfizh.hafalan-records.edit', $record) }}" 
                                           class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 text-indigo-600 hover:bg-indigo-50/30 dark:border-slate-800 dark:text-indigo-400 dark:hover:bg-indigo-950/20 transition-all" 
                                           title="Edit">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </button>

                                        {{-- Hapus --}}
                                        <form method="POST" action="{{ route('tahfizh.hafalan-records.destroy', $record) }}"
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus data setoran ini?')">
                                            @csrf 
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 text-rose-600 hover:bg-rose-50/30 dark:border-slate-800 dark:text-rose-450 dark:hover:bg-rose-950/20 transition-all" 
                                                    title="Hapus">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-sm text-slate-450 dark:text-slate-500">Belum ada data setoran terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    @if($records->hasPages())
        <div class="pt-4">
            {{ $records->links() }}
        </div>
    @endif

</div>
@endsection

