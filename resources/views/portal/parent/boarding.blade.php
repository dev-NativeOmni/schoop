@extends('layouts.app')

@section('content')
<div class="space-y-6 animate-fade-in">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-slate-200 pb-4 dark:border-slate-800">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Portal Asrama (Boarding)</h1>
            <p class="text-sm text-slate-500">Pantau penempatan kamar, perizinan, kesehatan, dan absensi malam anak Anda.</p>
        </div>
    </div>

    <!-- Child Selector if parent has multiple children -->
    @if($students->count() > 1)
        <div class="flex items-center gap-3 bg-slate-50 p-4 rounded-2xl border border-slate-200/60 dark:bg-slate-950/20 dark:border-slate-800">
            <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">Pilih Anak:</span>
            <div class="flex flex-wrap gap-2">
                @foreach($students as $st)
                    <a href="?student_id={{ $st->id }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-all 
                        @if($st->id == $student?->id) bg-emerald-500 text-white shadow-md shadow-emerald-200 dark:shadow-none
                        @else bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 dark:bg-slate-900 dark:border-slate-800 dark:text-slate-300 dark:hover:bg-slate-950 @endif">
                        {{ $st->full_name }}
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    @if(!$student)
        <div class="rounded-2xl border border-slate-200 bg-white p-8 text-center text-slate-500 dark:border-slate-800 dark:bg-slate-900">
            Belum ada profil santri yang dikaitkan dengan akun orang tua Anda.
        </div>
    @else
        <!-- Child Info & Placement details -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Left Panel: Profile & Placement Info -->
            <div class="md:col-span-1 space-y-6">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 space-y-4">
                    <div class="text-center pb-4 border-b border-slate-100 dark:border-slate-800">
                        <div class="w-16 h-16 bg-emerald-100 dark:bg-emerald-950/50 rounded-full flex items-center justify-center mx-auto text-emerald-600 font-extrabold text-2xl">
                            {{ substr($student->full_name, 0, 1) }}
                        </div>
                        <h2 class="text-lg font-bold text-slate-800 dark:text-white mt-3">{{ $student->full_name }}</h2>
                        <span class="text-xs text-slate-400">NIS: {{ $student->student_number ?? '-' }}</span>
                    </div>

                    <h3 class="text-xs font-bold uppercase text-slate-400 tracking-wider">Status Penempatan</h3>
                    @if(!$assignment)
                        <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-950/20 text-center text-xs text-slate-500">
                            Anak Anda belum memiliki penempatan kamar asrama yang aktif saat ini.
                        </div>
                    @else
                        <div class="space-y-3 text-sm font-medium text-slate-650 dark:text-slate-455">
                            <div class="flex justify-between">
                                <span>Asrama:</span>
                                <span class="font-bold text-slate-800 dark:text-white">{{ $assignment->dormitory->name }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Kamar:</span>
                                <span class="font-bold text-slate-800 dark:text-white">{{ $assignment->room->name }}</span>
                            </div>
                            @if($assignment->bed)
                                <div class="flex justify-between">
                                    <span>Ranjang:</span>
                                    <span class="font-bold text-slate-800 dark:text-white">{{ $assignment->bed->code }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between">
                                <span>Tanggal Masuk:</span>
                                <span class="font-bold text-slate-800 dark:text-white">{{ \Carbon\Carbon::parse($assignment->start_date)->format('d M Y') }}</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right Panel: Tabs for Activities -->
            <div class="md:col-span-2 space-y-6">
                <!-- Navigation Tabs -->
                <div class="flex border-b border-slate-200 dark:border-slate-800">
                    <button onclick="switchTab('leaves')" id="tab-btn-leaves" class="px-4 py-2 text-sm font-bold border-b-2 border-emerald-500 text-emerald-600 transition">Perizinan</button>
                    <button onclick="switchTab('health')" id="tab-btn-health" class="px-4 py-2 text-sm font-semibold border-b-2 border-transparent text-slate-500 hover:text-slate-700 transition">Kesehatan</button>
                    <button onclick="switchTab('attendance')" id="tab-btn-attendance" class="px-4 py-2 text-sm font-semibold border-b-2 border-transparent text-slate-500 hover:text-slate-700 transition">Absensi</button>
                    <button onclick="switchTab('discipline')" id="tab-btn-discipline" class="px-4 py-2 text-sm font-semibold border-b-2 border-transparent text-slate-500 hover:text-slate-700 transition">Sikap & Poin</button>
                </div>

                <!-- Tab: Leave Requests -->
                <div id="tab-leaves" class="space-y-4 tab-content">
                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <div class="flex justify-between items-center mb-4 pb-2 border-b border-slate-100 dark:border-slate-800">
                            <h3 class="font-bold text-slate-800 dark:text-white">Riwayat Perizinan Keluar Asrama</h3>
                        </div>
                        
                        <div class="divide-y divide-slate-100 dark:divide-slate-800">
                            @forelse($leaveRequests as $req)
                                <div class="py-3 flex justify-between items-start text-xs">
                                    <div>
                                        <span class="inline-flex items-center rounded px-1.5 py-0.5 text-3xs font-semibold uppercase bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-400">
                                            @if($req->type === 'short_leave') Keluar Sebentar
                                            @elseif($req->type === 'overnight_leave') Bermalam
                                            @elseif($req->type === 'home_visit') Pulang Rumah
                                            @elseif($req->type === 'medical_leave') Sakit / Medis
                                            @else Darurat
                                            @endif
                                        </span>
                                        <p class="font-bold text-slate-800 dark:text-slate-200 mt-1">Ke: {{ $req->destination }}</p>
                                        <p class="text-slate-500 mt-0.5">{{ $req->reason }}</p>
                                        <p class="text-slate-400 mt-1">Mulai: {{ \Carbon\Carbon::parse($req->leave_start_at)->format('d M Y H:i') }} | Rencana Kembali: {{ $req->leave_end_at ? \Carbon\Carbon::parse($req->leave_end_at)->format('d M Y H:i') : '-' }}</p>
                                        @if($req->returned_at)
                                            <p class="text-emerald-500 font-medium mt-1">Kembali Aktual: {{ \Carbon\Carbon::parse($req->returned_at)->format('d M Y H:i') }}</p>
                                        @endif
                                    </div>
                                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-2xs font-semibold
                                        @if($req->status === 'submitted') bg-amber-50 text-amber-700 dark:bg-amber-950/30 dark:text-amber-455
                                        @elseif($req->status === 'approved') bg-blue-50 text-blue-700 dark:bg-blue-950/30 dark:text-blue-455
                                        @elseif($req->status === 'returned') bg-green-50 text-green-700 dark:bg-green-950/30 dark:text-green-455
                                        @elseif($req->status === 'rejected') bg-rose-50 text-rose-700 dark:bg-rose-950/30 dark:text-rose-455
                                        @else bg-slate-50 text-slate-700 dark:bg-slate-950/30 dark:text-slate-455 @endif">
                                        @if($req->status === 'submitted') Diajukan
                                        @elseif($req->status === 'approved') Disetujui
                                        @elseif($req->status === 'returned') Kembali
                                        @elseif($req->status === 'rejected') Ditolak
                                        @else Dibatalkan
                                        @endif
                                    </span>
                                </div>
                            @empty
                                <p class="text-center py-6 text-slate-400">Tidak ada pengajuan izin.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Tab: Health Logs -->
                <div id="tab-health" class="space-y-4 tab-content hidden">
                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <h3 class="font-bold text-slate-800 dark:text-white mb-4 pb-2 border-b border-slate-100 dark:border-slate-800">Catatan Kondisi Kesehatan</h3>

                        <div class="divide-y divide-slate-100 dark:divide-slate-800">
                            @forelse($healthLogs as $log)
                                <div class="py-3 flex justify-between items-start text-xs">
                                    <div>
                                        <p class="font-bold text-slate-850 dark:text-slate-200">{{ $log->condition_title }}</p>
                                        @if($log->description)
                                            <p class="text-slate-500 mt-0.5">{{ $log->description }}</p>
                                        @endif
                                        @if($log->action_taken)
                                            <p class="text-emerald-650 dark:text-emerald-400 font-semibold mt-1">Penanganan: {{ $log->action_taken }}</p>
                                        @endif
                                        <p class="text-slate-400 mt-1">Dicatat pada: {{ \Carbon\Carbon::parse($log->logged_at)->format('d M Y H:i') }}</p>
                                    </div>
                                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-2xs font-semibold uppercase
                                        @if($log->severity === 'critical') bg-rose-50 text-rose-700 dark:bg-rose-950/30 dark:text-rose-455
                                        @elseif($log->severity === 'high') bg-orange-50 text-orange-700 dark:bg-orange-950/30 dark:text-orange-455
                                        @elseif($log->severity === 'medium') bg-amber-50 text-amber-700 dark:bg-amber-950/30 dark:text-amber-455
                                        @else bg-emerald-50 text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-455 @endif">
                                        {{ $log->severity }}
                                    </span>
                                </div>
                            @empty
                                <p class="text-center py-6 text-slate-400">Tidak ada catatan medis / kesehatan.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Tab: Attendance (Roll Call Records) -->
                <div id="tab-attendance" class="space-y-4 tab-content hidden">
                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <h3 class="font-bold text-slate-800 dark:text-white mb-4 pb-2 border-b border-slate-100 dark:border-slate-800">Pengecekan Malam (Roll Call)</h3>

                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse text-xs">
                                <thead>
                                    <tr class="border-b border-slate-150 text-slate-400 font-bold uppercase dark:border-slate-800">
                                        <th class="py-2">Tanggal</th>
                                        <th class="py-2">Sesi</th>
                                        <th class="py-2">Status</th>
                                        <th class="py-2">Catatan</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50 dark:divide-slate-800 text-slate-700 dark:text-slate-350">
                                    @forelse($rollCallRecords as $rec)
                                        <tr>
                                            <td class="py-2.5 font-semibold">{{ \Carbon\Carbon::parse($rec->session->session_date)->format('d M Y') }}</td>
                                            <td class="py-2.5 capitalize">
                                                @if($rec->session->session_type === 'night') Malam
                                                @elseif($rec->session->session_type === 'morning') Pagi
                                                @elseif($rec->session->session_type === 'afternoon') Sore
                                                @else Kustom
                                                @endif
                                            </td>
                                            <td class="py-2.5">
                                                <span class="inline-flex items-center rounded-full px-2 py-0.5 text-2xs font-semibold uppercase
                                                    @if($rec->status === 'present') bg-green-50 text-green-700 dark:bg-green-950/30 dark:text-green-455
                                                    @elseif($rec->status === 'late') bg-amber-50 text-amber-700 dark:bg-amber-950/30 dark:text-amber-455
                                                    @elseif($rec->status === 'permission') bg-blue-50 text-blue-700 dark:bg-blue-950/30 dark:text-blue-455
                                                    @elseif($rec->status === 'sick') bg-pink-50 text-pink-700 dark:bg-pink-950/30 dark:text-pink-455
                                                    @else bg-rose-50 text-rose-700 dark:bg-rose-950/30 dark:text-rose-455 @endif">
                                                    @if($rec->status === 'present') Hadir
                                                    @elseif($rec->status === 'late') Terlambat
                                                    @elseif($rec->status === 'permission') Izin
                                                    @elseif($rec->status === 'sick') Sakit
                                                    @else Alpha
                                                    @endif
                                                </span>
                                            </td>
                                            <td class="py-2.5 font-medium text-slate-500">{{ $rec->note ?? '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="py-6 text-center text-slate-400">Tidak ada rekam absensi asrama.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Tab: Discipline Logs -->
                <div id="tab-discipline" class="space-y-4 tab-content hidden">
                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <h3 class="font-bold text-slate-800 dark:text-white mb-4 pb-2 border-b border-slate-100 dark:border-slate-800">Log Sikap & Poin</h3>

                        <div class="divide-y divide-slate-100 dark:divide-slate-800">
                            @forelse($disciplineLogs as $log)
                                <div class="py-3 flex justify-between items-center text-xs">
                                    <div>
                                        <span class="inline-flex items-center rounded px-1.5 py-0.5 text-3xs font-semibold uppercase bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-400">
                                            {{ $log->category }}
                                        </span>
                                        <p class="font-bold text-slate-850 dark:text-slate-200 mt-1">{{ $log->description }}</p>
                                        @if($log->action_taken)
                                            <p class="text-slate-500 mt-0.5">Tindak Lanjut: {{ $log->action_taken }}</p>
                                        @endif
                                        <p class="text-slate-400 mt-1">{{ \Carbon\Carbon::parse($log->logged_at)->format('d M Y H:i') }}</p>
                                    </div>
                                    <div class="text-right">
                                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-2xs font-semibold uppercase
                                            @if($log->type === 'violation') bg-rose-50 text-rose-700 dark:bg-rose-950/30 dark:text-rose-455
                                            @elseif($log->type === 'warning') bg-amber-50 text-amber-700 dark:bg-amber-950/30 dark:text-amber-455
                                            @elseif($log->type === 'achievement') bg-emerald-50 text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-455
                                            @else bg-slate-50 text-slate-700 dark:bg-slate-950/30 dark:text-slate-455 @endif">
                                            @if($log->type === 'violation') Pelanggaran
                                            @elseif($log->type === 'warning') Peringatan
                                            @elseif($log->type === 'achievement') Prestasi
                                            @else Catatan
                                            @endif
                                        </span>
                                        <span class="block mt-1 font-bold @if($log->points > 0) text-emerald-500 @elseif($log->points < 0) text-rose-500 @else text-slate-500 @endif">
                                            {{ $log->points > 0 ? '+' : '' }}{{ $log->points }}
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <p class="text-center py-6 text-slate-400">Tidak ada catatan kedisiplinan / prestasi.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<script>
    function switchTab(tabName) {
        // Hide all contents
        document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
        
        // Remove border from all buttons
        document.querySelectorAll('[id^="tab-btn-"]').forEach(btn => {
            btn.classList.remove('border-emerald-500', 'text-emerald-600');
            btn.classList.add('border-transparent', 'text-slate-500');
        });

        // Show active tab content
        document.getElementById(`tab-${tabName}`).classList.remove('hidden');

        // Highlight active button
        const activeBtn = document.getElementById(`tab-btn-${tabName}`);
        activeBtn.classList.add('border-emerald-500', 'text-emerald-600');
        activeBtn.classList.remove('border-transparent', 'text-slate-500');
    }
</script>
@endsection
