@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-6">
    <x-developer-portal.shell title="API Documentation" subtitle="Dokumentasi operasional untuk partner dan integrasi internal.">
        <x-slot:action>
            <a href="{{ route('developer-portal.docs.create') }}" class="inline-flex rounded-lg bg-slate-950 px-4 py-2 text-sm font-black text-white dark:bg-lime-400 dark:text-slate-950">Halaman Baru</a>
        </x-slot:action>

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            @forelse($pages as $page)
                <a href="{{ route('developer-portal.docs.show', $page) }}" class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm transition hover:border-lime-300 dark:border-slate-800 dark:bg-slate-900 dark:hover:border-lime-500">
                    <p class="text-xs font-black uppercase text-slate-500 dark:text-slate-400">{{ $page->category ?? 'Docs' }} / {{ $page->status }}</p>
                    <h2 class="mt-2 text-lg font-black text-slate-950 dark:text-white">{{ $page->title }}</h2>
                    <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">{{ $page->slug }}</p>
                </a>
            @empty
                <div class="rounded-xl border border-slate-200 bg-white p-10 text-center text-sm text-slate-500 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-400">Belum ada dokumentasi.</div>
            @endforelse
        </div>

        {{ $pages->links() }}
    </x-developer-portal.shell>
</div>
@endsection
