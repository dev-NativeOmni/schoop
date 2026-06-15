<form action="{{ $action }}" method="POST" class="space-y-5 rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
    @csrf
    @if($method !== 'POST')
        @method($method)
    @endif

    <div class="grid gap-4 md:grid-cols-2">
        <div>
            <label class="text-xs font-black uppercase text-slate-500 dark:text-slate-400">Slug</label>
            <input name="slug" value="{{ old('slug', $page?->slug) }}" required class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-white">
        </div>
        <div>
            <label class="text-xs font-black uppercase text-slate-500 dark:text-slate-400">Judul</label>
            <input name="title" value="{{ old('title', $page?->title) }}" required class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-white">
        </div>
        <div>
            <label class="text-xs font-black uppercase text-slate-500 dark:text-slate-400">Kategori</label>
            <input name="category" value="{{ old('category', $page?->category) }}" class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-white">
        </div>
        <div>
            <label class="text-xs font-black uppercase text-slate-500 dark:text-slate-400">Sort Order</label>
            <input name="sort_order" value="{{ old('sort_order', $page?->sort_order ?? 0) }}" type="number" min="0" required class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-white">
        </div>
        <div>
            <label class="text-xs font-black uppercase text-slate-500 dark:text-slate-400">Visibility</label>
            <select name="visibility" class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                @foreach(['internal', 'partner', 'public'] as $visibility)
                    <option value="{{ $visibility }}" @selected(old('visibility', $page?->visibility ?? 'partner') === $visibility)>{{ $visibility }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="text-xs font-black uppercase text-slate-500 dark:text-slate-400">Status</label>
            <select name="status" class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                @foreach(['draft', 'published', 'archived'] as $status)
                    <option value="{{ $status }}" @selected(old('status', $page?->status ?? 'draft') === $status)>{{ $status }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div>
        <label class="text-xs font-black uppercase text-slate-500 dark:text-slate-400">Content</label>
        <textarea name="content" rows="14" required class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 font-mono text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-white">{{ old('content', $page?->content) }}</textarea>
    </div>

    <div class="flex flex-wrap gap-2">
        <button class="rounded-lg bg-slate-950 px-4 py-2 text-sm font-black text-white dark:bg-lime-400 dark:text-slate-950">Simpan</button>
        <a href="{{ route('developer-portal.docs.index') }}" class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-black text-slate-700 dark:border-slate-700 dark:text-slate-200">Batal</a>
    </div>
</form>
