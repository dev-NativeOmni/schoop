@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between border-b border-slate-200 pb-4 dark:border-slate-800">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Perizinan Boarding (Izin Keluar)</h1>
            <p class="text-sm text-slate-500">Kelola perizinan keluar lingkungan pesantren, pulang ke rumah, atau izin darurat.</p>
        </div>
        <a href="{{ route('boarding.leave-requests.create') }}" class="rounded-xl bg-gradient-to-r from-emerald-500 to-teal-600 px-4 py-2 text-sm font-semibold text-white shadow-md shadow-emerald-200 hover:opacity-90 transition dark:shadow-none">
            + Ajukan Izin Santri
        </a>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
        <div class="rounded-xl bg-emerald-50 p-4 text-sm text-emerald-800 dark:bg-emerald-950/30 dark:text-emerald-400">
            {{ session('success') }}
        </div>
    @endif

    <!-- Filter Form -->
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <form action="{{ route('boarding.leave-requests.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
            <!-- Search Text -->
            <div>
                <label for="q" class="block text-xs font-bold uppercase text-slate-400 dark:text-slate-500 mb-1">Cari Santri</label>
                <input type="text" name="q" id="q" value="{{ request('q') }}" placeholder="Nama / NIS..." class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-800 dark:bg-slate-950 dark:text-white">
            </div>

            <!-- Type Filter -->
            <div>
                <label for="type" class="block text-xs font-bold uppercase text-slate-400 dark:text-slate-500 mb-1">Jenis Izin</label>
                <select name="type" id="type" class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-800 dark:bg-slate-950 dark:text-white">
                    <option value="">Semua Jenis</option>
                    <option value="short_leave" {{ request('type') === 'short_leave' ? 'selected' : '' }}>Izin Keluar Sebentar (Short Leave)</option>
                    <option value="overnight_leave" {{ request('type') === 'overnight_leave' ? 'selected' : '' }}>Izin Bermalam (Overnight)</option>
                    <option value="home_visit" {{ request('type') === 'home_visit' ? 'selected' : '' }}>Pulang ke Rumah (Home Visit)</option>
                    <option value="medical_leave" {{ request('type') === 'medical_leave' ? 'selected' : '' }}>Izin Sakit / Medis</option>
                    <option value="emergency_leave" {{ request('type') === 'emergency_leave' ? 'selected' : '' }}>Izin Darurat (Emergency)</option>
                </select>
            </div>

            <!-- Status Filter -->
            <div>
                <label for="status" class="block text-xs font-bold uppercase text-slate-400 dark:text-slate-500 mb-1">Status Izin</label>
                <select name="status" id="status" class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-800 dark:bg-slate-950 dark:text-white">
                    <option value="">Semua Status</option>
                    <option value="submitted" {{ request('status') === 'submitted' ? 'selected' : '' }}>Menunggu Persetujuan</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Disetujui / Di Luar</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    <option value="returned" {{ request('status') === 'returned' ? 'selected' : '' }}>Sudah Kembali</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>

            <!-- Buttons -->
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 rounded-xl bg-slate-800 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700 transition dark:bg-slate-750 dark:hover:bg-slate-700">Filter</button>
                @if(request()->anyFilled(['q', 'type', 'status']))
                    <a href="{{ route('boarding.leave-requests.index') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-350 dark:hover:bg-slate-950">Reset</a>
                @endif
            </div>
        </form>
    </div>

    <!-- Table List -->
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 text-xs font-bold uppercase text-slate-400 dark:border-slate-800">
                        <th class="py-3 px-4">Nama Santri</th>
                        <th class="py-3 px-4">Jenis Izin</th>
                        <th class="py-3 px-4">Waktu Keluar</th>
                        <th class="py-3 px-4">Kembali Terencana</th>
                        <th class="py-3 px-4">Waktu Kembali Aktual</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 text-sm text-slate-700 dark:divide-slate-800 dark:text-slate-350">
                    @forelse($leaveRequests as $req)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-950/20">
                            <td class="py-3.5 px-4 font-semibold text-slate-800 dark:text-white">
                                <a href="{{ route('boarding.leave-requests.show', $req->id) }}" class="hover:text-emerald-500 transition">
                                    {{ $req->student->full_name }}
                                </a>
                                @if($req->reason)
                                    <span class="block text-xs font-normal text-slate-400 mt-0.5">{{ Str::limit($req->reason, 50) }}</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium uppercase
                                    @if($req->type === 'short_leave') bg-slate-50 text-slate-700 dark:bg-slate-950/30 dark:text-slate-455
                                    @elseif($req->type === 'overnight_leave') bg-blue-50 text-blue-700 dark:bg-blue-950/30 dark:text-blue-455
                                    @elseif($req->type === 'home_visit') bg-indigo-50 text-indigo-700 dark:bg-indigo-950/30 dark:text-indigo-455
                                    @elseif($req->type === 'medical_leave') bg-amber-50 text-amber-700 dark:bg-amber-950/30 dark:text-amber-455
                                    @else bg-rose-50 text-rose-700 dark:bg-rose-950/30 dark:text-rose-455 @endif">
                                    @if($req->type === 'short_leave') Keluar Sebentar
                                    @elseif($req->type === 'overnight_leave') Bermalam
                                    @elseif($req->type === 'home_visit') Pulang Rumah
                                    @elseif($req->type === 'medical_leave') Sakit / Medis
                                    @else Darurat
                                    @endif
                                </span>
                            </td>
                            <td class="py-3.5 px-4 font-medium text-slate-600 dark:text-slate-350">
                                {{ \Carbon\Carbon::parse($req->leave_start_at)->format('d M Y H:i') }}
                            </td>
                            <td class="py-3.5 px-4 font-medium text-slate-500 dark:text-slate-450">
                                {{ $req->leave_end_at ? \Carbon\Carbon::parse($req->leave_end_at)->format('d M Y H:i') : '-' }}
                            </td>
                            <td class="py-3.5 px-4 font-medium text-slate-800 dark:text-white">
                                @if($req->returned_at)
                                    <span class="text-emerald-500">{{ \Carbon\Carbon::parse($req->returned_at)->format('d M Y H:i') }}</span>
                                @else
                                    <span class="text-slate-400 font-normal">-</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold
                                    @if($req->status === 'submitted') bg-amber-50 text-amber-700 dark:bg-amber-950/30 dark:text-amber-455
                                    @elseif($req->status === 'approved') bg-blue-50 text-blue-700 dark:bg-blue-950/30 dark:text-blue-455
                                    @elseif($req->status === 'returned') bg-green-50 text-green-700 dark:bg-green-950/30 dark:text-green-455
                                    @elseif($req->status === 'rejected') bg-rose-50 text-rose-700 dark:bg-rose-950/30 dark:text-rose-455
                                    @else bg-slate-55 text-slate-700 dark:bg-slate-950/30 dark:text-slate-455 @endif">
                                    @if($req->status === 'submitted') Diajukan
                                    @elseif($req->status === 'approved') Disetujui (Di Luar)
                                    @elseif($req->status === 'returned') Kembali
                                    @elseif($req->status === 'rejected') Ditolak
                                    @else Dibatalkan
                                    @endif
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <a href="{{ route('boarding.leave-requests.show', $req->id) }}" class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-bold hover:bg-slate-50 dark:border-slate-800 dark:hover:bg-slate-950">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400">Belum ada pengajuan izin santri yang ditemukan.</td>
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
