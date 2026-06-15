@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between border-b border-slate-200 pb-4 dark:border-slate-800">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Catatan Kesehatan Santri</h1>
            <p class="text-sm text-slate-500">Kelola riwayat pemeriksaan kesehatan, sakit, atau rujukan UKS santri.</p>
        </div>
        <a href="{{ route('boarding.health-logs.create') }}" class="rounded-xl bg-gradient-to-r from-emerald-500 to-teal-600 px-4 py-2 text-sm font-semibold text-white shadow-md shadow-emerald-200 hover:opacity-90 transition dark:shadow-none">
            + Tambah Log Kesehatan
        </a>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
        <div class="rounded-xl bg-emerald-50 p-4 text-sm text-emerald-800 dark:bg-emerald-950/30 dark:text-emerald-400">
            {{ session('success') }}
        </div>
    @endif

    <!-- Filter Form -->
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <form action="{{ route('boarding.health-logs.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
            <!-- Search Text -->
            <div>
                <label for="q" class="block text-xs font-bold uppercase text-slate-400 dark:text-slate-500 mb-1">Cari Santri</label>
                <input type="text" name="q" id="q" value="{{ request('q') }}" placeholder="Nama / NIS..." class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-800 dark:bg-slate-950 dark:text-white">
            </div>

            <!-- Severity Filter -->
            <div>
                <label for="severity" class="block text-xs font-bold uppercase text-slate-400 dark:text-slate-500 mb-1">Tingkat Keparahan</label>
                <select name="severity" id="severity" class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-800 dark:bg-slate-950 dark:text-white">
                    <option value="">Semua Tingkat</option>
                    <option value="low" {{ request('severity') === 'low' ? 'selected' : '' }}>Ringan (Low)</option>
                    <option value="medium" {{ request('severity') === 'medium' ? 'selected' : '' }}>Sedang (Medium)</option>
                    <option value="high" {{ request('severity') === 'high' ? 'selected' : '' }}>Parah (High)</option>
                    <option value="critical" {{ request('severity') === 'critical' ? 'selected' : '' }}>Gawat (Critical)</option>
                </select>
            </div>

            <!-- Buttons -->
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 rounded-xl bg-slate-800 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700 transition dark:bg-slate-750 dark:hover:bg-slate-700">Filter</button>
                @if(request()->anyFilled(['q', 'severity']))
                    <a href="{{ route('boarding.health-logs.index') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-350 dark:hover:bg-slate-950">Reset</a>
                @endif
            </div>
        </form>
    </div>

    <!-- Table List -->
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 text-xs font-bold uppercase text-slate-400 dark:border-slate-800">
                        <th class="py-3 px-4">Nama Santri</th>
                        <th class="py-3 px-4">Kondisi / Keluhan</th>
                        <th class="py-3 px-4">Tingkat</th>
                        <th class="py-3 px-4">Tindakan</th>
                        <th class="py-3 px-4">Tanggal Dicatat</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 text-sm text-slate-700 dark:divide-slate-800 dark:text-slate-350">
                    @forelse($healthLogs as $log)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-950/20">
                            <td class="py-3.5 px-4 font-semibold text-slate-800 dark:text-white">
                                <a href="{{ route('boarding.health-logs.show', $log->id) }}" class="hover:text-emerald-500 transition">
                                    {{ $log->student->full_name }}
                                </a>
                                <span class="block text-xs font-normal text-slate-400 mt-0.5">NIS: {{ $log->student->student_number ?? '-' }}</span>
                            </td>
                            <td class="py-3.5 px-4 font-semibold text-slate-700 dark:text-slate-300">
                                {{ $log->condition_title }}
                                @if($log->description)
                                    <span class="block text-xs font-normal text-slate-400 mt-0.5">{{ Str::limit($log->description, 50) }}</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-semibold uppercase
                                    @if($log->severity === 'critical') bg-rose-50 text-rose-700 dark:bg-rose-950/30 dark:text-rose-455
                                    @elseif($log->severity === 'high') bg-orange-50 text-orange-700 dark:bg-orange-950/30 dark:text-orange-455
                                    @elseif($log->severity === 'medium') bg-amber-50 text-amber-700 dark:bg-amber-950/30 dark:text-amber-455
                                    @else bg-emerald-50 text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-455 @endif">
                                    @if($log->severity === 'critical') Gawat
                                    @elseif($log->severity === 'high') Parah
                                    @elseif($log->severity === 'medium') Sedang
                                    @else Ringan
                                    @endif
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-slate-600 dark:text-slate-450">
                                {{ Str::limit($log->action_taken, 40) ?? '-' }}
                            </td>
                            <td class="py-3.5 px-4 font-medium text-slate-500 dark:text-slate-450">
                                {{ \Carbon\Carbon::parse($log->logged_at)->format('d M Y H:i') }}
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <a href="{{ route('boarding.health-logs.show', $log->id) }}" class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-bold hover:bg-slate-50 dark:border-slate-800 dark:hover:bg-slate-950">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400">Belum ada catatan kesehatan yang ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $healthLogs->links() }}
        </div>
    </div>
</div>
@endsection
