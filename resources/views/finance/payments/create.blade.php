@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Catat Pembayaran Santri</h1>

    @if($errors->any())
        <div class="mb-4 rounded-lg bg-red-50 px-4 py-3 text-red-700">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('finance.payments.store') }}" method="POST" class="bg-white rounded-xl shadow p-6 space-y-5">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Santri</label>
                <select name="student_id" class="mt-1 w-full rounded-lg border-gray-300 select2" id="student_id_select" required>
                    <option value="">Pilih Santri</option>
                    @foreach($students as $student)
                        <option value="{{ $student->id }}">
                            {{ $student->full_name }} — {{ $student->classRoom?->name ?? '-' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Tanggal Bayar</label>
                <input type="date" name="payment_date" value="{{ now()->toDateString() }}" class="mt-1 w-full rounded-lg border-gray-300" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Metode</label>
                <select name="payment_method" class="mt-1 w-full rounded-lg border-gray-300" required>
                    <option value="cash">Tunai (Cash)</option>
                    <option value="bank_transfer">Transfer Bank (Manual)</option>
                    <option value="qris_external">QRIS External</option>
                    <option value="adjustment">Adjustment/Koreksi</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Nominal Pembayaran (Rp)</label>
                <input type="number" name="amount" min="1" class="mt-1 w-full rounded-lg border-gray-300" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Nomor Referensi (opsional)</label>
                <input type="text" name="reference_number" class="mt-1 w-full rounded-lg border-gray-300" placeholder="Contoh: No. Ref Transfer, ID Transaksi QRIS">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 font-bold mb-1">Alokasikan ke Tagihan</label>
            <p class="text-xs text-gray-500 mb-2">Tentukan tagihan mana yang dibayar. Sistem akan mengalokasikan pembayaran ke tagihan yang dipilih berdasarkan tanggal jatuh tempo terdekat.</p>
            <select name="bill_ids[]" id="bill_ids_select" multiple class="mt-1 w-full rounded-lg border-gray-300" style="min-height: 120px;">
                @foreach($openBills as $bill)
                    <option value="{{ $bill->id }}" data-student-id="{{ $bill->student_id }}">
                        [{{ $bill->invoice_number }}] {{ $bill->title }}
                        — Sisa: Rp {{ number_format($bill->outstanding_amount, 0, ',', '.') }}
                        ({{ $bill->student?->full_name }})
                    </option>
                @endforeach
            </select>
            <p class="text-xs text-gray-400 mt-1">Tekan tombol Ctrl (Windows) / Cmd (Mac) sambil klik untuk memilih lebih dari satu tagihan. Jika dikosongkan, tagihan harus dialokasikan manual nanti.</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Catatan Internal</label>
            <textarea name="note" rows="3" class="mt-1 w-full rounded-lg border-gray-300" placeholder="Keterangan tambahan untuk pembayaran ini..."></textarea>
        </div>

        <div class="text-right">
            <a href="{{ route('finance.payments.index') }}" class="px-4 py-2 border rounded-lg mr-2 inline-block">Batal</a>
            <button class="px-4 py-2 bg-blue-600 text-white rounded-lg">
                Simpan Pembayaran
            </button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const studentSelect = document.getElementById('student_id_select');
        const billSelect = document.getElementById('bill_ids_select');
        const billOptions = Array.from(billSelect.options);

        studentSelect.addEventListener('change', function() {
            const selectedStudentId = this.value;
            billSelect.innerHTML = '';
            
            if (!selectedStudentId) {
                billOptions.forEach(opt => billSelect.appendChild(opt.cloneNode(true)));
                return;
            }

            const filteredOptions = billOptions.filter(opt => opt.getAttribute('data-student-id') === selectedStudentId);
            if(filteredOptions.length === 0) {
                const noBillOpt = document.createElement('option');
                noBillOpt.value = '';
                noBillOpt.disabled = true;
                noBillOpt.text = 'Tidak ada tagihan aktif untuk santri ini';
                billSelect.appendChild(noBillOpt);
            } else {
                filteredOptions.forEach(opt => billSelect.appendChild(opt.cloneNode(true)));
            }
        });
    });
</script>
@endsection
