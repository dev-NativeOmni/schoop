@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-black text-slate-900 dark:text-white">Cashless Anak</h1>
        </div>

        @if(session('success'))
            <div class="rounded-xl border border-lime-200 bg-lime-50 px-4 py-3 text-sm font-bold text-lime-800 dark:border-lime-900/60 dark:bg-lime-950/40 dark:text-lime-200">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-bold text-red-800 dark:border-red-900/60 dark:bg-red-950/40 dark:text-red-200">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @forelse($wallets as $wallet)
            <div class="rounded-xl border border-slate-200 bg-white p-6 dark:border-slate-800 dark:bg-slate-900 shadow-sm transition duration-150 hover:shadow-md">
                <div class="grid gap-8 lg:grid-cols-[1fr_360px]">
                    <div>
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-slate-100 pb-4 mb-4 dark:border-slate-800 gap-3">
                            <div>
                                <h2 class="text-xl font-black text-slate-850 dark:text-white">{{ $wallet->student?->full_name }}</h2>
                                <p class="text-xs text-slate-500 font-medium mt-0.5">Nomor Wallet: <code class="bg-slate-100 dark:bg-slate-800 px-1.5 py-0.5 rounded text-slate-600 dark:text-slate-300 font-mono">{{ $wallet->wallet_number }}</code></p>
                            </div>
                            <div class="sm:text-right">
                                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Saldo Wallet</p>
                                <p class="text-3xl font-black text-lime-600 dark:text-lime-500 mt-0.5">Rp {{ number_format($wallet->balance, 0, ',', '.') }}</p>
                            </div>
                        </div>
                        
                        <div class="mb-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="rounded-xl bg-slate-50 p-4 dark:bg-slate-950/50 border border-slate-100 dark:border-slate-800/60">
                                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 block mb-1">Limit Belanja Harian</span>
                                <span class="font-extrabold text-slate-700 dark:text-slate-300 text-base">
                                    @if($wallet->daily_limit)
                                        Rp {{ number_format($wallet->daily_limit, 0, ',', '.') }} / hari
                                    @else
                                        <span class="text-slate-400 font-normal">Tanpa Batas (Unlimited)</span>
                                    @endif
                                </span>
                            </div>
                            <div class="rounded-xl bg-slate-50 p-4 dark:bg-slate-950/50 border border-slate-100 dark:border-slate-800/60">
                                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 block mb-1">Status Keamanan PIN</span>
                                <span class="font-extrabold text-slate-700 dark:text-slate-300 text-base">
                                    @if($wallet->pin)
                                        <span class="inline-flex items-center text-emerald-600 dark:text-emerald-400 text-sm font-bold">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                            Aktif (Wajib untuk ≥ Rp50.000)
                                        </span>
                                    @else
                                        <span class="inline-flex items-center text-amber-600 dark:text-amber-500 text-sm font-bold">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                            Belum Diaktifkan
                                        </span>
                                    @endif
                                </span>
                            </div>
                        </div>

                        <h3 class="text-sm font-black uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-3">10 Transaksi Terakhir</h3>
                        @include('cashless.reports._transactions-table', ['transactions' => $wallet->transactions])
                    </div>
                    
                    <!-- Form Update Limit & PIN -->
                    <div>
                        <div class="rounded-xl border border-slate-100 bg-slate-50/50 p-5 dark:border-slate-800 dark:bg-slate-950/20">
                            <h3 class="font-black text-sm text-slate-800 dark:text-white mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-lime-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                Pengaturan Keamanan
                            </h3>
                            
                            <form method="POST" action="{{ route('portal.parent.cashless.update', $wallet) }}" class="space-y-4">
                                @csrf
                                @method('PUT')
                                
                                <div>
                                    <label class="block text-xs font-black uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5" for="daily_limit_{{ $wallet->id }}">
                                        Limit Belanja Harian
                                    </label>
                                    <div class="relative">
                                        <span class="absolute left-3.5 top-2.5 text-xs text-slate-400 font-bold">Rp</span>
                                        <input type="number" name="daily_limit" id="daily_limit_{{ $wallet->id }}" 
                                            value="{{ $wallet->daily_limit }}" min="0" placeholder="Tanpa batas harian"
                                            class="w-full pl-9 pr-3 py-2 rounded-lg text-sm border border-slate-200 dark:border-slate-800 dark:bg-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-lime-500 font-medium">
                                    </div>
                                    <p class="text-[10px] text-slate-400 mt-1">Kosongkan untuk mengizinkan transaksi tanpa limit harian.</p>
                                </div>
                                
                                <div>
                                    <label class="block text-xs font-black uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5" for="pin_{{ $wallet->id }}">
                                        {{ $wallet->pin ? 'Ubah PIN Transaksi' : 'Setel PIN Baru' }}
                                    </label>
                                    <input type="password" name="pin" id="pin_{{ $wallet->id }}" 
                                        maxlength="6" placeholder="{{ $wallet->pin ? 'Masukkan PIN baru' : '4-6 digit angka' }}"
                                        class="w-full px-3.5 py-2 rounded-lg text-sm border border-slate-200 dark:border-slate-800 dark:bg-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-lime-500 font-medium tracking-widest">
                                    <p class="text-[10px] text-slate-400 mt-1">PIN digunakan untuk memvalidasi transaksi di atas Rp 50.000.</p>
                                </div>
                                
                                <button type="submit" class="w-full py-2.5 px-4 text-sm font-black text-white bg-lime-600 rounded-lg hover:bg-lime-700 transition duration-150 shadow-sm">
                                    Simpan Pengaturan
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="rounded-xl border border-slate-200 bg-white p-5 text-slate-500 dark:border-slate-800 dark:bg-slate-900">
                Belum ada data wallet santri untuk akun Anda.
            </div>
        @endforelse
    </div>
</div>
@endsection
