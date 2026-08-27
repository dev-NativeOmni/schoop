@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between border-b border-slate-200/80 pb-5 dark:border-slate-800">
        <div>
            <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-50 text-[11px] font-bold text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/40 mb-1">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                E-Perizinan Santri
            </div>
            <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white">Ajukan Izin Keluar Asrama</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400">Pengajuan surat jalan keluar/pulang bagi santri yang aktif di asrama.</p>
        </div>
        <a href="{{ route('boarding.leave-requests.index') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-bold text-slate-600 hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-slate-800 transition shadow-2xs">
            &larr; Kembali
        </a>
    </div>

    @if($errors->any())
        <div class="rounded-xl bg-rose-50 p-4 text-xs text-rose-800 border border-rose-200 dark:bg-rose-950/30 dark:text-rose-300 dark:border-rose-900/40">
            <ul class="list-disc pl-5 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card-natural p-6">
        <form action="{{ route('boarding.leave-requests.store') }}" method="POST" class="space-y-5">
            @csrf

            <!-- Student Selection -->
            <div>
                <label for="student_id" class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">Pilih Santri <span class="text-rose-500">*</span></label>
                <select name="student_id" id="student_id" required class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 focus:outline-none">
                    <option value="" disabled selected>-- Pilih Santri --</option>
                    @foreach($students as $st)
                        <option value="{{ $st->id }}" {{ old('student_id') == $st->id ? 'selected' : '' }}>
                            {{ $st->full_name }} (NIS: {{ $st->student_number ?? ($st->nis ?? '-') }})
                        </option>
                    @endforeach
                </select>
                <p class="text-2xs text-slate-400 mt-1.5">Hanya menampilkan santri yang saat ini bertempat di asrama aktif.</p>
            </div>

            <!-- Type -->
            <div>
                <label for="type" class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">Jenis Izin <span class="text-rose-500">*</span></label>
                <select name="type" id="type" required class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 focus:outline-none">
                    <option value="short_leave" {{ old('type') === 'short_leave' ? 'selected' : '' }}>⏱️ Izin Keluar Sebentar (Kembali di hari yang sama)</option>
                    <option value="overnight_leave" {{ old('type') === 'overnight_leave' ? 'selected' : '' }}>🌙 Izin Bermalam (Overnight)</option>
                    <option value="home_visit" {{ old('type') === 'home_visit' ? 'selected' : '' }}>🏠 Pulang ke Rumah (Home Visit)</option>
                    <option value="medical_leave" {{ old('type') === 'medical_leave' ? 'selected' : '' }}>🏥 Izin Medis / Klinik UKS</option>
                    <option value="emergency_leave" {{ old('type') === 'emergency_leave' ? 'selected' : '' }}>🚨 Izin Darurat</option>
                </select>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Start Datetime -->
                <div>
                    <label for="leave_start_at" class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">Waktu Mulai Keluar <span class="text-rose-500">*</span></label>
                    <input type="datetime-local" name="leave_start_at" id="leave_start_at" value="{{ old('leave_start_at', date('Y-m-d\TH:i')) }}" required class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 focus:outline-none">
                </div>

                <!-- End Datetime -->
                <div>
                    <label for="leave_end_at" class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">Waktu Selesai / Rencana Kembali <span class="text-rose-500">*</span></label>
                    <input type="datetime-local" name="leave_end_at" id="leave_end_at" value="{{ old('leave_end_at', date('Y-m-d\TH:i', strtotime('+2 hours'))) }}" required class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 focus:outline-none">
                </div>
            </div>

            <!-- Destination -->
            <div>
                <label for="destination" class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">Tujuan Kepergian <span class="text-rose-500">*</span></label>
                <input type="text" name="destination" id="destination" value="{{ old('destination') }}" required placeholder="Contoh: Rumah orang tua (Bandung), Klinik Sehat, Minimarket dekat sekolah" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 focus:outline-none">
            </div>

            <!-- Reason -->
            <div>
                <label for="reason" class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">Alasan Pengajuan <span class="text-rose-500">*</span></label>
                <textarea name="reason" id="reason" rows="3" required placeholder="Jelaskan alasan izin secara detail..." class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 focus:outline-none">{{ old('reason') }}</textarea>
            </div>

            <div class="border-t border-slate-100 dark:border-slate-800/80 pt-5 mt-6 flex items-center justify-between">
                <a href="{{ route('boarding.leave-requests.index') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-bold text-slate-600 hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-slate-800 transition">Batal</a>
                <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 px-6 py-2.5 text-sm font-bold text-white shadow-md shadow-emerald-600/20 active:scale-95 transition-all">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" /></svg>
                    <span>Kirim Pengajuan Izin</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
