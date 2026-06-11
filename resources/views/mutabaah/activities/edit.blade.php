@extends('layouts.app')

@section('title', 'Edit Aktivitas Mutabaah')

@section('content')
<div class="container py-4" style="max-width:720px">

    <div class="d-flex align-items-center mb-4 gap-2">
        <a href="{{ route('mutabaah.activities.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h1 class="h4 mb-0 fw-bold">Edit: {{ $activity->name }}</h1>
    </div>

    @if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('mutabaah.activities.update', $activity) }}" id="form-activity">
                @csrf
                @method('PUT')

                {{-- Kategori --}}
                <div class="mb-3">
                    <label for="mutabaah_category_id" class="form-label fw-semibold">Kategori</label>
                    <select name="mutabaah_category_id" id="mutabaah_category_id" class="form-select @error('mutabaah_category_id') is-invalid @enderror">
                        <option value="">— Pilih Kategori —</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" @selected(old('mutabaah_category_id', $activity->mutabaah_category_id) == $cat->id)>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('mutabaah_category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Nama --}}
                <div class="mb-3">
                    <label for="name" class="form-label fw-semibold">Nama Aktivitas <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name', $activity->name) }}"
                           class="form-control @error('name') is-invalid @enderror" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Deskripsi --}}
                <div class="mb-3">
                    <label for="description" class="form-label fw-semibold">Deskripsi</label>
                    <textarea name="description" id="description" rows="2"
                              class="form-control @error('description') is-invalid @enderror">{{ old('description', $activity->description) }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Tipe Input --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Tipe Input <span class="text-danger">*</span></label>
                    <div class="row g-2">
                        @foreach(['checklist' => ['icon' => 'check-circle', 'label' => 'Checklist', 'desc' => 'Selesai / Tidak'],
                                  'score'    => ['icon' => 'star',         'label' => 'Skor',       'desc' => 'Nilai 0–100'],
                                  'count'    => ['icon' => '123',          'label' => 'Jumlah',     'desc' => 'Hitungan/unit'],
                                  'text'     => ['icon' => 'chat-text',    'label' => 'Teks',       'desc' => 'Catatan singkat']] as $type => $meta)
                        <div class="col-6 col-md-3">
                            <input type="radio" class="btn-check" name="input_type" id="type_{{ $type }}" value="{{ $type }}"
                                   @checked(old('input_type', $activity->input_type) === $type) required>
                            <label class="btn btn-outline-primary w-100 py-2" for="type_{{ $type }}">
                                <i class="bi bi-{{ $meta['icon'] }} d-block fs-5"></i>
                                <span class="fw-semibold">{{ $meta['label'] }}</span><br>
                                <small class="text-muted">{{ $meta['desc'] }}</small>
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Target Score --}}
                <div id="section-score" class="mb-3 d-none">
                    <label for="target_score" class="form-label fw-semibold">Target Skor Minimum</label>
                    <input type="number" name="target_score" id="target_score" min="0" max="100"
                           value="{{ old('target_score', $activity->target_score) }}" class="form-control">
                </div>

                {{-- Target Count --}}
                <div id="section-count" class="mb-3 d-none">
                    <div class="row g-2">
                        <div class="col-6">
                            <label for="target_count" class="form-label fw-semibold">Target Jumlah</label>
                            <input type="number" name="target_count" id="target_count" min="0"
                                   value="{{ old('target_count', $activity->target_count) }}" class="form-control">
                        </div>
                        <div class="col-6">
                            <label for="target_unit" class="form-label fw-semibold">Satuan</label>
                            <input type="text" name="target_unit" id="target_unit"
                                   value="{{ old('target_unit', $activity->target_unit) }}" class="form-control" placeholder="halaman / kali">
                        </div>
                    </div>
                </div>

                {{-- Sort Order --}}
                <div class="mb-3">
                    <label for="sort_order" class="form-label fw-semibold">Urutan Tampil</label>
                    <input type="number" name="sort_order" id="sort_order" min="0" max="65535"
                           value="{{ old('sort_order', $activity->sort_order) }}" class="form-control" style="max-width:120px">
                </div>

                {{-- Flags --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold d-block">Pengaturan</label>
                    <div class="form-check mb-1">
                        <input class="form-check-input" type="checkbox" name="is_required" id="is_required" value="1" @checked(old('is_required', $activity->is_required))>
                        <label class="form-check-label" for="is_required">Aktivitas Wajib</label>
                    </div>
                    <div class="form-check mb-1">
                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" @checked(old('is_active', $activity->is_active))>
                        <label class="form-check-label" for="is_active">Aktif</label>
                    </div>
                    <div class="form-check mb-1">
                        <input class="form-check-input" type="checkbox" name="allow_teacher_input" id="allow_teacher_input" value="1" @checked(old('allow_teacher_input', $activity->allow_teacher_input))>
                        <label class="form-check-label" for="allow_teacher_input">Guru boleh input</label>
                    </div>
                    <div class="form-check mb-1">
                        <input class="form-check-input" type="checkbox" name="allow_parent_input" id="allow_parent_input" value="1" @checked(old('allow_parent_input', $activity->allow_parent_input))>
                        <label class="form-check-label" for="allow_parent_input">Orang tua boleh input</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="allow_student_input" id="allow_student_input" value="1" @checked(old('allow_student_input', $activity->allow_student_input))>
                        <label class="form-check-label" for="allow_student_input">Santri boleh input</label>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-save me-1"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('mutabaah.activities.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleTargetSections() {
    const type = document.querySelector('input[name="input_type"]:checked')?.value;
    document.getElementById('section-score').classList.toggle('d-none', type !== 'score');
    document.getElementById('section-count').classList.toggle('d-none', type !== 'count');
}
document.querySelectorAll('input[name="input_type"]').forEach(el => el.addEventListener('change', toggleTargetSections));
toggleTargetSections();
</script>
@endpush
