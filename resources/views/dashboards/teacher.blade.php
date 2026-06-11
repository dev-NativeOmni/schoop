@extends('layouts.app')

@section('content')
    <div class="rounded-2xl bg-white p-8 shadow-sm">
        <h2 class="text-2xl font-bold">Dashboard Guru Tahfidz</h2>
        <p class="mt-2 text-slate-600">
            Placeholder dashboard untuk guru tahfidz.
        </p>

        <div class="mt-4 flex flex-wrap gap-3">
            <a href="{{ route('tahfizh.hafalan-records.create') }}"
               class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">
                Input Setoran Tahfizh
            </a>

            <a href="{{ route('reports.tahfizh.dashboard') }}"
               class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">
                Dashboard Tahfizh
            </a>

            <a href="{{ route('reports.tahfizh.monthly.index') }}"
               class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                Laporan Bulanan
            </a>

            <a href="{{ route('reports.tahfizh.quarterly.index') }}"
               class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                Laporan Triwulan
            </a>
        </div>
    </div>
@endsection
