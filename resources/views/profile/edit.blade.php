@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="relative overflow-hidden bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border border-slate-200/50 dark:border-slate-800/50 rounded-3xl p-8 shadow-xl">
        <!-- Background Ambient Glow -->
        <div class="absolute -right-20 -top-20 h-40 w-40 rounded-full bg-indigo-500/10 dark:bg-indigo-500/5 blur-3xl"></div>
        <div class="absolute -left-20 -bottom-20 h-40 w-40 rounded-full bg-emerald-500/10 dark:bg-emerald-500/5 blur-3xl"></div>

        <div class="relative z-10 space-y-6">
            <!-- Title -->
            <div class="text-center md:text-left">
                <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white">Ubah Foto Profil</h1>
                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                    Perbarui foto identitas Anda yang akan ditampilkan di seluruh platform HafizPlus.
                </p>
            </div>

            <!-- Form -->
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf
                @method('PUT')

                <!-- Photo Preview Section -->
                <div class="flex flex-col items-center justify-center space-y-4 md:flex-row md:space-x-8 md:space-y-0 md:justify-start">
                    <div class="relative group">
                        <!-- Preview Circle -->
                        <div class="w-32 h-32 rounded-full overflow-hidden border-4 border-slate-200 dark:border-slate-700 shadow-md transition group-hover:border-emerald-500 duration-300">
                            <img id="avatar-preview" 
                                 src="{{ $user->profile_picture_url }}" 
                                 alt="Preview Avatar" 
                                 class="w-full h-full object-cover" />
                        </div>
                        
                        <!-- Overlay icon indicator -->
                        <div class="absolute inset-0 flex items-center justify-center bg-black/40 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300 cursor-pointer pointer-events-none">
                            <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                    </div>

                    <!-- File Input Controller -->
                    <div class="space-y-2 text-center md:text-left flex-1">
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                            Pilih Foto Baru
                        </label>
                        <input type="file" 
                               id="profile_picture_input"
                               name="profile_picture" 
                               accept="image/*" 
                               class="block w-full text-sm text-slate-500
                                      file:mr-4 file:py-2.5 file:px-5
                                      file:rounded-2xl file:border-0
                                      file:text-xs file:font-bold
                                      file:bg-indigo-50 file:text-indigo-700
                                      hover:file:bg-indigo-100
                                      dark:file:bg-slate-800 dark:file:text-indigo-400
                                      cursor-pointer" />
                        <p class="text-xs text-slate-400 dark:text-slate-500">
                            Mendukung JPG, PNG, WEBP, atau SVG. Ukuran maksimum 2MB.
                        </p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row justify-end items-center gap-3 pt-6 border-t border-slate-200/50 dark:border-slate-800/50">
                    <a href="{{ route('profile.show') }}" 
                       class="w-full sm:w-auto px-6 py-2.5 text-center text-sm font-semibold text-slate-700 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700 rounded-2xl transition">
                        Batal
                    </a>
                    <button type="submit" 
                            class="w-full sm:w-auto px-6 py-2.5 text-center text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-750 rounded-2xl shadow-md shadow-indigo-650/15 hover:shadow-lg transition">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Instant Image Preview JavaScript -->
<script>
    document.getElementById('profile_picture_input').addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (event) {
                document.getElementById('avatar-preview').src = event.target.result;
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection
