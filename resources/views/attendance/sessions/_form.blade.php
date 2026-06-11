@php
    $session = $session ?? null;
@endphp

<div class="grid grid-cols-1 gap-4 md:grid-cols-2">
    <div>
        <label class="block text-sm font-medium text-gray-700">Nama Session</label>
        <input type="text"
               name="name"
               value="{{ old('name', $session?->name ?? 'Presensi Harian') }}"
               class="mt-1 w-full rounded-lg border-gray-300"
               required>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Tanggal</label>
        <input type="date"
               name="attendance_date"
               value="{{ old('attendance_date', $session?->attendance_date?->toDateString() ?? now()->toDateString()) }}"
               class="mt-1 w-full rounded-lg border-gray-300"
               required>
    </div>
</div>

<div>
    <label class="block text-sm font-medium text-gray-700">Kelas</label>
    <select name="class_room_id" class="mt-1 w-full rounded-lg border-gray-300">
        <option value="">Semua kelas</option>
        @foreach($classRooms as $classRoom)
            <option value="{{ $classRoom->id }}" @selected(old('class_room_id', $session?->class_room_id) == $classRoom->id)>
                {{ $classRoom->name ?? $classRoom->nama ?? 'Kelas #' . $classRoom->id }}
            </option>
        @endforeach
    </select>
</div>

<div class="grid grid-cols-2 gap-4 md:grid-cols-5">
    <div>
        <label class="block text-sm font-medium text-gray-700">Mulai Masuk</label>
        <input type="time" name="check_in_starts_at" value="{{ old('check_in_starts_at', $session?->check_in_starts_at) }}" class="mt-1 w-full rounded-lg border-gray-300">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Batas Terlambat</label>
        <input type="time" name="late_after_at" value="{{ old('late_after_at', $session?->late_after_at) }}" class="mt-1 w-full rounded-lg border-gray-300">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Akhir Masuk</label>
        <input type="time" name="check_in_ends_at" value="{{ old('check_in_ends_at', $session?->check_in_ends_at) }}" class="mt-1 w-full rounded-lg border-gray-300">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Mulai Pulang</label>
        <input type="time" name="check_out_starts_at" value="{{ old('check_out_starts_at', $session?->check_out_starts_at) }}" class="mt-1 w-full rounded-lg border-gray-300">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Akhir Pulang</label>
        <input type="time" name="check_out_ends_at" value="{{ old('check_out_ends_at', $session?->check_out_ends_at) }}" class="mt-1 w-full rounded-lg border-gray-300">
    </div>
</div>

<div>
    <label class="block text-sm font-medium text-gray-700">Status</label>
    <select name="status" class="mt-1 w-full rounded-lg border-gray-300">
        @foreach(['draft' => 'Draft', 'active' => 'Active', 'closed' => 'Closed'] as $value => $label)
            <option value="{{ $value }}" @selected(old('status', $session?->status ?? 'active') === $value)>
                {{ $label }}
            </option>
        @endforeach
    </select>
</div>

<div>
    <label class="block text-sm font-medium text-gray-700">Catatan</label>
    <textarea name="note" rows="3" class="mt-1 w-full rounded-lg border-gray-300">{{ old('note', $session?->note) }}</textarea>
</div>
