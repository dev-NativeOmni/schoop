@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Module Registry</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400">Status modul internal SchoolOS Mini.</p>
    </div>

    <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
        @forelse($modules as $module)
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2">
                            <h2 class="font-bold text-slate-900 dark:text-slate-100">{{ $module->name }}</h2>
                            @if($module->is_core)
                                <span class="rounded-full bg-indigo-50 px-2 py-0.5 text-xs font-semibold text-indigo-700">Core</span>
                            @endif
                        </div>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $module->description }}</p>
                        <p class="mt-2 text-xs {{ $module->route_exists ? 'text-emerald-600' : 'text-rose-600' }}">
                            Route: {{ $module->route_name ?? '-' }} {{ $module->route_exists ? '(ready)' : '(missing)' }}
                        </p>
                    </div>
                    <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $module->is_enabled ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                        {{ $module->is_enabled ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>

                @php
                    $role = auth()->user()?->role?->name;
                    $canManage = in_array($role, ['super_admin', 'admin', 'admin_sekolah'], true);
                @endphp

                @if($canManage)
                    <form method="POST" action="{{ route('schoolos.modules.update', $module) }}" class="mt-4 flex flex-wrap items-center gap-3 border-t border-slate-100 pt-4 dark:border-slate-800">
                        @csrf
                        @method('PATCH')

                        <label class="inline-flex items-center gap-2 text-sm text-slate-700 dark:text-slate-300">
                            <input type="checkbox" name="is_enabled" value="1" @checked($module->is_enabled)>
                            Aktif
                        </label>

                        <label class="flex items-center gap-2 text-sm text-slate-700 dark:text-slate-300">
                            Urutan
                            <input type="number" name="sort_order" value="{{ $module->sort_order }}" min="0" max="65535" class="w-24 rounded-lg border-slate-300 text-sm">
                        </label>

                        <button class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white">Simpan</button>
                    </form>
                @endif
            </div>
        @empty
            <div class="rounded-xl border border-slate-200 bg-white p-8 text-center text-slate-500 dark:border-slate-800 dark:bg-slate-900">
                Belum ada module registry. Jalankan seeder SystemModuleSeeder.
            </div>
        @endforelse
    </div>
</div>
@endsection
