@extends('layouts.app')
@section('content')
<div class="max-w-7xl mx-auto px-4 py-6"><x-saas-ops.shell title="Subscription Plans"><a class="rounded-lg bg-lime-500 px-4 py-2 text-sm font-black text-white" href="{{ route('saas-ops.subscription-plans.create') }}">Tambah Plan</a><div class="overflow-x-auto rounded-xl border bg-white dark:border-slate-800 dark:bg-slate-900"><table class="min-w-full text-sm">@foreach($plans as $plan)<tr class="border-b dark:border-slate-800 dark:text-slate-200"><td class="p-3"><a class="font-bold" href="{{ route('saas-ops.subscription-plans.show',$plan) }}">{{ $plan->name }}</a></td><td>{{ $plan->code }}</td><td>{{ $plan->billing_cycle }}</td><td>{{ $plan->status }}</td></tr>@endforeach</table></div>{{ $plans->links() }}</x-saas-ops.shell></div>
@endsection
