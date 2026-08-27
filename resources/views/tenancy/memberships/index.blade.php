@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header section -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-slate-900">User Memberships</h1>
            <p class="text-sm text-slate-500 mt-1">Daftar staf, guru, dan admin yang memiliki akses ke sekolah ini.</p>
        </div>
        <div>
            <a href="{{ route('tenancy.memberships.create') }}" class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-slate-900 to-indigo-950 px-5 py-3 font-bold text-white shadow-md transition-all hover:scale-[1.02] active:scale-[0.98]">
                <svg class="h-5 w-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Anggota
            </a>
        </div>
    </div>

    <!-- Memberships Table Card -->
    <div class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-200 text-xs font-bold text-slate-500 uppercase tracking-wider">
                        <th class="px-6 py-4">User</th>
                        <th class="px-6 py-4">Role</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Default</th>
                        <th class="px-6 py-4">Bergabung</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($memberships as $membership)
                        <tr class="hover:bg-slate-50/30 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-9 w-9 items-center justify-center rounded-full bg-indigo-50 text-indigo-700 font-bold text-sm">
                                        {{ strtoupper(substr($membership->user->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <span class="block font-bold text-slate-800">{{ $membership->user->name }}</span>
                                        <span class="block text-xs text-slate-400 mt-0.5">{{ $membership->user->email }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center rounded-md bg-indigo-50 px-2 py-1 text-xs font-semibold text-indigo-700">
                                    {{ $membership->role?->name ?? 'None' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($membership->membership_status === 'active')
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-800">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-600"></span>
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600">
                                        <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                                        Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($membership->is_default)
                                    <span class="inline-flex items-center gap-1 text-xs font-bold text-slate-700">
                                        <svg class="h-4 w-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                                        Ya
                                    </span>
                                @else
                                    <span class="text-xs text-slate-400">Tidak</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-slate-500">
                                {{ $membership->joined_at ? $membership->joined_at->format('d M Y') : '-' }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('tenancy.memberships.edit', $membership) }}" class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-bold text-slate-700 hover:bg-slate-50 transition-colors">
                                        Ubah
                                    </a>
                                    <form action="{{ route('tenancy.memberships.destroy', $membership) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus membership ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-lg border border-red-200 bg-red-50 px-3 py-1.5 text-xs font-bold text-red-700 hover:bg-red-100 transition-colors">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                                <svg class="mx-auto h-12 w-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                                <p class="mt-4 text-sm font-bold">Belum ada anggota terdaftar</p>
                                <p class="text-xs text-slate-400 mt-1">Silakan tambahkan user untuk menghubungkannya ke sekolah ini.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($memberships->hasPages())
            <div class="border-t border-slate-100 px-6 py-4">
                {{ $memberships->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
