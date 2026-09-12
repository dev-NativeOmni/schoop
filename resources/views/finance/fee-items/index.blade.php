@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <div class="flex flex-col mb-6 gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Item Biaya / Tarif</h1>
            <p class="text-sm text-gray-600">Daftar item biaya atau tarif sekolah default.</p>
        </div>

        <a href="{{ route('finance.fee-items.create') }}"
           class="px-4 py-2 bg-blue-600 text-white rounded-lg">
            Tambah Item Biaya
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-lg bg-green-50 px-4 py-3 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 text-gray-700">
                <tr>
                    <th class="px-4 py-3 text-left">Nama</th>
                    <th class="px-4 py-3 text-left">Kategori</th>
                    <th class="px-4 py-3 text-left">Kode</th>
                    <th class="px-4 py-3 text-left">Nominal Default</th>
                    <th class="px-4 py-3 text-left">Siklus</th>
                    <th class="px-4 py-3 text-left">Aktif</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($items as $item)
                    <tr>
                        <td class="px-4 py-3 font-medium">{{ $item->name }}</td>
                        <td class="px-4 py-3">{{ $item->category?->name ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $item->code ?? '-' }}</td>
                        <td class="px-4 py-3">Rp {{ number_format($item->default_amount, 0, ',', '.') }}</td>
                        <td class="px-4 py-3">{{ $item->billing_cycle }}</td>
                        <td class="px-4 py-3">{{ $item->is_active ? 'Ya' : 'Tidak' }}</td>
                        <td class="px-4 py-3 text-right space-x-2">
                            <a href="{{ route('finance.fee-items.show', $item) }}" class="text-blue-600">Detail</a>
                            <a href="{{ route('finance.fee-items.edit', $item) }}" class="text-amber-600">Edit</a>
                            <form action="{{ route('finance.fee-items.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('Hapus item ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-6 text-center text-gray-500">
                            Belum ada item biaya.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $items->links() }}
    </div>
</div>
@endsection
