@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col border-b border-slate-200 pb-4 dark:border-slate-800 gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Daftar Asrama</h1>
            <p class="text-sm text-slate-500">Kelola asrama putra, putri, dan kapasitas tempat tidur.</p>
        </div>
        @if(auth()->user()->isSuperAdmin() || auth()->user()->isAdmin())
            <a href="{{ route('boarding.dormitories.create') }}" class="rounded-xl bg-gradient-to-r from-emerald-500 to-teal-600 px-4 py-2 text-sm font-semibold text-white shadow-md shadow-emerald-200 hover:opacity-90 transition dark:shadow-none">
                + Tambah Asrama
            </a>
        @endif
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 text-xs font-bold uppercase text-slate-400 dark:border-slate-800">
                        <th class="py-3 px-4">Nama Asrama</th>
                        <th class="py-3 px-4">Gender</th>
                        <th class="py-3 px-4">Jumlah Kamar</th>
                        <th class="py-3 px-4">Okupansi Ranjang</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 text-sm text-slate-700 dark:divide-slate-800 dark:text-slate-350">
                    @forelse($dormitories as $dormitory)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-950/20">
                            <td class="py-3.5 px-4 font-semibold text-slate-800 dark:text-white">
                                <a href="{{ route('boarding.dormitories.show', $dormitory->id) }}" class="hover:text-emerald-500 transition">
                                    {{ $dormitory->name }}
                                </a>
                                @if($dormitory->description)
                                    <span class="block text-xs font-normal text-slate-400 mt-0.5">{{ $dormitory->description }}</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium uppercase
                                    @if($dormitory->gender === 'male') bg-blue-50 text-blue-700 dark:bg-blue-950/30 dark:text-blue-455
                                    @elseif($dormitory->gender === 'female') bg-pink-50 text-pink-700 dark:bg-pink-950/30 dark:text-pink-455
                                    @else bg-slate-50 text-slate-700 dark:bg-slate-950/30 dark:text-slate-455 @endif">
                                    {{ $dormitory->gender === 'male' ? 'Putra' : ($dormitory->gender === 'female' ? 'Putri' : 'Campuran') }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 font-medium">{{ $dormitory->rooms_count }} Kamar</td>
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-24 bg-slate-100 rounded-full h-2 dark:bg-slate-800">
                                        <div class="bg-emerald-500 h-2 rounded-full" style="width: {{ min(100, $dormitory->stats['occupancy_rate']) }}%"></div>
                                    </div>
                                    <span class="text-xs font-bold">{{ $dormitory->stats['occupied'] }} / {{ $dormitory->stats['capacity'] }} ({{ $dormitory->stats['occupancy_rate'] }}%)</span>
                                </div>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold
                                    @if($dormitory->is_active) bg-green-50 text-green-700 dark:bg-green-950/30 dark:text-green-455
                                    @else bg-rose-50 text-rose-700 dark:bg-rose-950/30 dark:text-rose-455 @endif">
                                    {{ $dormitory->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('boarding.dormitories.show', $dormitory->id) }}" class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-bold hover:bg-slate-50 dark:border-slate-800 dark:hover:bg-slate-950">Detail</a>
                                    @if(auth()->user()->isSuperAdmin() || auth()->user()->isAdmin())
                                        <a href="{{ route('boarding.dormitories.edit', $dormitory->id) }}" class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-bold text-amber-600 hover:bg-slate-50 dark:border-slate-800 dark:hover:bg-slate-950">Edit</a>
                                        <form action="{{ route('boarding.dormitories.destroy', $dormitory->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus asrama ini beserta kamar dan ranjang di dalamnya?');" class="inline">
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
                            <td colspan="6" class="py-8 text-center text-slate-400">Belum ada data asrama yang ditambahkan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $dormitories->links() }}
        </div>
    </div>
</div>
@endsection
