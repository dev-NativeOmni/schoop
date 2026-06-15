@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between border-b border-slate-200 pb-4 dark:border-slate-800">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Daftar Kamar</h1>
            <p class="text-sm text-slate-500">Kelola kamar-kamar yang berada di dalam asrama.</p>
        </div>
        @if(auth()->user()->isSuperAdmin() || auth()->user()->isAdmin())
            <a href="{{ route('boarding.rooms.create') }}" class="rounded-xl bg-gradient-to-r from-emerald-500 to-teal-600 px-4 py-2 text-sm font-semibold text-white shadow-md shadow-emerald-200 hover:opacity-90 transition dark:shadow-none">
                + Tambah Kamar
            </a>
        @endif
    </div>

    <!-- Alert Success -->
    @if(session('success'))
        <div class="rounded-xl bg-emerald-50 p-4 text-sm text-emerald-800 dark:bg-emerald-950/30 dark:text-emerald-400">
            {{ session('success') }}
        </div>
    @endif

    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 text-xs font-bold uppercase text-slate-400 dark:border-slate-800">
                        <th class="py-3 px-4">Nama Kamar</th>
                        <th class="py-3 px-4">Asrama</th>
                        <th class="py-3 px-4">Lantai</th>
                        <th class="py-3 px-4">Kapasitas Ranjang</th>
                        <th class="py-3 px-4">Okupansi</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 text-sm text-slate-700 dark:divide-slate-800 dark:text-slate-350">
                    @forelse($rooms as $room)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-950/20">
                            <td class="py-3.5 px-4 font-semibold text-slate-800 dark:text-white">
                                <a href="{{ route('boarding.rooms.show', $room->id) }}" class="hover:text-emerald-500 transition">
                                    {{ $room->name }}
                                </a>
                                @if($room->description)
                                    <span class="block text-xs font-normal text-slate-400 mt-0.5">{{ $room->description }}</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                <a href="{{ route('boarding.dormitories.show', $room->dormitory->id) }}" class="font-medium text-slate-700 dark:text-slate-300 hover:text-emerald-500 transition">
                                    {{ $room->dormitory->name }}
                                </a>
                            </td>
                            <td class="py-3.5 px-4 font-medium">
                                {{ $room->floor ?? '-' }}
                            </td>
                            <td class="py-3.5 px-4">
                                {{ $room->beds_count }} / {{ $room->capacity }} Ranjang
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-24 bg-slate-100 rounded-full h-2 dark:bg-slate-800">
                                        <div class="bg-emerald-500 h-2 rounded-full" style="width: {{ min(100, $room->stats['occupancy_rate']) }}%"></div>
                                    </div>
                                    <span class="text-xs font-bold">{{ $room->stats['occupied'] }} / {{ $room->stats['capacity'] }} ({{ $room->stats['occupancy_rate'] }}%)</span>
                                </div>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold
                                    @if($room->is_active) bg-green-50 text-green-700 dark:bg-green-950/30 dark:text-green-455
                                    @else bg-rose-50 text-rose-700 dark:bg-rose-950/30 dark:text-rose-455 @endif">
                                    {{ $room->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('boarding.rooms.show', $room->id) }}" class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-bold hover:bg-slate-50 dark:border-slate-800 dark:hover:bg-slate-950">Detail</a>
                                    @if(auth()->user()->isSuperAdmin() || auth()->user()->isAdmin())
                                        <a href="{{ route('boarding.rooms.edit', $room->id) }}" class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-bold text-amber-600 hover:bg-slate-50 dark:border-slate-800 dark:hover:bg-slate-950">Edit</a>
                                        <form action="{{ route('boarding.rooms.destroy', $room->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kamar ini?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-bold text-rose-600 hover:bg-slate-50 dark:border-slate-800 dark:hover:bg-slate-950">Hapus</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400">Belum ada data kamar yang ditambahkan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $rooms->links() }}
        </div>
    </div>
</div>
@endsection
