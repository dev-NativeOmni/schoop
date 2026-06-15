@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-6">
    <x-analytics.shell title="Tenant Health Scoring" subtitle="Analisis kesehatan seluruh sekolah terdaftar berdasar usage, keuangan, support log, dan adopsi modul.">
        <!-- Filter -->
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <form action="{{ route('analytics.tenant-health.index') }}" method="GET" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300">Pilih Sekolah</label>
                    <select name="school_id" class="mt-1 w-full rounded-lg border border-slate-200 bg-white p-2 text-sm dark:border-slate-800 dark:bg-slate-950">
                        <option value="">Semua Sekolah</option>
                        @foreach($schools as $s)
                            <option value="{{ $s->id }}" {{ request('school_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300">Pilih Status</label>
                    <select name="status" class="mt-1 w-full rounded-lg border border-slate-200 bg-white p-2 text-sm dark:border-slate-800 dark:bg-slate-950">
                        <option value="">Semua Status</option>
                        <option value="healthy" {{ request('status') === 'healthy' ? 'selected' : '' }}>HEALTHY</option>
                        <option value="watch" {{ request('status') === 'watch' ? 'selected' : '' }}>WATCH</option>
                        <option value="risk" {{ request('status') === 'risk' ? 'selected' : '' }}>RISK</option>
                        <option value="critical" {{ request('status') === 'critical' ? 'selected' : '' }}>CRITICAL</option>
                        <option value="unknown" {{ request('status') === 'unknown' ? 'selected' : '' }}>UNKNOWN</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="rounded-lg bg-slate-950 px-4 py-2 text-sm font-bold text-white transition hover:bg-slate-800 dark:bg-lime-400 dark:text-slate-950">Filter</button>
                </div>
            </form>
        </div>

        <!-- Index Table -->
        <div class="overflow-x-auto rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50 text-xs font-black uppercase text-slate-500 dark:border-slate-800 dark:bg-slate-850">
                        <th class="px-6 py-4">Sekolah</th>
                        <th class="px-6 py-4">Skor</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Score Date</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($scores as $score)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-850">
                            <td class="px-6 py-4 font-bold text-slate-900 dark:text-white">{{ $score->school->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 font-black">{{ $score->score }}/100</td>
                            <td class="px-6 py-4">
                                <span class="rounded px-2 py-0.5 text-xs font-bold uppercase
                                    @if($score->status === 'healthy') bg-lime-100 text-lime-800 dark:bg-lime-950/40 dark:text-lime-400
                                    @elseif($score->status === 'watch') bg-amber-100 text-amber-800 dark:bg-amber-950/40 dark:text-amber-400
                                    @else bg-red-100 text-red-800 dark:bg-red-950/40 dark:text-red-400
                                    @endif">
                                    {{ $score->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-500">{{ $score->score_date->toDateString() }}</td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('analytics.tenant-health.show', $score->school_id) }}" class="rounded bg-slate-100 px-3 py-1.5 text-xs font-bold text-slate-700 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-slate-500">Belum ada skor kesehatan tenant terkalkulasi hari ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div>
            {{ $scores->links() }}
        </div>
    </x-analytics.shell>
</div>
@endsection
