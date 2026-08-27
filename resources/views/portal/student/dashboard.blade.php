@extends('layouts.app')

@section('content')
    <div class="mb-8">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 text-xs font-semibold text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/40 mb-3">
            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
            Portal Santri
        </div>
        <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white">Progres Tahfizh & Mutabaah</h1>
        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Pantau catatan hafalan harian, target bulanan, dan histori setoran Anda.</p>
    </div>

    @if (! $student)
        <div class="card-natural p-8 text-center max-w-lg mx-auto">
            <div class="w-14 h-14 mx-auto rounded-2xl bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 flex items-center justify-center mb-4">
                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
            </div>
            <h3 class="text-lg font-bold text-slate-900 dark:text-white">Profil Santri Belum Terhubung</h3>
            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400 leading-relaxed">
                Profil akun ini belum dihubungkan dengan data santri aktif. Silakan hubungi admin sekolah atau ustadz pengampu.
            </p>
        </div>
    @else
        <!-- Student Header Card -->
        <div class="card-natural p-6 mb-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-center space-x-4">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-700 text-white font-black text-xl flex items-center justify-center shadow-md shadow-emerald-950/20">
                    {{ strtoupper(substr($student->full_name, 0, 2)) }}
                </div>
                <div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white tracking-tight">{{ $student->full_name }}</h3>
                    <p class="text-xs font-medium text-slate-500 dark:text-slate-400 mt-0.5">
                        {{ $student->classRoom?->name ?? 'Halaqah Al-Qur\'an' }} &bull; NIS: {{ $student->nis ?? '-' }}
                    </p>
                </div>
            </div>

            <div class="flex flex-wrap gap-2">
                <a href="{{ route('portal.student.records') }}"
                   class="rounded-xl bg-emerald-600 px-4 py-2 text-xs font-bold text-white hover:bg-emerald-700 shadow-sm transition">
                    Riwayat Setoran
                </a>

                <a href="{{ route('portal.student.monthly') }}"
                   class="rounded-xl border border-slate-200 dark:border-slate-700 px-4 py-2 text-xs font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition">
                    Raport Bulanan
                </a>
            </div>
        </div>

        <!-- Metrics Bento Grid -->
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4 mb-8">
            <div class="card-natural p-5">
                <div class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Target Harian</div>
                <div class="mt-2 text-3xl font-black text-slate-900 dark:text-white tracking-tight">{{ $snapshot['target_daily_lines'] }}</div>
                <div class="text-xs font-medium text-slate-400 dark:text-slate-500 mt-1">baris / pertemuan</div>
            </div>

            <div class="card-natural p-5 bg-emerald-50/40 dark:bg-emerald-950/20 border-emerald-100/60 dark:border-emerald-900/40">
                <div class="text-xs font-semibold uppercase tracking-wider text-emerald-700 dark:text-emerald-400">Capaian Bulan Ini</div>
                <div class="mt-2 text-3xl font-black text-emerald-800 dark:text-emerald-300 tracking-tight">{{ $snapshot['total_lines'] }}</div>
                <div class="text-xs font-medium text-emerald-600/80 dark:text-emerald-400/80 mt-1">total baris disetor</div>
            </div>

            <div class="card-natural p-5">
                <div class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Frekuensi Setoran</div>
                <div class="mt-2 text-3xl font-black text-slate-900 dark:text-white tracking-tight">{{ $snapshot['total_records'] }}</div>
                <div class="text-xs font-medium text-slate-400 dark:text-slate-500 mt-1">kali sesi talaqqi</div>
            </div>

            <div class="card-natural p-5">
                <div class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Hutang Target</div>
                <div class="mt-2 text-3xl font-black {{ $snapshot['current_debt_lines'] > 0 ? 'text-amber-600 dark:text-amber-400' : 'text-slate-900 dark:text-white' }} tracking-tight">{{ $snapshot['current_debt_lines'] }}</div>
                <div class="text-xs font-medium text-slate-400 dark:text-slate-500 mt-1">baris tertunggak</div>
            </div>
        </div>

        <!-- Recent Records Table -->
        <div class="card-natural overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800/80 flex items-center justify-between">
                <h3 class="text-base font-bold text-slate-900 dark:text-white tracking-tight">Riwayat Setoran Terkini</h3>
                <a href="{{ route('portal.student.records') }}" class="text-xs font-bold text-emerald-600 dark:text-emerald-400 hover:underline">Lihat Semua &rarr;</a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-left text-sm">
                    <thead class="bg-slate-50/70 dark:bg-slate-850/50 text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 border-b border-slate-100 dark:border-slate-800">
                        <tr>
                            <th class="px-6 py-3.5">Tanggal</th>
                            <th class="px-6 py-3.5">Ustadz Pengampu</th>
                            <th class="px-6 py-3.5">Rentang Ayat/Halaman</th>
                            <th class="px-6 py-3.5">Jumlah Baris</th>
                            <th class="px-6 py-3.5">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                        @forelse ($snapshot['recent_records'] as $record)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-850/40 transition-colors duration-150">
                                <td class="px-6 py-4 text-xs font-bold text-slate-700 dark:text-slate-300">{{ $record->record_date?->format('d M Y') }}</td>
                                <td class="px-6 py-4 text-xs font-medium text-slate-900 dark:text-slate-100">{{ $record->teacher?->name ?? '-' }}</td>
                                <td class="px-6 py-4 text-xs text-slate-500 dark:text-slate-400">
                                    Hlm {{ $record->start_page }}:{{ $record->start_line }}
                                    &mdash;
                                    Hlm {{ $record->end_page }}:{{ $record->end_line }}
                                </td>
                                <td class="px-6 py-4 text-xs font-bold text-emerald-600 dark:text-emerald-400">+{{ $record->total_lines }} baris</td>
                                <td class="px-6 py-4 text-xs">
                                    @if ($record->status === \App\Models\HafalanRecord::STATUS_LUNAS)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300 border border-emerald-200/60">Lunas</span>
                                    @elseif ($record->status === \App\Models\HafalanRecord::STATUS_KURANG)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-50 text-amber-700 dark:bg-amber-950/40 dark:text-amber-300 border border-amber-200/60">Kurang</span>
                                    @elseif ($record->status === \App\Models\HafalanRecord::STATUS_LEBIH)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-teal-50 text-teal-700 dark:bg-teal-950/40 dark:text-teal-300 border border-teal-200/60">Lebih Target</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-xs text-slate-400 dark:text-slate-500">
                                    Belum ada catatan setoran terbaru.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endsection
