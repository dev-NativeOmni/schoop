@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <x-cashless.shell title="POS Kasir">
        @if(!$activeSession)
            <div class="rounded-xl border border-amber-200 bg-amber-50 p-5 text-amber-900 dark:border-amber-900/60 dark:bg-amber-950/40 dark:text-amber-200">Belum ada session POS open. <a class="font-black underline" href="{{ route('cashless.pos-sessions.open') }}">Buka session</a>.</div>
        @else
            <form method="POST" action="{{ route('cashless.pos.sales.store') }}" class="grid gap-5 lg:grid-cols-[1fr_360px]">
                @csrf
                <input type="hidden" name="cashless_pos_session_id" value="{{ $activeSession->id }}">
                <input type="hidden" name="idempotency_key" value="{{ (string) Str::uuid() }}">
                <div class="rounded-xl border border-slate-200 bg-white p-5 dark:border-slate-800 dark:bg-slate-900">
                    <label class="block text-sm font-bold dark:text-slate-200">Session <select onchange="location='{{ route('cashless.pos.cashier') }}?session='+this.value" class="mt-1 w-full rounded-lg dark:border-slate-700 dark:bg-slate-950">@foreach($sessions as $session)<option value="{{ $session->id }}" @selected($activeSession->id === $session->id)>{{ $session->merchant?->name }} · {{ $session->session_number }}</option>@endforeach</select></label>
                    <label class="mt-4 block text-sm font-bold dark:text-slate-200">Santri <select name="student_id" class="mt-1 w-full rounded-lg dark:border-slate-700 dark:bg-slate-950" required>@foreach($students as $student)<option value="{{ $student->id }}">{{ $student->full_name }} · Saldo Rp {{ number_format($student->cashlessWallet?->balance ?? 0,0,',','.') }}</option>@endforeach</select></label>
                    <div class="mt-5 grid gap-3 md:grid-cols-2">
                        @foreach($products as $i => $product)
                            <div class="rounded-lg border border-slate-200 p-3 dark:border-slate-700">
                                <p class="font-black dark:text-white">{{ $product->name }}</p>
                                <p class="text-sm text-slate-500">Rp {{ number_format($product->price,0,',','.') }}</p>
                                <input type="hidden" name="items[{{ $i }}][cashless_product_id]" value="{{ $product->id }}">
                                <input type="number" name="items[{{ $i }}][quantity]" value="0" min="0" class="mt-2 w-24 rounded-lg dark:border-slate-700 dark:bg-slate-950">
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="rounded-xl border border-slate-200 bg-white p-5 dark:border-slate-800 dark:bg-slate-900">
                    <h2 class="font-black dark:text-white">Checkout</h2>
                    <p class="mt-2 text-sm text-slate-500">Isi quantity produk yang dibeli. Produk dengan quantity 0 akan ditolak oleh validasi, jadi gunakan minimal satu item.</p>
                    <label class="mt-4 block text-sm font-bold dark:text-slate-200">PIN Transaksi <input type="password" name="pin" maxlength="6" placeholder="Masukkan 4-6 digit PIN" class="mt-1 w-full rounded-lg dark:border-slate-700 dark:bg-slate-950"></label>
                    <label class="mt-4 block text-sm font-bold dark:text-slate-200">Catatan <textarea name="note" class="mt-1 w-full rounded-lg dark:border-slate-700 dark:bg-slate-950"></textarea></label>
                    <button class="mt-5 w-full rounded-lg bg-lime-500 px-4 py-3 text-sm font-black text-white">Submit Transaksi</button>
                </div>
            </form>
        @endif
    </x-cashless.shell>
</div>
@endsection
