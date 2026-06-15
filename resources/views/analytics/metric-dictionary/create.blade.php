@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-6">
    <x-analytics.shell title="Tambah Metrik Baru" subtitle="Definisikan metrik analitik baru untuk Kamus Metrik platform.">
        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 max-w-2xl">
            <form action="{{ route('analytics.metric-dictionary.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300">Metric Key (Unique)</label>
                    <input type="text" name="metric_key" required placeholder="e.g. academic.hafalan_records" class="mt-1 w-full rounded-lg border border-slate-200 bg-white p-2 text-sm dark:border-slate-800 dark:bg-slate-950">
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300">Nama Metrik</label>
                    <input type="text" name="name" required placeholder="e.g. Hafalan Records" class="mt-1 w-full rounded-lg border border-slate-200 bg-white p-2 text-sm dark:border-slate-800 dark:bg-slate-950">
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300">Kategori</label>
                    <input type="text" name="category" required placeholder="e.g. academic" class="mt-1 w-full rounded-lg border border-slate-200 bg-white p-2 text-sm dark:border-slate-800 dark:bg-slate-950">
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300">Deskripsi</label>
                    <textarea name="description" placeholder="Penjelasan tentang metrik ini..." class="mt-1 w-full rounded-lg border border-slate-200 bg-white p-2 text-sm dark:border-slate-800 dark:bg-slate-950" rows="3"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300">Formula Rumus</label>
                    <input type="text" name="formula" placeholder="e.g. count(records)" class="mt-1 w-full rounded-lg border border-slate-200 bg-white p-2 text-sm dark:border-slate-800 dark:bg-slate-950">
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300">Unit Satuan</label>
                        <input type="text" name="unit" placeholder="e.g. count, percent, currency" class="mt-1 w-full rounded-lg border border-slate-200 bg-white p-2 text-sm dark:border-slate-800 dark:bg-slate-950">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300">Tipe Agregasi</label>
                        <input type="text" name="aggregation_type" placeholder="e.g. sum, avg" class="mt-1 w-full rounded-lg border border-slate-200 bg-white p-2 text-sm dark:border-slate-800 dark:bg-slate-950">
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <label class="flex items-center gap-2 text-sm font-bold text-slate-700 dark:text-slate-300">
                        <input type="checkbox" name="is_sensitive" value="1" class="rounded border-slate-200 dark:border-slate-800">
                        Sensitif (Sensitive Data)
                    </label>
                    <label class="flex items-center gap-2 text-sm font-bold text-slate-700 dark:text-slate-300">
                        <input type="checkbox" name="is_active" value="1" checked class="rounded border-slate-200 dark:border-slate-800">
                        Aktif
                    </label>
                </div>

                <div class="flex justify-end gap-2 pt-4">
                    <a href="{{ route('analytics.metric-dictionary.index') }}" class="rounded-lg bg-slate-100 px-4 py-2 text-sm font-bold text-slate-700 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300">Batal</a>
                    <button type="submit" class="rounded-lg bg-slate-950 px-4 py-2 text-sm font-bold text-white transition hover:bg-slate-800 dark:bg-lime-400 dark:text-slate-950">Simpan Metrik</button>
                </div>
            </form>
        </div>
    </x-analytics.shell>
</div>
@endsection
