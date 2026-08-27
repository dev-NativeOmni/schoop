@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-slate-200/80 pb-5 dark:border-slate-800 print:hidden">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('boarding.leave-requests.index') }}" class="text-xs font-semibold text-emerald-600 dark:text-emerald-400 hover:underline flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                    Daftar Perizinan
                </a>
                <span class="text-slate-300 dark:text-slate-700">/</span>
                <span class="text-xs text-slate-500 dark:text-slate-400">Detail Pas Keluar</span>
            </div>
            <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white">
                Pas Keluar & Izin Santri
            </h1>
            <p class="text-xs text-slate-500 dark:text-slate-400">Verifikasi keberadaan dan surat jalan santri keluar/pulang asrama.</p>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs font-bold text-slate-700 hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-slate-800 transition shadow-2xs">
                <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                <span>Cetak Pas Keluar</span>
            </button>
            <a href="{{ route('boarding.leave-requests.index') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-bold text-slate-600 hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-slate-800 transition shadow-2xs">
                &larr; Kembali
            </a>
        </div>
    </div>

    {{-- Alerts --}}
    @if($errors->any())
        <div class="rounded-xl bg-rose-50 p-4 text-xs text-rose-800 border border-rose-200 dark:bg-rose-950/30 dark:text-rose-300 dark:border-rose-900/40 print:hidden">
            <ul class="list-disc pl-5 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if(session('success'))
        <div class="rounded-xl bg-emerald-50 p-4 text-xs font-bold text-emerald-800 border border-emerald-200 dark:bg-emerald-950/30 dark:text-emerald-300 dark:border-emerald-900/40 flex items-center gap-2 print:hidden">
            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- Visual Status Step Progress --}}
    <div class="card-natural p-5 print:hidden">
        <div class="grid grid-cols-4 gap-2 text-center text-xs">
            {{-- Step 1: Diajukan --}}
            <div class="flex flex-col items-center">
                <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs mb-1.5 bg-emerald-600 text-white shadow-sm">
                    1
                </div>
                <span class="font-bold text-slate-800 dark:text-slate-200">Diajukan</span>
                <span class="text-[10px] text-slate-400">{{ $leaveRequest->created_at->format('d/m H:i') }}</span>
            </div>

            {{-- Step 2: Persetujuan --}}
            <div class="flex flex-col items-center">
                <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs mb-1.5
                    @if(in_array($leaveRequest->status, ['approved', 'returned'])) bg-emerald-600 text-white shadow-sm
                    @elseif($leaveRequest->status === 'rejected') bg-rose-600 text-white
                    @else bg-slate-200 dark:bg-slate-800 text-slate-500 @endif">
                    2
                </div>
                <span class="font-bold text-slate-800 dark:text-slate-200">
                    @if($leaveRequest->status === 'rejected') Ditolak @else Disetujui @endif
                </span>
                <span class="text-[10px] text-slate-400">
                    {{ $leaveRequest->approvedBy ? ($leaveRequest->updated_at->format('d/m H:i')) : 'Menunggu' }}
                </span>
            </div>

            {{-- Step 3: Check-out Gerbang --}}
            <div class="flex flex-col items-center">
                <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs mb-1.5
                    @if(in_array($leaveRequest->status, ['approved', 'returned'])) bg-emerald-600 text-white shadow-sm
                    @else bg-slate-200 dark:bg-slate-800 text-slate-500 @endif">
                    3
                </div>
                <span class="font-bold text-slate-800 dark:text-slate-200">Keluar Asrama</span>
                <span class="text-[10px] text-slate-400">{{ \Carbon\Carbon::parse($leaveRequest->leave_start_at)->format('d/m H:i') }}</span>
            </div>

            {{-- Step 4: Kembali (Check-in) --}}
            <div class="flex flex-col items-center">
                <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs mb-1.5
                    @if($leaveRequest->status === 'returned') bg-emerald-600 text-white shadow-sm
                    @else bg-slate-200 dark:bg-slate-800 text-slate-500 @endif">
                    4
                </div>
                <span class="font-bold text-slate-800 dark:text-slate-200">Tiba Kembali</span>
                <span class="text-[10px] text-slate-400">
                    {{ $leaveRequest->returned_at ? \Carbon\Carbon::parse($leaveRequest->returned_at)->format('d/m H:i') : 'Belum Kembali' }}
                </span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Main Digital Gate Pass Card --}}
        <div class="md:col-span-2 space-y-6">
            <div class="card-natural p-6 border-2 relative overflow-hidden
                @if($leaveRequest->status === 'approved') border-emerald-500/40 dark:border-emerald-500/30
                @elseif($leaveRequest->status === 'returned') border-slate-300 dark:border-slate-700
                @elseif($leaveRequest->status === 'rejected') border-rose-500/40
                @else border-amber-500/40 @endif">

                {{-- Watermark Status Badge --}}
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-800">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white font-extrabold flex items-center justify-center shadow-2xs">
                            HP
                        </div>
                        <div>
                            <h3 class="text-xs font-black uppercase tracking-wider text-slate-900 dark:text-white">SURAT PAS KELUAR ASRAMA</h3>
                            <p class="text-[11px] text-slate-500">No. Registrasi: #LV-{{ str_pad($leaveRequest->id, 5, '0', STR_PAD_LEFT) }}</p>
                        </div>
                    </div>

                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-extrabold uppercase
                        @if($leaveRequest->status === 'submitted') bg-amber-50 text-amber-700 dark:bg-amber-950/40 dark:text-amber-300 border border-amber-200
                        @elseif($leaveRequest->status === 'approved') bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300 border border-emerald-200
                        @elseif($leaveRequest->status === 'returned') bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300 border border-slate-200
                        @elseif($leaveRequest->status === 'rejected') bg-rose-50 text-rose-700 dark:bg-rose-950/40 dark:text-rose-300 border border-rose-200
                        @else bg-slate-100 text-slate-500 @endif">
                        @if($leaveRequest->status === 'submitted') ⏳ Menunggu Persetujuan
                        @elseif($leaveRequest->status === 'approved') 🟢 Izin Aktif (Di Luar)
                        @elseif($leaveRequest->status === 'returned') 🏁 Sudah Kembali
                        @elseif($leaveRequest->status === 'rejected') ❌ Ditolak
                        @else ⚪ Dibatalkan @endif
                    </span>
                </div>

                {{-- Student Profile In Gate Pass --}}
                <div class="my-5 p-4 rounded-2xl bg-slate-50/80 dark:bg-slate-950/40 border border-slate-200/60 dark:border-slate-800 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-emerald-700 text-white font-black text-base flex items-center justify-center shadow-xs shrink-0">
                        {{ strtoupper(substr($leaveRequest->student->full_name, 0, 2)) }}
                    </div>
                    <div>
                        <h4 class="text-base font-extrabold text-slate-900 dark:text-white">{{ $leaveRequest->student->full_name }}</h4>
                        <div class="flex flex-wrap items-center gap-2 text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                            <span>NIS: <b>{{ $leaveRequest->student->student_number ?? ($leaveRequest->student->nis ?? '-') }}</b></span>
                            <span>&bull;</span>
                            <span>Kelas: <b>{{ $leaveRequest->student->classRoom->name ?? 'Tanpa Kelas' }}</b></span>
                            @if($leaveRequest->student->activeBoardingAssignment)
                                <span>&bull;</span>
                                <span>Kamar: <b>{{ $leaveRequest->student->activeBoardingAssignment->room->name ?? '-' }}</b></span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Schedule & Details Grid --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div class="p-3.5 rounded-xl border border-slate-150 dark:border-slate-800 bg-white dark:bg-slate-900">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Jenis Perizinan</span>
                        <span class="text-sm font-bold text-slate-800 dark:text-white">
                            @if($leaveRequest->type === 'short_leave') ⏱️ Keluar Sebentar (Short Leave)
                            @elseif($leaveRequest->type === 'overnight_leave') 🌙 Izin Bermalam (Overnight)
                            @elseif($leaveRequest->type === 'home_visit') 🏠 Pulang ke Rumah (Home Visit)
                            @elseif($leaveRequest->type === 'medical_leave') 🏥 Izin Sakit / Medis
                            @else 🚨 Izin Darurat
                            @endif
                        </span>
                    </div>

                    <div class="p-3.5 rounded-xl border border-slate-150 dark:border-slate-800 bg-white dark:bg-slate-900">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Tujuan / Tempat</span>
                        <span class="text-sm font-bold text-slate-800 dark:text-white">{{ $leaveRequest->destination }}</span>
                    </div>

                    <div class="p-3.5 rounded-xl border border-slate-150 dark:border-slate-800 bg-white dark:bg-slate-900">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Waktu Mulai Keluar</span>
                        <span class="text-sm font-bold text-slate-800 dark:text-white">
                            {{ \Carbon\Carbon::parse($leaveRequest->leave_start_at)->translatedFormat('d M Y, H:i') }} WIB
                        </span>
                    </div>

                    <div class="p-3.5 rounded-xl border border-slate-150 dark:border-slate-800 bg-white dark:bg-slate-900">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Batas Waktu Kembali</span>
                        <span class="text-sm font-bold text-slate-800 dark:text-white">
                            {{ $leaveRequest->leave_end_at ? \Carbon\Carbon::parse($leaveRequest->leave_end_at)->translatedFormat('d M Y, H:i') . ' WIB' : '-' }}
                        </span>
                    </div>
                </div>

                {{-- Reason & Notes --}}
                <div class="mt-4 p-3.5 rounded-xl border border-slate-150 dark:border-slate-800 bg-white dark:bg-slate-900 text-xs">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Alasan Keperluan Izin</span>
                    <p class="text-slate-700 dark:text-slate-300 font-medium whitespace-pre-wrap">{{ $leaveRequest->reason }}</p>
                </div>

                {{-- Approval Signatures Box --}}
                <div class="mt-5 pt-4 border-t border-slate-100 dark:border-slate-800 grid grid-cols-2 gap-4 text-center text-xs">
                    <div>
                        <span class="text-[10px] text-slate-400 block">Pemohon / Wali</span>
                        <p class="font-bold text-slate-800 dark:text-white mt-1">{{ $leaveRequest->requestedBy->name ?? 'Wali Santri' }}</p>
                    </div>
                    <div>
                        <span class="text-[10px] text-slate-400 block">Pemberi Izin / Pembina</span>
                        <p class="font-bold text-slate-800 dark:text-white mt-1">{{ $leaveRequest->approvedBy->name ?? 'Belum Diverifikasi' }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sidebar Actions & Security Checkpoint --}}
        <div class="space-y-6 print:hidden">
            {{-- Security Checkpoint / Return Box --}}
            @if($leaveRequest->status === 'approved')
                <div class="card-natural p-6 space-y-4 border-2 border-emerald-500/50 bg-emerald-50/20">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-emerald-600 text-white flex items-center justify-center text-sm font-black">
                            ✓
                        </div>
                        <div>
                            <h3 class="text-sm font-extrabold text-slate-900 dark:text-white">Pos Keamanan (Check-In)</h3>
                            <p class="text-2xs text-slate-500">Verifikasi kepulangan santri</p>
                        </div>
                    </div>

                    <p class="text-xs text-slate-600 dark:text-slate-300">
                        Santri saat ini tercatat <b>berada di luar asrama</b>. Klik tombol di bawah saat santri sudah tiba kembali di gerbang/asrama.
                    </p>

                    @if(auth()->user()->isSuperAdmin() || auth()->user()->isAdmin() || auth()->user()->isPrincipal() || auth()->user()->isBoardingSupervisor())
                        <form action="{{ route('boarding.leave-requests.mark-returned', $leaveRequest->id) }}" method="POST" onsubmit="return confirm('Konfirmasi kedatangan santri kembali di asrama?');">
                            @csrf
                            <button type="submit" class="w-full rounded-xl bg-emerald-600 hover:bg-emerald-700 py-2.5 text-xs font-bold text-white shadow-md shadow-emerald-600/20 active:scale-95 transition-all flex items-center justify-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                <span>Konfirmasi Santri Tiba di Asrama</span>
                            </button>
                        </form>
                    @endif
                </div>
            @endif

            {{-- Action Panel For Submitted Status --}}
            @if($leaveRequest->status === 'submitted')
                <div class="card-natural p-6 space-y-4">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Verifikasi Pengajuan Izin</h3>
                    
                    @if(auth()->user()->isSuperAdmin() || auth()->user()->isAdmin() || auth()->user()->isPrincipal() || auth()->user()->isBoardingSupervisor())
                        <form action="" method="POST" id="approval-form" class="space-y-3">
                            @csrf
                            <div>
                                <label for="approval_note" class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5">Catatan Persetujuan (Opsional)</label>
                                <textarea name="approval_note" id="approval_note" rows="2" placeholder="Catatan untuk pemohon / satpam..." class="w-full rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-xs p-2.5 text-slate-800 dark:text-slate-200 focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 focus:outline-none"></textarea>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-2 pt-1">
                                <button type="button" onclick="submitApproval('approve')" class="rounded-xl bg-emerald-600 hover:bg-emerald-700 py-2.5 text-xs font-bold text-white shadow-sm shadow-emerald-600/20 active:scale-95 transition">
                                    ✓ Setujui Izin
                                </button>
                                <button type="button" onclick="submitApproval('reject')" class="rounded-xl bg-rose-600 hover:bg-rose-700 py-2.5 text-xs font-bold text-white shadow-sm shadow-rose-600/20 active:scale-95 transition">
                                    ✕ Tolak Izin
                                </button>
                            </div>
                        </form>
                    @endif

                    <form action="{{ route('boarding.leave-requests.cancel', $leaveRequest->id) }}" method="POST" onsubmit="return confirm('Batalkan pengajuan izin ini?');" class="pt-2 border-t border-slate-100 dark:border-slate-800">
                        @csrf
                        <button type="submit" class="w-full rounded-xl border border-slate-200 bg-white hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 py-2 text-xs font-bold text-slate-600 dark:text-slate-400 transition">
                            Batalkan Pengajuan
                        </button>
                    </form>
                </div>
            @endif

            {{-- Audit Details Card --}}
            <div class="card-natural p-5 space-y-2.5 text-xs text-slate-500 dark:text-slate-400">
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Log Riwayat Izin</h4>
                <div class="flex justify-between">
                    <span>Diajukan:</span>
                    <b class="text-slate-800 dark:text-slate-200">{{ $leaveRequest->created_at->format('d/m/Y H:i') }}</b>
                </div>
                @if($leaveRequest->approvedBy)
                    <div class="flex justify-between">
                        <span>Diverifikasi:</span>
                        <b class="text-slate-800 dark:text-slate-200">{{ $leaveRequest->approvedBy->name }}</b>
                    </div>
                @endif
                @if($leaveRequest->returned_at)
                    <div class="flex justify-between">
                        <span>Kembali Aktual:</span>
                        <b class="text-emerald-600">{{ \Carbon\Carbon::parse($leaveRequest->returned_at)->format('d/m/Y H:i') }}</b>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    function submitApproval(type) {
        const form = document.getElementById('approval-form');
        let confirmMsg = 'Setujui surat izin keluar santri ini?';
        let actionUrl = "{{ route('boarding.leave-requests.approve', $leaveRequest->id) }}";
        
        if (type === 'reject') {
            confirmMsg = 'Tolak pengajuan izin ini?';
            actionUrl = "{{ route('boarding.leave-requests.reject', $leaveRequest->id) }}";
        }
        
        if (confirm(confirmMsg)) {
            form.action = actionUrl;
            form.submit();
        }
    }
</script>
@endsection
