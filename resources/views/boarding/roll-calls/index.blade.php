@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-slate-200/80 pb-5 dark:border-slate-800">
        <div>
            <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-50 text-[11px] font-bold text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/40 mb-1">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                Manajemen Asrama
            </div>
            <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white">Roll Call (Absensi Kamar Malam)</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400">Pengecekan rutin keberadaan dan kehadiran santri di kamar asrama.</p>
        </div>
        <a href="{{ route('boarding.roll-calls.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 px-4 py-2.5 text-xs font-bold text-white shadow-md shadow-emerald-600/20 active:scale-95 transition-all">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
            <span>Mulai Sesi Absen Baru</span>
        </a>
    </div>

    @if(session('success'))
        <div class="rounded-xl bg-emerald-50 p-4 text-xs font-bold text-emerald-800 border border-emerald-200 dark:bg-emerald-950/30 dark:text-emerald-300 dark:border-emerald-900/40 flex items-center gap-2">
            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Sessions Table List -->
    <div class="card-natural p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:border-slate-800">
                        <th class="py-3 px-4">Tanggal Absen</th>
                        <th class="py-3 px-4">Waktu Sesi</th>
                        <th class="py-3 px-4">Gedung / Kamar</th>
                        <th class="py-3 px-4">Total Santri</th>
                        <th class="py-3 px-4">Status Sesi</th>
                        <th class="py-3 px-4">Musyriif / Pencatat</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs text-slate-700 dark:divide-slate-800 dark:text-slate-300">
                    @forelse($sessions as $session)
                        <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-950/20 transition-colors">
                            <td class="py-3.5 px-4 font-bold text-slate-900 dark:text-white">
                                {{ \Carbon\Carbon::parse($session->session_date)->translatedFormat('d F Y') }}
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[11px] font-bold
                                    @if($session->session_type === 'morning') bg-amber-50 text-amber-700 dark:bg-amber-950/30 dark:text-amber-300
                                    @elseif($session->session_type === 'afternoon') bg-orange-50 text-orange-700 dark:bg-orange-950/30 dark:text-orange-300
                                    @elseif($session->session_type === 'night') bg-indigo-50 text-indigo-700 dark:bg-indigo-950/40 dark:text-indigo-300
                                    @else bg-blue-50 text-blue-700 dark:bg-blue-950/30 dark:text-blue-300 @endif">
                                    @if($session->session_type === 'morning') 🌅 Sesi Pagi
                                    @elseif($session->session_type === 'afternoon') ☀️ Sesi Sore
                                    @elseif($session->session_type === 'night') 🌙 Sesi Malam
                                    @else ⏱️ Sesi Khusus
                                    @endif
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="block font-bold text-slate-800 dark:text-white">{{ $session->dormitory->name ?? 'Semua Asrama' }}</span>
                                @if($session->room)
                                    <span class="block text-[11px] text-slate-400 mt-0.5">Kamar: {{ $session->room->name }}</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 font-extrabold text-slate-800 dark:text-white">
                                {{ $session->records_count }} Santri
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-[11px] font-extrabold
                                    @if($session->status === 'open') bg-emerald-50 text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-300 border border-emerald-200/60
                                    @else bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400 @endif">
                                    <span class="w-1.5 h-1.5 rounded-full @if($session->status === 'open') bg-emerald-500 @else bg-slate-400 @endif"></span>
                                    {{ $session->status === 'open' ? 'Sesi Terbuka' : 'Terkunci' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-slate-500 dark:text-slate-400 font-medium">
                                {{ $session->creator->name ?? '-' }}
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <a href="{{ route('boarding.roll-calls.show', $session->id) }}" class="inline-flex items-center gap-1 rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-xs font-bold text-slate-700 hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-slate-800 transition shadow-2xs">
                                    <span>{{ $session->status === 'open' ? '⚡ Buka Absen' : 'Lihat Rekap' }}</span>
                                    &rarr;
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400">
                                <p class="text-sm font-medium">Belum ada sesi absensi yang dibuat.</p>
                            </td>
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
