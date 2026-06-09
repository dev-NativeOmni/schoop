@extends('layouts.app')

@section('content')
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold">Target Tahfizh</h2>
            <p class="text-sm text-slate-500">Kelola target hafalan untuk sekolah, kelas, program, atau santri secara spesifik.</p>
        </div>

        @if (auth()->user()->hasRole(['super_admin', 'admin']))
            <a href="{{ route('tahfizh.targets.create') }}"
               class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">
                Tambah Target
            </a>
        @endif
    </div>

    <!-- Filter Form -->
    <div class="mb-6 rounded-2xl bg-white p-4 shadow-sm border border-slate-100">
        <form method="GET" action="{{ route('tahfizh.targets.index') }}" class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-5 items-end">
            <div>
                <label for="school_id" class="block text-xs font-semibold text-slate-600 mb-1">Sekolah</label>
                <select name="school_id" id="school_id" class="w-full rounded-lg border border-slate-200 p-2 text-sm">
                    <option value="">Semua Sekolah</option>
                    @foreach ($schools as $school)
                        <option value="{{ $school->id }}" {{ request('school_id') == $school->id ? 'selected' : '' }}>
                            {{ $school->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="class_room_id" class="block text-xs font-semibold text-slate-600 mb-1">Kelas</label>
                <select name="class_room_id" id="class_room_id" class="w-full rounded-lg border border-slate-200 p-2 text-sm">
                    <option value="">Semua Kelas</option>
                    @foreach ($classRooms as $classRoom)
                        <option value="{{ $classRoom->id }}" {{ request('class_room_id') == $classRoom->id ? 'selected' : '' }}>
                            {{ $classRoom->name }} ({{ $classRoom->school?->name }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="student_id" class="block text-xs font-semibold text-slate-600 mb-1">Santri</label>
                <select name="student_id" id="student_id" class="w-full rounded-lg border border-slate-200 p-2 text-sm">
                    <option value="">Semua Santri</option>
                    @foreach ($students as $student)
                        <option value="{{ $student->id }}" {{ request('student_id') == $student->id ? 'selected' : '' }}>
                            {{ $student->full_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="program_type" class="block text-xs font-semibold text-slate-600 mb-1">Jenis Program</label>
                <select name="program_type" id="program_type" class="w-full rounded-lg border border-slate-200 p-2 text-sm">
                    <option value="">Semua Program</option>
                    <option value="reguler" {{ request('program_type') == 'reguler' ? 'selected' : '' }}>Reguler</option>
                    <option value="takhassus" {{ request('program_type') == 'takhassus' ? 'selected' : '' }}>Takhassus</option>
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="flex-1 rounded-lg bg-slate-900 py-2 px-3 text-sm font-semibold text-white hover:bg-slate-700">
                    Filter
                </button>
                <a href="{{ route('tahfizh.targets.index') }}" class="rounded-lg bg-slate-100 py-2 px-3 text-sm font-semibold text-slate-700 hover:bg-slate-200 text-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="overflow-hidden rounded-2xl bg-white shadow-sm border border-slate-100">
        <table class="w-full border-collapse text-left text-sm">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-4 py-3">Nama Target</th>
                    <th class="px-4 py-3">Berlaku Untuk</th>
                    <th class="px-4 py-3">Target Harian</th>
                    <th class="px-4 py-3">Target Mingguan</th>
                    <th class="px-4 py-3">Target Bulanan</th>
                    <th class="px-4 py-3">Masa Berlaku</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($targets as $target)
                    <tr class="border-t">
                        <td class="px-4 py-3 font-semibold">
                            <a href="{{ route('tahfizh.targets.show', $target) }}" class="text-slate-900 hover:underline">
                                {{ $target->name }}
                            </a>
                        </td>
                        <td class="px-4 py-3">
                            @if ($target->student)
                                <span class="inline-block bg-indigo-50 text-indigo-700 px-2 py-0.5 rounded text-xs font-semibold">
                                    Santri: {{ $target->student->full_name }}
                                </span>
                            @elseif ($target->classRoom)
                                <span class="inline-block bg-emerald-50 text-emerald-700 px-2 py-0.5 rounded text-xs font-semibold">
                                    Kelas: {{ $target->classRoom->name }}
                                </span>
                            @elseif ($target->program_type)
                                <span class="inline-block bg-amber-50 text-amber-700 px-2 py-0.5 rounded text-xs font-semibold">
                                    Program: {{ ucfirst($target->program_type) }}
                                </span>
                            @else
                                <span class="inline-block bg-slate-100 text-slate-700 px-2 py-0.5 rounded text-xs font-semibold">
                                    Sekolah: {{ $target->school->name }}
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3">{{ $target->daily_target_lines }} baris</td>
                        <td class="px-4 py-3">{{ $target->weekly_target_lines }} baris</td>
                        <td class="px-4 py-3">{{ $target->monthly_target_lines }} baris</td>
                        <td class="px-4 py-3">
                            <span class="text-xs">
                                {{ $target->effective_from ? $target->effective_from->format('d/m/Y') : 'Selamanya' }}
                                -
                                {{ $target->effective_until ? $target->effective_until->format('d/m/Y') : 'Selamanya' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-block px-2 py-0.5 rounded-full text-xs font-semibold {{ $target->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $target->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex justify-end gap-2 text-xs">
                                <a href="{{ route('tahfizh.targets.show', $target) }}" class="text-slate-700 hover:underline">Lihat</a>
                                @if (auth()->user()->hasRole(['super_admin', 'admin']))
                                    <a href="{{ route('tahfizh.targets.edit', $target) }}" class="text-blue-700 hover:underline">Edit</a>

                                    <form method="POST" action="{{ route('tahfizh.targets.destroy', $target) }}"
                                          onsubmit="return confirm('Hapus target ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-700 hover:underline">
                                            Hapus
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-6 text-center text-slate-500">
                            Belum ada target tahfizh yang dikonfigurasi.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $targets->links() }}
    </div>
@endsection
