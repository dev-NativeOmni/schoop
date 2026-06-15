@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-slate-900 dark:text-slate-100">Custom Domain Mappings</h1>
            <p class="text-sm text-slate-500 mt-1">Kelola subdomain hafizplus.id atau domain khusus (FQDN) milik sekolah Anda.</p>
        </div>
        <a href="{{ route('white-label.domains.create', ['school_id' => $school->id]) }}" class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-slate-900 to-indigo-950 px-5 py-3 font-bold text-white shadow-md transition-all hover:scale-[1.02] active:scale-[0.98] text-sm">
            Tambah Domain Baru
        </a>
    </div>

    <!-- Mappings Table -->
    <div class="rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 dark:border-slate-800 text-xs font-bold text-slate-400 uppercase tracking-wider">
                        <th class="py-3 px-4">Domain / Subdomain</th>
                        <th class="py-3 px-4">Tipe</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4">Token Verifikasi (TXT)</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-850 text-sm">
                    @forelse($domains as $domain)
                        <tr class="hover:bg-slate-50/40 dark:hover:bg-slate-950/20 transition-colors">
                            <td class="py-4 px-4 font-bold text-slate-800 dark:text-slate-200">
                                {{ $domain->domain }}
                            </td>
                            <td class="py-4 px-4 text-xs font-semibold text-slate-500 capitalize">
                                {{ str_replace('_', ' ', $domain->type) }}
                            </td>
                            <td class="py-4 px-4">
                                <span class="inline-flex items-center rounded-lg px-2.5 py-0.5 text-xs font-bold 
                                    @if($domain->status === 'active') bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700
                                    @elseif($domain->status === 'verified') bg-blue-50 dark:bg-blue-950/20 text-blue-700
                                    @elseif($domain->status === 'pending') bg-amber-50 dark:bg-amber-950/20 text-amber-700
                                    @else bg-slate-100 dark:bg-slate-850 text-slate-500 @endif">
                                    {{ strtoupper($domain->status) }}
                                </span>
                            </td>
                            <td class="py-4 px-4">
                                @if($domain->status === 'pending')
                                    <code class="bg-slate-100 dark:bg-slate-950 p-1.5 rounded-lg text-xs font-mono text-slate-700 dark:text-slate-300">{{ $domain->verification_token }}</code>
                                @else
                                    <span class="text-xs text-slate-400">Verifikasi Selesai</span>
                                @endif
                            </td>
                            <td class="py-4 px-4 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <!-- Verify (Super Admin only) -->
                                    @if($domain->status === 'pending' && auth()->user()->isSuperAdmin())
                                        <form action="{{ route('white-label.domains.verify', ['domain' => $domain->id, 'school_id' => $school->id]) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 transition">
                                                Verifikasi Manual
                                            </button>
                                        </form>
                                    @endif

                                    <!-- Activate / Enable -->
                                    @if(($domain->status === 'verified' || $domain->status === 'disabled') && (auth()->user()->isSuperAdmin() || auth()->user()->hasRole(['admin', 'admin_sekolah'])))
                                        <form action="{{ route('white-label.domains.activate', ['domain' => $domain->id, 'school_id' => $school->id]) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="text-xs font-bold text-emerald-600 hover:text-emerald-800 transition">
                                                Aktifkan
                                            </button>
                                        </form>
                                    @endif

                                    <!-- Disable -->
                                    @if($domain->status === 'active' && (auth()->user()->isSuperAdmin() || auth()->user()->hasRole(['admin', 'admin_sekolah'])))
                                        <form action="{{ route('white-label.domains.disable', ['domain' => $domain->id, 'school_id' => $school->id]) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="text-xs font-bold text-amber-600 hover:text-amber-800 transition">
                                                Non-aktifkan
                                            </button>
                                        </form>
                                    @endif

                                    <a href="{{ route('white-label.domains.edit', ['domain' => $domain->id, 'school_id' => $school->id]) }}" class="text-xs font-bold text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-200 transition">
                                        Edit
                                    </a>

                                    <!-- Delete -->
                                    <form action="{{ route('white-label.domains.destroy', ['domain' => $domain->id, 'school_id' => $school->id]) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus mapping domain ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs font-bold text-red-600 hover:text-red-800 transition">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-400">Belum ada mapping domain yang dikonfigurasi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
