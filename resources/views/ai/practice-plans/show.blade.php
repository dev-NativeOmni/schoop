@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div>
        <a href="{{ route('ai-learning.practice-plans.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 transition">
            &larr; Kembali ke Daftar Rencana Latihan
        </a>
        <h1 class="text-3xl font-black tracking-tight text-slate-900 dark:text-slate-100 mt-2">Detail Practice Plan</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Rencana latihan harian terstruktur yang dipublikasikan untuk siswa.</p>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-450 border border-emerald-100 dark:border-emerald-950 text-sm font-semibold">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main items list -->
        <div class="lg:col-span-2 space-y-6">
            <div class="rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm space-y-6">
                <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-850 pb-4">
                    <div>
                        <h2 class="text-xl font-bold text-slate-850 dark:text-slate-150">{{ $practicePlan->title }}</h2>
                        <p class="text-xs text-slate-450 mt-1">
                            Siswa: <strong class="text-slate-650 dark:text-slate-350">{{ $practicePlan->student->user?->name }}</strong>
                        </p>
                    </div>
                    <span class="inline-flex items-center rounded-lg px-2.5 py-0.5 text-xs font-bold 
                        @if($practicePlan->status === 'published') bg-emerald-50 dark:bg-emerald-950/20 text-emerald-600
                        @elseif($practicePlan->status === 'completed') bg-blue-50 dark:bg-blue-950/20 text-blue-600
                        @else bg-slate-100 dark:bg-slate-850 text-slate-500
                        @endif">
                        {{ strtoupper($practicePlan->status) }}
                    </span>
                </div>

                <!-- Daily checklist schedule -->
                <div class="space-y-6">
                    @php
                        $groupedItems = $practicePlan->items->groupBy(function($item) {
                            return $item->practice_date->toDateString();
                        });
                    @endphp

                    @foreach($groupedItems as $dateStr => $items)
                        <div class="space-y-3">
                            <h3 class="text-xs font-bold text-indigo-650 dark:text-indigo-400 uppercase tracking-wider border-b border-slate-100 dark:border-slate-850 pb-1">
                                {{ \Carbon\Carbon::parse($dateStr)->translatedFormat('l, d M Y') }}
                            </h3>
                            <div class="space-y-2">
                                @foreach($items as $item)
                                    <div class="p-4 rounded-2xl border border-slate-100 dark:border-slate-850 bg-slate-50/20 dark:bg-slate-950/10 flex items-start justify-between gap-4">
                                        <div class="space-y-1">
                                            <div class="flex items-center gap-2">
                                                <span class="inline-flex items-center rounded px-2 py-0.5 text-[9px] font-black uppercase bg-slate-150 dark:bg-slate-800 text-slate-550">
                                                    {{ str_replace('_', ' ', $item->item_type) }}
                                                </span>
                                                <span class="text-sm font-bold text-slate-850 dark:text-slate-200">{{ $item->title }}</span>
                                            </div>
                                            <p class="text-xs text-slate-600 dark:text-slate-400 font-medium">{{ $item->description }}</p>
                                            <div class="text-[10px] text-slate-400">Estimasi waktu: {{ $item->estimated_minutes }} menit</div>
                                        </div>
                                        <span class="inline-flex items-center rounded px-2.5 py-0.5 text-[10px] font-bold uppercase
                                            @if($item->completion_status === 'done') bg-emerald-50 text-emerald-600
                                            @elseif($item->completion_status === 'skipped') bg-rose-50 text-rose-600
                                            @else bg-slate-100 text-slate-500
                                            @endif">
                                            {{ $item->completion_status }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Disclaimer -->
                <div class="p-3 bg-slate-50 dark:bg-slate-950 rounded-2xl border border-slate-100 dark:border-slate-850">
                    <p class="text-[10px] text-slate-450 italic leading-normal">
                        Rekomendasi ini dibuat oleh sistem untuk membantu proses belajar dan telah/harus direview oleh guru. Keputusan pembelajaran tetap mengikuti arahan guru.
                    </p>
                </div>
            </div>
        </div>

        <!-- Sidebar Actions -->
        <div class="space-y-6">
            <!-- Summary card -->
            <div class="rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm space-y-4">
                <h3 class="font-bold text-slate-900 dark:text-slate-100 border-b border-slate-100 dark:border-slate-850 pb-3">Informasi Plan</h3>
                <div class="space-y-2">
                    <div class="text-xs font-semibold text-slate-550">
                        <span class="block text-slate-400 font-bold uppercase">Tanggal Mulai:</span>
                        {{ $practicePlan->start_date?->translatedFormat('d M Y') }}
                    </div>
                    <div class="text-xs font-semibold text-slate-550">
                        <span class="block text-slate-400 font-bold uppercase">Tanggal Berakhir:</span>
                        {{ $practicePlan->end_date?->translatedFormat('d M Y') }}
                    </div>
                </div>
            </div>

            <!-- Publish actions -->
            @if($practicePlan->status === 'draft')
                <div class="rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm space-y-3">
                    <h3 class="font-bold text-slate-900 dark:text-slate-100 pb-2 border-b border-slate-100 dark:border-slate-850">Aksi Guru</h3>
                    <form action="{{ route('ai-learning.practice-plans.publish', $practicePlan) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full inline-flex items-center justify-center rounded-2xl bg-emerald-600 px-4 py-2.5 font-bold text-white shadow hover:bg-emerald-700 transition text-xs">
                            Publikasikan Plan
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
