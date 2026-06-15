<form action="{{ $action }}" method="POST" class="space-y-5 rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
    @csrf
    @if($method !== 'POST')
        @method($method)
    @endif

    <div class="grid gap-4 md:grid-cols-2">
        <div>
            <label class="text-xs font-black uppercase text-slate-500 dark:text-slate-400">Nama Integrasi</label>
            <input name="name" value="{{ old('name', $integration?->name) }}" required class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-white">
        </div>
        <div>
            <label class="text-xs font-black uppercase text-slate-500 dark:text-slate-400">Provider</label>
            <input name="provider_name" value="{{ old('provider_name', $integration?->provider_name) }}" class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-white">
        </div>
        <div>
            <label class="text-xs font-black uppercase text-slate-500 dark:text-slate-400">Tipe</label>
            <select name="integration_type" class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                @foreach($types as $type)
                    <option value="{{ $type }}" @selected(old('integration_type', $integration?->integration_type ?? 'custom') === $type)>{{ $type }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="text-xs font-black uppercase text-slate-500 dark:text-slate-400">Status</label>
            <select name="status" class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                @foreach($statuses as $status)
                    <option value="{{ $status }}" @selected(old('status', $integration?->status ?? 'draft') === $status)>{{ $status }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="text-xs font-black uppercase text-slate-500 dark:text-slate-400">Sekolah</label>
            <select name="school_id" class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                <option value="">Global</option>
                @foreach($schools as $school)
                    <option value="{{ $school->id }}" @selected((int) old('school_id', $integration?->school_id) === (int) $school->id)>{{ $school->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="text-xs font-black uppercase text-slate-500 dark:text-slate-400">API Client</label>
            <select name="api_client_id" class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                <option value="">Belum ditautkan</option>
                @foreach($clients as $client)
                    <option value="{{ $client->id }}" @selected((int) old('api_client_id', $integration?->api_client_id) === (int) $client->id)>{{ $client->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div>
        <label class="text-xs font-black uppercase text-slate-500 dark:text-slate-400">Configuration JSON</label>
        <textarea name="configuration" rows="6" class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 font-mono text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-white">{{ old('configuration', $integration?->configuration ? json_encode($integration->configuration, JSON_PRETTY_PRINT) : '') }}</textarea>
    </div>
    <div>
        <label class="text-xs font-black uppercase text-slate-500 dark:text-slate-400">Catatan</label>
        <textarea name="notes" rows="4" class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-white">{{ old('notes', $integration?->notes) }}</textarea>
    </div>

    <div class="flex flex-wrap gap-2">
        <button class="rounded-lg bg-slate-950 px-4 py-2 text-sm font-black text-white dark:bg-lime-400 dark:text-slate-950">Simpan</button>
        <a href="{{ route('developer-portal.partner-integrations.index') }}" class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-black text-slate-700 dark:border-slate-700 dark:text-slate-200">Batal</a>
    </div>
</form>
