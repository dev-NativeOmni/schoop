@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6"><x-cashless.shell title="{{ $product->name }}" subtitle="{{ $product->merchant?->name }}"><a href="{{ route('cashless.products.edit', $product) }}" class="rounded-lg bg-lime-500 px-4 py-2 text-sm font-black text-white">Edit</a><div class="rounded-xl border border-slate-200 bg-white p-5 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-200">Harga: Rp {{ number_format($product->price,0,',','.') }}<br>Stok: {{ $product->track_stock ? $product->stock : 'Tidak ditrack' }}<br>Status: {{ $product->status }}</div></x-cashless.shell></div>
@endsection
