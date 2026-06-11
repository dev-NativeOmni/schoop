@extends('layouts.app')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Input Presensi Manual</h1>
    <p class="text-sm text-gray-600">Gunakan untuk izin, sakit, absen, atau koreksi data.</p>
</div>

@if(session('success'))
    <div class="mb-4 rounded-lg bg-green-50 px-4 py-3 text-green-700">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="mb-4 rounded-lg bg-red-50 px-4 py-3 text-red-700">
        <ul class="list-inside list-disc">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('attendance.manual.store') }}" method="POST" class="space-y-5 rounded-xl bg-white p-6 shadow">
    @csrf

    <div>
        <label class="block text-sm font-medium text-gray-700">Session</label>
        <select name="attendance_session_id" class="mt-1 w-full rounded-lg border-gray-300" required>
            @foreach($sessions as $session)
                <option value="{{ $session->id }}">
                    {{ $session->name }} — {{ $session->attendance_date?->format('d M Y') }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Santri</label>
        <select name="student_id" class="mt-1 w-full rounded-lg border-gray-300" required>
            @foreach($students as $student)
                <option value="{{ $student->id }}">
                    {{ $student->full_name ?? $student->nama_lengkap ?? $student->name ?? 'Santri #' . $student->id }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Status</label>
        <select name="status" class="mt-1 w-full rounded-lg border-gray-300" required>
            <option value="present">Hadir</option>
            <option value="late">Terlambat</option>
            <option value="sick">Sakit</option>
            <option value="permission">Izin</option>
            <option value="absent">Tidak Hadir</option>
        </select>
    </div>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
        <div>
            <label class="block text-sm font-medium text-gray-700">Check In</label>
            <input type="datetime-local" name="check_in_at" class="mt-1 w-full rounded-lg border-gray-300">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Check Out</label>
            <input type="datetime-local" name="check_out_at" class="mt-1 w-full rounded-lg border-gray-300">
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Catatan</label>
        <textarea name="note" rows="3" class="mt-1 w-full rounded-lg border-gray-300"></textarea>
    </div>

    <div class="text-right">
        <button class="rounded-lg bg-blue-600 px-4 py-2 text-white">
            Simpan
        </button>
    </div>
</form>
@endsection
