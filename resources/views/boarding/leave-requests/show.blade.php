@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between border-b border-slate-200 pb-4 dark:border-slate-800">
        <div>
            <div class="flex items-center gap-2">
                <a href="{{ route('boarding.leave-requests.index') }}" class="text-sm font-semibold text-emerald-500 hover:underline">Daftar Perizinan</a>
                <span class="text-slate-400">/</span>
                <span class="text-sm text-slate-500 dark:text-slate-400">Detail Pengajuan Izin</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white mt-1">Izin: {{ $leaveRequest->student->full_name }}</h1>
            <p class="text-sm text-slate-500">NIS: {{ $leaveRequest->student->student_number ?? '-' }} | Kelas: {{ $leaveRequest->student->classRoom->name ?? '-' }}</p>
        </div>
        <a href="{{ route('boarding.leave-requests.index') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-350 dark:hover:bg-slate-950">
            Kembali
        </a>
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

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Main Info -->
        <div class="md:col-span-2 space-y-6">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 space-y-4">
                <h2 class="text-lg font-bold text-slate-800 dark:text-white border-b border-slate-100 pb-2 dark:border-slate-800">Detail Pengajuan</h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Jenis Izin</span>
                        <div class="mt-1">
                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium uppercase
                                @if($leaveRequest->type === 'short_leave') bg-slate-50 text-slate-700 dark:bg-slate-950/30 dark:text-slate-455
                                @elseif($leaveRequest->type === 'overnight_leave') bg-blue-50 text-blue-700 dark:bg-blue-950/30 dark:text-blue-455
                                @elseif($leaveRequest->type === 'home_visit') bg-indigo-50 text-indigo-700 dark:bg-indigo-950/30 dark:text-indigo-455
                                @elseif($leaveRequest->type === 'medical_leave') bg-amber-50 text-amber-700 dark:bg-amber-950/30 dark:text-amber-455
                                @else bg-rose-50 text-rose-700 dark:bg-rose-950/30 dark:text-rose-455 @endif">
                                @if($leaveRequest->type === 'short_leave') Keluar Sebentar
                                @elseif($leaveRequest->type === 'overnight_leave') Bermalam
                                @elseif($leaveRequest->type === 'home_visit') Pulang Rumah
                                @elseif($leaveRequest->type === 'medical_leave') Sakit / Medis
                                @else Darurat
                                @endif
                            </span>
                        </div>
                    </div>
                    <div>
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Status Izin</span>
                        <div class="mt-1">
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold
                                @if($leaveRequest->status === 'submitted') bg-amber-50 text-amber-700 dark:bg-amber-950/30 dark:text-amber-455
                                @elseif($leaveRequest->status === 'approved') bg-blue-50 text-blue-700 dark:bg-blue-950/30 dark:text-blue-455
                                @elseif($leaveRequest->status === 'returned') bg-green-50 text-green-700 dark:bg-green-950/30 dark:text-green-455
                                @elseif($leaveRequest->status === 'rejected') bg-rose-50 text-rose-700 dark:bg-rose-950/30 dark:text-rose-455
                                @else bg-slate-50 text-slate-700 dark:bg-slate-950/30 dark:text-slate-455 @endif">
                                @if($leaveRequest->status === 'submitted') Menunggu Persetujuan
                                @elseif($leaveRequest->status === 'approved') Disetujui (Di Luar)
                                @elseif($leaveRequest->status === 'returned') Sudah Kembali
                                @elseif($leaveRequest->status === 'rejected') Ditolak
                                @else Dibatalkan
                                @endif
                            </span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Tujuan Kepergian</span>
                        <span class="text-sm font-semibold text-slate-800 dark:text-white block mt-1">{{ $leaveRequest->destination }}</span>
                    </div>
                    <div>
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Diajukan Oleh</span>
                        <span class="text-sm font-semibold text-slate-800 dark:text-white block mt-1">{{ $leaveRequest->requestedBy->name ?? '-' }}</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 border-t border-slate-100 pt-3 dark:border-slate-800">
                    <div>
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Mulai Keluar</span>
                        <span class="text-sm font-semibold text-slate-800 dark:text-white block mt-1">{{ \Carbon\Carbon::parse($leaveRequest->leave_start_at)->format('d M Y H:i') }}</span>
                    </div>
                    <div>
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Rencana Kembali</span>
                        <span class="text-sm font-semibold text-slate-800 dark:text-white block mt-1">
                            {{ $leaveRequest->leave_end_at ? \Carbon\Carbon::parse($leaveRequest->leave_end_at)->format('d M Y H:i') : '-' }}
                        </span>
                    </div>
                </div>

                @if($leaveRequest->returned_at)
                    <div class="border-t border-slate-100 pt-3 dark:border-slate-800">
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Waktu Kembali Aktual</span>
                        <span class="text-sm font-semibold text-emerald-600 block mt-1">{{ \Carbon\Carbon::parse($leaveRequest->returned_at)->format('d M Y H:i') }}</span>
                    </div>
                @endif

                <div class="border-t border-slate-100 pt-3 dark:border-slate-800">
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Alasan Izin</span>
                    <p class="text-sm text-slate-655 dark:text-slate-350 mt-1 whitespace-pre-wrap">{{ $leaveRequest->reason }}</p>
                </div>

                @if($leaveRequest->approved_by_user_id)
                    <div class="border-t border-slate-100 pt-3 dark:border-slate-800 grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Diverifikasi Oleh</span>
                            <span class="text-sm font-semibold text-slate-800 dark:text-white block mt-1">{{ $leaveRequest->approvedBy->name ?? '-' }}</span>
                        </div>
                        <div>
                            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Catatan Verifikasi</span>
                            <span class="text-sm font-medium text-slate-700 dark:text-slate-300 block mt-1">{{ $leaveRequest->approval_note ?? '-' }}</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar / Actions -->
        <div class="space-y-6">
            <!-- Action Panel -->
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 space-y-4">
                <h3 class="text-md font-bold text-slate-800 dark:text-white">Panel Tindakan</h3>
                
                @if($leaveRequest->status === 'submitted')
                    <!-- Approve / Reject Form for admins/principals/supervisors -->
                    @if(auth()->user()->isSuperAdmin() || auth()->user()->isAdmin() || auth()->user()->isPrincipal() || auth()->user()->isBoardingSupervisor())
                        <form action="" method="POST" id="approval-form" class="space-y-3">
                            @csrf
                            <div>
                                <label for="approval_note" class="block text-xs font-semibold text-slate-755 dark:text-slate-400 mb-1">Catatan Verifikasi (Opsional)</label>
                                <textarea name="approval_note" id="approval_note" rows="2" placeholder="Masukkan catatan persetujuan/penolakan..." class="w-full rounded-xl border-slate-200 text-xs focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-800 dark:bg-slate-950 dark:text-white"></textarea>
                            </div>
                            
                            <div class="flex gap-2">
                                <button type="submit" onclick="submitApproval(event, 'approve')" class="flex-1 rounded-xl bg-emerald-550 py-2 text-xs font-semibold text-white shadow-md hover:bg-emerald-600 transition">Setujui</button>
                                <button type="submit" onclick="submitApproval(event, 'reject')" class="flex-1 rounded-xl bg-rose-550 py-2 text-xs font-semibold text-white shadow-md hover:bg-rose-600 transition">Tolak</button>
                            </div>
                        </form>
                    @endif

                    <!-- Cancel Request Form -->
                    <form action="{{ route('boarding.leave-requests.cancel', $leaveRequest->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pengajuan izin ini?');">
                        @csrf
                        <button type="submit" class="w-full rounded-xl border border-slate-200 bg-white py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-350 dark:hover:bg-slate-950">Batalkan Pengajuan</button>
                    </form>
                @endif

                @if($leaveRequest->status === 'approved')
                    <!-- Mark as Returned Form -->
                    @if(auth()->user()->isSuperAdmin() || auth()->user()->isAdmin() || auth()->user()->isPrincipal() || auth()->user()->isBoardingSupervisor())
                        <div class="space-y-2">
                            <p class="text-xs text-slate-500">Santri saat ini masih berada di luar asrama. Tandai sudah kembali jika santri sudah tiba kembali di lingkungan asrama.</p>
                            <form action="{{ route('boarding.leave-requests.mark-returned', $leaveRequest->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menandai santri sudah kembali ke asrama?');">
                                @csrf
                                <button type="submit" class="w-full rounded-xl bg-gradient-to-r from-emerald-500 to-teal-600 py-2 text-xs font-semibold text-white shadow-md hover:opacity-90 transition">Santri Sudah Kembali</button>
                            </form>
                        </div>
                    @endif
                @endif

                @if(in_array($leaveRequest->status, ['returned', 'rejected', 'cancelled']))
                    <p class="text-xs text-slate-500 text-center py-2">Tidak ada tindakan lanjutan untuk izin dengan status ini.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    function submitApproval(event, type) {
        event.preventDefault();
        const form = document.getElementById('approval-form');
        const noteInput = document.getElementById('approval_note').value;
        
        let confirmMsg = 'Apakah Anda yakin ingin menyetujui pengajuan izin ini?';
        let actionUrl = "{{ route('boarding.leave-requests.approve', $leaveRequest->id) }}";
        
        if (type === 'reject') {
            confirmMsg = 'Apakah Anda yakin ingin menolak pengajuan izin ini?';
            actionUrl = "{{ route('boarding.leave-requests.reject', $leaveRequest->id) }}";
        }
        
        if (confirm(confirmMsg)) {
            form.action = actionUrl;
            form.submit();
        }
    }
</script>
@endsection
