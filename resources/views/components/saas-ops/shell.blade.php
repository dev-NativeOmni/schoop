@props(['title', 'subtitle' => null])

<div class="space-y-6">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-2xl font-black text-slate-900 dark:text-white">{{ $title }}</h1>
            @if($subtitle)<p class="mt-1 text-sm text-slate-600 dark:text-slate-300">{{ $subtitle }}</p>@endif
        </div>
        <div class="flex flex-wrap gap-2 text-xs font-bold">
            <a href="{{ route('saas-ops.dashboard') }}" class="rounded-lg bg-slate-900 px-3 py-2 text-white dark:bg-lime-400 dark:text-slate-950">Dashboard</a>
            <a href="{{ route('saas-ops.subscription-plans.index') }}" class="rounded-lg border border-slate-200 px-3 py-2 dark:border-slate-700 dark:text-slate-200">Plans</a>
            <a href="{{ route('saas-ops.school-subscriptions.index') }}" class="rounded-lg border border-slate-200 px-3 py-2 dark:border-slate-700 dark:text-slate-200">Subscriptions</a>
            <a href="{{ route('saas-ops.tenant-invoices.index') }}" class="rounded-lg border border-slate-200 px-3 py-2 dark:border-slate-700 dark:text-slate-200">Invoices</a>
            <a href="{{ route('saas-ops.support-tickets.index') }}" class="rounded-lg border border-slate-200 px-3 py-2 dark:border-slate-700 dark:text-slate-200">Support</a>
        </div>
    </div>
    @if(session('success'))<div class="rounded-xl border border-lime-200 bg-lime-50 px-4 py-3 text-sm font-bold text-lime-800 dark:border-lime-900/60 dark:bg-lime-950/40 dark:text-lime-200">{{ session('success') }}</div>@endif
    @if($errors->any())<div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 dark:border-red-900/60 dark:bg-red-950/40 dark:text-red-200"><ul class="list-disc pl-5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
    {{ $slot }}
</div>
