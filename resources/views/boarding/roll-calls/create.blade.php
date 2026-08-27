@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between border-b border-slate-200/80 pb-5 dark:border-slate-800">
        <div>
            <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-50 text-[11px] font-bold text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/40 mb-1">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                Roll Call Asrama
            </div>
            <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white">Mulai Sesi Absen Baru</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400">Pilih gedung asrama dan kamar untuk memuat daftar santri otomatis.</p>
        </div>
        <a href="{{ route('boarding.roll-calls.index') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-bold text-slate-600 hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-slate-800 transition shadow-2xs">
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
        <form action="{{ route('boarding.roll-calls.store') }}" method="POST" class="space-y-5">
            @csrf

            <!-- Dormitory Scope -->
            <div>
                <label for="boarding_dormitory_id" class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">Cakupan Gedung Asrama <span class="text-rose-500">*</span></label>
                <select name="boarding_dormitory_id" id="boarding_dormitory_id" required class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 focus:outline-none">
                    <option value="" disabled selected>-- Pilih Gedung Asrama --</option>
                    @foreach($dormitories as $dorm)
                        <option value="{{ $dorm->id }}" {{ old('boarding_dormitory_id') == $dorm->id ? 'selected' : '' }}>
                            {{ $dorm->name }} (Asrama {{ $dorm->gender === 'male' ? 'Putra' : ($dorm->gender === 'female' ? 'Putri' : 'Umum') }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Room Scope -->
            <div>
                <label for="boarding_room_id" class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">Cakupan Kamar (Opsional)</label>
                <select name="boarding_room_id" id="boarding_room_id" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 focus:outline-none">
                    <option value="">-- Semua Kamar dalam Asrama Ini --</option>
                    @foreach($rooms as $room)
                        <option value="{{ $room->id }}" data-dormitory="{{ $room->boarding_dormitory_id }}" {{ old('boarding_room_id') == $room->id ? 'selected' : '' }}>
                            {{ $room->name }} (Lantai {{ $room->floor ?? '1' }})
                        </option>
                    @endforeach
                </select>
                <p class="text-2xs text-slate-400 mt-1.5">Kosongkan jika ingin mengabsen seluruh kamar di asrama yang dipilih sekaligus.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Date -->
                <div>
                    <label for="session_date" class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">Tanggal Absensi <span class="text-rose-500">*</span></label>
                    <input type="date" name="session_date" id="session_date" value="{{ old('session_date', date('Y-m-d')) }}" required class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 focus:outline-none">
                </div>

                <!-- Session Type -->
                <div>
                    <label for="session_type" class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">Waktu Sesi Absen <span class="text-rose-500">*</span></label>
                    <select name="session_type" id="session_type" required class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 focus:outline-none">
                        <option value="night" {{ old('session_type', 'night') === 'night' ? 'selected' : '' }}>🌙 Malam (Pengecekan Kamar)</option>
                        <option value="morning" {{ old('session_type') === 'morning' ? 'selected' : '' }}>🌅 Pagi (Bangun & Subuh)</option>
                        <option value="afternoon" {{ old('session_type') === 'afternoon' ? 'selected' : '' }}>☀️ Sore (Ba'da Ashar)</option>
                        <option value="custom" {{ old('session_type') === 'custom' ? 'selected' : '' }}>⏱️ Sesi Khusus / Sidak</option>
                    </select>
                </div>
            </div>

            <div class="border-t border-slate-100 dark:border-slate-800/80 pt-5 mt-6 flex items-center justify-between">
                <a href="{{ route('boarding.roll-calls.index') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-bold text-slate-600 hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-slate-800 transition">Batal</a>
                <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 px-6 py-2.5 text-sm font-bold text-white shadow-md shadow-emerald-600/20 active:scale-95 transition-all">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                    <span>Mulai & Muat Santri</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dormSelect = document.getElementById('boarding_dormitory_id');
        const roomSelect = document.getElementById('boarding_room_id');
        const originalOptions = Array.from(roomSelect.options);

        function updateRooms() {
            const selectedDormId = dormSelect.value;
            roomSelect.innerHTML = '';
            
            originalOptions.forEach(option => {
                if (!option.value || option.dataset.dormitory === selectedDormId) {
                    roomSelect.appendChild(option.cloneNode(true));
                }
            });
        }

        dormSelect.addEventListener('change', updateRooms);
        if (dormSelect.value) {
            updateRooms();
        }
    });
</script>
@endsection
