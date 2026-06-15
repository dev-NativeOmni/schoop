@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-6">
    <x-analytics.shell :title="$report->title" :subtitle="'Periode Laporan: ' . $report->period_start->toDateString() . ' s/d ' . $report->period_end->toDateString()" :schoolId="$report->school_id">
        <div class="flex justify-end gap-2 mb-4">
            <a href="{{ route('analytics.executive-reports.print', $report->id) }}" target="_blank" class="rounded-lg bg-slate-100 px-4 py-2 text-sm font-bold text-slate-700 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300">Cetak PDF / Print</a>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            <!-- Summary, Highlights, Risks -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Summary -->
                <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <h3 class="text-lg font-black text-slate-950 dark:text-white">Ringkasan Eksekutif</h3>
                    <p class="mt-3 text-sm text-slate-700 dark:text-slate-350 leading-relaxed">{{ $report->summary ?: 'Tidak ada ringkasan tertulis.' }}</p>
                </div>

                <!-- Sections content -->
                @foreach($report->sections as $sec)
                    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <h3 class="text-lg font-black text-slate-950 dark:text-white">{{ $sec->title }}</h3>
                        <p class="mt-3 text-sm text-slate-700 dark:text-slate-350 leading-relaxed">{{ $sec->content }}</p>
                    </div>
                @endforeach
            </div>

            <!-- Side recommendations / Info -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Status/Meta -->
                <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <h4 class="text-xs font-black uppercase text-slate-500">Metadata Laporan</h4>
                    <div class="mt-3 text-sm space-y-2 text-slate-700 dark:text-slate-300">
                        <p><strong>Tenant:</strong> {{ $report->school->name ?? 'Internal HafizPlus' }}</p>
                        <p><strong>Status:</strong> {{ strtoupper($report->status) }}</p>
                        <p><strong>Pembuat:</strong> {{ $report->user->name ?? 'System' }}</p>
                        <p><strong>Dibuat Pada:</strong> {{ $report->generated_at?->toDateTimeString() ?: $report->created_at->toDateTimeString() }}</p>
                    </div>
                </div>

                <!-- Highlights list -->
                <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <h4 class="text-sm font-black text-slate-950 dark:text-white">Poin Utama (Highlights)</h4>
                    <ul class="mt-3 list-disc pl-5 text-sm text-slate-700 dark:text-slate-300 space-y-2">
                        @foreach($report->highlights ?: ['Tidak ada highlight dicatat.'] as $hl)
                            <li>{{ $hl }}</li>
                        @endforeach
                    </ul>
                </div>

                <!-- Risks list -->
                <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <h4 class="text-sm font-black text-red-600 dark:text-red-400">Pola Risiko & Isu</h4>
                    <ul class="mt-3 list-disc pl-5 text-sm text-slate-700 dark:text-slate-300 space-y-2">
                        @foreach($report->risks ?: ['Tidak ada risiko terindikasi.'] as $r)
                            <li>{{ $r }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </x-analytics.shell>
</div>
@endsection
