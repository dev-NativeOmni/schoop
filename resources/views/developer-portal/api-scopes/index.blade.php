@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-6">
    <x-developer-portal.shell title="API Scopes" subtitle="Daftar permission granular untuk External API v1.">
        <div class="overflow-x-auto rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50 text-xs uppercase text-slate-500 dark:bg-slate-950 dark:text-slate-400">
                    <tr>
                        <th class="px-4 py-3 text-left">Scope</th>
                        <th class="px-4 py-3 text-left">Kategori</th>
                        <th class="px-4 py-3 text-left">Sensitive</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                    @forelse($scopes as $scope)
                        <tr class="text-slate-700 dark:text-slate-200">
                            <td class="px-4 py-3">
                                <p class="font-black text-slate-950 dark:text-white">{{ $scope->code }}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400">{{ $scope->description }}</p>
                            </td>
                            <td class="px-4 py-3">{{ $scope->category }}</td>
                            <td class="px-4 py-3">{{ $scope->is_sensitive ? 'Ya' : 'Tidak' }}</td>
                            <td class="px-4 py-3">{{ $scope->is_active ? 'active' : 'inactive' }}</td>
                            <td class="px-4 py-3 text-right"><a href="{{ route('developer-portal.api-scopes.show', $scope) }}" class="font-black text-lime-700 dark:text-lime-300">Detail</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-4 py-10 text-center text-sm text-slate-500 dark:text-slate-400">Belum ada scope.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $scopes->links() }}
    </x-developer-portal.shell>
</div>
@endsection
