@extends('layouts.app')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold">Detail Sekolah</h2>
            <p class="text-sm text-slate-500">Informasi lengkap sekolah.</p>
        </div>

        <a href="{{ route('master-data.schools.index') }}"
           class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
            Kembali
        </a>
    </div>

    <div class="max-w-xl rounded-2xl bg-white p-6 shadow-sm space-y-4">
        <div>
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Nama Sekolah</h3>
            <p class="text-lg font-semibold text-slate-900">{{ $school->name }}</p>
        </div>

        <div>
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Kode</h3>
            <p class="text-base text-slate-700">{{ $school->code }}</p>
        </div>

        <div>
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">NPSN</h3>
            <p class="text-base text-slate-700">{{ $school->npsn ?? '-' }}</p>
        </div>

        <div>
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Email</h3>
            <p class="text-base text-slate-700">{{ $school->email ?? '-' }}</p>
        </div>

        <div>
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Telepon</h3>
            <p class="text-base text-slate-700">{{ $school->phone ?? '-' }}</p>
        </div>

        <div>
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Alamat</h3>
            <p class="text-base text-slate-700">{{ $school->address ?? '-' }}</p>
        </div>

        <div class="flex gap-4">
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Warna Utama</h3>
                <div class="flex items-center gap-2 mt-1">
                    <span class="inline-block w-6 h-6 rounded border" style="background-color: {{ $school->primary_color ?? '#fff' }}"></span>
                    <span class="text-sm font-mono">{{ $school->primary_color ?? '-' }}</span>
                </div>
            </div>

            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Warna Sekunder</h3>
                <div class="flex items-center gap-2 mt-1">
                    <span class="inline-block w-6 h-6 rounded border" style="background-color: {{ $school->secondary_color ?? '#fff' }}"></span>
                    <span class="text-sm font-mono">{{ $school->secondary_color ?? '-' }}</span>
                </div>
            </div>
        </div>

        <div>
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Status</h3>
            <p class="text-base mt-1">
                <span class="inline-block rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $school->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $school->is_active ? 'Aktif' : 'Nonaktif' }}
                </span>
            </p>
        </div>
    </div>
@endsection
