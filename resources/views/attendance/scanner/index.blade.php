@extends('layouts.app')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Scanner QR Attendance</h1>
    <p class="text-sm text-gray-600">Scan QR santri untuk check in/check out.</p>
</div>

<div class="rounded-xl bg-white p-6 shadow">
    <div class="mb-5">
        <label class="block text-sm font-medium text-gray-700">Session Presensi</label>
        <select id="attendance_session_id" class="mt-1 w-full rounded-lg border-gray-300">
            @foreach($sessions as $session)
                <option value="{{ $session->id }}">
                    {{ $session->name }} — {{ $session->attendance_date?->format('d M Y') }}
                </option>
            @endforeach
        </select>
    </div>

    @if($sessions->isEmpty())
        <div class="rounded-lg bg-yellow-50 px-4 py-3 text-yellow-700">
            Belum ada session presensi aktif. Buat session terlebih dahulu.
        </div>
    @else
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <div>
                <video id="preview" class="w-full rounded-lg bg-black" autoplay muted playsinline></video>

                <div class="mt-3 flex gap-2">
                    <button id="startScanner" class="rounded-lg bg-blue-600 px-4 py-2 text-white">
                        Mulai Scanner
                    </button>
                    <button id="stopScanner" class="rounded-lg bg-gray-600 px-4 py-2 text-white">
                        Stop
                    </button>
                </div>

                <p class="mt-2 text-xs text-gray-500">
                    Jika kamera/browser tidak mendukung scanner otomatis, gunakan input manual token di samping.
                </p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Manual QR Payload</label>
                <textarea id="qr_payload"
                          rows="5"
                          class="mt-1 w-full rounded-lg border-gray-300"
                          placeholder="Tempel payload QR di sini"></textarea>

                <button id="submitManual"
                        class="mt-3 rounded-lg bg-green-600 px-4 py-2 text-white">
                    Submit Presensi
                </button>

                <div id="scanResult" class="mt-4 hidden rounded-lg px-4 py-3"></div>
            </div>
        </div>
    @endif
</div>
@endsection

<script>
    const csrfToken = @json(csrf_token());
    const scanUrl = @json(route('attendance.scanner.scan'));

    let stream = null;
    let detector = null;
    let scanning = false;
    let lastPayload = null;
    let lastScanAt = 0;

    const video = document.getElementById('preview');
    const resultBox = document.getElementById('scanResult');

    function showResult(message, success = true) {
        resultBox.classList.remove('hidden');
        resultBox.classList.remove('bg-green-50', 'text-green-700', 'bg-red-50', 'text-red-700');

        if (success) {
            resultBox.classList.add('bg-green-50', 'text-green-700');
        } else {
            resultBox.classList.add('bg-red-50', 'text-red-700');
        }

        resultBox.textContent = message;
    }

    async function submitPayload(payload) {
        const sessionId = document.getElementById('attendance_session_id').value;

        if (!sessionId) {
            showResult('Pilih session presensi terlebih dahulu.', false);
            return;
        }

        if (!payload) {
            showResult('QR payload kosong.', false);
            return;
        }

        const now = Date.now();

        if (payload === lastPayload && now - lastScanAt < 3000) {
            return;
        }

        lastPayload = payload;
        lastScanAt = now;

        try {
            const response = await fetch(scanUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    attendance_session_id: sessionId,
                    qr_payload: payload,
                }),
            });

            const data = await response.json();

            if (!response.ok) {
                const message = data.message || Object.values(data.errors || {}).flat().join(' ') || 'Scan gagal.';
                showResult(message, false);
                return;
            }

            const record = data.record;
            showResult(`${record.student_name} — ${record.status} — IN: ${record.check_in_at || '-'} OUT: ${record.check_out_at || '-'}`, true);
        } catch (error) {
            showResult('Gagal mengirim data presensi.', false);
        }
    }

    async function startScanner() {
        if (!('BarcodeDetector' in window)) {
            showResult('Browser tidak mendukung BarcodeDetector. Gunakan input manual token.', false);
            return;
        }

        detector = new BarcodeDetector({ formats: ['qr_code'] });

        stream = await navigator.mediaDevices.getUserMedia({
            video: { facingMode: 'environment' },
            audio: false,
        });

        video.srcObject = stream;
        scanning = true;

        requestAnimationFrame(scanLoop);
    }

    async function scanLoop() {
        if (!scanning || !detector || !video) {
            return;
        }

        try {
            const barcodes = await detector.detect(video);

            if (barcodes.length > 0) {
                await submitPayload(barcodes[0].rawValue);
            }
        } catch (error) {
            // Abaikan frame error.
        }

        requestAnimationFrame(scanLoop);
    }

    function stopScanner() {
        scanning = false;

        if (stream) {
            stream.getTracks().forEach(track => track.stop());
            stream = null;
        }
    }

    document.getElementById('startScanner')?.addEventListener('click', startScanner);
    document.getElementById('stopScanner')?.addEventListener('click', stopScanner);

    document.getElementById('submitManual')?.addEventListener('click', function () {
        const payload = document.getElementById('qr_payload').value.trim();
        submitPayload(payload);
    });
</script>
