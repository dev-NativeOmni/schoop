@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col border-b border-slate-200 pb-4 dark:border-slate-800 gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Log Kedisiplinan & Prestasi</h1>
            <p class="text-sm text-slate-500">Kelola catatan pelanggaran tata tertib, peringatan, atau prestasi santri asrama.</p>
        </div>
        <a href="{{ route('boarding.discipline-logs.create') }}" class="rounded-xl bg-gradient-to-r from-emerald-500 to-teal-600 px-4 py-2 text-sm font-semibold text-white shadow-md shadow-emerald-200 hover:opacity-90 transition dark:shadow-none">
            + Tambah Log Kedisiplinan
        </a>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
        <div class="rounded-xl bg-emerald-50 p-4 text-sm text-emerald-800 dark:bg-emerald-950/30 dark:text-emerald-400">
            {{ session('success') }}
        </div>
    @endif

    <!-- Filter Form -->
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <form action="{{ route('boarding.discipline-logs.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
            <!-- Search Text -->
            <div>
                <label for="q" class="block text-xs font-bold uppercase text-slate-400 dark:text-slate-500 mb-1">Cari Santri</label>
                <input type="text" name="q" id="q" value="{{ request('q') }}" placeholder="Nama / NIS..." class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-800 dark:bg-slate-950 dark:text-white">
            </div>

            <!-- Type Filter -->
            <div>
                <label for="type" class="block text-xs font-bold uppercase text-slate-400 dark:text-slate-500 mb-1">Jenis Log</label>
                <select name="type" id="type" class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-800 dark:bg-slate-950 dark:text-white">
                    <option value="">Semua Jenis</option>
                    <option value="violation" {{ request('type') === 'violation' ? 'selected' : '' }}>Pelanggaran (Violation)</option>
                    <option value="warning" {{ request('type') === 'warning' ? 'selected' : '' }}>Peringatan (Warning)</option>
                    <option value="achievement" {{ request('type') === 'achievement' ? 'selected' : '' }}>Prestasi (Achievement)</option>
                    <option value="note" {{ request('type') === 'note' ? 'selected' : '' }}>Catatan Umum (Note)</option>
                </select>
            </div>

            <!-- Buttons -->
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 rounded-xl bg-slate-800 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700 transition dark:bg-slate-755 dark:hover:bg-slate-700">Filter</button>
                @if(request()->anyFilled(['q', 'type']))
                    <a href="{{ route('boarding.discipline-logs.index') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-350 dark:hover:bg-slate-950">Reset</a>
                @endif
            </div>
        </form>
    </div>

    <!-- Table List -->
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 text-xs font-bold uppercase text-slate-400 dark:border-slate-800">
                        <th class="py-3 px-4">Nama Santri</th>
                        <th class="py-3 px-4">Kategori & Deskripsi</th>
                        <th class="py-3 px-4">Jenis</th>
                        <th class="py-3 px-4">Poin</th>
                        <th class="py-3 px-4">Tanggal Dicatat</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 text-sm text-slate-700 dark:divide-slate-800 dark:text-slate-350">
                    @forelse($disciplineLogs as $log)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-950/20">
                            <td class="py-3.5 px-4 font-semibold text-slate-800 dark:text-white">
                                <a href="{{ route('boarding.discipline-logs.show', $log->id) }}" class="hover:text-emerald-500 transition">
                                    {{ $log->student->full_name }}
                                </a>
                                <span class="block text-xs font-normal text-slate-400 mt-0.5">NIS: {{ $log->student->student_number ?? '-' }}</span>
                            </td>
                            <td class="py-3.5 px-4 text-slate-750 dark:text-slate-300">
                                @if($log->category)
                                    <span class="inline-flex items-center rounded bg-slate-100 px-1.5 py-0.5 text-2xs font-semibold uppercase text-slate-800 mr-1 dark:bg-slate-800 dark:text-slate-300">
                                        {{ $log->category }}
                                    </span>
                                @endif
                                {{ Str::limit($log->description, 50) }}
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold uppercase
                                    @if($log->type === 'violation') bg-rose-50 text-rose-700 dark:bg-rose-950/30 dark:text-rose-455
                                    @elseif($log->type === 'warning') bg-amber-50 text-amber-700 dark:bg-amber-950/30 dark:text-amber-455
                                    @elseif($log->type === 'achievement') bg-emerald-50 text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-455
                                    @else bg-slate-50 text-slate-700 dark:bg-slate-950/30 dark:text-slate-455 @endif">
                                    @if($log->type === 'violation') Pelanggaran
                                    @elseif($log->type === 'warning') Peringatan
                                    @elseif($log->type === 'achievement') Prestasi
                                    @else Catatan
                                    @endif
                                </span>
                            </td>
                            <td class="py-3.5 px-4 font-bold
                                @if($log->points > 0) text-emerald-555
                                @elseif($log->points < 0) text-rose-555
                                @else text-slate-500 @endif">
                                {{ $log->points > 0 ? '+' : '' }}{{ $log->points }} Poin
                            </td>
                            <td class="py-3.5 px-4 font-medium text-slate-500 dark:text-slate-455">
                                {{ \Carbon\Carbon::parse($log->logged_at)->format('d M Y H:i') }}
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <a href="{{ route('boarding.discipline-logs.show', $log->id) }}" class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-bold hover:bg-slate-50 dark:border-slate-800 dark:hover:bg-slate-950">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400">Belum ada catatan kedisiplinan yang ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $disciplineLogs->links() }}
        </div>
    </div>
</div>
@endsection
