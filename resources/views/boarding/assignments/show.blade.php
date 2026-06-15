@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between border-b border-slate-200 pb-4 dark:border-slate-800">
        <div>
            <div class="flex items-center gap-2">
                <a href="{{ route('boarding.assignments.index') }}" class="text-sm font-semibold text-emerald-500 hover:underline">Daftar Penempatan</a>
                <span class="text-slate-400">/</span>
                <span class="text-sm text-slate-500 dark:text-slate-400">Detail Penempatan</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white mt-1">Penempatan: {{ $assignment->student->full_name }}</h1>
            <p class="text-sm text-slate-500">NIS: {{ $assignment->student->student_number ?? '-' }} | Kelas: {{ $assignment->student->classRoom->name ?? '-' }}</p>
        </div>
        <a href="{{ route('boarding.assignments.index') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-350 dark:hover:bg-slate-950">
            Kembali
        </a>
    </div>

    <!-- Error/Success Alert -->
    @if($errors->any())
        <div class="rounded-xl bg-rose-50 p-4 text-sm text-rose-850 dark:bg-rose-950/30 dark:text-rose-400">
            <ul class="list-disc pl-5 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if(session('success'))
        <div class="rounded-xl bg-emerald-50 p-4 text-sm text-emerald-800 dark:bg-emerald-950/30 dark:text-emerald-400">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Details Column -->
        <div class="md:col-span-2 space-y-6">
            <!-- Info Card -->
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 space-y-4">
                <h2 class="text-lg font-bold text-slate-800 dark:text-white border-b border-slate-100 pb-2 dark:border-slate-800">Detail Informasi Penempatan</h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Asrama</span>
                        <span class="text-sm font-semibold text-slate-800 dark:text-white block mt-1">{{ $assignment->dormitory->name }}</span>
                    </div>
                    <div>
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Kamar & Ranjang</span>
                        <span class="text-sm font-semibold text-slate-800 dark:text-white block mt-1">
                            Kamar: {{ $assignment->room->name }} 
                            @if($assignment->bed)
                                | Ranjang: {{ $assignment->bed->code }}
                            @endif
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Tanggal Mulai</span>
                        <span class="text-sm font-semibold text-slate-800 dark:text-white block mt-1">{{ \Carbon\Carbon::parse($assignment->start_date)->format('d M Y') }}</span>
                    </div>
                    <div>
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Tanggal Selesai</span>
                        <span class="text-sm font-semibold text-slate-800 dark:text-white block mt-1">{{ $assignment->end_date ? \Carbon\Carbon::parse($assignment->end_date)->format('d M Y') : '-' }}</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Status Penempatan</span>
                        <div class="mt-1">
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold
                                @if($assignment->status === 'active') bg-green-50 text-green-700 dark:bg-green-950/30 dark:text-green-455
                                @elseif($assignment->status === 'moved') bg-blue-50 text-blue-700 dark:bg-blue-950/30 dark:text-blue-455
                                @elseif($assignment->status === 'ended') bg-slate-50 text-slate-700 dark:bg-slate-950/30 dark:text-slate-455
                                @else bg-rose-50 text-rose-700 dark:bg-rose-950/30 dark:text-rose-455 @endif">
                                @if($assignment->status === 'active') Aktif
                                @elseif($assignment->status === 'moved') Pindah Kamar
                                @elseif($assignment->status === 'ended') Sudah Selesai
                                @else Dibatalkan
                                @endif
                            </span>
                        </div>
                    </div>
                    <div>
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Dicatat Oleh</span>
                        <span class="text-sm font-semibold text-slate-800 dark:text-white block mt-1">{{ $assignment->creator->name ?? '-' }}</span>
                    </div>
                </div>

                @if($assignment->notes)
                    <div>
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Catatan Tambahan</span>
                        <p class="text-sm text-slate-650 dark:text-slate-350 mt-1 whitespace-pre-wrap">{{ $assignment->notes }}</p>
                    </div>
                @endif
            </div>

            <!-- Move Room Section (Only if status is active) -->
            @if($assignment->status === 'active')
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 space-y-4">
                    <h2 class="text-lg font-bold text-slate-800 dark:text-white border-b border-slate-100 pb-2 dark:border-slate-800">Pindahkan Santri ke Kamar Lain</h2>
                    
                    <form action="{{ route('boarding.assignments.move', $assignment->id) }}" method="POST" class="space-y-4">
                        @csrf
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Dormitory -->
                            <div>
                                <label for="boarding_dormitory_id" class="block text-sm font-semibold text-slate-700 dark:text-slate-350 mb-1">Pilih Asrama <span class="text-rose-500">*</span></label>
                                <select name="boarding_dormitory_id" id="boarding_dormitory_id" required class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-800 dark:bg-slate-950 dark:text-white">
                                    <option value="" disabled>-- Pilih Asrama --</option>
                                    @foreach($dormitories as $dorm)
                                        <option value="{{ $dorm->id }}" {{ $assignment->boarding_dormitory_id == $dorm->id ? 'selected' : '' }}>
                                            {{ $dorm->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Room -->
                            <div>
                                <label for="boarding_room_id" class="block text-sm font-semibold text-slate-700 dark:text-slate-350 mb-1">Pilih Kamar Baru <span class="text-rose-500">*</span></label>
                                <select name="boarding_room_id" id="boarding_room_id" required class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-800 dark:bg-slate-950 dark:text-white">
                                    @foreach($rooms as $room)
                                        <option value="{{ $room->id }}" data-dormitory="{{ $room->boarding_dormitory_id }}" {{ $assignment->boarding_room_id == $room->id ? 'selected' : '' }}>
                                            {{ $room->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Bed -->
                            <div>
                                <label for="boarding_bed_id" class="block text-sm font-semibold text-slate-700 dark:text-slate-350 mb-1">Pilih Ranjang Baru</label>
                                <select name="boarding_bed_id" id="boarding_bed_id" class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-800 dark:bg-slate-950 dark:text-white">
                                    <option value="">-- Pilih Ranjang (Opsional) --</option>
                                    @foreach($beds as $bed)
                                        <option value="{{ $bed->id }}" data-room="{{ $bed->boarding_room_id }}" {{ $assignment->boarding_bed_id == $bed->id ? 'selected' : '' }}>
                                            {{ $bed->code }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Move Date -->
                            <div>
                                <label for="move_date" class="block text-sm font-semibold text-slate-700 dark:text-slate-350 mb-1">Tanggal Kepindahan <span class="text-rose-500">*</span></label>
                                <input type="date" name="move_date" id="move_date" value="{{ date('Y-m-d') }}" required class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-800 dark:bg-slate-950 dark:text-white">
                            </div>
                        </div>

                        <div>
                            <label for="move_notes" class="block text-sm font-semibold text-slate-700 dark:text-slate-350 mb-1">Alasan Kepindahan / Catatan</label>
                            <textarea name="notes" id="move_notes" rows="2" placeholder="Sebutkan alasan pindah kamar..." class="w-full rounded-xl border-slate-200 text-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-800 dark:bg-slate-950 dark:text-white"></textarea>
                        </div>

                        <div class="flex justify-end pt-2">
                            <button type="submit" class="rounded-xl bg-emerald-500 px-4 py-2 text-sm font-semibold text-white shadow-md hover:bg-emerald-600 transition">Proses Pindah Kamar</button>
                        </div>
                    </form>
                </div>
            @endif
        </div>

        <!-- Sidebar / Actions Column -->
        <div class="space-y-6">
            @if($assignment->status === 'active')
                <!-- End Assignment Card -->
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 space-y-4">
                    <h3 class="text-md font-bold text-slate-800 dark:text-white">Akhiri Penempatan</h3>
                    <p class="text-xs text-slate-500">Gunakan fitur ini apabila santri sudah lulus, keluar dari pesantren, atau menyelesaikan masa mukim asramanya.</p>
                    
                    <form action="{{ route('boarding.assignments.end', $assignment->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menyelesaikan penempatan asrama santri ini? Status ranjang akan dikosongkan.');">
                        @csrf
                        @method('PUT')
                        
                        <div>
                            <label for="end_date" class="block text-xs font-semibold text-slate-750 dark:text-slate-400 mb-1">Tanggal Keluar</label>
                            <input type="date" name="end_date" id="end_date" value="{{ date('Y-m-d') }}" required class="w-full rounded-xl border-slate-200 text-xs focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-800 dark:bg-slate-950 dark:text-white">
                        </div>

                        <button type="submit" class="w-full mt-4 rounded-xl bg-rose-500 px-4 py-2 text-xs font-semibold text-white shadow-md hover:bg-rose-600 transition">Selesaikan Penempatan</button>
                    </form>
                </div>
            @endif

            <!-- Quick Student Info -->
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 space-y-3">
                <h3 class="text-md font-bold text-slate-800 dark:text-white">Santri 360</h3>
                <a href="{{ route('schoolos.students.show', $assignment->student_id) }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-500 hover:underline">
                    Buka Profil Lengkap Santri
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
</div>

@if($assignment->status === 'active')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dormSelect = document.getElementById('boarding_dormitory_id');
        const roomSelect = document.getElementById('boarding_room_id');
        const bedSelect = document.getElementById('boarding_bed_id');

        const originalRooms = Array.from(roomSelect.options);
        const originalBeds = Array.from(bedSelect.options);

        function filterRooms() {
            const selectedDormId = dormSelect.value;
            const currentSelectedRoom = roomSelect.value;
            
            roomSelect.innerHTML = '';
            originalRooms.forEach(option => {
                if (option.dataset.dormitory === selectedDormId) {
                    const cloned = option.cloneNode(true);
                    if (cloned.value === currentSelectedRoom) {
                        cloned.selected = true;
                    }
                    roomSelect.appendChild(cloned);
                }
            });
            filterBeds();
        }

        function filterBeds() {
            const selectedRoomId = roomSelect.value;
            const currentSelectedBed = bedSelect.value;
            
            bedSelect.innerHTML = '';
            
            // Add placeholder option
            bedSelect.appendChild(originalBeds[0].cloneNode(true));
            
            originalBeds.forEach(option => {
                if (option.value && option.dataset.room === selectedRoomId) {
                    const cloned = option.cloneNode(true);
                    if (cloned.value === currentSelectedBed) {
                        cloned.selected = true;
                    }
                    bedSelect.appendChild(cloned);
                }
            });
        }

        dormSelect.addEventListener('change', filterRooms);
        roomSelect.addEventListener('change', filterBeds);
        
        filterRooms(); // Run on init
    });
</script>
@endif
@endsection
