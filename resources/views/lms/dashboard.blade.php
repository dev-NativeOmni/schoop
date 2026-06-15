@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <div class="flex items-center justify-between border-b pb-4">
        <div>
            <h1 class="text-3xl font-extrabold tracking-tight">LMS Lite & Learning Content</h1>
            <p class="text-slate-500">Ringkasan aktivitas pembelajaran dan materi sekolah.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('lms.courses.index') }}" class="btn-primary px-4 py-2 text-white font-medium rounded-lg">Kelola Kelas</a>
            <a href="{{ route('lms.reports.index') }}" class="bg-slate-700 hover:bg-slate-800 text-white px-4 py-2 font-medium rounded-lg">Laporan Progres</a>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid gap-6 md:grid-cols-3">
        <div class="rounded-2xl border bg-white p-6 shadow-sm dark:bg-slate-900 dark:border-slate-800">
            <div class="flex items-center justify-between">
                <span class="text-sm font-medium text-slate-500">Total Kelas</span>
                <span class="text-xs font-semibold bg-blue-100 text-blue-800 px-2 py-0.5 rounded">Aktif</span>
            </div>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-4xl font-bold tracking-tight">{{ $totalCourses }}</span>
            </div>
        </div>

        <div class="rounded-2xl border bg-white p-6 shadow-sm dark:bg-slate-900 dark:border-slate-800">
            <div class="flex items-center justify-between">
                <span class="text-sm font-medium text-slate-500">Santri Terdaftar</span>
                <span class="text-xs font-semibold bg-green-100 text-green-800 px-2 py-0.5 rounded">Aktif</span>
            </div>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-4xl font-bold tracking-tight">{{ $totalEnrollments }}</span>
            </div>
        </div>

        <div class="rounded-2xl border bg-white p-6 shadow-sm dark:bg-slate-900 dark:border-slate-800">
            <div class="flex items-center justify-between">
                <span class="text-sm font-medium text-slate-500">Rata-rata Progres</span>
                <span class="text-xs font-semibold bg-amber-100 text-amber-800 px-2 py-0.5 rounded">Rata-rata</span>
            </div>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-4xl font-bold tracking-tight">{{ number_format($averageProgress, 2) }}%</span>
            </div>
        </div>
    </div>

    <!-- Recent Activity Log -->
    <div class="rounded-2xl border bg-white shadow-sm dark:bg-slate-900 dark:border-slate-800">
        <div class="border-b px-6 py-4">
            <h2 class="text-lg font-bold">Aktivitas LMS Terbaru</h2>
        </div>
        <div class="p-6">
            @if($recentActivities->isEmpty())
                <p class="text-slate-500 text-center py-4">Belum ada aktivitas tercatat.</p>
            @else
                <div class="flow-root">
                    <ul class="-mb-8">
                        @foreach($recentActivities as $activity)
                            <li>
                                <div class="relative pb-8">
                                    <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-slate-200" aria-hidden="true"></span>
                                    <div class="relative flex space-x-3">
                                        <div>
                                            <span class="h-8 w-8 rounded-full bg-slate-100 flex items-center justify-center ring-8 ring-white dark:bg-slate-800">
                                                📝
                                            </span>
                                        </div>
                                        <div class="flex-1 min-w-0 pt-1.5 flex justify-between space-x-4">
                                            <div>
                                                <p class="text-sm text-slate-800 dark:text-slate-200">
                                                    {{ $activity->description }}
                                                    <span class="font-medium text-slate-600 dark:text-slate-400">by {{ $activity->user?->name ?? 'System' }}</span>
                                                </p>
                                            </div>
                                            <div class="text-right text-xs whitespace-nowrap text-slate-500">
                                                <time>{{ $activity->created_at->diffForHumans() }}</time>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
