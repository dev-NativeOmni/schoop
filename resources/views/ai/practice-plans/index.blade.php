@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-slate-900 dark:text-slate-100">Practice Plans</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Daftar rencana latihan mandiri Qur’an terstruktur bagi siswa.</p>
        </div>
        <a href="{{ route('ai-learning.practice-plans.create') }}" class="inline-flex items-center gap-2 rounded-2xl bg-indigo-600 px-4 py-2.5 font-bold text-white shadow hover:bg-indigo-700 transition text-sm">
            Buat Rencana Latihan Baru
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-450 border border-emerald-100 dark:border-emerald-950 text-sm font-semibold">
            {{ session('success') }}
        </div>
    @endif

    <div class="rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-150 dark:border-slate-800 text-xs font-bold text-slate-400 uppercase">
                        <th class="py-3 px-4">Nama Siswa</th>
                        <th class="py-3 px-4">Judul Rencana</th>
                        <th class="py-3 px-4">Periode Tanggal</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4">Tipe Generator</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-150 dark:divide-slate-800">
                    @forelse($practicePlans as $plan)
                        <tr class="text-sm text-slate-700 dark:text-slate-350">
                            <td class="py-4 px-4 font-bold text-slate-800 dark:text-slate-200">
                                {{ $plan->student->user?->name ?? 'Ananda' }}
                            </td>
                            <td class="py-4 px-4 font-semibold text-slate-750 dark:text-slate-250">
                                {{ $plan->title }}
                            </td>
                            <td class="py-4 px-4 text-xs font-medium text-slate-500">
                                {{ $plan->start_date?->translatedFormat('d M Y') }} s/d {{ $plan->end_date?->translatedFormat('d M Y') }}
                            </td>
                            <td class="py-4 px-4">
                                <span class="inline-flex items-center rounded-lg px-2.5 py-0.5 text-xs font-bold 
                                    @if($plan->status === 'published') bg-emerald-50 dark:bg-emerald-950/20 text-emerald-600
                                    @elseif($plan->status === 'completed') bg-blue-50 dark:bg-blue-950/20 text-blue-600
                                    @else bg-slate-100 dark:bg-slate-850 text-slate-500
                                    @endif">
                                    {{ strtoupper($plan->status) }}
                                </span>
                            </td>
                            <td class="py-4 px-4 text-xs font-bold uppercase text-slate-400">
                                {{ $plan->generated_by_type }}
                            </td>
                            <td class="py-4 px-4 text-right">
                                <a href="{{ route('ai-learning.practice-plans.show', $plan) }}" class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 dark:border-slate-800 px-3 py-1.5 font-bold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-950 transition text-xs">
                                    Lihat Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400">Belum ada rencana latihan mandiri Qur'an yang terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $practicePlans->links() }}
        </div>
    </div>
</div>
@endsection
