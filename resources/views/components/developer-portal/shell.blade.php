@props(['title', 'subtitle' => null, 'action' => null])

@php
    $links = [
        ['label' => 'Dashboard', 'route' => 'developer-portal.dashboard', 'active' => 'developer-portal.dashboard'],
        ['label' => 'API Clients', 'route' => 'developer-portal.api-clients.index', 'active' => 'developer-portal.api-clients.*'],
        ['label' => 'Scopes', 'route' => 'developer-portal.api-scopes.index', 'active' => 'developer-portal.api-scopes.*'],
        ['label' => 'Partners', 'route' => 'developer-portal.partner-integrations.index', 'active' => 'developer-portal.partner-integrations.*'],
        ['label' => 'Webhooks', 'route' => 'developer-portal.webhooks.index', 'active' => 'developer-portal.webhooks.*'],
        ['label' => 'Deliveries', 'route' => 'developer-portal.webhook-deliveries.index', 'active' => 'developer-portal.webhook-deliveries.*'],
        ['label' => 'Logs', 'route' => 'developer-portal.request-logs.index', 'active' => 'developer-portal.request-logs.*'],
        ['label' => 'Docs', 'route' => 'developer-portal.docs.index', 'active' => 'developer-portal.docs.*'],
    ];
@endphp

<div class="space-y-6">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <h1 class="text-2xl font-black text-slate-950 dark:text-white">{{ $title }}</h1>
            @if($subtitle)
                <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">{{ $subtitle }}</p>
            @endif
        </div>
        @if($action)
            <div>{{ $action }}</div>
        @endif
    </div>

    <div class="flex flex-wrap gap-2 rounded-xl border border-slate-200 bg-white p-2 text-xs font-bold shadow-sm dark:border-slate-800 dark:bg-slate-900">
        @foreach($links as $link)
            <a href="{{ route($link['route']) }}" class="rounded-lg px-3 py-2 transition {{ request()->routeIs($link['active']) ? 'bg-slate-950 text-white dark:bg-lime-400 dark:text-slate-950' : 'text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800' }}">{{ $link['label'] }}</a>
        @endforeach
    </div>

    @if(session('success'))
        <div class="rounded-xl border border-lime-200 bg-lime-50 px-4 py-3 text-sm font-bold text-lime-800 dark:border-lime-900/60 dark:bg-lime-950/40 dark:text-lime-200">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-bold text-red-800 dark:border-red-900/60 dark:bg-red-950/40 dark:text-red-200">{{ session('error') }}</div>
    @endif

    @if($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 dark:border-red-900/60 dark:bg-red-950/40 dark:text-red-200">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{ $slot }}
</div>
