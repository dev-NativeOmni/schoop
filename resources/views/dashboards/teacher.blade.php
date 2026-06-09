@extends('layouts.app')

@section('content')
    <div class="rounded-2xl bg-white p-8 shadow-sm">
        <h2 class="text-2xl font-bold">Dashboard Guru Tahfidz</h2>
        <p class="mt-2 text-slate-600">
            Placeholder dashboard untuk guru tahfidz.
        </p>

        <a href="{{ route('tahfizh.hafalan-records.create') }}"
           class="mt-4 inline-block rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">
            Input Setoran Tahfizh
        </a>
    </div>
@endsection
