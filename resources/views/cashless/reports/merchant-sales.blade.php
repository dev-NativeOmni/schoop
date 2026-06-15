@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6"><x-cashless.shell title="Laporan Penjualan Merchant"><div class="overflow-x-auto rounded-xl border border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-900"><table class="min-w-full text-sm"><tbody>@foreach($sales as $sale)<tr class="border-b dark:border-slate-800 dark:text-slate-200"><td class="p-3">{{ $sale->receipt_number }}</td><td>{{ $sale->merchant?->name }}</td><td>{{ $sale->student?->full_name }}</td><td>Rp {{ number_format($sale->total_amount,0,',','.') }}</td><td>{{ $sale->status }}</td></tr>@endforeach</tbody></table></div>{{ $sales->links() }}</x-cashless.shell></div>
@endsection
