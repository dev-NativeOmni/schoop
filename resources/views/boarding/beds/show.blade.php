@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between border-b border-slate-200 pb-4 dark:border-slate-800">
        <div>
            <div class="flex items-center gap-2">
                <a href="{{ route('boarding.beds.index') }}" class="text-sm font-semibold text-emerald-500 hover:underline">Daftar Ranjang</a>
                <span class="text-slate-400">/</span>
                <span class="text-sm text-slate-500 dark:text-slate-400">Detail Ranjang</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white mt-1">Kode Ranjang: {{ $bed->code }}</h1>
            <p class="text-sm text-slate-500">Asrama: {{ $bed->room->dormitory->name }} | Kamar: {{ $bed->room->name }}</p>
        </div>
        <div class="flex items-center gap-2">
            @if(auth()->user()->isSuperAdmin() || auth()->user()->isAdmin())
                <a href="{{ route('boarding.beds.edit', $bed->id) }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-amber-600 hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:hover:bg-slate-950">
                    Edit Ranjang
                </a>
            @endif
            <a href="{{ route('boarding.beds.index') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-350 dark:hover:bg-slate-950">
                Kembali
            </a>
        </div>
    </div>

    <!-- Details Card -->
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 space-y-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 border-b border-slate-100 pb-4 dark:border-slate-800">
            <div>
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Asrama</span>
                <a href="{{ route('boarding.dormitories.show', $bed->room->dormitory->id) }}" class="text-sm font-semibold text-slate-800 dark:text-white hover:text-emerald-500 transition block mt-1">
                    {{ $bed->room->dormitory->name }}
                </a>
            </div>
            <div>
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Kamar</span>
                <a href="{{ route('boarding.rooms.show', $bed->room->id) }}" class="text-sm font-semibold text-slate-800 dark:text-white hover:text-emerald-500 transition block mt-1">
                    {{ $bed->room->name }}
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 border-b border-slate-100 pb-4 dark:border-slate-800">
            <div>
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Lantai Kamar</span>
                <span class="text-sm font-semibold text-slate-800 dark:text-white block mt-1">{{ $bed->room->floor ?? '-' }}</span>
            </div>
            <div>
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Status Ranjang</span>
                <div class="mt-1">
                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold uppercase
                        @if($bed->status === 'available') bg-green-50 text-green-700 dark:bg-green-950/30 dark:text-green-455
                        @elseif($bed->status === 'occupied') bg-blue-50 text-blue-700 dark:bg-blue-950/30 dark:text-blue-455
                        @elseif($bed->status === 'maintenance') bg-amber-50 text-amber-700 dark:bg-amber-950/30 dark:text-amber-455
                        @else bg-rose-50 text-rose-700 dark:bg-rose-950/30 dark:text-rose-455 @endif">
                        {{ $bed->status === 'available' ? 'Kosong / Tersedia' : ($bed->status === 'occupied' ? 'Terisi / Ditempati' : ($bed->status === 'maintenance' ? 'Dalam Perbaikan' : 'Nonaktif')) }}
                    </span>
                </div>
            </div>
        </div>

        @if($bed->description)
            <div>
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Keterangan / Deskripsi</span>
                <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">{{ $bed->description }}</p>
            </div>
        @endif
    </div>
</div>
@endsection
