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
