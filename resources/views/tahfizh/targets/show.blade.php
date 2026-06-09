@extends('layouts.app')

@section('content')
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <div>
            <a href="{{ route('tahfizh.targets.index') }}" class="text-sm font-semibold text-slate-600 hover:text-slate-900">
                &larr; Kembali ke Daftar Target
            </a>
            <h2 class="text-2xl font-bold mt-2">{{ $target->name }}</h2>
            <p class="text-sm text-slate-500">Detail konfigurasi target hafalan tahfizh.</p>
        </div>

        @if (auth()->user()->hasRole(['super_admin', 'admin']))
            <div class="flex gap-2">
                <a href="{{ route('tahfizh.targets.edit', $target) }}"
                   class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                    Edit Target
                </a>

                <form method="POST" action="{{ route('tahfizh.targets.destroy', $target) }}"
                      onsubmit="return confirm('Hapus target ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">
                        Hapus Target
                    </button>
                </form>
            </div>
        @endif
    </div>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
        <!-- Configuration Details -->
        <div class="md:col-span-2 space-y-6">
            <div class="rounded-2xl bg-white p-6 shadow-sm border border-slate-100">
                <h3 class="text-lg font-bold mb-4 text-slate-900">Spesifikasi Target</h3>
                <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                    <div>
                        <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Sekolah</dt>
                        <dd class="mt-1 text-sm font-semibold text-slate-900">{{ $target->school->name }}</dd>
                    </div>

                    <div>
                        <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Status Aktif</dt>
                        <dd class="mt-1 text-sm">
                            <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-bold {{ $target->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $target->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </dd>
                    </div>

                    <div>
                        <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Cakupan Target</dt>
                        <dd class="mt-1 text-sm font-semibold text-slate-900">
                            @if ($target->student)
                                <span class="inline-block bg-indigo-100 text-indigo-800 px-2.5 py-0.5 rounded text-xs font-bold">
                                    Santri: {{ $target->student->full_name }}
                                </span>
                            @elseif ($target->classRoom)
                                <span class="inline-block bg-emerald-100 text-emerald-800 px-2.5 py-0.5 rounded text-xs font-bold">
                                    Kelas: {{ $target->classRoom->name }}
                                </span>
                            @elseif ($target->program_type)
                                <span class="inline-block bg-amber-100 text-amber-800 px-2.5 py-0.5 rounded text-xs font-bold">
                                    Program: {{ ucfirst($target->program_type) }}
                                </span>
                            @else
                                <span class="inline-block bg-slate-100 text-slate-800 px-2.5 py-0.5 rounded text-xs font-bold">
                                    Umum Sekolah
                                </span>
                            @endif
                        </dd>
                    </div>

                    <div>
                        <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Jenis Program</dt>
                        <dd class="mt-1 text-sm font-semibold text-slate-900">{{ $target->program_type ? ucfirst($target->program_type) : '-' }}</dd>
                    </div>

                    <div>
                        <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Mulai Berlaku</dt>
                        <dd class="mt-1 text-sm font-semibold text-slate-900">{{ $target->effective_from ? $target->effective_from->format('d F Y') : 'Selamanya' }}</dd>
                    </div>

                    <div>
                        <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Berakhir Berlaku</dt>
                        <dd class="mt-1 text-sm font-semibold text-slate-900">{{ $target->effective_until ? $target->effective_until->format('d F Y') : 'Selamanya' }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Target metrics -->
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
                <div class="rounded-2xl bg-white p-6 shadow-sm border border-slate-100 text-center">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Target Harian</p>
                    <p class="mt-2 text-3xl font-extrabold text-slate-900">{{ $target->daily_target_lines }}</p>
                    <p class="text-xs text-slate-400 mt-1">baris setoran / hari</p>
                </div>

                <div class="rounded-2xl bg-white p-6 shadow-sm border border-slate-100 text-center">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Target Mingguan</p>
                    <p class="mt-2 text-3xl font-extrabold text-slate-900">{{ $target->weekly_target_lines }}</p>
                    <p class="text-xs text-slate-400 mt-1">baris setoran / minggu</p>
                </div>

                <div class="rounded-2xl bg-white p-6 shadow-sm border border-slate-100 text-center">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Target Bulanan</p>
                    <p class="mt-2 text-3xl font-extrabold text-slate-900">{{ $target->monthly_target_lines }}</p>
                    <p class="text-xs text-slate-400 mt-1">baris setoran / bulan</p>
                </div>
            </div>
        </div>

        <!-- Audit Details -->
        <div class="rounded-2xl bg-white p-6 shadow-sm border border-slate-100 h-fit">
            <h3 class="text-lg font-bold mb-4 text-slate-900">Audit Info</h3>
            <div class="space-y-4">
                <div>
                    <span class="block text-xs font-semibold text-slate-500 uppercase tracking-wider">Dibuat Oleh</span>
                    <span class="text-sm font-semibold text-slate-900">{{ $target->creator?->name ?? '-' }}</span>
                </div>
                <div>
                    <span class="block text-xs font-semibold text-slate-500 uppercase tracking-wider">Tanggal Dibuat</span>
                    <span class="text-sm font-semibold text-slate-900">{{ $target->created_at->format('d F Y H:i') }}</span>
                </div>
                <div>
                    <span class="block text-xs font-semibold text-slate-500 uppercase tracking-wider">Terakhir Diperbarui</span>
                    <span class="text-sm font-semibold text-slate-900">{{ $target->updated_at->format('d F Y H:i') }}</span>
                </div>
            </div>
        </div>
    </div>
@endsection
