@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <x-cashless.shell title="{{ $merchant->name }}" subtitle="{{ $merchant->code }} · {{ $merchant->status }}">
        <div class="flex gap-2"><a href="{{ route('cashless.merchants.edit', $merchant) }}" class="rounded-lg bg-lime-500 px-4 py-2 text-sm font-black text-white">Edit</a></div>
        <div class="grid gap-4 md:grid-cols-2">
            <div class="rounded-xl border border-slate-200 bg-white p-5 dark:border-slate-800 dark:bg-slate-900"><h2 class="font-black dark:text-white">Kasir</h2>@foreach($merchant->users as $assignment)<p class="mt-2 text-sm dark:text-slate-200">{{ $assignment->user?->name }} · {{ $assignment->role }}</p>@endforeach</div>
            <div class="rounded-xl border border-slate-200 bg-white p-5 dark:border-slate-800 dark:bg-slate-900"><h2 class="font-black dark:text-white">Produk</h2>@foreach($merchant->products as $product)<p class="mt-2 text-sm dark:text-slate-200">{{ $product->name }} · Rp {{ number_format($product->price,0,',','.') }}</p>@endforeach</div>
        </div>
    </x-cashless.shell>
</div>
@endsection
