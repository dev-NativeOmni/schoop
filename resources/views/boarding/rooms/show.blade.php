@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-slate-200 pb-4 dark:border-slate-800">
        <div>
            <div class="flex items-center gap-2">
                <a href="{{ route('boarding.rooms.index') }}" class="text-sm font-semibold text-emerald-500 hover:underline">Daftar Kamar</a>
                <span class="text-slate-400">/</span>
                <span class="text-sm text-slate-500 dark:text-slate-400">Detail Kamar</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white mt-1">{{ $room->name }}</h1>
            <p class="text-sm text-slate-500">Asrama: 
                <a href="{{ route('boarding.dormitories.show', $room->dormitory->id) }}" class="font-semibold text-slate-700 dark:text-slate-300 hover:text-emerald-500 transition">
                    {{ $room->dormitory->name }}
                </a>
            </p>
        </div>
        <div class="flex items-center gap-2">
            @if(auth()->user()->isSuperAdmin() || auth()->user()->isAdmin())
                <a href="{{ route('boarding.rooms.edit', $room->id) }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-amber-600 hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:hover:bg-slate-950">
                    Edit Kamar
                </a>
            @endif
            <a href="{{ route('boarding.rooms.index') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-350 dark:hover:bg-slate-950">
                Kembali
            </a>
        </div>
    </div>

    <!-- Quick Stats Room -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Kapasitas Ranjang</span>
            <span class="text-2xl font-bold text-slate-800 dark:text-white mt-1 block">{{ $stats['capacity'] }} Ranjang</span>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Okupansi Aktif</span>
            <span class="text-2xl font-bold text-slate-800 dark:text-white mt-1 block">{{ $stats['occupied'] }} Terisi</span>
            <div class="w-full bg-slate-100 rounded-full h-1.5 mt-2 dark:bg-slate-800">
                <div class="bg-emerald-500 h-1.5 rounded-full" style="width: {{ $stats['occupancy_rate'] }}%"></div>
            </div>
            <span class="text-2xs text-slate-450 mt-1 block">{{ $stats['occupancy_rate'] }}% terisi</span>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Ranjang Tersedia</span>
            <span class="text-2xl font-bold text-slate-800 dark:text-white mt-1 block">{{ $stats['available'] }} Ranjang</span>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Pemeliharaan / Nonaktif</span>
            <span class="text-2xl font-bold text-slate-800 dark:text-white mt-1 block">{{ $stats['maintenance'] + $stats['inactive'] }} Ranjang</span>
        </div>
    </div>

    <!-- Description Card -->
    @if($room->description)
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200">Keterangan / Catatan Kamar</h3>
            <p class="text-sm text-slate-600 dark:text-slate-400 mt-2">{{ $room->description }}</p>
        </div>
    @endif

    <!-- Beds Section -->
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4 dark:border-slate-800 mb-6">
            <div>
                <h2 class="text-lg font-bold text-slate-800 dark:text-white">Daftar Tempat Tidur / Ranjang</h2>
                <p class="text-xs text-slate-500">Ranjang yang terdaftar di dalam kamar ini.</p>
            </div>
            @if(auth()->user()->isSuperAdmin() || auth()->user()->isAdmin())
                <a href="{{ route('boarding.beds.create', ['boarding_room_id' => $room->id]) }}" class="rounded-xl bg-gradient-to-r from-emerald-500 to-teal-600 px-4 py-2 text-xs font-semibold text-white shadow-md shadow-emerald-200 hover:opacity-90 transition dark:shadow-none">
                    + Tambah Ranjang
                </a>
            @endif
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @forelse($room->beds as $bed)
                <div class="rounded-xl border border-slate-150 p-4 shadow-2xs dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/20 hover:border-slate-300 dark:hover:border-slate-700 transition">
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-lg text-slate-800 dark:text-white">{{ $bed->code }}</span>
                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-semibold uppercase
                            @if($bed->status === 'available') bg-green-50 text-green-700 dark:bg-green-950/30 dark:text-green-455
                            @elseif($bed->status === 'occupied') bg-blue-50 text-blue-700 dark:bg-blue-950/30 dark:text-blue-455
                            @elseif($bed->status === 'maintenance') bg-amber-50 text-amber-700 dark:bg-amber-950/30 dark:text-amber-455
                            @else bg-rose-50 text-rose-700 dark:bg-rose-950/30 dark:text-rose-455 @endif">
                            {{ $bed->status === 'available' ? 'Kosong' : ($bed->status === 'occupied' ? 'Terisi' : ($bed->status === 'maintenance' ? 'Perbaikan' : 'Nonaktif')) }}
                        </span>
                    </div>
                    @if($bed->description)
                        <p class="text-xs text-slate-500 mt-2 line-clamp-2">{{ $bed->description }}</p>
                    @endif
                    <div class="mt-4 pt-3 border-t border-slate-200/60 dark:border-slate-800 flex justify-end gap-2">
                        <a href="{{ route('boarding.beds.show', $bed->id) }}" class="text-xs font-bold text-slate-600 dark:text-slate-400 hover:text-emerald-500 transition">Detail</a>
                        @if(auth()->user()->isSuperAdmin() || auth()->user()->isAdmin())
                            <span class="text-slate-300 dark:text-slate-800">|</span>
                            <a href="{{ route('boarding.beds.edit', $bed->id) }}" class="text-xs font-bold text-amber-600 hover:underline">Edit</a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full py-8 text-center text-slate-450">Belum ada ranjang terdaftar di kamar ini.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
