@extends('layouts.app')

@section('title', 'Template Aktivitas Mutabaah')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-slate-200/80 pb-5 dark:border-slate-800">
        <div>
            <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-50 text-[11px] font-bold text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/40 mb-1">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                Konfigurasi Indikator Ibadah
            </div>
            <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-slate-900 dark:text-white">Template Aktivitas Mutabaah</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400">Kelola dan atur template aktivitas ibadah serta pembiasaan karakter harian santri.</p>
        </div>
        <div>
            @if(auth()->user()->hasRole(['super_admin', 'admin']))
                <a href="{{ route('mutabaah.activities.create') }}" class="btn-natural-primary text-xs px-4 py-2">
                    ➕ Tambah Aktivitas
                </a>
            @endif
        </div>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="card-natural p-4 bg-emerald-50/80 border-emerald-200 text-emerald-800 dark:bg-emerald-950/30 dark:border-emerald-900/50 dark:text-emerald-300 flex items-center gap-3">
            <div class="w-8 h-8 rounded-full bg-emerald-100 dark:bg-emerald-900/50 flex items-center justify-center text-emerald-600 shrink-0">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <span class="text-xs font-bold">{{ session('success') }}</span>
        </div>
    @endif

    {{-- Filters --}}
    <div class="card-natural p-5">
        <form method="GET" class="grid gap-4 sm:grid-cols-12 items-end">
            <div class="sm:col-span-4">
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">Kategori</label>
                <select name="category_id" 
                        class="w-full rounded-xl border border-slate-200/80 bg-slate-50/50 px-3.5 py-2 text-xs font-semibold text-slate-950 focus:border-emerald-500 focus:bg-white focus:outline-none dark:border-slate-800 dark:bg-slate-950 dark:text-white">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" @selected(request('category_id') == $cat->id)>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="sm:col-span-5">
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">Cari Aktivitas</label>
                <input type="text" name="search" class="w-full rounded-xl border border-slate-200/80 bg-slate-50/50 px-3.5 py-2 text-xs font-semibold text-slate-950 placeholder-slate-400 focus:border-emerald-500 focus:bg-white focus:outline-none dark:border-slate-800 dark:bg-slate-950 dark:text-white"
                       value="{{ request('search') }}" placeholder="Ketik nama aktivitas...">
            </div>
            <div class="sm:col-span-3">
                <button type="submit" class="w-full btn-natural-primary text-xs py-2.5 justify-center">
                    🔍 Cari Aktivitas
                </button>
            </div>
        </form>
    </div>

    {{-- Activities List grouped by category --}}
    @php
        $grouped = $activities->getCollection()->groupBy(fn($a) => $a->category?->name ?? 'Tanpa Kategori');
    @endphp

    @forelse($grouped as $categoryName => $activityGroup)
        <div class="card-natural overflow-hidden mb-6">
            {{-- Category Header --}}
            <div class="bg-slate-50/70 dark:bg-slate-950/40 px-5 py-3.5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                    <h3 class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-wider">
                        Kategori: {{ $categoryName }}
                    </h3>
                </div>
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400">
                    {{ $activityGroup->count() }} aktivitas
                </span>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="border-b border-slate-100 text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:border-slate-800 bg-slate-50/40 dark:bg-slate-950/20">
                            <th class="py-3 px-5">Nama Aktivitas</th>
                            <th class="py-3 px-4 w-32">Tipe Input</th>
                            <th class="py-3 px-4 w-32">Target</th>
                            <th class="py-3 px-4 text-center w-28">Kewajiban</th>
                            <th class="py-3 px-4 text-center w-28">Status</th>
                            <th class="py-3 px-4 text-center w-36">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @foreach($activityGroup->sortBy('sort_order') as $activity)
                            <tr class="hover:bg-slate-50/40 dark:hover:bg-slate-950/10 transition-all">
                                <td class="py-3.5 px-5">
                                    <span class="font-extrabold text-slate-900 dark:text-white block">{{ $activity->name }}</span>
                                    @if($activity->description)
                                        <span class="text-[11px] text-slate-400 block mt-0.5">{{ Str::limit($activity->description, 70) }}</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4">
                                    @php
                                        $badges = [
                                            'checklist' => 'bg-emerald-50 text-emerald-700 border-emerald-200/80 dark:bg-emerald-950/30 dark:text-emerald-300 dark:border-emerald-800/40',
                                            'score' => 'bg-sky-50 text-sky-700 border-sky-200/80 dark:bg-sky-950/30 dark:text-sky-300 dark:border-sky-800/40',
                                            'count' => 'bg-amber-50 text-amber-700 border-amber-200/80 dark:bg-amber-950/30 dark:text-amber-300 dark:border-amber-800/40',
                                            'text' => 'bg-slate-100 text-slate-700 border-slate-200 dark:bg-slate-800 dark:text-slate-300',
                                        ];
                                        $colorClass = $badges[$activity->input_type] ?? 'bg-slate-100 text-slate-700 border-slate-200';
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-extrabold uppercase border {{ $colorClass }}">
                                        {{ $activity->input_type }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 font-semibold text-slate-700 dark:text-slate-300">
                                    @if($activity->input_type === 'score' && $activity->target_score)
                                        <span>&ge; {{ $activity->target_score }} skor</span>
                                    @elseif($activity->input_type === 'count' && $activity->target_count)
                                        <span>{{ $activity->target_count }} {{ $activity->target_unit }}</span>
                                    @else
                                        <span class="text-slate-400">—</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    @if($activity->is_required)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200/80 dark:bg-rose-950/30 dark:text-rose-300">Wajib</span>
                                    @else
                                        <span class="text-slate-400 text-xs font-medium">Opsional</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    @if($activity->is_active)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/80 dark:bg-emerald-950/30 dark:text-emerald-300">Aktif</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-500 border border-slate-200 dark:bg-slate-800 dark:text-slate-400">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <div class="inline-flex items-center gap-1.5">
                                        {{-- Detail --}}
                                        <a href="{{ route('mutabaah.activities.show', $activity) }}" 
                                           class="inline-flex h-8 w-8 items-center justify-center rounded-xl border border-slate-200 text-slate-500 hover:bg-slate-50 dark:border-slate-800 dark:text-slate-400 dark:hover:bg-slate-800 transition-all shadow-2xs" 
                                           title="Detail">
                                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>

                                        @if(auth()->user()->hasRole(['super_admin', 'admin']))
                                            {{-- Edit --}}
                                            <a href="{{ route('mutabaah.activities.edit', $activity) }}" 
                                               class="inline-flex h-8 w-8 items-center justify-center rounded-xl border border-slate-200 text-emerald-600 hover:bg-emerald-50/50 dark:border-slate-800 dark:text-emerald-400 dark:hover:bg-emerald-950/20 transition-all shadow-2xs" 
                                               title="Edit">
                                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                </svg>
                                            </a>

                                            {{-- Delete --}}
                                            <form method="POST" action="{{ route('mutabaah.activities.destroy', $activity) }}"
                                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus aktivitas mutabaah ini?')">
                                                @csrf 
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="inline-flex h-8 w-8 items-center justify-center rounded-xl border border-slate-200 text-rose-600 hover:bg-rose-50/50 dark:border-slate-800 dark:text-rose-400 dark:hover:bg-rose-950/20 transition-all shadow-2xs" 
                                                        title="Hapus">
                                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @empty
        <div class="card-natural p-12 text-center text-slate-400">
            <h3 class="text-sm font-bold text-slate-900 dark:text-white">Belum ada template aktivitas</h3>
            <p class="text-xs text-slate-500 mt-1">Silakan tambahkan template aktivitas baru untuk merekam mutabaah santri.</p>
        </div>
    @endforelse

    {{-- Pagination --}}
    @if($activities->hasPages())
        <div class="pt-4">
            {{ $activities->links() }}
        </div>
    @endif

</div>
@endsection

