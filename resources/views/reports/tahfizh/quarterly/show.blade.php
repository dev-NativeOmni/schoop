@extends('layouts.app')

@section('content')
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <div>
            <a href="{{ route('reports.tahfizh.quarterly.index', ['year' => $year, 'quarter' => $quarter]) }}"
               class="text-sm font-semibold text-slate-600 hover:text-slate-900">
                &larr; Kembali ke Laporan Triwulan
            </a>
            <h2 class="text-2xl font-bold mt-2">Detail Laporan Triwulan</h2>
            <p class="text-sm text-slate-500">
                {{ $student->full_name }} — {{ $periodStart->format('d/m/Y') }} sampai {{ $periodEnd->format('d/m/Y') }} ({{ $label }})
            </p>
        </div>
    </div>

    <!-- Student and Class Summary -->
    <div class="mb-6 rounded-2xl bg-white p-5 shadow-sm border border-slate-100 max-w-xl">
        <h3 class="text-base font-bold text-slate-900 mb-4">Informasi Akademik</h3>
        <dl class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <dt class="font-semibold text-slate-500 text-xs uppercase">Nama Lengkap</dt>
                <dd class="mt-0.5 font-bold text-slate-900">{{ $student->full_name }}</dd>
            </div>
            <div>
                <dt class="font-semibold text-slate-500 text-xs uppercase">Kelas</dt>
                <dd class="mt-0.5 font-bold text-slate-900">{{ $student->classRoom?->name ?? '-' }}</dd>
            </div>
            <div>
                <dt class="font-semibold text-slate-500 text-xs uppercase">No. Induk / NISN</dt>
                <dd class="mt-0.5 text-slate-700">
                    {{ $student->student_number ?? '-' }} / {{ $student->nisn ?? '-' }}
                </dd>
            </div>
            <div>
                <dt class="font-semibold text-slate-500 text-xs uppercase">Sekolah</dt>
                <dd class="mt-0.5 text-slate-700">{{ $student->school->name }}</dd>
            </div>
        </dl>
    </div>

    <!-- Records Table -->
    <div class="overflow-hidden rounded-2xl bg-white shadow-sm border border-slate-100">
        <table class="w-full border-collapse text-left text-sm">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-4 py-3">Tanggal</th>
                    <th class="px-4 py-3">Guru</th>
                    <th class="px-4 py-3">Rentang Ayat</th>
                    <th class="px-4 py-3">Total Baris</th>
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
                                {{ $record->startSurah?->name_latin ?? '-' }} (Ayat {{ $record->start_ayah }})
                            </span>
                            <span class="text-xs text-slate-400 block mt-0.5">
                                Hlm {{ $record->start_page }}:{{ $record->start_line }}
                                &rarr;
                                Hlm {{ $record->end_page }}:{{ $record->end_line }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-indigo-700 font-semibold">+{{ $record->total_lines }}</td>
                        <td class="px-4 py-3">
                            @if ($record->status === \App\Models\HafalanRecord::STATUS_LUNAS)
                                <span class="inline-block bg-green-100 text-green-800 px-2 py-0.5 rounded text-xs font-semibold">Lunas</span>
                            @elseif ($record->status === \App\Models\HafalanRecord::STATUS_KURANG)
                                <span class="inline-block bg-red-100 text-red-800 px-2 py-0.5 rounded text-xs font-semibold">Kurang</span>
                            @elseif ($record->status === \App\Models\HafalanRecord::STATUS_LEBIH)
                                <span class="inline-block bg-blue-100 text-blue-800 px-2 py-0.5 rounded text-xs font-semibold">Lebih</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-slate-600 italic">
                            "{{ $record->notes ?? '-' }}"
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-slate-500">
                            Belum ada setoran pada triwulan ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
