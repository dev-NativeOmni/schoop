<form method="POST" action="{{ $product ? route('cashless.products.update', $product) : route('cashless.products.store') }}" class="rounded-xl border border-slate-200 bg-white p-5 dark:border-slate-800 dark:bg-slate-900">
    @csrf @if($product) @method('PUT') @endif
    <div class="grid gap-4 md:grid-cols-2">
        <label class="text-sm font-bold dark:text-slate-200">Merchant <select name="cashless_merchant_id" class="mt-1 w-full rounded-lg dark:border-slate-700 dark:bg-slate-950">@foreach($merchants as $merchant)<option value="{{ $merchant->id }}" @selected(old('cashless_merchant_id', $product?->cashless_merchant_id) == $merchant->id)>{{ $merchant->name }}</option>@endforeach</select></label>
        <label class="text-sm font-bold dark:text-slate-200">Nama <input name="name" value="{{ old('name', $product?->name) }}" class="mt-1 w-full rounded-lg dark:border-slate-700 dark:bg-slate-950" required></label>
        <label class="text-sm font-bold dark:text-slate-200">SKU <input name="sku" value="{{ old('sku', $product?->sku) }}" class="mt-1 w-full rounded-lg dark:border-slate-700 dark:bg-slate-950"></label>
        <label class="text-sm font-bold dark:text-slate-200">Harga <input type="number" name="price" value="{{ old('price', $product?->price) }}" class="mt-1 w-full rounded-lg dark:border-slate-700 dark:bg-slate-950" required></label>
        <label class="text-sm font-bold dark:text-slate-200">Stok <input type="number" name="stock" value="{{ old('stock', $product?->stock) }}" class="mt-1 w-full rounded-lg dark:border-slate-700 dark:bg-slate-950"></label>
        <label class="text-sm font-bold dark:text-slate-200">Status <select name="status" class="mt-1 w-full rounded-lg dark:border-slate-700 dark:bg-slate-950"><option value="active" @selected(old('status', $product?->status ?? 'active') === 'active')>active</option><option value="inactive" @selected(old('status', $product?->status) === 'inactive')>inactive</option></select></label>
    </div>
    <label class="mt-4 flex items-center gap-2 text-sm font-bold dark:text-slate-200"><input type="checkbox" name="track_stock" value="1" @checked(old('track_stock', $product?->track_stock))> Track stock</label>
    <label class="mt-4 block text-sm font-bold dark:text-slate-200">Deskripsi <textarea name="description" class="mt-1 w-full rounded-lg dark:border-slate-700 dark:bg-slate-950">{{ old('description', $product?->description) }}</textarea></label>
    <button class="mt-5 rounded-lg bg-slate-900 px-4 py-2 text-sm font-black text-white dark:bg-lime-400 dark:text-slate-950">Simpan</button>
</form>
