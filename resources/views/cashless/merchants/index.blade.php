@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <x-cashless.shell title="Merchant Cashless">
        <div class="flex justify-end"><a href="{{ route('cashless.merchants.create') }}" class="rounded-lg bg-lime-500 px-4 py-2 text-sm font-black text-white">Tambah Merchant</a></div>
        <div class="grid gap-4 md:grid-cols-2">
            @foreach ($merchants as $merchant)
                <a href="{{ route('cashless.merchants.show', $merchant) }}" class="rounded-xl border border-slate-200 bg-white p-5 dark:border-slate-800 dark:bg-slate-900">
                    <p class="text-lg font-black text-slate-900 dark:text-white">{{ $merchant->name }}</p>
                    <p class="text-sm text-slate-500">{{ $merchant->code }} · {{ $merchant->type }} · {{ $merchant->status }}</p>
                </a>
            @endforeach
        </div>
        {{ $merchants->links() }}
    </x-cashless.shell>
</div>
@endsection
