@extends('layouts.app')

@section('title', 'Detail Aktivitas Mutabaah')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    {{-- Back Button & Title --}}
    <div class="flex items-center justify-between border-b border-slate-200/80 pb-5 dark:border-slate-800">
        <div class="flex items-center space-x-4">
            <a href="{{ route('mutabaah.activities.index') }}" 
               class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-400 dark:hover:bg-slate-800 transition">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white">{{ $activity->name }}</h1>
        </div>
        <div>
            @if(auth()->user()->hasRole(['super_admin','admin']))
                <a href="{{ route('mutabaah.activities.edit', $activity) }}" 
                   class="inline-flex items-center space-x-2 rounded-xl border border-amber-200 bg-amber-50/50 px-4 py-2 text-xs font-bold text-amber-700 hover:bg-amber-100/50 dark:border-amber-900/50 dark:bg-amber-950/20 dark:text-amber-450 dark:hover:bg-amber-950/35 transition">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                    <span>Edit Aktivitas</span>
                </a>
            @endif
        </div>
    </div>

    {{-- Detail Card --}}
    <div class="rounded-2xl border border-slate-200/80 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900 overflow-hidden">
        <div class="bg-slate-50/80 dark:bg-slate-850 px-6 py-4 border-b border-slate-200/80 dark:border-slate-800">
            <h3 class="text-sm font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Informasi Detail Aktivitas</h3>
        </div>
        <div class="p-6">
            <dl class="divide-y divide-slate-100 dark:divide-slate-800">
                <div class="py-3.5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-bold text-slate-500 dark:text-slate-400">Kategori</dt>
                    <dd class="mt-1 text-sm font-semibold text-slate-900 dark:text-slate-200 sm:col-span-2 sm:mt-0">
                        {{ $activity->category?->name ?? 'Belum ada kategori' }}
                    </dd>
                </div>

                <div class="py-3.5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-bold text-slate-500 dark:text-slate-400">Tipe Input</dt>
                    <dd class="mt-1 text-sm font-semibold text-slate-900 dark:text-slate-250 sm:col-span-2 sm:mt-0">
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
                    </dd>
                </div>

                @if($activity->input_type === 'score')
                    <div class="py-3.5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-bold text-slate-500 dark:text-slate-400">Target Skor Minimum</dt>
                        <dd class="mt-1 text-sm font-semibold text-slate-900 dark:text-slate-200 sm:col-span-2 sm:mt-0">
                            {{ $activity->target_score ?? 'Tidak diatur' }}
                        </dd>
                    </div>
                @endif

                @if($activity->input_type === 'count')
                    <div class="py-3.5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-bold text-slate-500 dark:text-slate-400">Target Jumlah</dt>
                        <dd class="mt-1 text-sm font-semibold text-slate-900 dark:text-slate-200 sm:col-span-2 sm:mt-0">
                            {{ $activity->target_count ?? 'Tidak diatur' }} {{ $activity->target_unit }}
                        </dd>
                    </div>
                @endif

                <div class="py-3.5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-bold text-slate-500 dark:text-slate-400">Sifat Kewajiban</dt>
                    <dd class="mt-1 text-sm font-semibold text-slate-900 sm:col-span-2 sm:mt-0">
                        @if($activity->is_required)
                            <span class="inline-flex items-center rounded-full bg-rose-50 px-2.5 py-0.5 text-[10px] font-bold text-rose-700 border border-rose-150 dark:bg-rose-950/20 dark:text-rose-450 dark:border-rose-900/50">Wajib</span>
                        @else
                            <span class="text-slate-450 dark:text-slate-500 text-sm font-medium">Opsional</span>
                        @endif
                    </dd>
                </div>

                <div class="py-3.5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-bold text-slate-500 dark:text-slate-400">Status Template</dt>
                    <dd class="mt-1 text-sm font-semibold text-slate-900 sm:col-span-2 sm:mt-0">
                        @if($activity->is_active)
                            <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-0.5 text-[10px] font-bold text-emerald-700 border border-emerald-150 dark:bg-emerald-950/20 dark:text-emerald-400 dark:border-emerald-900/50">Aktif</span>
                        @else
                            <span class="inline-flex items-center rounded-full bg-slate-50 px-2.5 py-0.5 text-[10px] font-bold text-slate-500 border border-slate-150 dark:bg-slate-800/40 dark:text-slate-450 dark:border-slate-750">Nonaktif</span>
                        @endif
                    </dd>
                </div>

                <div class="py-3.5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-bold text-slate-500 dark:text-slate-400">Akses Input</dt>
                    <dd class="mt-1 text-sm text-slate-800 dark:text-slate-350 sm:col-span-2 sm:mt-0">
                        <ul class="list-disc pl-5 text-xs space-y-1 font-semibold text-slate-650 dark:text-slate-400">
                            <li>Guru: <span class="{{ $activity->allow_teacher_input ? 'text-emerald-600 dark:text-emerald-450' : 'text-slate-400' }}">{{ $activity->allow_teacher_input ? 'Boleh' : 'Tidak' }}</span></li>
                            <li>Orang Tua: <span class="{{ $activity->allow_parent_input ? 'text-emerald-600 dark:text-emerald-450' : 'text-slate-400' }}">{{ $activity->allow_parent_input ? 'Boleh' : 'Tidak' }}</span></li>
                            <li>Santri: <span class="{{ $activity->allow_student_input ? 'text-emerald-600 dark:text-emerald-450' : 'text-slate-400' }}">{{ $activity->allow_student_input ? 'Boleh' : 'Tidak' }}</span></li>
                        </ul>
                    </dd>
                </div>

                <div class="py-3.5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-bold text-slate-500 dark:text-slate-400">Urutan Tampil</dt>
                    <dd class="mt-1 text-sm font-semibold text-slate-900 dark:text-slate-200 sm:col-span-2 sm:mt-0">
                        #{{ $activity->sort_order }}
                    </dd>
                </div>

                <div class="py-3.5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-bold text-slate-500 dark:text-slate-400">Keterangan / Deskripsi</dt>
                    <dd class="mt-1 text-sm font-medium text-slate-600 dark:text-slate-450 whitespace-pre-line sm:col-span-2 sm:mt-0">
                        {{ $activity->description ?? 'Tidak ada deskripsi.' }}
                    </dd>
                </div>
            </dl>
        </div>
    </div>
</div>
@endsection
