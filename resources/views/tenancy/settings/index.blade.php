@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div>
        <h1 class="text-3xl font-black tracking-tight text-slate-900">Tenant Settings</h1>
        <p class="text-sm text-slate-500 mt-1">Konfigurasi pengaturan spesifik sekolah aktif saat ini.</p>
    </div>

    <!-- Update Existing Settings Card -->
    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="text-xl font-bold text-slate-900 mb-6">Pengaturan Sekolah</h2>
        
        <form action="{{ route('tenancy.settings.update') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="space-y-6 divide-y divide-slate-100">
                @forelse($settings as $index => $setting)
                    <div class="pt-6 first:pt-0 space-y-3">
                        <div class="flex items-center justify-between">
                            <div>
                                <label for="value_{{ $index }}" class="text-sm font-bold text-slate-800">{{ $setting->setting_key }}</label>
                                <p class="text-xs text-slate-400 mt-0.5">{{ $setting->description ?? 'Tidak ada deskripsi' }}</p>
                            </div>
                            <span class="inline-flex items-center rounded bg-slate-100 px-2 py-0.5 text-xs font-semibold text-slate-600 uppercase">
                                {{ $setting->value_type }}
                            </span>
                        </div>
                        
                        <input type="hidden" name="settings[{{ $index }}][key]" value="{{ $setting->setting_key }}">
                        <input type="hidden" name="settings[{{ $index }}][type]" value="{{ $setting->value_type }}">
                        <input type="hidden" name="settings[{{ $index }}][description]" value="{{ $setting->description }}">
                        <input type="hidden" name="settings[{{ $index }}][is_public]" value="{{ $setting->is_public ? '1' : '0' }}">

                        @if($setting->value_type === 'boolean' || $setting->value_type === 'bool')
                            <select id="value_{{ $index }}" name="settings[{{ $index }}][value]" class="block w-full rounded-2xl border border-slate-200 px-4 py-3 text-slate-800 focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="true" {{ $setting->setting_value === 'true' || $setting->setting_value === '1' ? 'selected' : '' }}>True</option>
                                <option value="false" {{ $setting->setting_value === 'false' || $setting->setting_value === '0' ? 'selected' : '' }}>False</option>
                            </select>
                        @else
                            <input type="text" id="value_{{ $index }}" name="settings[{{ $index }}][value]" value="{{ $setting->setting_value }}" class="block w-full rounded-2xl border border-slate-200 px-4 py-3 text-slate-800 focus:border-indigo-500 focus:ring-indigo-500">
                        @endif
                    </div>
                @empty
                    <p class="text-sm text-slate-500 py-4 text-center">Belum ada pengaturan yang dikonfigurasi. Anda dapat menambahkannya menggunakan panel di bawah.</p>
                @endforelse
            </div>

            @if($settings->isNotEmpty())
                <div class="pt-6 border-t border-slate-100">
                    <button type="submit" class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-slate-900 to-indigo-950 px-5 py-3 font-bold text-white shadow-md transition-all hover:scale-[1.02] active:scale-[0.98]">
                        Simpan Perubahan
                    </button>
                </div>
            @endif
        </form>
    </div>

    <!-- Create Setting Card -->
    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="text-xl font-bold text-slate-900 mb-4">Tambah Pengaturan Baru</h2>
        
        <form action="{{ route('tenancy.settings.update') }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="grid gap-4 md:grid-cols-2">
                <div class="space-y-2">
                    <label for="new_key" class="text-sm font-bold text-slate-700">Setting Key</label>
                    <input type="text" id="new_key" name="settings[0][key]" placeholder="CONTOH_PENGATURAN" required class="block w-full rounded-2xl border border-slate-200 px-4 py-3 text-slate-800 focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                
                <div class="space-y-2">
                    <label for="new_type" class="text-sm font-bold text-slate-700">Tipe Nilai</label>
                    <select id="new_type" name="settings[0][type]" required class="block w-full rounded-2xl border border-slate-200 px-4 py-3 text-slate-800 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="string">String</option>
                        <option value="integer">Integer</option>
                        <option value="float">Float</option>
                        <option value="boolean">Boolean</option>
                        <option value="array">Array/JSON</option>
                    </select>
                </div>
            </div>

            <div class="space-y-2">
                <label for="new_value" class="text-sm font-bold text-slate-700">Nilai Pengaturan</label>
                <input type="text" id="new_value" name="settings[0][value]" placeholder="Nilai..." required class="block w-full rounded-2xl border border-slate-200 px-4 py-3 text-slate-800 focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <div class="space-y-2">
                <label for="new_desc" class="text-sm font-bold text-slate-700">Deskripsi</label>
                <textarea id="new_desc" name="settings[0][description]" placeholder="Penjelasan singkat..." rows="2" class="block w-full rounded-2xl border border-slate-200 px-4 py-3 text-slate-800 focus:border-indigo-500 focus:ring-indigo-500"></textarea>
            </div>

            <div class="flex items-center space-x-3 py-2">
                <input type="checkbox" id="new_public" name="settings[0][is_public]" value="1" class="h-4.5 w-4.5 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                <label for="new_public" class="text-sm font-bold text-slate-700">Dapat Diakses Publik (Is Public)</label>
            </div>

            <div class="pt-4">
                <button type="submit" class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-slate-900 to-indigo-950 px-5 py-3 font-bold text-white shadow-md transition-all hover:scale-[1.02] active:scale-[0.98]">
                    Tambah Pengaturan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
