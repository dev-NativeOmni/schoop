@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6"><x-cashless.shell title="Receipt {{ $sale->receipt_number }}" subtitle="{{ $sale->student?->full_name }}"><div class="rounded-xl border border-slate-200 bg-white p-5 dark:border-slate-800 dark:bg-slate-900"><table class="min-w-full text-sm"><tbody class="divide-y divide-slate-100 dark:divide-slate-800">@foreach($sale->items as $item)<tr class="dark:text-slate-200"><td class="py-2">{{ $item->product_name }} x {{ $item->quantity }}</td><td class="text-right">Rp {{ number_format($item->subtotal,0,',','.') }}</td></tr>@endforeach</tbody><tfoot><tr class="font-black dark:text-white"><td class="py-3">Total</td><td class="text-right">Rp {{ number_format($sale->total_amount,0,',','.') }}</td></tr></tfoot></table><p class="mt-4 text-sm text-slate-500">Status: {{ $sale->status }} · Refunded: Rp {{ number_format($sale->refunded_amount,0,',','.') }}</p></div></x-cashless.shell></div>
@endsection
