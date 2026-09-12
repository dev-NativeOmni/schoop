@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex flex-col border-b border-slate-200 pb-4 dark:border-slate-800 gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Mulai Sesi Absen Baru</h1>
            <p class="text-sm text-slate-500">Mulai sesi roll call baru untuk asrama atau kamar tertentu.</p>
        </div>
        <a href="{{ route('boarding.roll-calls.index') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-350 dark:hover:bg-slate-950">
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
        <form action="{{ route('boarding.roll-calls.store') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Dormitory Scope -->
            <div>
                <label for="boarding_dormitory_id" class="block text-sm font-semibold text-slate-700 dark:text-slate-350 mb-1">Cakupan Asrama <span class="text-rose-500">*</span></label>
                <select name="boarding_dormitory_id" id="boarding_dormitory_id" required class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-800 dark:bg-slate-950 dark:text-white">
                    <option value="" disabled selected>-- Pilih Asrama --</option>
                    @foreach($dormitories as $dorm)
                        <option value="{{ $dorm->id }}" {{ old('boarding_dormitory_id') == $dorm->id ? 'selected' : '' }}>
                            {{ $dorm->name }} (Gender: {{ $dorm->gender === 'male' ? 'Putra' : ($dorm->gender === 'female' ? 'Putri' : 'Campuran') }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Room Scope -->
            <div>
                <label for="boarding_room_id" class="block text-sm font-semibold text-slate-700 dark:text-slate-350 mb-1">Cakupan Kamar (Opsional)</label>
                <select name="boarding_room_id" id="boarding_room_id" class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-800 dark:bg-slate-950 dark:text-white">
                    <option value="">-- Semua Kamar --</option>
                    @foreach($rooms as $room)
                        <option value="{{ $room->id }}" data-dormitory="{{ $room->boarding_dormitory_id }}" {{ old('boarding_room_id') == $room->id ? 'selected' : '' }}>
                            {{ $room->name }}
                        </option>
                    @endforeach
                </select>
                <p class="text-xs text-slate-400 mt-1">Pilih kamar tertentu jika ingin mengabsen per kamar saja. Kosongkan untuk mengabsen satu asrama sekaligus.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Date -->
                <div>
                    <label for="session_date" class="block text-sm font-semibold text-slate-700 dark:text-slate-350 mb-1">Tanggal Absensi <span class="text-rose-500">*</span></label>
                    <input type="date" name="session_date" id="session_date" value="{{ old('session_date', date('Y-m-d')) }}" required class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-800 dark:bg-slate-950 dark:text-white">
                </div>

                <!-- Session Type -->
                <div>
                    <label for="session_type" class="block text-sm font-semibold text-slate-700 dark:text-slate-350 mb-1">Waktu Sesi Absen <span class="text-rose-500">*</span></label>
                    <select name="session_type" id="session_type" required class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-800 dark:bg-slate-950 dark:text-white">
                        <option value="night" {{ old('session_type', 'night') === 'night' ? 'selected' : '' }}>Malam Pengecekan Keberadaan</option>
                        <option value="morning" {{ old('session_type') === 'morning' ? 'selected' : '' }}>Pagi</option>
                        <option value="afternoon" {{ old('session_type') === 'afternoon' ? 'selected' : '' }}>Sore</option>
                        <option value="custom" {{ old('session_type') === 'custom' ? 'selected' : '' }}>Kustom / Sesi Khusus</option>
                    </select>
                </div>
            </div>

            <div class="border-t border-slate-100 pt-4 dark:border-slate-800 flex justify-end gap-3">
                <a href="{{ route('boarding.roll-calls.index') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-350 dark:hover:bg-slate-950">Batal</a>
                <button type="submit" class="rounded-xl bg-gradient-to-r from-emerald-500 to-teal-600 px-4 py-2 text-sm font-semibold text-white shadow-md hover:opacity-90 transition">Buat Sesi & Ambil Santri</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dormSelect = document.getElementById('boarding_dormitory_id');
        const roomSelect = document.getElementById('boarding_room_id');
        const originalOptions = Array.from(roomSelect.options);

        dormSelect.addEventListener('change', function() {
            const selectedDormId = this.value;
            roomSelect.innerHTML = '';
            
            originalOptions.forEach(option => {
                if (!option.value || option.dataset.dormitory === selectedDormId) {
                    roomSelect.appendChild(option.cloneNode(true));
                }
            });
        });
    });
</script>
@endsection
