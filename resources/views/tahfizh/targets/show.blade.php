@extends('layouts.app')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between border-b border-slate-200/80 pb-5 dark:border-slate-800 gap-4">
        <div class="flex items-center space-x-3">
            <a href="{{ route('tahfizh.targets.index') }}" 
               class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-slate-200 text-slate-500 hover:bg-slate-50 dark:border-slate-800 dark:text-slate-400 dark:hover:bg-slate-800 transition-all" 
               title="Kembali ke Daftar">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h2 class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white">{{ $target->name }}</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400">Detail konfigurasi target hafalan tahfizh.</p>
            </div>
        </div>
        @if (auth()->user()->hasRole(['super_admin', 'admin']))
            <div class="flex items-center space-x-2">
                <a href="{{ route('tahfizh.targets.edit', $target) }}"
                   class="inline-flex items-center justify-center rounded-full border border-slate-200 bg-white px-4 py-2 text-xs font-bold text-slate-600 hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-400 dark:hover:bg-slate-800 transition-all">
                    Edit Target
                </a>

                <form method="POST" action="{{ route('tahfizh.targets.destroy', $target) }}"
                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus target ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="inline-flex items-center justify-center rounded-full bg-rose-600 px-4 py-2 text-xs font-bold text-white hover:bg-rose-700 shadow-sm transition-all focus:outline-none focus:ring-4 focus:ring-rose-500/20 active:scale-[0.98]">
                        Hapus Target
                    </button>
                </form>
            </div>
        @endif
    </div>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
        {{-- Spesifikasi Target --}}
        <div class="md:col-span-2 space-y-6">
            <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h3 class="mb-5 text-sm font-bold uppercase tracking-wider text-indigo-600 dark:text-indigo-400 border-b border-slate-100 dark:border-slate-800/60 pb-3">Spesifikasi Target</h3>
                
                <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                    <div>
                        <dt class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Sekolah</dt>
                        <dd class="mt-1 text-sm font-semibold text-slate-800 dark:text-slate-200">{{ $target->school->name }}</dd>
                    </div>

                    <div>
                        <dt class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Status Keaktifan</dt>
                        <dd class="mt-1.5">
                            @if($target->is_active)
                                <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-0.5 text-[10px] font-bold text-emerald-700 border border-emerald-150 dark:bg-emerald-950/20 dark:text-emerald-400 dark:border-emerald-900/50">Aktif</span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-slate-50 px-2.5 py-0.5 text-[10px] font-bold text-slate-500 border border-slate-150 dark:bg-slate-800/40 dark:text-slate-450 dark:border-slate-750">Nonaktif</span>
                            @endif
                        </dd>
                    </div>

                    <div>
                        <dt class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Cakupan Target</dt>
                        <dd class="mt-1.5">
                            @if ($target->student)
                                <span class="inline-flex items-center rounded-md bg-indigo-50 px-2.5 py-0.5 text-xs font-bold text-indigo-700 border border-indigo-150/40 dark:bg-indigo-950/20 dark:text-indigo-400 dark:border-indigo-900/50">
                                    Santri: {{ $target->student->full_name }}
                                </span>
                            @elseif ($target->classRoom)
                                <span class="inline-flex items-center rounded-md bg-emerald-50 px-2.5 py-0.5 text-xs font-bold text-emerald-700 border border-emerald-150/40 dark:bg-emerald-950/20 dark:text-emerald-400 dark:border-emerald-900/50">
                                    Kelas: {{ $target->classRoom->name }}
                                </span>
                            @elseif ($target->program_type)
                                <span class="inline-flex items-center rounded-md bg-amber-50 px-2.5 py-0.5 text-xs font-bold text-amber-700 border border-amber-150/40 dark:bg-amber-950/20 dark:text-amber-450 dark:border-amber-900/50">
                                    Program: {{ ucfirst($target->program_type) }}
                                </span>
                            @else
                                <span class="inline-flex items-center rounded-md bg-slate-50 px-2.5 py-0.5 text-xs font-bold text-slate-700 border border-slate-150 dark:bg-slate-800/40 dark:text-slate-450 dark:border-slate-750">
                                    Umum Sekolah
                                </span>
                            @endif
                        </dd>
                    </div>

                    <div>
                        <dt class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Jenis Program</dt>
                        <dd class="mt-1 text-sm font-semibold text-slate-800 dark:text-slate-200">{{ $target->program_type ? ucfirst($target->program_type) : '-' }}</dd>
                    </div>

                    <div>
                        <dt class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Mulai Berlaku</dt>
                        <dd class="mt-1 text-sm font-semibold text-slate-800 dark:text-slate-200">{{ $target->effective_from ? $target->effective_from->format('d F Y') : 'Selamanya' }}</dd>
                    </div>

                    <div>
                        <dt class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Berakhir Berlaku</dt>
                        <dd class="mt-1 text-sm font-semibold text-slate-800 dark:text-slate-200">{{ $target->effective_until ? $target->effective_until->format('d F Y') : 'Selamanya' }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Kartu Metrik --}}
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
                <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900 text-center">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Target Harian</p>
                    <p class="mt-2 text-3xl font-black text-slate-800 dark:text-white">{{ $target->daily_target_lines }}</p>
                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">baris setoran / hari</p>
                </div>

                <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900 text-center">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Target Mingguan</p>
                    <p class="mt-2 text-3xl font-black text-slate-800 dark:text-white">{{ $target->weekly_target_lines }}</p>
                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">baris setoran / minggu</p>
                </div>

                <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900 text-center">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Target Bulanan</p>
                    <p class="mt-2 text-3xl font-black text-slate-800 dark:text-white">{{ $target->monthly_target_lines }}</p>
                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">baris setoran / bulan</p>
                </div>
            </div>
        </div>

        {{-- Audit Info --}}
        <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 h-fit">
            <h3 class="mb-5 text-sm font-bold uppercase tracking-wider text-indigo-600 dark:text-indigo-400 border-b border-slate-100 dark:border-slate-800/60 pb-3">Informasi Audit</h3>
            
            <div class="space-y-4">
                <div>
                    <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Dibuat Oleh</span>
                    <span class="text-sm font-semibold text-slate-850 dark:text-slate-200">{{ $target->creator?->name ?? '-' }}</span>
                </div>
                <div>
                    <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Tanggal Dibuat</span>
                    <span class="text-sm font-semibold text-slate-850 dark:text-slate-200">{{ $target->created_at->format('d F Y H:i') }}</span>
                </div>
                <div>
                    <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Terakhir Diperbarui</span>
                    <span class="text-sm font-semibold text-slate-850 dark:text-slate-200">{{ $target->updated_at->format('d F Y H:i') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

