<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'HafizPlus School Platform') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 text-slate-900">
    <div class="min-h-screen">
        @auth
            <header class="border-b bg-white">
                <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4">
                    <div>
                        <h1 class="text-lg font-bold">HafizPlus School Platform</h1>
                        <p class="text-sm text-slate-500">
                            {{ auth()->user()->name }} —
                            {{ auth()->user()->role?->label ?? 'Tanpa Role' }}
                        </p>

                        <nav class="mt-4 flex flex-wrap gap-3 text-sm">
                            @if (auth()->user()->hasRole(['super_admin', 'admin']))
                                @if (auth()->user()->hasRole('super_admin'))
                                    <a href="{{ route('master-data.schools.index') }}" class="font-semibold text-slate-700 hover:text-slate-950">
                                        Sekolah
                                    </a>
                                @endif

                                <a href="{{ route('master-data.class-rooms.index') }}" class="font-semibold text-slate-700 hover:text-slate-950">
                                    Kelas
                                </a>

                                <a href="{{ route('master-data.teachers.index') }}" class="font-semibold text-slate-700 hover:text-slate-950">
                                    Guru
                                </a>

                                <a href="{{ route('master-data.parents.index') }}" class="font-semibold text-slate-700 hover:text-slate-950">
                                    Orang Tua
                                </a>

                                <a href="{{ route('master-data.students.index') }}" class="font-semibold text-slate-700 hover:text-slate-950">
                                    Santri
                                </a>
                            @endif

                            @if (auth()->user()->hasRole(['super_admin', 'admin', 'teacher', 'principal']))
                                <a href="{{ route('tahfizh.hafalan-records.index') }}" class="font-semibold text-slate-700 hover:text-slate-950">
                                    Setoran Tahfizh
                                </a>

                                <a href="{{ route('tahfizh.targets.index') }}" class="font-semibold text-slate-700 hover:text-slate-950">
                                    Target Tahfizh
                                </a>

                                <a href="{{ route('tahfizh.debts.index') }}" class="font-semibold text-slate-700 hover:text-slate-950">
                                    Hutang Hafalan
                                </a>

                                <a href="{{ route('reports.tahfizh.dashboard') }}" class="font-semibold text-slate-700 hover:text-slate-950">
                                    Dashboard Tahfizh
                                </a>

                                <a href="{{ route('reports.tahfizh.monthly.index') }}" class="font-semibold text-slate-700 hover:text-slate-950">
                                    Laporan Bulanan
                                </a>

                                <a href="{{ route('reports.tahfizh.quarterly.index') }}" class="font-semibold text-slate-700 hover:text-slate-950">
                                    Laporan Triwulan
                                </a>

                                <a href="{{ route('exports.tahfizh.index') }}" class="font-semibold text-slate-700 hover:text-slate-950">
                                    Export Laporan
                                </a>

                                <a href="{{ route('mutabaah.reports.dashboard') }}" class="font-semibold text-slate-700 hover:text-slate-950">
                                    Mutabaah
                                </a>

                                <a href="{{ route('attendance.reports.dashboard') }}" class="font-semibold text-slate-700 hover:text-slate-950">
                                    Attendance
                                </a>

                                <a href="{{ route('tahsin.reports.dashboard') }}" class="font-semibold text-slate-700 hover:text-slate-950">
                                    Tahsin
                                </a>

                                <a href="{{ route('tahsin.assessments.index') }}" class="font-semibold text-slate-700 hover:text-slate-950">
                                    Asesmen Tahsin
                                </a>

                                <a href="{{ route('tahsin.profiles.index') }}" class="font-semibold text-slate-700 hover:text-slate-950">
                                    Profil Tahsin
                                </a>
                            @endif

                            @if (auth()->user()->hasRole(['super_admin', 'admin', 'principal']))
                                <a href="{{ route('finance.reports.dashboard') }}" class="font-semibold text-slate-700 hover:text-slate-950 text-blue-600">
                                    Finance Dashboard
                                </a>

                                <a href="{{ route('finance.bills.index') }}" class="font-semibold text-slate-700 hover:text-slate-950">
                                    Tagihan Santri
                                </a>

                                <a href="{{ route('finance.payments.index') }}" class="font-semibold text-slate-700 hover:text-slate-950">
                                    Pembayaran
                                </a>
                            @endif

                            @if (auth()->user()->hasRole(['super_admin', 'admin', 'teacher']))
                                <a href="{{ route('mutabaah.daily.index') }}" class="font-semibold text-slate-700 hover:text-slate-950">
                                    Input Mutabaah
                                </a>

                                <a href="{{ route('attendance.scanner.index') }}" class="font-semibold text-slate-700 hover:text-slate-950">
                                    Scanner QR
                                </a>

                                <a href="{{ route('attendance.manual.create') }}" class="font-semibold text-slate-700 hover:text-slate-950">
                                    Presensi Manual
                                </a>
                            @endif

                            @if (auth()->user()->hasRole(['super_admin', 'admin']))
                                <a href="{{ route('mutabaah.activities.index') }}" class="font-semibold text-slate-700 hover:text-slate-950">
                                    Template Mutabaah
                                </a>

                                <a href="{{ route('attendance.sessions.index') }}" class="font-semibold text-slate-700 hover:text-slate-950">
                                    Session Presensi
                                </a>

                                <a href="{{ route('attendance.qr-cards.index') }}" class="font-semibold text-slate-700 hover:text-slate-950">
                                    QR Santri
                                </a>

                                <a href="{{ route('tahsin.levels.index') }}" class="font-semibold text-slate-700 hover:text-slate-950">
                                    Level Tahsin
                                </a>

                                <a href="{{ route('tahsin.skills.index') }}" class="font-semibold text-slate-700 hover:text-slate-950">
                                    Skill Tahsin
                                </a>

                                <a href="{{ route('finance.fee-categories.index') }}" class="font-semibold text-slate-700 hover:text-slate-950 text-amber-600">
                                    Kategori Biaya
                                </a>

                                <a href="{{ route('finance.fee-items.index') }}" class="font-semibold text-slate-700 hover:text-slate-950 text-amber-600">
                                    Item Biaya
                                </a>
                            @endif

                            @if (auth()->user()->hasRole('parent'))
                                <a href="{{ route('portal.parent.dashboard') }}" class="font-semibold text-slate-700 hover:text-slate-950">
                                    Portal Orang Tua
                                </a>

                                @if(auth()->user()->parentProfile?->students()->exists())
                                <a href="#" class="font-semibold text-slate-700 hover:text-slate-950" id="parent-mutabaah-link">
                                    Mutabaah Anak
                                </a>
                                @endif

                                <a href="{{ route('portal.parent.attendance') }}" class="font-semibold text-slate-700 hover:text-slate-950">
                                    Presensi Anak
                                </a>

                                <a href="{{ route('portal.parent.tahsin') }}" class="font-semibold text-slate-700 hover:text-slate-950">
                                    Tahsin Anak
                                </a>

                                <a href="{{ route('portal.parent.finance') }}" class="font-semibold text-slate-700 hover:text-slate-950 text-blue-600">
                                    Keuangan Anak
                                </a>
                            @endif

                            @if (auth()->user()->hasRole('student'))
                                <a href="{{ route('portal.student.dashboard') }}" class="font-semibold text-slate-700 hover:text-slate-950">
                                    Portal Santri
                                </a>

                                <a href="{{ route('portal.student.mutabaah') }}" class="font-semibold text-slate-700 hover:text-slate-950">
                                    Mutabaah Saya
                                </a>

                                <a href="{{ route('portal.student.attendance') }}" class="font-semibold text-slate-700 hover:text-slate-950">
                                    Presensi Saya
                                </a>

                                <a href="{{ route('portal.student.tahsin') }}" class="font-semibold text-slate-700 hover:text-slate-950">
                                    Tahsin Saya
                                </a>

                                <a href="{{ route('portal.student.finance') }}" class="font-semibold text-slate-700 hover:text-slate-950 text-blue-600">
                                    Keuangan Saya
                                </a>
                            @endif

                            @php
                                $unreadNotificationCount = auth()->user()->unreadNotifications()->count();
                            @endphp

                            <a href="{{ route('notifications.index') }}" class="relative font-semibold text-slate-700 hover:text-slate-950">
                                Notifikasi

                                @if ($unreadNotificationCount > 0)
                                    <span class="absolute -right-4 -top-2 rounded-full bg-red-600 px-2 py-0.5 text-xs font-bold text-white">
                                        {{ $unreadNotificationCount > 99 ? '99+' : $unreadNotificationCount }}
                                    </span>
                                @endif
                            </a>

                            @if (auth()->user()->hasRole(['super_admin', 'admin']))
                                <a href="{{ route('notifications.announcements.create') }}" class="font-semibold text-slate-700 hover:text-slate-950">
                                    Kirim Pengumuman
                                </a>

                                <a href="{{ route('admin.system.status') }}" class="font-semibold text-slate-700 hover:text-slate-950">
                                    System Status
                                </a>
                            @endif
                        </nav>
                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button
                            type="submit"
                            class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700"
                        >
                            Logout
                        </button>
                    </form>
                </div>
            </header>
        @endauth

        <main class="mx-auto max-w-7xl px-4 py-8">
            @if (session('success'))
                <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</body>
</html>
