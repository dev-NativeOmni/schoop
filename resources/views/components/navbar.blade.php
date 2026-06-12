@php
    $user = auth()->user();
    $isSuperAdmin = $user->isSuperAdmin();
    $isAdmin = $user->isAdmin();
    $isPrincipal = $user->isPrincipal();
    $isTeacher = $user->isTeacher();
    $isParent = $user->isParent();
    $isStudent = $user->isStudent();

    $hasAdminOrSuperAdmin = $isSuperAdmin || $isAdmin;
    $hasInternalAccess = $hasAdminOrSuperAdmin || $isTeacher || $isPrincipal;
    $hasFinanceAccess = $hasAdminOrSuperAdmin || $isPrincipal;
    $roleName = $user->role?->name;
    $canViewSchoolOs = in_array($roleName, ['super_admin', 'admin', 'admin_sekolah', 'kepala_sekolah', 'principal', 'teacher', 'guru', 'guru_tahfidz', 'parent', 'student'], true);
    $canManageSchoolOs = in_array($roleName, ['super_admin', 'admin', 'admin_sekolah'], true);

    // Parent dynamic student parameter
    $firstChild = null;
    if ($isParent) {
        $parentProfile = $user->parentProfile;
        $firstChild = $parentProfile?->students?->first();
    }
@endphp

<nav x-data="{ open: false, openDropdown: null }" class="border-b border-slate-200/80 bg-white/80 backdrop-blur-md sticky top-0 z-50 shadow-sm transition-all duration-300 dark:border-slate-800/80 dark:bg-slate-900/80" @keydown.escape="openDropdown = null">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
            <div class="flex items-center space-x-8">
                <!-- Logo -->
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-2 group">
                    <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 text-white shadow-md shadow-indigo-200 group-hover:scale-105 transition-all dark:shadow-none">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <span class="text-xl font-bold tracking-tight text-slate-800 dark:text-slate-100">Hafiz<span class="text-indigo-600 dark:text-indigo-400">Plus</span></span>
                </a>

                <!-- Desktop Navigation -->
                <div class="hidden lg:flex items-center space-x-1 text-sm font-medium">
                    <!-- Dashboard Link -->
                    <a href="{{ route('dashboard') }}" class="rounded-lg px-3 py-2 text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-slate-100">
                        Dashboard
                    </a>

                    @if ($canViewSchoolOs)
                        <div class="relative" @click.outside="openDropdown === 'schoolos' && (openDropdown = null)">
                            <button @click="openDropdown = openDropdown === 'schoolos' ? null : 'schoolos'" class="flex items-center space-x-1 rounded-lg px-3 py-2 text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-slate-100">
                                <span>SchoolOS</span>
                                <svg class="h-4 w-4 transition-transform duration-200" :class="{ 'rotate-180': openDropdown === 'schoolos' }" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                            </button>
                            <div x-show="openDropdown === 'schoolos'" x-transition:enter="transition ease-out duration-150" class="absolute left-0 mt-2 w-56 rounded-xl border border-slate-100 bg-white p-2 shadow-xl dark:border-slate-800 dark:bg-slate-850" style="display: none;">
                                <a href="{{ route('schoolos.dashboard') }}" class="block rounded-lg px-3 py-2 text-slate-700 hover:bg-slate-50 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800">Dashboard SchoolOS</a>
                                @if ($canManageSchoolOs)
                                    <a href="{{ route('schoolos.academic-years.index') }}" class="block rounded-lg px-3 py-2 text-slate-700 hover:bg-slate-50 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800">Tahun Ajaran</a>
                                    <a href="{{ route('schoolos.modules.index') }}" class="block rounded-lg px-3 py-2 text-slate-700 hover:bg-slate-50 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800">Module Registry</a>
                                    <a href="{{ route('schoolos.settings.index') }}" class="block rounded-lg px-3 py-2 text-slate-700 hover:bg-slate-50 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800">School Settings</a>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Admin: Master Data Dropdown -->
                    @if ($hasAdminOrSuperAdmin)
                        <div class="relative" @click.outside="openDropdown === 'master' && (openDropdown = null)">
                            <button @click="openDropdown = openDropdown === 'master' ? null : 'master'" class="flex items-center space-x-1 rounded-lg px-3 py-2 text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-slate-100">
                                <span>Data Master</span>
                                <svg class="h-4 w-4 transition-transform duration-200" :class="{ 'rotate-180': openDropdown === 'master' }" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                            </button>
                            <div x-show="openDropdown === 'master'" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-1" class="absolute left-0 mt-2 w-56 rounded-xl border border-slate-100 bg-white p-2 shadow-xl dark:border-slate-800 dark:bg-slate-850" style="display: none;">
                                @if ($isSuperAdmin)
                                    <a href="{{ route('master-data.schools.index') }}" class="block rounded-lg px-3 py-2 text-slate-700 hover:bg-slate-50 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800">Sekolah</a>
                                @endif
                                <a href="{{ route('master-data.class-rooms.index') }}" class="block rounded-lg px-3 py-2 text-slate-700 hover:bg-slate-50 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800">Kelas</a>
                                <a href="{{ route('master-data.teachers.index') }}" class="block rounded-lg px-3 py-2 text-slate-700 hover:bg-slate-50 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800">Guru</a>
                                <a href="{{ route('master-data.students.index') }}" class="block rounded-lg px-3 py-2 text-slate-700 hover:bg-slate-50 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800">Siswa</a>
                            </div>
                        </div>
                    @endif

                    <!-- Internal Access: Tahfizh, Mutabaah, Attendance, Tahsin -->
                    @if ($hasInternalAccess)
                        <!-- Tahfizh Dropdown -->
                        <div class="relative" @click.outside="openDropdown === 'tahfizh' && (openDropdown = null)">
                            <button @click="openDropdown = openDropdown === 'tahfizh' ? null : 'tahfizh'" class="flex items-center space-x-1 rounded-lg px-3 py-2 text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-slate-100">
                                <span>Tahfizh</span>
                                <svg class="h-4 w-4 transition-transform duration-200" :class="{ 'rotate-180': openDropdown === 'tahfizh' }" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                            </button>
                            <div x-show="openDropdown === 'tahfizh'" x-transition:enter="transition ease-out duration-150" class="absolute left-0 mt-2 w-56 rounded-xl border border-slate-100 bg-white p-2 shadow-xl dark:border-slate-800 dark:bg-slate-850" style="display: none;">
                                <a href="{{ route('reports.tahfizh.dashboard') }}" class="block rounded-lg px-3 py-2 text-slate-700 hover:bg-slate-50 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800">Dashboard Laporan</a>
                                <a href="{{ route('tahfizh.hafalan-records.index') }}" class="block rounded-lg px-3 py-2 text-slate-700 hover:bg-slate-50 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800">Setoran Hafalan</a>
                                @if ($hasAdminOrSuperAdmin || $isTeacher)
                                    <a href="{{ route('tahfizh.hafalan-records.create') }}" class="block rounded-lg px-3 py-2 text-slate-700 hover:bg-slate-50 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800">Input Setoran</a>
                                @endif
                                <a href="{{ route('tahfizh.targets.index') }}" class="block rounded-lg px-3 py-2 text-slate-700 hover:bg-slate-50 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800">Target Hafalan</a>
                                <a href="{{ route('tahfizh.debts.index') }}" class="block rounded-lg px-3 py-2 text-slate-700 hover:bg-slate-50 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800">Hutang Hafalan</a>
                                <a href="{{ route('reports.tahfizh.monthly.index') }}" class="block rounded-lg px-3 py-2 text-slate-700 hover:bg-slate-50 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800">Laporan Bulanan</a>
                                <a href="{{ route('reports.tahfizh.quarterly.index') }}" class="block rounded-lg px-3 py-2 text-slate-700 hover:bg-slate-50 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800">Laporan Triwulan</a>
                            </div>
                        </div>

                        <!-- Mutabaah Dropdown -->
                        <div class="relative" @click.outside="openDropdown === 'mutabaah' && (openDropdown = null)">
                            <button @click="openDropdown = openDropdown === 'mutabaah' ? null : 'mutabaah'" class="flex items-center space-x-1 rounded-lg px-3 py-2 text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-slate-100">
                                <span>Mutabaah</span>
                                <svg class="h-4 w-4 transition-transform duration-200" :class="{ 'rotate-180': openDropdown === 'mutabaah' }" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                            </button>
                            <div x-show="openDropdown === 'mutabaah'" class="absolute left-0 mt-2 w-56 rounded-xl border border-slate-100 bg-white p-2 shadow-xl dark:border-slate-800 dark:bg-slate-850" style="display: none;">
                                <a href="{{ route('mutabaah.reports.dashboard') }}" class="block rounded-lg px-3 py-2 text-slate-700 hover:bg-slate-50 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800">Dashboard Laporan</a>
                                @if ($hasAdminOrSuperAdmin || $isTeacher)
                                    <a href="{{ route('mutabaah.daily.index') }}" class="block rounded-lg px-3 py-2 text-slate-700 hover:bg-slate-50 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800">Input Mutabaah</a>
                                @endif
                                @if ($hasAdminOrSuperAdmin)
                                    <a href="{{ route('mutabaah.activities.index') }}" class="block rounded-lg px-3 py-2 text-slate-700 hover:bg-slate-50 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800">Kelola Aktivitas</a>
                                @endif
                            </div>
                        </div>

                        <!-- Kehadiran (Attendance) Dropdown -->
                        <div class="relative" @click.outside="openDropdown === 'attendance' && (openDropdown = null)">
                            <button @click="openDropdown = openDropdown === 'attendance' ? null : 'attendance'" class="flex items-center space-x-1 rounded-lg px-3 py-2 text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-slate-100">
                                <span>Kehadiran</span>
                                <svg class="h-4 w-4 transition-transform duration-200" :class="{ 'rotate-180': openDropdown === 'attendance' }" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                            </button>
                            <div x-show="openDropdown === 'attendance'" class="absolute left-0 mt-2 w-56 rounded-xl border border-slate-100 bg-white p-2 shadow-xl dark:border-slate-800 dark:bg-slate-850" style="display: none;">
                                <a href="{{ route('attendance.reports.dashboard') }}" class="block rounded-lg px-3 py-2 text-slate-700 hover:bg-slate-50 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800">Dashboard Laporan</a>
                                <a href="{{ route('attendance.qr-cards.index') }}" class="block rounded-lg px-3 py-2 text-slate-700 hover:bg-slate-50 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800">Kartu QR Siswa</a>
                                <a href="{{ route('attendance.scanner.index') }}" class="block rounded-lg px-3 py-2 text-slate-700 hover:bg-slate-50 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800">Scanner Kehadiran</a>
                                <a href="{{ route('attendance.manual.create') }}" class="block rounded-lg px-3 py-2 text-slate-700 hover:bg-slate-50 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800">Input Manual</a>
                                <a href="{{ route('attendance.sessions.index') }}" class="block rounded-lg px-3 py-2 text-slate-700 hover:bg-slate-50 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800">Sesi Kehadiran</a>
                            </div>
                        </div>

                        <!-- Tahsin Dropdown -->
                        <div class="relative" @click.outside="openDropdown === 'tahsin' && (openDropdown = null)">
                            <button @click="openDropdown = openDropdown === 'tahsin' ? null : 'tahsin'" class="flex items-center space-x-1 rounded-lg px-3 py-2 text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-slate-100">
                                <span>Tahsin</span>
                                <svg class="h-4 w-4 transition-transform duration-200" :class="{ 'rotate-180': openDropdown === 'tahsin' }" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                            </button>
                            <div x-show="openDropdown === 'tahsin'" class="absolute left-0 mt-2 w-56 rounded-xl border border-slate-100 bg-white p-2 shadow-xl dark:border-slate-800 dark:bg-slate-850" style="display: none;">
                                <a href="{{ route('tahsin.reports.dashboard') }}" class="block rounded-lg px-3 py-2 text-slate-700 hover:bg-slate-50 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800">Dashboard Laporan</a>
                                <a href="{{ route('tahsin.profiles.index') }}" class="block rounded-lg px-3 py-2 text-slate-700 hover:bg-slate-50 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800">Profil Tahsin Siswa</a>
                                <a href="{{ route('tahsin.assessments.index') }}" class="block rounded-lg px-3 py-2 text-slate-700 hover:bg-slate-50 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800">Penilaian Siswa</a>
                                @if ($hasAdminOrSuperAdmin)
                                    <a href="{{ route('tahsin.levels.index') }}" class="block rounded-lg px-3 py-2 text-slate-700 hover:bg-slate-50 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800">Kelola Jenjang</a>
                                    <a href="{{ route('tahsin.skills.index') }}" class="block rounded-lg px-3 py-2 text-slate-700 hover:bg-slate-50 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800">Kelola Keterampilan</a>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Finance Dropdown -->
                    @if ($hasFinanceAccess)
                        <div class="relative" @click.outside="openDropdown === 'finance' && (openDropdown = null)">
                            <button @click="openDropdown = openDropdown === 'finance' ? null : 'finance'" class="flex items-center space-x-1 rounded-lg px-3 py-2 text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-slate-100">
                                <span>Keuangan</span>
                                <svg class="h-4 w-4 transition-transform duration-200" :class="{ 'rotate-180': openDropdown === 'finance' }" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                            </button>
                            <div x-show="openDropdown === 'finance'" class="absolute left-0 mt-2 w-56 rounded-xl border border-slate-100 bg-white p-2 shadow-xl dark:border-slate-800 dark:bg-slate-850" style="display: none;">
                                <a href="{{ route('finance.reports.dashboard') }}" class="block rounded-lg px-3 py-2 text-slate-700 hover:bg-slate-50 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800">Dashboard Laporan</a>
                                <a href="{{ route('finance.bills.index') }}" class="block rounded-lg px-3 py-2 text-slate-700 hover:bg-slate-50 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800">Tagihan Siswa</a>
                                <a href="{{ route('finance.payments.index') }}" class="block rounded-lg px-3 py-2 text-slate-700 hover:bg-slate-50 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800">Pembayaran Siswa</a>
                                @if ($hasAdminOrSuperAdmin)
                                    <a href="{{ route('finance.fee-categories.index') }}" class="block rounded-lg px-3 py-2 text-slate-700 hover:bg-slate-50 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800">Kategori Biaya</a>
                                    <a href="{{ route('finance.fee-items.index') }}" class="block rounded-lg px-3 py-2 text-slate-700 hover:bg-slate-50 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800">Item Biaya</a>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Parent Portal Menu -->
                    @if ($isParent)
                        <a href="{{ route('portal.parent.dashboard') }}" class="rounded-lg px-3 py-2 text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-slate-100">
                            Progres Tahfizh
                        </a>
                        @if ($firstChild)
                            <a href="{{ route('portal.parent.mutabaah', $firstChild) }}" class="rounded-lg px-3 py-2 text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-slate-100">
                                Mutabaah Anak
                            </a>
                        @endif
                        <a href="{{ route('portal.parent.attendance') }}" class="rounded-lg px-3 py-2 text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-slate-100">
                            Kehadiran Anak
                        </a>
                        <a href="{{ route('portal.parent.tahsin') }}" class="rounded-lg px-3 py-2 text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-slate-100">
                            Tahsin Anak
                        </a>
                        <a href="{{ route('portal.parent.finance') }}" class="rounded-lg px-3 py-2 text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-slate-100">
                            Keuangan Anak
                        </a>
                    @endif

                    <!-- Student Portal Menu -->
                    @if ($isStudent)
                        <a href="{{ route('portal.student.dashboard') }}" class="rounded-lg px-3 py-2 text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-slate-100">
                            Progres Tahfizh
                        </a>
                        <a href="{{ route('portal.student.mutabaah') }}" class="rounded-lg px-3 py-2 text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-slate-100">
                            Mutabaah Saya
                        </a>
                        <a href="{{ route('portal.student.attendance') }}" class="rounded-lg px-3 py-2 text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-slate-100">
                            Kehadiran Saya
                        </a>
                        <a href="{{ route('portal.student.tahsin') }}" class="rounded-lg px-3 py-2 text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-slate-100">
                            Tahsin Saya
                        </a>
                        <a href="{{ route('portal.student.finance') }}" class="rounded-lg px-3 py-2 text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-slate-100">
                            Keuangan Saya
                        </a>
                    @endif
                </div>
            </div>

            <div class="flex items-center space-x-4">
                <!-- Notifications Link -->
                <a href="{{ route('notifications.index') }}" class="relative rounded-full p-2 text-slate-500 hover:bg-slate-50 hover:text-slate-800 transition dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-slate-200">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                </a>

                <!-- Dark Mode Toggle -->
                <button @click="Alpine.store('darkMode', !Alpine.store('darkMode')); localStorage.setItem('darkMode', Alpine.store('darkMode'))" class="rounded-full p-2 text-slate-500 hover:bg-slate-50 hover:text-slate-800 transition dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-slate-200">
                    <svg x-show="!$store.darkMode" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" /></svg>
                    <svg x-show="$store.darkMode" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m0-12.728l.707.707m12.728 12.728l.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z" /></svg>
                </button>

                <!-- Avatar Dropdown -->
                <x-avatar />

                <!-- Mobile Hamburger -->
                <button @click="open = !open" class="rounded-lg p-2 text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition dark:text-slate-400 dark:hover:bg-slate-800 lg:hidden focus:outline-none">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div x-show="open" x-transition class="lg:hidden border-t border-slate-100 bg-white/95 backdrop-blur-md px-4 py-3 space-y-1 dark:border-slate-800 dark:bg-slate-900/95" style="display: none;" @click.away="open = false">
        <a href="{{ route('dashboard') }}" class="block rounded-lg px-3 py-2 text-base font-semibold text-slate-700 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800">Dashboard</a>

        @if ($canViewSchoolOs)
            <div class="py-2 border-t border-slate-100 dark:border-slate-800">
                <p class="px-3 text-xs font-semibold text-slate-450 uppercase tracking-wider">SchoolOS</p>
                <a href="{{ route('schoolos.dashboard') }}" class="block rounded-lg px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800">Dashboard SchoolOS</a>
                @if ($canManageSchoolOs)
                    <a href="{{ route('schoolos.academic-years.index') }}" class="block rounded-lg px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800">Tahun Ajaran</a>
                    <a href="{{ route('schoolos.modules.index') }}" class="block rounded-lg px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800">Module Registry</a>
                    <a href="{{ route('schoolos.settings.index') }}" class="block rounded-lg px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800">School Settings</a>
                @endif
            </div>
        @endif

        @if ($hasAdminOrSuperAdmin)
            <div class="py-2 border-t border-slate-100 dark:border-slate-800">
                <p class="px-3 text-xs font-semibold text-slate-450 uppercase tracking-wider">Data Master</p>
                @if ($isSuperAdmin)
                    <a href="{{ route('master-data.schools.index') }}" class="block rounded-lg px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800">Sekolah</a>
                @endif
                <a href="{{ route('master-data.class-rooms.index') }}" class="block rounded-lg px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800">Kelas</a>
                <a href="{{ route('master-data.teachers.index') }}" class="block rounded-lg px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800">Guru</a>
                <a href="{{ route('master-data.students.index') }}" class="block rounded-lg px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800">Siswa</a>
            </div>
        @endif

        @if ($hasInternalAccess)
            <div class="py-2 border-t border-slate-100 dark:border-slate-800">
                <p class="px-3 text-xs font-semibold text-slate-450 uppercase tracking-wider">Halaqah & Tahfizh</p>
                <a href="{{ route('reports.tahfizh.dashboard') }}" class="block rounded-lg px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800">Dashboard Tahfizh</a>
                <a href="{{ route('tahfizh.hafalan-records.index') }}" class="block rounded-lg px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800">Setoran Hafalan</a>
                @if ($hasAdminOrSuperAdmin || $isTeacher)
                    <a href="{{ route('tahfizh.hafalan-records.create') }}" class="block rounded-lg px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800">Input Setoran</a>
                @endif
                <a href="{{ route('tahfizh.targets.index') }}" class="block rounded-lg px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800">Target Hafalan</a>
                <a href="{{ route('tahfizh.debts.index') }}" class="block rounded-lg px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800">Hutang Hafalan</a>
            </div>

            <div class="py-2 border-t border-slate-100 dark:border-slate-800">
                <p class="px-3 text-xs font-semibold text-slate-450 uppercase tracking-wider">Mutabaah</p>
                <a href="{{ route('mutabaah.reports.dashboard') }}" class="block rounded-lg px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800">Dashboard Mutabaah</a>
                @if ($hasAdminOrSuperAdmin || $isTeacher)
                    <a href="{{ route('mutabaah.daily.index') }}" class="block rounded-lg px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800">Input Mutabaah</a>
                @endif
                @if ($hasAdminOrSuperAdmin)
                    <a href="{{ route('mutabaah.activities.index') }}" class="block rounded-lg px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800">Kelola Aktivitas</a>
                @endif
            </div>

            <div class="py-2 border-t border-slate-100 dark:border-slate-800">
                <p class="px-3 text-xs font-semibold text-slate-450 uppercase tracking-wider">Kehadiran</p>
                <a href="{{ route('attendance.reports.dashboard') }}" class="block rounded-lg px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800">Dashboard Kehadiran</a>
                <a href="{{ route('attendance.qr-cards.index') }}" class="block rounded-lg px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800">Kartu QR Siswa</a>
                <a href="{{ route('attendance.scanner.index') }}" class="block rounded-lg px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800">Scanner Kehadiran</a>
                <a href="{{ route('attendance.manual.create') }}" class="block rounded-lg px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800">Input Manual</a>
                <a href="{{ route('attendance.sessions.index') }}" class="block rounded-lg px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800">Sesi Kehadiran</a>
            </div>

            <div class="py-2 border-t border-slate-100 dark:border-slate-800">
                <p class="px-3 text-xs font-semibold text-slate-450 uppercase tracking-wider">Tahsin</p>
                <a href="{{ route('tahsin.reports.dashboard') }}" class="block rounded-lg px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800">Dashboard Tahsin</a>
                <a href="{{ route('tahsin.profiles.index') }}" class="block rounded-lg px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800">Profil Tahsin Siswa</a>
                <a href="{{ route('tahsin.assessments.index') }}" class="block rounded-lg px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800">Penilaian Siswa</a>
                @if ($hasAdminOrSuperAdmin)
                    <a href="{{ route('tahsin.levels.index') }}" class="block rounded-lg px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800">Kelola Jenjang</a>
                    <a href="{{ route('tahsin.skills.index') }}" class="block rounded-lg px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800">Kelola Keterampilan</a>
                @endif
            </div>
        @endif

        @if ($hasFinanceAccess)
            <div class="py-2 border-t border-slate-100 dark:border-slate-800">
                <p class="px-3 text-xs font-semibold text-slate-450 uppercase tracking-wider">Keuangan</p>
                <a href="{{ route('finance.reports.dashboard') }}" class="block rounded-lg px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800">Dashboard Keuangan</a>
                <a href="{{ route('finance.bills.index') }}" class="block rounded-lg px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800">Tagihan Siswa</a>
                <a href="{{ route('finance.payments.index') }}" class="block rounded-lg px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800">Pembayaran Siswa</a>
                @if ($hasAdminOrSuperAdmin)
                    <a href="{{ route('finance.fee-categories.index') }}" class="block rounded-lg px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800">Kategori Biaya</a>
                    <a href="{{ route('finance.fee-items.index') }}" class="block rounded-lg px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800">Item Biaya</a>
                @endif
            </div>
        @endif

        @if ($isParent)
            <div class="py-2 border-t border-slate-100 dark:border-slate-800">
                <p class="px-3 text-xs font-semibold text-slate-450 uppercase tracking-wider">Portal Wali</p>
                <a href="{{ route('portal.parent.dashboard') }}" class="block rounded-lg px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800">Progres Tahfizh</a>
                @if ($firstChild)
                    <a href="{{ route('portal.parent.mutabaah', $firstChild) }}" class="block rounded-lg px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800">Mutabaah Anak</a>
                @endif
                <a href="{{ route('portal.parent.attendance') }}" class="block rounded-lg px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800">Kehadiran Anak</a>
                <a href="{{ route('portal.parent.tahsin') }}" class="block rounded-lg px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800">Tahsin Anak</a>
                <a href="{{ route('portal.parent.finance') }}" class="block rounded-lg px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800">Keuangan Anak</a>
            </div>
        @endif

        @if ($isStudent)
            <div class="py-2 border-t border-slate-100 dark:border-slate-800">
                <p class="px-3 text-xs font-semibold text-slate-450 uppercase tracking-wider">Portal Santri</p>
                <a href="{{ route('portal.student.dashboard') }}" class="block rounded-lg px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800">Progres Tahfizh</a>
                <a href="{{ route('portal.student.mutabaah') }}" class="block rounded-lg px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800">Mutabaah Saya</a>
                <a href="{{ route('portal.student.attendance') }}" class="block rounded-lg px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800">Kehadiran Saya</a>
                <a href="{{ route('portal.student.tahsin') }}" class="block rounded-lg px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800">Tahsin Saya</a>
                <a href="{{ route('portal.student.finance') }}" class="block rounded-lg px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800">Keuangan Saya</a>
            </div>
        @endif
    </div>
</nav>
