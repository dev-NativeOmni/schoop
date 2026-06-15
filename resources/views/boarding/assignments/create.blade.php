@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between border-b border-slate-200 pb-4 dark:border-slate-800">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Tambah Penempatan Kamar Santri</h1>
            <p class="text-sm text-slate-500">Assign/tempatkan santri yang belum mempunyai kamar asrama aktif.</p>
        </div>
        <a href="{{ route('boarding.assignments.index') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-350 dark:hover:bg-slate-950">
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
        <form action="{{ route('boarding.assignments.store') }}" method="POST" class="space-y-4">
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
                <p class="text-xs text-slate-400 mt-1">Hanya menampilkan santri yang belum memiliki penempatan asrama berstatus aktif.</p>
            </div>

            <!-- Dormitory Selection -->
            <div>
                <label for="boarding_dormitory_id" class="block text-sm font-semibold text-slate-700 dark:text-slate-350 mb-1">Pilih Asrama <span class="text-rose-500">*</span></label>
                <select name="boarding_dormitory_id" id="boarding_dormitory_id" required class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-800 dark:bg-slate-950 dark:text-white">
                    <option value="" disabled selected>-- Pilih Asrama --</option>
                    @foreach($dormitories as $dorm)
                        <option value="{{ $dorm->id }}" {{ old('boarding_dormitory_id') == $dorm->id ? 'selected' : '' }}>
                            {{ $dorm->name }} (Gender: {{ $dorm->gender === 'male' ? 'Putra' : ($dorm->gender === 'female' ? 'Putri' : 'Campuran') }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Room Selection -->
            <div>
                <label for="boarding_room_id" class="block text-sm font-semibold text-slate-700 dark:text-slate-350 mb-1">Pilih Kamar <span class="text-rose-500">*</span></label>
                <select name="boarding_room_id" id="boarding_room_id" required class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-800 dark:bg-slate-950 dark:text-white">
                    <option value="" disabled selected>-- Pilih Kamar (Pilih Asrama Terlebih Dahulu) --</option>
                    @foreach($rooms as $room)
                        <option value="{{ $room->id }}" data-dormitory="{{ $room->boarding_dormitory_id }}" {{ old('boarding_room_id') == $room->id ? 'selected' : '' }}>
                            {{ $room->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Bed Selection -->
            <div>
                <label for="boarding_bed_id" class="block text-sm font-semibold text-slate-700 dark:text-slate-350 mb-1">Pilih Ranjang / Tempat Tidur</label>
                <select name="boarding_bed_id" id="boarding_bed_id" class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-800 dark:bg-slate-950 dark:text-white">
                    <option value="">-- Pilih Ranjang (Pilih Kamar Terlebih Dahulu - Opsional) --</option>
                    @foreach($beds as $bed)
                        <option value="{{ $bed->id }}" data-room="{{ $bed->boarding_room_id }}" {{ old('boarding_bed_id') == $bed->id ? 'selected' : '' }}>
                            {{ $bed->code }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Start Date -->
            <div>
                <label for="start_date" class="block text-sm font-semibold text-slate-700 dark:text-slate-350 mb-1">Tanggal Mulai Penempatan <span class="text-rose-500">*</span></label>
                <input type="date" name="start_date" id="start_date" value="{{ old('start_date', date('Y-m-d')) }}" required class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-800 dark:bg-slate-950 dark:text-white">
            </div>

            <!-- Notes -->
            <div>
                <label for="notes" class="block text-sm font-semibold text-slate-700 dark:text-slate-350 mb-1">Catatan Tambahan</label>
                <textarea name="notes" id="notes" rows="3" placeholder="Masukkan keterangan tambahan jika ada..." class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-800 dark:bg-slate-950 dark:text-white">{{ old('notes') }}</textarea>
            </div>

            <div class="border-t border-slate-100 pt-4 dark:border-slate-800 flex justify-end gap-3">
                <a href="{{ route('boarding.assignments.index') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-350 dark:hover:bg-slate-950">Batal</a>
                <button type="submit" class="rounded-xl bg-gradient-to-r from-emerald-500 to-teal-600 px-4 py-2 text-sm font-semibold text-white shadow-md hover:opacity-90 transition">Simpan Penempatan</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dormSelect = document.getElementById('boarding_dormitory_id');
        const roomSelect = document.getElementById('boarding_room_id');
        const bedSelect = document.getElementById('boarding_bed_id');

        const originalRooms = Array.from(roomSelect.options);
        const originalBeds = Array.from(bedSelect.options);

        // Filter Rooms based on selected Dormitory
        dormSelect.addEventListener('change', function() {
            const selectedDormId = this.value;
            
            // Clear selections
            roomSelect.innerHTML = '';
            bedSelect.innerHTML = '';
            
            // Re-add placeholder/empty options
            originalRooms.forEach(option => {
                if (!option.value || option.dataset.dormitory === selectedDormId) {
                    roomSelect.appendChild(option.cloneNode(true));
                }
            });
            
            // Add bed placeholder
            bedSelect.appendChild(originalBeds[0].cloneNode(true));
        });

        // Filter Beds based on selected Room
        roomSelect.addEventListener('change', function() {
            const selectedRoomId = this.value;
            
            // Clear selection
            bedSelect.innerHTML = '';
            
            originalBeds.forEach(option => {
                if (!option.value || option.dataset.room === selectedRoomId) {
                    bedSelect.appendChild(option.cloneNode(true));
                }
            });
        });
    });
</script>
@endsection
