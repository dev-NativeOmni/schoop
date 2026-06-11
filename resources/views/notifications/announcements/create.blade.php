@extends('layouts.app')

@section('content')
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold">Kirim Pengumuman</h2>
            <p class="text-sm text-slate-500">
                Pengumuman akan masuk ke Notification Center penerima.
            </p>
        </div>

        <a href="{{ route('notifications.index') }}"
           class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
            Kembali
        </a>
    </div>

    @if ($errors->any())
        <div class="mb-4 rounded-lg bg-red-50 p-4 text-sm text-red-700">
            <div class="font-bold">Validasi gagal:</div>
            <ul class="mt-2 list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('notifications.announcements.store') }}"
          class="rounded-2xl bg-white p-6 shadow-sm border border-slate-100">
        @csrf

        <div class="grid gap-5">
            <div>
                <label class="mb-1 block text-sm font-semibold">Judul</label>
                <input type="text" name="title" value="{{ old('title') }}"
                       class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                       required>
            </div>

            <div>
                <label class="mb-1 block text-sm font-semibold">Isi Pengumuman</label>
                <textarea name="body" rows="6"
                          class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                          required>{{ old('body') }}</textarea>
            </div>

            <div>
                <label class="mb-1 block text-sm font-semibold">Jenis</label>
                <select name="type" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" required>
                    <option value="info" @selected(old('type') === 'info')>Info</option>
                    <option value="success" @selected(old('type') === 'success')>Success</option>
                    <option value="warning" @selected(old('type') === 'warning')>Warning</option>
                    <option value="danger" @selected(old('type') === 'danger')>Danger</option>
                </select>
            </div>

            <div>
                <label class="mb-1 block text-sm font-semibold">Jenis Penerima</label>
                <select name="recipient_type" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" required>
                    <option value="all" @selected(old('recipient_type') === 'all')>Semua User Aktif</option>
                    <option value="role" @selected(old('recipient_type') === 'role')>Berdasarkan Role</option>
                    <option value="class_room_parents" @selected(old('recipient_type') === 'class_room_parents')>Orang Tua per Kelas</option>
                    <option value="student_parents" @selected(old('recipient_type') === 'student_parents')>Orang Tua Santri Tertentu</option>
                    <option value="specific_users" @selected(old('recipient_type') === 'specific_users')>User Tertentu</option>
                </select>

                <p class="mt-1 text-xs text-slate-500">
                    Isi field terkait sesuai jenis penerima yang dipilih.
                </p>
            </div>

            <div>
                <label class="mb-1 block text-sm font-semibold">Role Penerima</label>
                <select name="roles[]" multiple class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                    @foreach ($roles as $role)
                        <option value="{{ $role->name }}">
                            {{ $role->label }} / {{ $role->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="mb-1 block text-sm font-semibold">Kelas untuk Orang Tua</label>
                <select name="class_room_id" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                    <option value="">- Tidak dipilih -</option>
                    @foreach ($classRooms as $classRoom)
                        <option value="{{ $classRoom->id }}" @selected(old('class_room_id') == $classRoom->id)>
                            {{ $classRoom->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="mb-1 block text-sm font-semibold">Santri untuk Orang Tua</label>
                <select name="student_id" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                    <option value="">- Tidak dipilih -</option>
                    @foreach ($students as $student)
                        <option value="{{ $student->id }}" @selected(old('student_id') == $student->id)>
                            {{ $student->full_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="mb-1 block text-sm font-semibold">User Tertentu</label>
                <select name="user_ids[]" multiple class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">
                            {{ $user->name }} — {{ $user->email }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="mb-1 block text-sm font-semibold">Action URL</label>
                <input type="text" name="action_url" value="{{ old('action_url') }}"
                       placeholder="/portal/parent/dashboard"
                       class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
            </div>

            <div class="flex justify-end">
                <button type="submit"
                        class="rounded-lg bg-slate-900 px-5 py-2 text-sm font-semibold text-white hover:bg-slate-700">
                    Kirim Pengumuman
                </button>
            </div>
        </div>
    </form>
@endsection
