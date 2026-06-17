@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div>
        <h1 class="text-3xl font-black tracking-tight text-slate-900 dark:text-slate-100">Asisten Rekomendasi AI</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Laporan berkala hasil olah data setoran hafalan, tahsin, dan ibadah harian ananda.</p>
    </div>

    @forelse($children as $child)
        @php
            $digest = $digests->get($child->id);
            $profile = $digest['profile'] ?? null;
        @endphp

        <div class="rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm space-y-6">
            <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-850 pb-4">
                <div>
                    <h2 class="text-xl font-bold text-slate-850 dark:text-slate-150">Laporan Ananda: {{ $child->user?->name }}</h2>
                    <p class="text-xs text-slate-450 mt-1">NIS: {{ $child->nis ?? '-' }}</p>
                </div>
            </div>

            @if($profile)
                <!-- Summary -->
                <div class="space-y-2">
                    <h3 class="text-xs font-black uppercase tracking-wider text-slate-400">Ringkasan Evaluasi Pekan Ini</h3>
                    <p class="text-sm font-medium text-slate-700 dark:text-slate-350 leading-relaxed leading-normal bg-slate-50 dark:bg-slate-950/40 p-4 rounded-2xl border border-slate-100 dark:border-slate-850">
                        {{ $profile['summary'] }}
                    </p>
                </div>

                <!-- Strengths / Focus -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="p-4 rounded-2xl border border-emerald-100 dark:border-emerald-950/20 bg-emerald-50/10 space-y-2">
                        <h4 class="text-xs font-bold text-emerald-700 dark:text-emerald-400 flex items-center gap-1.5">&check; Kekuatan Belajar</h4>
                        <ul class="text-xs text-slate-650 list-disc pl-4 space-y-1">
                            @foreach($profile['strengths'] as $st)
                                <li>{{ $st }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="p-4 rounded-2xl border border-amber-100 dark:border-amber-950/20 bg-amber-50/10 space-y-2">
                        <h4 class="text-xs font-bold text-amber-700 dark:text-amber-400 flex items-center gap-1.5">&excl; Fokus Latihan</h4>
                        <ul class="text-xs text-slate-650 list-disc pl-4 space-y-1">
                            @foreach($profile['focus_areas'] as $fa)
                                <li>{{ $fa }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @else
                <p class="text-xs text-slate-450 italic py-4">Belum ada profil ringkasan terbaru untuk ananda saat ini.</p>
            @endif

            <!-- Recommendations list -->
            @if(!empty($digest['recommendations']) && count($digest['recommendations']) > 0)
                <div class="space-y-3 pt-4 border-t border-slate-100 dark:border-slate-850">
                    <h3 class="text-xs font-black uppercase tracking-wider text-slate-400">Rekomendasi Latihan Khusus</h3>
                    <div class="space-y-3">
                        @foreach($digest['recommendations'] as $rec)
                            <div class="p-4 rounded-2xl border border-slate-100 dark:border-slate-850 bg-slate-50/20 dark:bg-slate-950/10 space-y-2">
                                <h4 class="text-sm font-bold text-slate-800 dark:text-slate-200">{{ $rec['title'] }}</h4>
                                <p class="text-xs text-slate-600 dark:text-slate-400">{{ $rec['description'] }}</p>
                                <div class="pt-2">
                                    <p class="text-[10px] font-bold text-slate-450 uppercase mb-1">Panduan Tindakan Mandiri:</p>
                                    <ul class="text-xs text-slate-600 dark:text-slate-450 list-disc pl-4 space-y-1">
                                        @foreach($rec['recommended_actions'] as $act)
                                            <li>{{ $act }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    @empty
        <p class="text-slate-500 text-center py-12">Tidak ada santri yang terhubung dengan akun Anda.</p>
    @endforelse
</div>
@endsection
