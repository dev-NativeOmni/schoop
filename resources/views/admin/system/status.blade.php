@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h2 class="text-2xl font-bold">System Status</h2>
        <p class="text-sm text-slate-500">
            Ringkasan kesiapan production Schoop Platform.
        </p>
    </div>

    <div class="mb-6 rounded-2xl p-5 shadow-sm {{ $isHealthy ? 'bg-green-50' : 'bg-red-50' }}">
        <div class="text-sm font-semibold {{ $isHealthy ? 'text-green-700' : 'text-red-700' }}">
            Status Sistem
        </div>
        <div class="mt-2 text-2xl font-bold {{ $isHealthy ? 'text-green-900' : 'text-red-900' }}">
            {{ $isHealthy ? 'HEALTHY' : 'NEEDS ATTENTION' }}
        </div>
    </div>

    <div class="grid gap-6 md:grid-cols-2">
        @foreach ($checks as $name => $result)
            <div class="rounded-2xl bg-white p-6 shadow-sm">
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="text-lg font-bold">{{ strtoupper($name) }}</h3>

                    @if ($result['ok'] ?? false)
                        <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-bold text-green-700">
                            OK
                        </span>
                    @else
                        <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-bold text-red-700">
                            FAILED
                        </span>
                    @endif
                </div>

                <dl class="space-y-2 text-sm">
                    @foreach ($result as $key => $value)
                        @continue($key === 'ok')

                        <div>
                            <dt class="font-semibold text-slate-600">{{ str_replace('_', ' ', $key) }}</dt>
                            <dd class="text-slate-800">
                                @if (is_array($value))
                                    {{ json_encode($value, JSON_UNESCAPED_UNICODE) }}
                                @elseif (is_bool($value))
                                    {{ $value ? 'true' : 'false' }}
                                @else
                                    {{ $value ?? '-' }}
                                @endif
                            </dd>
                        </div>
                    @endforeach
                </dl>
            </div>
        @endforeach
    </div>

    <div class="mt-8 rounded-2xl bg-white p-6 shadow-sm">
        <h3 class="mb-4 text-lg font-bold">Database Backups</h3>

        <div class="overflow-hidden rounded-xl border border-slate-200">
            <table class="w-full border-collapse text-left text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3">File</th>
                        <th class="px-4 py-3">Size</th>
                        <th class="px-4 py-3">Last Modified</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($backups as $backup)
                        <tr class="border-t">
                            <td class="px-4 py-3 font-semibold">{{ $backup['name'] }}</td>
                            <td class="px-4 py-3">{{ number_format($backup['size_bytes']) }} bytes</td>
                            <td class="px-4 py-3">{{ $backup['last_modified'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-4 py-6 text-center text-slate-500">
                                Belum ada backup database.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <p class="mt-4 text-sm text-slate-500">
            Backup manual dijalankan lewat command: php artisan app:backup-database
        </p>
    </div>
@endsection
