@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto space-y-6">
    <div>
        <h1 class="text-3xl font-black tracking-tight text-slate-900 dark:text-slate-100">Edit Domain Mapping</h1>
        <p class="text-sm text-slate-500 mt-1">Perbarui domain atau catatan mapping untuk tenant sekolah.</p>
    </div>

    <div class="rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm">
        <form action="{{ route('white-label.domains.update', ['domain' => $domain->id, 'school_id' => $school->id]) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="rounded-2xl border border-amber-200/70 bg-amber-50/60 p-4 text-xs leading-relaxed text-amber-800 dark:border-amber-900/40 dark:bg-amber-950/20 dark:text-amber-300">
                Jika nama domain diubah, status mapping akan kembali menjadi <strong>pending</strong> dan token verifikasi baru akan dibuat.
            </div>

            <div class="space-y-2">
                <label for="domain" class="text-sm font-bold text-slate-700 dark:text-slate-350">Nama Domain / Subdomain <span class="text-red-500">*</span></label>
                <input type="text" id="domain" name="domain" value="{{ old('domain', $domain->domain) }}" required class="block w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950/40 px-4 py-3 text-slate-800 dark:text-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('domain') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="space-y-2">
                <label for="type" class="text-sm font-bold text-slate-700 dark:text-slate-350">Tipe Domain</label>
                <select id="type" name="type" required class="block w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950/40 px-4 py-3 text-slate-800 dark:text-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="subdomain" @selected(old('type', $domain->type) === 'subdomain')>Subdomain Internal (*.schoop.id)</option>
                    <option value="custom_domain" @selected(old('type', $domain->type) === 'custom_domain')>Domain Kustom Eksternal (FQDN)</option>
                </select>
                @error('type') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="space-y-2">
                <label for="notes" class="text-sm font-bold text-slate-700 dark:text-slate-350">Catatan Tambahan</label>
                <textarea id="notes" name="notes" rows="3" class="block w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950/40 px-4 py-3 text-slate-800 dark:text-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('notes', $domain->notes) }}</textarea>
                @error('notes') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="pt-4 flex items-center gap-3">
                <button type="submit" class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-slate-900 to-indigo-950 px-5 py-3 font-bold text-white shadow-md transition-all hover:scale-[1.02] active:scale-[0.98] text-sm">
                    Simpan Perubahan
                </button>
                <a href="{{ route('white-label.domains.index', ['school_id' => $school->id]) }}" class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 dark:border-slate-800 px-5 py-3 font-bold text-slate-700 dark:text-slate-300 transition-all hover:bg-slate-50 dark:hover:bg-slate-950 text-sm">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
