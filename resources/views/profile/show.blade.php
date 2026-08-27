@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="relative overflow-hidden bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border border-slate-200/50 dark:border-slate-800/50 rounded-3xl p-8 shadow-xl">
        <!-- Ambient Glow -->
        <div class="absolute -right-20 -top-20 h-40 w-40 rounded-full bg-indigo-500/10 dark:bg-indigo-500/5 blur-3xl"></div>
        <div class="absolute -left-20 -bottom-20 h-40 w-40 rounded-full bg-emerald-500/10 dark:bg-emerald-500/5 blur-3xl"></div>

        <div class="relative z-10 space-y-6">
            <!-- User Header Info -->
            <div class="flex flex-col items-center text-center space-y-4 md:flex-row md:text-left md:space-y-0 md:space-x-6">
                <div class="shrink-0">
                    <img src="{{ $user->profile_picture_url }}"
                         alt="Avatar"
                         class="w-24 h-24 rounded-full border-4 border-slate-200 dark:border-slate-700 shadow-md object-cover" />
                </div>
                <div class="flex-1 space-y-1">
                    <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white">
                        {{ $user->name }}
                    </h1>
                    <p class="text-sm font-semibold text-indigo-600 dark:text-indigo-400">
                        {{ '@' . ($user->username ?? 'username') }}
                    </p>
                    <p class="text-slate-500 dark:text-slate-400 text-sm">
                        {{ $user->email }}
                    </p>
                </div>
            </div>

            <!-- Profile Details -->
            <div class="border-t border-slate-200/50 dark:border-slate-800/50 pt-6 space-y-4">
                <h2 class="text-lg font-bold text-slate-900 dark:text-white">Detail Akun</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="p-4 rounded-2xl bg-slate-50/50 dark:bg-slate-800/30 border border-slate-100 dark:border-slate-800/50">
                        <span class="text-xs text-slate-400 block">Tanggal Registrasi</span>
                        <span class="text-sm font-semibold text-slate-800 dark:text-slate-200">
                            {{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}
                        </span>
                    </div>

                    <div class="p-4 rounded-2xl bg-slate-50/50 dark:bg-slate-800/30 border border-slate-100 dark:border-slate-800/50">
                        <span class="text-xs text-slate-400 block">Terakhir Diperbarui</span>
                        <span class="text-sm font-semibold text-slate-800 dark:text-slate-200">
                            {{ $user->updated_at ? $user->updated_at->format('d M Y') : '-' }}
                        </span>
                    </div>

                    <div class="p-4 rounded-2xl bg-slate-50/50 dark:bg-slate-800/30 border border-slate-100 dark:border-slate-800/50">
                        <span class="text-xs text-slate-400 block">Peran (Role)</span>
                        <span class="text-sm font-semibold text-slate-800 dark:text-slate-200 capitalize">
                            {{ str_replace('_', ' ', $user->role?->name ?? 'User') }}
                        </span>
                    </div>

                    @if ($user->phone)
                        <div class="p-4 rounded-2xl bg-slate-50/50 dark:bg-slate-800/30 border border-slate-100 dark:border-slate-800/50">
                            <span class="text-xs text-slate-400 block">Nomor Telepon</span>
                            <span class="text-sm font-semibold text-slate-800 dark:text-slate-200">
                                {{ $user->phone }}
                            </span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row justify-end items-center gap-3 pt-6 border-t border-slate-200/50 dark:border-slate-800/50">
                <form method="POST" action="{{ route('logout') }}" class="w-full sm:w-auto order-last sm:order-first">
                    @csrf
                    <button type="submit" 
                            class="w-full sm:w-auto px-6 py-2.5 text-center text-sm font-semibold text-red-600 hover:text-red-700 bg-red-50 hover:bg-red-100 dark:bg-red-950/20 dark:hover:bg-red-950/45 rounded-2xl transition">
                        Keluar (Logout)
                    </button>
                </form>
                
                <a href="{{ route('profile.edit') }}" 
                   class="w-full sm:w-auto px-6 py-2.5 text-center text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-750 rounded-2xl shadow-md shadow-indigo-650/15 hover:shadow-lg transition">
                    Ubah Foto Profil
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
