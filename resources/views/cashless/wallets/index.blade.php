@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <x-cashless.shell title="Wallet Santri">
        <div class="overflow-x-auto rounded-xl border border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-900">
            <table class="min-w-full text-sm">
                <thead class="text-left text-xs uppercase text-slate-500"><tr><th class="p-3">Wallet</th><th>Santri</th><th>Kelas</th><th>Saldo</th><th>Status</th></tr></thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @foreach($wallets as $wallet)
                        <tr class="dark:text-slate-200"><td class="p-3"><a class="font-bold" href="{{ route('cashless.wallets.show', $wallet) }}">{{ $wallet->wallet_number }}</a></td><td>{{ $wallet->student?->full_name }}</td><td>{{ $wallet->student?->classRoom?->name }}</td><td>Rp {{ number_format($wallet->balance,0,',','.') }}</td><td>{{ $wallet->status }}</td></tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $wallets->links() }}
    </x-cashless.shell>
</div>
@endsection
