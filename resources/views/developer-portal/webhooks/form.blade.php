@php
    $selectedEvents = old('subscribed_events', $webhook?->subscribed_events ?? []);
@endphp

<form action="{{ $action }}" method="POST" class="space-y-5 rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
    @csrf
    @if($method !== 'POST')
        @method($method)
    @endif

    <div class="grid gap-4 md:grid-cols-2">
        <div>
            <label class="text-xs font-black uppercase text-slate-500 dark:text-slate-400">Nama Endpoint</label>
            <input name="name" value="{{ old('name', $webhook?->name) }}" required class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-white">
        </div>
        <div>
            <label class="text-xs font-black uppercase text-slate-500 dark:text-slate-400">Status</label>
            <select name="status" class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                @foreach($statuses as $status)
                    <option value="{{ $status }}" @selected(old('status', $webhook?->status ?? 'active') === $status)>{{ $status }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="text-xs font-black uppercase text-slate-500 dark:text-slate-400">Sekolah</label>
            <select name="school_id" class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                <option value="">Global</option>
                @foreach($schools as $school)
                    <option value="{{ $school->id }}" @selected((int) old('school_id', $webhook?->school_id) === (int) $school->id)>{{ $school->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="text-xs font-black uppercase text-slate-500 dark:text-slate-400">API Client</label>
            <select name="api_client_id" required class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                <option value="">Pilih client</option>
                @foreach($clients as $client)
                    <option value="{{ $client->id }}" @selected((int) old('api_client_id', $webhook?->api_client_id) === (int) $client->id)>{{ $client->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="md:col-span-2">
            <label class="text-xs font-black uppercase text-slate-500 dark:text-slate-400">URL</label>
            <input name="url" value="{{ old('url', $webhook?->url) }}" type="url" required class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-white">
        </div>
        <div class="md:col-span-2">
            <label class="text-xs font-black uppercase text-slate-500 dark:text-slate-400">Secret</label>
            <input name="secret" type="password" placeholder="{{ $webhook ? 'Kosongkan untuk mempertahankan secret lama' : 'Minimal 16 karakter' }}" class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-white">
        </div>
    </div>

    <div>
        <p class="text-xs font-black uppercase text-slate-500 dark:text-slate-400">Subscribed Events</p>
        <div class="mt-2 grid gap-2 md:grid-cols-2">
            @foreach($events as $event)
                <label class="flex items-center gap-3 rounded-lg border border-slate-200 p-3 text-sm dark:border-slate-800">
                    <input type="checkbox" name="subscribed_events[]" value="{{ $event }}" @checked(in_array($event, $selectedEvents, true)) class="rounded border-slate-300 text-lime-600">
                    <span class="font-black text-slate-900 dark:text-white">{{ $event }}</span>
                </label>
            @endforeach
        </div>
    </div>

    <div class="flex flex-wrap gap-2">
        <button class="rounded-lg bg-slate-950 px-4 py-2 text-sm font-black text-white dark:bg-lime-400 dark:text-slate-950">Simpan</button>
        <a href="{{ route('developer-portal.webhooks.index') }}" class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-black text-slate-700 dark:border-slate-700 dark:text-slate-200">Batal</a>
    </div>
</form>
