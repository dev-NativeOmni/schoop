@extends('layouts.app')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between border-b border-slate-200/80 pb-5 dark:border-slate-800 gap-4">
        <div class="flex items-center space-x-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 dark:bg-indigo-950/30 dark:text-indigo-400">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h3m-3-3H8m4 2a2 2 0 100-4 2 2 0 000 4z" />
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white">QR Santri</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400">Kelola QR token presensi santri untuk scanner otomatis.</p>
            </div>
        </div>

        <a href="{{ route('attendance.qr-cards.print') }}"
           target="_blank"
           class="inline-flex items-center justify-center rounded-full bg-indigo-600 px-5 py-2.5 text-xs font-bold text-white hover:bg-indigo-700 shadow-sm shadow-indigo-500/10 hover:shadow-indigo-500/20 active:scale-[0.98] transition-all focus:outline-none focus:ring-4 focus:ring-indigo-500/20 gap-1.5">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
            </svg>
            Cetak QR Cards
        </a>
    </div>

    {{-- Notification --}}
    @if(session('success'))
        <div class="rounded-xl border border-emerald-100 bg-emerald-50/50 p-4 text-sm font-semibold text-emerald-700 dark:border-emerald-900/30 dark:bg-emerald-950/20 dark:text-emerald-450">
            {{ session('success') }}
        </div>
    @endif

    {{-- Main Container --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left text-sm text-slate-500 dark:text-slate-400">
                <thead class="bg-slate-50/75 text-xs font-bold uppercase tracking-wider text-slate-500 dark:bg-slate-850/50 dark:text-slate-400 border-b border-slate-200/85 dark:border-slate-800">
                    <tr>
                        <th scope="col" class="px-6 py-4">Santri</th>
                        <th scope="col" class="px-6 py-4">QR Aktif</th>
                        <th scope="col" class="px-6 py-4">Terakhir Dipakai</th>
                        <th scope="col" class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200/70 dark:divide-slate-800/80">
                    @forelse($students as $student)
                        <tr class="hover:bg-slate-50/40 dark:hover:bg-slate-800/20 transition-colors">
                            <td class="px-6 py-4 font-bold text-slate-850 dark:text-slate-200">
                                {{ $student->full_name ?? $student->nama_lengkap ?? $student->name ?? 'Santri #' . $student->id }}
                            </td>
                            <td class="px-6 py-4">
                                @if($student->attendanceQrToken)
                                    <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-semibold text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-400">
                                        Ya
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-semibold text-slate-600 dark:bg-slate-800 dark:text-slate-400">
                                        Belum
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-medium text-slate-600 dark:text-slate-350">
                                {{ $student->attendanceQrToken?->last_used_at?->format('d M Y H:i') ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <form action="{{ route('attendance.qr-cards.rotate', $student) }}"
                                      method="POST"
                                      onsubmit="return confirm('Ganti QR santri ini? QR lama akan tidak aktif.')"
                                      class="inline-block">
                                    @csrf
                                    <button class="inline-flex items-center justify-center rounded-full bg-rose-50 px-3.5 py-1.5 text-xs font-bold text-rose-600 hover:bg-rose-100 transition-all focus:outline-none active:scale-[0.98] dark:bg-rose-950/20 dark:text-rose-400 dark:hover:bg-rose-900/30">
                                        Rotate QR
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center font-medium text-slate-400 dark:text-slate-500">
                                Belum ada data santri.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    @if($students->hasPages())
        <div class="mt-4">
            {{ $students->links() }}
        </div>
    @endif

</div>
@endsection
