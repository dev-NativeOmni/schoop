@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-6">
    <div class="bg-white rounded-xl shadow p-6">
        <h1 class="text-2xl font-bold text-gray-900">{{ $level->name }}</h1>
        <p class="text-sm text-gray-600 mt-1">{{ $level->description ?? '-' }}</p>

        <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <div class="text-sm text-gray-500">Minimum Score</div>
                <div class="font-semibold">{{ $level->minimum_score }}</div>
            </div>

            <div>
                <div class="text-sm text-gray-500">Status</div>
                <div class="font-semibold">{{ $level->is_active ? 'Aktif' : 'Nonaktif' }}</div>
            </div>

            <div>
                <div class="text-sm text-gray-500">Jumlah Skill</div>
                <div class="font-semibold">{{ $level->skills->count() }}</div>
            </div>
        </div>
    </div>

    <div class="mt-6 bg-white rounded-xl shadow overflow-hidden">
        <div class="px-4 py-3 bg-gray-50 font-semibold">Skill pada level ini</div>
        <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <tbody class="divide-y">
                @forelse($level->skills as $skill)
                    <tr>
                        <td class="px-4 py-3">{{ $skill->name }}</td>
                        <td class="px-4 py-3">{{ $skill->code }}</td>
                    </tr>
                @empty
                    <tr>
                        <td class="px-4 py-6 text-center text-gray-500">Belum ada skill.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>
</div>
@endsection
