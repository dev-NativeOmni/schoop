@extends('layouts.app')

@section('title', 'Edit Aktivitas Mutabaah')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    {{-- Back Button & Title --}}
    <div class="flex items-center space-x-4">
        <a href="{{ route('mutabaah.activities.index') }}" 
           class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-400 dark:hover:bg-slate-800 transition">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white">Edit: {{ $activity->name }}</h1>
    </div>

    {{-- Form Card --}}
    <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <form method="POST" action="{{ route('mutabaah.activities.update', $activity) }}" id="form-activity" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Kategori --}}
            <div>
                <label for="mutabaah_category_id" class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">Kategori</label>
                <select name="mutabaah_category_id" id="mutabaah_category_id" 
                        class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-sm text-slate-950 focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 dark:border-slate-800 dark:bg-slate-950 dark:text-white dark:focus:border-indigo-500 @error('mutabaah_category_id') border-rose-500 dark:border-rose-500 @enderror">
                    <option value="">— Pilih Kategori —</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" @selected(old('mutabaah_category_id', $activity->mutabaah_category_id) == $cat->id)>{{ $cat->name }}</option>
                    @endforeach
                </select>
                @error('mutabaah_category_id')
                    <p class="mt-1.5 text-xs font-semibold text-rose-600 dark:text-rose-450">{{ $message }}</p>
                @enderror
            </div>

            {{-- Nama Aktivitas --}}
            <div>
                <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">Nama Aktivitas <span class="text-rose-500">*</span></label>
                <input type="text" name="name" id="name" value="{{ old('name', $activity->name) }}" required
                       class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-sm text-slate-950 placeholder-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 dark:border-slate-800 dark:bg-slate-950 dark:text-white dark:focus:border-indigo-500 @error('name') border-rose-500 dark:border-rose-500 @enderror">
                @error('name')
                    <p class="mt-1.5 text-xs font-semibold text-rose-600 dark:text-rose-450">{{ $message }}</p>
                @enderror
            </div>

            {{-- Deskripsi --}}
            <div>
                <label for="description" class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">Deskripsi</label>
                <textarea name="description" id="description" rows="3"
                          class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-sm text-slate-950 placeholder-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 dark:border-slate-800 dark:bg-slate-950 dark:text-white dark:focus:border-indigo-500 @error('description') border-rose-500 dark:border-rose-500 @enderror"
                          placeholder="Berikan keterangan singkat atau instruksi pengerjaan aktivitas...">{{ old('description', $activity->description) }}</textarea>
                @error('description')
                    <p class="mt-1.5 text-xs font-semibold text-rose-600 dark:text-rose-450">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tipe Input --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-3">Tipe Input <span class="text-rose-500">*</span></label>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    @foreach(['checklist' => ['icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'Checklist', 'desc' => 'Ya / Tidak'],
                              'score'    => ['icon' => 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.907c.961 0 1.371 1.24.588 1.81l-3.97 2.883a1 1 0 00-.364 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.971-2.883a1 1 0 00-1.18 0l-3.97 2.883c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.364-1.118l-3.97-2.883c-.783-.57-.372-1.81.588-1.81h4.906a1 1 0 00.95-.69l1.518-4.674z', 'label' => 'Skor', 'desc' => 'Nilai 0–100'],
                              'count'    => ['icon' => 'M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z', 'label' => 'Jumlah', 'desc' => 'Hitungan Unit'],
                              'text'     => ['icon' => 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z', 'label' => 'Teks', 'desc' => 'Catatan Singkat']] as $type => $meta)
                        <label class="relative block rounded-xl border border-slate-200 bg-white p-3.5 cursor-pointer hover:bg-slate-50/50 focus-within:ring-2 focus-within:ring-indigo-500/20 dark:border-slate-800 dark:bg-slate-950 dark:hover:bg-slate-900 transition">
                            <input type="radio" name="input_type" value="{{ $type }}" class="sr-only peer" @checked(old('input_type', $activity->input_type) === $type) required>
                            <div class="absolute inset-0 rounded-xl border-2 border-transparent peer-checked:border-indigo-600 peer-checked:bg-indigo-50/5 dark:peer-checked:border-indigo-500 dark:peer-checked:bg-indigo-950/10 pointer-events-none transition"></div>
                            
                            <div class="relative z-10 flex flex-col items-center text-center">
                                <svg class="h-6 w-6 text-slate-400 peer-checked:text-indigo-600 dark:text-slate-500 dark:peer-checked:text-indigo-400 mb-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $meta['icon'] }}" />
                                </svg>
                                <span class="text-xs font-bold text-slate-800 dark:text-slate-200 block">{{ $meta['label'] }}</span>
                                <span class="text-[10px] text-slate-400 mt-0.5 block leading-tight">{{ $meta['desc'] }}</span>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- Target Score --}}
            <div id="section-score" class="hidden">
                <label for="target_score" class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">Target Skor Minimum</label>
                <input type="number" name="target_score" id="target_score" min="0" max="100"
                       value="{{ old('target_score', $activity->target_score) }}" 
                       class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-sm text-slate-950 placeholder-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 dark:border-slate-800 dark:bg-slate-950 dark:text-white dark:focus:border-indigo-500" 
                       placeholder="Contoh: 80">
            </div>

            {{-- Target Count --}}
            <div id="section-count" class="hidden">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="target_count" class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">Target Jumlah</label>
                        <input type="number" name="target_count" id="target_count" min="0"
                               value="{{ old('target_count', $activity->target_count) }}" 
                               class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-sm text-slate-950 placeholder-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 dark:border-slate-800 dark:bg-slate-950 dark:text-white dark:focus:border-indigo-500" 
                               placeholder="Contoh: 1">
                    </div>
                    <div>
                        <label for="target_unit" class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">Satuan Unit</label>
                        <input type="text" name="target_unit" id="target_unit"
                               value="{{ old('target_unit', $activity->target_unit) }}" 
                               class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-sm text-slate-950 placeholder-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 dark:border-slate-800 dark:bg-slate-950 dark:text-white dark:focus:border-indigo-500" 
                               placeholder="Contoh: halaman / kali">
                    </div>
                </div>
            </div>

            {{-- Sort Order --}}
            <div>
                <label for="sort_order" class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">Urutan Tampilan</label>
                <input type="number" name="sort_order" id="sort_order" min="0" max="65535"
                       value="{{ old('sort_order', $activity->sort_order) }}" 
                       class="w-full sm:max-w-[150px] rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-sm text-slate-950 focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 dark:border-slate-800 dark:bg-slate-950 dark:text-white dark:focus:border-indigo-500">
            </div>

            {{-- Flags Checkboxes --}}
            <div class="border-t border-slate-100 dark:border-slate-800 pt-5">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-3">Pengaturan Akses</label>
                <div class="space-y-3">
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="checkbox" name="is_required" id="is_required" value="1" @checked(old('is_required', $activity->is_required))
                               class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500/30 h-4.5 w-4.5">
                        <span class="text-sm font-semibold text-slate-700 dark:text-slate-350">Aktivitas Wajib (Harus dikerjakan setiap hari)</span>
                    </label>
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="checkbox" name="is_active" id="is_active" value="1" @checked(old('is_active', $activity->is_active))
                               class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500/30 h-4.5 w-4.5">
                        <span class="text-sm font-semibold text-slate-700 dark:text-slate-350">Aktif (Tampil di form mutabaah harian)</span>
                    </label>
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="checkbox" name="allow_teacher_input" id="allow_teacher_input" value="1" @checked(old('allow_teacher_input', $activity->allow_teacher_input))
                               class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500/30 h-4.5 w-4.5">
                        <span class="text-sm font-semibold text-slate-700 dark:text-slate-350">Guru boleh input</span>
                    </label>
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="checkbox" name="allow_parent_input" id="allow_parent_input" value="1" @checked(old('allow_parent_input', $activity->allow_parent_input))
                               class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500/30 h-4.5 w-4.5">
                        <span class="text-sm font-semibold text-slate-700 dark:text-slate-350">Orang tua boleh input</span>
                    </label>
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="checkbox" name="allow_student_input" id="allow_student_input" value="1" @checked(old('allow_student_input', $activity->allow_student_input))
                               class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500/30 h-4.5 w-4.5">
                        <span class="text-sm font-semibold text-slate-700 dark:text-slate-350">Santri boleh input</span>
                    </label>
                </div>
            </div>

            {{-- Actions --}}
            <div class="border-t border-slate-100 dark:border-slate-800 pt-5 flex items-center space-x-3">
                <button type="submit" 
                        class="inline-flex items-center justify-center space-x-2 rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700 shadow-sm transition-all focus:outline-none focus:ring-4 focus:ring-indigo-500/20 active:scale-[0.98]">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-1-1m1 1V3" />
                    </svg>
                    <span>Simpan Perubahan</span>
                </button>
                <a href="{{ route('mutabaah.activities.index') }}" 
                   class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-slate-800 transition">
                    Batal
                </a>
            </div>

        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleTargetSections() {
    const type = document.querySelector('input[name="input_type"]:checked')?.value;
    document.getElementById('section-score').classList.toggle('hidden', type !== 'score');
    document.getElementById('section-count').classList.toggle('hidden', type !== 'count');
}
document.querySelectorAll('input[name="input_type"]').forEach(el => el.addEventListener('change', toggleTargetSections));
toggleTargetSections();
</script>
@endpush
