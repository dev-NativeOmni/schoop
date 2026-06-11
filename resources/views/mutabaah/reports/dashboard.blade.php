@extends('layouts.app')

@section('title', 'Laporan Mutabaah')

@section('content')
<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="mb-4">
        <h1 class="h3 mb-1 fw-bold text-dark">
            <i class="bi bi-bar-chart text-primary me-2"></i>Laporan Mutabaah
        </h1>
        <p class="text-muted mb-0">Ringkasan mutabaah santri berdasarkan periode.</p>
    </div>

    {{-- Filter --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body py-3">
            <form method="GET" action="{{ route('mutabaah.reports.dashboard') }}" class="row g-2 align-items-end">
                <div class="col-md-2">
                    <label class="form-label small fw-semibold">Tanggal Mulai</label>
                    <input type="date" name="start_date"
                           value="{{ $filters['start_date'] ?? $period['start_date'] }}"
                           class="form-control form-control-sm">
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold">Tanggal Selesai</label>
                    <input type="date" name="end_date"
                           value="{{ $filters['end_date'] ?? $period['end_date'] }}"
                           class="form-control form-control-sm">
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold">Kelas</label>
                    <select name="class_room_id" class="form-select form-select-sm">
                        <option value="">Semua Kelas</option>
                        @foreach($classRooms as $classRoom)
                        <option value="{{ $classRoom->id }}" @selected(($filters['class_room_id'] ?? null) == $classRoom->id)>
                            {{ $classRoom->name ?? 'Kelas #' . $classRoom->id }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold">Santri</label>
                    <select name="student_id" class="form-select form-select-sm">
                        <option value="">Semua Santri</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-sm w-100">
                        <i class="bi bi-search me-1"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body text-center py-3">
                    <div class="text-muted small">Total Record</div>
                    <div class="fs-4 fw-bold text-dark">{{ $summary['total_records'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body text-center py-3">
                    <div class="text-muted small">Selesai</div>
                    <div class="fs-4 fw-bold text-success">{{ $summary['done_records'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body text-center py-3">
                    <div class="text-muted small">Belum</div>
                    <div class="fs-4 fw-bold text-danger">{{ $summary['not_done_records'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body text-center py-3">
                    <div class="text-muted small">Completion Rate</div>
                    <div class="fs-4 fw-bold text-primary">{{ $summary['completion_rate'] }}%</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tables --}}
    <div class="row g-4">
        {{-- By Student --}}
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-light py-2">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-people me-1"></i>Rekap per Santri</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Santri</th>
                                <th class="text-center">Selesai</th>
                                <th class="text-center">Total</th>
                                <th class="text-center">%</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($by_student as $row)
                            <tr>
                                <td class="fw-semibold">{{ $row['student']?->full_name ?? $row['student']?->user?->name ?? '-' }}</td>
                                <td class="text-center">{{ $row['done'] }}</td>
                                <td class="text-center">{{ $row['total'] }}</td>
                                <td class="text-center">
                                    <span class="badge {{ $row['completion_rate'] >= 80 ? 'bg-success' : ($row['completion_rate'] >= 50 ? 'bg-warning' : 'bg-danger') }}">
                                        {{ $row['completion_rate'] }}%
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">Belum ada data.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- By Activity --}}
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-light py-2">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-list-check me-1"></i>Rekap per Aktivitas</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Aktivitas</th>
                                <th class="text-center">Selesai</th>
                                <th class="text-center">Total</th>
                                <th class="text-center">%</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($by_activity as $row)
                            <tr>
                                <td class="fw-semibold">{{ $row['activity']?->name ?? '-' }}</td>
                                <td class="text-center">{{ $row['done'] }}</td>
                                <td class="text-center">{{ $row['total'] }}</td>
                                <td class="text-center">
                                    <span class="badge {{ $row['completion_rate'] >= 80 ? 'bg-success' : ($row['completion_rate'] >= 50 ? 'bg-warning' : 'bg-danger') }}">
                                        {{ $row['completion_rate'] }}%
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">Belum ada data.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
