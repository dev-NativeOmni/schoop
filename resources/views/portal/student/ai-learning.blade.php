@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-slate-900 dark:text-slate-100">Asisten Belajar Mandiri</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Rencana latihan harian terpadu hasil evaluasi guru untuk menyempurnakan bacaanmu.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-450 border border-emerald-100 dark:border-emerald-950 text-sm font-semibold">
            {{ session('success') }}
        </div>
    @endif

    <!-- Encouragement Box -->
    <div class="rounded-3xl border border-indigo-200 dark:border-indigo-950 bg-indigo-50/50 dark:bg-indigo-950/20 p-5 shadow-sm">
        <p class="text-sm font-bold text-indigo-800 dark:text-indigo-300">
            {{ $encouragement }}
        </p>
    </div>

    <!-- Today's exercises -->
    <div class="rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm space-y-6">
        <h2 class="text-xl font-bold text-slate-850 dark:text-slate-150">Latihan Hari Ini</h2>
        <div class="space-y-4">
            @forelse($today_items as $item)
                <div class="p-4 rounded-2xl border border-slate-100 dark:border-slate-850 bg-slate-50/20 dark:bg-slate-950/10 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center rounded px-2 py-0.5 text-[9px] font-black uppercase bg-slate-150 dark:bg-slate-800 text-slate-550">
                                {{ str_replace('_', ' ', $item['item_type']) }}
                            </span>
                            <span class="text-sm font-bold text-slate-800 dark:text-slate-200">{{ $item['title'] }}</span>
                        </div>
                        <p class="text-xs text-slate-650 dark:text-slate-400 font-medium">{{ $item['description'] }}</p>
                        <div class="text-[10px] text-slate-400">Estimasi pengerjaan: {{ $item['estimated_minutes'] }} menit</div>
                    </div>

                    <div class="flex items-center gap-2">
                        @if($item['completion_status'] !== 'done')
                            <form action="{{ route('portal.student.ai-learning.update-status', $item['id']) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="done">
                                <button type="submit" class="inline-flex items-center rounded-xl bg-emerald-600 px-3 py-2 text-xs font-bold text-white shadow hover:bg-emerald-700 transition">
                                    &check; Tandai Selesai
                                </button>
                            </form>
                            <form action="{{ route('portal.student.ai-learning.update-status', $item['id']) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="skipped">
                                <button type="submit" class="inline-flex items-center rounded-xl border border-slate-200 dark:border-slate-800 px-3 py-2 text-xs font-bold text-slate-600 dark:text-slate-400 hover:bg-slate-50 transition">
                                    Lewati
                                </button>
                            </form>
                        @else
                            <span class="inline-flex items-center rounded px-2.5 py-1 text-xs font-bold bg-emerald-50 text-emerald-600">
                                &check; Selesai
                            </span>
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-sm text-slate-450 italic text-center py-6">Hari ini tidak ada latihan terjadwal. Selamat beristirahat dan murajaah mandiri!</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
