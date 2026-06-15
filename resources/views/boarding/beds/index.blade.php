@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between border-b border-slate-200 pb-4 dark:border-slate-800">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Daftar Tempat Tidur (Ranjang)</h1>
            <p class="text-sm text-slate-500">Daftar semua ranjang individual beserta status keterisiannya.</p>
        </div>
        @if(auth()->user()->isSuperAdmin() || auth()->user()->isAdmin())
            <a href="{{ route('boarding.beds.create') }}" class="rounded-xl bg-gradient-to-r from-emerald-500 to-teal-600 px-4 py-2 text-sm font-semibold text-white shadow-md shadow-emerald-200 hover:opacity-90 transition dark:shadow-none">
                + Tambah Ranjang
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
                        <th class="py-3 px-4">Kode Ranjang</th>
                        <th class="py-3 px-4">Kamar</th>
                        <th class="py-3 px-4">Asrama</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 text-sm text-slate-700 dark:divide-slate-800 dark:text-slate-350">
                    @forelse($beds as $bed)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-950/20">
                            <td class="py-3.5 px-4 font-semibold text-slate-800 dark:text-white">
                                <a href="{{ route('boarding.beds.show', $bed->id) }}" class="hover:text-emerald-500 transition">
                                    {{ $bed->code }}
                                </a>
                                @if($bed->description)
                                    <span class="block text-xs font-normal text-slate-400 mt-0.5">{{ $bed->description }}</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 font-medium">
                                <a href="{{ route('boarding.rooms.show', $bed->room->id) }}" class="hover:text-emerald-500 transition">
                                    {{ $bed->room->name }}
                                </a>
                            </td>
                            <td class="py-3.5 px-4 font-medium text-slate-500 dark:text-slate-450">
                                <a href="{{ route('boarding.dormitories.show', $bed->room->dormitory->id) }}" class="hover:text-emerald-500 transition">
                                    {{ $bed->room->dormitory->name }}
                                </a>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold uppercase
                                    @if($bed->status === 'available') bg-green-50 text-green-700 dark:bg-green-950/30 dark:text-green-455
                                    @elseif($bed->status === 'occupied') bg-blue-50 text-blue-700 dark:bg-blue-950/30 dark:text-blue-455
                                    @elseif($bed->status === 'maintenance') bg-amber-50 text-amber-700 dark:bg-amber-950/30 dark:text-amber-455
                                    @else bg-rose-50 text-rose-700 dark:bg-rose-950/30 dark:text-rose-455 @endif">
                                    {{ $bed->status === 'available' ? 'Kosong' : ($bed->status === 'occupied' ? 'Terisi' : ($bed->status === 'maintenance' ? 'Perbaikan' : 'Nonaktif')) }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('boarding.beds.show', $bed->id) }}" class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-bold hover:bg-slate-50 dark:border-slate-800 dark:hover:bg-slate-950">Detail</a>
                                    @if(auth()->user()->isSuperAdmin() || auth()->user()->isAdmin())
                                        <a href="{{ route('boarding.beds.edit', $bed->id) }}" class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-bold text-amber-600 hover:bg-slate-50 dark:border-slate-800 dark:hover:bg-slate-950">Edit</a>
                                        <form action="{{ route('boarding.beds.destroy', $bed->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus ranjang ini?');" class="inline">
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
                            <td colspan="5" class="py-8 text-center text-slate-400">Belum ada data ranjang yang ditambahkan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $beds->links() }}
        </div>
    </div>
</div>
@endsection
