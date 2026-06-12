@extends('layouts.app')

@section('content')
@php
    $fields = [
        'school_name' => 'Nama Sekolah',
        'school_address' => 'Alamat Sekolah',
        'school_phone' => 'Telepon Sekolah',
        'principal_name' => 'Nama Kepala Sekolah',
    ];
@endphp

<div class="mx-auto max-w-3xl space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-100">School Settings</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400">Pengaturan konteks sekolah untuk SchoolOS Mini.</p>
    </div>

    @if($errors->any())
        <div class="rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('schoolos.settings.update') }}" class="space-y-5 rounded-xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
        @csrf
        @method('PATCH')

        @foreach($fields as $key => $label)
            <div>
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">{{ $label }}</label>
                <input
                    type="text"
                    name="settings[{{ $key }}]"
                    value="{{ old('settings.'.$key, $settings->get($key)?->value) }}"
                    class="mt-1 w-full rounded-lg border-slate-300 text-sm"
                >
            </div>
        @endforeach

        <div class="text-right">
            <button class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white">Simpan Settings</button>
        </div>
    </form>
</div>
@endsection
