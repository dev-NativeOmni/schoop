@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex flex-col border-b border-slate-200 pb-4 dark:border-slate-800 gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Tambah Catatan Kesehatan</h1>
            <p class="text-sm text-slate-500">Catat kondisi medis, sakit, atau rujukan santri asrama.</p>
        </div>
        <a href="{{ route('boarding.health-logs.index') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-350 dark:hover:bg-slate-950">
            Kembali
        </a>
    </div>

    <!-- Alert Error -->
    @if($errors->any())
        <div class="rounded-xl bg-rose-50 p-4 text-sm text-rose-850 dark:bg-rose-950/30 dark:text-rose-400">
            <ul class="list-disc pl-5 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <form action="{{ route('boarding.health-logs.store') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Student Selection -->
            <div>
                <label for="student_id" class="block text-sm font-semibold text-slate-700 dark:text-slate-350 mb-1">Pilih Santri <span class="text-rose-500">*</span></label>
                <select name="student_id" id="student_id" required class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-800 dark:bg-slate-950 dark:text-white">
                    <option value="" disabled selected>-- Pilih Santri --</option>
                    @foreach($students as $st)
                        <option value="{{ $st->id }}" {{ old('student_id') == $st->id ? 'selected' : '' }}>
                            {{ $st->full_name }} (NIS: {{ $st->student_number ?? '-' }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Condition Title -->
                <div>
                    <label for="condition_title" class="block text-sm font-semibold text-slate-700 dark:text-slate-350 mb-1">Keluhan / Diagnosa Awal <span class="text-rose-500">*</span></label>
                    <input type="text" name="condition_title" id="condition_title" value="{{ old('condition_title') }}" required placeholder="Contoh: Demam tinggi, Batuk pilek, Keseleo" class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-800 dark:bg-slate-950 dark:text-white">
                </div>

                <!-- Severity -->
                <div>
                    <label for="severity" class="block text-sm font-semibold text-slate-700 dark:text-slate-350 mb-1">Tingkat Keparahan <span class="text-rose-500">*</span></label>
                    <select name="severity" id="severity" required class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-800 dark:bg-slate-950 dark:text-white">
                        <option value="low" {{ old('severity') === 'low' ? 'selected' : '' }}>Ringan (Dapat beraktivitas biasa)</option>
                        <option value="medium" {{ old('severity') === 'medium' ? 'selected' : '' }}>Sedang (Butuh istirahat di asrama/UKS)</option>
                        <option value="high" {{ old('severity') === 'high' ? 'selected' : '' }}>Parah (Butuh observasi ketat / periksa UKS)</option>
                        <option value="critical" {{ old('severity', 'low') === 'critical' ? 'selected' : '' }}>Gawat / Darurat (Harus dirujuk RS/Klinik)</option>
                    </select>
                </div>
            </div>

            <!-- Logged At -->
            <div>
                <label for="logged_at" class="block text-sm font-semibold text-slate-700 dark:text-slate-350 mb-1">Waktu Kejadian <span class="text-rose-500">*</span></label>
                <input type="datetime-local" name="logged_at" id="logged_at" value="{{ old('logged_at', date('Y-m-d\TH:i')) }}" required class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-800 dark:bg-slate-950 dark:text-white">
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-semibold text-slate-700 dark:text-slate-350 mb-1">Deskripsi Gejala / Keterangan Tambahan</label>
                <textarea name="description" id="description" rows="3" placeholder="Sebutkan detail gejala yang dialami santri..." class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-800 dark:bg-slate-950 dark:text-white">{{ old('description') }}</textarea>
            </div>

            <!-- Action Taken -->
            <div>
                <label for="action_taken" class="block text-sm font-semibold text-slate-700 dark:text-slate-350 mb-1">Tindakan / Penanganan yang Diberikan</label>
                <textarea name="action_taken" id="action_taken" rows="2" placeholder="Contoh: Diberikan paracetamol 500mg dan istirahat di UKS..." class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-800 dark:bg-slate-950 dark:text-white">{{ old('action_taken') }}</textarea>
            </div>

            <div class="border-t border-slate-100 pt-4 dark:border-slate-800 flex justify-end gap-3">
                <a href="{{ route('boarding.health-logs.index') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-350 dark:hover:bg-slate-950">Batal</a>
                <button type="submit" class="rounded-xl bg-gradient-to-r from-emerald-500 to-teal-600 px-4 py-2 text-sm font-semibold text-white shadow-md hover:opacity-90 transition">Simpan Catatan</button>
            </div>
        </form>
    </div>
</div>
@endsection
