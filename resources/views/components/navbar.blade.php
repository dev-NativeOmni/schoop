@php
    $user = auth()->user();
    $isSuperAdmin = $user->isSuperAdmin();
    $isAdmin = $user->isAdmin();
    $isPrincipal = $user->isPrincipal();
    $isTeacher = $user->isTeacher();
    $isParent = $user->isParent();
    $isStudent = $user->isStudent();
    $isBoardingSupervisor = $user->isBoardingSupervisor();

    $hasAdminOrSuperAdmin = $isSuperAdmin || $isAdmin;
    $hasInternalAccess = $hasAdminOrSuperAdmin || $isTeacher || $isPrincipal;
    $hasFinanceAccess = $hasAdminOrSuperAdmin || $isPrincipal || $user->hasRole('finance');
    $canManageFinance = $hasAdminOrSuperAdmin || $user->hasRole('finance');
    $hasBoardingAccess = $hasAdminOrSuperAdmin || $isPrincipal || $isBoardingSupervisor;
    $roleName = $user->role?->name;
    $hasCashlessAccess = in_array($roleName, ['super_admin', 'admin', 'admin_sekolah', 'finance', 'cashier', 'merchant', 'principal', 'kepala_sekolah'], true);
    $canManageCashless = in_array($roleName, ['super_admin', 'admin', 'admin_sekolah', 'finance'], true);
    $canUseCashlessPos = in_array($roleName, ['super_admin', 'admin', 'admin_sekolah', 'cashier', 'merchant'], true);
    
    // SaaS Operations access & granular sub-permissions
    $hasSaasOpsAccess = in_array($roleName, ['super_admin', 'operations_manager', 'support_staff', 'customer_success', 'sales'], true);
    $canManageSaasSubscriptions = in_array($roleName, ['super_admin', 'operations_manager'], true);
    $canManageSaasIncidents = in_array($roleName, ['super_admin', 'operations_manager', 'support_staff'], true);
    $canViewSaasKnowledgeBase = in_array($roleName, ['super_admin', 'operations_manager', 'support_staff', 'customer_success'], true);
    
    $hasDeveloperPortalAccess = in_array($roleName, ['super_admin', 'operations_manager', 'support_staff', 'customer_success', 'admin', 'admin_sekolah'], true);
    $canManageDevScopes = $roleName === 'super_admin';
    $canManageDevDocs = in_array($roleName, ['super_admin', 'operations_manager'], true);
    $canManageDevClients = in_array($roleName, ['super_admin', 'operations_manager', 'admin', 'admin_sekolah'], true);

    $canViewSchoolOs = in_array($roleName, ['super_admin', 'admin', 'admin_sekolah', 'kepala_sekolah', 'principal', 'teacher', 'guru', 'guru_tahfidz'], true);
    $canManageSchoolOs = in_array($roleName, ['super_admin', 'admin', 'admin_sekolah'], true);
    $canManageTenancy = in_array($roleName, ['super_admin', 'admin', 'admin_sekolah', 'kepala_sekolah', 'principal'], true);
    $activeSchool = app(\App\Services\Tenancy\TenantContextService::class)->activeSchool();
    $activeSchoolId = app(\App\Services\Tenancy\TenantContextService::class)->activeSchoolId();
    $moduleAccess = app(\App\Services\Billing\ModuleAccessService::class);
    $moduleAvailable = fn (string $moduleKey): bool => 
        (method_exists($user, 'hasRole') && $user->hasRole(['super_admin'])) 
        || ! $activeSchool 
        || $moduleAccess->isEnabledForSchool($activeSchool, $moduleKey);
    $canUseSchoolOs = $moduleAvailable('schoolos');
    $canUseTahfizh = $moduleAvailable('tahfizh');
    $canUseMutabaah = $moduleAvailable('mutabaah');
    $canUseAttendance = $moduleAvailable('attendance');
    $canUseTahsin = $moduleAvailable('tahsin');
    $canUseFinanceModule = $moduleAvailable('finance');
    $canUseBoardingModule = $moduleAvailable('boarding');
    $canUseCashlessModule = $moduleAvailable('cashless');
    $canUseWhiteLabel = $moduleAvailable('white_label');
    $canUseLms = $moduleAvailable('lms');
    $canUseNotifications = $moduleAvailable('notifications');
    $globalLogoExists = \Illuminate\Support\Facades\Storage::disk('public')->exists('system/logo.png')
        || \App\Models\SystemAsset::has('system/logo.png');

    // Parent dynamic student parameter
    $firstChild = null;
    if ($isParent) {
        $parentProfile = $user->parentProfile;
        $firstChild = $parentProfile?->students?->first();
    }
@endphp

<div x-data="{ openDropdown: '{{ request()->segment(1) }}' }" 
     class="shrink-0 transition-all duration-300 ease-in-out"
     :class="($store.sidebar?.open ?? true) ? 'lg:w-80' : 'lg:w-0'">

    <!-- DESKTOP SIDEBAR: Left-Hand Side (hidden lg:flex) -->
    <aside class="hidden lg:flex fixed top-0 left-0 z-40 w-80 h-screen bg-[#1F2937] border-r border-slate-750 text-slate-100 flex-col shadow-xl overflow-hidden transition-transform duration-300 ease-in-out"
           :class="($store.sidebar?.open ?? true) ? 'translate-x-0' : '-translate-x-full'">
        
        <!-- Sidebar Header: School and Platform Branding -->
        <div class="p-6 border-b border-slate-750 flex flex-col space-y-4 shrink-0 bg-[#1F2937]/50">
            <!-- Platform Branding (HafizPlus) & Collapse Button -->
            <div class="flex items-center justify-between">
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-2 group">
                    @if($globalLogoExists)
                        <img src="{{ asset('storage/system/logo.png') }}" alt="HafizPlus Logo" class="h-8 w-8 object-contain bg-white/10 p-0.5 rounded-lg">
                    @else
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-[#A3E635] to-[#84cc16] text-[#1F2937] shadow-md shadow-[#A3E635]/25">
                            <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                    @endif
                    <span class="text-base font-extrabold tracking-tight text-white">Hafiz<span class="text-[#A3E635]">Plus</span></span>
                </a>

                <!-- Desktop Collapse Button -->
                <button @click="$store.sidebar.toggle()" 
                        type="button" 
                        title="Tutup Sidebar" 
                        class="hidden lg:flex items-center justify-center p-1.5 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800 transition-colors focus:outline-none">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18.75 19.5l-7.5-7.5 7.5-7.5m-6 15L5.25 12l7.5-7.5" />
                    </svg>
                </button>
            </div>

            <!-- Active School Branding Section (Dual Branding) -->
            @php
                $resolvedBrand = null;
                if ($activeSchoolId) {
                    $publishedSettings = app(\App\Services\WhiteLabel\WhiteLabelPublicationService::class)->getActivePublishedSettings($activeSchoolId);
                    $resolvedBrand = $publishedSettings['brand'];
                }
            @endphp

            @if($activeSchoolId)
                <div class="flex items-center space-x-3 p-3 bg-slate-800/60 border border-slate-700/50 rounded-xl">
                    @if($resolvedBrand && $resolvedBrand->logo_path)
                        <img src="{{ asset('storage/' . $resolvedBrand->logo_path) }}" alt="Logo {{ $resolvedBrand->display_name }}" class="h-9 w-9 object-contain bg-white/20 p-1 rounded-lg">
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-bold text-white truncate">{{ $resolvedBrand->display_name }}</p>
                            <p class="text-[10px] text-slate-400 font-semibold truncate">Sekolah Aktif</p>
                        </div>
                    @elseif($activeSchool)
                        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-slate-700 text-white font-bold text-sm">
                            {{ strtoupper(substr($activeSchool->name, 0, 2)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-bold text-white truncate">{{ $activeSchool->name }}</p>
                            <p class="text-[10px] text-slate-400 font-semibold truncate">Sekolah Aktif</p>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <!-- Scrollable Navigation Area -->
        <div class="flex-1 overflow-y-auto px-4 py-6 space-y-4">
            
            <!-- User Profile Box -->
            <div class="p-3 bg-slate-800/30 border border-slate-700/30 rounded-xl flex items-center space-x-3 mb-4">
                <img src="{{ Auth::user()->profile_picture_url ?? 'https://api.dicebear.com/7.x/adventurer-neutral/svg?seed=' . urlencode(Auth::user()->name) }}" alt="Avatar" class="w-10 h-10 rounded-full object-cover border border-slate-700">
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-bold text-white truncate">{{ Auth::user()->name }}</p>
                    <p class="text-[10px] text-slate-400 font-semibold capitalize truncate">{{ $roleName ?? 'User' }}</p>
                </div>
            </div>

            <!-- Menu Sections / Links -->
            <nav class="space-y-1.5 text-xs font-semibold">
                
                <!-- Dashboard -->
                @php $active = request()->routeIs('dashboard*'); @endphp
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-xl transition-all duration-200 {{ $active ? 'bg-slate-800 text-[#A3E635] border-l-4 border-[#A3E635] font-black' : 'text-slate-350 hover:bg-slate-800/50 hover:text-white' }}">
                    <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span>Dashboard</span>
                </a>

                <!-- Al-Qur'an -->
                @php $active = request()->routeIs('quran*'); @endphp
                <a href="{{ route('quran.mushaf') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-xl transition-all duration-200 {{ $active ? 'bg-slate-800 text-[#A3E635] border-l-4 border-[#A3E635] font-black' : 'text-slate-355 hover:bg-slate-800/50 hover:text-white' }}">
                    <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <span>Al-Qur'an Mushaf</span>
                </a>

                <!-- SchoolOS Accordion -->
                @if ($canViewSchoolOs && $canUseSchoolOs)
                    @php $active = request()->routeIs('schoolos.*'); @endphp
                    <div class="space-y-1">
                        <button @click="openDropdown = openDropdown === 'schoolos' ? null : 'schoolos'" 
                                class="w-full flex items-center justify-between px-4 py-2.5 rounded-xl transition-all duration-200 {{ $active ? 'bg-slate-800 text-[#A3E635] font-black' : 'text-slate-355 hover:bg-slate-800/50 hover:text-white' }}">
                            <div class="flex items-center space-x-3">
                                <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.263 8.535l7.24-3.62a.75.75 0 01.674 0l7.24 3.62a.75.75 0 010 1.342l-7.24 3.62a.75.75 0 01-.674 0L4.263 9.876a.75.75 0 010-1.342z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.31 12.593v3.136c0 .716.495 1.344 1.196 1.492a10.875 10.875 0 008.99 0c.701-.148 1.196-.776 1.196-1.492v-3.136" />
                                </svg>
                                <span>SchoolOS</span>
                            </div>
                            <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="{ 'rotate-180': openDropdown === 'schoolos' }" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </button>
                        <div x-show="openDropdown === 'schoolos'" x-collapse class="pl-7 space-y-1.5" style="display: none;">
                            <a href="{{ route('schoolos.dashboard') }}" class="block py-1 px-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/40 transition">Dashboard SchoolOS</a>
                            @if ($canManageSchoolOs)
                                <a href="{{ route('schoolos.academic-years.index') }}" class="block py-1 px-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/40 transition">Tahun Ajaran</a>
                                <a href="{{ route('schoolos.modules.index') }}" class="block py-1 px-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/40 transition">Module Registry</a>
                                <a href="{{ route('schoolos.settings.index') }}" class="block py-1 px-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/40 transition">School Settings</a>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Data Master Accordion -->
                @if ($hasAdminOrSuperAdmin)
                    @php $active = request()->routeIs('master-data.*'); @endphp
                    <div class="space-y-1">
                        <button @click="openDropdown = openDropdown === 'master' ? null : 'master'" 
                                class="w-full flex items-center justify-between px-4 py-2.5 rounded-xl transition-all duration-200 {{ $active ? 'bg-slate-800 text-[#A3E635] font-black' : 'text-slate-355 hover:bg-slate-800/50 hover:text-white' }}">
                            <div class="flex items-center space-x-3">
                                <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75M3.75 10.125v3.75m16.5 0v3.75M3.75 13.875v3.75" />
                                </svg>
                                <span>Data Master</span>
                            </div>
                            <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="{ 'rotate-180': openDropdown === 'master' }" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </button>
                        <div x-show="openDropdown === 'master'" x-collapse class="pl-7 space-y-1.5" style="display: none;">
                            @if ($isSuperAdmin)
                                <a href="{{ route('master-data.schools.index') }}" class="block py-1 px-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/40 transition">Sekolah</a>
                                <a href="{{ route('master-data.users.index') }}" class="block py-1 px-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/40 transition">Kelola User</a>
                            @endif
                            <a href="{{ route('master-data.class-rooms.index') }}" class="block py-1 px-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/40 transition">Kelas</a>
                            <a href="{{ route('master-data.teachers.index') }}" class="block py-1 px-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/40 transition">Guru</a>
                            <a href="{{ route('master-data.students.index') }}" class="block py-1 px-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/40 transition">Siswa</a>
                        </div>
                    </div>
                @endif

                <!-- Tenancy Accordion -->
                @if ($canManageTenancy)
                    @php $active = request()->routeIs('tenancy.*') || request()->routeIs('white-label.*'); @endphp
                    <div class="space-y-1">
                        <button @click="openDropdown = openDropdown === 'tenancy' ? null : 'tenancy'" 
                                class="w-full flex items-center justify-between px-4 py-2.5 rounded-xl transition-all duration-200 {{ $active ? 'bg-slate-800 text-[#A3E635] font-black' : 'text-slate-355 hover:bg-slate-800/50 hover:text-white' }}">
                            <div class="flex items-center space-x-3">
                                <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572 1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span>Branding & Tenancy</span>
                            </div>
                            <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="{ 'rotate-180': openDropdown === 'tenancy' }" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </button>
                        <div x-show="openDropdown === 'tenancy'" x-collapse class="pl-7 space-y-1.5" style="display: none;">
                            <a href="{{ route('tenancy.dashboard') }}" class="block py-1 px-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/40 transition">Tenant Dashboard</a>
                            <a href="{{ route('tenancy.switcher') }}" class="block py-1 px-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/40 transition">Ganti Sekolah</a>
                            @if ($hasAdminOrSuperAdmin)
                                <a href="{{ route('tenancy.memberships.index') }}" class="block py-1 px-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/40 transition">User Memberships</a>
                                <a href="{{ route('tenancy.settings.index') }}" class="block py-1 px-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/40 transition">Tenant Settings</a>
                                <a href="{{ route('tenancy.modules.index') }}" class="block py-1 px-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/40 transition">Tenant Modules</a>
                                @if($canUseWhiteLabel)
                                    <a href="{{ route('white-label.dashboard') }}" class="block py-1 px-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/40 transition">White-Label Builder</a>
                                @endif
                                <a href="{{ route('tenancy.audit-logs.index') }}" class="block py-1 px-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/40 transition">Tenant Audit Logs</a>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Billing & Subscription Accordion -->
                @if (in_array($roleName, ['super_admin', 'admin', 'admin_sekolah'], true))
                    @php $active = request()->routeIs('billing.*'); @endphp
                    <div class="space-y-1">
                        <button @click="openDropdown = openDropdown === 'billing' ? null : 'billing'" 
                                class="w-full flex items-center justify-between px-4 py-2.5 rounded-xl transition-all duration-200 {{ $active ? 'bg-slate-800 text-[#A3E635] font-black' : 'text-slate-355 hover:bg-slate-800/50 hover:text-white' }}">
                            <div class="flex items-center space-x-3">
                                <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
                                </svg>
                                <span>Billing & Paket</span>
                            </div>
                            <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="{ 'rotate-180': openDropdown === 'billing' }" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </button>
                        <div x-show="openDropdown === 'billing'" x-collapse class="pl-7 space-y-1.5" style="display: none;">
                            <a href="{{ route('billing.plans.index') }}" class="block py-1 px-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/40 transition">Paket Langganan</a>
                            <a href="{{ route('billing.school-subscriptions.index') }}" class="block py-1 px-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/40 transition">Langganan Sekolah</a>
                            <a href="{{ route('billing.module-overrides.index') }}" class="block py-1 px-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/40 transition">Override Modul</a>
                        </div>
                    </div>
                @endif

                <!-- Tahfizh Accordion -->
                @if ($hasInternalAccess && $canUseTahfizh)
                    @php $active = request()->routeIs('reports.tahfizh.*') || request()->routeIs('tahfizh.*'); @endphp
                    <div class="space-y-1">
                        <button @click="openDropdown = openDropdown === 'tahfizh' ? null : 'tahfizh'" 
                                class="w-full flex items-center justify-between px-4 py-2.5 rounded-xl transition-all duration-200 {{ $active ? 'bg-slate-800 text-[#A3E635] font-black' : 'text-slate-355 hover:bg-slate-800/50 hover:text-white' }}">
                            <div class="flex items-center space-x-3">
                                <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                                </svg>
                                <span>Tahfizh</span>
                            </div>
                            <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="{ 'rotate-180': openDropdown === 'tahfizh' }" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </button>
                        <div x-show="openDropdown === 'tahfizh'" x-collapse class="pl-7 space-y-1.5" style="display: none;">
                            <a href="{{ route('reports.tahfizh.dashboard') }}" class="block py-1 px-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/40 transition">Dashboard Laporan</a>
                            <a href="{{ route('tahfizh.hafalan-records.index') }}" class="block py-1 px-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/40 transition">Setoran Hafalan</a>
                            @if ($hasAdminOrSuperAdmin || $isTeacher)
                                <a href="{{ route('tahfizh.hafalan-records.create') }}" class="block py-1 px-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/40 transition">Input Setoran</a>
                            @endif
                            <a href="{{ route('tahfizh.targets.index') }}" class="block py-1 px-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/40 transition">Target Hafalan</a>
                            <a href="{{ route('tahfizh.debts.index') }}" class="block py-1 px-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/40 transition">Hutang Hafalan</a>
                            <a href="{{ route('reports.tahfizh.monthly.index') }}" class="block py-1 px-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/40 transition">Laporan Bulanan</a>
                            <a href="{{ route('reports.tahfizh.quarterly.index') }}" class="block py-1 px-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/40 transition">Laporan Triwulan</a>
                        </div>
                    </div>
                @endif

                <!-- Mutabaah Accordion -->
                @if ($hasInternalAccess && $canUseMutabaah)
                    @php $active = request()->routeIs('mutabaah.*'); @endphp
                    <div class="space-y-1">
                        <button @click="openDropdown = openDropdown === 'mutabaah' ? null : 'mutabaah'" 
                                class="w-full flex items-center justify-between px-4 py-2.5 rounded-xl transition-all duration-200 {{ $active ? 'bg-slate-800 text-[#A3E635] font-black' : 'text-slate-355 hover:bg-slate-800/50 hover:text-white' }}">
                            <div class="flex items-center space-x-3">
                                <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <span>Mutabaah</span>
                            </div>
                            <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="{ 'rotate-180': openDropdown === 'mutabaah' }" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </button>
                        <div x-show="openDropdown === 'mutabaah'" x-collapse class="pl-7 space-y-1.5" style="display: none;">
                            <a href="{{ route('mutabaah.reports.dashboard') }}" class="block py-1 px-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/40 transition">Dashboard Laporan</a>
                            @if ($hasAdminOrSuperAdmin || $isTeacher)
                                <a href="{{ route('mutabaah.daily.index') }}" class="block py-1 px-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/40 transition">Input Mutabaah</a>
                            @endif
                            @if ($hasAdminOrSuperAdmin)
                                <a href="{{ route('mutabaah.activities.index') }}" class="block py-1 px-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/40 transition">Kelola Aktivitas</a>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Kehadiran Accordion -->
                @if ($hasInternalAccess && $canUseAttendance)
                    @php $active = request()->routeIs('attendance.*'); @endphp
                    <div class="space-y-1">
                        <button @click="openDropdown = openDropdown === 'attendance' ? null : 'attendance'" 
                                class="w-full flex items-center justify-between px-4 py-2.5 rounded-xl transition-all duration-200 {{ $active ? 'bg-slate-800 text-[#A3E635] font-black' : 'text-slate-355 hover:bg-slate-800/50 hover:text-white' }}">
                            <div class="flex items-center space-x-3">
                                <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                </svg>
                                <span>Kehadiran</span>
                            </div>
                            <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="{ 'rotate-180': openDropdown === 'attendance' }" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </button>
                        <div x-show="openDropdown === 'attendance'" x-collapse class="pl-7 space-y-1.5" style="display: none;">
                            <a href="{{ route('attendance.reports.dashboard') }}" class="block py-1 px-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/40 transition">Dashboard Laporan</a>
                            @if($hasAdminOrSuperAdmin)
                                <a href="{{ route('attendance.qr-cards.index') }}" class="block py-1 px-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/40 transition">Kartu QR Siswa</a>
                            @endif
                            <a href="{{ route('attendance.scanner.index') }}" class="block py-1 px-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/40 transition">Scanner Kehadiran</a>
                            <a href="{{ route('attendance.manual.create') }}" class="block py-1 px-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/40 transition">Input Manual</a>
                            <a href="{{ route('attendance.sessions.index') }}" class="block py-1 px-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/40 transition">Sesi Kehadiran</a>
                        </div>
                    </div>
                @endif

                <!-- Tahsin Accordion -->
                @if ($hasInternalAccess && $canUseTahsin)
                    @php $active = request()->routeIs('tahsin.*'); @endphp
                    <div class="space-y-1">
                        <button @click="openDropdown = openDropdown === 'tahsin' ? null : 'tahsin'" 
                                class="w-full flex items-center justify-between px-4 py-2.5 rounded-xl transition-all duration-200 {{ $active ? 'bg-slate-800 text-[#A3E635] font-black' : 'text-slate-355 hover:bg-slate-800/50 hover:text-white' }}">
                            <div class="flex items-center space-x-3">
                                <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 21l5.438-3.125 5.437 3.125-1.687-6.096L21 11.25l-6.219-.469L12 5.25 9.219 10.781 3 11.25l4.875 3.656z" />
                                </svg>
                                <span>Tahsin</span>
                            </div>
                            <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="{ 'rotate-180': openDropdown === 'tahsin' }" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </button>
                        <div x-show="openDropdown === 'tahsin'" x-collapse class="pl-7 space-y-1.5" style="display: none;">
                            <a href="{{ route('tahsin.reports.dashboard') }}" class="block py-1 px-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/40 transition">Dashboard Laporan</a>
                            <a href="{{ route('tahsin.profiles.index') }}" class="block py-1 px-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/40 transition">Profil Tahsin Siswa</a>
                            <a href="{{ route('tahsin.assessments.index') }}" class="block py-1 px-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/40 transition">Penilaian Siswa</a>
                            @if ($hasAdminOrSuperAdmin)
                                <a href="{{ route('tahsin.levels.index') }}" class="block py-1 px-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/40 transition">Kelola Jenjang</a>
                                <a href="{{ route('tahsin.skills.index') }}" class="block py-1 px-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/40 transition">Kelola Keterampilan</a>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- LMS Accordion -->
                @if ($hasInternalAccess && $canUseLms)
                    @php $active = request()->routeIs('lms.*'); @endphp
                    <div class="space-y-1">
                        <button @click="openDropdown = openDropdown === 'lms' ? null : 'lms'" 
                                class="w-full flex items-center justify-between px-4 py-2.5 rounded-xl transition-all duration-200 {{ $active ? 'bg-slate-800 text-[#A3E635] font-black' : 'text-slate-355 hover:bg-slate-800/50 hover:text-white' }}">
                            <div class="flex items-center space-x-3">
                                <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                                <span>LMS Belajar</span>
                            </div>
                            <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="{ 'rotate-180': openDropdown === 'lms' }" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </button>
                        <div x-show="openDropdown === 'lms'" x-collapse class="pl-7 space-y-1.5" style="display: none;">
                            <a href="{{ route('lms.dashboard') }}" class="block py-1 px-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/40 transition">Dashboard LMS</a>
                            <a href="{{ route('lms.courses.index') }}" class="block py-1 px-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/40 transition">Kursus Belajar</a>
                            <a href="{{ route('lms.reports.index') }}" class="block py-1 px-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/40 transition">Laporan Progress</a>
                        </div>
                    </div>
                @endif

                <!-- Keuangan Accordion -->
                @if ($hasFinanceAccess && $canUseFinanceModule)
                    @php $active = request()->routeIs('finance.*'); @endphp
                    <div class="space-y-1">
                        <button @click="openDropdown = openDropdown === 'finance' ? null : 'finance'" 
                                class="w-full flex items-center justify-between px-4 py-2.5 rounded-xl transition-all duration-200 {{ $active ? 'bg-slate-800 text-[#A3E635] font-black' : 'text-slate-355 hover:bg-slate-800/50 hover:text-white' }}">
                            <div class="flex items-center space-x-3">
                                <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
                                </svg>
                                <span>Keuangan</span>
                            </div>
                            <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="{ 'rotate-180': openDropdown === 'finance' }" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </button>
                        <div x-show="openDropdown === 'finance'" x-collapse class="pl-7 space-y-1.5" style="display: none;">
                            <a href="{{ route('finance.reports.dashboard') }}" class="block py-1 px-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/40 transition">Dashboard Laporan</a>
                            <a href="{{ route('finance.bills.index') }}" class="block py-1 px-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/40 transition">Tagihan Siswa</a>
                            <a href="{{ route('finance.payments.index') }}" class="block py-1 px-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/40 transition">Pembayaran Siswa</a>
                            @if ($canManageFinance)
                                <a href="{{ route('finance.fee-categories.index') }}" class="block py-1 px-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/40 transition">Kategori Biaya</a>
                                <a href="{{ route('finance.fee-items.index') }}" class="block py-1 px-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/40 transition">Item Biaya</a>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Cashless Accordion -->
                @if ($hasCashlessAccess && $canUseCashlessModule)
                    @php $active = request()->routeIs('cashless.*'); @endphp
                    <div class="space-y-1">
                        <button @click="openDropdown = openDropdown === 'cashless' ? null : 'cashless'" 
                                class="w-full flex items-center justify-between px-4 py-2.5 rounded-xl transition-all duration-200 {{ $active ? 'bg-slate-800 text-[#A3E635] font-black' : 'text-slate-355 hover:bg-slate-800/50 hover:text-white' }}">
                            <div class="flex items-center space-x-3">
                                <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125h17.25c.621 0 1.125.504 1.125 1.125V6m-19.5 0h19.5m-19.5 0v11.25c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125V6" />
                                </svg>
                                <span>Cashless System</span>
                            </div>
                            <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="{ 'rotate-180': openDropdown === 'slate-750' }" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </button>
                        <div x-show="openDropdown === 'cashless'" x-collapse class="pl-7 space-y-1.5" style="display: none;">
                            <a href="{{ route('cashless.reports.dashboard') }}" class="block py-1 px-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/40 transition">Dashboard Laporan</a>
                            @if ($canManageCashless)
                                <a href="{{ route('cashless.merchants.index') }}" class="block py-1 px-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/40 transition">Merchant</a>
                                <a href="{{ route('cashless.products.index') }}" class="block py-1 px-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/40 transition">Produk Merchant</a>
                                <a href="{{ route('cashless.wallets.index') }}" class="block py-1 px-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/40 transition">Wallet Santri</a>
                                <a href="{{ route('cashless.top-ups.create') }}" class="block py-1 px-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/40 transition">Top Up Wallet</a>
                                <a href="{{ route('cashless.refunds.create') }}" class="block py-1 px-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/40 transition">Refund / Void</a>
                                <a href="{{ route('cashless.settlements.index') }}" class="block py-1 px-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/40 transition">Settlement</a>
                            @endif
                            @if ($canUseCashlessPos)
                                <a href="{{ route('cashless.pos.cashier') }}" class="block py-1 px-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/40 transition">POS Kasir</a>
                                <a href="{{ route('cashless.pos-sessions.index') }}" class="block py-1 px-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/40 transition">Session POS</a>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- SaaS Operations Accordion -->
                @if ($hasSaasOpsAccess)
                    @php $active = request()->routeIs('saas-ops.*'); @endphp
                    <div class="space-y-1">
                        <button @click="openDropdown = openDropdown === 'saasops' ? null : 'saasops'" 
                                class="w-full flex items-center justify-between px-4 py-2.5 rounded-xl transition-all duration-200 {{ $active ? 'bg-slate-800 text-[#A3E635] font-black' : 'text-slate-355 hover:bg-slate-800/50 hover:text-white' }}">
                            <div class="flex items-center space-x-3">
                                <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h15.75c.621 0 1.125.504 1.125 1.125v6.75C21 20.496 20.496 21 19.875 21H4.125A1.125 1.125 0 013 19.875v-6.75zM4.5 10.5L12 3l7.5 7.5M9 21v-6h6v6" />
                                </svg>
                                <span>SaaS Ops</span>
                            </div>
                            <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="{ 'rotate-180': openDropdown === 'saasops' }" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </button>
                        <div x-show="openDropdown === 'saasops'" x-collapse class="pl-7 space-y-1.5" style="display: none;">
                            <a href="{{ route('saas-ops.dashboard') }}" class="block py-1 px-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/40 transition">Scale Dashboard</a>
                            @if ($canManageSaasSubscriptions)
                                <a href="{{ route('saas-ops.subscription-plans.index') }}" class="block py-1 px-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/40 transition">Plans</a>
                                <a href="{{ route('saas-ops.school-subscriptions.index') }}" class="block py-1 px-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/40 transition">School Subs</a>
                                <a href="{{ route('saas-ops.tenant-invoices.index') }}" class="block py-1 px-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/40 transition">Invoices</a>
                            @endif
                            <a href="{{ route('saas-ops.support-tickets.index') }}" class="block py-1 px-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/40 transition">Tickets</a>
                            @if ($canManageSaasIncidents)
                                <a href="{{ route('saas-ops.incident-reports.index') }}" class="block py-1 px-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/40 transition">Incidents</a>
                                <a href="{{ route('saas-ops.release-notes.index') }}" class="block py-1 px-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/40 transition">Releases</a>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Developer Portal Accordion -->
                @if ($hasDeveloperPortalAccess)
                    @php $active = request()->routeIs('developer-portal.*'); @endphp
                    <div class="space-y-1">
                        <button @click="openDropdown = openDropdown === 'developerportal' ? null : 'developerportal'" 
                                class="w-full flex items-center justify-between px-4 py-2.5 rounded-xl transition-all duration-200 {{ $active ? 'bg-slate-800 text-[#A3E635] font-black' : 'text-slate-355 hover:bg-slate-800/50 hover:text-white' }}">
                            <div class="flex items-center space-x-3">
                                <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-1.5-1.5v-10.5a1.5 1.5 0 011.5-1.5h7.5a1.5 1.5 0 011.5 1.5v10.5a1.5 1.5 0 01-1.5 1.5h-7.5zM9.75 9.75h4.5m-4.5 3h4.5m-4.5 3h2.25" />
                                </svg>
                                <span>Developer Portal</span>
                            </div>
                            <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="{ 'rotate-180': openDropdown === 'developerportal' }" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </button>
                        <div x-show="openDropdown === 'developerportal'" x-collapse class="pl-7 space-y-1.5" style="display: none;">
                            <a href="{{ route('developer-portal.dashboard') }}" class="block py-1 px-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/40 transition">Dev Dashboard</a>
                            @if ($canManageDevClients)
                                <a href="{{ route('developer-portal.api-clients.index') }}" class="block py-1 px-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/40 transition">API Clients</a>
                            @endif
                            <a href="{{ route('developer-portal.docs.index') }}" class="block py-1 px-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/40 transition">API Docs</a>
                        </div>
                    </div>
                @endif

                <!-- Boarding Accordion -->
                @if ($hasBoardingAccess && $canUseBoardingModule)
                    @php $active = request()->routeIs('boarding.*'); @endphp
                    <div class="space-y-1">
                        <button @click="openDropdown = openDropdown === 'boarding' ? null : 'boarding'" 
                                class="w-full flex items-center justify-between px-4 py-2.5 rounded-xl transition-all duration-200 {{ $active ? 'bg-slate-800 text-[#A3E635] font-black' : 'text-slate-355 hover:bg-slate-800/50 hover:text-white' }}">
                            <div class="flex items-center space-x-3">
                                <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                <span>Boarding</span>
                            </div>
                            <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="{ 'rotate-180': openDropdown === 'boarding' }" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </button>
                        <div x-show="openDropdown === 'boarding'" x-collapse class="pl-7 space-y-1.5" style="display: none;">
                            <a href="{{ route('boarding.dashboard') }}" class="block py-1 px-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/40 transition">Dashboard Boarding</a>
                            <a href="{{ route('boarding.dormitories.index') }}" class="block py-1 px-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/40 transition">Asrama</a>
                            <a href="{{ route('boarding.rooms.index') }}" class="block py-1 px-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/40 transition">Kamar</a>
                            <a href="{{ route('boarding.beds.index') }}" class="block py-1 px-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/40 transition">Ranjang</a>
                            <a href="{{ route('boarding.assignments.index') }}" class="block py-1 px-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/40 transition">Penempatan Santri</a>
                            <a href="{{ route('boarding.leave-requests.index') }}" class="block py-1 px-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/40 transition">Perizinan</a>
                        </div>
                    </div>
                @endif

                <!-- Portal Wali Accordion -->
                @if ($isParent)
                    @php $active = request()->routeIs('portal.parent.*'); @endphp
                    <div class="space-y-1">
                        <button @click="openDropdown = openDropdown === 'parent' ? null : 'parent'" 
                                class="w-full flex items-center justify-between px-4 py-2.5 rounded-xl transition-all duration-200 {{ $active ? 'bg-slate-800 text-[#A3E635] font-black' : 'text-slate-355 hover:bg-slate-800/50 hover:text-white' }}">
                            <div class="flex items-center space-x-3">
                                <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                <span>Portal Wali</span>
                            </div>
                            <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="{ 'rotate-180': openDropdown === 'parent' }" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </button>
                        <div x-show="openDropdown === 'parent'" x-collapse class="pl-7 space-y-1.5" style="display: none;">
                            @if($canUseTahfizh)
                                <a href="{{ route('portal.parent.dashboard') }}" class="block py-1 px-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/40 transition">Progres Tahfizh</a>
                            @endif
                            @if ($firstChild && $canUseMutabaah)
                                <a href="{{ route('portal.parent.mutabaah', $firstChild) }}" class="block py-1 px-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/40 transition">Mutabaah Anak</a>
                            @endif
                            @if($canUseAttendance)
                                <a href="{{ route('portal.parent.attendance') }}" class="block py-1 px-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/40 transition">Kehadiran Anak</a>
                            @endif
                            @if($canUseTahsin)
                                <a href="{{ route('portal.parent.tahsin') }}" class="block py-1 px-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/40 transition">Tahsin Anak</a>
                            @endif
                            @if($canUseFinanceModule)
                                <a href="{{ route('portal.parent.finance') }}" class="block py-1 px-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/40 transition">Keuangan Anak</a>
                            @endif
                            @if($canUseCashlessModule)
                                <a href="{{ route('portal.parent.cashless') }}" class="block py-1 px-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/40 transition">Cashless Anak</a>
                            @endif
                            @if($canUseBoardingModule)
                                <a href="{{ route('portal.parent.boarding') }}" class="block py-1 px-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/40 transition">Boarding Anak</a>
                            @endif
                            @if($canUseLms)
                                <a href="{{ route('portal.parent.lms.index') }}" class="block py-1 px-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/40 transition">LMS Anak</a>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Portal Santri Accordion -->
                @if ($isStudent)
                    @php $active = request()->routeIs('portal.student.*'); @endphp
                    <div class="space-y-1">
                        <button @click="openDropdown = openDropdown === 'student' ? null : 'student'" 
                                class="w-full flex items-center justify-between px-4 py-2.5 rounded-xl transition-all duration-200 {{ $active ? 'bg-slate-800 text-[#A3E635] font-black' : 'text-slate-355 hover:bg-slate-800/50 hover:text-white' }}">
                            <div class="flex items-center space-x-3">
                                <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span>Portal Santri</span>
                            </div>
                            <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="{ 'rotate-180': openDropdown === 'student' }" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </button>
                        <div x-show="openDropdown === 'student'" x-collapse class="pl-7 space-y-1.5" style="display: none;">
                            @if($canUseTahfizh)
                                <a href="{{ route('portal.student.dashboard') }}" class="block py-1 px-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/40 transition">Progres Tahfizh</a>
                            @endif
                            @if($canUseMutabaah)
                                <a href="{{ route('portal.student.mutabaah') }}" class="block py-1 px-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/40 transition">Mutabaah Saya</a>
                            @endif
                            @if($canUseAttendance)
                                <a href="{{ route('portal.student.attendance') }}" class="block py-1 px-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/40 transition">Kehadiran Saya</a>
                            @endif
                            @if($canUseTahsin)
                                <a href="{{ route('portal.student.tahsin') }}" class="block py-1 px-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/40 transition">Tahsin Saya</a>
                            @endif
                            @if($canUseFinanceModule)
                                <a href="{{ route('portal.student.finance') }}" class="block py-1 px-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/40 transition">Keuangan Saya</a>
                            @endif
                            @if($canUseCashlessModule)
                                <a href="{{ route('portal.student.cashless') }}" class="block py-1 px-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/40 transition">Cashless Saya</a>
                            @endif
                            @if($canUseBoardingModule)
                                <a href="{{ route('portal.student.boarding') }}" class="block py-1 px-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/40 transition">Boarding Saya</a>
                            @endif
                            @if($canUseLms)
                                <a href="{{ route('portal.student.lms.index') }}" class="block py-1 px-3 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/40 transition">LMS Saya</a>
                            @endif
                        </div>
                    </div>
                @endif
            </nav>
        </div>

        <!-- Sidebar Footer: Logout Button -->
        <div class="p-4 border-t border-slate-750 shrink-0 bg-[#1F2937]/50">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center space-x-2 px-4 py-2.5 text-sm font-bold text-red-400 hover:bg-red-950/30 hover:text-red-350 border border-red-900/50 hover:border-red-800 rounded-xl transition duration-200">
                    <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span>Keluar</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- MOBILE DRAWER (Slide-Over Menu from Left) -->
    <!-- Backdrop overlay -->
    <div x-show="$store.sidebar.mobileOpen" 
         x-transition:enter="transition-opacity ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="lg:hidden fixed inset-0 bg-slate-900/80 backdrop-blur-sm z-50"
         style="display: none;"
         @click="$store.sidebar.closeMobile()">
    </div>

    <!-- Drawer Content Panel (From Left) -->
    <div x-show="$store.sidebar.mobileOpen" 
         x-transition:enter="transition-transform ease-out duration-300"
         x-transition:enter-start="-translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition-transform ease-in duration-200"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="-translate-x-full"
         class="lg:hidden fixed inset-y-0 left-0 w-80 bg-[#111827] text-slate-100 z-50 flex flex-col shadow-2xl"
         style="display: none;">
        
        <!-- Drawer Header -->
        <div class="p-5 border-b border-slate-750 flex items-center justify-between bg-[#1F2937]/50">
            <div class="flex items-center space-x-2">
                @if($globalLogoExists)
                    <img src="{{ asset('storage/system/logo.png') }}" alt="HafizPlus Logo" class="h-8 w-8 object-contain bg-white/10 p-0.5 rounded-lg">
                @else
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-[#A3E635] to-[#84cc16] text-[#1F2937] shadow-md shadow-[#A3E635]/25">
                        <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                @endif
                <span class="text-base font-extrabold tracking-tight text-white">Hafiz<span class="text-[#A3E635]">Plus</span></span>
            </div>
            <button @click="$store.sidebar.closeMobile()" class="p-1.5 text-slate-400 hover:text-white focus:outline-none">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Scrollable Navigation Area inside Drawer -->
        <div class="flex-1 overflow-y-auto px-4 py-5 space-y-4">
            
            <!-- Mobile User Profile -->
            <div class="p-3 bg-slate-800/30 border border-slate-700/30 rounded-xl flex items-center space-x-3 mb-4">
                <img src="{{ Auth::user()->profile_picture_url ?? 'https://api.dicebear.com/7.x/adventurer-neutral/svg?seed=' . urlencode(Auth::user()->name) }}" alt="Avatar" class="w-9 h-9 rounded-full object-cover">
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-bold text-white truncate">{{ Auth::user()->name }}</p>
                    <p class="text-[10px] text-slate-400 capitalize truncate">{{ $roleName ?? 'User' }}</p>
                </div>
            </div>

            <!-- Mobile Quick Settings (Dark Mode toggle inside mobile menu) -->
            <div class="flex items-center justify-between p-3 bg-slate-800/20 border border-slate-700/30 rounded-xl mb-4 text-xs">
                <span class="text-slate-400">Mode Gelap</span>
                <button @click="Alpine.store('darkMode', !Alpine.store('darkMode')); localStorage.setItem('darkMode', Alpine.store('darkMode'))" class="p-1 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300">
                    <svg x-show="!$store.darkMode" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" /></svg>
                    <svg x-show="$store.darkMode" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m0-12.728l.707.707m12.728 12.728l.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z" /></svg>
                </button>
            </div>

            <!-- Drawer Links -->
            <nav class="space-y-1 text-xs font-semibold">
                
                <!-- Dashboard -->
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl transition text-slate-350 hover:bg-slate-800 hover:text-white">
                    <span>Dashboard</span>
                </a>

                <!-- Al-Qur'an -->
                <a href="{{ route('quran.mushaf') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl transition text-slate-355 hover:bg-slate-800 hover:text-white">
                    <span>Al-Qur'an Mushaf</span>
                </a>

                <!-- SchoolOS Mobile -->
                @if ($canViewSchoolOs && $canUseSchoolOs)
                    <div class="space-y-1">
                        <button @click="openDropdown = openDropdown === 'schoolos-m' ? null : 'schoolos-m'" class="w-full flex items-center justify-between px-3.5 py-2 rounded-xl text-slate-355 hover:bg-slate-800 hover:text-white">
                            <span>SchoolOS</span>
                            <svg class="w-3 h-3 transition-transform" :class="{ 'rotate-180': openDropdown === 'schoolos-m' }" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </button>
                        <div x-show="openDropdown === 'schoolos-m'" x-collapse class="pl-5 space-y-1" style="display: none;">
                            <a href="{{ route('schoolos.dashboard') }}" class="block py-1 px-3 text-xs text-slate-400 hover:text-white">Dashboard SchoolOS</a>
                            @if ($canManageSchoolOs)
                                <a href="{{ route('schoolos.academic-years.index') }}" class="block py-1 px-3 text-xs text-slate-400 hover:text-white">Tahun Ajaran</a>
                                <a href="{{ route('schoolos.modules.index') }}" class="block py-1 px-3 text-xs text-slate-400 hover:text-white">Module Registry</a>
                                <a href="{{ route('schoolos.settings.index') }}" class="block py-1 px-3 text-xs text-slate-400 hover:text-white">School Settings</a>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Data Master Mobile -->
                @if ($hasAdminOrSuperAdmin)
                    <div class="space-y-1">
                        <button @click="openDropdown = openDropdown === 'master-m' ? null : 'master-m'" class="w-full flex items-center justify-between px-3.5 py-2 rounded-xl text-slate-355 hover:bg-slate-800 hover:text-white">
                            <span>Data Master</span>
                            <svg class="w-3 h-3 transition-transform" :class="{ 'rotate-180': openDropdown === 'master-m' }" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </button>
                        <div x-show="openDropdown === 'master-m'" x-collapse class="pl-5 space-y-1" style="display: none;">
                            @if ($isSuperAdmin)
                                <a href="{{ route('master-data.schools.index') }}" class="block py-1 px-3 text-xs text-slate-400 hover:text-white">Sekolah</a>
                                <a href="{{ route('master-data.users.index') }}" class="block py-1 px-3 text-xs text-slate-400 hover:text-white">Kelola User</a>
                            @endif
                            <a href="{{ route('master-data.class-rooms.index') }}" class="block py-1 px-3 text-xs text-slate-400 hover:text-white">Kelas</a>
                            <a href="{{ route('master-data.teachers.index') }}" class="block py-1 px-3 text-xs text-slate-400 hover:text-white">Guru</a>
                            <a href="{{ route('master-data.students.index') }}" class="block py-1 px-3 text-xs text-slate-400 hover:text-white">Siswa</a>
                        </div>
                    </div>
                @endif

                <!-- Tenancy Mobile -->
                @if ($canManageTenancy)
                    <div class="space-y-1">
                        <button @click="openDropdown = openDropdown === 'tenancy-m' ? null : 'tenancy-m'" class="w-full flex items-center justify-between px-3.5 py-2 rounded-xl text-slate-355 hover:bg-slate-800 hover:text-white">
                            <span>Tenancy</span>
                            <svg class="w-3 h-3 transition-transform" :class="{ 'rotate-180': openDropdown === 'tenancy-m' }" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </button>
                        <div x-show="openDropdown === 'tenancy-m'" x-collapse class="pl-5 space-y-1" style="display: none;">
                            <a href="{{ route('tenancy.dashboard') }}" class="block py-1 px-3 text-xs text-slate-400 hover:text-white">Tenant Dashboard</a>
                            <a href="{{ route('tenancy.switcher') }}" class="block py-1 px-3 text-xs text-slate-400 hover:text-white">Ganti Sekolah</a>
                            @if ($hasAdminOrSuperAdmin)
                                <a href="{{ route('tenancy.memberships.index') }}" class="block py-1 px-3 text-xs text-slate-400 hover:text-white">User Memberships</a>
                                <a href="{{ route('tenancy.settings.index') }}" class="block py-1 px-3 text-xs text-slate-400 hover:text-white">Tenant Settings</a>
                                <a href="{{ route('tenancy.modules.index') }}" class="block py-1 px-3 text-xs text-slate-400 hover:text-white">Tenant Modules</a>
                                @if($canUseWhiteLabel)
                                    <a href="{{ route('white-label.dashboard') }}" class="block py-1 px-3 text-xs text-slate-400 hover:text-white">White-Label Builder</a>
                                @endif
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Billing & Subscription Mobile -->
                @if (in_array($roleName, ['super_admin', 'admin', 'admin_sekolah'], true))
                    <div class="space-y-1">
                        <button @click="openDropdown = openDropdown === 'billing-m' ? null : 'billing-m'" class="w-full flex items-center justify-between px-3.5 py-2 rounded-xl text-slate-355 hover:bg-slate-800 hover:text-white">
                            <span>Billing & Paket</span>
                            <svg class="w-3 h-3 transition-transform" :class="{ 'rotate-180': openDropdown === 'billing-m' }" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </button>
                        <div x-show="openDropdown === 'billing-m'" x-collapse class="pl-5 space-y-1" style="display: none;">
                            <a href="{{ route('billing.plans.index') }}" class="block py-1 px-3 text-xs text-slate-400 hover:text-white">Paket Langganan</a>
                            <a href="{{ route('billing.school-subscriptions.index') }}" class="block py-1 px-3 text-xs text-slate-400 hover:text-white">Langganan Sekolah</a>
                            <a href="{{ route('billing.module-overrides.index') }}" class="block py-1 px-3 text-xs text-slate-400 hover:text-white">Override Modul</a>
                        </div>
                    </div>
                @endif

                <!-- Tahfizh Mobile -->
                @if ($hasInternalAccess && $canUseTahfizh)
                    <div class="space-y-1">
                        <button @click="openDropdown = openDropdown === 'tahfizh-m' ? null : 'tahfizh-m'" class="w-full flex items-center justify-between px-3.5 py-2 rounded-xl text-slate-355 hover:bg-slate-800 hover:text-white">
                            <span>Tahfizh</span>
                            <svg class="w-3 h-3 transition-transform" :class="{ 'rotate-180': openDropdown === 'tahfizh-m' }" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </button>
                        <div x-show="openDropdown === 'tahfizh-m'" x-collapse class="pl-5 space-y-1" style="display: none;">
                            <a href="{{ route('reports.tahfizh.dashboard') }}" class="block py-1 px-3 text-xs text-slate-400 hover:text-white">Dashboard Laporan</a>
                            <a href="{{ route('tahfizh.hafalan-records.index') }}" class="block py-1 px-3 text-xs text-slate-400 hover:text-white">Setoran Hafalan</a>
                            @if ($hasAdminOrSuperAdmin || $isTeacher)
                                <a href="{{ route('tahfizh.hafalan-records.create') }}" class="block py-1 px-3 text-xs text-slate-400 hover:text-white">Input Setoran</a>
                            @endif
                            <a href="{{ route('tahfizh.targets.index') }}" class="block py-1 px-3 text-xs text-slate-400 hover:text-white">Target Hafalan</a>
                            <a href="{{ route('tahfizh.debts.index') }}" class="block py-1 px-3 text-xs text-slate-400 hover:text-white">Hutang Hafalan</a>
                            <a href="{{ route('reports.tahfizh.monthly.index') }}" class="block py-1 px-3 text-xs text-slate-400 hover:text-white">Laporan Bulanan</a>
                        </div>
                    </div>
                @endif

                <!-- Mutabaah Mobile -->
                @if ($hasInternalAccess && $canUseMutabaah)
                    <div class="space-y-1">
                        <button @click="openDropdown = openDropdown === 'mutabaah-m' ? null : 'mutabaah-m'" class="w-full flex items-center justify-between px-3.5 py-2 rounded-xl text-slate-355 hover:bg-slate-800 hover:text-white">
                            <span>Mutabaah</span>
                            <svg class="w-3 h-3 transition-transform" :class="{ 'rotate-180': openDropdown === 'mutabaah-m' }" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </button>
                        <div x-show="openDropdown === 'mutabaah-m'" x-collapse class="pl-5 space-y-1" style="display: none;">
                            <a href="{{ route('mutabaah.reports.dashboard') }}" class="block py-1 px-3 text-xs text-slate-400 hover:text-white">Dashboard Laporan</a>
                            @if ($hasAdminOrSuperAdmin || $isTeacher)
                                <a href="{{ route('mutabaah.daily.index') }}" class="block py-1 px-3 text-xs text-slate-400 hover:text-white">Input Mutabaah</a>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Kehadiran Mobile -->
                @if ($hasInternalAccess && $canUseAttendance)
                    <div class="space-y-1">
                        <button @click="openDropdown = openDropdown === 'attendance-m' ? null : 'attendance-m'" class="w-full flex items-center justify-between px-3.5 py-2 rounded-xl text-slate-355 hover:bg-slate-800 hover:text-white">
                            <span>Kehadiran</span>
                            <svg class="w-3 h-3 transition-transform" :class="{ 'rotate-180': openDropdown === 'attendance-m' }" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </button>
                        <div x-show="openDropdown === 'attendance-m'" x-collapse class="pl-5 space-y-1" style="display: none;">
                            <a href="{{ route('attendance.reports.dashboard') }}" class="block py-1 px-3 text-xs text-slate-400 hover:text-white">Dashboard Laporan</a>
                            @if($hasAdminOrSuperAdmin)
                                <a href="{{ route('attendance.qr-cards.index') }}" class="block py-1 px-3 text-xs text-slate-400 hover:text-white">Kartu QR Siswa</a>
                            @endif
                            <a href="{{ route('attendance.scanner.index') }}" class="block py-1 px-3 text-xs text-slate-400 hover:text-white">Scanner Kehadiran</a>
                            <a href="{{ route('attendance.manual.create') }}" class="block py-1 px-3 text-xs text-slate-400 hover:text-white">Input Manual</a>
                            <a href="{{ route('attendance.sessions.index') }}" class="block py-1 px-3 text-xs text-slate-400 hover:text-white">Sesi Kehadiran</a>
                        </div>
                    </div>
                @endif
            </nav>
        </div>

        <!-- Mobile Drawer Footer -->
        <div class="p-4 border-t border-slate-750 bg-[#1F2937]/50">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center space-x-2 px-4 py-2.5 text-xs font-bold text-red-400 hover:bg-red-950/30 hover:text-red-350 border border-red-900/50 rounded-xl transition duration-200">
                    <span>Keluar</span>
                </button>
            </form>
        </div>
    </div>
</div>
