<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $brand->display_name }}</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="manifest" href="{{ route('tenant.pwa.manifest') }}">
    
    @if($brand->favicon_path)
        <link rel="icon" type="image/png" href="{{ Storage::disk('public')->url($brand->favicon_path) }}">
    @endif

    <style>
        {!! $cssVariables !!}

        .tenant-primary-btn {
            background-color: var(--school-primary) !important;
            border-radius: var(--school-radius-button) !important;
            color: #ffffff !important;
        }

        .tenant-primary-btn:hover {
            opacity: 0.9;
        }

        .tenant-card {
            border-radius: var(--school-radius-card) !important;
        }

        .tenant-text-primary {
            color: var(--school-primary) !important;
        }
    </style>
</head>
<body class="min-h-screen font-sans bg-slate-50 text-slate-900 flex flex-col justify-between" style="background-color: var(--school-background); color: var(--school-text);">
    
    <!-- Navbar -->
    <header class="w-full max-w-7xl mx-auto px-6 py-4 flex items-center justify-between border-b border-slate-100">
        <div class="flex items-center space-x-3">
            @if($brand->logo_path)
                <img src="{{ Storage::disk('public')->url($brand->logo_path) }}" alt="Logo {{ $school->name }}" class="h-10 w-10 object-contain rounded-xl bg-white/20 p-1">
            @else
                <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-700 flex items-center justify-center font-bold text-white text-sm">HP</div>
            @endif
            <span class="font-extrabold text-lg">{{ $brand->display_name }}</span>
        </div>
        <div>
            @if($isAuthenticated)
                <a href="{{ route('dashboard') }}" class="tenant-primary-btn px-5 py-2.5 font-bold text-sm shadow-md transition-all hover:scale-[1.02] active:scale-[0.98]">
                    Buka Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="tenant-primary-btn px-5 py-2.5 font-bold text-sm shadow-md transition-all hover:scale-[1.02] active:scale-[0.98]">
                    Masuk Portal
                </a>
            @endif
        </div>
    </header>

    <!-- Main Hero -->
    <main class="flex-1 max-w-4xl mx-auto px-6 py-20 text-center flex flex-col items-center justify-center space-y-8">
        @if($brand->logo_path)
            <img src="{{ Storage::disk('public')->url($brand->logo_path) }}" alt="Logo {{ $school->name }}" class="h-28 w-28 object-contain rounded-3xl bg-white p-2 border shadow-sm">
        @endif

        <div class="space-y-4">
            <h1 class="text-4xl md:text-5xl font-black tracking-tight leading-tight">
                Selamat Datang di Portal Resmi<br>
                <span class="tenant-text-primary">{{ $brand->display_name }}</span>
            </h1>
            @if($brand->tagline)
                <p class="text-lg opacity-80 max-w-2xl mx-auto leading-relaxed">
                    "{{ $brand->tagline }}"
                </p>
            @endif
        </div>

        <div class="flex flex-wrap items-center justify-center gap-4 pt-4">
            @if($isAuthenticated)
                <a href="{{ route('dashboard') }}" class="tenant-primary-btn px-8 py-3.5 font-bold shadow-lg transition-all hover:scale-[1.02] active:scale-[0.98] text-base">
                    Masuk ke Dashboard Saya
                </a>
            @else
                <a href="{{ route('login') }}" class="tenant-primary-btn px-8 py-3.5 font-bold shadow-lg transition-all hover:scale-[1.02] active:scale-[0.98] text-base">
                    Log Masuk Akun
                </a>
            @endif
        </div>
    </main>

    <!-- Footer -->
    <footer class="w-full max-w-7xl mx-auto px-6 py-8 border-t border-slate-100 flex flex-col md:flex-row md:items-center md:justify-between gap-4 text-xs opacity-60">
        <div>
            &copy; {{ date('Y') }} {{ $brand->display_name }}. Hak Cipta Dilindungi.
        </div>
        <div class="flex flex-wrap items-center gap-6">
            @if($brand->public_contact_phone)
                <span>Tlp: {{ $brand->public_contact_phone }}</span>
            @endif
            @if($brand->public_contact_email)
                <span>Email: {{ $brand->public_contact_email }}</span>
            @endif
            @if($brand->public_website_url)
                <a href="{{ $brand->public_website_url }}" target="_blank" class="hover:underline">Website Utama</a>
            @endif
        </div>
    </footer>

</body>
</html>
