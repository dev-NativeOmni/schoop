@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto space-y-6">
    <div>
        <h1 class="text-3xl font-black tracking-tight text-slate-900 dark:text-slate-100">Tambah Domain Mapping</h1>
        <p class="text-sm text-slate-500 mt-1">Daftarkan subdomain baru atau FQDN eksternal untuk tenant sekolah.</p>
    </div>

    <!-- Create Card -->
    <div class="rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm">
        <form action="{{ route('white-label.domains.store', ['school_id' => $school->id]) }}" method="POST" class="space-y-4">
            @csrf

            <div class="space-y-2">
                <label for="domain" class="text-sm font-bold text-slate-700 dark:text-slate-350">Nama Domain / Subdomain <span class="text-red-500">*</span></label>
                <input type="text" id="domain" name="domain" placeholder="alazhar7.hafizplus.id atau portal.sekolah.sch.id" required class="block w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950/40 px-4 py-3 text-slate-800 dark:text-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                <p class="text-[10px] text-slate-400">Masukkan nama domain tanpa `http://` atau `https://` atau garis miring `/` di akhir.</p>
                @error('domain') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="space-y-2">
                <label for="type" class="text-sm font-bold text-slate-700 dark:text-slate-350">Tipe Domain</label>
                <select id="type" name="type" required class="block w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950/40 px-4 py-3 text-slate-800 dark:text-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="subdomain">Subdomain Internal (*.hafizplus.id)</option>
                    <option value="custom_domain">Domain Kustom Eksternal (FQDN)</option>
                </select>
                @error('type') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="space-y-2">
                <label for="notes" class="text-sm font-bold text-slate-700 dark:text-slate-350">Catatan Tambahan</label>
                <textarea id="notes" name="notes" rows="3" placeholder="Contoh: Digunakan untuk portal utama siswa..." class="block w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950/40 px-4 py-3 text-slate-800 dark:text-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                @error('notes') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="pt-4 flex items-center gap-3">
                <button type="submit" class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-slate-900 to-indigo-950 px-5 py-3 font-bold text-white shadow-md transition-all hover:scale-[1.02] active:scale-[0.98] text-sm">
                    Daftarkan Domain
                </button>
                <a href="{{ route('white-label.domains.index', ['school_id' => $school->id]) }}" class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 dark:border-slate-800 px-5 py-3 font-bold text-slate-700 dark:text-slate-300 transition-all hover:bg-slate-50 dark:hover:bg-slate-950 text-sm">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
