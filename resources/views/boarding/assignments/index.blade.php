@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col border-b border-slate-200 pb-4 dark:border-slate-800 gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Penempatan Santri (Asrama)</h1>
            <p class="text-sm text-slate-500">Kelola dan pantau data santri yang menempati asrama, kamar, dan ranjang.</p>
        </div>
        <a href="{{ route('boarding.assignments.create') }}" class="rounded-xl bg-gradient-to-r from-emerald-500 to-teal-600 px-4 py-2 text-sm font-semibold text-white shadow-md shadow-emerald-200 hover:opacity-90 transition dark:shadow-none">
            + Tambah Penempatan
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
        <form action="{{ route('boarding.assignments.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
            <!-- Search Text -->
            <div>
                <label for="q" class="block text-xs font-bold uppercase text-slate-400 dark:text-slate-500 mb-1">Cari Santri</label>
                <input type="text" name="q" id="q" value="{{ request('q') }}" placeholder="Nama / NIS..." class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-800 dark:bg-slate-950 dark:text-white">
            </div>

            <!-- Dormitory Filter -->
            <div>
                <label for="boarding_dormitory_id" class="block text-xs font-bold uppercase text-slate-400 dark:text-slate-500 mb-1">Asrama</label>
                <select name="boarding_dormitory_id" id="boarding_dormitory_id" class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-800 dark:bg-slate-950 dark:text-white">
                    <option value="">Semua Asrama</option>
                    @foreach($dormitories as $dorm)
                        <option value="{{ $dorm->id }}" {{ request('boarding_dormitory_id') == $dorm->id ? 'selected' : '' }}>
                            {{ $dorm->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Status Filter -->
            <div>
                <label for="status" class="block text-xs font-bold uppercase text-slate-400 dark:text-slate-500 mb-1">Status</label>
                <select name="status" id="status" class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-800 dark:bg-slate-950 dark:text-white">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="moved" {{ request('status') === 'moved' ? 'selected' : '' }}>Sudah Pindah</option>
                    <option value="ended" {{ request('status') === 'ended' ? 'selected' : '' }}>Selesai</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>

            <!-- Buttons -->
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 rounded-xl bg-slate-800 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700 transition dark:bg-slate-750 dark:hover:bg-slate-700">Filter</button>
                @if(request()->anyFilled(['q', 'boarding_dormitory_id', 'status']))
                    <a href="{{ route('boarding.assignments.index') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-350 dark:hover:bg-slate-950">Reset</a>
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
                        <th class="py-3 px-4">Penempatan</th>
                        <th class="py-3 px-4">Tanggal Mulai</th>
                        <th class="py-3 px-4">Tanggal Selesai</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 text-sm text-slate-700 dark:divide-slate-800 dark:text-slate-350">
                    @forelse($assignments as $assignment)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-950/20">
                            <td class="py-3.5 px-4 font-semibold text-slate-800 dark:text-white">
                                <span class="block hover:text-emerald-500 transition cursor-pointer" onclick="window.location='{{ route('boarding.assignments.show', $assignment->id) }}'">
                                    {{ $assignment->student->full_name }}
                                </span>
                                <span class="block text-xs font-normal text-slate-400 mt-0.5">NIS: {{ $assignment->student->student_number ?? '-' }}</span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="block font-semibold text-slate-700 dark:text-slate-300">Asrama: {{ $assignment->dormitory->name }}</span>
                                <span class="block text-xs text-slate-500">Kamar: {{ $assignment->room->name }} | Ranjang: {{ $assignment->bed ? $assignment->bed->code : 'Belum Ditentukan' }}</span>
                            </td>
                            <td class="py-3.5 px-4 font-medium">
                                {{ \Carbon\Carbon::parse($assignment->start_date)->format('d M Y') }}
                            </td>
                            <td class="py-3.5 px-4 font-medium">
                                {{ $assignment->end_date ? \Carbon\Carbon::parse($assignment->end_date)->format('d M Y') : '-' }}
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold
                                    @if($assignment->status === 'active') bg-green-50 text-green-700 dark:bg-green-950/30 dark:text-green-455
                                    @elseif($assignment->status === 'moved') bg-blue-50 text-blue-700 dark:bg-blue-950/30 dark:text-blue-455
                                    @elseif($assignment->status === 'ended') bg-slate-50 text-slate-700 dark:bg-slate-950/30 dark:text-slate-455
                                    @else bg-rose-50 text-rose-700 dark:bg-rose-950/30 dark:text-rose-455 @endif">
                                    @if($assignment->status === 'active') Aktif
                                    @elseif($assignment->status === 'moved') Pindah
                                    @elseif($assignment->status === 'ended') Selesai
                                    @else Dibatalkan
                                    @endif
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <a href="{{ route('boarding.assignments.show', $assignment->id) }}" class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-bold hover:bg-slate-50 dark:border-slate-800 dark:hover:bg-slate-950">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400">Belum ada data penempatan santri yang ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $assignments->links() }}
        </div>
    </div>
</div>
@endsection
