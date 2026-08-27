@extends('layouts.app')

@section('content')
@php
    $recordsData = $records->map(function ($rec, $idx) {
        return [
            'index' => $idx,
            'student_id' => $rec->student_id,
            'name' => $rec->student->full_name,
            'nis' => $rec->student->student_number ?? ($rec->student->nis ?? '-'),
            'class_name' => $rec->student->classRoom->name ?? 'Tanpa Kelas',
            'status' => old("records.{$idx}.status", $rec->status ?? 'present'),
            'note' => old("records.{$idx}.note", $rec->note ?? ''),
        ];
    })->values();

    $isClosed = $rollCall->status === 'closed' && !(auth()->user()->isSuperAdmin() || auth()->user()->isAdmin());
@endphp

<div x-data="rollCallEngine({
        initialRecords: {{ json_encode($recordsData) }},
        isClosed: {{ $isClosed ? 'true' : 'false' }}
    })" 
    class="space-y-6">

    {{-- Header Section --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-slate-200/80 pb-5 dark:border-slate-800">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('boarding.roll-calls.index') }}" class="text-xs font-semibold text-emerald-600 dark:text-emerald-400 hover:underline flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                    Daftar Sesi Roll Call
                </a>
                <span class="text-slate-300 dark:text-slate-700">/</span>
                <span class="text-xs text-slate-500 dark:text-slate-400">Absensi Kamar Malam</span>
            </div>
            <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white">
                Sesi Absensi Asrama: {{ \Carbon\Carbon::parse($rollCall->session_date)->translatedFormat('d F Y') }}
            </h1>
            <div class="flex flex-wrap items-center gap-2 mt-1 text-xs text-slate-500 dark:text-slate-400">
                <span class="font-medium text-slate-700 dark:text-slate-300">Asrama: <b>{{ $rollCall->dormitory->name ?? 'Semua Asrama' }}</b></span>
                @if($rollCall->room)
                    <span>&bull;</span>
                    <span class="font-medium text-slate-700 dark:text-slate-300">Kamar: <b>{{ $rollCall->room->name }}</b></span>
                @endif
                <span>&bull;</span>
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-bold uppercase
                    @if($rollCall->session_type === 'night') bg-indigo-50 text-indigo-700 dark:bg-indigo-950/40 dark:text-indigo-300 border border-indigo-200/60 dark:border-indigo-800/40
                    @elseif($rollCall->session_type === 'morning') bg-amber-50 text-amber-700 dark:bg-amber-950/40 dark:text-amber-300 border border-amber-200/60 dark:border-amber-800/40
                    @else bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/40 @endif">
                    @if($rollCall->session_type === 'morning') 🌅 Sesi Pagi
                    @elseif($rollCall->session_type === 'afternoon') ☀️ Sesi Sore
                    @elseif($rollCall->session_type === 'night') 🌙 Sesi Malam (Pengecekan Kamar)
                    @else ⏱️ Sesi Khusus
                    @endif
                </span>
            </div>
        </div>

        <div class="flex items-center gap-2.5">
            <a href="{{ route('boarding.roll-calls.index') }}" class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-bold text-slate-600 hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-slate-800 transition shadow-2xs">
                &larr; Kembali
            </a>
        </div>
    </div>

    {{-- Alerts --}}
    @if($errors->any())
        <div class="rounded-xl bg-rose-50 p-4 text-xs text-rose-800 border border-rose-200 dark:bg-rose-950/30 dark:text-rose-300 dark:border-rose-900/40">
            <ul class="list-disc pl-5 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if(session('success'))
        <div class="rounded-xl bg-emerald-50 p-4 text-xs font-bold text-emerald-800 border border-emerald-200 dark:bg-emerald-950/30 dark:text-emerald-300 dark:border-emerald-900/40 flex items-center gap-2">
            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- Interactive Live Summary Banner --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
        <div class="card-natural p-3.5 flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-black text-sm flex items-center justify-center">
                <span x-text="records.length"></span>
            </div>
            <div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Santri</span>
                <div class="text-sm font-extrabold text-slate-900 dark:text-white" x-text="`${records.length} Santri`"></div>
            </div>
        </div>

        <div class="card-natural p-3.5 flex items-center gap-3 cursor-pointer hover:border-emerald-500 transition" @click="filterStatus = (filterStatus === 'present' ? 'all' : 'present')">
            <div class="w-9 h-9 rounded-xl bg-emerald-100 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300 font-black text-sm flex items-center justify-center">
                <span x-text="counts.present"></span>
            </div>
            <div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">Hadir</span>
                <div class="text-sm font-extrabold text-slate-900 dark:text-white" x-text="`${counts.present} Orang`"></div>
            </div>
        </div>

        <div class="card-natural p-3.5 flex items-center gap-3 cursor-pointer hover:border-amber-500 transition" @click="filterStatus = (filterStatus === 'late' ? 'all' : 'late')">
            <div class="w-9 h-9 rounded-xl bg-amber-100 dark:bg-amber-950/50 text-amber-700 dark:text-amber-300 font-black text-sm flex items-center justify-center">
                <span x-text="counts.late"></span>
            </div>
            <div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-amber-600 dark:text-amber-400">Terlambat</span>
                <div class="text-sm font-extrabold text-slate-900 dark:text-white" x-text="`${counts.late} Orang`"></div>
            </div>
        </div>

        <div class="card-natural p-3.5 flex items-center gap-3 cursor-pointer hover:border-blue-500 transition" @click="filterStatus = (filterStatus === 'permission' ? 'all' : 'permission')">
            <div class="w-9 h-9 rounded-xl bg-blue-100 dark:bg-blue-950/50 text-blue-700 dark:text-blue-300 font-black text-sm flex items-center justify-center">
                <span x-text="counts.permission"></span>
            </div>
            <div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400">Izin</span>
                <div class="text-sm font-extrabold text-slate-900 dark:text-white" x-text="`${counts.permission} Orang`"></div>
            </div>
        </div>

        <div class="card-natural p-3.5 flex items-center gap-3 cursor-pointer hover:border-purple-500 transition" @click="filterStatus = (filterStatus === 'sick' ? 'all' : 'sick')">
            <div class="w-9 h-9 rounded-xl bg-purple-100 dark:bg-purple-950/50 text-purple-700 dark:text-purple-300 font-black text-sm flex items-center justify-center">
                <span x-text="counts.sick"></span>
            </div>
            <div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-purple-600 dark:text-purple-400">Sakit (UKS)</span>
                <div class="text-sm font-extrabold text-slate-900 dark:text-white" x-text="`${counts.sick} Orang`"></div>
            </div>
        </div>

        <div class="card-natural p-3.5 flex items-center gap-3 cursor-pointer hover:border-rose-500 transition" @click="filterStatus = (filterStatus === 'absent' ? 'all' : 'absent')">
            <div class="w-9 h-9 rounded-xl bg-rose-100 dark:bg-rose-950/50 text-rose-700 dark:text-rose-300 font-black text-sm flex items-center justify-center">
                <span x-text="counts.absent"></span>
            </div>
            <div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-rose-600 dark:text-rose-450">Alpha</span>
                <div class="text-sm font-extrabold text-slate-900 dark:text-white" x-text="`${counts.absent} Orang`"></div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        {{-- Main Roll Call List --}}
        <div class="lg:col-span-3 space-y-4">
            <form action="{{ route('boarding.roll-calls.records.store', $rollCall->id) }}" method="POST" class="space-y-4">
                @csrf
                
                <div class="card-natural p-6">
                    {{-- Action & Fast Controls Bar --}}
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-5 border-b border-slate-100 dark:border-slate-800">
                        <div>
                            <h2 class="text-base font-extrabold text-slate-900 dark:text-white">Checklist Kehadiran Santri Asrama</h2>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Pilih status keberadaan masing-masing santri saat pengecekan.</p>
                        </div>

                        {{-- 1-Click Fast Actions --}}
                        @if(!$isClosed)
                            <div class="flex items-center gap-2">
                                <button type="button" 
                                        @click="markAll('present')"
                                        class="px-3.5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-sm shadow-emerald-600/20 active:scale-95 transition-all flex items-center gap-1.5">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                    <span>Tandai Semua Hadir</span>
                                </button>
                            </div>
                        @endif
                    </div>

                    {{-- Fast Filter & Search Bar --}}
                    <div class="mt-4 mb-5 flex flex-col sm:flex-row items-center justify-between gap-3">
                        <div class="relative w-full sm:w-72">
                            <input type="text" 
                                   x-model="searchQuery"
                                   placeholder="Cari nama santri / NIS..."
                                   class="w-full pl-9 pr-3.5 py-2 text-xs rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-800 dark:text-slate-200 focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 focus:outline-none">
                            <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        </div>

                        {{-- Filter Pill Tabs --}}
                        <div class="flex flex-wrap items-center gap-1.5 w-full sm:w-auto">
                            <button type="button" @click="filterStatus = 'all'" :class="filterStatus === 'all' ? 'bg-slate-900 text-white dark:bg-white dark:text-slate-900' : 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400 hover:bg-slate-200'" class="px-2.5 py-1 rounded-lg text-xs font-bold transition">Semua</button>
                            <button type="button" @click="filterStatus = 'present'" :class="filterStatus === 'present' ? 'bg-emerald-600 text-white' : 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300 hover:bg-emerald-100'" class="px-2.5 py-1 rounded-lg text-xs font-bold transition">Hadir</button>
                            <button type="button" @click="filterStatus = 'late'" :class="filterStatus === 'late' ? 'bg-amber-600 text-white' : 'bg-amber-50 text-amber-700 dark:bg-amber-950/40 dark:text-amber-300 hover:bg-amber-100'" class="px-2.5 py-1 rounded-lg text-xs font-bold transition">Terlambat</button>
                            <button type="button" @click="filterStatus = 'permission'" :class="filterStatus === 'permission' ? 'bg-blue-600 text-white' : 'bg-blue-50 text-blue-700 dark:bg-blue-950/40 dark:text-blue-300 hover:bg-blue-100'" class="px-2.5 py-1 rounded-lg text-xs font-bold transition">Izin</button>
                            <button type="button" @click="filterStatus = 'sick'" :class="filterStatus === 'sick' ? 'bg-purple-600 text-white' : 'bg-purple-50 text-purple-700 dark:bg-purple-950/40 dark:text-purple-300 hover:bg-purple-100'" class="px-2.5 py-1 rounded-lg text-xs font-bold transition">Sakit</button>
                            <button type="button" @click="filterStatus = 'absent'" :class="filterStatus === 'absent' ? 'bg-rose-600 text-white' : 'bg-rose-50 text-rose-700 dark:bg-rose-950/40 dark:text-rose-300 hover:bg-rose-100'" class="px-2.5 py-1 rounded-lg text-xs font-bold transition">Alpha</button>
                        </div>
                    </div>

                    @if($records->isEmpty())
                        <div class="text-center py-16 text-slate-400">
                            <svg class="w-12 h-12 mx-auto text-slate-300 dark:text-slate-700 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                            <p class="font-medium text-sm">Tidak ada santri yang terdaftar dengan penempatan aktif di asrama/kamar ini.</p>
                        </div>
                    @else
                        <div class="space-y-3">
                            <template x-for="(rec, idx) in filteredRecords" :key="rec.student_id">
                                <div class="p-4 rounded-2xl border transition-all duration-200 flex flex-col md:flex-row md:items-center justify-between gap-4"
                                     :class="{
                                         'border-emerald-200/80 bg-emerald-50/30 dark:border-emerald-900/40 dark:bg-emerald-950/20': rec.status === 'present',
                                         'border-amber-200/80 bg-amber-50/30 dark:border-amber-900/40 dark:bg-amber-950/20': rec.status === 'late',
                                         'border-blue-200/80 bg-blue-50/30 dark:border-blue-900/40 dark:bg-blue-950/20': rec.status === 'permission',
                                         'border-purple-200/80 bg-purple-50/30 dark:border-purple-900/40 dark:bg-purple-950/20': rec.status === 'sick',
                                         'border-rose-200/80 bg-rose-50/30 dark:border-rose-900/40 dark:bg-rose-950/20': rec.status === 'absent',
                                         'border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-900': !rec.status
                                     }">
                                    
                                    {{-- Student Info --}}
                                    <div class="flex items-center space-x-3.5 flex-1 min-w-[220px]">
                                        <div class="w-10 h-10 rounded-xl font-black text-xs flex items-center justify-center shadow-2xs shrink-0"
                                             :class="{
                                                 'bg-emerald-600 text-white': rec.status === 'present',
                                                 'bg-amber-500 text-white': rec.status === 'late',
                                                 'bg-blue-600 text-white': rec.status === 'permission',
                                                 'bg-purple-600 text-white': rec.status === 'sick',
                                                 'bg-rose-600 text-white': rec.status === 'absent',
                                                 'bg-slate-200 text-slate-700': !rec.status
                                             }">
                                            <span x-text="rec.name.substring(0,2).toUpperCase()"></span>
                                        </div>
                                        <div>
                                            <input type="hidden" :name="`records[${rec.index}][student_id]`" :value="rec.student_id">
                                            <h4 class="text-sm font-bold text-slate-900 dark:text-white" x-text="rec.name"></h4>
                                            <p class="text-xs text-slate-500 dark:text-slate-400">
                                                NIS: <span x-text="rec.nis"></span> &bull; <span x-text="rec.class_name"></span>
                                            </p>
                                        </div>
                                    </div>
                                    
                                    {{-- Status Selection Buttons (Custom Segmented Radios) --}}
                                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3">
                                        <div class="grid grid-cols-5 gap-1.5 p-1 rounded-xl bg-slate-100 dark:bg-slate-950/60 border border-slate-200/70 dark:border-slate-800">
                                            {{-- Hadir --}}
                                            <button type="button" 
                                                    @click="setStatus(rec.index, 'present')"
                                                    :disabled="isClosed"
                                                    :class="rec.status === 'present' ? 'bg-emerald-600 text-white shadow-xs font-bold' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900'"
                                                    class="px-2.5 py-1 rounded-lg text-[11px] font-semibold transition active:scale-95 text-center">
                                                Hadir
                                            </button>

                                            {{-- Terlambat --}}
                                            <button type="button" 
                                                    @click="setStatus(rec.index, 'late')"
                                                    :disabled="isClosed"
                                                    :class="rec.status === 'late' ? 'bg-amber-500 text-white shadow-xs font-bold' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900'"
                                                    class="px-2.5 py-1 rounded-lg text-[11px] font-semibold transition active:scale-95 text-center">
                                                Telat
                                            </button>

                                            {{-- Izin --}}
                                            <button type="button" 
                                                    @click="setStatus(rec.index, 'permission')"
                                                    :disabled="isClosed"
                                                    :class="rec.status === 'permission' ? 'bg-blue-600 text-white shadow-xs font-bold' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900'"
                                                    class="px-2.5 py-1 rounded-lg text-[11px] font-semibold transition active:scale-95 text-center">
                                                Izin
                                            </button>

                                            {{-- Sakit --}}
                                            <button type="button" 
                                                    @click="setStatus(rec.index, 'sick')"
                                                    :disabled="isClosed"
                                                    :class="rec.status === 'sick' ? 'bg-purple-600 text-white shadow-xs font-bold' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900'"
                                                    class="px-2.5 py-1 rounded-lg text-[11px] font-semibold transition active:scale-95 text-center">
                                                Sakit
                                            </button>

                                            {{-- Alpha --}}
                                            <button type="button" 
                                                    @click="setStatus(rec.index, 'absent')"
                                                    :disabled="isClosed"
                                                    :class="rec.status === 'absent' ? 'bg-rose-600 text-white shadow-xs font-bold' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900'"
                                                    class="px-2.5 py-1 rounded-lg text-[11px] font-semibold transition active:scale-95 text-center">
                                                Alpha
                                            </button>
                                        </div>

                                        {{-- Hidden input sync for form submission --}}
                                        <input type="hidden" :name="`records[${rec.index}][status]`" :value="rec.status">

                                        {{-- Notes Input --}}
                                        <div class="w-full sm:w-48">
                                            <input type="text" 
                                                   :name="`records[${rec.index}][note]`" 
                                                   x-model="rec.note"
                                                   :disabled="isClosed"
                                                   placeholder="Catatan / keterangan..." 
                                                   class="w-full rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-xs px-3 py-1.5 text-slate-800 dark:text-slate-200 focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 focus:outline-none">
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>

                        {{-- Submit Button Footer --}}
                        @if(!$isClosed)
                            <div class="border-t border-slate-100 dark:border-slate-800/80 pt-6 mt-6 flex items-center justify-between">
                                <span class="text-xs text-slate-400">Pastikan seluruh data terisi sebelum menyimpan.</span>
                                <button type="submit" 
                                        class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 px-6 py-2.5 text-sm font-bold text-white shadow-md shadow-emerald-600/20 active:scale-95 transition-all">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                    <span>Simpan Rekap Kehadiran</span>
                                </button>
                            </div>
                        @endif
                    @endif
                </div>
            </form>
        </div>

        {{-- Sidebar Section --}}
        <div class="space-y-6">
            {{-- Session Status Card --}}
            <div class="card-natural p-6 space-y-4">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Status Sesi Absensi</h3>
                
                <div class="flex items-center gap-2.5">
                    <span class="w-3 h-3 rounded-full @if($rollCall->status === 'open') bg-emerald-500 animate-pulse @else bg-slate-400 @endif"></span>
                    <span class="font-extrabold text-sm text-slate-900 dark:text-white uppercase">
                        {{ $rollCall->status === 'open' ? 'Sesi Terbuka (Aktif)' : 'Sesi Terkunci / Selesai' }}
                    </span>
                </div>

                <div class="text-xs text-slate-500 dark:text-slate-400 space-y-1.5 pt-2 border-t border-slate-100 dark:border-slate-800">
                    <div>Pencatat: <b>{{ $rollCall->creator->name ?? 'Sistem' }}</b></div>
                    <div>Waktu Dibuat: <b>{{ $rollCall->created_at->format('d/m/Y H:i') }}</b></div>
                </div>
                
                @if($rollCall->status === 'open')
                    @if(auth()->user()->isSuperAdmin() || auth()->user()->isAdmin() || auth()->user()->isPrincipal() || auth()->user()->isBoardingSupervisor())
                        <div class="pt-3 border-t border-slate-100 dark:border-slate-800">
                            <p class="text-2xs text-slate-400 mb-3">Kunci sesi ini jika pengecekan asrama malam telah selesai untuk memfinalkan data.</p>
                            
                            <form action="{{ route('boarding.roll-calls.close', $rollCall->id) }}" method="POST" onsubmit="return confirm('Kunci sesi absensi ini? Data yang sudah terkunci tidak dapat diubah kembali.');">
                                @csrf
                                <button type="submit" class="w-full rounded-xl bg-slate-800 hover:bg-slate-900 dark:bg-slate-700 dark:hover:bg-slate-600 py-2.5 text-xs font-bold text-white transition shadow-sm active:scale-95">
                                    🔒 Kunci & Tutup Sesi
                                </button>
                            </form>
                        </div>
                    @endif
                @else
                    <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-950/40 text-2xs text-slate-500 border border-slate-100 dark:border-slate-800">
                        Sesi absensi ini sudah dikunci pada {{ $rollCall->updated_at->format('d M Y H:i') }}.
                    </div>
                @endif
            </div>

            {{-- Quick Guide Box --}}
            <div class="card-natural p-5 space-y-3">
                <h4 class="text-xs font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400 flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    Panduan Absensi Kamar
                </h4>
                <ul class="text-xs text-slate-500 dark:text-slate-400 space-y-2">
                    <li class="flex items-start gap-1.5">
                        <span class="text-emerald-500 font-bold">&bull;</span>
                        <span>Klik <b>Tandai Semua Hadir</b> untuk menghemat waktu saat seluruh santri hadir.</span>
                    </li>
                    <li class="flex items-start gap-1.5">
                        <span class="text-amber-500 font-bold">&bull;</span>
                        <span>Ubah status ke <b>Sakit</b> jika santri sedang dirawat di UKS/Klinik.</span>
                    </li>
                    <li class="flex items-start gap-1.5">
                        <span class="text-blue-500 font-bold">&bull;</span>
                        <span>Status <b>Izin</b> otomatis tersinkron bila santri memiliki surat izin keluar aktif.</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
function rollCallEngine(config) {
    return {
        records: config.initialRecords || [],
        isClosed: config.isClosed || false,
        searchQuery: '',
        filterStatus: 'all',

        get filteredRecords() {
            return this.records.filter(r => {
                const matchesSearch = !this.searchQuery || 
                    r.name.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                    r.nis.toLowerCase().includes(this.searchQuery.toLowerCase());
                const matchesStatus = this.filterStatus === 'all' || r.status === this.filterStatus;
                return matchesSearch && matchesStatus;
            });
        },

        get counts() {
            const res = { present: 0, late: 0, permission: 0, sick: 0, absent: 0 };
            this.records.forEach(r => {
                if (res[r.status] !== undefined) {
                    res[r.status]++;
                }
            });
            return res;
        },

        setStatus(idx, status) {
            if (this.isClosed) return;
            const target = this.records.find(r => r.index === idx);
            if (target) {
                target.status = status;
            }
        },

        markAll(status) {
            if (this.isClosed) return;
            this.records.forEach(r => {
                r.status = status;
            });
        }
    };
}
</script>
@endsection
