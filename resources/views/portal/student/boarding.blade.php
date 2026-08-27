@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-slate-200/80 pb-5 dark:border-slate-800">
        <div>
            <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-50 text-[11px] font-bold text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/40 mb-1">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                Portal Santri
            </div>
            <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white">Asrama & Kehidupan Santri</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400">Pantau detail penempatan kamar, perizinan keluar, catatan kesehatan UKS, dan kehadiran absen malam Anda.</p>
        </div>
    </div>

    <!-- Student Info & Placement details -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Left Panel: Profile & Placement Info -->
        <div class="md:col-span-1 space-y-6">
            <div class="card-natural p-6 space-y-5">
                <div class="text-center pb-5 border-b border-slate-100 dark:border-slate-800">
                    <div class="w-16 h-16 bg-emerald-100 dark:bg-emerald-950/50 rounded-2xl flex items-center justify-center mx-auto text-emerald-700 dark:text-emerald-300 font-black text-2xl shadow-2xs">
                        {{ strtoupper(substr($student->full_name, 0, 2)) }}
                    </div>
                    <h2 class="text-base font-extrabold text-slate-900 dark:text-white mt-3">{{ $student->full_name }}</h2>
                    <span class="text-xs text-slate-400">NIS: {{ $student->student_number ?? ($student->nis ?? '-') }}</span>
                </div>

                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Status Kamar Anda</h3>
                @if(!$assignment)
                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-950/30 text-center text-xs text-slate-500 border border-slate-150 dark:border-slate-800">
                        Anda belum ditempatkan di kamar asrama yang aktif saat ini. Silakan hubungi Musyriif / Pembina Asrama.
                    </div>
                @else
                    <div class="space-y-2.5 text-xs">
                        <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-950/40 border border-slate-150 dark:border-slate-800 flex justify-between items-center">
                            <span class="text-slate-500">Gedung Asrama:</span>
                            <span class="font-extrabold text-slate-900 dark:text-white">{{ $assignment->dormitory->name }}</span>
                        </div>
                        <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-950/40 border border-slate-150 dark:border-slate-800 flex justify-between items-center">
                            <span class="text-slate-500">Nomor Kamar:</span>
                            <span class="font-extrabold text-emerald-600 dark:text-emerald-400">{{ $assignment->room->name }}</span>
                        </div>
                        @if($assignment->bed)
                            <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-950/40 border border-slate-150 dark:border-slate-800 flex justify-between items-center">
                                <span class="text-slate-500">Nomor Ranjang:</span>
                                <span class="font-extrabold text-slate-900 dark:text-white">{{ $assignment->bed->code }}</span>
                            </div>
                        @endif
                        <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-950/40 border border-slate-150 dark:border-slate-800 flex justify-between items-center">
                            <span class="text-slate-500">Tanggal Masuk:</span>
                            <span class="font-bold text-slate-700 dark:text-slate-300">{{ \Carbon\Carbon::parse($assignment->start_date)->translatedFormat('d M Y') }}</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Right Panel: Tabs for Activities -->
        <div class="md:col-span-2 space-y-4">
            <!-- Navigation Tabs -->
            <div class="flex flex-wrap gap-1.5 p-1 rounded-2xl bg-slate-100 dark:bg-slate-950/60 border border-slate-200/70 dark:border-slate-800">
                <button onclick="switchTab('leaves')" id="tab-btn-leaves" class="px-3.5 py-1.5 text-xs font-bold rounded-xl transition-all bg-white dark:bg-slate-900 text-emerald-600 dark:text-emerald-400 shadow-xs">🎫 Perizinan Saya</button>
                <button onclick="switchTab('health')" id="tab-btn-health" class="px-3.5 py-1.5 text-xs font-bold rounded-xl transition-all text-slate-600 dark:text-slate-400 hover:text-slate-900">🏥 Kesehatan UKS</button>
                <button onclick="switchTab('attendance')" id="tab-btn-attendance" class="px-3.5 py-1.5 text-xs font-bold rounded-xl transition-all text-slate-600 dark:text-slate-400 hover:text-slate-900">🌙 Absen Kamar Malam</button>
                <button onclick="switchTab('discipline')" id="tab-btn-discipline" class="px-3.5 py-1.5 text-xs font-bold rounded-xl transition-all text-slate-600 dark:text-slate-400 hover:text-slate-900">⭐ Sikap & Poin</button>
            </div>

            <!-- Tab: Leave Requests -->
            <div id="tab-leaves" class="space-y-4 tab-content">
                <div class="card-natural p-6">
                    <div class="flex justify-between items-center mb-4 pb-3 border-b border-slate-100 dark:border-slate-800">
                        <h3 class="text-sm font-extrabold text-slate-900 dark:text-white">Riwayat Perizinan Keluar Saya</h3>
                    </div>
                    
                    <div class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($leaveRequests as $req)
                            <div class="py-3.5 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
                                <div>
                                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-bold uppercase 
                                        @if($req->type === 'short_leave') bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300
                                        @elseif($req->type === 'overnight_leave') bg-indigo-50 text-indigo-700 dark:bg-indigo-950/30 dark:text-indigo-300
                                        @elseif($req->type === 'home_visit') bg-emerald-50 text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-300
                                        @else bg-amber-50 text-amber-700 dark:bg-amber-950/30 dark:text-amber-300 @endif">
                                        @if($req->type === 'short_leave') ⏱️ Keluar Sebentar
                                        @elseif($req->type === 'overnight_leave') 🌙 Bermalam
                                        @elseif($req->type === 'home_visit') 🏠 Pulang Rumah
                                        @elseif($req->type === 'medical_leave') 🏥 Sakit / Medis
                                        @else 🚨 Darurat
                                        @endif
                                    </span>
                                    <p class="font-bold text-slate-900 dark:text-white mt-1">Tujuan: {{ $req->destination }}</p>
                                    <p class="text-slate-500 mt-0.5">{{ $req->reason }}</p>
                                    <p class="text-[11px] text-slate-400 mt-1">Mulai: {{ \Carbon\Carbon::parse($req->leave_start_at)->translatedFormat('d M Y H:i') }} &bull; Rencana Kembali: {{ $req->leave_end_at ? \Carbon\Carbon::parse($req->leave_end_at)->translatedFormat('d M Y H:i') : '-' }}</p>
                                    @if($req->returned_at)
                                        <p class="text-emerald-600 font-semibold mt-1">✓ Tiba di Asrama: {{ \Carbon\Carbon::parse($req->returned_at)->translatedFormat('d M Y H:i') }}</p>
                                    @endif
                                </div>
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[11px] font-extrabold shrink-0
                                    @if($req->status === 'submitted') bg-amber-50 text-amber-700 dark:bg-amber-950/30 dark:text-amber-300 border border-amber-200/60
                                    @elseif($req->status === 'approved') bg-emerald-50 text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-300 border border-emerald-200/60
                                    @elseif($req->status === 'returned') bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300
                                    @elseif($req->status === 'rejected') bg-rose-50 text-rose-700 dark:bg-rose-950/30 dark:text-rose-300 border border-rose-200/60
                                    @else bg-slate-100 text-slate-500 @endif">
                                    @if($req->status === 'submitted') ⏳ Menunggu Persetujuan
                                    @elseif($req->status === 'approved') 🟢 Izin Aktif (Di Luar)
                                    @elseif($req->status === 'returned') 🏁 Sudah Kembali
                                    @elseif($req->status === 'rejected') ❌ Ditolak
                                    @else ⚪ Dibatalkan
                                    @endif
                                </span>
                            </div>
                        @empty
                            <p class="text-center py-8 text-slate-400">Tidak ada riwayat pengajuan izin.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Tab: Health Logs -->
            <div id="tab-health" class="space-y-4 tab-content hidden">
                <div class="card-natural p-6">
                    <h3 class="text-sm font-extrabold text-slate-900 dark:text-white mb-4 pb-3 border-b border-slate-100 dark:border-slate-800">Catatan Medis Kesehatan Saya</h3>

                    <div class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($healthLogs as $log)
                            <div class="py-3 flex justify-between items-start text-xs gap-3">
                                <div>
                                    <p class="font-bold text-slate-900 dark:text-white">{{ $log->condition_title }}</p>
                                    @if($log->description)
                                        <p class="text-slate-500 mt-0.5">{{ $log->description }}</p>
                                    @endif
                                    @if($log->action_taken)
                                        <p class="text-emerald-600 font-semibold mt-1">Penanganan UKS: {{ $log->action_taken }}</p>
                                    @endif
                                    <p class="text-[11px] text-slate-400 mt-1">Dicatat pada: {{ \Carbon\Carbon::parse($log->logged_at)->translatedFormat('d M Y, H:i') }}</p>
                                </div>
                                <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-bold uppercase shrink-0
                                    @if($log->severity === 'critical') bg-rose-50 text-rose-700 dark:bg-rose-950/30 dark:text-rose-300
                                    @elseif($log->severity === 'high') bg-orange-50 text-orange-700 dark:bg-orange-950/30 dark:text-orange-300
                                    @elseif($log->severity === 'medium') bg-amber-50 text-amber-700 dark:bg-amber-950/30 dark:text-amber-300
                                    @else bg-emerald-50 text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-300 @endif">
                                    {{ $log->severity }}
                                </span>
                            </div>
                        @empty
                            <p class="text-center py-8 text-slate-400">Tidak ada catatan pemeriksaan medis.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Tab: Attendance -->
            <div id="tab-attendance" class="space-y-4 tab-content hidden">
                <div class="card-natural p-6">
                    <h3 class="text-sm font-extrabold text-slate-900 dark:text-white mb-4 pb-3 border-b border-slate-100 dark:border-slate-800">Riwayat Roll Call Absensi Saya</h3>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead>
                                <tr class="border-b border-slate-100 text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:border-slate-800">
                                    <th class="py-2.5 px-3">Tanggal</th>
                                    <th class="py-2.5 px-3">Waktu Sesi</th>
                                    <th class="py-2.5 px-3">Status Kehadiran</th>
                                    <th class="py-2.5 px-3">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300">
                                @forelse($rollCallRecords as $rec)
                                    <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-950/20">
                                        <td class="py-2.5 px-3 font-semibold text-slate-800 dark:text-white">
                                            {{ \Carbon\Carbon::parse($rec->session->session_date)->translatedFormat('d M Y') }}
                                        </td>
                                        <td class="py-2.5 px-3 capitalize text-slate-500">
                                            {{ $rec->session->session_type ?? 'Malam' }}
                                        </td>
                                        <td class="py-2.5 px-3">
                                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[11px] font-extrabold
                                                @if($rec->status === 'present') bg-emerald-50 text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-300
                                                @elseif($rec->status === 'late') bg-amber-50 text-amber-700 dark:bg-amber-950/30 dark:text-amber-300
                                                @elseif($rec->status === 'permission') bg-blue-50 text-blue-700 dark:bg-blue-950/30 dark:text-blue-300
                                                @elseif($rec->status === 'sick') bg-purple-50 text-purple-700 dark:bg-purple-950/30 dark:text-purple-300
                                                @else bg-rose-50 text-rose-700 dark:bg-rose-950/30 dark:text-rose-300 @endif">
                                                @if($rec->status === 'present') Hadir di Kamar
                                                @elseif($rec->status === 'late') Terlambat
                                                @elseif($rec->status === 'permission') Izin Resmi
                                                @elseif($rec->status === 'sick') Sakit (UKS)
                                                @else Alpha / Tidak Ada
                                                @endif
                                            </span>
                                        </td>
                                        <td class="py-2.5 px-3 text-slate-500">{{ $rec->note ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-8 text-center text-slate-400">Tidak ada rekam absensi.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Tab: Discipline Logs -->
            <div id="tab-discipline" class="space-y-4 tab-content hidden">
                <div class="card-natural p-6">
                    <h3 class="text-sm font-extrabold text-slate-900 dark:text-white mb-4 pb-3 border-b border-slate-100 dark:border-slate-800">Catatan Sikap & Poin Karakter</h3>

                    <div class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($disciplineLogs as $log)
                            <div class="py-3.5 flex justify-between items-center text-xs gap-3">
                                <div>
                                    <span class="inline-flex items-center rounded px-1.5 py-0.5 text-[10px] font-bold uppercase bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-400">
                                        {{ $log->category }}
                                    </span>
                                    <p class="font-bold text-slate-900 dark:text-white mt-1">{{ $log->description }}</p>
                                    @if($log->action_taken)
                                        <p class="text-slate-500 mt-0.5">Tindak Lanjut: {{ $log->action_taken }}</p>
                                    @endif
                                    <p class="text-[10px] text-slate-400 mt-1">{{ \Carbon\Carbon::parse($log->logged_at)->translatedFormat('d M Y, H:i') }}</p>
                                </div>
                                <div class="text-right shrink-0">
                                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-bold uppercase
                                        @if($log->type === 'violation') bg-rose-50 text-rose-700 dark:bg-rose-950/30 dark:text-rose-300
                                        @elseif($log->type === 'warning') bg-amber-50 text-amber-700 dark:bg-amber-950/30 dark:text-amber-300
                                        @elseif($log->type === 'achievement') bg-emerald-50 text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-300
                                        @else bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300 @endif">
                                        @if($log->type === 'violation') Pelanggaran
                                        @elseif($log->type === 'warning') Peringatan
                                        @elseif($log->type === 'achievement') Prestasi
                                        @else Catatan
                                        @endif
                                    </span>
                                    <span class="block mt-1 font-black text-sm @if($log->points > 0) text-emerald-600 @elseif($log->points < 0) text-rose-600 @else text-slate-500 @endif">
                                        {{ $log->points > 0 ? '+' : '' }}{{ $log->points }} Poin
                                    </span>
                                </div>
                            </div>
                        @empty
                            <p class="text-center py-8 text-slate-400">Tidak ada catatan sikap.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function switchTab(tabName) {
        document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
        
        document.querySelectorAll('[id^="tab-btn-"]').forEach(btn => {
            btn.classList.remove('bg-white', 'dark:bg-slate-900', 'text-emerald-600', 'dark:text-emerald-400', 'shadow-xs');
            btn.classList.add('text-slate-600', 'dark:text-slate-400');
        });

        document.getElementById(`tab-${tabName}`).classList.remove('hidden');

        const activeBtn = document.getElementById(`tab-btn-${tabName}`);
        activeBtn.classList.add('bg-white', 'dark:bg-slate-900', 'text-emerald-600', 'dark:text-emerald-400', 'shadow-xs');
        activeBtn.classList.remove('text-slate-600', 'dark:text-slate-400');
    }
</script>
@endsection
