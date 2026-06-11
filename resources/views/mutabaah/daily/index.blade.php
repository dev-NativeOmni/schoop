@extends('layouts.app')

@section('title', 'Input Mutabaah Harian')

@section('content')
<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="mb-4">
        <h1 class="h3 mb-1 fw-bold text-dark">
            <i class="bi bi-pencil-square text-primary me-2"></i>Input Mutabaah Harian
        </h1>
        <p class="text-muted mb-0">Input aktivitas ibadah dan karakter harian santri.</p>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Filter --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body py-3">
            <form method="GET" action="{{ route('mutabaah.daily.index') }}" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small fw-semibold">Tanggal</label>
                    <input type="date" name="record_date" value="{{ $selectedDate }}" class="form-control form-control-sm">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold">Kelas</label>
                    <select name="class_room_id" class="form-select form-select-sm">
                        <option value="">Semua Kelas</option>
                        @foreach($classRooms as $classRoom)
                        <option value="{{ $classRoom->id }}" @selected(request('class_room_id') == $classRoom->id)>
                            {{ $classRoom->name ?? 'Kelas #' . $classRoom->id }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold">Santri</label>
                    <select name="student_id" class="form-select form-select-sm">
                        <option value="">Semua Santri</option>
                        @foreach($students as $s)
                        <option value="{{ $s->id }}" @selected(request('student_id') == $s->id)>
                            {{ $s->full_name ?? 'Santri #' . $s->id }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary btn-sm w-100">
                        <i class="bi bi-search me-1"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    @forelse($students as $student)
        <form method="POST" action="{{ route('mutabaah.daily.store') }}" class="card shadow-sm mb-4">
            @csrf

            <input type="hidden" name="student_id" value="{{ $student->id }}">
            <input type="hidden" name="record_date" value="{{ $selectedDate }}">

            <div class="card-header bg-light py-2 d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-0 fw-bold">
                        <i class="bi bi-person me-1"></i>{{ $student->full_name ?? 'Santri #' . $student->id }}
                    </h6>
                    <small class="text-muted">{{ $student->classRoom?->name ?? '-' }} — Tanggal: {{ $selectedDate }}</small>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width:30%">Aktivitas</th>
                            <th style="width:20%">Status</th>
                            <th style="width:15%">Skor</th>
                            <th style="width:15%">Jumlah</th>
                            <th style="width:20%">Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($activities as $index => $activity)
                            @php
                                $record = $existingRecords->get($activity->id);
                            @endphp

                            <tr>
                                <td>
                                    <input type="hidden" name="records[{{ $index }}][mutabaah_activity_id]" value="{{ $activity->id }}">
                                    <div class="fw-semibold">{{ $activity->name }}</div>
                                    <small class="text-muted">{{ $activity->category?->name ?? '-' }} · {{ $activity->input_type }}</small>
                                </td>
                                <td>
                                    <select name="records[{{ $index }}][status]" class="form-select form-select-sm">
                                        <option value="done" @selected(($record?->status ?? 'not_done') === 'done')>Selesai</option>
                                        <option value="not_done" @selected(($record?->status ?? 'not_done') === 'not_done')>Belum</option>
                                        <option value="excused" @selected(($record?->status ?? 'not_done') === 'excused')>Izin/Alasan</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="records[{{ $index }}][score]"
                                           value="{{ $record?->score }}" min="0" max="100"
                                           class="form-control form-control-sm"
                                           @disabled($activity->input_type !== 'score')>
                                </td>
                                <td>
                                    <input type="number" name="records[{{ $index }}][count_value]"
                                           value="{{ $record?->count_value }}" min="0"
                                           class="form-control form-control-sm"
                                           @disabled($activity->input_type !== 'count')>
                                </td>
                                <td>
                                    <input type="text" name="records[{{ $index }}][note]"
                                           value="{{ $record?->note }}"
                                           class="form-control form-control-sm" placeholder="Catatan">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="card-footer bg-light text-end">
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="bi bi-save me-1"></i> Simpan Mutabaah
                </button>
            </div>
        </form>
    @empty
        <div class="text-center py-5 text-muted">
            <i class="bi bi-inbox display-4"></i>
            <p class="mt-2">Tidak ada santri pada filter ini.</p>
        </div>
    @endforelse

</div>
@endsection
