@extends('layouts.app')

@section('content')
    <div class="rounded-2xl bg-white p-8 shadow-sm">
        <h2 class="text-2xl font-bold">Dashboard Orang Tua</h2>
        <p class="mt-2 text-slate-600">
            Placeholder dashboard untuk orang tua.
        </p>

        <div class="mt-4 flex flex-wrap gap-3">
            <a href="{{ route('portal.parent.dashboard') }}"
               class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">
                Buka Portal Orang Tua
            </a>

            <a href="{{ route('notifications.index') }}"
               class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                Lihat Notifikasi
            </a>
        </div>
    </div>
@endsection
