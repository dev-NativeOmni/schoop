@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div>
        <a href="{{ route('ai-learning.learning-profiles.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 transition">
            &larr; Kembali ke Daftar Profil
        </a>
        <h1 class="text-3xl font-black tracking-tight text-slate-900 dark:text-slate-100 mt-2">Detail Profil Belajar AI</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Laporan komprehensif perkembangan Qur’an siswa.</p>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-450 border border-emerald-100 dark:border-emerald-950 text-sm font-semibold">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Summary card -->
            <div class="rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-850 pb-4">
                    <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100">Ringkasan Analisis</h2>
                    <span class="inline-flex items-center rounded-lg px-2.5 py-0.5 text-xs font-bold 
                        @if($profile->profile_status === 'published') bg-emerald-50 dark:bg-emerald-950/20 text-emerald-600
                        @elseif($profile->profile_status === 'teacher_reviewed') bg-indigo-50 dark:bg-indigo-950/20 text-indigo-600
                        @else bg-slate-100 dark:bg-slate-850 text-slate-500
                        @endif">
                        {{ strtoupper($profile->profile_status) }}
                    </span>
                </div>

                <p class="text-sm text-slate-700 dark:text-slate-350 leading-relaxed font-medium">
                    {{ $profile->summary }}
                </p>

                <!-- Disclaimer -->
                <div class="p-3 bg-slate-50 dark:bg-slate-950 rounded-2xl border border-slate-100 dark:border-slate-850">
                    <p class="text-[10px] text-slate-450 italic leading-normal">
                        Rekomendasi ini dibuat oleh sistem untuk membantu proses belajar dan telah/harus direview oleh guru. Keputusan pembelajaran tetap mengikuti arahan guru.
                    </p>
                </div>
            </div>

            <!-- Strengths and focus areas -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Strengths -->
                <div class="rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm space-y-3">
                    <h3 class="font-bold text-slate-900 dark:text-slate-100 flex items-center gap-2">
                        <span class="text-emerald-500">&check;</span> Kekuatan / Prestasi
                    </h3>
                    <ul class="space-y-2 text-xs text-slate-600 dark:text-slate-400 list-disc pl-4">
                        @foreach($profile->strengths ?? [] as $st)
                            <li>{{ $st }}</li>
                        @endforeach
                    </ul>
                </div>

                <!-- Focus areas -->
                <div class="rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm space-y-3">
                    <h3 class="font-bold text-slate-900 dark:text-slate-100 flex items-center gap-2">
                        <span class="text-amber-500">&excl;</span> Fokus Pembinaan
                    </h3>
                    <ul class="space-y-2 text-xs text-slate-600 dark:text-slate-400 list-disc pl-4">
                        @foreach($profile->focus_areas ?? [] as $fa)
                            <li>{{ $fa }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Detail signals list -->
            <div class="rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm space-y-4">
                <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100">Sinyal Belajar Terdeteksi</h2>
                <div class="space-y-3">
                    @forelse($profile->signals as $sig)
                        <div class="p-4 rounded-2xl border border-slate-100 dark:border-slate-850 bg-slate-50/20 dark:bg-slate-950/10 flex items-start justify-between gap-4">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center rounded px-2 py-0.5 text-[9px] font-black uppercase bg-slate-150 dark:bg-slate-800 text-slate-500">
                                        {{ $sig->source_module }}
                                    </span>
                                    <span class="text-xs font-bold text-slate-800 dark:text-slate-200">{{ $sig->description }}</span>
                                </div>
                                <div class="text-[10px] text-slate-450 mt-1 font-medium">Tanggal Sinyal: {{ $sig->signal_date?->toDateString() }}</div>
                            </div>
                            <span class="inline-flex items-center rounded-lg px-2 py-0.5 text-[10px] font-bold 
                                @if($sig->severity === 'urgent') bg-rose-50 text-rose-600
                                @elseif($sig->severity === 'attention') bg-amber-50 text-amber-600
                                @elseif($sig->severity === 'positive') bg-emerald-50 text-emerald-600
                                @else bg-slate-50 text-slate-500
                                @endif">
                                {{ strtoupper($sig->severity) }}
                            </span>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 py-4 text-center">Tidak ada sinyal belajar khusus terdeteksi.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar Actions -->
        <div class="space-y-6">
            <!-- Student identity card -->
            <div class="rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm space-y-4">
                <h3 class="font-bold text-slate-900 dark:text-slate-100 border-b border-slate-100 dark:border-slate-850 pb-3">Siswa</h3>
                <div>
                    <p class="text-sm font-bold text-slate-800 dark:text-slate-200">{{ $profile->student->user?->name }}</p>
                    <p class="text-xs text-slate-500">NIS: {{ $profile->student->nis ?? '-' }}</p>
                </div>
                <div class="pt-2">
                    <p class="text-xs text-slate-450 font-bold uppercase">Keandalan Data</p>
                    <p class="text-2xl font-black text-indigo-600 mt-1">{{ $profile->confidence_score }}%</p>
                    <p class="text-[10px] text-slate-400 leading-normal mt-0.5">Skor keandalan mencerminkan kelengkapan data input belajar yang digunakan sistem.</p>
                </div>
            </div>

            <!-- Publish controls -->
            @if($profile->profile_status === 'draft')
                <div class="rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm space-y-3">
                    <h3 class="font-bold text-slate-900 dark:text-slate-100 pb-2 border-b border-slate-100 dark:border-slate-850">Aksi Guru</h3>
                    <form action="{{ route('ai-learning.learning-profiles.review', $profile) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full inline-flex items-center justify-center rounded-2xl bg-indigo-600 px-4 py-2.5 font-bold text-white shadow hover:bg-indigo-700 transition text-xs">
                            Setujui Draf (Review)
                        </button>
                    </form>
                    <form action="{{ route('ai-learning.learning-profiles.publish', $profile) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full inline-flex items-center justify-center rounded-2xl bg-emerald-600 px-4 py-2.5 font-bold text-white shadow hover:bg-emerald-700 transition text-xs">
                            Setujui & Publikasikan
                        </button>
                    </form>
                </div>
            @elseif($profile->profile_status === 'teacher_reviewed')
                <div class="rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm space-y-3">
                    <h3 class="font-bold text-slate-900 dark:text-slate-100 pb-2 border-b border-slate-100 dark:border-slate-850">Aksi Guru</h3>
                    <form action="{{ route('ai-learning.learning-profiles.publish', $profile) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full inline-flex items-center justify-center rounded-2xl bg-emerald-600 px-4 py-2.5 font-bold text-white shadow hover:bg-emerald-700 transition text-xs">
                            Publikasikan Laporan
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
