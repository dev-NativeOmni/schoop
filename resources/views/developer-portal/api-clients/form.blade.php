@php
    $selectedScopes = collect(old('scope_ids', $client?->scopes?->pluck('id')->all() ?? []))->map(fn ($id) => (int) $id)->all();
    $allowedIps = old('allowed_ips', $client?->allowed_ips ? implode("\n", $client->allowed_ips) : '');
@endphp

<form action="{{ $action }}" method="POST" class="space-y-5 rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
    @csrf
    @if($method !== 'POST')
        @method($method)
    @endif

    <div class="grid gap-4 md:grid-cols-2">
        <div>
            <label class="text-xs font-black uppercase text-slate-500 dark:text-slate-400">Nama Client</label>
            <input name="name" value="{{ old('name', $client?->name) }}" required class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-950 dark:text-white">
        </div>
        <div>
            <label class="text-xs font-black uppercase text-slate-500 dark:text-slate-400">Client Code</label>
            <input name="client_code" value="{{ old('client_code', $client?->client_code) }}" placeholder="Opsional, otomatis jika kosong" class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-950 dark:text-white">
        </div>
        <div>
            <label class="text-xs font-black uppercase text-slate-500 dark:text-slate-400">Sekolah</label>
            <select name="school_id" class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                <option value="">Global / Internal</option>
                @foreach($schools as $school)
                    <option value="{{ $school->id }}" @selected((int) old('school_id', $client?->school_id) === (int) $school->id)>{{ $school->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="text-xs font-black uppercase text-slate-500 dark:text-slate-400">Status</label>
            <select name="status" class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                @foreach(['draft', 'active', 'suspended', 'revoked'] as $status)
                    <option value="{{ $status }}" @selected(old('status', $client?->status ?? 'draft') === $status)>{{ $status }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="text-xs font-black uppercase text-slate-500 dark:text-slate-400">Owner Name</label>
            <input name="owner_name" value="{{ old('owner_name', $client?->owner_name) }}" class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-950 dark:text-white">
        </div>
        <div>
            <label class="text-xs font-black uppercase text-slate-500 dark:text-slate-400">Owner Email</label>
            <input name="owner_email" value="{{ old('owner_email', $client?->owner_email) }}" type="email" class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-950 dark:text-white">
        </div>
        <div>
            <label class="text-xs font-black uppercase text-slate-500 dark:text-slate-400">Rate Limit per Menit</label>
            <input name="rate_limit_per_minute" value="{{ old('rate_limit_per_minute', $client?->rate_limit_per_minute ?? 120) }}" type="number" min="1" max="1000" required class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-950 dark:text-white">
        </div>
        <div>
            <label class="text-xs font-black uppercase text-slate-500 dark:text-slate-400">Allowed IPs</label>
            <textarea name="allowed_ips" rows="3" placeholder="Satu IP per baris, kosongkan untuk semua IP" class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-950 dark:text-white">{{ $allowedIps }}</textarea>
        </div>
    </div>

    <div>
        <label class="text-xs font-black uppercase text-slate-500 dark:text-slate-400">Deskripsi</label>
        <textarea name="description" rows="3" class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-950 dark:text-white">{{ old('description', $client?->description) }}</textarea>
    </div>

    <div>
        <p class="text-xs font-black uppercase text-slate-500 dark:text-slate-400">Scopes</p>
        <div class="mt-2 grid gap-2 md:grid-cols-2">
            @foreach($scopes as $scope)
                <label class="flex gap-3 rounded-lg border border-slate-200 p-3 text-sm dark:border-slate-800">
                    <input type="checkbox" name="scope_ids[]" value="{{ $scope->id }}" @checked(in_array((int) $scope->id, $selectedScopes, true)) class="mt-1 rounded border-slate-300 text-lime-600">
                    <span>
                        <span class="block font-black text-slate-900 dark:text-white">{{ $scope->code }}</span>
                        <span class="block text-xs text-slate-500 dark:text-slate-400">{{ $scope->description }}</span>
                    </span>
                </label>
            @endforeach
        </div>
    </div>

    <div class="flex flex-wrap gap-2">
        <button class="rounded-lg bg-slate-950 px-4 py-2 text-sm font-black text-white dark:bg-lime-400 dark:text-slate-950">Simpan</button>
        <a href="{{ route('developer-portal.api-clients.index') }}" class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-black text-slate-700 dark:border-slate-700 dark:text-slate-200">Batal</a>
    </div>
</form>
