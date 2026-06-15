@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6"><div class="space-y-5"><h1 class="text-2xl font-black text-slate-900 dark:text-white">Cashless Saya</h1>@if($wallet)<div class="rounded-xl border border-slate-200 bg-white p-5 dark:border-slate-800 dark:bg-slate-900"><p class="font-black dark:text-white">{{ $student?->full_name }}</p><p class="text-2xl font-black text-lime-600">Rp {{ number_format($wallet->balance,0,',','.') }}</p><p class="text-sm text-slate-500">Status: {{ $wallet->status }}</p></div>@include('cashless.reports._transactions-table', ['transactions' => $wallet->transactions])@else<div class="rounded-xl border border-slate-200 bg-white p-5 text-slate-500 dark:border-slate-800 dark:bg-slate-900">Wallet belum tersedia.</div>@endif</div></div>
@endsection
