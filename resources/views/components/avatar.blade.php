<div class="relative" x-data="{ open: false }" @click.outside="open = false">
    <button aria-label="Menu Pengguna" aria-haspopup="true" :aria-expanded="open.toString()" @click="open = !open" class="flex items-center rounded-full focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900 transition-all duration-200">
        <!-- Avatar image using Dicebear for dynamic user identity -->
        <img src="{{ Auth::user()->profile_picture_url ?? 'https://api.dicebear.com/7.x/adventurer-neutral/svg?seed=' . urlencode(Auth::user()->name) }}" alt="Avatar" class="w-9 h-9 rounded-full object-cover border border-slate-200 dark:border-slate-700">
    </button>
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-100" 
         x-transition:enter-start="opacity-0 scale-95" 
         x-transition:enter-end="opacity-100 scale-100" 
         x-transition:leave="transition ease-in duration-75" 
         x-transition:leave-start="opacity-100 scale-100" 
         x-transition:leave-end="opacity-0 scale-95" 
         class="absolute right-0 mt-2 w-52 bg-white dark:bg-slate-800 rounded-xl shadow-xl border border-slate-100 dark:border-slate-700/50 py-1.5 z-50 origin-top-right" 
         style="display: none;">
        
        <div class="px-4 py-2.5 border-b border-slate-100 dark:border-slate-700/50 mb-1">
            <p class="text-sm font-semibold text-slate-800 dark:text-slate-100">{{ Auth::user()->name }}</p>
            <p class="text-xs text-slate-500 truncate dark:text-slate-450 mt-0.5">{{ Auth::user()->email }}</p>
        </div>

        <a href="{{ route('profile.show') }}" class="flex items-center space-x-2 px-4 py-2 text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition">
            <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            <span>Profil Saya</span>
        </a>
        @if (auth()->user()->hasRole(['admin', 'admin_sekolah', 'principal', 'kepala_sekolah', 'teacher', 'merchant', 'boarding_supervisor', 'finance', 'cashier']))
        <a href="{{ route('saas-ops.support-tickets.index') }}" class="flex items-center space-x-2 px-4 py-2 text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition">
            <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            <span>Tiket Bantuan</span>
        </a>
        @endif

        <form method="POST" action="{{ route('logout') }}" class="mt-1 border-t border-slate-100 dark:border-slate-700/50 pt-1">
            @csrf
            <button type="submit" class="w-full flex items-center space-x-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-950/30 transition text-left">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                <span>Keluar</span>
            </button>
        </form>
    </div>
</div>
