@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-6">
    <x-developer-portal.shell title="{{ $page->title }}" subtitle="{{ $page->category ?? 'Docs' }} / {{ $page->slug }}">
        <x-slot:action>
            <a href="{{ route('developer-portal.docs.edit', $page) }}" class="inline-flex rounded-lg border border-slate-200 px-4 py-2 text-sm font-black text-slate-700 dark:border-slate-700 dark:text-slate-200">Edit</a>
        </x-slot:action>

        <article class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="flex flex-wrap gap-2 text-xs font-black">
                <span class="rounded-full bg-slate-100 px-3 py-1 text-slate-700 dark:bg-slate-800 dark:text-slate-200">{{ $page->visibility }}</span>
                <span class="rounded-full bg-lime-50 px-3 py-1 text-lime-800 dark:bg-lime-950/40 dark:text-lime-200">{{ $page->status }}</span>
                <span class="rounded-full bg-slate-100 px-3 py-1 text-slate-700 dark:bg-slate-800 dark:text-slate-200">Sort {{ $page->sort_order }}</span>
            </div>
            <div class="prose prose-slate mt-6 max-w-none dark:prose-invert">
                {!! nl2br(e($page->content)) !!}
            </div>
        </article>

        <form action="{{ route('developer-portal.docs.destroy', $page) }}" method="POST">
            @csrf
            @method('DELETE')
            <button class="rounded-lg bg-red-600 px-4 py-2 text-sm font-black text-white">Arsipkan</button>
        </form>
    </x-developer-portal.shell>
</div>
@endsection
