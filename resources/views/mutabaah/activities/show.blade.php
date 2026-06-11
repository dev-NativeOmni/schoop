@extends('layouts.app')

@section('title', 'Detail Aktivitas Mutabaah')

@section('content')
<div class="container py-4" style="max-width:720px">

    <div class="d-flex align-items-center mb-4 gap-2">
        <a href="{{ route('mutabaah.activities.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h1 class="h4 mb-0 fw-bold">{{ $activity->name }}</h1>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-4">
            <dl class="row mb-0">
                <dt class="col-sm-4 text-secondary">Kategori</dt>
                <dd class="col-sm-8 fw-semibold">{{ $activity->category?->name ?? '-' }}</dd>

                <dt class="col-sm-4 text-secondary">Tipe Input</dt>
                <dd class="col-sm-8">
                    <span class="badge bg-info">{{ ucfirst($activity->input_type) }}</span>
                </dd>

                <dt class="col-sm-4 text-secondary">Target Skor</dt>
                <dd class="col-sm-8">{{ $activity->target_score ?? '-' }}</dd>

                <dt class="col-sm-4 text-secondary">Target Jumlah</dt>
                <dd class="col-sm-8">
                    {{ $activity->target_count ?? '-' }}
                    {{ $activity->target_unit }}
                </dd>

                <dt class="col-sm-4 text-secondary">Wajib</dt>
                <dd class="col-sm-8">
                    @if($activity->is_required)
                        <span class="badge bg-danger">Wajib</span>
                    @else
                        <span class="text-muted">Tidak</span>
                    @endif
                </dd>

                <dt class="col-sm-4 text-secondary">Aktif</dt>
                <dd class="col-sm-8">
                    @if($activity->is_active)
                        <span class="badge bg-success">Aktif</span>
                    @else
                        <span class="badge bg-secondary">Nonaktif</span>
                    @endif
                </dd>

                <dt class="col-sm-4 text-secondary">Guru boleh input</dt>
                <dd class="col-sm-8">{{ $activity->allow_teacher_input ? 'Ya' : 'Tidak' }}</dd>

                <dt class="col-sm-4 text-secondary">Orang tua boleh input</dt>
                <dd class="col-sm-8">{{ $activity->allow_parent_input ? 'Ya' : 'Tidak' }}</dd>

                <dt class="col-sm-4 text-secondary">Santri boleh input</dt>
                <dd class="col-sm-8">{{ $activity->allow_student_input ? 'Ya' : 'Tidak' }}</dd>

                <dt class="col-sm-4 text-secondary">Urutan</dt>
                <dd class="col-sm-8">{{ $activity->sort_order }}</dd>

                <dt class="col-sm-4 text-secondary">Deskripsi</dt>
                <dd class="col-sm-8 whitespace-pre-line">{{ $activity->description ?? '-' }}</dd>
            </dl>
        </div>
    </div>

    @if(auth()->user()->hasRole(['super_admin','admin']))
    <div class="mt-3 d-flex gap-2">
        <a href="{{ route('mutabaah.activities.edit', $activity) }}" class="btn btn-warning">
            <i class="bi bi-pencil me-1"></i> Edit
        </a>
    </div>
    @endif
</div>
@endsection
