@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <x-cashless.shell title="Top Up Wallet">
        <form method="POST" action="{{ route('cashless.top-ups.store') }}" class="rounded-xl border border-slate-200 bg-white p-5 dark:border-slate-800 dark:bg-slate-900">
            @csrf
            <div class="grid gap-4 md:grid-cols-2">
                <label class="text-sm font-bold dark:text-slate-200">Wallet <select name="cashless_wallet_id" class="mt-1 w-full rounded-lg dark:border-slate-700 dark:bg-slate-950">@foreach($wallets as $wallet)<option value="{{ $wallet->id }}">{{ $wallet->student?->full_name }} · Rp {{ number_format($wallet->balance,0,',','.') }}</option>@endforeach</select></label>
                <label class="text-sm font-bold dark:text-slate-200">Nominal <input type="number" name="amount" min="1000" class="mt-1 w-full rounded-lg dark:border-slate-700 dark:bg-slate-950" required></label>
            </div>
            <label class="mt-4 block text-sm font-bold dark:text-slate-200">Catatan <textarea name="note" class="mt-1 w-full rounded-lg dark:border-slate-700 dark:bg-slate-950"></textarea></label>
            <button class="mt-5 rounded-lg bg-lime-500 px-4 py-2 text-sm font-black text-white">Post Top-up</button>
        </form>
    </x-cashless.shell>
</div>
@endsection
