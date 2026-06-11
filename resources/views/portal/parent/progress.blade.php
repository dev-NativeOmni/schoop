@extends('layouts.app')

@section('content')
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold">Progres Tahfizh Anak</h2>
            <p class="text-sm text-slate-500">
                {{ $student->full_name }} — {{ $student->classRoom?->name ?? '-' }}
            </p>
        </div>

        <a href="{{ route('portal.parent.dashboard') }}"
           class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
            Kembali
        </a>
    </div>

    <div class="grid gap-4 md:grid-cols-4">
        <div class="rounded-2xl bg-white p-5 shadow-sm border border-slate-100">
            <div class="text-sm text-slate-500">Target Harian</div>
            <div class="mt-2 text-3xl font-bold text-slate-900">{{ $snapshot['target_daily_lines'] }}</div>
            <div class="text-xs text-slate-500">baris</div>
        </div>

        <div class="rounded-2xl bg-white p-5 shadow-sm border border-slate-100">
            <div class="text-sm text-slate-500">Target Bulanan</div>
            <div class="mt-2 text-3xl font-bold text-slate-900">{{ $snapshot['target_monthly_lines'] }}</div>
            <div class="text-xs text-slate-500">baris</div>
        </div>

        <div class="rounded-2xl bg-white p-5 shadow-sm border border-slate-100">
            <div class="text-sm text-slate-500">Capaian Bulan Ini</div>
            <div class="mt-2 text-3xl font-bold text-slate-900">{{ $snapshot['total_lines'] }}</div>
            <div class="text-xs text-slate-500">baris</div>
        </div>

        <div class="rounded-2xl bg-white p-5 shadow-sm border border-slate-100">
            <div class="text-sm text-slate-500">Akumulasi Hutang</div>
            <div class="mt-2 text-3xl font-bold text-slate-900">{{ $snapshot['current_debt_lines'] }}</div>
            <div class="text-xs text-slate-500">baris</div>
        </div>
    </div>

    <div class="mt-6 grid gap-6 md:grid-cols-2">
        <div class="rounded-2xl bg-white p-6 shadow-sm border border-slate-100">
            <h3 class="mb-4 text-lg font-bold text-slate-950">Target Aktiv</h3>

            @if ($snapshot['active_target'])
                <dl class="space-y-3 text-sm">
                    <div>
                        <dt class="font-semibold text-slate-500 text-xs uppercase">Nama Target</dt>
                        <dd class="mt-0.5 text-slate-800 font-medium">{{ $snapshot['active_target']->name }}</dd>
                    </div>
                    <div>
                        <dt class="font-semibold text-slate-500 text-xs uppercase">Program</dt>
                        <dd class="mt-0.5 text-slate-800 font-medium">{{ $snapshot['active_target']->program_type ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="font-semibold text-slate-500 text-xs uppercase">Target Mingguan</dt>
                        <dd class="mt-0.5 text-slate-800 font-medium">{{ $snapshot['active_target']->weekly_target_lines }} baris</dd>
                    </div>
                    <div>
                        <dt class="font-semibold text-slate-500 text-xs uppercase">Target Bulanan</dt>
                        <dd class="mt-0.5 text-slate-800 font-medium">{{ $snapshot['active_target']->monthly_target_lines }} baris</dd>
                    </div>
                </dl>
            @else
                <p class="text-sm text-slate-500">Belum ada target aktif.</p>
            @endif
        </div>

        <div class="rounded-2xl bg-white p-6 shadow-sm border border-slate-100">
            <h3 class="mb-4 text-lg font-bold text-slate-950">Setoran Terakhir</h3>

            @if ($snapshot['latest_record'])
                <dl class="space-y-3 text-sm">
                    <div>
                        <dt class="font-semibold text-slate-500 text-xs uppercase">Tanggal</dt>
                        <dd class="mt-0.5 text-slate-800 font-medium">{{ $snapshot['latest_record']->record_date?->format('d/m/Y') }}</dd>
                    </div>
                    <div>
                        <dt class="font-semibold text-slate-500 text-xs uppercase">Guru</dt>
                        <dd class="mt-0.5 text-slate-800 font-medium">{{ $snapshot['latest_record']->teacher?->name ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="font-semibold text-slate-500 text-xs uppercase">Rentang</dt>
                        <dd class="mt-0.5 text-slate-800 font-medium">
                            Hlm {{ $snapshot['latest_record']->start_page }}:{{ $snapshot['latest_record']->start_line }}
                            —
                            Hlm {{ $snapshot['latest_record']->end_page }}:{{ $snapshot['latest_record']->end_line }}
                        </dd>
                    </div>
                    <div>
                        <dt class="font-semibold text-slate-500 text-xs uppercase">Catatan</dt>
                        <dd class="mt-0.5 text-slate-800 italic">"{{ $snapshot['latest_record']->notes ?? '-' }}"</dd>
                    </div>
                </dl>
            @else
                <p class="text-sm text-slate-500">Belum ada setoran.</p>
            @endif
        </div>
    </div>

    <div class="mt-6 rounded-2xl bg-white p-6 shadow-sm border border-slate-100">
        <div class="mb-4 flex items-center justify-between">
            <h3 class="text-lg font-bold text-slate-950">Riwayat Terbaru</h3>
            <a href="{{ route('portal.parent.children.records', $student) }}" class="text-sm font-semibold text-blue-700 hover:underline">
                Lihat semua
            </a>
        </div>

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
                        <td class="px-4 py-3">
                            Hlm {{ $record->start_page }}:{{ $record->start_line }}
                            —
                            Hlm {{ $record->end_page }}:{{ $record->end_line }}
                        </td>
                        <td class="px-4 py-3 font-bold text-slate-900">+{{ $record->total_lines }}</td>
                        <td class="px-4 py-3">
                            @if ($record->status === \App\Models\HafalanRecord::STATUS_LUNAS)
                                <span class="inline-block bg-green-100 text-green-800 px-2 py-0.5 rounded text-xs font-semibold">Lunas</span>
                            @elseif ($record->status === \App\Models\HafalanRecord::STATUS_KURANG)
                                <span class="inline-block bg-red-100 text-red-800 px-2 py-0.5 rounded text-xs font-semibold">Kurang</span>
                            @elseif ($record->status === \App\Models\HafalanRecord::STATUS_LEBIH)
                                <span class="inline-block bg-blue-100 text-blue-800 px-2 py-0.5 rounded text-xs font-semibold">Lebih</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-slate-500">
                            Belum ada riwayat setoran.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
