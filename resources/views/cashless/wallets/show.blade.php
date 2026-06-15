@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <x-cashless.shell title="{{ $wallet->student?->full_name }}" subtitle="{{ $wallet->wallet_number }}">
        <div class="grid gap-4 md:grid-cols-3">
            <div class="rounded-xl border border-slate-200 bg-white p-5 dark:border-slate-800 dark:bg-slate-900"><p class="text-xs font-bold text-slate-500">Saldo</p><p class="text-2xl font-black dark:text-white">Rp {{ number_format($wallet->balance,0,',','.') }}</p></div>
            <div class="rounded-xl border border-slate-200 bg-white p-5 dark:border-slate-800 dark:bg-slate-900"><p class="text-xs font-bold text-slate-500">Status</p><p class="text-2xl font-black dark:text-white">{{ $wallet->status }}</p></div>
            <div class="rounded-xl border border-slate-200 bg-white p-5 dark:border-slate-800 dark:bg-slate-900"><form method="POST" action="{{ $wallet->status === 'active' ? route('cashless.wallets.freeze', $wallet) : route('cashless.wallets.unfreeze', $wallet) }}">@csrf @method('PATCH')<input name="reason" placeholder="Alasan" class="mb-2 w-full rounded-lg dark:border-slate-700 dark:bg-slate-950"><button class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-black text-white dark:bg-lime-400 dark:text-slate-950">{{ $wallet->status === 'active' ? 'Freeze' : 'Unfreeze' }}</button></form></div>
        </div>
        @include('cashless.reports._transactions-table', ['transactions' => $wallet->transactions])
    </x-cashless.shell>
</div>
@endsection
