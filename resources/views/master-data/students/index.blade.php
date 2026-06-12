@extends('layouts.app')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between border-b border-slate-200/80 pb-5 dark:border-slate-800 gap-4">
        <div class="flex items-center space-x-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 dark:bg-indigo-950/30 dark:text-indigo-400">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                </svg>
            </div>
            <div>
                <h2 class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white">Data Santri</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400">Kelola data santri/siswa sekolah.</p>
            </div>
        </div>
        <div>
            <a href="{{ route('master-data.students.create') }}"
               class="inline-flex items-center space-x-2 rounded-full bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700 shadow-sm transition-all focus:outline-none focus:ring-4 focus:ring-indigo-500/20 active:scale-[0.98]">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                <span>Tambah Santri</span>
            </a>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="rounded-2xl border border-slate-200/80 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50/40 dark:bg-slate-850/40 border-b border-slate-200/80 dark:border-slate-800">
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Nama Santri</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">No. Induk</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Kelas</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Program</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Wali Murid</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 text-center w-28">Status</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 text-center w-36">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse ($students as $student)
                        <tr class="hover:bg-slate-50/40 dark:hover:bg-slate-850/10 transition-all">
                            <td class="px-6 py-4 font-bold text-slate-800 dark:text-slate-200">
                                {{ $student->full_name }}
                            </td>
                            <td class="px-6 py-4 text-slate-600 dark:text-slate-400 font-medium">
                                {{ $student->student_number ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-slate-700 dark:text-slate-350 font-semibold">
                                {{ $student->classRoom?->name ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-slate-500 dark:text-slate-400">
                                @if($student->program_type)
                                    <span class="inline-flex items-center rounded-md bg-blue-50 px-2.5 py-0.5 text-xs font-semibold text-blue-700 border border-blue-150/40 dark:bg-blue-950/20 dark:text-blue-450 dark:border-blue-900/50">
                                        {{ $student->program_type }}
                                    </span>
                                @else
                                    <span class="text-slate-400 text-xs">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-slate-500 dark:text-slate-400">
                                <div class="flex flex-wrap gap-1.5">
                                    @forelse ($student->parents as $parent)
                                        <span class="inline-flex items-center rounded-md bg-indigo-50 px-2 py-0.5 text-xs font-semibold text-indigo-700 border border-indigo-150/40 dark:bg-indigo-950/20 dark:text-indigo-400 dark:border-indigo-900/50">
                                            {{ $parent->user?->name }} <span class="text-[10px] text-indigo-500 dark:text-indigo-450 ml-1">({{ $parent->relationship ?? 'Wali' }})</span>
                                        </span>
                                    @empty
                                        <span class="text-slate-400 text-xs font-medium italic">-</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($student->is_active)
                                    <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-0.5 text-[10px] font-bold text-emerald-700 border border-emerald-150 dark:bg-emerald-950/20 dark:text-emerald-400 dark:border-emerald-900/50">Aktif</span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-slate-50 px-2.5 py-0.5 text-[10px] font-bold text-slate-500 border border-slate-150 dark:bg-slate-800/40 dark:text-slate-450 dark:border-slate-750">Nonaktif</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="inline-flex items-center space-x-1.5">
                                    {{-- Detail --}}
                                    <a href="{{ route('master-data.students.show', $student) }}" 
                                       class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 text-slate-500 hover:bg-slate-50 dark:border-slate-800 dark:text-slate-400 dark:hover:bg-slate-800 transition-all" 
                                       title="Detail">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>

                                    {{-- Edit --}}
                                    <a href="{{ route('master-data.students.edit', $student) }}" 
                                       class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 text-indigo-600 hover:bg-indigo-50/30 dark:border-slate-800 dark:text-indigo-400 dark:hover:bg-indigo-950/20 transition-all" 
                                       title="Edit">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </a>

                                    {{-- Hapus --}}
                                    <form method="POST" action="{{ route('master-data.students.destroy', $student) }}"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus data santri ini?')">
                                        @csrf 
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 text-rose-600 hover:bg-rose-50/30 dark:border-slate-800 dark:text-rose-450 dark:hover:bg-rose-950/20 transition-all" 
                                                title="Hapus">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-sm text-slate-450 dark:text-slate-500">Belum ada data santri terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    @if($students->hasPages())
        <div class="pt-4">
            {{ $students->links() }}
        </div>
    @endif

</div>
@endsection

