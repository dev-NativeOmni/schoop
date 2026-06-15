@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between border-b border-slate-200 pb-4 dark:border-slate-800">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Tambah Catatan Kedisiplinan / Prestasi</h1>
            <p class="text-sm text-slate-500">Catat pelanggaran tata tertib asrama atau pencapaian prestasi santri.</p>
        </div>
        <a href="{{ route('boarding.discipline-logs.index') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-350 dark:hover:bg-slate-950">
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
        <form action="{{ route('boarding.discipline-logs.store') }}" method="POST" class="space-y-4">
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
                <!-- Type -->
                <div>
                    <label for="type" class="block text-sm font-semibold text-slate-700 dark:text-slate-350 mb-1">Jenis Log <span class="text-rose-500">*</span></label>
                    <select name="type" id="type" required class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-800 dark:bg-slate-950 dark:text-white">
                        <option value="violation" {{ old('type') === 'violation' ? 'selected' : '' }}>Pelanggaran (Violation)</option>
                        <option value="warning" {{ old('type') === 'warning' ? 'selected' : '' }}>Peringatan (Warning)</option>
                        <option value="achievement" {{ old('type') === 'achievement' ? 'selected' : '' }}>Prestasi (Achievement)</option>
                        <option value="note" {{ old('type') === 'note' ? 'selected' : '' }}>Catatan Umum (Note)</option>
                    </select>
                </div>

                <!-- Category -->
                <div>
                    <label for="category" class="block text-sm font-semibold text-slate-700 dark:text-slate-350 mb-1">Kategori <span class="text-rose-500">*</span></label>
                    <input type="text" name="category" id="category" value="{{ old('category') }}" required placeholder="Contoh: Kerapian, Ibadah, Kebersihan" class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-800 dark:bg-slate-950 dark:text-white">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Points -->
                <div>
                    <label for="points" class="block text-sm font-semibold text-slate-700 dark:text-slate-350 mb-1">Poin Sikap (Positif/Negatif) <span class="text-rose-500">*</span></label>
                    <input type="number" name="points" id="points" value="{{ old('points', 0) }}" required placeholder="Contoh: -5 (pelanggaran) atau 10 (prestasi)" class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-800 dark:bg-slate-950 dark:text-white">
                    <p class="text-2xs text-slate-400 mt-1">Gunakan tanda minus (-) untuk poin pelanggaran dan angka biasa untuk poin prestasi.</p>
                </div>

                <!-- Logged At -->
                <div>
                    <label for="logged_at" class="block text-sm font-semibold text-slate-700 dark:text-slate-350 mb-1">Waktu Kejadian <span class="text-rose-500">*</span></label>
                    <input type="datetime-local" name="logged_at" id="logged_at" value="{{ old('logged_at', date('Y-m-d\TH:i')) }}" required class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-800 dark:bg-slate-950 dark:text-white">
                </div>
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-semibold text-slate-700 dark:text-slate-350 mb-1">Deskripsi / Detail Kronologi <span class="text-rose-500">*</span></label>
                <textarea name="description" id="description" rows="3" required placeholder="Jelaskan detail pelanggaran/prestasi/catatan..." class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-800 dark:bg-slate-950 dark:text-white">{{ old('description') }}</textarea>
            </div>

            <!-- Action Taken -->
            <div>
                <label for="action_taken" class="block text-sm font-semibold text-slate-700 dark:text-slate-350 mb-1">Tindakan / Sanksi / Penghargaan yang Diberikan</label>
                <textarea name="action_taken" id="action_taken" rows="2" placeholder="Contoh: Diberikan teguran lisan dan tugas membersihkan koridor asrama..." class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-800 dark:bg-slate-955 dark:text-white">{{ old('action_taken') }}</textarea>
            </div>

            <div class="border-t border-slate-100 pt-4 dark:border-slate-800 flex justify-end gap-3">
                <a href="{{ route('boarding.discipline-logs.index') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-350 dark:hover:bg-slate-950">Batal</a>
                <button type="submit" class="rounded-xl bg-gradient-to-r from-emerald-500 to-teal-600 px-4 py-2 text-sm font-semibold text-white shadow-md hover:opacity-90 transition">Simpan Catatan</button>
            </div>
        </form>
    </div>
</div>
@endsection
