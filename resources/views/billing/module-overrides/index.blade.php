@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">Manual Module Overrides</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">Atur manual pengecualian akses modul per sekolah (add-on, custom enterprise, dsb).</p>
        </div>
        <div>
            <a href="{{ route('billing.module-overrides.create') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl transition duration-150 shadow-md">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                Tambah Manual Override
            </a>
        </div>
    </div>

    <!-- Table Card -->
    <div class="bg-white dark:bg-slate-900 shadow-xl rounded-2xl overflow-hidden border border-slate-100 dark:border-slate-800">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-850 border-b border-slate-100 dark:border-slate-800">
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Sekolah</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Modul Sistem</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Status Override</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Alasan</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Kadaluarsa</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($overrides as $override)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-850/30 transition duration-150">
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-900 dark:text-white">{{ $override->school?->name }}</div>
                                <div class="text-xs text-slate-400">ID: {{ $override->school_id }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-semibold text-slate-700 dark:text-slate-350">{{ $override->module?->name }}</span>
                                <code class="text-[10px] text-slate-400 font-semibold block">{{ $override->module?->module_key }}</code>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold {{ $override->is_enabled ? 'bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-300' : 'bg-rose-50 dark:bg-rose-950/20 text-rose-700 dark:text-rose-300' }}">
                                    {{ $override->is_enabled ? 'ENABLED (Aktif)' : 'DISABLED (Kunci)' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-500 dark:text-slate-400">
                                {{ $override->reason ?? '-' }}
                                <span class="block text-[10px] text-slate-400">Oleh: {{ $override->creator?->name ?? 'System' }}</span>
                            </td>
                            <td class="px-6 py-4 text-slate-500 dark:text-slate-400">
                                @if($override->expires_at)
                                    @if($override->expires_at->isPast())
                                        <span class="text-rose-500 font-bold block">Expired: {{ $override->expires_at->format('d M Y H:i') }}</span>
                                    @else
                                        <span class="text-emerald-500 font-semibold block">Aktif s.d: {{ $override->expires_at->format('d M Y H:i') }}</span>
                                    @endif
                                @else
                                    <span class="text-slate-400 block italic">Selamanya</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('billing.module-overrides.edit', $override->id) }}" class="p-1 text-slate-400 hover:text-amber-600 dark:hover:text-amber-400 transition" title="Edit">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                    </a>
                                    <form action="{{ route('billing.module-overrides.destroy', $override->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus manual override ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1 text-slate-400 hover:text-rose-600 transition" title="Hapus">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-slate-400">
                                Belum ada manual override modul untuk sekolah mana pun. Klik "Tambah Manual Override" untuk membuat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($overrides->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-850/20">
                {{ $overrides->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
