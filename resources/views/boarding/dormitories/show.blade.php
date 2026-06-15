@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-slate-200 pb-4 dark:border-slate-800">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">{{ $dormitory->name }}</h1>
            <p class="text-sm text-slate-500">Detail asrama, kamar-kamar, dan statistik okupansi.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('boarding.dormitories.index') }}" class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition dark:border-slate-800 dark:text-slate-355 dark:hover:bg-slate-950">Kembali</a>
            @if(auth()->user()->isSuperAdmin() || auth()->user()->isAdmin())
                <a href="{{ route('boarding.dormitories.edit', $dormitory->id) }}" class="rounded-xl bg-amber-500 px-4 py-2 text-sm font-semibold text-white shadow-md hover:bg-amber-600 transition">Edit Asrama</a>
            @endif
        </div>
    </div>

    <!-- Dormitory Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-1 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <h3 class="font-bold text-slate-800 dark:text-white mb-4">Informasi Asrama</h3>
            <div class="space-y-3 text-sm">
                <div>
                    <span class="text-slate-400 block text-xs">Nama Asrama</span>
                    <span class="font-semibold text-slate-700 dark:text-slate-200">{{ $dormitory->name }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block text-xs">Kategori Gender</span>
                    <span class="font-semibold text-slate-700 dark:text-slate-200 uppercase">{{ $dormitory->gender === 'male' ? 'Putra' : ($dormitory->gender === 'female' ? 'Putri' : 'Campuran') }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block text-xs">Keterangan</span>
                    <span class="text-slate-600 dark:text-slate-450">{{ $dormitory->description ?? '-' }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block text-xs">Status</span>
                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-semibold {{ $dormitory->is_active ? 'bg-green-50 text-green-700 dark:bg-green-950/30' : 'bg-rose-50 text-rose-700 dark:bg-rose-950/30' }}">
                        {{ $dormitory->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>
            </div>
        </div>

        <div class="md:col-span-2 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <h3 class="font-bold text-slate-800 dark:text-white mb-4">Statistik Ranjang</h3>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-center">
                <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-950">
                    <span class="text-xs text-slate-500 block">Total Kapasitas</span>
                    <span class="text-2xl font-extrabold text-slate-800 dark:text-white">{{ $stats['capacity'] }}</span>
                </div>
                <div class="p-4 rounded-xl bg-emerald-50/50 dark:bg-emerald-950/20">
                    <span class="text-xs text-slate-500 block">Terisi</span>
                    <span class="text-2xl font-extrabold text-emerald-600">{{ $stats['occupied'] }}</span>
                </div>
                <div class="p-4 rounded-xl bg-teal-50/50 dark:bg-teal-950/20">
                    <span class="text-xs text-slate-500 block">Kosong</span>
                    <span class="text-2xl font-extrabold text-teal-600">{{ $stats['available'] }}</span>
                </div>
                <div class="p-4 rounded-xl bg-amber-50/50 dark:bg-amber-950/20">
                    <span class="text-xs text-slate-500 block">Okupansi</span>
                    <span class="text-2xl font-extrabold text-amber-600">{{ $stats['occupancy_rate'] }}%</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Rooms List inside Dormitory -->
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold text-slate-800 dark:text-white">Daftar Kamar di Asrama Ini</h2>
            @if(auth()->user()->isSuperAdmin() || auth()->user()->isAdmin())
                <a href="{{ route('boarding.rooms.create', ['boarding_dormitory_id' => $dormitory->id]) }}" class="text-sm font-bold text-emerald-500 hover:underline">+ Tambah Kamar</a>
            @endif
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 text-xs font-bold uppercase text-slate-400 dark:border-slate-800">
                        <th class="py-3 px-4">Nama Kamar</th>
                        <th class="py-3 px-4">Lantai</th>
                        <th class="py-3 px-4">Okupansi Ranjang</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 text-sm text-slate-700 dark:divide-slate-800 dark:text-slate-350">
                    @forelse($dormitory->rooms as $room)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-950/20">
                            <td class="py-3 px-4 font-semibold text-slate-800 dark:text-white">
                                <a href="{{ route('boarding.rooms.show', $room->id) }}" class="hover:text-emerald-500 transition">{{ $room->name }}</a>
                            </td>
                            <td class="py-3 px-4">{{ $room->floor ?? '-' }}</td>
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-20 bg-slate-100 rounded-full h-1.5 dark:bg-slate-800">
                                        <div class="bg-emerald-500 h-1.5 rounded-full" style="width: {{ min(100, $room->stats['occupancy_rate']) }}%"></div>
                                    </div>
                                    <span class="text-xs font-bold">{{ $room->stats['occupied'] }} / {{ $room->stats['capacity'] }} ({{ $room->stats['occupancy_rate'] }}%)</span>
                                </div>
                            </td>
                            <td class="py-3 px-4">
                                <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-semibold {{ $room->is_active ? 'bg-green-50 text-green-700 dark:bg-green-950/30' : 'bg-rose-50 text-rose-700 dark:bg-rose-950/30' }}">
                                    {{ $room->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('boarding.rooms.show', $room->id) }}" class="rounded-lg border border-slate-200 px-2 py-1 text-xs hover:bg-slate-50 dark:border-slate-800 dark:hover:bg-slate-950">Detail</a>
                                    @if(auth()->user()->isSuperAdmin() || auth()->user()->isAdmin())
                                        <a href="{{ route('boarding.rooms.edit', $room->id) }}" class="rounded-lg border border-slate-200 px-2 py-1 text-xs text-amber-600 hover:bg-slate-50 dark:border-slate-800 dark:hover:bg-slate-950">Edit</a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-6 text-center text-slate-400">Belum ada kamar di asrama ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
