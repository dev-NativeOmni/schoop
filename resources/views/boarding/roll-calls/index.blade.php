@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between border-b border-slate-200 pb-4 dark:border-slate-800">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Roll Call (Absensi Asrama)</h1>
            <p class="text-sm text-slate-500">Mulai dan rekam absensi malam/pagi/sore keberadaan santri di asrama.</p>
        </div>
        <a href="{{ route('boarding.roll-calls.create') }}" class="rounded-xl bg-gradient-to-r from-emerald-500 to-teal-600 px-4 py-2 text-sm font-semibold text-white shadow-md shadow-emerald-200 hover:opacity-90 transition dark:shadow-none">
            + Mulai Sesi Absen Baru
        </a>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
        <div class="rounded-xl bg-emerald-50 p-4 text-sm text-emerald-800 dark:bg-emerald-950/30 dark:text-emerald-400">
            {{ session('success') }}
        </div>
    @endif

    <!-- Sessions Table List -->
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 text-xs font-bold uppercase text-slate-400 dark:border-slate-800">
                        <th class="py-3 px-4">Tanggal Absen</th>
                        <th class="py-3 px-4">Sesi</th>
                        <th class="py-3 px-4">Asrama / Kamar</th>
                        <th class="py-3 px-4">Jumlah Santri</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4">Dibuat Oleh</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 text-sm text-slate-700 dark:divide-slate-800 dark:text-slate-350">
                    @forelse($sessions as $session)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-950/20">
                            <td class="py-3.5 px-4 font-semibold text-slate-800 dark:text-white">
                                {{ \Carbon\Carbon::parse($session->session_date)->format('d M Y') }}
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold uppercase
                                    @if($session->session_type === 'morning') bg-amber-50 text-amber-700 dark:bg-amber-950/30 dark:text-amber-455
                                    @elseif($session->session_type === 'afternoon') bg-orange-50 text-orange-700 dark:bg-orange-950/30 dark:text-orange-455
                                    @elseif($session->session_type === 'night') bg-slate-900 text-slate-100 dark:bg-slate-950 dark:text-slate-400
                                    @else bg-blue-50 text-blue-700 dark:bg-blue-950/30 dark:text-blue-455 @endif">
                                    @if($session->session_type === 'morning') Pagi
                                    @elseif($session->session_type === 'afternoon') Sore
                                    @elseif($session->session_type === 'night') Malam
                                    @else Kustom
                                    @endif
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="block font-semibold text-slate-700 dark:text-slate-300">Asrama: {{ $session->dormitory->name ?? 'Semua Asrama' }}</span>
                                @if($session->room)
                                    <span class="block text-xs text-slate-450 mt-0.5">Kamar: {{ $session->room->name }}</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 font-medium">
                                {{ $session->records_count }} Santri
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold
                                    @if($session->status === 'open') bg-green-50 text-green-700 dark:bg-green-950/30 dark:text-green-455
                                    @else bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400 @endif">
                                    {{ $session->status === 'open' ? 'Terbuka' : 'Terkunci / Selesai' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 font-medium text-slate-650 dark:text-slate-400">
                                {{ $session->creator->name ?? '-' }}
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('boarding.roll-calls.show', $session->id) }}" class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-bold hover:bg-slate-50 dark:border-slate-800 dark:hover:bg-slate-950">
                                        {{ $session->status === 'open' ? 'Mulai Absen' : 'Lihat Rekap' }}
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400">Belum ada sesi absensi yang dibuat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $sessions->links() }}
        </div>
    </div>
</div>
@endsection
