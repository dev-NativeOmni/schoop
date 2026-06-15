@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <x-cashless.shell title="Session POS">
        <div class="flex justify-end"><a href="{{ route('cashless.pos-sessions.open') }}" class="rounded-lg bg-lime-500 px-4 py-2 text-sm font-black text-white">Buka Session</a></div>
        <div class="overflow-x-auto rounded-xl border border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-900">
            <table class="min-w-full text-sm"><thead class="text-left text-xs uppercase text-slate-500"><tr><th class="p-3">Session</th><th>Merchant</th><th>Kasir</th><th>Total</th><th>Status</th></tr></thead><tbody class="divide-y divide-slate-100 dark:divide-slate-800">@foreach($sessions as $session)<tr class="dark:text-slate-200"><td class="p-3"><a class="font-bold" href="{{ route('cashless.pos-sessions.show', $session) }}">{{ $session->session_number }}</a></td><td>{{ $session->merchant?->name }}</td><td>{{ $session->cashier?->name }}</td><td>Rp {{ number_format($session->total_sales,0,',','.') }}</td><td>{{ $session->status }}</td></tr>@endforeach</tbody></table>
        </div>
        {{ $sessions->links() }}
    </x-cashless.shell>
</div>
@endsection
