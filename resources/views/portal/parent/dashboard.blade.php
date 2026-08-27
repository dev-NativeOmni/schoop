@extends('layouts.app')

@section('content')
    <div class="mb-8">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 text-xs font-semibold text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/40 mb-3">
            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
            Portal Wali Santri
        </div>
        <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white">Monitoring Perkembangan Ananda</h1>
        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Pantau capaian hafalan Al-Qur'an, mutabaah ibadah, dan administrasi ananda secara real-time.</p>
    </div>

    @if ($children->isEmpty())
        <div class="card-natural p-8 text-center max-w-lg mx-auto">
            <div class="w-14 h-14 mx-auto rounded-2xl bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 flex items-center justify-center mb-4">
                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
            </div>
            <h3 class="text-lg font-bold text-slate-900 dark:text-white">Belum Ada Santri Terhubung</h3>
            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400 leading-relaxed">
                Akun orang tua ini belum terhubung dengan data santri aktif. Silakan hubungi bagian administrasi sekolah untuk menghubungkan akun.
            </p>
        </div>
    @else
        <div class="grid gap-6 md:grid-cols-2">
            @foreach ($snapshots as $snapshot)
                <div class="card-natural p-7">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center space-x-3.5">
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-700 text-white font-black text-lg flex items-center justify-center shadow-sm">
                                {{ strtoupper(substr($snapshot['student']->full_name, 0, 2)) }}
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-slate-900 dark:text-white tracking-tight">{{ $snapshot['student']->full_name }}</h3>
                                <p class="text-xs font-medium text-slate-500 dark:text-slate-400 mt-0.5">
                                    {{ $snapshot['student']->classRoom?->name ?? 'Halaqah Utama' }} &bull; {{ $snapshot['student']->school?->name ?? 'HafizPlus' }}
                                </p>
                            </div>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300 border border-emerald-200/50">Santri Aktif</span>
                    </div>

                    <div class="grid gap-3 grid-cols-3 mb-6">
                        <div class="rounded-2xl bg-slate-50/80 dark:bg-slate-850/60 p-4 border border-slate-100 dark:border-slate-800">
                            <div class="text-xs font-medium text-slate-400 dark:text-slate-500">Setoran Bulan Ini</div>
                            <div class="mt-1 text-2xl font-black text-slate-900 dark:text-white tracking-tight">{{ $snapshot['total_records'] }}</div>
                        </div>

                        <div class="rounded-2xl bg-emerald-50/50 dark:bg-emerald-950/30 p-4 border border-emerald-100/60 dark:border-emerald-900/30">
                            <div class="text-xs font-medium text-emerald-700 dark:text-emerald-400">Baris Disetor</div>
                            <div class="mt-1 text-2xl font-black text-emerald-800 dark:text-emerald-300 tracking-tight">{{ $snapshot['total_lines'] }}</div>
                        </div>

                        <div class="rounded-2xl bg-slate-50/80 dark:bg-slate-850/60 p-4 border border-slate-100 dark:border-slate-800">
                            <div class="text-xs font-medium text-slate-400 dark:text-slate-500">Hutang Target</div>
                            <div class="mt-1 text-2xl font-black {{ $snapshot['current_debt_lines'] > 0 ? 'text-amber-600 dark:text-amber-400' : 'text-slate-900 dark:text-white' }} tracking-tight">{{ $snapshot['current_debt_lines'] }}</div>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-2.5 pt-4 border-t border-slate-100 dark:border-slate-800/80">
                        <a href="{{ route('portal.parent.children.progress', $snapshot['student']) }}"
                           class="rounded-xl bg-emerald-600 px-4 py-2 text-xs font-bold text-white hover:bg-emerald-700 shadow-sm transition">
                            Lihat Progres &rarr;
                        </a>

                        <a href="{{ route('portal.parent.children.records', $snapshot['student']) }}"
                           class="rounded-xl border border-slate-200 dark:border-slate-700 px-4 py-2 text-xs font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition">
                            Riwayat Setoran
                        </a>

                        <a href="{{ route('portal.parent.children.monthly', $snapshot['student']) }}"
                           class="rounded-xl border border-slate-200 dark:border-slate-700 px-4 py-2 text-xs font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition">
                            Raport Bulanan
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection
