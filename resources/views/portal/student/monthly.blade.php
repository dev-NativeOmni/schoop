@extends('layouts.app')

@section('content')
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold">Laporan Bulanan Saya</h2>
            <p class="text-sm text-slate-500">
                {{ $student?->full_name ?? 'Profil belum terhubung' }}
            </p>
        </div>

        <a href="{{ route('portal.student.dashboard') }}"
           class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
            Kembali
        </a>
    </div>

    @if (! $student)
        <div class="rounded-2xl bg-white p-6 shadow-sm border border-slate-100">
            <p class="text-sm text-slate-600">
                Profil santri belum terhubung. Hubungi admin sekolah.
            </p>
        </div>
    @else
        <form method="GET" action="{{ route('portal.student.monthly') }}"
              class="mb-6 grid gap-4 rounded-2xl bg-white p-4 shadow-sm border border-slate-100 md:grid-cols-2 items-end">
            <div>
                <label class="mb-1 block text-xs font-semibold text-slate-500 uppercase">Bulan</label>
                <input type="month" name="month" value="{{ request('month', $report['month']) }}"
                       class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm">
            </div>

            <div>
                <button type="submit"
                        class="w-full rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">
                    Tampilkan
                </button>
            </div>
        </form>

        <div class="mb-6 grid gap-4 md:grid-cols-5">
            <div class="rounded-2xl bg-white p-5 shadow-sm border border-slate-100">
                <div class="text-xs font-semibold text-slate-500 uppercase">Setoran</div>
                <div class="mt-2 text-3xl font-bold text-slate-900">{{ $report['total_records'] }}</div>
            </div>

            <div class="rounded-2xl bg-white p-5 shadow-sm border border-slate-100">
                <div class="text-xs font-semibold text-slate-500 uppercase">Capaian</div>
                <div class="mt-2 text-3xl font-bold text-slate-900">{{ $report['total_lines'] }}</div>
            </div>

            <div class="rounded-2xl bg-white p-5 shadow-sm border border-slate-100">
                <div class="text-xs font-semibold text-slate-500 uppercase">Target</div>
                <div class="mt-2 text-3xl font-bold text-slate-900">{{ $report['debt']?->target_lines ?? 0 }}</div>
            </div>

            <div class="rounded-2xl bg-white p-5 shadow-sm border border-slate-100">
                <div class="text-xs font-semibold text-slate-500 uppercase">Hutang</div>
                <div class="mt-2 text-3xl font-bold text-slate-900">{{ $report['debt']?->debt_lines ?? 0 }}</div>
            </div>

            <div class="rounded-2xl bg-white p-5 shadow-sm border border-slate-100">
                <div class="text-xs font-semibold text-slate-500 uppercase">Akumulasi</div>
                <div class="mt-2 text-3xl font-bold text-slate-900">{{ $report['debt']?->cumulative_debt_lines ?? 0 }}</div>
            </div>
        </div>

        <div class="overflow-hidden rounded-2xl bg-white shadow-sm border border-slate-100">
            <table class="w-full border-collapse text-left text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3">Guru</th>
                        <th class="px-4 py-3">Rentang</th>
                        <th class="px-4 py-3">Baris</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($report['records'] as $record)
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
                            <td class="px-4 py-3 text-slate-600 italic">"{{ $record->notes ?? '-' }}"</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-slate-500">
                                Belum ada setoran pada bulan ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif
@endsection
