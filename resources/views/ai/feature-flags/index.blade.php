@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-slate-900 dark:text-slate-100">Konfigurasi Fitur AI</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Kelola visibilitas, filter, dan persetujuan guru untuk setiap modul rekomendasi AI.</p>
        </div>
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
                        <th class="py-3 px-4">Nama Fitur</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4">Review Guru</th>
                        <th class="py-3 px-4">Wali Murid</th>
                        <th class="py-3 px-4">Siswa</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-150 dark:divide-slate-800">
                    @forelse($flags as $flag)
                        <tr class="text-sm text-slate-700 dark:text-slate-350">
                            <td class="py-4 px-4 font-bold text-slate-800 dark:text-slate-200">
                                <div>{{ $flag->label }}</div>
                                <div class="text-[10px] text-slate-400 font-medium">Key: {{ $flag->feature_key }}</div>
                            </td>
                            <td class="py-4 px-4">
                                <span class="inline-flex items-center rounded-lg px-2.5 py-0.5 text-xs font-bold {{ $flag->is_enabled ? 'bg-emerald-50 dark:bg-emerald-950/20 text-emerald-600' : 'bg-slate-100 dark:bg-slate-850 text-slate-500' }}">
                                    {{ $flag->is_enabled ? 'Aktif' : 'Non-aktif' }}
                                </span>
                            </td>
                            <td class="py-4 px-4">
                                <span class="text-xs font-semibold {{ $flag->requires_teacher_review ? 'text-amber-600' : 'text-slate-400' }}">
                                    {{ $flag->requires_teacher_review ? 'Wajib Review' : 'Tanpa Review' }}
                                </span>
                            </td>
                            <td class="py-4 px-4 text-xs font-semibold {{ $flag->visible_to_parent ? 'text-indigo-600' : 'text-slate-400' }}">
                                {{ $flag->visible_to_parent ? 'Terlihat' : 'Tersembunyi' }}
                            </td>
                            <td class="py-4 px-4 text-xs font-semibold {{ $flag->visible_to_student ? 'text-indigo-600' : 'text-slate-400' }}">
                                {{ $flag->visible_to_student ? 'Terlihat' : 'Tersembunyi' }}
                            </td>
                            <td class="py-4 px-4 text-right">
                                <a href="{{ route('ai-learning.feature-flags.edit', $flag) }}" class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 dark:border-slate-800 px-3 py-1.5 font-bold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-950 transition text-xs">
                                    Edit
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400">Belum ada konfigurasi flag fitur AI sekolah.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
