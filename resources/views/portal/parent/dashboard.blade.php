@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h2 class="text-2xl font-bold">Portal Orang Tua</h2>
        <p class="text-sm text-slate-500">Pantau progres tahfizh anak.</p>
    </div>

    @if ($children->isEmpty())
        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <h3 class="text-lg font-bold">Belum Ada Anak Terhubung</h3>
            <p class="mt-2 text-sm text-slate-600">
                Akun orang tua ini belum terhubung dengan data santri. Hubungi admin sekolah.
            </p>
        </div>
    @else
        <div class="grid gap-6 md:grid-cols-2">
            @foreach ($snapshots as $snapshot)
                <div class="rounded-2xl bg-white p-6 shadow-sm border border-slate-100">
                    <div class="mb-4">
                        <h3 class="text-xl font-bold">{{ $snapshot['student']->full_name }}</h3>
                        <p class="text-sm text-slate-500">
                            {{ $snapshot['student']->classRoom?->name ?? '-' }}
                        </p>
                    </div>

                    <div class="grid gap-3 grid-cols-3">
                        <div class="rounded-xl bg-slate-50 p-4">
                            <div class="text-xs text-slate-500">Setoran Bulan Ini</div>
                            <div class="mt-1 text-2xl font-bold text-slate-900">{{ $snapshot['total_records'] }}</div>
                        </div>

                        <div class="rounded-xl bg-slate-50 p-4">
                            <div class="text-xs text-slate-500">Baris Bulan Ini</div>
                            <div class="mt-1 text-2xl font-bold text-slate-900">{{ $snapshot['total_lines'] }}</div>
                        </div>

                        <div class="rounded-xl bg-slate-50 p-4">
                            <div class="text-xs text-slate-500">Hutang Aktif</div>
                            <div class="mt-1 text-2xl font-bold text-slate-900">{{ $snapshot['current_debt_lines'] }}</div>
                        </div>
                    </div>

                    <div class="mt-5 flex flex-wrap gap-2">
                        <a href="{{ route('portal.parent.children.progress', $snapshot['student']) }}"
                           class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">
                            Lihat Progres
                        </a>

                        <a href="{{ route('portal.parent.children.records', $snapshot['student']) }}"
                           class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                            Riwayat Setoran
                        </a>

                        <a href="{{ route('portal.parent.children.monthly', $snapshot['student']) }}"
                           class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                            Laporan Bulanan
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection
