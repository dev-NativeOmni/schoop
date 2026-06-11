@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Edit Skill Tahsin</h1>

    @if($errors->any())
        <div class="mb-4 rounded-lg bg-red-50 px-4 py-3 text-red-700">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('tahsin.skills.update', $skill) }}" method="POST" class="bg-white rounded-xl shadow p-6 space-y-5">
        @csrf
        @method('PUT')

        @include('tahsin.skills._form', [
            'skill' => $skill,
            'levels' => $levels,
        ])

        <div class="flex justify-end gap-3">
            <a href="{{ route('tahsin.skills.index') }}" class="px-4 py-2 border rounded-lg">Batal</a>
            <button class="px-4 py-2 bg-blue-600 text-white rounded-lg">Simpan</button>
        </div>
    </form>
</div>
@endsection
