@extends('layouts.app')
@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
<x-saas-ops.shell title="SaaS Operations Dashboard" subtitle="Product scale, support, billing, onboarding, and tenant health.">
    <div class="grid gap-4 md:grid-cols-4">@foreach($summary as $label => $value)<div class="rounded-xl border border-slate-200 bg-white p-4 dark:border-slate-800 dark:bg-slate-900"><p class="text-xs font-bold uppercase text-slate-500">{{ str_replace('_',' ',$label) }}</p><p class="mt-2 text-2xl font-black dark:text-white">{{ is_numeric($value) && str_contains($label, 'balance') ? 'Rp '.number_format($value,0,',','.') : $value }}</p></div>@endforeach</div>
    <div class="grid gap-4 lg:grid-cols-2"><div class="rounded-xl border border-slate-200 bg-white p-5 dark:border-slate-800 dark:bg-slate-900"><h2 class="font-black dark:text-white">Tenant Health</h2>@forelse($snapshots as $snapshot)<p class="mt-2 text-sm dark:text-slate-200">{{ $snapshot->school?->name }} - {{ $snapshot->health_score }} / {{ $snapshot->health_status }}</p>@empty<p class="mt-2 text-sm text-slate-500">Belum ada snapshot.</p>@endforelse</div><div class="rounded-xl border border-slate-200 bg-white p-5 dark:border-slate-800 dark:bg-slate-900"><h2 class="font-black dark:text-white">Recent Tickets</h2>@forelse($tickets as $ticket)<p class="mt-2 text-sm dark:text-slate-200">{{ $ticket->ticket_number }} - {{ $ticket->subject }} ({{ $ticket->status }})</p>@empty<p class="mt-2 text-sm text-slate-500">Belum ada ticket.</p>@endforelse</div></div>
</x-saas-ops.shell>
</div>
@endsection
