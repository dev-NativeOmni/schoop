@extends('layouts.app')

@section('content')
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold">{{ $data['title'] ?? 'Detail Notifikasi' }}</h2>
            <p class="text-sm text-slate-500">
                {{ $notification->created_at?->format('d/m/Y H:i') }}
            </p>
        </div>

        <a href="{{ route('notifications.index') }}"
           class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
            Kembali
        </a>
    </div>

    <div class="rounded-2xl bg-white p-6 shadow-sm border border-slate-100">
        <div class="mb-4 flex flex-wrap gap-2">
            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">
                {{ str_replace('_', ' ', $data['category'] ?? '-') }}
            </span>

            <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">
                {{ strtoupper($data['type'] ?? 'info') }}
            </span>
        </div>

        <div class="prose max-w-none">
            <p class="whitespace-pre-line text-slate-700">
                {{ $data['body'] ?? '-' }}
            </p>
        </div>

        @if (! empty($data['action_url']))
            <div class="mt-6">
                <a href="{{ $data['action_url'] }}"
                   class="inline-block rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">
                    Buka Tautan
                </a>
            </div>
        @endif

        <div class="mt-8 rounded-xl bg-slate-50 p-4 border border-slate-100">
            <h3 class="mb-3 text-sm font-bold text-slate-900">Data Notifikasi</h3>

            <dl class="grid gap-3 text-sm md:grid-cols-2">
                @foreach ($data as $key => $value)
                    <div>
                        <dt class="font-semibold text-slate-500 text-xs uppercase">{{ str_replace('_', ' ', $key) }}</dt>
                        <dd class="text-slate-800 font-medium">
                            @if (is_array($value))
                                {{ json_encode($value) }}
                            @else
                                {{ $value ?? '-' }}
                            @endif
                        </dd>
                    </div>
                @endforeach
            </dl>
        </div>
    </div>
@endsection
