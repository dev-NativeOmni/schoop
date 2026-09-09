@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-6">
    <x-analytics.shell title="Executive Reports" subtitle="Laporan eksekutif periodik mengenai status, pencapaian akademik, serta risiko operasional tenant." :schoolId="$schoolId">
        <div class="flex justify-end mb-4">
            <a href="{{ route('analytics.executive-reports.create') }}" class="rounded-lg bg-slate-950 px-4 py-2 text-sm font-bold text-white transition hover:bg-slate-800 dark:bg-lime-400 dark:text-slate-950">Buat Draft Laporan Baru</a>
        </div>

        <!-- Index Table -->
        <div class="overflow-x-auto rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50 text-xs font-black uppercase text-slate-500 dark:border-slate-800 dark:bg-slate-850">
                        <th class="px-6 py-4">Judul Laporan</th>
                        <th class="px-6 py-4">Tenant</th>
                        <th class="px-6 py-4">Periode</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Dibuat Oleh</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($reports as $report)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-850">
                            <td class="px-6 py-4 font-bold text-slate-900 dark:text-white">{{ $report->title }}</td>
                            <td class="px-6 py-4">{{ $report->school->name ?? 'Internal Schoop' }}</td>
                            <td class="px-6 py-4 text-xs">{{ $report->period_start->toDateString() }} s/d {{ $report->period_end->toDateString() }}</td>
                            <td class="px-6 py-4">
                                <span class="rounded px-2 py-0.5 text-xs font-bold uppercase
                                    @if($report->status === 'published') bg-lime-100 text-lime-800 dark:bg-lime-950/40 dark:text-lime-400
                                    @else bg-slate-100 text-slate-800 dark:bg-slate-800 dark:text-slate-300
                                    @endif">
                                    {{ $report->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-xs">{{ $report->user->name ?? 'System' }}</td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <a href="{{ route('analytics.executive-reports.show', $report->id) }}" class="rounded bg-slate-100 px-3 py-1.5 text-xs font-bold text-slate-700 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700">Lihat</a>
                                <a href="{{ route('analytics.executive-reports.print', $report->id) }}" target="_blank" class="rounded bg-slate-100 px-3 py-1.5 text-xs font-bold text-slate-700 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700">Cetak</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-slate-500">Belum ada laporan eksekutif yang dibentuk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div>
            {{ $reports->links() }}
        </div>
    </x-analytics.shell>
</div>
@endsection
