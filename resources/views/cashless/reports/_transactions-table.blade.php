<div class="overflow-x-auto rounded-xl border border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-900">
    <table class="min-w-full text-sm">
        <thead class="text-left text-xs uppercase text-slate-500"><tr><th class="p-3">No</th><th>Tipe</th><th>Arah</th><th>Nominal</th><th>Saldo Akhir</th><th>Waktu</th></tr></thead>
        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
            @forelse($transactions as $trx)
                <tr class="dark:text-slate-200"><td class="p-3">{{ $trx->transaction_number }}</td><td>{{ $trx->type }}</td><td>{{ $trx->direction }}</td><td>Rp {{ number_format($trx->amount,0,',','.') }}</td><td>Rp {{ number_format($trx->balance_after,0,',','.') }}</td><td>{{ $trx->created_at?->format('d M Y H:i') }}</td></tr>
            @empty
                <tr><td colspan="6" class="p-6 text-center text-slate-500">Belum ada mutasi.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
