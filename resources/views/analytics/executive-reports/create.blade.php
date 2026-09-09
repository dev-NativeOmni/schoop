@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-6">
    <x-analytics.shell title="Generate Executive Report" subtitle="Buat draft laporan eksekutif berkala akademik, finansial, dan operasional." :schoolId="$schoolId">
        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 max-w-2xl">
            <form action="{{ route('analytics.executive-reports.store') }}" method="POST" class="space-y-4">
                @csrf

                @if(! $schoolId)
                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300">Sekolah / Tenant</label>
                        <select name="school_id" class="mt-1 w-full rounded-lg border border-slate-200 bg-white p-2 text-sm dark:border-slate-800 dark:bg-slate-950">
                            <option value="">Laporan Gabungan Internal Schoop</option>
                            @foreach($schools as $s)
                                <option value="{{ $s->id }}">{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @else
                    <input type="hidden" name="school_id" value="{{ $schoolId }}">
                @endif

                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300">Tipe Periode</label>
                    <select name="report_type" class="mt-1 w-full rounded-lg border border-slate-200 bg-white p-2 text-sm dark:border-slate-800 dark:bg-slate-950">
                        <option value="monthly">Bulanan</option>
                        <option value="quarterly">Kuartal</option>
                        <option value="yearly">Tahunan</option>
                    </select>
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300">Mulai Periode</label>
                        <input type="date" name="period_start" required class="mt-1 w-full rounded-lg border border-slate-200 bg-white p-2 text-sm dark:border-slate-800 dark:bg-slate-950">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300">Akhir Periode</label>
                        <input type="date" name="period_end" required class="mt-1 w-full rounded-lg border border-slate-200 bg-white p-2 text-sm dark:border-slate-800 dark:bg-slate-950">
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-4">
                    <a href="{{ route('analytics.executive-reports.index') }}" class="rounded-lg bg-slate-100 px-4 py-2 text-sm font-bold text-slate-700 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300">Batal</a>
                    <button type="submit" class="rounded-lg bg-slate-950 px-4 py-2 text-sm font-bold text-white transition hover:bg-slate-800 dark:bg-lime-400 dark:text-slate-950">Proses Report</button>
                </div>
            </form>
        </div>
    </x-analytics.shell>
</div>
@endsection
