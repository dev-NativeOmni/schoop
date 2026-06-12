@extends('layouts.app')

@section('title', 'Template Aktivitas Mutabaah')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between border-b border-slate-200/80 pb-5 dark:border-slate-800 gap-4">
        <div>
            <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 dark:bg-indigo-950/30 dark:text-indigo-400">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <span>Template Aktivitas Mutabaah</span>
            </h1>
            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Kelola dan atur template aktivitas ibadah serta karakter harian santri.</p>
        </div>
        <div>
            @if(auth()->user()->hasRole(['super_admin', 'admin']))
                <a href="{{ route('mutabaah.activities.create') }}" 
                   class="inline-flex items-center space-x-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700 shadow-sm transition-all focus:outline-none focus:ring-4 focus:ring-indigo-500/20 active:scale-[0.98]">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Tambah Aktivitas</span>
                </a>
            @endif
        </div>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50/80 p-4 text-emerald-800 backdrop-blur-sm dark:border-emerald-900/50 dark:bg-emerald-950/30 dark:text-emerald-350">
            <div class="flex">
                <svg class="h-5 w-5 text-emerald-500 me-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-sm font-semibold">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    {{-- Filters --}}
    <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <form method="GET" class="grid gap-4 sm:grid-cols-12 items-end">
            <div class="sm:col-span-4">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">Kategori</label>
                <select name="category_id" 
                        class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-sm text-slate-950 focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 dark:border-slate-800 dark:bg-slate-950 dark:text-white dark:focus:border-indigo-500">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" @selected(request('category_id') == $cat->id)>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="sm:col-span-5">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">Cari Aktivitas</label>
                <input type="text" name="search" class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-sm text-slate-950 placeholder-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 dark:border-slate-800 dark:bg-slate-950 dark:text-white dark:focus:border-indigo-500"
                       value="{{ request('search') }}" placeholder="Ketik nama aktivitas...">
            </div>
            <div class="sm:col-span-3">
                <button type="submit" 
                        class="flex w-full items-center justify-center space-x-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700 shadow-sm transition-all focus:outline-none focus:ring-4 focus:ring-indigo-500/20 active:scale-[0.98]">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <span>Cari Aktivitas</span>
                </button>
            </div>
        </form>
    </div>

    {{-- Activities List grouped by category --}}
    @php
        $grouped = $activities->getCollection()->groupBy(fn($a) => $a->category?->name ?? 'Tanpa Kategori');
    @endphp

    @forelse($grouped as $categoryName => $activityGroup)
        <div class="rounded-2xl border border-slate-200/80 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900 overflow-hidden mb-6">
            {{-- Category Header --}}
            <div class="bg-slate-50/80 dark:bg-slate-850 px-6 py-4 border-b border-slate-200/80 dark:border-slate-800 flex items-center justify-between">
                <h3 class="text-base font-bold text-slate-800 dark:text-white flex items-center gap-2">
                    <svg class="h-5 w-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>Kategori: {{ $categoryName }}</span>
                </h3>
                <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-bold text-slate-600 dark:bg-slate-800 dark:text-slate-400">
                    {{ $activityGroup->count() }} aktivitas
                </span>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/40 dark:bg-slate-850/40 border-b border-slate-200/80 dark:border-slate-800">
                            <th class="px-6 py-3.5 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Nama Aktivitas</th>
                            <th class="px-6 py-3.5 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 w-32">Tipe Input</th>
                            <th class="px-6 py-3.5 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 w-32">Target</th>
                            <th class="px-6 py-3.5 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 text-center w-28">Kewajiban</th>
                            <th class="px-6 py-3.5 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 text-center w-28">Status</th>
                            <th class="px-6 py-3.5 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 text-center w-36">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @foreach($activityGroup->sortBy('sort_order') as $activity)
                            <tr class="hover:bg-slate-50/40 dark:hover:bg-slate-850/10 transition-all">
                                <td class="px-6 py-4">
                                    <span class="text-sm font-bold text-slate-800 dark:text-slate-250 block">{{ $activity->name }}</span>
                                    @if($activity->description)
                                        <span class="text-xs text-slate-400 dark:text-slate-500 block mt-0.5">{{ Str::limit($activity->description, 70) }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $badges = [
                                            'checklist' => 'bg-emerald-50 text-emerald-700 border-emerald-150 dark:bg-emerald-950/20 dark:text-emerald-400 dark:border-emerald-900/50',
                                            'score' => 'bg-sky-50 text-sky-700 border-sky-150 dark:bg-sky-950/20 dark:text-sky-450 dark:border-sky-900/50',
                                            'count' => 'bg-amber-50 text-amber-700 border-amber-150 dark:bg-amber-950/20 dark:text-amber-450 dark:border-amber-900/50',
                                            'text' => 'bg-slate-50 text-slate-700 border-slate-150 dark:bg-slate-800/20 dark:text-slate-400 dark:border-slate-750',
                                        ];
                                        $colorClass = $badges[$activity->input_type] ?? 'bg-slate-50 text-slate-700 border-slate-150';
                                    @endphp
                                    <span class="inline-flex items-center rounded-md border px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide {{ $colorClass }}">
                                        {{ $activity->input_type }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-xs font-semibold text-slate-700 dark:text-slate-350">
                                    @if($activity->input_type === 'score' && $activity->target_score)
                                        <span>&ge; {{ $activity->target_score }} skor</span>
                                    @elseif($activity->input_type === 'count' && $activity->target_count)
                                        <span>{{ $activity->target_count }} {{ $activity->target_unit }}</span>
                                    @else
                                        <span class="text-slate-400 dark:text-slate-600">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($activity->is_required)
                                        <span class="inline-flex items-center rounded-full bg-rose-50 px-2 py-0.5 text-[10px] font-bold text-rose-700 border border-rose-150 dark:bg-rose-950/20 dark:text-rose-450 dark:border-rose-900/50">Wajib</span>
                                    @else
                                        <span class="text-slate-450 dark:text-slate-600 text-xs font-medium">Opsional</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($activity->is_active)
                                        <span class="inline-flex items-center rounded-full bg-emerald-50 px-2 py-0.5 text-[10px] font-bold text-emerald-700 border border-emerald-150 dark:bg-emerald-950/20 dark:text-emerald-400 dark:border-emerald-900/50">Aktif</span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-slate-50 px-2 py-0.5 text-[10px] font-bold text-slate-500 border border-slate-150 dark:bg-slate-800/40 dark:text-slate-450 dark:border-slate-750">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="inline-flex items-center space-x-1.5">
                                        {{-- Detail --}}
                                        <a href="{{ route('mutabaah.activities.show', $activity) }}" 
                                           class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 text-slate-500 hover:bg-slate-50 dark:border-slate-800 dark:text-slate-400 dark:hover:bg-slate-800 transition-all" 
                                           title="Detail">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>

                                        @if(auth()->user()->hasRole(['super_admin', 'admin']))
                                            {{-- Edit --}}
                                            <a href="{{ route('mutabaah.activities.edit', $activity) }}" 
                                               class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 text-indigo-600 hover:bg-indigo-50/30 dark:border-slate-800 dark:text-indigo-400 dark:hover:bg-indigo-950/20 transition-all" 
                                               title="Edit">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                </svg>
                                            </a>

                                            {{-- Delete --}}
                                            <form method="POST" action="{{ route('mutabaah.activities.destroy', $activity) }}"
                                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus aktivitas mutabaah ini?')">
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
        <div class="rounded-2xl border border-dashed border-slate-300 p-12 text-center dark:border-slate-700 bg-white dark:bg-slate-900">
            <svg class="mx-auto h-12 w-12 text-slate-400 dark:text-slate-650" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
            </svg>
            <h3 class="mt-4 text-sm font-bold text-slate-900 dark:text-white">Belum ada template aktivitas</h3>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Silakan tambahkan template aktivitas baru untuk merekam mutabaah santri.</p>
            @if(auth()->user()->hasRole(['super_admin', 'admin']))
                <a href="{{ route('mutabaah.activities.create') }}" 
                   class="mt-4 inline-flex items-center space-x-2 rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700 shadow-sm transition">
                    <span>Tambah Aktivitas Pertama</span>
                </a>
            @endif
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
