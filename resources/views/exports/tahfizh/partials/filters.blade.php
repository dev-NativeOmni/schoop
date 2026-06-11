<div>
    <label class="mb-1 block text-sm font-semibold">Kelas</label>
    <select name="class_room_id" class="w-full rounded-lg border border-slate-300 px-3 py-2">
        <option value="">Semua Kelas</option>
        @foreach ($classRooms as $classRoom)
            <option value="{{ $classRoom->id }}">{{ $classRoom->name }}</option>
        @endforeach
    </select>
</div>

<div>
    <label class="mb-1 block text-sm font-semibold">Santri</label>
    <select name="student_id" class="w-full rounded-lg border border-slate-300 px-3 py-2">
        <option value="">Semua Santri</option>
        @foreach ($students as $student)
            <option value="{{ $student->id }}">{{ $student->full_name }}</option>
        @endforeach
    </select>
</div>

<div>
    <label class="mb-1 block text-sm font-semibold">Guru</label>
    <select name="teacher_id" class="w-full rounded-lg border border-slate-300 px-3 py-2">
        <option value="">Semua Guru</option>
        @foreach ($teachers as $teacher)
            <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
        @endforeach
    </select>
</div>

<div>
    <label class="mb-1 block text-sm font-semibold">Status</label>
    <select name="status" class="w-full rounded-lg border border-slate-300 px-3 py-2">
        <option value="">Semua Status</option>
        @foreach ($statuses as $status)
            <option value="{{ $status }}">{{ str_replace('_', ' ', strtoupper($status)) }}</option>
        @endforeach
    </select>
</div>
