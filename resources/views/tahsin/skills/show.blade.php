@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-6">
    <div class="bg-white rounded-xl shadow p-6">
        <h1 class="text-2xl font-bold text-gray-900">{{ $skill->name }}</h1>
        <p class="text-sm text-gray-600 mt-1">{{ $skill->description ?? '-' }}</p>

        <dl class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6 text-sm">
            <div>
                <dt class="text-gray-500">Kode</dt>
                <dd class="font-semibold">{{ $skill->code ?? '-' }}</dd>
            </div>
            <div>
                <dt class="text-gray-500">Level</dt>
                <dd class="font-semibold">{{ $skill->level?->name ?? '-' }}</dd>
            </div>
            <div>
                <dt class="text-gray-500">Maximum Score</dt>
                <dd class="font-semibold">{{ $skill->maximum_score }}</dd>
            </div>
        </dl>
    </div>
</div>
@endsection
