@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h2 class="text-2xl font-bold">Export Laporan Tahfizh</h2>
        <p class="text-sm text-slate-500">
            Export laporan tahfizh dalam format PDF dan Excel.
        </p>
    </div>

    @if ($errors->any())
        <div class="mb-4 rounded-lg bg-red-50 p-4 text-sm text-red-700">
            <div class="font-bold">Validasi gagal:</div>
            <ul class="mt-2 list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid gap-6">
        <section class="rounded-2xl bg-white p-6 shadow-sm">
            <h3 class="mb-4 text-lg font-bold">Export Laporan Bulanan</h3>

            <form method="GET" class="grid gap-4 md:grid-cols-3">
                <div>
                    <label class="mb-1 block text-sm font-semibold">Bulan</label>
                    <input type="month" name="month" value="{{ now()->format('Y-m') }}"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2">
                </div>

                @include('exports.tahfizh.partials.filters')

                <div class="flex items-end gap-2">
                    <button type="submit"
                            formaction="{{ route('exports.tahfizh.monthly.excel') }}"
                            class="rounded-lg bg-green-700 px-4 py-2 text-sm font-semibold text-white hover:bg-green-600">
                        Excel
                    </button>

                    <button type="submit"
                            formaction="{{ route('exports.tahfizh.monthly.pdf') }}"
                            class="rounded-lg bg-red-700 px-4 py-2 text-sm font-semibold text-white hover:bg-red-600">
                        PDF
                    </button>
                </div>
            </form>
        </section>

        <section class="rounded-2xl bg-white p-6 shadow-sm">
            <h3 class="mb-4 text-lg font-bold">Export Laporan Triwulan</h3>

            <form method="GET" class="grid gap-4 md:grid-cols-3">
                <div>
                    <label class="mb-1 block text-sm font-semibold">Tahun</label>
                    <input type="number" name="year" value="{{ now()->format('Y') }}"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2">
                </div>

                <div>
                    <label class="mb-1 block text-sm font-semibold">Triwulan</label>
                    <select name="quarter" class="w-full rounded-lg border border-slate-300 px-3 py-2">
                        <option value="1">Triwulan 1</option>
                        <option value="2">Triwulan 2</option>
                        <option value="3">Triwulan 3</option>
                        <option value="4">Triwulan 4</option>
                    </select>
                </div>

                @include('exports.tahfizh.partials.filters')

                <div class="flex items-end gap-2">
                    <button type="submit"
                            formaction="{{ route('exports.tahfizh.quarterly.excel') }}"
                            class="rounded-lg bg-green-700 px-4 py-2 text-sm font-semibold text-white hover:bg-green-600">
                        Excel
                    </button>

                    <button type="submit"
                            formaction="{{ route('exports.tahfizh.quarterly.pdf') }}"
                            class="rounded-lg bg-red-700 px-4 py-2 text-sm font-semibold text-white hover:bg-red-600">
                        PDF
                    </button>
                </div>
            </form>
        </section>

        <section class="rounded-2xl bg-white p-6 shadow-sm">
            <h3 class="mb-4 text-lg font-bold">Export Ringkasan Dashboard</h3>

            <form method="GET" class="grid gap-4 md:grid-cols-3">
                <div>
                    <label class="mb-1 block text-sm font-semibold">Tanggal Mulai</label>
                    <input type="date" name="date_from" value="{{ now()->startOfMonth()->toDateString() }}"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2">
                </div>

                <div>
                    <label class="mb-1 block text-sm font-semibold">Tanggal Akhir</label>
                    <input type="date" name="date_until" value="{{ now()->toDateString() }}"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2">
                </div>

                @include('exports.tahfizh.partials.filters')

                <div class="flex items-end gap-2">
                    <button type="submit"
                            formaction="{{ route('exports.tahfizh.dashboard.excel') }}"
                            class="rounded-lg bg-green-700 px-4 py-2 text-sm font-semibold text-white hover:bg-green-600">
                        Excel
                    </button>

                    <button type="submit"
                            formaction="{{ route('exports.tahfizh.dashboard.pdf') }}"
                            class="rounded-lg bg-red-700 px-4 py-2 text-sm font-semibold text-white hover:bg-red-600">
                        PDF
                    </button>
                </div>
            </form>
        </section>
    </div>
@endsection
