@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-slate-200/80 pb-5 dark:border-slate-800">
        <div>
            <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-50 text-[11px] font-bold text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/40 mb-1">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                Manajemen Asrama
            </div>
            <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white">Perizinan Keluar Asrama (Gate Pass)</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400">Monitoring surat jalan santri, izin pulang ke rumah, dan verifikasi pos gerbang.</p>
        </div>
        <a href="{{ route('boarding.leave-requests.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 px-4 py-2.5 text-xs font-bold text-white shadow-md shadow-emerald-600/20 active:scale-95 transition-all">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
            <span>Ajukan Izin Santri</span>
        </a>
    </div>

    @if(session('success'))
        <div class="rounded-xl bg-emerald-50 p-4 text-xs font-bold text-emerald-800 border border-emerald-200 dark:bg-emerald-950/30 dark:text-emerald-300 dark:border-emerald-900/40 flex items-center gap-2">
            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Filter Form -->
    <div class="card-natural p-5">
        <form action="{{ route('boarding.leave-requests.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
            <!-- Search Text -->
            <div>
                <label for="q" class="block text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-1.5">Cari Santri</label>
                <input type="text" name="q" id="q" value="{{ request('q') }}" placeholder="Nama santri / NIS..." class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs text-slate-800 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 focus:outline-none">
            </div>

            <!-- Type Filter -->
            <div>
                <label for="type" class="block text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-1.5">Jenis Izin</label>
                <select name="type" id="type" class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs text-slate-800 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 focus:outline-none">
                    <option value="">Semua Jenis</option>
                    <option value="short_leave" {{ request('type') === 'short_leave' ? 'selected' : '' }}>⏱️ Keluar Sebentar</option>
                    <option value="overnight_leave" {{ request('type') === 'overnight_leave' ? 'selected' : '' }}>🌙 Bermalam</option>
                    <option value="home_visit" {{ request('type') === 'home_visit' ? 'selected' : '' }}>🏠 Pulang ke Rumah</option>
                    <option value="medical_leave" {{ request('type') === 'medical_leave' ? 'selected' : '' }}>🏥 Sakit / Medis</option>
                    <option value="emergency_leave" {{ request('type') === 'emergency_leave' ? 'selected' : '' }}>🚨 Darurat</option>
                </select>
            </div>

            <!-- Status Filter -->
            <div>
                <label for="status" class="block text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-1.5">Status Izin</label>
                <select name="status" id="status" class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs text-slate-800 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 focus:outline-none">
                    <option value="">Semua Status</option>
                    <option value="submitted" {{ request('status') === 'submitted' ? 'selected' : '' }}>⏳ Menunggu Persetujuan</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>🟢 Disetujui (Di Luar)</option>
                    <option value="returned" {{ request('status') === 'returned' ? 'selected' : '' }}>🏁 Sudah Kembali</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>❌ Ditolak</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>⚪ Dibatalkan</option>
                </select>
            </div>

            <!-- Buttons -->
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 rounded-xl bg-slate-900 hover:bg-slate-800 dark:bg-white dark:hover:bg-slate-100 dark:text-slate-900 px-4 py-2 text-xs font-bold text-white transition shadow-sm active:scale-95">Filter</button>
                @if(request()->anyFilled(['q', 'type', 'status']))
                    <a href="{{ route('boarding.leave-requests.index') }}" class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-bold text-slate-600 hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-slate-800 transition">Reset</a>
                @endif
            </div>
        </form>
    </div>

    <!-- Table List -->
    <div class="card-natural p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:border-slate-800">
                        <th class="py-3 px-4">Nama Santri</th>
                        <th class="py-3 px-4">Jenis Izin</th>
                        <th class="py-3 px-4">Waktu Keluar</th>
                        <th class="py-3 px-4">Rencana Kembali</th>
                        <th class="py-3 px-4">Kembali Aktual</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs text-slate-700 dark:divide-slate-800 dark:text-slate-300">
                    @forelse($leaveRequests as $req)
                        <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-950/20 transition-colors">
                            <td class="py-3.5 px-4 font-bold text-slate-900 dark:text-white">
                                <a href="{{ route('boarding.leave-requests.show', $req->id) }}" class="hover:text-emerald-600 transition flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-lg bg-emerald-100 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300 font-extrabold text-[10px] flex items-center justify-center">
                                        {{ strtoupper(substr($req->student->full_name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <span>{{ $req->student->full_name }}</span>
                                        <span class="block text-[10px] font-medium text-slate-400">NIS: {{ $req->student->student_number ?? ($req->student->nis ?? '-') }}</span>
                                    </div>
                                </a>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[11px] font-bold
                                    @if($req->type === 'short_leave') bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300
                                    @elseif($req->type === 'overnight_leave') bg-indigo-50 text-indigo-700 dark:bg-indigo-950/30 dark:text-indigo-300
                                    @elseif($req->type === 'home_visit') bg-emerald-50 text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-300
                                    @elseif($req->type === 'medical_leave') bg-amber-50 text-amber-700 dark:bg-amber-950/30 dark:text-amber-300
                                    @else bg-rose-50 text-rose-700 dark:bg-rose-950/30 dark:text-rose-300 @endif">
                                    @if($req->type === 'short_leave') ⏱️ Keluar Sebentar
                                    @elseif($req->type === 'overnight_leave') 🌙 Bermalam
                                    @elseif($req->type === 'home_visit') 🏠 Pulang Rumah
                                    @elseif($req->type === 'medical_leave') 🏥 Medis / Sakit
                                    @else 🚨 Darurat
                                    @endif
                                </span>
                            </td>
                            <td class="py-3.5 px-4 font-semibold text-slate-700 dark:text-slate-300">
                                {{ \Carbon\Carbon::parse($req->leave_start_at)->translatedFormat('d M Y, H:i') }}
                            </td>
                            <td class="py-3.5 px-4 text-slate-500 dark:text-slate-400">
                                {{ $req->leave_end_at ? \Carbon\Carbon::parse($req->leave_end_at)->translatedFormat('d M Y, H:i') : '-' }}
                            </td>
                            <td class="py-3.5 px-4 font-bold">
                                @if($req->returned_at)
                                    <span class="text-emerald-600 dark:text-emerald-400">{{ \Carbon\Carbon::parse($req->returned_at)->translatedFormat('d M Y, H:i') }}</span>
                                @else
                                    <span class="text-slate-400 font-normal">-</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[11px] font-extrabold
                                    @if($req->status === 'submitted') bg-amber-50 text-amber-700 dark:bg-amber-950/30 dark:text-amber-300 border border-amber-200/60
                                    @elseif($req->status === 'approved') bg-emerald-50 text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-300 border border-emerald-200/60
                                    @elseif($req->status === 'returned') bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300
                                    @elseif($req->status === 'rejected') bg-rose-50 text-rose-700 dark:bg-rose-950/30 dark:text-rose-300 border border-rose-200/60
                                    @else bg-slate-100 text-slate-500 @endif">
                                    @if($req->status === 'submitted') ⏳ Diajukan
                                    @elseif($req->status === 'approved') 🟢 Izin Aktif (Di Luar)
                                    @elseif($req->status === 'returned') 🏁 Kembali
                                    @elseif($req->status === 'rejected') ❌ Ditolak
                                    @else ⚪ Dibatalkan
                                    @endif
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <a href="{{ route('boarding.leave-requests.show', $req->id) }}" class="inline-flex items-center gap-1 rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-xs font-bold text-slate-700 hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-slate-800 transition shadow-2xs">
                                    <span>Detail</span>
                                    &rarr;
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400">
                                <p class="text-sm font-medium">Belum ada pengajuan izin santri yang ditemukan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $leaveRequests->links() }}
        </div>
    </div>
</div>
@endsection
