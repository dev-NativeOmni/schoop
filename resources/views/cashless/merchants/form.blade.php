<form method="POST" action="{{ $merchant ? route('cashless.merchants.update', $merchant) : route('cashless.merchants.store') }}" class="rounded-xl border border-slate-200 bg-white p-5 dark:border-slate-800 dark:bg-slate-900">
    @csrf
    @if ($merchant) @method('PUT') @endif
    <div class="grid gap-4 md:grid-cols-2">
        <label class="text-sm font-bold text-slate-700 dark:text-slate-200">Nama <input name="name" value="{{ old('name', $merchant?->name) }}" class="mt-1 w-full rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-950" required></label>
        <label class="text-sm font-bold text-slate-700 dark:text-slate-200">Kode <input name="code" value="{{ old('code', $merchant?->code) }}" class="mt-1 w-full rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-950" required></label>
        <label class="text-sm font-bold text-slate-700 dark:text-slate-200">Tipe <select name="type" class="mt-1 w-full rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-950">@foreach(['canteen','cooperative','bookstore','laundry','other'] as $type)<option value="{{ $type }}" @selected(old('type', $merchant?->type ?? 'canteen') === $type)>{{ $type }}</option>@endforeach</select></label>
        <label class="text-sm font-bold text-slate-700 dark:text-slate-200">Status <select name="status" class="mt-1 w-full rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-950"><option value="active" @selected(old('status', $merchant?->status ?? 'active') === 'active')>active</option><option value="inactive" @selected(old('status', $merchant?->status) === 'inactive')>inactive</option></select></label>
        <label class="text-sm font-bold text-slate-700 dark:text-slate-200">Telepon <input name="phone" value="{{ old('phone', $merchant?->phone) }}" class="mt-1 w-full rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-950"></label>
    </div>
    <label class="mt-4 block text-sm font-bold text-slate-700 dark:text-slate-200">Kasir
        <select name="cashier_user_ids[]" multiple class="mt-1 h-36 w-full rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-950">
            @foreach ($users as $user)
                <option value="{{ $user->id }}" @selected(in_array($user->id, old('cashier_user_ids', $assignedUserIds), true))>{{ $user->name }} · {{ $user->role?->name }}</option>
            @endforeach
        </select>
    </label>
    <label class="mt-4 block text-sm font-bold text-slate-700 dark:text-slate-200">Deskripsi <textarea name="description" class="mt-1 w-full rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-950">{{ old('description', $merchant?->description) }}</textarea></label>
    <button class="mt-5 rounded-lg bg-slate-900 px-4 py-2 text-sm font-black text-white dark:bg-lime-400 dark:text-slate-950">Simpan</button>
</form>
