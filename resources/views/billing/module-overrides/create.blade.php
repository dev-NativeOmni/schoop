@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <!-- Breadcrumbs -->
    <div class="flex items-center space-x-2 text-sm text-slate-500">
        <a href="{{ route('billing.module-overrides.index') }}" class="hover:text-indigo-600">Overrides</a>
        <span>/</span>
        <span class="text-slate-900 dark:text-white font-semibold">Tambah Manual Override</span>
    </div>

    <!-- Header -->
    <div>
        <h1 class="text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">Tambah Manual Override</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400">Atur hak modul pengecualian secara manual untuk sekolah tertentu.</p>
    </div>

    <!-- Form Card -->
    <div class="bg-white dark:bg-slate-900 shadow-xl rounded-2xl border border-slate-100 dark:border-slate-800 p-6 md:p-8">
        <form action="{{ route('billing.module-overrides.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- School -->
                <div>
                    <label for="school_id" class="block text-sm font-bold text-slate-700 dark:text-slate-350 mb-2">Sekolah / Tenant</label>
                    <select id="school_id" name="school_id" required class="w-full rounded-xl border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition">
                        <option value="">-- Pilih Sekolah --</option>
                        @foreach($schools as $school)
                            <option value="{{ $school->id }}" {{ old('school_id') == $school->id ? 'selected' : '' }}>{{ $school->name }}</option>
                        @endforeach
                    </select>
                    @error('school_id')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Module -->
                <div>
                    <label for="system_module_id" class="block text-sm font-bold text-slate-700 dark:text-slate-350 mb-2">Modul Sistem</label>
                    <select id="system_module_id" name="system_module_id" required class="w-full rounded-xl border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition">
                        <option value="">-- Pilih Modul --</option>
                        @foreach($modules as $module)
                            <option value="{{ $module->id }}" {{ old('system_module_id') == $module->id ? 'selected' : '' }}>{{ $module->name }} ({{ $module->module_key }})</option>
                        @endforeach
                    </select>
                    @error('system_module_id')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status Override (Enabled / Disabled) -->
                <div>
                    <label for="is_enabled" class="block text-sm font-bold text-slate-700 dark:text-slate-350 mb-2">Status Override</label>
                    <select id="is_enabled" name="is_enabled" required class="w-full rounded-xl border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition">
                        <option value="1" {{ old('is_enabled') == '1' ? 'selected' : '' }}>ENABLED (Aktifkan modul walaupun di luar plan)</option>
                        <option value="0" {{ old('is_enabled') == '0' ? 'selected' : '' }}>DISABLED (Kunci modul walaupun plan memilikinya)</option>
                    </select>
                    @error('is_enabled')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Expires At -->
                <div>
                    <label for="expires_at" class="block text-sm font-bold text-slate-700 dark:text-slate-350 mb-2">Masa Berlaku Override (Optional)</label>
                    <input type="date" id="expires_at" name="expires_at" value="{{ old('expires_at') }}" class="w-full rounded-xl border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition">
                    <p class="text-slate-400 text-[10px] mt-1">Kosongkan jika override berlaku selamanya.</p>
                    @error('expires_at')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Reason -->
            <div>
                <label for="reason" class="block text-sm font-bold text-slate-700 dark:text-slate-350 mb-2">Alasan / Catatan Override</label>
                <input type="text" id="reason" name="reason" value="{{ old('reason') }}" placeholder="e.g. trial_addon, manual_enable, enterprise_custom" class="w-full rounded-xl border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition">
                @error('reason')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Action buttons -->
            <div class="flex items-center justify-end space-x-3 border-t border-slate-100 dark:border-slate-800 pt-6">
                <a href="{{ route('billing.module-overrides.index') }}" class="px-4 py-2 text-sm font-bold text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 rounded-xl transition">Batal</a>
                <button type="submit" class="px-5 py-2 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl transition shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Simpan Override
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
