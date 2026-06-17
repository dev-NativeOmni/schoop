@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-slate-900 dark:text-slate-100">Verification Queue</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Lakukan peninjauan hasil AI asisten sebelum dipublikasikan ke siswa/orang tua.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-450 border border-emerald-100 dark:border-emerald-950 text-sm font-semibold">
            {{ session('success') }}
        </div>
    @endif

    <div class="rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-150 dark:border-slate-800 text-xs font-bold text-slate-400 uppercase">
                        <th class="py-3 px-4">Nama Siswa</th>
                        <th class="py-3 px-4">Tipe Review</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4">Tanggal Diajukan</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-150 dark:divide-slate-800">
                    @forelse($queueItems as $item)
                        <tr class="text-sm text-slate-700 dark:text-slate-350">
                            <td class="py-4 px-4 font-bold text-slate-800 dark:text-slate-200">
                                {{ $item->student->user?->name ?? 'Ananda' }}
                            </td>
                            <td class="py-4 px-4 font-semibold text-slate-750 dark:text-slate-250">
                                <span class="inline-flex items-center rounded px-2 py-0.5 text-[10px] font-black uppercase bg-slate-100 text-slate-500">
                                    {{ str_replace('_', ' ', $item->review_type) }}
                                </span>
                            </td>
                            <td class="py-4 px-4">
                                <span class="inline-flex items-center rounded-lg px-2.5 py-0.5 text-xs font-bold 
                                    @if($item->status === 'published') bg-emerald-50 text-emerald-600
                                    @elseif($item->status === 'approved') bg-blue-50 text-blue-600
                                    @elseif($item->status === 'rejected') bg-rose-50 text-rose-600
                                    @else bg-amber-50 text-amber-600
                                    @endif">
                                    {{ strtoupper($item->status) }}
                                </span>
                            </td>
                            <td class="py-4 px-4 text-xs font-medium text-slate-500">
                                {{ $item->created_at->translatedFormat('d M Y, H:i') }}
                            </td>
                            <td class="py-4 px-4 text-right">
                                <a href="{{ route('ai-learning.review-queue.show', $item) }}" class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 dark:border-slate-800 px-3 py-1.5 font-bold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-950 transition text-xs">
                                    Tinjau Output
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-400">Antrean peninjauan asisten AI bersih (kosong).</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $queueItems->links() }}
        </div>
    </div>
</div>
@endsection
