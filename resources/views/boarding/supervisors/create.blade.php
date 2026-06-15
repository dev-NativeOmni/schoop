@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between border-b border-slate-200 pb-4 dark:border-slate-800">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Daftarkan Pembina Asrama</h1>
            <p class="text-sm text-slate-500">Tunjuk user/guru sebagai pembina asrama dengan ruang lingkup tugasnya.</p>
        </div>
        <a href="{{ route('boarding.supervisors.index') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-350 dark:hover:bg-slate-950">
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
        <form action="{{ route('boarding.supervisors.store') }}" method="POST" class="space-y-4">
            @csrf

            <!-- User Selection -->
            <div>
                <label for="user_id" class="block text-sm font-semibold text-slate-700 dark:text-slate-350 mb-1">Pilih Pengguna / Guru <span class="text-rose-500">*</span></label>
                <select name="user_id" id="user_id" required class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-800 dark:bg-slate-950 dark:text-white">
                    <option value="" disabled selected>-- Pilih Pengguna --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} (Role: {{ $user->role->name }})
                        </option>
                    @endforeach
                </select>
                <p class="text-xs text-slate-400 mt-1">Hanya menampilkan pengguna ber-role administrator, guru, atau pembina asrama yang belum memiliki profil pembina.</p>
            </div>

            <!-- Dormitory Scope -->
            <div>
                <label for="boarding_dormitory_id" class="block text-sm font-semibold text-slate-700 dark:text-slate-350 mb-1">Cakupan Asrama (Opsional)</label>
                <select name="boarding_dormitory_id" id="boarding_dormitory_id" class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-800 dark:bg-slate-950 dark:text-white">
                    <option value="">-- Semua Asrama --</option>
                    @foreach($dormitories as $dormitory)
                        <option value="{{ $dormitory->id }}" {{ old('boarding_dormitory_id') == $dormitory->id ? 'selected' : '' }}>
                            {{ $dormitory->name }}
                        </option>
                    @endforeach
                </select>
                <p class="text-xs text-slate-400 mt-1">Kosongkan jika pembina ini bertugas memantau seluruh asrama di sekolah ini.</p>
            </div>

            <!-- Room Scope -->
            <div>
                <label for="boarding_room_id" class="block text-sm font-semibold text-slate-700 dark:text-slate-350 mb-1">Cakupan Kamar Spesifik (Opsional)</label>
                <select name="boarding_room_id" id="boarding_room_id" class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-800 dark:bg-slate-950 dark:text-white">
                    <option value="">-- Semua Kamar --</option>
                    @foreach($rooms as $room)
                        <option value="{{ $room->id }}" data-dormitory="{{ $room->boarding_dormitory_id }}" {{ old('boarding_room_id') == $room->id ? 'selected' : '' }}>
                            {{ $room->name }} (Asrama: {{ $room->dormitory->name }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Contact -->
            <div>
                <label for="phone" class="block text-sm font-semibold text-slate-700 dark:text-slate-350 mb-1">Nomor Telepon / WhatsApp</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone') }}" placeholder="Contoh: 08123456789" class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-800 dark:bg-slate-950 dark:text-white">
            </div>

            <!-- Status -->
            <div>
                <label for="status" class="block text-sm font-semibold text-slate-700 dark:text-slate-350 mb-1">Status Keaktifan <span class="text-rose-500">*</span></label>
                <select name="status" id="status" required class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-800 dark:bg-slate-950 dark:text-white">
                    <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>

            <!-- Notes -->
            <div>
                <label for="notes" class="block text-sm font-semibold text-slate-700 dark:text-slate-350 mb-1">Catatan</label>
                <textarea name="notes" id="notes" rows="3" placeholder="Masukkan keterangan tambahan jika diperlukan..." class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-800 dark:bg-slate-950 dark:text-white">{{ old('notes') }}</textarea>
            </div>

            <div class="border-t border-slate-100 pt-4 dark:border-slate-800 flex justify-end gap-3">
                <a href="{{ route('boarding.supervisors.index') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-350 dark:hover:bg-slate-950">Batal</a>
                <button type="submit" class="rounded-xl bg-gradient-to-r from-emerald-500 to-teal-600 px-4 py-2 text-sm font-semibold text-white shadow-md hover:opacity-90 transition">Simpan Pembina</button>
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
                if (!option.value || !selectedDormId || option.dataset.dormitory === selectedDormId) {
                    roomSelect.appendChild(option.cloneNode(true));
                }
            });
        });
    });
</script>
@endsection
