@props(['title', 'subtitle' => null, 'action' => null, 'schoolId' => null])

@php
    $user = request()->user();
    $activeSchoolId = $schoolId ?: app(\App\Services\Tenancy\TenantContextService::class)->activeSchoolId();
    $isInternal = $user->hasRole(['super_admin', 'operations_manager', 'customer_success', 'support_staff', 'sales']);

    $links = [];
    
    if ($isInternal) {
        $links[] = ['label' => 'Executive', 'route' => 'analytics.executive.dashboard', 'active' => 'analytics.executive.*'];
        $links[] = ['label' => 'Tenant Health', 'route' => 'analytics.tenant-health.index', 'active' => 'analytics.tenant-health.*'];
    }
    
    if ($activeSchoolId) {
        $links[] = ['label' => 'School Dashboard', 'route' => 'analytics.school.dashboard', 'active' => 'analytics.school.*', 'params' => ['school_id' => $activeSchoolId]];
        $links[] = ['label' => 'Academic', 'route' => 'analytics.academic.dashboard', 'active' => 'analytics.academic.*', 'params' => ['school_id' => $activeSchoolId]];
        $links[] = ['label' => 'Operational', 'route' => 'analytics.operational.dashboard', 'active' => 'analytics.operational.*', 'params' => ['school_id' => $activeSchoolId]];
        $links[] = ['label' => 'Finance', 'route' => 'analytics.finance.dashboard', 'active' => 'analytics.finance.*', 'params' => ['school_id' => $activeSchoolId]];
    }

    $links[] = ['label' => 'Support', 'route' => 'analytics.support.dashboard', 'active' => 'analytics.support.*', 'params' => $activeSchoolId ? ['school_id' => $activeSchoolId] : []];
    $links[] = ['label' => 'Mobile & API', 'route' => 'analytics.mobile-api.dashboard', 'active' => 'analytics.mobile-api.*', 'params' => $activeSchoolId ? ['school_id' => $activeSchoolId] : []];
    $links[] = ['label' => 'Reports', 'route' => 'analytics.executive-reports.index', 'active' => 'analytics.executive-reports.*'];
    $links[] = ['label' => 'Dictionary', 'route' => 'analytics.metric-dictionary.index', 'active' => 'analytics.metric-dictionary.*'];
@endphp

<div class="space-y-6">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white tracking-tight">{{ $title }}</h1>
            @if($subtitle)
                <p class="mt-1 text-xs sm:text-sm text-slate-500 dark:text-slate-400">{{ $subtitle }}</p>
            @endif
        </div>
        @if($action)
            <div>{{ $action }}</div>
        @endif
    </div>

    <div class="flex flex-wrap gap-1.5 p-1 rounded-2xl border border-slate-200/90 bg-white dark:border-slate-800 dark:bg-slate-900 shadow-xs text-xs font-bold">
        @foreach($links as $link)
            <a href="{{ route($link['route'], $link['params'] ?? []) }}" 
               class="px-3.5 py-2 rounded-xl transition-all duration-150 {{ request()->routeIs($link['active']) ? 'bg-emerald-600 text-white shadow-sm font-black' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100 dark:text-slate-300 dark:hover:text-white dark:hover:bg-slate-800' }}">
                {{ $link['label'] }}
            </a>
        @endforeach
    </div>

    @if(session('success'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50/90 px-4 py-3.5 text-xs sm:text-sm font-bold text-emerald-900 dark:border-emerald-900/60 dark:bg-emerald-950/40 dark:text-emerald-300 shadow-xs flex items-center gap-2.5">
            <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="rounded-2xl border border-rose-200 bg-rose-50/90 px-4 py-3.5 text-xs sm:text-sm font-bold text-rose-900 dark:border-rose-900/60 dark:bg-rose-950/40 dark:text-rose-300 shadow-xs">
            {{ session('error') }}
        </div>
    @endif

    {{ $slot }}
</div>
