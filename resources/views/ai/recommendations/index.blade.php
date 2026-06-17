@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-slate-900 dark:text-slate-100">Learning Recommendations</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Daftar tindakan belajar spesifik yang direkomendasikan untuk menunjang progres murid.</p>
        </div>
    </div>

    <div class="rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-150 dark:border-slate-800 text-xs font-bold text-slate-400 uppercase">
                        <th class="py-3 px-4">Nama Siswa</th>
                        <th class="py-3 px-4">Judul Rekomendasi</th>
                        <th class="py-3 px-4">Tipe</th>
                        <th class="py-3 px-4">Prioritas</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-150 dark:divide-slate-800">
                    @forelse($recommendations as $rec)
                        <tr class="text-sm text-slate-700 dark:text-slate-350">
                            <td class="py-4 px-4 font-bold text-slate-800 dark:text-slate-200">
                                {{ $rec->student->user?->name ?? 'Ananda' }}
                            </td>
                            <td class="py-4 px-4 font-semibold text-slate-750 dark:text-slate-250">
                                {{ $rec->title }}
                            </td>
                            <td class="py-4 px-4 text-xs font-bold uppercase text-slate-500">
                                {{ str_replace('_', ' ', $rec->recommendation_type) }}
                            </td>
                            <td class="py-4 px-4">
                                <span class="inline-flex items-center rounded px-2 py-0.5 text-[10px] font-black uppercase
                                    @if($rec->priority === 'urgent') bg-rose-50 text-rose-600
                                    @elseif($rec->priority === 'high') bg-amber-50 text-amber-600
                                    @else bg-slate-50 text-slate-500
                                    @endif">
                                    {{ $rec->priority }}
                                </span>
                            </td>
                            <td class="py-4 px-4">
                                <span class="inline-flex items-center rounded-lg px-2.5 py-0.5 text-xs font-bold 
                                    @if($rec->status === 'published') bg-emerald-50 dark:bg-emerald-950/20 text-emerald-600
                                    @else bg-slate-100 dark:bg-slate-850 text-slate-500
                                    @endif">
                                    {{ strtoupper($rec->status) }}
                                </span>
                            </td>
                            <td class="py-4 px-4 text-right">
                                <a href="{{ route('ai-learning.recommendations.show', $rec) }}" class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 dark:border-slate-800 px-3 py-1.5 font-bold text-slate-700 dark:text-slate-330 hover:bg-slate-50 dark:hover:bg-slate-950 transition text-xs">
                                    Lihat Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400">Belum ada rekomendasi belajar yang terdata.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $recommendations->links() }}
        </div>
    </div>
</div>
@endsection
