@extends('layouts.app')

@section('title', 'Template Aktivitas Mutabaah')

@section('content')
<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold text-dark">
                <i class="bi bi-list-check text-primary me-2"></i>Template Aktivitas Mutabaah
            </h1>
            <p class="text-muted mb-0">Kelola template aktivitas ibadah dan karakter harian santri.</p>
        </div>
        @can('manage-mutabaah-template')
        <a href="{{ route('mutabaah.activities.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Tambah Aktivitas
        </a>
        @else
        @if(auth()->user()->hasRole(['super_admin','admin']))
        <a href="{{ route('mutabaah.activities.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Tambah Aktivitas
        </a>
        @endif
        @endcan
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- Filters --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body py-3">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Kategori</label>
                    <select name="category_id" class="form-select form-select-sm">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" @selected(request('category_id') == $cat->id)>
                            {{ $cat->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-5">
                    <label class="form-label small fw-semibold">Cari Aktivitas</label>
                    <input type="text" name="search" class="form-control form-control-sm"
                           value="{{ request('search') }}" placeholder="Nama aktivitas...">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary btn-sm w-100">
                        <i class="bi bi-search me-1"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Activities List grouped by category --}}
    @php
        $grouped = $activities->getCollection()->groupBy(fn($a) => $a->category?->name ?? 'Tanpa Kategori');
    @endphp

    @forelse($grouped as $categoryName => $activityGroup)
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light py-2">
            <h6 class="mb-0 fw-bold text-secondary">
                <i class="bi bi-tag me-1"></i>{{ $categoryName }}
                <span class="badge bg-secondary ms-2">{{ $activityGroup->count() }}</span>
            </h6>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Aktivitas</th>
                        <th>Tipe Input</th>
                        <th>Target</th>
                        <th class="text-center">Wajib</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($activityGroup->sortBy('sort_order') as $activity)
                    <tr>
                        <td>
                            <div class="fw-semibold">{{ $activity->name }}</div>
                            @if($activity->description)
                            <small class="text-muted">{{ Str::limit($activity->description, 60) }}</small>
                            @endif
                        </td>
                        <td>
                            @php
                                $badges = [
                                    'checklist' => 'success',
                                    'score' => 'info',
                                    'count' => 'warning',
                                    'text' => 'secondary',
                                ];
                            @endphp
                            <span class="badge bg-{{ $badges[$activity->input_type] ?? 'secondary' }}">
                                {{ ucfirst($activity->input_type) }}
                            </span>
                        </td>
                        <td>
                            @if($activity->input_type === 'score' && $activity->target_score)
                                <span class="text-muted small">≥ {{ $activity->target_score }}</span>
                            @elseif($activity->input_type === 'count' && $activity->target_count)
                                <span class="text-muted small">{{ $activity->target_count }} {{ $activity->target_unit }}</span>
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($activity->is_required)
                            <span class="badge bg-danger">Wajib</span>
                            @else
                            <span class="text-muted small">—</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($activity->is_active)
                            <span class="badge bg-success">Aktif</span>
                            @else
                            <span class="badge bg-secondary">Nonaktif</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('mutabaah.activities.show', $activity) }}" class="btn btn-outline-info" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if(auth()->user()->hasRole(['super_admin','admin']))
                                <a href="{{ route('mutabaah.activities.edit', $activity) }}" class="btn btn-outline-primary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="POST" action="{{ route('mutabaah.activities.destroy', $activity) }}"
                                      onsubmit="return confirm('Hapus aktivitas ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @empty
    <div class="text-center py-5 text-muted">
        <i class="bi bi-inbox display-4"></i>
        <p class="mt-2">Belum ada aktivitas mutabaah.</p>
        @if(auth()->user()->hasRole(['super_admin','admin']))
        <a href="{{ route('mutabaah.activities.create') }}" class="btn btn-primary">
            Tambah Aktivitas Pertama
        </a>
        @endif
    </div>
    @endforelse

    {{-- Pagination --}}
    <div class="d-flex justify-content-center">
        {{ $activities->links() }}
    </div>

</div>
@endsection
