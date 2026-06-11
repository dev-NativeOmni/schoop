@extends('layouts.app')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Edit Session Presensi</h1>
    <p class="text-sm text-gray-600">{{ $session->name }}</p>
</div>

@if($errors->any())
    <div class="mb-4 rounded-lg bg-red-50 px-4 py-3 text-red-700">
        <ul class="list-inside list-disc">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('attendance.sessions.update', $session) }}" method="POST" class="space-y-5 rounded-xl bg-white p-6 shadow">
    @csrf
    @method('PUT')

    @include('attendance.sessions._form', [
        'session' => $session,
        'classRooms' => $classRooms,
    ])

    <div class="flex items-center justify-end gap-3">
        <a href="{{ route('attendance.sessions.index') }}" class="rounded-lg border px-4 py-2">
            Batal
        </a>
        <button class="rounded-lg bg-blue-600 px-4 py-2 text-white">
            Simpan Perubahan
        </button>
    </div>
</form>
@endsection
