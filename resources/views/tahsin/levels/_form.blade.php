@php
    $level = $level ?? null;
@endphp

<div>
    <label class="block text-sm font-medium text-gray-700">Nama Level</label>
    <input type="text"
           name="name"
           value="{{ old('name', $level?->name) }}"
           class="mt-1 w-full rounded-lg border-gray-300"
           required>
</div>

<div>
    <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
    <textarea name="description"
              rows="3"
              class="mt-1 w-full rounded-lg border-gray-300">{{ old('description', $level?->description) }}</textarea>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700">Minimum Score</label>
        <input type="number"
               name="minimum_score"
               value="{{ old('minimum_score', $level?->minimum_score ?? 70) }}"
               min="0"
               max="100"
               class="mt-1 w-full rounded-lg border-gray-300"
               required>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Urutan</label>
        <input type="number"
               name="sort_order"
               value="{{ old('sort_order', $level?->sort_order ?? 0) }}"
               min="0"
               class="mt-1 w-full rounded-lg border-gray-300">
    </div>
</div>

<label class="flex items-center gap-2">
    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $level?->is_active ?? true))>
    <span class="text-sm text-gray-700">Aktif</span>
</label>
