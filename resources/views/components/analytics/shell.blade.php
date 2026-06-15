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
            <a href="{{ route($link['route'], $link['params'] ?? []) }}" class="rounded-lg px-3 py-2 transition {{ request()->routeIs($link['active']) ? 'bg-slate-950 text-white dark:bg-lime-400 dark:text-slate-950' : 'text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800' }}">{{ $link['label'] }}</a>
        @endforeach
    </div>

    @if(session('success'))
        <div class="rounded-xl border border-lime-200 bg-lime-50 px-4 py-3 text-sm font-bold text-lime-800 dark:border-lime-900/60 dark:bg-lime-950/40 dark:text-lime-200">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-bold text-red-800 dark:border-red-900/60 dark:bg-red-950/40 dark:text-red-200">{{ session('error') }}</div>
    @endif

    {{ $slot }}
</div>
