@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <x-cashless.shell title="Produk Merchant">
        <div class="flex justify-end"><a href="{{ route('cashless.products.create') }}" class="rounded-lg bg-lime-500 px-4 py-2 text-sm font-black text-white">Tambah Produk</a></div>
        <div class="overflow-x-auto rounded-xl border border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-900">
            <table class="min-w-full text-sm">
                <thead class="text-left text-xs uppercase text-slate-500"><tr><th class="p-3">Produk</th><th>Merchant</th><th>Harga</th><th>Stok</th><th>Status</th></tr></thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @foreach($products as $product)
                        <tr class="dark:text-slate-200"><td class="p-3"><a class="font-bold" href="{{ route('cashless.products.show', $product) }}">{{ $product->name }}</a></td><td>{{ $product->merchant?->name }}</td><td>Rp {{ number_format($product->price,0,',','.') }}</td><td>{{ $product->track_stock ? $product->stock : '-' }}</td><td>{{ $product->status }}</td></tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $products->links() }}
    </x-cashless.shell>
</div>
@endsection
