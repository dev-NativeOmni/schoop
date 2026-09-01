@props(['title', 'subtitle' => null])

@php
    $links = [
        ['label' => 'Dashboard', 'route' => 'saas-ops.dashboard', 'active' => 'saas-ops.dashboard'],
        ['label' => 'Plans', 'route' => 'saas-ops.subscription-plans.index', 'active' => 'saas-ops.subscription-plans.*'],
        ['label' => 'Subscriptions', 'route' => 'saas-ops.school-subscriptions.index', 'active' => 'saas-ops.school-subscriptions.*'],
        ['label' => 'Invoices', 'route' => 'saas-ops.tenant-invoices.index', 'active' => 'saas-ops.tenant-invoices.*'],
        ['label' => 'Support', 'route' => 'saas-ops.support-tickets.index', 'active' => 'saas-ops.support-tickets.*'],
    ];
@endphp

<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white tracking-tight">{{ $title }}</h1>
            @if($subtitle)<p class="mt-1 text-xs sm:text-sm text-slate-500 dark:text-slate-400">{{ $subtitle }}</p>@endif
        </div>
        <div class="flex flex-wrap items-center gap-1.5 p-1 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/90 dark:border-slate-800 shadow-xs">
            @foreach($links as $link)
                <a href="{{ route($link['route']) }}" 
                   class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all duration-150 {{ request()->routeIs($link['active']) ? 'bg-emerald-600 text-white shadow-sm font-black' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100 dark:text-slate-300 dark:hover:text-white dark:hover:bg-slate-800' }}">
                    {{ $link['label'] }}
                </a>
            @endforeach
        </div>
    </div>

    @if(session('success'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50/90 px-4 py-3.5 text-xs sm:text-sm font-bold text-emerald-900 dark:border-emerald-900/60 dark:bg-emerald-950/40 dark:text-emerald-300 shadow-xs flex items-center gap-2.5">
            <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="rounded-2xl border border-rose-200 bg-rose-50/90 px-4 py-3.5 text-xs sm:text-sm text-rose-900 dark:border-rose-900/60 dark:bg-rose-950/40 dark:text-rose-300 shadow-xs">
            <ul class="list-disc pl-5 space-y-0.5 font-semibold">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{ $slot }}
</div>
