@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <a href="{{ route('tahfizh.debts.index') }}" class="text-sm font-semibold text-slate-600 hover:text-slate-900">
            &larr; Kembali ke Daftar Hutang
        </a>
        <h2 class="text-2xl font-bold mt-2">Detail Hutang Hafalan</h2>
        <p class="text-sm text-slate-500">Rincian perhitungan capaian dan hutang hafalan santri.</p>
    </div>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
        <!-- Main Stats & Calculations -->
        <div class="md:col-span-2 space-y-6">
            <!-- Summary Card -->
            <div class="rounded-2xl bg-white p-6 shadow-sm border border-slate-100">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Periode Perhitungan</span>
                        <h3 class="text-xl font-bold text-slate-900">{{ ucfirst($debt->period_type) }}</h3>
                        <p class="text-xs text-slate-500 mt-1">
                            {{ $debt->period_start->format('d F Y') }} - {{ $debt->period_end->format('d F Y') }}
                        </p>
                    </div>
                    <div>
                        @if ($debt->status === \App\Models\TahfizhDebt::STATUS_NO_TARGET)
                            <span class="inline-block bg-slate-100 text-slate-700 px-3 py-1 rounded-full text-xs font-bold">
                                Belum Ada Target
                            </span>
                        @elseif ($debt->status === \App\Models\TahfizhDebt::STATUS_MET)
                            <span class="inline-block bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-bold">
                                Tercapai
                            </span>
                        @elseif ($debt->status === \App\Models\TahfizhDebt::STATUS_BEHIND)
                            <span class="inline-block bg-red-100 text-red-800 px-3 py-1 rounded-full text-xs font-bold">
                                Kurang
                            </span>
                        @elseif ($debt->status === \App\Models\TahfizhDebt::STATUS_AHEAD)
                            <span class="inline-block bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-bold">
                                Lebih
                            </span>
                        @endif
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 sm:grid-cols-4 border-t pt-6">
                    <div class="text-center p-3 rounded-xl bg-slate-50">
                        <span class="block text-xs font-semibold text-slate-500 uppercase tracking-wider">Target</span>
                        <span class="block text-xl font-extrabold text-slate-900 mt-1">{{ $debt->target_lines }} baris</span>
                    </div>

                    <div class="text-center p-3 rounded-xl bg-emerald-50 text-emerald-800">
                        <span class="block text-xs font-semibold text-emerald-600 uppercase tracking-wider">Capaian</span>
                        <span class="block text-xl font-extrabold text-emerald-900 mt-1">+{{ $debt->actual_lines }} baris</span>
                    </div>

                    <div class="text-center p-3 rounded-xl bg-red-50 text-red-800">
                        <span class="block text-xs font-semibold text-red-600 uppercase tracking-wider">Hutang Baru</span>
                        <span class="block text-xl font-extrabold text-red-900 mt-1">{{ $debt->debt_lines }} baris</span>
                    </div>

                    <div class="text-center p-3 rounded-xl bg-blue-50 text-blue-800">
                        <span class="block text-xs font-semibold text-blue-600 uppercase tracking-wider">Kelebihan</span>
                        <span class="block text-xl font-extrabold text-blue-900 mt-1">{{ $debt->surplus_lines }} baris</span>
                    </div>
                </div>

                <div class="mt-6 p-4 rounded-xl bg-slate-900 text-white flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <span class="block text-xs font-bold uppercase tracking-wider text-slate-400">Total Akumulasi Hutang</span>
                        <p class="text-xs text-slate-300 mt-1">Mengakumulasikan hutang periode sebelumnya dikurangi kelebihan hari ini.</p>
                    </div>
                    <div class="text-right">
                        <span class="text-2xl font-black">
                            @if ($debt->cumulative_debt_lines > 0)
                                {{ $debt->cumulative_debt_lines }} baris
                            @else
                                Lunas / 0
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            <!-- Context Info -->
            <div class="rounded-2xl bg-white p-6 shadow-sm border border-slate-100">
                <h4 class="text-base font-bold text-slate-900 mb-4">Informasi Akademik & Target</h4>
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <span class="block text-xs font-semibold text-slate-500 uppercase tracking-wider">Santri</span>
                        <span class="text-sm font-semibold text-slate-900 block mt-1">{{ $debt->student->full_name }}</span>
                        <span class="text-xs text-slate-500">No. Induk: {{ $debt->student->student_number ?? '-' }}</span>
                    </div>

                    <div>
                        <span class="block text-xs font-semibold text-slate-500 uppercase tracking-wider">Kelas / Sekolah</span>
                        <span class="text-sm font-semibold text-slate-900 block mt-1">{{ $debt->classRoom?->name ?? '-' }}</span>
                        <span class="text-xs text-slate-500">{{ $debt->school->name }}</span>
                    </div>

                    <div>
                        <span class="block text-xs font-semibold text-slate-500 uppercase tracking-wider">Target Aktif Terpilih</span>
                        @if ($debt->tahfizhTarget)
                            <a href="{{ route('tahfizh.targets.show', $debt->tahfizhTarget) }}" class="text-sm font-semibold text-indigo-600 hover:underline block mt-1">
                                {{ $debt->tahfizhTarget->name }}
                            </a>
                            <span class="text-xs text-slate-500">
                                Target: {{ $debt->tahfizhTarget->daily_target_lines }}/{{ $debt->tahfizhTarget->weekly_target_lines }}/{{ $debt->tahfizhTarget->monthly_target_lines }} baris
                            </span>
                        @else
                            <span class="text-sm text-slate-400 block mt-1">-</span>
                            <span class="text-xs text-slate-500">Tidak ada target spesifik aktif, menggunakan fallback 0.</span>
                        @endif
                    </div>

                    <div>
                        <span class="block text-xs font-semibold text-slate-500 uppercase tracking-wider">Program Santri</span>
                        <span class="text-sm font-semibold text-slate-900 block mt-1">{{ $debt->student->program_type ? ucfirst($debt->student->program_type) : 'Reguler' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Audit & Logs Column -->
        <div class="space-y-6">
            <div class="rounded-2xl bg-white p-6 shadow-sm border border-slate-100">
                <h4 class="text-base font-bold text-slate-900 mb-4">Informasi Perhitungan</h4>
                <div class="space-y-4">
                    <div>
                        <span class="block text-xs font-semibold text-slate-500 uppercase tracking-wider">Dihitung Oleh</span>
                        <span class="text-sm font-semibold text-slate-900">{{ $debt->calculator?->name ?? 'Sistem / Otomatis' }}</span>
                    </div>

                    <div>
                        <span class="block text-xs font-semibold text-slate-500 uppercase tracking-wider">Tanggal Perhitungan</span>
                        <span class="text-sm font-semibold text-slate-900">{{ $debt->calculation_date->format('d F Y') }}</span>
                    </div>

                    <div>
                        <span class="block text-xs font-semibold text-slate-500 uppercase tracking-wider">Terakhir Diperbarui</span>
                        <span class="text-sm font-semibold text-slate-900">{{ $debt->updated_at->format('d F Y H:i') }}</span>
                    </div>

                    <div class="border-t pt-4">
                        <span class="block text-xs font-semibold text-slate-500 uppercase tracking-wider">Catatan Kalkulator</span>
                        <p class="text-sm text-slate-600 mt-1 italic">
                            "{{ $debt->notes ?? 'Tidak ada catatan.' }}"
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
