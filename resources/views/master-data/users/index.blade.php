@extends('layouts.app')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between border-b border-slate-200/80 pb-5 dark:border-slate-800 gap-4">
        <div class="flex items-center space-x-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-600 text-white shadow-md shadow-emerald-600/20">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>
            <div>
                <h2 class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white">Manajemen User Sistem</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400">Kelola semua akun terdaftar (Admin, Guru, Wali, Santri) lintas sekolah.</p>
            </div>
        </div>
        <div>
            <a href="{{ route('master-data.users.create') }}"
               class="inline-flex items-center space-x-2 rounded-full bg-gradient-to-r from-emerald-600 to-teal-700 px-5 py-2.5 text-sm font-bold text-[#1F2937] hover:scale-105 shadow-md shadow-emerald-600/15 hover:shadow-lg hover:shadow-emerald-600/20 transition-all focus:outline-none focus:ring-4 focus:ring-emerald-500/20 active:scale-[0.98]">
                <svg class="h-4 w-4 text-[#1F2937]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                <span>Tambah User Baru</span>
            </a>
        </div>
    </div>

    {{-- Filter Form --}}
    <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <form method="GET" action="{{ route('master-data.users.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div>
                <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Pencarian</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Cari nama, username, email..." 
                       class="w-full rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-850 px-4 py-2.5 text-sm text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/15 transition">
            </div>

            <div>
                <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Peran (Role)</label>
                <select name="role_id" 
                        class="w-full rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-850 px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/15 transition">
                    <option value="">Semua Peran</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}" @selected(request('role_id') == $role->id)>
                            {{ $role->label }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Sekolah</label>
                <select name="school_id" 
                        class="w-full rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-850 px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/15 transition">
                    <option value="">Semua Sekolah</option>
                    <option value="null" @selected(request('school_id') === 'null')>Global / Super Admin</option>
                    @foreach ($schools as $school)
                        <option value="{{ $school->id }}" @selected(request('school_id') == $school->id)>
                            {{ $school->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit" 
                        class="flex-1 rounded-xl bg-slate-900 dark:bg-slate-800 hover:bg-slate-850 dark:hover:bg-slate-750 px-4 py-2.5 text-sm font-bold text-white transition focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                    Filter
                </button>
                @if(request()->anyFilled(['search', 'role_id', 'school_id']))
                    <a href="{{ route('master-data.users.index') }}" 
                       class="rounded-xl border border-slate-200 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-850 px-4 py-2.5 text-sm font-bold text-slate-500 dark:text-slate-400 transition flex items-center justify-center">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Table Card --}}
    <div class="rounded-2xl border border-slate-200/80 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50/40 dark:bg-slate-850/40 border-b border-slate-200/80 dark:border-slate-800">
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Nama Lengkap</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Username</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Peran / Sekolah</th>
                        @if(auth()->user()?->isSuperAdmin())
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 w-44">Password</th>
                        @endif
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 text-center w-24">Status</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 text-center w-28">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse ($users as $user)
                        <tr class="hover:bg-slate-50/40 dark:hover:bg-slate-850/10 transition-all">
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-900 dark:text-white">{{ $user->name }}</div>
                                <div class="text-xs text-slate-400">{{ $user->email }}</div>
                            </td>
                            <td class="px-6 py-4 text-slate-700 dark:text-slate-350 font-semibold select-all">
                                {{ $user->username }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center rounded-lg bg-slate-100 dark:bg-slate-800 px-2 py-1 text-xs font-bold text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 mb-1">
                                    {{ $user->role?->label }}
                                </span>
                                <div class="text-xs text-slate-500 dark:text-slate-455 font-semibold">
                                    {{ $user->school?->name ?? 'Akses Global (Super Admin)' }}
                                </div>
                            </td>
                            @if(auth()->user()?->isSuperAdmin())
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-2">
                                        <span id="pwd-text-{{ $user->id }}" class="font-mono text-sm text-slate-700 dark:text-slate-350" style="display: none;">
                                            {{ $user->decrypted_password ?? '-' }}
                                        </span>
                                        <span id="pwd-masked-{{ $user->id }}" class="font-mono text-sm text-slate-400">
                                            ••••••••
                                        </span>
                                        <button type="button" onclick="togglePlaintextPassword({{ $user->id }})" class="text-slate-400 hover:text-slate-650 dark:hover:text-slate-200 transition-colors">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path id="pwd-icon-{{ $user->id }}" stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            @endif
                            <td class="px-6 py-4 text-center">
                                @if($user->is_active)
                                    <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-0.5 text-[10px] font-bold text-emerald-700 border border-emerald-150 dark:bg-emerald-950/20 dark:text-emerald-400 dark:border-emerald-900/50">Aktif</span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-slate-50 px-2.5 py-0.5 text-[10px] font-bold text-slate-500 border border-slate-150 dark:bg-slate-800/40 dark:text-slate-450 dark:border-slate-750">Nonaktif</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="inline-flex items-center space-x-1.5">
                                    {{-- Edit --}}
                                    <a href="{{ route('master-data.users.edit', $user) }}" 
                                       class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 text-emerald-500 hover:bg-slate-50/10 dark:border-slate-800 dark:hover:bg-slate-800 transition-all" 
                                       title="Edit">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </a>

                                    @if(auth()->id() !== $user->id)
                                        {{-- Hapus --}}
                                        <form method="POST" action="{{ route('master-data.users.destroy', $user) }}"
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini? Ini juga akan menghapus profile terkait jika terhubung.')">
                                            @csrf 
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 text-rose-500 hover:bg-rose-50/10 dark:border-slate-800 dark:hover:bg-rose-950/20 transition-all" 
                                                    title="Hapus">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-sm text-slate-450 dark:text-slate-500">Belum ada data user terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    @if($users->hasPages())
        <div class="pt-4">
            {{ $users->links() }}
        </div>
    @endif

    @if(auth()->user()?->isSuperAdmin())
        <script>
            function togglePlaintextPassword(userId) {
                const textEl = document.getElementById(`pwd-text-${userId}`);
                const maskedEl = document.getElementById(`pwd-masked-${userId}`);
                const pathEl = document.getElementById(`pwd-icon-${userId}`);
                
                if (textEl.style.display === 'none') {
                    textEl.style.display = 'inline';
                    maskedEl.style.display = 'none';
                    pathEl.setAttribute('d', 'M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21');
                } else {
                    textEl.style.display = 'none';
                    maskedEl.style.display = 'inline';
                    pathEl.setAttribute('d', 'M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z');
                }
            }
        </script>
    @endif

</div>

@endsection
