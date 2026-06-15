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
            <link rel="icon" type="image/png" href="{{ Storage::disk('public')->url($brand->favicon_path) }}">
        @endif
    @else
        <title>{{ config('app.name', 'HafizPlus School Platform') }}</title>
    @endif

    <link rel="manifest" href="{{ route('tenant.pwa.manifest') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @if($cssVariables)
        <style>
            {!! $cssVariables !!}
            
            /* Enforce theme variables on core elements */
            body {
                background: var(--school-background) !important;
                color: var(--school-text) !important;
            }
            .btn-primary, button[type="submit"], .bg-slate-900 {
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
<body class="min-h-screen bg-slate-100 text-slate-900 antialiased transition-colors duration-200 dark:bg-slate-950 dark:text-slate-100">
    <div class="min-h-screen">
        @auth
            <x-navbar />
        @endauth

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
</body>
</html>
