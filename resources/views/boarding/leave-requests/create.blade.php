@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between border-b border-slate-200 pb-4 dark:border-slate-800">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Ajukan Izin Keluar Santri</h1>
            <p class="text-sm text-slate-500">Ajukan izin keluar asrama bagi santri yang terdaftar aktif di boarding.</p>
        </div>
        <a href="{{ route('boarding.leave-requests.index') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-350 dark:hover:bg-slate-950">
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
        <form action="{{ route('boarding.leave-requests.store') }}" method="POST" class="space-y-4">
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
                <p class="text-xs text-slate-400 mt-1">Hanya menampilkan santri yang saat ini bertempat di asrama aktif.</p>
            </div>

            <!-- Type -->
            <div>
                <label for="type" class="block text-sm font-semibold text-slate-700 dark:text-slate-350 mb-1">Jenis Izin <span class="text-rose-500">*</span></label>
                <select name="type" id="type" required class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-800 dark:bg-slate-950 dark:text-white">
                    <option value="short_leave" {{ old('type') === 'short_leave' ? 'selected' : '' }}>Izin Keluar Sebentar (Kembali di hari yang sama)</option>
                    <option value="overnight_leave" {{ old('type') === 'overnight_leave' ? 'selected' : '' }}>Izin Bermalam</option>
                    <option value="home_visit" {{ old('type') === 'home_visit' ? 'selected' : '' }}>Pulang ke Rumah</option>
                    <option value="medical_leave" {{ old('type') === 'medical_leave' ? 'selected' : '' }}>Izin Medis / Kesehatan</option>
                    <option value="emergency_leave" {{ old('type') === 'emergency_leave' ? 'selected' : '' }}>Izin Darurat</option>
                </select>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Start Datetime -->
                <div>
                    <label for="leave_start_at" class="block text-sm font-semibold text-slate-700 dark:text-slate-350 mb-1">Waktu Mulai Keluar <span class="text-rose-500">*</span></label>
                    <input type="datetime-local" name="leave_start_at" id="leave_start_at" value="{{ old('leave_start_at', date('Y-m-d\TH:i')) }}" required class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-800 dark:bg-slate-950 dark:text-white">
                </div>

                <!-- End Datetime -->
                <div>
                    <label for="leave_end_at" class="block text-sm font-semibold text-slate-700 dark:text-slate-350 mb-1">Waktu Selesai / Rencana Kembali <span class="text-rose-500">*</span></label>
                    <input type="datetime-local" name="leave_end_at" id="leave_end_at" value="{{ old('leave_end_at', date('Y-m-d\TH:i', strtotime('+2 hours'))) }}" required class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-800 dark:bg-slate-950 dark:text-white">
                </div>
            </div>

            <!-- Destination -->
            <div>
                <label for="destination" class="block text-sm font-semibold text-slate-700 dark:text-slate-350 mb-1">Tujuan Kepergian <span class="text-rose-500">*</span></label>
                <input type="text" name="destination" id="destination" value="{{ old('destination') }}" required placeholder="Contoh: Rumah orang tua (Bandung), Klinik Sehat, Minimarket dekat sekolah" class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-800 dark:bg-slate-950 dark:text-white">
            </div>

            <!-- Reason -->
            <div>
                <label for="reason" class="block text-sm font-semibold text-slate-700 dark:text-slate-350 mb-1">Alasan Pengajuan <span class="text-rose-500">*</span></label>
                <textarea name="reason" id="reason" rows="3" required placeholder="Jelaskan alasan izin secara detail..." class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-800 dark:bg-slate-950 dark:text-white">{{ old('reason') }}</textarea>
            </div>

            <div class="border-t border-slate-100 pt-4 dark:border-slate-800 flex justify-end gap-3">
                <a href="{{ route('boarding.leave-requests.index') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-350 dark:hover:bg-slate-950">Batal</a>
                <button type="submit" class="rounded-xl bg-gradient-to-r from-emerald-500 to-teal-600 px-4 py-2 text-sm font-semibold text-white shadow-md hover:opacity-90 transition">Kirim Pengajuan Izin</button>
            </div>
        </form>
    </div>
</div>
@endsection
