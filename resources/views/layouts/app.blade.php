@php
    $schoolId = app(\App\Services\Tenancy\TenantContextService::class)->activeSchoolId();
    $brand = null;
    $theme = null;
    $cssVariables = '';
    
    if ($schoolId) {
        $settings = app(\App\Services\WhiteLabel\WhiteLabelPublicationService::class)->getActivePublishedSettings($schoolId);
        $brand = $settings['brand'];
        $theme = $settings['theme'];
        $cssVariables = app(\App\Services\WhiteLabel\SchoolThemeService::class)->generateCssVariables($theme);
    }
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    @if($brand)
        <title>{{ $brand->display_name }} - HafizPlus</title>
        @if($brand->favicon_path)
            <link rel="icon" type="image/png" href="{{ \App\Models\SystemAsset::url($brand->favicon_path) }}">
        @elseif(\App\Models\SystemAsset::has('system/logo.png'))
            <link rel="icon" type="image/png" href="{{ \App\Models\SystemAsset::url('system/logo.png') }}">
        @else
            <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo_pwa.svg') }}">
        @endif
    @else
        <title>{{ config('app.name', 'HafizPlus School Platform') }}</title>
        @if(\App\Models\SystemAsset::has('system/logo.png'))
            <link rel="icon" type="image/png" href="{{ \App\Models\SystemAsset::url('system/logo.png') }}">
        @else
            <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo_pwa.svg') }}">
        @endif
    @endif

    <link rel="manifest" href="{{ route('tenant.pwa.manifest') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @if($cssVariables)
        <style>
            {!! $cssVariables !!}
            
            /* Enforce theme variables on core branding elements */
            body {
                background: var(--school-background);
                color: var(--school-text);
            }
            .btn-primary, button.btn-primary {
                background-color: var(--school-primary) !important;
                color: #ffffff !important;
                border-radius: var(--school-radius-button) !important;
            }
            .rounded-2xl, .rounded-3xl {
                border-radius: var(--school-radius-card) !important;
            }
        </style>
    @endif
</head>
<body x-data class="min-h-screen bg-slate-100 text-slate-900 antialiased transition-colors duration-200 dark:bg-slate-950 dark:text-slate-100">
    @auth
        <div class="min-h-screen flex flex-col lg:flex-row bg-slate-50/50 dark:bg-slate-950/50 relative">
        <!-- Impersonation Active Floating Banner -->
        @if(session()->has('impersonator_id'))
            <div class="bg-gradient-to-r from-amber-600 via-amber-700 to-orange-700 text-white px-4 py-2.5 shadow-md flex items-center justify-between text-xs font-bold sticky top-0 z-50 transition-all">
                <div class="flex items-center space-x-2.5 min-w-0 flex-1">
                    <span class="p-1 rounded-md bg-white/20 text-sm shrink-0">🎭</span>
                    <span class="truncate">
                        Mode Simulasi: Anda sedang login sebagai <span class="underline font-black">{{ Auth::user()->name }}</span> ({{ Auth::user()->role?->label ?? 'User' }}{{ Auth::user()->school ? ' - ' . Auth::user()->school->name : '' }}).
                    </span>
                </div>
                <form method="POST" action="{{ route('impersonation.leave') }}" class="shrink-0 ml-3">
                    @csrf
                    <button type="submit" class="bg-white text-slate-900 hover:bg-amber-100 px-3.5 py-1.5 rounded-xl font-black shadow-sm transition-all flex items-center space-x-1.5 cursor-pointer active:scale-95 text-xs">
                        <svg class="w-3.5 h-3.5 text-amber-700 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"/></svg>
                        <span>Kembali ke Super Admin</span>
                    </button>
                </form>
            </div>
        @endif

        <div class="min-h-screen flex bg-slate-50 text-slate-800 dark:bg-slate-950 dark:text-slate-200 antialiased selection:bg-emerald-500 selection:text-white transition-colors duration-200 relative">
            <!-- Subtle Ambient Background Light (Natural Calm Ambiance) -->
            <div class="fixed top-0 left-1/4 w-96 h-96 bg-emerald-500/5 rounded-full blur-3xl pointer-events-none -z-10"></div>
            <div class="fixed bottom-0 right-1/4 w-96 h-96 bg-amber-500/3 rounded-full blur-3xl pointer-events-none -z-10"></div>

            <!-- Sidebar Navigation (Left) -->
            <x-navbar />

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col min-w-0 min-h-screen">
                <!-- Topbar Header (Responsive) -->
                <header class="sticky top-0 z-30 bg-slate-900/95 dark:bg-slate-950/95 backdrop-blur-md border-b border-slate-800/70 h-16 flex items-center justify-between px-3 sm:px-6 shadow-sm transition-colors duration-200 gap-2">
                    <div class="flex items-center space-x-2 sm:space-x-3 min-w-0">
                        <!-- Desktop Sidebar Toggle Button -->
                        <button @click="$store.sidebar.toggle()" 
                                type="button"
                                title="Buka / Tutup Sidebar"
                                class="hidden lg:inline-flex items-center justify-center p-2 rounded-xl text-slate-400 hover:text-emerald-300 hover:bg-slate-800/80 transition-all duration-200 focus:outline-none shrink-0">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                            </svg>
                        </button>

                        <!-- Mobile Sidebar Toggle Button -->
                        <button @click="$store.sidebar.toggleMobile()" 
                                type="button"
                                title="Menu"
                                class="lg:hidden inline-flex items-center justify-center p-2 rounded-xl text-slate-400 hover:text-emerald-300 hover:bg-slate-800/80 transition-all duration-200 focus:outline-none shrink-0">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                            </svg>
                        </button>

                        <!-- Instant Tenant Switcher for Superadmin & Multi-School Users -->
                        @php
                            $authUser = Auth::user();
                            $isSuperAdminUser = $authUser->isSuperAdmin();
                            $allTenantSchools = $isSuperAdminUser 
                                ? \App\Models\School::query()->orderBy('name')->get()
                                : $authUser->accessibleSchools()->wherePivot('membership_status', 'active')->orderBy('name')->get();
                            $currentActiveSchool = app(\App\Services\Tenancy\TenantContextService::class)->activeSchool();
                        @endphp

                        @if($isSuperAdminUser || $allTenantSchools->count() > 1)
                            <div x-data="{ openSwitcher: false }" class="relative shrink-0">
                                <button @click="openSwitcher = !openSwitcher"
                                        @click.outside="openSwitcher = false"
                                        type="button"
                                        title="Ganti Sekolah Aktif"
                                        class="flex items-center space-x-2 px-2.5 sm:px-3 py-1.5 rounded-xl bg-slate-800/90 border border-slate-700 text-xs font-bold text-slate-200 hover:text-white hover:bg-slate-800 transition shadow-xs focus:outline-none">
                                    <span class="w-2 h-2 rounded-full bg-emerald-400 shrink-0"></span>
                                    <span class="max-w-[110px] sm:max-w-[180px] truncate text-left">
                                        {{ $currentActiveSchool?->name ?? 'Pilih Sekolah' }}
                                    </span>
                                    <svg class="w-3.5 h-3.5 text-slate-400 transition-transform shrink-0" :class="{ 'rotate-180': openSwitcher }" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </button>

                                <div x-show="openSwitcher" 
                                     x-transition:enter="transition ease-out duration-150"
                                     x-transition:enter-start="opacity-0 scale-95"
                                     x-transition:enter-end="opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-100"
                                     x-transition:leave-start="opacity-100 scale-100"
                                     x-transition:leave-end="opacity-0 scale-95"
                                     class="absolute left-0 mt-2 w-64 rounded-2xl bg-[#0b1322] border border-slate-700 shadow-2xl p-2 z-50 space-y-1"
                                     style="display: none;">
                                    <div class="px-2.5 py-1 text-[10px] uppercase font-bold text-slate-400 tracking-wider">
                                        Pilih Sekolah Aktif
                                    </div>
                                    
                                    <div class="max-h-56 overflow-y-auto space-y-1 pr-1">
                                        @foreach($allTenantSchools as $tenantSchool)
                                            <form method="POST" action="{{ route('tenancy.switch') }}">
                                                @csrf
                                                <input type="hidden" name="school_id" value="{{ $tenantSchool->id }}">
                                                <button type="submit" class="w-full text-left px-2.5 py-1.5 rounded-lg text-xs font-bold flex items-center justify-between cursor-pointer {{ $currentActiveSchool?->id === $tenantSchool->id ? 'bg-emerald-950/70 text-emerald-300 font-black border border-emerald-800/60' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                                    <span class="truncate">{{ $tenantSchool->name }}</span>
                                                    @if($currentActiveSchool?->id === $tenantSchool->id)
                                                        <span class="text-emerald-400 shrink-0 ml-2">✓</span>
                                                    @endif
                                                </button>
                                            </form>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @else
                            <!-- Greeting -->
                            <div class="flex items-center space-x-2 min-w-0">
                                <span class="text-sm font-medium text-slate-400 hidden sm:inline">Selamat datang,</span>
                                <span class="text-sm font-bold text-white tracking-tight truncate max-w-[140px] sm:max-w-none">{{ Auth::user()->name }}</span>
                            </div>
                        @endif
                    </div>
                    <div class="flex items-center space-x-2 sm:space-x-3 shrink-0">
                        <!-- Notifications Link -->
                        @php $activeNotif = request()->routeIs('notifications.*'); @endphp
                        <a href="{{ route('notifications.index') }}" class="relative rounded-full p-2 text-slate-400 hover:bg-slate-800/80 hover:text-emerald-400 transition-colors focus:outline-none">
                            @if ($activeNotif)
                                <svg class="h-5 w-5 text-emerald-400" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z" /></svg>
                            @else
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                            @endif
                        </a>

                        <!-- Dark Mode Toggle -->
                        <button @click="Alpine.store('darkMode', !Alpine.store('darkMode')); localStorage.setItem('darkMode', Alpine.store('darkMode'))" class="rounded-full p-2 text-slate-400 hover:bg-slate-800/80 hover:text-emerald-400 transition-colors focus:outline-none">
                            <svg x-show="!$store.darkMode" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" /></svg>
                            <svg x-show="$store.darkMode" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m0-12.728l.707.707m12.728 12.728l.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z" /></svg>
                        </button>

                        <!-- Avatar Dropdown Component -->
                        <x-avatar />
                    </div>
                </header>

                <main class="flex-1 p-4 sm:p-6 lg:p-8">
                    @if (session('success'))
                        <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50/90 px-5 py-3.5 text-sm font-medium text-emerald-900 shadow-sm flex items-center gap-3 dark:border-emerald-900/50 dark:bg-emerald-950/40 dark:text-emerald-300">
                            <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <span>{{ session('success') }}</span>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50/90 px-5 py-3.5 text-sm font-medium text-rose-900 shadow-sm flex items-center gap-3 dark:border-rose-900/50 dark:bg-rose-950/40 dark:text-rose-300">
                            <svg class="w-5 h-5 text-rose-600 dark:text-rose-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <span>{{ session('error') }}</span>
                        </div>
                    @endif

                    @yield('content')
                </main>
            </div>
        </div>
    @else
        <div class="min-h-screen bg-slate-50 dark:bg-slate-950">
            <main class="mx-auto max-w-7xl px-4 py-8">
                @if (session('success'))
                    <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-800 dark:border-green-900/50 dark:bg-green-950/30 dark:text-green-300">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-800 dark:border-red-900/50 dark:bg-red-950/30 dark:text-red-300">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    @endauth

    <!-- Service Worker Registration & PWA Readiness -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(reg => console.log('[PWA] Service Worker registered:', reg.scope))
                    .catch(err => console.log('[PWA] Service Worker registration failed:', err));
            });
        }
    </script>
</body>
</html>
