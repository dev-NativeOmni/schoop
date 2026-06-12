@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Buat Tagihan Santri</h1>

    @if($errors->any())
        <div class="mb-4 rounded-lg bg-red-50 px-4 py-3 text-red-700">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('finance.bills.store') }}" method="POST" class="bg-white rounded-xl shadow p-6 space-y-5">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Santri</label>
                <select name="student_id" class="mt-1 w-full rounded-lg border-gray-300" required>
                    @foreach($students as $student)
                        <option value="{{ $student->id }}">
                            {{ $student->full_name }} — {{ $student->classRoom?->name ?? '-' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Judul Tagihan</label>
                <input type="text" name="title" class="mt-1 w-full rounded-lg border-gray-300" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Tanggal Terbit</label>
                <input type="date" name="issued_date" value="{{ now()->toDateString() }}" class="mt-1 w-full rounded-lg border-gray-300" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Jatuh Tempo</label>
                <input type="date" name="due_date" class="mt-1 w-full rounded-lg border-gray-300">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
            <textarea name="description" rows="2" class="mt-1 w-full rounded-lg border-gray-300"></textarea>
        </div>

        <div class="bg-gray-50 rounded-lg p-4">
            <h2 class="font-semibold text-gray-900 mb-3">Item Tagihan</h2>

            @for($i = 0; $i < 5; $i++)
                <div class="grid grid-cols-1 md:grid-cols-5 gap-3 bg-white rounded-lg border p-3 mb-3">
                    <div>
                        <label class="block text-xs text-gray-500">Master Item</label>
                        <select name="items[{{ $i }}][finance_fee_item_id]" class="mt-1 w-full rounded-lg border-gray-300">
                            <option value="">Manual</option>
                            @foreach($feeItems as $feeItem)
                                <option value="{{ $feeItem->id }}">{{ $feeItem->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs text-gray-500">Nama Item</label>
                        <input type="text" name="items[{{ $i }}][name]" class="mt-1 w-full rounded-lg border-gray-300" @if($i === 0) required @endif>
                    </div>

                    <div>
                        <label class="block text-xs text-gray-500">Qty</label>
                        <input type="number" name="items[{{ $i }}][quantity]" value="1" min="1" class="mt-1 w-full rounded-lg border-gray-300">
                    </div>

                    <div>
                        <label class="block text-xs text-gray-500">Nominal</label>
                        <input type="number" name="items[{{ $i }}][unit_amount]" value="0" min="0" class="mt-1 w-full rounded-lg border-gray-300">
                    </div>

                    <div>
                        <label class="block text-xs text-gray-500">Catatan</label>
                        <input type="text" name="items[{{ $i }}][description]" class="mt-1 w-full rounded-lg border-gray-300">
                    </div>
                </div>
            @endfor
        </div>

        <div class="text-right">
            <a href="{{ route('finance.bills.index') }}" class="px-4 py-2 border rounded-lg mr-2 inline-block">Batal</a>
            <button class="px-4 py-2 bg-blue-600 text-white rounded-lg">
                Simpan Tagihan
            </button>
        </div>
    </form>
</div>
@endsection
