@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-6">
    <x-developer-portal.shell title="{{ $scope->code }}" subtitle="{{ $scope->name }}">
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="grid gap-4 md:grid-cols-4">
                <div>
                    <p class="text-xs font-black uppercase text-slate-500 dark:text-slate-400">Kategori</p>
                    <p class="mt-1 font-bold text-slate-900 dark:text-white">{{ $scope->category }}</p>
                </div>
                <div>
                    <p class="text-xs font-black uppercase text-slate-500 dark:text-slate-400">Sensitive</p>
                    <p class="mt-1 font-bold text-slate-900 dark:text-white">{{ $scope->is_sensitive ? 'Ya' : 'Tidak' }}</p>
                </div>
                <div>
                    <p class="text-xs font-black uppercase text-slate-500 dark:text-slate-400">Status</p>
                    <p class="mt-1 font-bold text-slate-900 dark:text-white">{{ $scope->is_active ? 'active' : 'inactive' }}</p>
                </div>
                <div>
                    <p class="text-xs font-black uppercase text-slate-500 dark:text-slate-400">Client Granted</p>
                    <p class="mt-1 font-bold text-slate-900 dark:text-white">{{ $scope->clients_count }}</p>
                </div>
            </div>
            <p class="mt-5 text-sm leading-6 text-slate-700 dark:text-slate-200">{{ $scope->description }}</p>
        </div>
    </x-developer-portal.shell>
</div>
@endsection
