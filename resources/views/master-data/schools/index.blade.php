@extends('layouts.app')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between border-b border-slate-200/80 pb-5 dark:border-slate-800 gap-4">
        <div class="flex items-center space-x-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 dark:bg-emerald-950/30 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/50">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
            <div>
                <h2 class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white">Data Sekolah</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400">Kelola data sekolah di platform.</p>
            </div>
        </div>
        <div>
            <a href="{{ route('master-data.schools.create') }}"
               class="btn-natural-primary text-xs">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                <span>Tambah Sekolah</span>
            </a>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="card-natural overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50/40 dark:bg-slate-850/40 border-b border-slate-200/80 dark:border-slate-800">
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Nama Sekolah</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Kode</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Email</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 text-center w-28">Status</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 text-center w-36">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse ($schools as $school)
                        <tr class="hover:bg-slate-50/40 dark:hover:bg-slate-850/10 transition-all">
                            <td class="px-6 py-4 font-bold text-slate-800 dark:text-slate-200">
                                {{ $school->name }}
                            </td>
                            <td class="px-6 py-4 text-slate-600 dark:text-slate-400 font-semibold">
                                {{ $school->code }}
                            </td>
                            <td class="px-6 py-4 text-slate-700 dark:text-slate-350">
                                {{ $school->email ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($school->is_active)
                                    <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-0.5 text-[10px] font-bold text-emerald-700 border border-emerald-150 dark:bg-emerald-950/20 dark:text-emerald-400 dark:border-emerald-900/50">Aktif</span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-slate-50 px-2.5 py-0.5 text-[10px] font-bold text-slate-500 border border-slate-150 dark:bg-slate-800/40 dark:text-slate-450 dark:border-slate-750">Nonaktif</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="inline-flex items-center space-x-1.5">
                                    {{-- Detail --}}
                                    <a href="{{ route('master-data.schools.show', $school) }}" 
                                       class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 text-slate-500 hover:bg-slate-50 dark:border-slate-800 dark:text-slate-400 dark:hover:bg-slate-800 transition-all" 
                                       title="Detail">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>

                                    {{-- Edit --}}
                                    <a href="{{ route('master-data.schools.edit', $school) }}" 
                                       class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 text-indigo-600 hover:bg-indigo-50/30 dark:border-slate-800 dark:text-indigo-400 dark:hover:bg-indigo-950/20 transition-all" 
                                       title="Edit">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </a>

                                    {{-- Hapus --}}
                                    <form method="POST" action="{{ route('master-data.schools.destroy', $school) }}"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus data sekolah ini?')">
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
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-sm text-slate-450 dark:text-slate-500">Belum ada data sekolah terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    @if($schools->hasPages())
        <div class="pt-4">
            {{ $schools->links() }}
        </div>
    @endif

</div>
@endsection

