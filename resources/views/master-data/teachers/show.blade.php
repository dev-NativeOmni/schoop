@extends('layouts.app')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold">Detail Guru</h2>
            <p class="text-sm text-slate-500">Detail data guru tahfidz.</p>
        </div>

        <a href="{{ route('master-data.teachers.index') }}"
           class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
            Kembali
        </a>
    </div>

    <div class="max-w-xl rounded-2xl bg-white p-6 shadow-sm space-y-4">
        <div>
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Nama Lengkap</h3>
            <p class="text-lg font-semibold text-slate-900">{{ $teacher->user?->name }}</p>
        </div>

        <div>
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Username</h3>
            <p class="text-base text-slate-700">{{ $teacher->user?->username }}</p>
        </div>

        <div>
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Email</h3>
            <p class="text-base text-slate-700">{{ $teacher->user?->email }}</p>
        </div>

        <div>
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Telepon / HP</h3>
            <p class="text-base text-slate-700">{{ $teacher->user?->phone ?? '-' }}</p>
        </div>

        <div>
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">NIP / Nomor Pegawai</h3>
            <p class="text-base text-slate-700">{{ $teacher->employee_number ?? '-' }}</p>
        </div>

        <div>
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Spesialisasi</h3>
            <p class="text-base text-slate-700">{{ $teacher->specialization ?? '-' }}</p>
        </div>

        <div>
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Sekolah</h3>
            <p class="text-base text-slate-700">{{ $teacher->school?->name }}</p>
        </div>

        <div>
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Alamat</h3>
            <p class="text-base text-slate-700">{{ $teacher->address ?? '-' }}</p>
        </div>

        <div>
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Tanggal Bergabung</h3>
            <p class="text-base text-slate-700">{{ $teacher->joined_at ? $teacher->joined_at->format('d M Y') : '-' }}</p>
        </div>

        <div>
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Status</h3>
            <p class="text-base mt-1">
                <span class="inline-block rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $teacher->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $teacher->is_active ? 'Aktif' : 'Nonaktif' }}
                </span>
            </p>
        </div>
    </div>
@endsection
