@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-6">
    <x-analytics.shell title="Kamus Metrik (Metric Dictionary)" subtitle="Referensi resmi rumus, unit, frekuensi update, serta tingkatan sensitivitas data untuk semua metrik analitik.">
        @if($canManage)
            <div class="flex justify-end mb-4">
                <a href="{{ route('analytics.metric-dictionary.create') }}" class="rounded-lg bg-slate-950 px-4 py-2 text-sm font-bold text-white transition hover:bg-slate-800 dark:bg-lime-400 dark:text-slate-950">Tambah Metrik Baru</a>
            </div>
        @endif

        <div class="space-y-8">
            @foreach($categories as $category => $metrics)
                <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 space-y-4">
                    <h2 class="text-lg font-black text-slate-950 dark:text-white uppercase tracking-wide">{{ $category }} Metrics</h2>
                    <div class="divide-y divide-slate-100 dark:divide-slate-800">
                        @foreach($metrics as $m)
                            <div class="py-4 first:pt-0 last:pb-0 space-y-2">
                                <div class="flex flex-wrap items-center justify-between gap-2">
                                    <div class="flex items-center gap-2">
                                        <h3 class="text-sm font-bold text-slate-900 dark:text-white">{{ $m['name'] }}</h3>
                                        <code class="rounded bg-slate-100 px-1.5 py-0.5 text-xs text-slate-600 dark:bg-slate-800 dark:text-slate-400">{{ $m['metric_key'] }}</code>
                                    </div>
                                    <div class="flex gap-2">
                                        <span class="rounded bg-slate-100 px-2 py-0.5 text-xs font-bold text-slate-600 dark:bg-slate-800 dark:text-slate-400">{{ $m['unit'] }}</span>
                                        @if($m['is_sensitive'])
                                            <span class="rounded bg-red-100 px-2 py-0.5 text-xs font-bold text-red-800 dark:bg-red-950/40 dark:text-red-400">SENSITIVE</span>
                                        @endif
                                    </div>
                                </div>
                                <p class="text-sm text-slate-600 dark:text-slate-350">{{ $m['description'] }}</p>
                                <p class="text-xs text-slate-500 font-mono">Formula: {{ $m['formula'] }}</p>

                                @if($canManage)
                                    <div class="flex justify-end gap-2 text-xs font-bold pt-2">
                                        <a href="{{ route('analytics.metric-dictionary.edit', $m['id']) }}" class="text-slate-600 hover:text-slate-950 dark:text-slate-400 dark:hover:text-white">Edit</a>
                                        <form action="{{ route('analytics.metric-dictionary.destroy', $m['id']) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus metrik ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800">Hapus</button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </x-analytics.shell>
</div>
@endsection
