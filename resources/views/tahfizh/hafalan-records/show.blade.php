@extends('layouts.app')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold">Detail Setoran Tahfizh</h2>
            <p class="text-sm text-slate-500">Informasi lengkap setoran hafalan.</p>
        </div>

        <a href="{{ route('tahfizh.hafalan-records.index') }}"
           class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
            Kembali
        </a>
    </div>

    <div class="grid gap-6 md:grid-cols-2">
        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <h3 class="mb-4 text-lg font-bold">Data Setoran</h3>

            <dl class="space-y-3 text-sm">
                <div>
                    <dt class="font-semibold text-slate-500">Tanggal</dt>
                    <dd class="mt-1 font-medium">{{ $record->record_date?->format('d/m/Y') }}</dd>
                </div>

                <div>
                    <dt class="font-semibold text-slate-500">Santri</dt>
                    <dd class="mt-1 font-medium">{{ $record->student?->full_name }}</dd>
                </div>

                <div>
                    <dt class="font-semibold text-slate-500">Kelas</dt>
                    <dd class="mt-1 font-medium">{{ $record->student?->classRoom?->name ?? '-' }}</dd>
                </div>

                <div>
                    <dt class="font-semibold text-slate-500">Guru Penerima</dt>
                    <dd class="mt-1 font-medium">{{ $record->teacher?->name ?? '-' }}</dd>
                </div>

                <div>
                    <dt class="font-semibold text-slate-500">Target Tahfizh</dt>
                    <dd class="mt-1 font-medium">{{ $record->tahfizhTarget?->name ?? '-' }}</dd>
                </div>

                <div>
                    <dt class="font-semibold text-slate-500">Status</dt>
                    <dd class="mt-1 font-medium">{{ strtoupper(str_replace('_', ' ', $record->status)) }}</dd>
                </div>
            </dl>
        </div>

        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <h3 class="mb-4 text-lg font-bold">Rentang Hafalan</h3>

            <dl class="space-y-3 text-sm">
                <div>
                    <dt class="font-semibold text-slate-500">Mulai</dt>
                    <dd class="mt-1 font-medium">Halaman {{ $record->start_page }}, Baris {{ $record->start_line }}</dd>
                </div>

                <div>
                    <dt class="font-semibold text-slate-500">Selesai</dt>
                    <dd class="mt-1 font-medium">Halaman {{ $record->end_page }}, Baris {{ $record->end_line }}</dd>
                </div>

                <div>
                    <dt class="font-semibold text-slate-500">Total Baris</dt>
                    <dd class="mt-1 font-medium">{{ $record->total_lines }} baris</dd>
                </div>

                <div>
                    <dt class="font-semibold text-slate-500">Surah Awal</dt>
                    <dd class="mt-1 font-medium">{{ $record->startSurah?->name_latin ?? '-' }} {{ $record->start_ayah ? 'ayat '.$record->start_ayah : '' }}</dd>
                </div>

                <div>
                    <dt class="font-semibold text-slate-500">Surah Akhir</dt>
                    <dd class="mt-1 font-medium">{{ $record->endSurah?->name_latin ?? '-' }} {{ $record->end_ayah ? 'ayat '.$record->end_ayah : '' }}</dd>
                </div>

                <div>
                    <dt class="font-semibold text-slate-500">Kesesuaian Urutan (Sequential)</dt>
                    <dd class="mt-1 font-medium">{{ $record->is_sequence_valid ? 'Valid (Berurutan)' : 'Tidak Valid' }}</dd>
                </div>

                <div>
                    <dt class="font-semibold text-slate-500">Catatan Sequence</dt>
                    <dd class="mt-1 font-medium text-slate-600">{{ $record->sequence_note ?? '-' }}</dd>
                </div>
            </dl>
        </div>
    </div>

    <div class="mt-6 rounded-2xl bg-white p-6 shadow-sm">
        <h3 class="mb-4 text-lg font-bold">Catatan Tambahan</h3>
        <p class="text-sm text-slate-700">{{ $record->notes ?? '-' }}</p>
    </div>
@endsection
