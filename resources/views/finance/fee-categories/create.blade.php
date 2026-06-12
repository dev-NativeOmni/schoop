@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Tambah Kategori Biaya</h1>

    @if($errors->any())
        <div class="mb-4 rounded-lg bg-red-50 px-4 py-3 text-red-700">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('finance.fee-categories.store') }}" method="POST" class="bg-white rounded-xl shadow p-6 space-y-5">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700">Nama Kategori</label>
            <input type="text"
                   name="name"
                   value="{{ old('name') }}"
                   class="mt-1 w-full rounded-lg border-gray-300"
                   required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
            <textarea name="description"
                      rows="3"
                      class="mt-1 w-full rounded-lg border-gray-300">{{ old('description') }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Urutan</label>
            <input type="number"
                   name="sort_order"
                   value="{{ old('sort_order', 0) }}"
                   min="0"
                   class="mt-1 w-full rounded-lg border-gray-300">
        </div>

        <label class="flex items-center gap-2">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', true))>
            <span class="text-sm text-gray-700">Aktif</span>
        </label>

        <div class="flex justify-end gap-3">
            <a href="{{ route('finance.fee-categories.index') }}" class="px-4 py-2 border rounded-lg">Batal</a>
            <button class="px-4 py-2 bg-blue-600 text-white rounded-lg">Simpan</button>
        </div>
    </form>
</div>
@endsection
