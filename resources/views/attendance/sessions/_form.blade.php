@php
    $session = $session ?? null;
@endphp

<div class="space-y-6">
    {{-- Session Name & Date --}}
    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
        <div class="space-y-1.5">
            <label class="block text-xs font-bold text-slate-500 dark:text-slate-455 uppercase tracking-wider">Nama Session</label>
            <input type="text"
                   name="name"
                   value="{{ old('name', $session?->name ?? 'Presensi Harian') }}"
                   class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-850 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500"
                   placeholder="Contoh: Presensi Harian Pagi"
                   required>
        </div>

        <div class="space-y-1.5">
            <label class="block text-xs font-bold text-slate-500 dark:text-slate-455 uppercase tracking-wider">Tanggal</label>
            <input type="date"
                   name="attendance_date"
                   value="{{ old('attendance_date', $session?->attendance_date?->toDateString() ?? now()->toDateString()) }}"
                   class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-850 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500"
                   required>
        </div>
    </div>

    {{-- Class Selection --}}
    <div class="space-y-1.5">
        <label class="block text-xs font-bold text-slate-500 dark:text-slate-455 uppercase tracking-wider">Filter Kelas</label>
        <select name="class_room_id" class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-850 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500">
            <option value="">Semua kelas (Santri)</option>
            @foreach($classRooms as $classRoom)
                <option value="{{ $classRoom->id }}" @selected(old('class_room_id', $session?->class_room_id) == $classRoom->id)>
                    {{ $classRoom->name ?? $classRoom->nama ?? 'Kelas #' . $classRoom->id }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Time limits Grid --}}
    <div class="grid grid-cols-2 gap-4 md:grid-cols-5">
        <div class="space-y-1.5">
            <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-455 uppercase tracking-wider">Mulai Masuk</label>
            <input type="time" name="check_in_starts_at" value="{{ old('check_in_starts_at', $session?->check_in_starts_at) }}" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-850 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500">
        </div>

        <div class="space-y-1.5">
            <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-455 uppercase tracking-wider">Batas Terlambat</label>
            <input type="time" name="late_after_at" value="{{ old('late_after_at', $session?->late_after_at) }}" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-850 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500">
        </div>

        <div class="space-y-1.5">
            <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-455 uppercase tracking-wider">Akhir Masuk</label>
            <input type="time" name="check_in_ends_at" value="{{ old('check_in_ends_at', $session?->check_in_ends_at) }}" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-850 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500">
        </div>

        <div class="space-y-1.5">
            <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-455 uppercase tracking-wider">Mulai Pulang</label>
            <input type="time" name="check_out_starts_at" value="{{ old('check_out_starts_at', $session?->check_out_starts_at) }}" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-850 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500">
        </div>

        <div class="space-y-1.5">
            <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-455 uppercase tracking-wider">Akhir Pulang</label>
            <input type="time" name="check_out_ends_at" value="{{ old('check_out_ends_at', $session?->check_out_ends_at) }}" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-850 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500">
        </div>
    </div>

    {{-- Status Select --}}
    <div class="space-y-1.5">
        <label class="block text-xs font-bold text-slate-500 dark:text-slate-455 uppercase tracking-wider">Status Session</label>
        <select name="status" class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-850 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500">
            @foreach(['draft' => 'Draft', 'active' => 'Active', 'closed' => 'Closed'] as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $session?->status ?? 'active') === $value)>
                    {{ $label }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Note --}}
    <div class="space-y-1.5">
        <label class="block text-xs font-bold text-slate-500 dark:text-slate-455 uppercase tracking-wider">Catatan</label>
        <textarea name="note" rows="3" class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-850 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 placeholder-slate-400" placeholder="Keterangan tambahan untuk session ini...">{{ old('note', $session?->note) }}</textarea>
    </div>
</div>
