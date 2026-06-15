@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between border-b border-slate-200 pb-4 dark:border-slate-800">
        <div>
            <div class="flex items-center gap-2">
                <a href="{{ route('boarding.supervisors.index') }}" class="text-sm font-semibold text-emerald-500 hover:underline">Daftar Pembina</a>
                <span class="text-slate-400">/</span>
                <span class="text-sm text-slate-500 dark:text-slate-400">Detail Pembina</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white mt-1">{{ $supervisor->user->name }}</h1>
            <p class="text-sm text-slate-500">Email: {{ $supervisor->user->email }}</p>
        </div>
        <div class="flex items-center gap-2">
            @if(auth()->user()->isSuperAdmin() || auth()->user()->isAdmin())
                <a href="{{ route('boarding.supervisors.edit', $supervisor->id) }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-amber-600 hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:hover:bg-slate-950">
                    Edit Profil
                </a>
            @endif
            <a href="{{ route('boarding.supervisors.index') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-350 dark:hover:bg-slate-950">
                Kembali
            </a>
        </div>
    </div>

    <!-- Details Card -->
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 space-y-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 border-b border-slate-100 pb-4 dark:border-slate-800">
            <div>
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Nomor Telepon / WA</span>
                <span class="text-sm font-semibold text-slate-800 dark:text-white block mt-1">{{ $supervisor->phone ?? '-' }}</span>
            </div>
            <div>
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Status Profil</span>
                <div class="mt-1">
                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold
                        @if($supervisor->status === 'active') bg-green-50 text-green-700 dark:bg-green-950/30 dark:text-green-455
                        @else bg-rose-50 text-rose-700 dark:bg-rose-950/30 dark:text-rose-455 @endif">
                        {{ $supervisor->status === 'active' ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 border-b border-slate-100 pb-4 dark:border-slate-800">
            <div>
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Cakupan Asrama</span>
                <span class="text-sm font-semibold text-slate-800 dark:text-white block mt-1">
                    @if($supervisor->dormitory)
                        <a href="{{ route('boarding.dormitories.show', $supervisor->dormitory->id) }}" class="text-emerald-500 hover:underline">
                            {{ $supervisor->dormitory->name }}
                        </a>
                    @else
                        Semua Asrama (Supervisory Global)
                    @endif
                </span>
            </div>
            <div>
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Cakupan Kamar</span>
                <span class="text-sm font-semibold text-slate-800 dark:text-white block mt-1">
                    @if($supervisor->room)
                        <a href="{{ route('boarding.rooms.show', $supervisor->room->id) }}" class="text-emerald-500 hover:underline">
                            {{ $supervisor->room->name }}
                        </a>
                    @else
                        Semua Kamar di Asrama Terkait
                    @endif
                </span>
            </div>
        </div>

        @if($supervisor->notes)
            <div>
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Catatan / Keterangan</span>
                <p class="text-sm text-slate-600 dark:text-slate-400 mt-1 whitespace-pre-wrap">{{ $supervisor->notes }}</p>
            </div>
        @endif
    </div>
</div>
@endsection
