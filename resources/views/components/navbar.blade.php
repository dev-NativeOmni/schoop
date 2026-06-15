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
    $hasFinanceAccess = $hasAdminOrSuperAdmin || $isPrincipal;
    $hasBoardingAccess = $hasAdminOrSuperAdmin || $isPrincipal || $isBoardingSupervisor;
    $roleName = $user->role?->name;
    $canViewSchoolOs = in_array($roleName, ['super_admin', 'admin', 'admin_sekolah', 'kepala_sekolah', 'principal', 'teacher', 'guru', 'guru_tahfidz', 'parent', 'student'], true);
    $canManageSchoolOs = in_array($roleName, ['super_admin', 'admin', 'admin_sekolah'], true);
    $canManageTenancy = in_array($roleName, ['super_admin', 'admin', 'admin_sekolah', 'kepala_sekolah', 'principal'], true);
    $activeSchool = app(\App\Services\Tenancy\TenantContextService::class)->activeSchool();

    // Parent dynamic student parameter
    $firstChild = null;
    if ($isParent) {
        $parentProfile = $user->parentProfile;
        $firstChild = $parentProfile?->students?->first();
    }
@endphp

<nav x-data="{ open: false, openDropdown: null }" 
     class="sticky top-0 z-50 w-full border-b border-slate-750 bg-[#1F2937]/95 backdrop-blur-md shadow-lg text-white transition-all duration-300" 
     @keydown.escape="openDropdown = null">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
            <div class="flex items-center space-x-6">
                <!-- Logo -->
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-2 group shrink-0">
                    <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-[#A3E635] to-[#84cc16] text-[#1F2937] shadow-md shadow-[#A3E635]/25 group-hover:scale-105 transition-all">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <span class="text-base font-extrabold tracking-tight text-white">Hafiz<span class="text-[#A3E635]">Plus</span></span>
                </a>

                <!-- Desktop Navigation Links -->
                <div class="hidden lg:flex items-center space-x-0.5 text-xs font-semibold">
                    <!-- Dashboard -->
                    @php $active = request()->routeIs('dashboard'); @endphp
                    <a href="{{ route('dashboard') }}" class="flex flex-col items-center justify-center px-3.5 py-1.5 group transition-all rounded-xl focus:outline-none">
                        @if ($active)
                            <svg class="h-5 w-5 text-[#A3E635]" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" /></svg>
                            <span class="text-[11px] tracking-wide font-black text-[#A3E635] mt-1">Dashboard</span>
                        @else
                            <svg class="h-5 w-5 text-slate-400 group-hover:text-[#A3E635] transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                            <span class="text-[11px] tracking-wide text-slate-400 group-hover:text-white mt-1 transition-colors">Dashboard</span>
                        @endif
                    </a>

                    <!-- Al-Qur'an -->
                    @php $active = request()->routeIs('quran.*'); @endphp
                    <a href="{{ route('quran.mushaf') }}" class="flex flex-col items-center justify-center px-3.5 py-1.5 group transition-all rounded-xl focus:outline-none">
                        @if ($active)
                            <svg class="h-5 w-5 text-[#A3E635]" fill="currentColor" viewBox="0 0 20 20"><path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z" /></svg>
                            <span class="text-[11px] tracking-wide font-black text-[#A3E635] mt-1">Al-Qur'an</span>
                        @else
                            <svg class="h-5 w-5 text-slate-400 group-hover:text-[#A3E635] transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                            <span class="text-[11px] tracking-wide text-slate-400 group-hover:text-white mt-1 transition-colors">Al-Qur'an</span>
                        @endif
                    </a>

                    <!-- SchoolOS Dropdown -->
                    @if ($canViewSchoolOs)
                        @php $active = request()->routeIs('schoolos.*'); @endphp
                        <div class="relative" @click.outside="openDropdown === 'schoolos' && (openDropdown = null)">
                            <button @click="openDropdown = openDropdown === 'schoolos' ? null : 'schoolos'" class="flex flex-col items-center justify-center px-3.5 py-1.5 group transition-all rounded-xl focus:outline-none">
                                @if ($active)
                                    <svg class="h-5 w-5 text-[#A3E635]" fill="currentColor" viewBox="0 0 20 20"><path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.939.831a1 1 0 00.788 0l7-3a1 1 0 000-1.839l-7-3zM3.102 9.758a1 1 0 00-.802 1.006c.058 1.2.347 2.483.945 3.515.542.937 1.385 1.764 2.502 2.221a6.927 6.927 0 005.506 0c1.117-.457 1.96-1.284 2.502-2.221.598-1.032.887-2.316.945-3.515a1 1 0 00-.802-1.006l-4.702-.94a3.016 3.016 0 01-.788 0l-4.702.94z" /></svg>
                                    <span class="text-[11px] tracking-wide font-black text-[#A3E635] mt-1 flex items-center justify-center gap-0.5 w-full">SchoolOS <svg class="h-2.5 w-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" /></svg></span>
                                @else
                                    <svg class="h-5 w-5 text-slate-400 group-hover:text-[#A3E635] transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M4.263 8.535l7.24-3.62a.75.75 0 01.674 0l7.24 3.62a.75.75 0 010 1.342l-7.24 3.62a.75.75 0 01-.674 0L4.263 9.876a.75.75 0 010-1.342z" /><path stroke-linecap="round" stroke-linejoin="round" d="M6.31 12.593v3.136c0 .716.495 1.344 1.196 1.492a10.875 10.875 0 008.99 0c.701-.148 1.196-.776 1.196-1.492v-3.136" /></svg>
                                    <span class="text-[11px] tracking-wide text-slate-400 group-hover:text-white mt-1 transition-colors flex items-center justify-center gap-0.5 w-full">SchoolOS <svg class="h-2.5 w-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" /></svg></span>
                                @endif
                            </button>
                            <div x-show="openDropdown === 'schoolos'" x-transition:enter="transition ease-out duration-150" class="absolute left-0 mt-3 w-52 rounded-2xl border border-slate-700 bg-[#1F2937]/95 backdrop-blur-md p-2 shadow-2xl z-50 text-white" style="display: none;">
                                <a href="{{ route('schoolos.dashboard') }}" class="block rounded-xl px-3 py-2 text-slate-300 hover:bg-[#A3E635] hover:text-[#1F2937] transition font-bold text-xs">Dashboard SchoolOS</a>
                                @if ($canManageSchoolOs)
                                    <a href="{{ route('schoolos.academic-years.index') }}" class="block rounded-xl px-3 py-2 text-slate-300 hover:bg-[#A3E635] hover:text-[#1F2937] transition font-bold text-xs">Tahun Ajaran</a>
                                    <a href="{{ route('schoolos.modules.index') }}" class="block rounded-xl px-3 py-2 text-slate-300 hover:bg-[#A3E635] hover:text-[#1F2937] transition font-bold text-xs">Module Registry</a>
                                    <a href="{{ route('schoolos.settings.index') }}" class="block rounded-xl px-3 py-2 text-slate-300 hover:bg-[#A3E635] hover:text-[#1F2937] transition font-bold text-xs">School Settings</a>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Data Master Dropdown -->
                    @if ($hasAdminOrSuperAdmin)
                        @php $active = request()->routeIs('master-data.*'); @endphp
                        <div class="relative" @click.outside="openDropdown === 'master' && (openDropdown = null)">
                            <button @click="openDropdown = openDropdown === 'master' ? null : 'master'" class="flex flex-col items-center justify-center px-3.5 py-1.5 group transition-all rounded-xl focus:outline-none">
                                @if ($active)
                                    <svg class="h-5 w-5 text-[#A3E635]" fill="currentColor" viewBox="0 0 20 20"><path d="M3 12v3c0 1.657 3.134 3 7 3s7-1.343 7-3v-3c0 1.657-3.134 3-7 3s-7-1.343-7-3z" /><path d="M3 7v3c0 1.657 3.134 3 7 3s7-1.343 7-3V7c0 1.657-3.134 3-7 3S3 8.657 3 7z" /><path d="M10 2c-3.866 0-7 1.343-7 3s3.134 3 7 3 7-1.343 7-3-3.134-3-7-3z" /></svg>
                                    <span class="text-[11px] tracking-wide font-black text-[#A3E635] mt-1 flex items-center justify-center gap-0.5 w-full">Data Master <svg class="h-2.5 w-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" /></svg></span>
                                @else
                                    <svg class="h-5 w-5 text-slate-400 group-hover:text-[#A3E635] transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75M3.75 10.125v3.75m16.5 0v3.75M3.75 13.875v3.75" /></svg>
                                    <span class="text-[11px] tracking-wide text-slate-400 group-hover:text-white mt-1 transition-colors flex items-center justify-center gap-0.5 w-full">Data Master <svg class="h-2.5 w-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" /></svg></span>
                                @endif
                            </button>
                            <div x-show="openDropdown === 'master'" x-transition:enter="transition ease-out duration-150" class="absolute left-0 mt-3 w-52 rounded-2xl border border-slate-700 bg-[#1F2937]/95 backdrop-blur-md p-2 shadow-2xl z-50 text-white" style="display: none;">
                                @if ($isSuperAdmin)
                                    <a href="{{ route('master-data.schools.index') }}" class="block rounded-xl px-3 py-2 text-slate-300 hover:bg-[#A3E635] hover:text-[#1F2937] transition font-bold text-xs">Sekolah</a>
                                    <a href="{{ route('master-data.users.index') }}" class="block rounded-xl px-3 py-2 text-slate-300 hover:bg-[#A3E635] hover:text-[#1F2937] transition font-bold text-xs">Kelola User</a>
                                @endif
                                <a href="{{ route('master-data.class-rooms.index') }}" class="block rounded-xl px-3 py-2 text-slate-300 hover:bg-[#A3E635] hover:text-[#1F2937] transition font-bold text-xs">Kelas</a>
                                <a href="{{ route('master-data.teachers.index') }}" class="block rounded-xl px-3 py-2 text-slate-300 hover:bg-[#A3E635] hover:text-[#1F2937] transition font-bold text-xs">Guru</a>
                                <a href="{{ route('master-data.students.index') }}" class="block rounded-xl px-3 py-2 text-slate-300 hover:bg-[#A3E635] hover:text-[#1F2937] transition font-bold text-xs">Siswa</a>
                            </div>
                        </div>
                    @endif

                    <!-- Tenancy Dropdown -->
                    @if ($canManageTenancy)
                        @php $active = request()->routeIs('tenancy.*'); @endphp
                        <div class="relative" @click.outside="openDropdown === 'tenancy' && (openDropdown = null)">
                            <button @click="openDropdown = openDropdown === 'tenancy' ? null : 'tenancy'" class="flex flex-col items-center justify-center px-3.5 py-1.5 group transition-all rounded-xl focus:outline-none">
                                @if ($active)
                                    <svg class="h-5 w-5 text-[#A3E635]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.724 1.724 0 01-2.573 1.066c-1.543-.94-3.31.826-2.37 2.37a1.724 1.724 0 01-1.065 2.572c-1.56.38-1.56 2.6 0 2.98a1.724 1.724 0 011.066 2.573c-.94 1.543.826 3.31 2.37 2.37.996.608 2.296.07 2.572-1.065.38-1.56 2.6-1.56 2.98 0a1.724 1.724 0 012.573 1.066c1.543.94 3.31-.826 2.37-2.37.996-.608 2.296-.07 2.572 1.065.38 1.56 2.6 1.56 2.98 0a1.724 1.724 0 011.066-2.573c.94-1.543-.826-3.31-2.37-2.37.996-.608 2.296-.07 2.572 1.065z" clip-rule="evenodd" /><path fill-rule="evenodd" d="M10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" /></svg>
                                    <span class="text-[11px] tracking-wide font-black text-[#A3E635] mt-1 flex items-center justify-center gap-0.5 w-full">Tenancy <svg class="h-2.5 w-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" /></svg></span>
                                @else
                                    <svg class="h-5 w-5 text-slate-400 group-hover:text-[#A3E635] transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572 1.065z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                    <span class="text-[11px] tracking-wide text-slate-400 group-hover:text-white mt-1 transition-colors flex items-center justify-center gap-0.5 w-full">Tenancy <svg class="h-2.5 w-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" /></svg></span>
                                @endif
                            </button>
                            <div x-show="openDropdown === 'tenancy'" x-transition:enter="transition ease-out duration-150" class="absolute left-0 mt-3 w-52 rounded-2xl border border-slate-700 bg-[#1F2937]/95 backdrop-blur-md p-2 shadow-2xl z-50 text-white" style="display: none;">
                                <a href="{{ route('tenancy.dashboard') }}" class="block rounded-xl px-3 py-2 text-slate-300 hover:bg-[#A3E635] hover:text-[#1F2937] transition font-bold text-xs">Tenant Dashboard</a>
                                <a href="{{ route('tenancy.switcher') }}" class="block rounded-xl px-3 py-2 text-slate-300 hover:bg-[#A3E635] hover:text-[#1F2937] transition font-bold text-xs">Ganti Sekolah</a>
                                <a href="{{ route('tenancy.memberships.index') }}" class="block rounded-xl px-3 py-2 text-slate-300 hover:bg-[#A3E635] hover:text-[#1F2937] transition font-bold text-xs">User Memberships</a>
                                <a href="{{ route('tenancy.settings.index') }}" class="block rounded-xl px-3 py-2 text-slate-300 hover:bg-[#A3E635] hover:text-[#1F2937] transition font-bold text-xs">Tenant Settings</a>
                                <a href="{{ route('tenancy.modules.index') }}" class="block rounded-xl px-3 py-2 text-slate-300 hover:bg-[#A3E635] hover:text-[#1F2937] transition font-bold text-xs">Tenant Modules</a>
                                <a href="{{ route('tenancy.audit-logs.index') }}" class="block rounded-xl px-3 py-2 text-slate-300 hover:bg-[#A3E635] hover:text-[#1F2937] transition font-bold text-xs">Tenant Audit Logs</a>
                            </div>
                        </div>
                    @endif

                    @if ($hasInternalAccess)
                        <!-- Tahfizh Dropdown -->
                        @php $active = request()->routeIs('reports.tahfizh.*') || request()->routeIs('tahfizh.*'); @endphp
                        <div class="relative" @click.outside="openDropdown === 'tahfizh' && (openDropdown = null)">
                            <button @click="openDropdown = openDropdown === 'tahfizh' ? null : 'tahfizh'" class="flex flex-col items-center justify-center w-20 py-1.5 group transition-all rounded-lg focus:outline-none">
                                @if ($active)
                                    <svg class="h-5 w-5 text-[#A3E635]" fill="currentColor" viewBox="0 0 20 20"><path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z" /></svg>
                                    <span class="text-[11px] tracking-wide font-black text-[#A3E635] mt-1 flex items-center justify-center gap-0.5 w-full">Tahfizh <svg class="h-2.5 w-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" /></svg></span>
                                @else
                                    <svg class="h-5 w-5 text-slate-400 group-hover:text-[#A3E635] transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" /></svg>
                                    <span class="text-[11px] tracking-wide text-slate-400 group-hover:text-white mt-1 transition-colors flex items-center justify-center gap-0.5 w-full">Tahfizh <svg class="h-2.5 w-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" /></svg></span>
                                @endif
                            </button>
                            <div x-show="openDropdown === 'tahfizh'" x-transition:enter="transition ease-out duration-150" class="absolute left-0 mt-3 w-52 rounded-2xl border border-slate-700 bg-[#1F2937]/95 backdrop-blur-md p-2 shadow-2xl z-50 text-white" style="display: none;">
                                <a href="{{ route('reports.tahfizh.dashboard') }}" class="block rounded-xl px-3 py-2 text-slate-300 hover:bg-[#A3E635] hover:text-[#1F2937] transition font-bold text-xs">Dashboard Laporan</a>
                                <a href="{{ route('tahfizh.hafalan-records.index') }}" class="block rounded-xl px-3 py-2 text-slate-300 hover:bg-[#A3E635] hover:text-[#1F2937] transition font-bold text-xs">Setoran Hafalan</a>
                                @if ($hasAdminOrSuperAdmin || $isTeacher)
                                    <a href="{{ route('tahfizh.hafalan-records.create') }}" class="block rounded-xl px-3 py-2 text-slate-300 hover:bg-[#A3E635] hover:text-[#1F2937] transition font-bold text-xs">Input Setoran</a>
                                @endif
                                <a href="{{ route('tahfizh.targets.index') }}" class="block rounded-xl px-3 py-2 text-slate-300 hover:bg-[#A3E635] hover:text-[#1F2937] transition font-bold text-xs">Target Hafalan</a>
                                <a href="{{ route('tahfizh.debts.index') }}" class="block rounded-xl px-3 py-2 text-slate-300 hover:bg-[#A3E635] hover:text-[#1F2937] transition font-bold text-xs">Hutang Hafalan</a>
                                <a href="{{ route('reports.tahfizh.monthly.index') }}" class="block rounded-xl px-3 py-2 text-slate-300 hover:bg-[#A3E635] hover:text-[#1F2937] transition font-bold text-xs">Laporan Bulanan</a>
                                <a href="{{ route('reports.tahfizh.quarterly.index') }}" class="block rounded-xl px-3 py-2 text-slate-300 hover:bg-[#A3E635] hover:text-[#1F2937] transition font-bold text-xs">Laporan Triwulan</a>
                            </div>
                        </div>

                        <!-- Mutabaah Dropdown -->
                        @php $active = request()->routeIs('mutabaah.*'); @endphp
                        <div class="relative" @click.outside="openDropdown === 'mutabaah' && (openDropdown = null)">
                            <button @click="openDropdown = openDropdown === 'mutabaah' ? null : 'mutabaah'" class="flex flex-col items-center justify-center w-20 py-1.5 group transition-all rounded-lg focus:outline-none">
                                @if ($active)
                                    <svg class="h-5 w-5 text-[#A3E635]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A1 1 0 0113 2.586L18.414 8a1 1 0 01.293.707V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 2h6v2H6V6zm0 4h6v2H6v-2zm0 4h6v2H6v-2z" clip-rule="evenodd" /></svg>
                                    <span class="text-[11px] tracking-wide font-black text-[#A3E635] mt-1 flex items-center justify-center gap-0.5 w-full">Mutabaah <svg class="h-2.5 w-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" /></svg></span>
                                @else
                                    <svg class="h-5 w-5 text-slate-400 group-hover:text-[#A3E635] transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                    <span class="text-[11px] tracking-wide text-slate-400 group-hover:text-white mt-1 transition-colors flex items-center justify-center gap-0.5 w-full">Mutabaah <svg class="h-2.5 w-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" /></svg></span>
                                @endif
                            </button>
                            <div x-show="openDropdown === 'mutabaah'" x-transition:enter="transition ease-out duration-150" class="absolute left-0 mt-3 w-52 rounded-2xl border border-slate-700 bg-[#1F2937]/95 backdrop-blur-md p-2 shadow-2xl z-50 text-white" style="display: none;">
                                <a href="{{ route('mutabaah.reports.dashboard') }}" class="block rounded-xl px-3 py-2 text-slate-300 hover:bg-[#A3E635] hover:text-[#1F2937] transition font-bold text-xs">Dashboard Laporan</a>
                                @if ($hasAdminOrSuperAdmin || $isTeacher)
                                    <a href="{{ route('mutabaah.daily.index') }}" class="block rounded-xl px-3 py-2 text-slate-300 hover:bg-[#A3E635] hover:text-[#1F2937] transition font-bold text-xs">Input Mutabaah</a>
                                @endif
                                @if ($hasAdminOrSuperAdmin)
                                    <a href="{{ route('mutabaah.activities.index') }}" class="block rounded-xl px-3 py-2 text-slate-300 hover:bg-[#A3E635] hover:text-[#1F2937] transition font-bold text-xs">Kelola Aktivitas</a>
                                @endif
                            </div>
                        </div>

                        <!-- Kehadiran Dropdown -->
                        @php $active = request()->routeIs('attendance.*'); @endphp
                        <div class="relative" @click.outside="openDropdown === 'attendance' && (openDropdown = null)">
                            <button @click="openDropdown = openDropdown === 'attendance' ? null : 'attendance'" class="flex flex-col items-center justify-center w-20 py-1.5 group transition-all rounded-lg focus:outline-none">
                                @if ($active)
                                    <svg class="h-5 w-5 text-[#A3E635]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1-1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2h-1V2a1 1 0 10-2 0v1H7V2a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6zm0 4a1 1 0 100 2h8a1 1 0 100-2H6z" clip-rule="evenodd" /></svg>
                                    <span class="text-[11px] tracking-wide font-black text-[#A3E635] mt-1 flex items-center justify-center gap-0.5 w-full">Kehadiran <svg class="h-2.5 w-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" /></svg></span>
                                @else
                                    <svg class="h-5 w-5 text-slate-400 group-hover:text-[#A3E635] transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" /></svg>
                                    <span class="text-[11px] tracking-wide text-slate-400 group-hover:text-white mt-1 transition-colors flex items-center justify-center gap-0.5 w-full">Kehadiran <svg class="h-2.5 w-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" /></svg></span>
                                @endif
                            </button>
                            <div x-show="openDropdown === 'attendance'" x-transition:enter="transition ease-out duration-150" class="absolute left-0 mt-3 w-52 rounded-2xl border border-slate-700 bg-[#1F2937]/95 backdrop-blur-md p-2 shadow-2xl z-50 text-white" style="display: none;">
                                <a href="{{ route('attendance.reports.dashboard') }}" class="block rounded-xl px-3 py-2 text-slate-300 hover:bg-[#A3E635] hover:text-[#1F2937] transition font-bold text-xs">Dashboard Laporan</a>
                                <a href="{{ route('attendance.qr-cards.index') }}" class="block rounded-xl px-3 py-2 text-slate-300 hover:bg-[#A3E635] hover:text-[#1F2937] transition font-bold text-xs">Kartu QR Siswa</a>
                                <a href="{{ route('attendance.scanner.index') }}" class="block rounded-xl px-3 py-2 text-slate-300 hover:bg-[#A3E635] hover:text-[#1F2937] transition font-bold text-xs">Scanner Kehadiran</a>
                                <a href="{{ route('attendance.manual.create') }}" class="block rounded-xl px-3 py-2 text-slate-300 hover:bg-[#A3E635] hover:text-[#1F2937] transition font-bold text-xs">Input Manual</a>
                                <a href="{{ route('attendance.sessions.index') }}" class="block rounded-xl px-3 py-2 text-slate-300 hover:bg-[#A3E635] hover:text-[#1F2937] transition font-bold text-xs">Sesi Kehadiran</a>
                            </div>
                        </div>

                        <!-- Tahsin Dropdown -->
                        @php $active = request()->routeIs('tahsin.*'); @endphp
                        <div class="relative" @click.outside="openDropdown === 'tahsin' && (openDropdown = null)">
                            <button @click="openDropdown = openDropdown === 'tahsin' ? null : 'tahsin'" class="flex flex-col items-center justify-center w-20 py-1.5 group transition-all rounded-lg focus:outline-none">
                                @if ($active)
                                    <svg class="h-5 w-5 text-[#A3E635]" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                                    <span class="text-[11px] tracking-wide font-black text-[#A3E635] mt-1 flex items-center justify-center gap-0.5 w-full">Tahsin <svg class="h-2.5 w-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" /></svg></span>
                                @else
                                    <svg class="h-5 w-5 text-slate-400 group-hover:text-[#A3E635] transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 21l5.438-3.125 5.437 3.125-1.687-6.096L21 11.25l-6.219-.469L12 5.25 9.219 10.781 3 11.25l4.875 3.656z" /></svg>
                                    <span class="text-[11px] tracking-wide text-slate-400 group-hover:text-white mt-1 transition-colors flex items-center justify-center gap-0.5 w-full">Tahsin <svg class="h-2.5 w-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" /></svg></span>
                                @endif
                            </button>
                            <div x-show="openDropdown === 'tahsin'" x-transition:enter="transition ease-out duration-150" class="absolute left-0 mt-3 w-52 rounded-2xl border border-slate-700 bg-[#1F2937]/95 backdrop-blur-md p-2 shadow-2xl z-50 text-white" style="display: none;">
                                <a href="{{ route('tahsin.reports.dashboard') }}" class="block rounded-xl px-3 py-2 text-slate-300 hover:bg-[#A3E635] hover:text-[#1F2937] transition font-bold text-xs">Dashboard Laporan</a>
                                <a href="{{ route('tahsin.profiles.index') }}" class="block rounded-xl px-3 py-2 text-slate-300 hover:bg-[#A3E635] hover:text-[#1F2937] transition font-bold text-xs">Profil Tahsin Siswa</a>
                                <a href="{{ route('tahsin.assessments.index') }}" class="block rounded-xl px-3 py-2 text-slate-300 hover:bg-[#A3E635] hover:text-[#1F2937] transition font-bold text-xs">Penilaian Siswa</a>
                                @if ($hasAdminOrSuperAdmin)
                                    <a href="{{ route('tahsin.levels.index') }}" class="block rounded-xl px-3 py-2 text-slate-300 hover:bg-[#A3E635] hover:text-[#1F2937] transition font-bold text-xs">Kelola Jenjang</a>
                                    <a href="{{ route('tahsin.skills.index') }}" class="block rounded-xl px-3 py-2 text-slate-300 hover:bg-[#A3E635] hover:text-[#1F2937] transition font-bold text-xs">Kelola Keterampilan</a>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Finance Dropdown -->
                    @if ($hasFinanceAccess)
                        @php $active = request()->routeIs('finance.*'); @endphp
                        <div class="relative" @click.outside="openDropdown === 'finance' && (openDropdown = null)">
                            <button @click="openDropdown = openDropdown === 'finance' ? null : 'finance'" class="flex flex-col items-center justify-center w-20 py-1.5 group transition-all rounded-lg focus:outline-none">
                                @if ($active)
                                    <svg class="h-5 w-5 text-[#A3E635]" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4zM18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM6 11a1 1 0 110 2H5a1 1 0 110-2h1z" /></svg>
                                    <span class="text-[11px] tracking-wide font-black text-[#A3E635] mt-1 flex items-center justify-center gap-0.5 w-full">Keuangan <svg class="h-2.5 w-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" /></svg></span>
                                @else
                                    <svg class="h-5 w-5 text-slate-400 group-hover:text-[#A3E635] transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" /></svg>
                                    <span class="text-[11px] tracking-wide text-slate-400 group-hover:text-white mt-1 transition-colors flex items-center justify-center gap-0.5 w-full">Keuangan <svg class="h-2.5 w-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" /></svg></span>
                                @endif
                            </button>
                            <div x-show="openDropdown === 'finance'" x-transition:enter="transition ease-out duration-150" class="absolute left-0 mt-3 w-52 rounded-2xl border border-slate-700 bg-[#1F2937]/95 backdrop-blur-md p-2 shadow-2xl z-50 text-white" style="display: none;">
                                <a href="{{ route('finance.reports.dashboard') }}" class="block rounded-xl px-3 py-2 text-slate-300 hover:bg-[#A3E635] hover:text-[#1F2937] transition font-bold text-xs">Dashboard Laporan</a>
                                <a href="{{ route('finance.bills.index') }}" class="block rounded-xl px-3 py-2 text-slate-300 hover:bg-[#A3E635] hover:text-[#1F2937] transition font-bold text-xs">Tagihan Siswa</a>
                                <a href="{{ route('finance.payments.index') }}" class="block rounded-xl px-3 py-2 text-slate-300 hover:bg-[#A3E635] hover:text-[#1F2937] transition font-bold text-xs">Pembayaran Siswa</a>
                                @if ($hasAdminOrSuperAdmin)
                                    <a href="{{ route('finance.fee-categories.index') }}" class="block rounded-xl px-3 py-2 text-slate-300 hover:bg-[#A3E635] hover:text-[#1F2937] transition font-bold text-xs">Kategori Biaya</a>
                                    <a href="{{ route('finance.fee-items.index') }}" class="block rounded-xl px-3 py-2 text-slate-300 hover:bg-[#A3E635] hover:text-[#1F2937] transition font-bold text-xs">Item Biaya</a>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Boarding Dropdown -->
                    @if ($hasBoardingAccess)
                        @php $active = request()->routeIs('boarding.*'); @endphp
                        <div class="relative" @click.outside="openDropdown === 'boarding' && (openDropdown = null)">
                            <button @click="openDropdown = openDropdown === 'boarding' ? null : 'boarding'" class="flex flex-col items-center justify-center w-20 py-1.5 group transition-all rounded-lg focus:outline-none">
                                @if ($active)
                                    <svg class="h-5 w-5 text-[#A3E635]" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" /></svg>
                                    <span class="text-[11px] tracking-wide font-black text-[#A3E635] mt-1 flex items-center justify-center gap-0.5 w-full">Boarding <svg class="h-2.5 w-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" /></svg></span>
                                @else
                                    <svg class="h-5 w-5 text-slate-400 group-hover:text-[#A3E635] transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                                    <span class="text-[11px] tracking-wide text-slate-400 group-hover:text-white mt-1 transition-colors flex items-center justify-center gap-0.5 w-full">Boarding <svg class="h-2.5 w-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" /></svg></span>
                                @endif
                            </button>
                            <div x-show="openDropdown === 'boarding'" x-transition:enter="transition ease-out duration-150" class="absolute left-0 mt-3 w-52 rounded-2xl border border-slate-700 bg-[#1F2937]/95 backdrop-blur-md p-2 shadow-2xl z-50 text-white" style="display: none;">
                                <a href="{{ route('boarding.dashboard') }}" class="block rounded-xl px-3 py-2 text-slate-300 hover:bg-[#A3E635] hover:text-[#1F2937] transition font-bold text-xs">Dashboard Boarding</a>
                                @if ($hasAdminOrSuperAdmin)
                                    <a href="{{ route('boarding.dormitories.index') }}" class="block rounded-xl px-3 py-2 text-slate-300 hover:bg-[#A3E635] hover:text-[#1F2937] transition font-bold text-xs">Asrama</a>
                                    <a href="{{ route('boarding.rooms.index') }}" class="block rounded-xl px-3 py-2 text-slate-300 hover:bg-[#A3E635] hover:text-[#1F2937] transition font-bold text-xs">Kamar</a>
                                    <a href="{{ route('boarding.beds.index') }}" class="block rounded-xl px-3 py-2 text-slate-300 hover:bg-[#A3E635] hover:text-[#1F2937] transition font-bold text-xs">Ranjang</a>
                                    <a href="{{ route('boarding.supervisors.index') }}" class="block rounded-xl px-3 py-2 text-slate-300 hover:bg-[#A3E635] hover:text-[#1F2937] transition font-bold text-xs">Pembina Asrama</a>
                                @endif
                                <a href="{{ route('boarding.assignments.index') }}" class="block rounded-xl px-3 py-2 text-slate-300 hover:bg-[#A3E635] hover:text-[#1F2937] transition font-bold text-xs">Penempatan Santri</a>
                                <a href="{{ route('boarding.leave-requests.index') }}" class="block rounded-xl px-3 py-2 text-slate-300 hover:bg-[#A3E635] hover:text-[#1F2937] transition font-bold text-xs">Perizinan</a>
                                <a href="{{ route('boarding.health-logs.index') }}" class="block rounded-xl px-3 py-2 text-slate-300 hover:bg-[#A3E635] hover:text-[#1F2937] transition font-bold text-xs">Health Log</a>
                                <a href="{{ route('boarding.discipline-logs.index') }}" class="block rounded-xl px-3 py-2 text-slate-300 hover:bg-[#A3E635] hover:text-[#1F2937] transition font-bold text-xs">Discipline Log</a>
                                <a href="{{ route('boarding.roll-calls.index') }}" class="block rounded-xl px-3 py-2 text-slate-300 hover:bg-[#A3E635] hover:text-[#1F2937] transition font-bold text-xs">Roll Call</a>
                                <a href="{{ route('boarding.reports.dashboard') }}" class="block rounded-xl px-3 py-2 text-slate-300 hover:bg-[#A3E635] hover:text-[#1F2937] transition font-bold text-xs">Laporan Boarding</a>
                            </div>
                        </div>
                    @endif

                    <!-- Parent Portal Menu -->
                    @if ($isParent)
                        @php $active = request()->routeIs('portal.parent.*'); @endphp
                        <div class="relative" @click.outside="openDropdown === 'parent' && (openDropdown = null)">
                            <button @click="openDropdown = openDropdown === 'parent' ? null : 'parent'" class="flex flex-col items-center justify-center w-20 py-1.5 group transition-all rounded-lg focus:outline-none">
                                @if ($active)
                                    <svg class="h-5 w-5 text-[#A3E635]" fill="currentColor" viewBox="0 0 20 20"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" /></svg>
                                    <span class="text-[11px] tracking-wide font-black text-[#A3E635] mt-1 flex items-center justify-center gap-0.5 w-full">Portal Wali <svg class="h-2.5 w-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" /></svg></span>
                                @else
                                    <svg class="h-5 w-5 text-slate-400 group-hover:text-[#A3E635] transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                                    <span class="text-[11px] tracking-wide text-slate-400 group-hover:text-white mt-1 transition-colors flex items-center justify-center gap-0.5 w-full">Portal Wali <svg class="h-2.5 w-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" /></svg></span>
                                @endif
                            </button>
                            <div x-show="openDropdown === 'parent'" x-transition:enter="transition ease-out duration-150" class="absolute left-0 mt-3 w-52 rounded-2xl border border-slate-700 bg-[#1F2937]/95 backdrop-blur-md p-2 shadow-2xl z-50 text-white" style="display: none;">
                                <a href="{{ route('portal.parent.dashboard') }}" class="block rounded-xl px-3 py-2 text-slate-300 hover:bg-[#A3E635] hover:text-[#1F2937] transition font-bold text-xs">Progres Tahfizh</a>
                                @if ($firstChild)
                                    <a href="{{ route('portal.parent.mutabaah', $firstChild) }}" class="block rounded-xl px-3 py-2 text-slate-300 hover:bg-[#A3E635] hover:text-[#1F2937] transition font-bold text-xs">Mutabaah Anak</a>
                                @endif
                                <a href="{{ route('portal.parent.attendance') }}" class="block rounded-xl px-3 py-2 text-slate-300 hover:bg-[#A3E635] hover:text-[#1F2937] transition font-bold text-xs">Kehadiran Anak</a>
                                <a href="{{ route('portal.parent.tahsin') }}" class="block rounded-xl px-3 py-2 text-slate-300 hover:bg-[#A3E635] hover:text-[#1F2937] transition font-bold text-xs">Tahsin Anak</a>
                                <a href="{{ route('portal.parent.finance') }}" class="block rounded-xl px-3 py-2 text-slate-300 hover:bg-[#A3E635] hover:text-[#1F2937] transition font-bold text-xs">Keuangan Anak</a>
                                <a href="{{ route('portal.parent.boarding') }}" class="block rounded-xl px-3 py-2 text-slate-300 hover:bg-[#A3E635] hover:text-[#1F2937] transition font-bold text-xs">Boarding Anak</a>
                            </div>
                        </div>
                    @endif

                    <!-- Student Portal Menu -->
                    @if ($isStudent)
                        @php $active = request()->routeIs('portal.student.*'); @endphp
                        <div class="relative" @click.outside="openDropdown === 'student' && (openDropdown = null)">
                            <button @click="openDropdown = openDropdown === 'student' ? null : 'student'" class="flex flex-col items-center justify-center w-20 py-1.5 group transition-all rounded-lg focus:outline-none">
                                @if ($active)
                                    <svg class="h-5 w-5 text-[#A3E635]" fill="currentColor" viewBox="0 0 20 20"><path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" /></svg>
                                    <span class="text-[11px] tracking-wide font-black text-[#A3E635] mt-1 flex items-center justify-center gap-0.5 w-full">Portal Santri <svg class="h-2.5 w-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" /></svg></span>
                                @else
                                    <svg class="h-5 w-5 text-slate-400 group-hover:text-[#A3E635] transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                    <span class="text-[11px] tracking-wide text-slate-400 group-hover:text-white mt-1 transition-colors flex items-center justify-center gap-0.5 w-full">Portal Santri <svg class="h-2.5 w-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" /></svg></span>
                                @endif
                            </button>
                            <div x-show="openDropdown === 'student'" x-transition:enter="transition ease-out duration-150" class="absolute left-0 mt-3 w-52 rounded-2xl border border-slate-700 bg-[#1F2937]/95 backdrop-blur-md p-2 shadow-2xl z-50 text-white" style="display: none;">
                                <a href="{{ route('portal.student.dashboard') }}" class="block rounded-xl px-3 py-2 text-slate-300 hover:bg-[#A3E635] hover:text-[#1F2937] transition font-bold text-xs">Progres Tahfizh</a>
                                <a href="{{ route('portal.student.mutabaah') }}" class="block rounded-xl px-3 py-2 text-slate-300 hover:bg-[#A3E635] hover:text-[#1F2937] transition font-bold text-xs">Mutabaah Saya</a>
                                <a href="{{ route('portal.student.attendance') }}" class="block rounded-xl px-3 py-2 text-slate-300 hover:bg-[#A3E635] hover:text-[#1F2937] transition font-bold text-xs">Kehadiran Saya</a>
                                <a href="{{ route('portal.student.tahsin') }}" class="block rounded-xl px-3 py-2 text-slate-300 hover:bg-[#A3E635] hover:text-[#1F2937] transition font-bold text-xs">Tahsin Saya</a>
                                <a href="{{ route('portal.student.finance') }}" class="block rounded-xl px-3 py-2 text-slate-300 hover:bg-[#A3E635] hover:text-[#1F2937] transition font-bold text-xs">Keuangan Saya</a>
                                <a href="{{ route('portal.student.boarding') }}" class="block rounded-xl px-3 py-2 text-slate-300 hover:bg-[#A3E635] hover:text-[#1F2937] transition font-bold text-xs">Boarding Saya</a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right side elements -->
            <div class="flex items-center space-x-3 shrink-0">
                <!-- Active School Badge -->
                @if (isset($activeSchool) && $activeSchool)
                    <div class="hidden md:flex items-center space-x-1.5 px-3 py-1 bg-slate-800/80 border border-slate-700/60 rounded-xl text-slate-300 text-xs font-bold shadow-inner">
                        <svg class="h-3.5 w-3.5 text-[#A3E635]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <span>{{ $activeSchool->name }}</span>
                    </div>
                @endif
                <!-- Notifications Link -->
                @php $activeNotif = request()->routeIs('notifications.*'); @endphp
                <a href="{{ route('notifications.index') }}" class="relative rounded-full p-2 text-slate-400 hover:bg-slate-800 hover:text-[#A3E635] transition-colors focus:outline-none">
                    @if ($activeNotif)
                        <svg class="h-5.5 w-5.5 text-[#A3E635]" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z" /></svg>
                    @else
                        <svg class="h-5.5 w-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                    @endif
                </a>

                <!-- Dark Mode Toggle -->
                <button @click="Alpine.store('darkMode', !Alpine.store('darkMode')); localStorage.setItem('darkMode', Alpine.store('darkMode'))" class="rounded-full p-2 text-slate-400 hover:bg-slate-800 hover:text-[#A3E635] transition-colors focus:outline-none">
                    <svg x-show="!$store.darkMode" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" /></svg>
                    <svg x-show="$store.darkMode" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m0-12.728l.707.707m12.728 12.728l.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z" /></svg>
                </button>

                <!-- Avatar Dropdown Component -->
                <x-avatar />

                <!-- Mobile Hamburger Toggle -->
                <button @click="open = !open" class="rounded-xl p-2 text-slate-400 hover:bg-slate-800 hover:text-white transition-colors lg:hidden focus:outline-none">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu Overlay -->
    <div x-show="open" x-transition class="lg:hidden border-t border-slate-750 bg-[#1F2937]/95 backdrop-blur-md px-4 sm:px-6 py-4 space-y-2 text-white" style="display: none;" @click.away="open = false">
        <a href="{{ route('dashboard') }}" class="block rounded-xl px-3 py-2.5 text-sm font-semibold hover:bg-slate-800 transition">Dashboard</a>
        <a href="{{ route('quran.mushaf') }}" class="block rounded-xl px-3 py-2.5 text-sm font-semibold hover:bg-slate-800 transition">Al-Qur'an</a>

        @if ($canViewSchoolOs)
            <div class="py-1 border-t border-slate-750">
                <p class="px-3 py-1 text-[10px] font-bold text-slate-455 uppercase tracking-wider">SchoolOS</p>
                <a href="{{ route('schoolos.dashboard') }}" class="block rounded-xl px-3 py-2 text-xs text-slate-300 hover:bg-slate-800 transition">Dashboard SchoolOS</a>
                @if ($canManageSchoolOs)
                    <a href="{{ route('schoolos.academic-years.index') }}" class="block rounded-xl px-3 py-2 text-xs text-slate-300 hover:bg-slate-800 transition">Tahun Ajaran</a>
                    <a href="{{ route('schoolos.modules.index') }}" class="block rounded-xl px-3 py-2 text-xs text-slate-300 hover:bg-slate-800 transition">Module Registry</a>
                    <a href="{{ route('schoolos.settings.index') }}" class="block rounded-xl px-3 py-2 text-xs text-slate-300 hover:bg-slate-800 transition">School Settings</a>
                @endif
            </div>
        @endif

        @if ($hasAdminOrSuperAdmin)
            <div class="py-1 border-t border-slate-750">
                <p class="px-3 py-1 text-[10px] font-bold text-slate-455 uppercase tracking-wider">Data Master</p>
                @if ($isSuperAdmin)
                    <a href="{{ route('master-data.schools.index') }}" class="block rounded-xl px-3 py-2 text-xs text-slate-300 hover:bg-slate-800 transition">Sekolah</a>
                    <a href="{{ route('master-data.users.index') }}" class="block rounded-xl px-3 py-2 text-xs text-slate-300 hover:bg-slate-800 transition">Kelola User</a>
                @endif
                <a href="{{ route('master-data.class-rooms.index') }}" class="block rounded-xl px-3 py-2 text-xs text-slate-300 hover:bg-slate-800 transition">Kelas</a>
                <a href="{{ route('master-data.teachers.index') }}" class="block rounded-xl px-3 py-2 text-xs text-slate-300 hover:bg-slate-800 transition">Guru</a>
                <a href="{{ route('master-data.students.index') }}" class="block rounded-xl px-3 py-2 text-xs text-slate-300 hover:bg-slate-800 transition">Siswa</a>
            </div>
        @endif

        @if ($hasInternalAccess)
            <div class="py-1 border-t border-slate-750">
                <p class="px-3 py-1 text-[10px] font-bold text-slate-455 uppercase tracking-wider">Halaqah & Tahfizh</p>
                <a href="{{ route('reports.tahfizh.dashboard') }}" class="block rounded-xl px-3 py-2 text-xs text-slate-300 hover:bg-slate-800 transition">Dashboard Tahfizh</a>
                <a href="{{ route('tahfizh.hafalan-records.index') }}" class="block rounded-xl px-3 py-2 text-xs text-slate-300 hover:bg-slate-800 transition">Setoran Hafalan</a>
                @if ($hasAdminOrSuperAdmin || $isTeacher)
                    <a href="{{ route('tahfizh.hafalan-records.create') }}" class="block rounded-xl px-3 py-2 text-xs text-slate-300 hover:bg-slate-800 transition">Input Setoran</a>
                @endif
                <a href="{{ route('tahfizh.targets.index') }}" class="block rounded-xl px-3 py-2 text-xs text-slate-300 hover:bg-slate-800 transition">Target Hafalan</a>
                <a href="{{ route('tahfizh.debts.index') }}" class="block rounded-xl px-3 py-2 text-xs text-slate-300 hover:bg-slate-800 transition">Hutang Hafalan</a>
            </div>

            <div class="py-1 border-t border-slate-750">
                <p class="px-3 py-1 text-[10px] font-bold text-slate-455 uppercase tracking-wider">Mutabaah</p>
                <a href="{{ route('mutabaah.reports.dashboard') }}" class="block rounded-xl px-3 py-2 text-xs text-slate-300 hover:bg-slate-800 transition">Dashboard Mutabaah</a>
                @if ($hasAdminOrSuperAdmin || $isTeacher)
                    <a href="{{ route('mutabaah.daily.index') }}" class="block rounded-xl px-3 py-2 text-xs text-slate-300 hover:bg-slate-800 transition">Input Mutabaah</a>
                @endif
                @if ($hasAdminOrSuperAdmin)
                    <a href="{{ route('mutabaah.activities.index') }}" class="block rounded-xl px-3 py-2 text-xs text-slate-300 hover:bg-slate-800 transition">Kelola Aktivitas</a>
                @endif
            </div>

            <div class="py-1 border-t border-slate-750">
                <p class="px-3 py-1 text-[10px] font-bold text-slate-455 uppercase tracking-wider">Kehadiran</p>
                <a href="{{ route('attendance.reports.dashboard') }}" class="block rounded-xl px-3 py-2 text-xs text-slate-300 hover:bg-slate-800 transition">Dashboard Kehadiran</a>
                <a href="{{ route('attendance.qr-cards.index') }}" class="block rounded-xl px-3 py-2 text-xs text-slate-300 hover:bg-slate-800 transition">Kartu QR Siswa</a>
                <a href="{{ route('attendance.scanner.index') }}" class="block rounded-xl px-3 py-2 text-xs text-slate-300 hover:bg-slate-800 transition">Scanner Kehadiran</a>
                <a href="{{ route('attendance.manual.create') }}" class="block rounded-xl px-3 py-2 text-xs text-slate-300 hover:bg-slate-800 transition">Input Manual</a>
                <a href="{{ route('attendance.sessions.index') }}" class="block rounded-xl px-3 py-2 text-xs text-slate-300 hover:bg-slate-800 transition">Sesi Kehadiran</a>
            </div>

            <div class="py-1 border-t border-slate-750">
                <p class="px-3 py-1 text-[10px] font-bold text-slate-455 uppercase tracking-wider">Tahsin</p>
                <a href="{{ route('tahsin.reports.dashboard') }}" class="block rounded-xl px-3 py-2 text-xs text-slate-300 hover:bg-slate-800 transition">Dashboard Tahsin</a>
                <a href="{{ route('tahsin.profiles.index') }}" class="block rounded-xl px-3 py-2 text-xs text-slate-300 hover:bg-slate-800 transition">Profil Tahsin Siswa</a>
                <a href="{{ route('tahsin.assessments.index') }}" class="block rounded-xl px-3 py-2 text-xs text-slate-300 hover:bg-slate-800 transition">Penilaian Siswa</a>
                @if ($hasAdminOrSuperAdmin)
                    <a href="{{ route('tahsin.levels.index') }}" class="block rounded-xl px-3 py-2 text-xs text-slate-300 hover:bg-slate-800 transition">Kelola Jenjang</a>
                    <a href="{{ route('tahsin.skills.index') }}" class="block rounded-xl px-3 py-2 text-xs text-slate-300 hover:bg-slate-800 transition">Kelola Keterampilan</a>
                @endif
            </div>
        @endif

        @if ($hasFinanceAccess)
            <div class="py-1 border-t border-slate-750">
                <p class="px-3 py-1 text-[10px] font-bold text-slate-455 uppercase tracking-wider">Keuangan</p>
                <a href="{{ route('finance.reports.dashboard') }}" class="block rounded-xl px-3 py-2 text-xs text-slate-300 hover:bg-slate-800 transition">Dashboard Keuangan</a>
                <a href="{{ route('finance.bills.index') }}" class="block rounded-xl px-3 py-2 text-xs text-slate-300 hover:bg-slate-800 transition">Tagihan Siswa</a>
                <a href="{{ route('finance.payments.index') }}" class="block rounded-xl px-3 py-2 text-xs text-slate-300 hover:bg-slate-800 transition">Pembayaran Siswa</a>
                @if ($hasAdminOrSuperAdmin)
                    <a href="{{ route('finance.fee-categories.index') }}" class="block rounded-xl px-3 py-2 text-xs text-slate-300 hover:bg-slate-800 transition">Kategori Biaya</a>
                    <a href="{{ route('finance.fee-items.index') }}" class="block rounded-xl px-3 py-2 text-xs text-slate-300 hover:bg-slate-800 transition">Item Biaya</a>
                @endif
            </div>
        @endif

        @if ($hasBoardingAccess)
            <div class="py-1 border-t border-slate-750">
                <p class="px-3 py-1 text-[10px] font-bold text-slate-455 uppercase tracking-wider">Boarding</p>
                <a href="{{ route('boarding.dashboard') }}" class="block rounded-xl px-3 py-2 text-xs text-slate-300 hover:bg-slate-800 transition">Dashboard Boarding</a>
                @if ($hasAdminOrSuperAdmin)
                    <a href="{{ route('boarding.dormitories.index') }}" class="block rounded-xl px-3 py-2 text-xs text-slate-300 hover:bg-slate-800 transition">Asrama</a>
                    <a href="{{ route('boarding.rooms.index') }}" class="block rounded-xl px-3 py-2 text-xs text-slate-300 hover:bg-slate-800 transition">Kamar</a>
                    <a href="{{ route('boarding.beds.index') }}" class="block rounded-xl px-3 py-2 text-xs text-slate-300 hover:bg-slate-800 transition">Ranjang</a>
                    <a href="{{ route('boarding.supervisors.index') }}" class="block rounded-xl px-3 py-2 text-xs text-slate-300 hover:bg-slate-800 transition">Pembina Asrama</a>
                @endif
                <a href="{{ route('boarding.assignments.index') }}" class="block rounded-xl px-3 py-2 text-xs text-slate-300 hover:bg-slate-800 transition">Penempatan Santri</a>
                <a href="{{ route('boarding.leave-requests.index') }}" class="block rounded-xl px-3 py-2 text-xs text-slate-300 hover:bg-slate-800 transition">Perizinan</a>
                <a href="{{ route('boarding.health-logs.index') }}" class="block rounded-xl px-3 py-2 text-xs text-slate-300 hover:bg-slate-800 transition">Health Log</a>
                <a href="{{ route('boarding.discipline-logs.index') }}" class="block rounded-xl px-3 py-2 text-xs text-slate-300 hover:bg-slate-800 transition">Discipline Log</a>
                <a href="{{ route('boarding.roll-calls.index') }}" class="block rounded-xl px-3 py-2 text-xs text-slate-300 hover:bg-slate-800 transition">Roll Call</a>
                <a href="{{ route('boarding.reports.dashboard') }}" class="block rounded-xl px-3 py-2 text-xs text-slate-300 hover:bg-slate-800 transition">Laporan Boarding</a>
            </div>
        @endif

        @if ($isParent)
            <div class="py-1 border-t border-slate-750">
                <p class="px-3 py-1 text-[10px] font-bold text-slate-455 uppercase tracking-wider">Portal Wali</p>
                <a href="{{ route('portal.parent.dashboard') }}" class="block rounded-xl px-3 py-2 text-xs text-slate-300 hover:bg-slate-800 transition">Progres Tahfizh</a>
                @if ($firstChild)
                    <a href="{{ route('portal.parent.mutabaah', $firstChild) }}" class="block rounded-xl px-3 py-2 text-xs text-slate-300 hover:bg-slate-800 transition">Mutabaah Anak</a>
                @endif
                <a href="{{ route('portal.parent.attendance') }}" class="block rounded-xl px-3 py-2 text-xs text-slate-300 hover:bg-slate-800 transition">Kehadiran Anak</a>
                <a href="{{ route('portal.parent.tahsin') }}" class="block rounded-xl px-3 py-2 text-xs text-slate-300 hover:bg-slate-800 transition">Tahsin Anak</a>
                <a href="{{ route('portal.parent.finance') }}" class="block rounded-xl px-3 py-2 text-xs text-slate-300 hover:bg-slate-800 transition">Keuangan Anak</a>
                <a href="{{ route('portal.parent.boarding') }}" class="block rounded-xl px-3 py-2 text-xs text-slate-300 hover:bg-slate-800 transition">Boarding Anak</a>
            </div>
        @endif

        @if ($isStudent)
            <div class="py-1 border-t border-slate-750">
                <p class="px-3 py-1 text-[10px] font-bold text-slate-455 uppercase tracking-wider">Portal Santri</p>
                <a href="{{ route('portal.student.dashboard') }}" class="block rounded-xl px-3 py-2 text-xs text-slate-300 hover:bg-slate-800 transition">Progres Tahfizh</a>
                <a href="{{ route('portal.student.mutabaah') }}" class="block rounded-xl px-3 py-2 text-xs text-slate-300 hover:bg-slate-800 transition">Mutabaah Saya</a>
                <a href="{{ route('portal.student.attendance') }}" class="block rounded-xl px-3 py-2 text-xs text-slate-300 hover:bg-slate-800 transition">Kehadiran Saya</a>
                <a href="{{ route('portal.student.tahsin') }}" class="block rounded-xl px-3 py-2 text-xs text-slate-300 hover:bg-slate-800 transition">Tahsin Saya</a>
                <a href="{{ route('portal.student.finance') }}" class="block rounded-xl px-3 py-2 text-xs text-slate-300 hover:bg-slate-800 transition">Keuangan Saya</a>
                <a href="{{ route('portal.student.boarding') }}" class="block rounded-xl px-3 py-2 text-xs text-slate-300 hover:bg-slate-800 transition">Boarding Saya</a>
            </div>
        @endif
    </div>
</nav>
