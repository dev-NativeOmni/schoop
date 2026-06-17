@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-slate-900 dark:text-slate-100">AI Learning Assistant</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Kelola asisten pembelajaran Qur'an, pantau tren belajar, dan publikasikan rekomendasi personal.</p>
        </div>
    </div>

    <!-- Warnings / Human-in-the-loop Disclaimer -->
    <div class="rounded-3xl border border-amber-200 dark:border-amber-950/60 bg-amber-50/50 dark:bg-amber-950/20 p-5 shadow-sm">
        <div class="flex items-start space-x-3">
            <div class="p-1 text-amber-600 dark:text-amber-400">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <div>
                <h3 class="font-bold text-amber-800 dark:text-amber-300">Prinsip Human-in-the-Loop</h3>
                <p class="text-xs text-amber-700 dark:text-amber-400/90 mt-1">
                    AI Assistant hanya membantu menyusun draf rekomendasi dan latihan berdasarkan data riil. Semua penilaian resmi, keputusan kenaikan level, atau cetak rapor mutlak di bawah kendali dan persetujuan guru. Draf AI tidak akan pernah dipublikasikan langsung ke wali murid/siswa sebelum disetujui guru.
                </p>
            </div>
        </div>
    </div>

    <!-- Metric Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <a href="{{ route('ai-learning.learning-profiles.index') }}" class="group block rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm hover:shadow-md transition">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase">Learning Profiles</p>
                    <p class="text-3xl font-black text-slate-800 dark:text-slate-100 mt-2">{{ $profileDraftsCount }}</p>
                    <p class="text-xs text-slate-500 mt-1">Draf profil siswa pekan ini</p>
                </div>
                <div class="p-3 bg-indigo-50 dark:bg-indigo-950/30 rounded-2xl text-indigo-600 dark:text-indigo-400">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
            </div>
        </a>

        <a href="{{ route('ai-learning.recommendations.index') }}" class="group block rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm hover:shadow-md transition">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase">Recommendations</p>
                    <p class="text-3xl font-black text-slate-800 dark:text-slate-100 mt-2">{{ $recsCount }}</p>
                    <p class="text-xs text-slate-500 mt-1">Rekomendasi draf baru</p>
                </div>
                <div class="p-3 bg-amber-50 dark:bg-amber-950/30 rounded-2xl text-amber-600 dark:text-amber-400">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 113.536 0z" />
                    </svg>
                </div>
            </div>
        </a>

        <a href="{{ route('ai-learning.review-queue.index') }}" class="group block rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm hover:shadow-md transition">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase">Verification Queue</p>
                    <p class="text-3xl font-black text-slate-800 dark:text-slate-100 mt-2">{{ $reviewQueueCount }}</p>
                    <p class="text-xs text-slate-500 mt-1">Menunggu persetujuan guru</p>
                </div>
                <div class="p-3 bg-emerald-50 dark:bg-emerald-950/30 rounded-2xl text-emerald-600 dark:text-emerald-400">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 00-2 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                </div>
            </div>
        </a>

        <a href="{{ route('ai-learning.safety-events.index') }}" class="group block rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm hover:shadow-md transition">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase">Safety Alerts</p>
                    <p class="text-3xl font-black text-rose-600 mt-2">{{ $safetyEventsCount }}</p>
                    <p class="text-xs text-slate-500 mt-1">Unresolved safety warnings</p>
                </div>
                <div class="p-3 bg-rose-50 dark:bg-rose-950/30 rounded-2xl text-rose-600 dark:text-rose-400">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
            </div>
        </a>
    </div>

    <!-- Features Flags & Management Column -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Actions -->
        <div class="lg:col-span-2 space-y-6">
            <div class="rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm">
                <h2 class="text-xl font-bold text-slate-900 dark:text-slate-100 mb-6">Menu Asisten Belajar</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <a href="{{ route('ai-learning.practice-plans.create') }}" class="group block rounded-2xl border border-slate-100 dark:border-slate-800/60 bg-slate-50/50 dark:bg-slate-950/30 p-5 transition hover:bg-slate-100 dark:hover:bg-slate-950">
                        <h3 class="font-bold text-slate-800 dark:text-slate-200 group-hover:text-indigo-600 transition">1. Rencana Latihan Mandiri</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Buat practice plan terstruktur 3-7 hari berdasarkan draf rekomendasi Qur'an siswa.</p>
                    </a>

                    <a href="{{ route('ai-learning.feedback-drafts.create') }}" class="group block rounded-2xl border border-slate-100 dark:border-slate-800/60 bg-slate-50/50 dark:bg-slate-950/30 p-5 transition hover:bg-slate-100 dark:hover:bg-slate-950">
                        <h3 class="font-bold text-slate-800 dark:text-slate-200 group-hover:text-indigo-600 transition">2. Draf Catatan Wali Murid</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Generasi draf catatan evaluasi untuk orang tua menggunakan template kalimat suportif.</p>
                    </a>

                    <a href="{{ route('ai-learning.review-queue.index') }}" class="group block rounded-2xl border border-slate-100 dark:border-slate-800/60 bg-slate-50/50 dark:bg-slate-950/30 p-5 transition hover:bg-slate-100 dark:hover:bg-slate-950">
                        <h3 class="font-bold text-slate-800 dark:text-slate-200 group-hover:text-indigo-600 transition">3. Verifikasi Output AI</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Setujui, sunting draf, atau batalkan output AI sebelum dikirimkan ke portal orang tua / siswa.</p>
                    </a>

                    <a href="{{ route('ai-learning.feature-flags.index') }}" class="group block rounded-2xl border border-slate-100 dark:border-slate-800/60 bg-slate-50/50 dark:bg-slate-950/30 p-5 transition hover:bg-slate-100 dark:hover:bg-slate-950">
                        <h3 class="font-bold text-slate-800 dark:text-slate-200 group-hover:text-indigo-600 transition">4. Konfigurasi Fitur AI</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Kelola ijin dan visibility dashboard asisten AI bagi orang tua dan siswa.</p>
                    </a>
                </div>
            </div>
        </div>

        <!-- Right Side Feature Flags Info -->
        <div class="space-y-6">
            <div class="rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm">
                <h2 class="text-xl font-bold text-slate-900 dark:text-slate-100 mb-4">Status Fitur AI</h2>
                <div class="space-y-3">
                    @foreach($featureFlags as $flag)
                        <div class="flex items-center justify-between p-3 rounded-2xl border border-slate-100 dark:border-slate-850">
                            <div>
                                <p class="text-xs font-bold text-slate-800 dark:text-slate-250">{{ $flag->label }}</p>
                                <p class="text-[10px] text-slate-400">Key: {{ $flag->feature_key }}</p>
                            </div>
                            <span class="inline-flex items-center rounded-lg px-2 py-0.5 text-[10px] font-bold {{ $flag->is_enabled ? 'bg-emerald-50 dark:bg-emerald-950/20 text-emerald-600' : 'bg-slate-100 dark:bg-slate-850 text-slate-550' }}">
                                {{ $flag->is_enabled ? 'Aktif' : 'Non-aktif' }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
