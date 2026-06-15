@extends('layouts.app')

@section('content')
<div class="space-y-6 animate-fade-in">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-slate-200 pb-4 dark:border-slate-800">
        <div>
            <div class="flex items-center gap-2">
                <a href="{{ route('boarding.roll-calls.index') }}" class="text-sm font-semibold text-emerald-500 hover:underline">Daftar Roll Call</a>
                <span class="text-slate-400">/</span>
                <span class="text-sm text-slate-500 dark:text-slate-400">Detail Sesi Absensi</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white mt-1">Sesi Absensi: {{ \Carbon\Carbon::parse($rollCall->session_date)->format('d M Y') }}</h1>
            <p class="text-sm text-slate-500">Asrama: {{ $rollCall->dormitory->name ?? 'Semua Asrama' }} 
                @if($rollCall->room) | Kamar: {{ $rollCall->room->name }} @endif
                | Sesi: 
                <span class="font-semibold uppercase text-slate-700 dark:text-slate-350">
                    @if($rollCall->session_type === 'morning') Pagi
                    @elseif($rollCall->session_type === 'afternoon') Sore
                    @elseif($rollCall->session_type === 'night') Malam
                    @else Kustom
                    @endif
                </span>
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('boarding.roll-calls.index') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-350 dark:hover:bg-slate-950">
                Kembali
            </a>
        </div>
    </div>

    <!-- Alert success/error -->
    @if($errors->any())
        <div class="rounded-xl bg-rose-50 p-4 text-sm text-rose-850 dark:bg-rose-950/30 dark:text-rose-400">
            <ul class="list-disc pl-5 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if(session('success'))
        <div class="rounded-xl bg-emerald-50 p-4 text-sm text-emerald-800 dark:bg-emerald-950/30 dark:text-emerald-400">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Main Form Column -->
        <div class="lg:col-span-3 space-y-6">
            <form action="{{ route('boarding.roll-calls.records.store', $rollCall->id) }}" method="POST" class="space-y-4">
                @csrf
                
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <div class="flex items-center justify-between mb-6 border-b border-slate-100 pb-4 dark:border-slate-800">
                        <h2 class="text-lg font-bold text-slate-800 dark:text-white">Daftar Kehadiran Santri</h2>
                        <span class="text-xs text-slate-550 dark:text-slate-400">Menampilkan santri yang aktif bertempat di asrama terkait.</span>
                    </div>

                    @if($records->isEmpty())
                        <div class="text-center py-12 text-slate-400">
                            Tidak ada santri yang terdaftar dengan penempatan aktif di asrama/kamar ini pada sesi ini.
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($records as $index => $record)
                                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 p-4 rounded-xl border border-slate-150 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/10 hover:border-slate-350 dark:hover:border-slate-700 transition">
                                    <!-- Student Info -->
                                    <div class="flex-1">
                                        <input type="hidden" name="records[{{ $index }}][student_id]" value="{{ $record->student_id }}">
                                        <span class="font-bold text-slate-800 dark:text-white block">{{ $record->student->full_name }}</span>
                                        <span class="block text-2xs text-slate-450 mt-0.5">NIS: {{ $record->student->student_number ?? '-' }} | Kelas: {{ $record->student->classRoom->name ?? '-' }}</span>
                                    </div>
                                    
                                    <!-- Attendance Actions -->
                                    <div class="flex flex-wrap items-center gap-3">
                                        <!-- Status Radio Select -->
                                        <div class="flex items-center gap-2">
                                            @foreach(['present' => 'Hadir', 'late' => 'Terlambat', 'permission' => 'Izin', 'sick' => 'Sakit', 'absent' => 'Alpha'] as $status => $label)
                                                <label class="inline-flex items-center cursor-pointer">
                                                    <input type="radio" name="records[{{ $index }}][status]" value="{{ $status }}" {{ old("records.{$index}.status", $record->status) === $status ? 'checked' : '' }} {{ $rollCall->status === 'closed' && !(auth()->user()->isSuperAdmin() || auth()->user()->isAdmin()) ? 'disabled' : '' }} class="sr-only peer">
                                                    <div class="px-2.5 py-1 text-2xs font-semibold rounded-lg border border-slate-200 text-slate-600 bg-white peer-checked:bg-emerald-500 peer-checked:text-white peer-checked:border-emerald-500 transition-all dark:border-slate-800 dark:bg-slate-950 dark:text-slate-400 peer-checked:dark:bg-emerald-500 peer-checked:dark:text-white">
                                                        {{ $label }}
                                                    </div>
                                                </label>
                                            @endforeach
                                        </div>

                                        <!-- Notes -->
                                        <div>
                                            <input type="text" name="records[{{ $index }}][note]" value="{{ old("records.{$index}.note", $record->note) }}" {{ $rollCall->status === 'closed' && !(auth()->user()->isSuperAdmin() || auth()->user()->isAdmin()) ? 'disabled' : '' }} placeholder="Keterangan..." class="rounded-xl border-slate-200 text-2xs py-1 px-2.5 w-44 focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-800 dark:bg-slate-950 dark:text-white">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Submit Buttons -->
                        @if($rollCall->status === 'open' || (auth()->user()->isSuperAdmin() || auth()->user()->isAdmin()))
                            <div class="border-t border-slate-100 pt-6 mt-6 dark:border-slate-800 flex justify-end gap-3">
                                <button type="submit" class="rounded-xl bg-gradient-to-r from-emerald-500 to-teal-600 px-6 py-2.5 text-sm font-semibold text-white shadow-md shadow-emerald-200 hover:opacity-90 transition dark:shadow-none">Simpan Data Absen</button>
                            </div>
                        @endif
                    @endif
                </div>
            </form>
        </div>

        <!-- Sidebar Actions Column -->
        <div class="space-y-6">
            <!-- Session Status Card -->
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 space-y-4">
                <h3 class="text-md font-bold text-slate-800 dark:text-white">Status Sesi</h3>
                
                <div class="flex items-center gap-2">
                    <span class="w-3.5 h-3.5 rounded-full @if($rollCall->status === 'open') bg-green-500 @else bg-slate-400 @endif animate-pulse"></span>
                    <span class="font-bold text-sm text-slate-800 dark:text-white uppercase">{{ $rollCall->status === 'open' ? 'Terbuka' : 'Terkunci / Selesai' }}</span>
                </div>
                
                @if($rollCall->status === 'open')
                    @if(auth()->user()->isSuperAdmin() || auth()->user()->isAdmin() || auth()->user()->isPrincipal() || auth()->user()->isBoardingSupervisor())
                        <div class="pt-3 border-t border-slate-150 dark:border-slate-800">
                            <p class="text-2xs text-slate-500 mb-3">Mengunci sesi absensi mencegah perubahan data di kemudian hari kecuali oleh Administrator.</p>
                            
                            <form action="{{ route('boarding.roll-calls.close', $rollCall->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mengunci sesi absensi ini? Setelah dikunci, data tidak dapat diubah lagi.');">
                                @csrf
                                <button type="submit" class="w-full rounded-xl bg-rose-500 py-2 text-xs font-semibold text-white shadow-md hover:bg-rose-600 transition">Kunci & Tutup Sesi</button>
                            </form>
                        </div>
                    @endif
                @else
                    <p class="text-2xs text-slate-500 bg-slate-50 dark:bg-slate-950 p-3 rounded-xl border border-slate-100 dark:border-slate-800">Sesi absensi ini sudah dikunci oleh {{ $rollCall->creator->name ?? '-' }} pada {{ $rollCall->updated_at->format('d M Y H:i') }}.</p>
                @endif
            </div>

            <!-- Summary Statistics Card -->
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 space-y-3">
                <h3 class="text-md font-bold text-slate-800 dark:text-white">Rekap Absensi</h3>
                
                <div class="space-y-2 text-xs font-medium text-slate-600 dark:text-slate-400">
                    <div class="flex justify-between">
                        <span>Hadir (Present):</span>
                        <span class="font-bold text-emerald-600">{{ $records->where('status', 'present')->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Terlambat (Late):</span>
                        <span class="font-bold text-amber-600">{{ $records->where('status', 'late')->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Izin (Permission):</span>
                        <span class="font-bold text-blue-600">{{ $records->where('status', 'permission')->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Sakit (Sick):</span>
                        <span class="font-bold text-pink-600">{{ $records->where('status', 'sick')->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Alpha (Absent):</span>
                        <span class="font-bold text-rose-600">{{ $records->where('status', 'absent')->count() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
