@extends('layouts.app')

@section('content')
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold">Riwayat Setoran Saya</h2>
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
        <form method="GET" action="{{ route('portal.student.records') }}"
              class="mb-6 grid gap-4 rounded-2xl bg-white p-4 shadow-sm border border-slate-100 md:grid-cols-3 items-end">
            <div>
                <label class="mb-1 block text-xs font-semibold text-slate-500 uppercase">Dari</label>
                <input type="date" name="date_from" value="{{ request('date_from', $dateFrom) }}"
                       class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm">
            </div>

            <div>
                <label class="mb-1 block text-xs font-semibold text-slate-500 uppercase">Sampai</label>
                <input type="date" name="date_until" value="{{ request('date_until', $dateUntil) }}"
                       class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm">
            </div>

            <div class="flex gap-2">
                <button type="submit"
                        class="flex-1 rounded-lg bg-slate-900 py-2 px-3 text-sm font-semibold text-white hover:bg-slate-700">
                    Filter
                </button>
                <a href="{{ route('portal.student.records') }}"
                   class="rounded-lg bg-slate-100 py-2 px-3 text-sm font-semibold text-slate-700 hover:bg-slate-200 text-center">
                    Reset
                </a>
            </div>
        </form>

        <div class="overflow-hidden rounded-2xl bg-white shadow-sm border border-slate-100">
            <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3">Guru</th>
                        <th class="px-4 py-3">Surah</th>
                        <th class="px-4 py-3">Rentang</th>
                        <th class="px-4 py-3">Baris</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($records as $record)
                        <tr class="border-t hover:bg-slate-50">
                            <td class="px-4 py-3 font-semibold text-slate-700">
                                {{ $record->record_date?->format('d/m/Y') }}
                            </td>
                            <td class="px-4 py-3 text-slate-900">{{ $record->teacher?->name ?? '-' }}</td>
                            <td class="px-4 py-3">
                                <span class="font-semibold text-slate-800">
                                    {{ $record->startSurah?->name_latin ?? '-' }}
                                </span>
                                @if($record->start_surah_id !== $record->end_surah_id)
                                    <span class="text-xs text-slate-400">ke</span>
                                    <span class="font-semibold text-slate-800">
                                        {{ $record->endSurah?->name_latin ?? '-' }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-xs text-slate-500">
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
                            <td colspan="7" class="px-4 py-6 text-center text-slate-500">
                                Belum ada setoran pada periode ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            </div>
        </div>

        <div class="mt-6">
            {{ $records->links() }}
        </div>
    @endif
@endsection
