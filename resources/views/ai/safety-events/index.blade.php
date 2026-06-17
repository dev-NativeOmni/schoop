@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-slate-900 dark:text-slate-100">Safety Alerts</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Daftar kejadian pemicu keamanan (safety triggers) yang dicatat oleh sistem pengawas.</p>
        </div>
    </div>

    <div class="rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-150 dark:border-slate-800 text-xs font-bold text-slate-400 uppercase">
                        <th class="py-3 px-4">Nama Siswa</th>
                        <th class="py-3 px-4">Tipe Kejadian</th>
                        <th class="py-3 px-4">Tingkat Bahaya</th>
                        <th class="py-3 px-4">Keterangan</th>
                        <th class="py-3 px-4">Tanggal Kejadian</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-150 dark:divide-slate-800">
                    @forelse($safetyEvents as $ev)
                        <tr class="text-sm text-slate-700 dark:text-slate-350">
                            <td class="py-4 px-4 font-bold text-slate-800 dark:text-slate-200">
                                {{ $ev->student->user?->name ?? 'System-wide' }}
                            </td>
                            <td class="py-4 px-4 font-semibold text-slate-750 dark:text-slate-250">
                                <span class="inline-flex items-center rounded px-2 py-0.5 text-[10px] font-black uppercase bg-rose-50 text-rose-600">
                                    {{ str_replace('_', ' ', $ev->event_type) }}
                                </span>
                            </td>
                            <td class="py-4 px-4">
                                <span class="inline-flex items-center rounded-lg px-2.5 py-0.5 text-xs font-bold 
                                    @if($ev->severity === 'urgent') bg-rose-50 text-rose-600
                                    @else bg-amber-50 text-amber-600
                                    @endif">
                                    {{ strtoupper($ev->severity) }}
                                </span>
                            </td>
                            <td class="py-4 px-4 font-medium text-slate-600 dark:text-slate-400">
                                {{ $ev->description }}
                            </td>
                            <td class="py-4 px-4 text-xs font-medium text-slate-500">
                                {{ $ev->created_at->translatedFormat('d M Y, H:i') }}
                            </td>
                            <td class="py-4 px-4 text-right">
                                <a href="{{ route('ai-learning.safety-events.show', $ev) }}" class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 dark:border-slate-800 px-3 py-1.5 font-bold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-950 transition text-xs">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400">Tidak ada peringatan keamanan safety yang tercatat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $safetyEvents->links() }}
        </div>
    </div>
</div>
@endsection
