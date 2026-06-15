@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-6">
    <x-analytics.shell :title="'Health Score Breakdown - ' . $school->name" subtitle="Detail pembagian skor kesehatan komponen operasional, akademi, support, dan SaaS." :schoolId="$school->id">
        @if(! $score)
            <div class="rounded-xl border border-amber-200 bg-amber-50 p-6 text-sm font-bold text-amber-800 dark:border-amber-900/60 dark:bg-amber-950/40 dark:text-amber-200">
                Skor kesehatan untuk tenant ini belum dihitung. Jalankan capture/kalkulasi skor kesehatan terlebih dahulu.
            </div>
        @else
            <div class="grid gap-6 lg:grid-cols-3">
                <div class="lg:col-span-1 space-y-6">
                    <!-- Score Overview -->
                    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 text-center">
                        <h3 class="text-xs font-black uppercase text-slate-500">Skor Kesehatan</h3>
                        <p class="mt-4 text-6xl font-black text-slate-950 dark:text-white">{{ $score->score }}</p>
                        <p class="mt-1 text-sm text-slate-500">dari maks 100 poin</p>
                        <div class="mt-4">
                            <span class="rounded px-3 py-1 text-xs font-bold uppercase tracking-wide
                                @if($score->status === 'healthy') bg-lime-100 text-lime-800 dark:bg-lime-950/40 dark:text-lime-400
                                @elseif($score->status === 'watch') bg-amber-100 text-amber-800 dark:bg-amber-950/40 dark:text-amber-400
                                @else bg-red-100 text-red-800 dark:bg-red-950/40 dark:text-red-400
                                @endif">
                                {{ $score->status }}
                            </span>
                        </div>
                        <p class="mt-4 text-xs text-slate-400">Dihitung pada: {{ $score->score_date->toDateString() }}</p>
                    </div>

                    <!-- Risk Flags & Recommendations -->
                    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <h4 class="text-sm font-black text-slate-950 dark:text-white">Rekomendasi CS & Tindakan</h4>
                        <ul class="mt-3 list-disc pl-5 text-sm text-slate-700 dark:text-slate-300 space-y-2">
                            @foreach($score->recommendations ?: ['Belum ada tindakan khusus yang direkomendasikan.'] as $rec)
                                <li>{{ $rec }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <!-- Component Breakdown -->
                <div class="lg:col-span-2 rounded-xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 space-y-6">
                    <h3 class="text-lg font-black text-slate-950 dark:text-white">Breakdown Komponen</h3>
                    <div class="space-y-4">
                        @foreach($score->components as $comp)
                            <div>
                                <div class="flex items-center justify-between text-sm">
                                    <span class="font-bold text-slate-900 dark:text-white">{{ $comp->name }}</span>
                                    <span class="font-mono font-bold">{{ $comp->score }}/{{ $comp->max_score }}</span>
                                </div>
                                <div class="mt-1 h-2 w-full rounded-full bg-slate-100 dark:bg-slate-800">
                                    <div class="h-2 rounded-full bg-slate-950 dark:bg-lime-400" style="width: {{ ($comp->score / $comp->max_score) * 100 }}%"></div>
                                </div>
                                <p class="mt-1 text-xs text-slate-500">{{ $comp->explanation }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </x-analytics.shell>
</div>
@endsection
