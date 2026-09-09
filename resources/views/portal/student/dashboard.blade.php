@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h2 class="text-2xl font-bold">Portal Santri</h2>
        <p class="text-sm text-slate-500">Pantau progres tahfizh pribadi.</p>
    </div>

    @if (! $student)
        <div class="rounded-2xl bg-white p-6 shadow-sm border border-slate-100">
            <h3 class="text-lg font-bold">Profil Santri Belum Terhubung</h3>
            <p class="mt-2 text-sm text-slate-600">
                Profil santri belum terhubung. Hubungi admin sekolah.
            </p>
        </div>
    @else
        <div class="mb-6">
            <h3 class="text-xl font-bold text-slate-900">{{ $student->full_name }}</h3>
            <p class="text-sm text-slate-500">{{ $student->classRoom?->name ?? '-' }}</p>
        </div>

        <div class="grid gap-4 md:grid-cols-4">
            <div class="rounded-2xl bg-white p-5 shadow-sm border border-slate-100">
                <div class="text-sm text-slate-500">Target Harian</div>
                <div class="mt-2 text-3xl font-bold text-slate-900">{{ $snapshot['target_daily_lines'] }}</div>
                <div class="text-xs text-slate-500">baris</div>
            </div>

            <div class="rounded-2xl bg-white p-5 shadow-sm border border-slate-100">
                <div class="text-sm text-slate-500">Capaian Bulan Ini</div>
                <div class="mt-2 text-3xl font-bold text-slate-900">{{ $snapshot['total_lines'] }}</div>
                <div class="text-xs text-slate-500">baris</div>
            </div>

            <div class="rounded-2xl bg-white p-5 shadow-sm border border-slate-100">
                <div class="text-sm text-slate-500">Setoran Bulan Ini</div>
                <div class="mt-2 text-3xl font-bold text-slate-900">{{ $snapshot['total_records'] }}</div>
            </div>

            <div class="rounded-2xl bg-white p-5 shadow-sm border border-slate-100">
                <div class="text-sm text-slate-500">Akumulasi Hutang</div>
                <div class="mt-2 text-3xl font-bold text-slate-900">{{ $snapshot['current_debt_lines'] }}</div>
                <div class="text-xs text-slate-500">baris</div>
            </div>
        </div>

        <div class="mt-6 flex flex-wrap gap-2">
            <a href="{{ route('portal.student.records') }}"
               class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">
                Riwayat Setoran
            </a>

            <a href="{{ route('portal.student.monthly') }}"
               class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                Laporan Bulanan
            </a>
        </div>

        <div class="mt-6 rounded-2xl bg-white p-6 shadow-sm border border-slate-100">
            <h3 class="mb-4 text-lg font-bold text-slate-950">Riwayat Terbaru</h3>

            <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3">Guru</th>
                        <th class="px-4 py-3">Rentang</th>
                        <th class="px-4 py-3">Baris</th>
                        <th class="px-4 py-3">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($snapshot['recent_records'] as $record)
                        <tr class="border-t hover:bg-slate-50">
                            <td class="px-4 py-3 font-semibold text-slate-700">{{ $record->record_date?->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 text-slate-900">{{ $record->teacher?->name ?? '-' }}</td>
                            <td class="px-4 py-3 text-xs text-slate-600">
                                Hlm {{ $record->start_page }}:{{ $record->start_line }}
                                —
                                Hlm {{ $record->end_page }}:{{ $record->end_line }}
                            </td>
                            <td class="px-4 py-3 font-bold text-slate-900">+{{ $record->total_lines }}</td>
                            <td class="px-4 py-3 text-xs">
                                @if ($record->status === \App\Models\HafalanRecord::STATUS_LUNAS)
                                    <span class="inline-block bg-green-100 text-green-800 px-2 py-0.5 rounded font-semibold">Lunas</span>
                                @elseif ($record->status === \App\Models\HafalanRecord::STATUS_KURANG)
                                    <span class="inline-block bg-red-100 text-red-800 px-2 py-0.5 rounded font-semibold">Kurang</span>
                                @elseif ($record->status === \App\Models\HafalanRecord::STATUS_LEBIH)
                                    <span class="inline-block bg-blue-100 text-blue-800 px-2 py-0.5 rounded font-semibold">Lebih</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-slate-500">
                                Belum ada setoran.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            </div>
        </div>
    @endif
@endsection
