@php
    $skill = $skill ?? null;
@endphp

<div>
    <label class="block text-sm font-medium text-gray-700">Level</label>
    <select name="tahsin_level_id" class="mt-1 w-full rounded-lg border-gray-300">
        <option value="">Tanpa level khusus</option>
        @foreach($levels as $level)
            <option value="{{ $level->id }}" @selected(old('tahsin_level_id', $skill?->tahsin_level_id) == $level->id)>
                {{ $level->name }}
            </option>
        @endforeach
    </select>
</div>

<div>
    <label class="block text-sm font-medium text-gray-700">Nama Skill</label>
    <input type="text" name="name" value="{{ old('name', $skill?->name) }}" class="mt-1 w-full rounded-lg border-gray-300" required>
</div>

<div>
    <label class="block text-sm font-medium text-gray-700">Kode</label>
    <input type="text" name="code" value="{{ old('code', $skill?->code) }}" class="mt-1 w-full rounded-lg border-gray-300">
</div>

<div>
    <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
    <textarea name="description" rows="3" class="mt-1 w-full rounded-lg border-gray-300">{{ old('description', $skill?->description) }}</textarea>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700">Maximum Score</label>
        <input type="number" name="maximum_score" value="{{ old('maximum_score', $skill?->maximum_score ?? 100) }}" min="1" max="100" class="mt-1 w-full rounded-lg border-gray-300" required>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Urutan</label>
        <input type="number" name="sort_order" value="{{ old('sort_order', $skill?->sort_order ?? 0) }}" min="0" class="mt-1 w-full rounded-lg border-gray-300">
    </div>
</div>

<label class="flex items-center gap-2">
    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $skill?->is_active ?? true))>
    <span class="text-sm text-gray-700">Aktif</span>
</label>
