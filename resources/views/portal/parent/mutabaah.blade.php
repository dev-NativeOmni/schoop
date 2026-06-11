@extends('layouts.app')

@section('title', 'Mutabaah Anak')

@section('content')
<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="mb-4">
        <h1 class="h3 mb-1 fw-bold text-dark">
            <i class="bi bi-heart text-primary me-2"></i>Mutabaah Anak
        </h1>
        <p class="text-muted mb-0">Pantau aktivitas ibadah dan karakter anak.</p>
    </div>

    {{-- Children Selector + Date Filter --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body py-3">
            <form method="GET" action="{{ route('portal.parent.mutabaah', $student ?? '') }}" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small fw-semibold">Anak</label>
                    <select name="student" class="form-select form-select-sm" onchange="this.form.submit()">
                        @foreach($children ?? [] as $child)
                        <option value="{{ $child->id }}" @selected(($student->id ?? null) == $child->id)>
                            {{ $child->full_name ?? 'Santri #' . $child->id }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold">Tanggal Mulai</label>
                    <input type="date" name="start_date"
                           value="{{ $period['start_date'] ?? now()->startOfWeek()->toDateString() }}"
                           class="form-control form-control-sm">
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold">Tanggal Selesai</label>
                    <input type="date" name="end_date"
                           value="{{ $period['end_date'] ?? now()->endOfWeek()->toDateString() }}"
                           class="form-control form-control-sm">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-sm w-100">
                        <i class="bi bi-search me-1"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    @if(isset($student))
        {{-- Summary Cards --}}
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body text-center py-3">
                        <div class="text-muted small">Total Record</div>
                        <div class="fs-4 fw-bold text-dark">{{ $summary['total_records'] }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body text-center py-3">
                        <div class="text-muted small">Selesai</div>
                        <div class="fs-4 fw-bold text-success">{{ $summary['done_records'] }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body text-center py-3">
                        <div class="text-muted small">Completion Rate</div>
                        <div class="fs-4 fw-bold text-primary">{{ $summary['completion_rate'] }}%</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Records Table --}}
        <div class="card shadow-sm">
            <div class="card-header bg-light py-2">
                <h6 class="mb-0 fw-bold"><i class="bi bi-table me-1"></i>Detail Mutabaah</h6>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Tanggal</th>
                            <th>Aktivitas</th>
                            <th>Status</th>
                            <th>Nilai/Jumlah</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($records as $record)
                        <tr>
                            <td>{{ $record->record_date?->format('d M Y') }}</td>
                            <td class="fw-semibold">{{ $record->activity?->name ?? '-' }}</td>
                            <td>
                                @if($record->status === 'done')
                                    <span class="badge bg-success">Selesai</span>
                                @elseif($record->status === 'excused')
                                    <span class="badge bg-warning text-dark">Izin/Alasan</span>
                                @else
                                    <span class="badge bg-danger">Belum</span>
                                @endif
                            </td>
                            <td>{{ $record->score ?? $record->count_value ?? '-' }}</td>
                            <td>{{ $record->note ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Belum ada mutabaah pada periode ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="text-center py-5 text-muted">
            <i class="bi bi-person-x display-4"></i>
            <p class="mt-2">Belum ada data anak yang terhubung. Hubungi admin sekolah.</p>
        </div>
    @endif

</div>
@endsection
