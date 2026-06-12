@extends('layouts.app')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between border-b border-slate-200/80 pb-5 dark:border-slate-800 gap-4">
        <div class="flex items-center space-x-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 dark:bg-indigo-950/30 dark:text-indigo-400">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h3m-3-3H8m4 2a2 2 0 100-4 2 2 0 000 4z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                </svg>
            </div>
            <div>
                <h2 class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white">Scanner QR Attendance</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400">Scan QR code santri untuk check in/check out presensi.</p>
            </div>
        </div>
    </div>

    <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 space-y-6">
        <div>
            <label for="attendance_session_id" class="mb-1.5 block text-xs font-bold text-slate-500 dark:text-slate-455 uppercase tracking-wider">Session Presensi</label>
            <select id="attendance_session_id" class="w-full max-w-xl rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500">
                @foreach($sessions as $session)
                    <option value="{{ $session->id }}">
                        {{ $session->name }} — {{ $session->attendance_date?->format('d M Y') }}
                    </option>
                @endforeach
            </select>
        </div>

        @if($sessions->isEmpty())
            <div class="rounded-xl border border-amber-100 bg-amber-50/30 p-4 text-sm text-amber-700 dark:border-amber-900/30 dark:bg-amber-950/20 dark:text-amber-400">
                Belum ada session presensi aktif. Silakan buat session presensi terlebih dahulu.
            </div>
        @else
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                {{-- Scanner Column --}}
                <div class="space-y-4">
                    <div class="relative rounded-2xl overflow-hidden bg-black border border-slate-250/60 dark:border-slate-800 shadow-md">
                        <video id="preview" class="w-full aspect-[4/3] object-cover" autoplay muted playsinline></video>
                        <div class="absolute inset-0 border-2 border-indigo-500/30 pointer-events-none rounded-2xl"></div>
                    </div>

                    <div class="flex items-center space-x-2">
                        <button id="startScanner" class="inline-flex items-center justify-center rounded-full bg-indigo-600 px-5 py-2.5 text-xs font-bold text-white hover:bg-indigo-700 shadow-sm transition-all focus:outline-none focus:ring-4 focus:ring-indigo-500/20 active:scale-[0.98]">
                            Mulai Scanner
                        </button>
                        <button id="stopScanner" class="inline-flex items-center justify-center rounded-full bg-slate-100 px-5 py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-350 dark:hover:bg-slate-750 transition-all focus:outline-none active:scale-[0.98]">
                            Stop
                        </button>
                    </div>

                    <p class="text-[11px] font-semibold text-slate-455 dark:text-slate-500 leading-relaxed">
                        Jika kamera/browser Anda tidak mendukung scanner otomatis, gunakan input manual token di sebelah kanan.
                    </p>
                </div>

                {{-- Manual Payload Column --}}
                <div class="space-y-4">
                    <div>
                        <label for="qr_payload" class="mb-1.5 block text-xs font-bold text-slate-500 dark:text-slate-455 uppercase tracking-wider">Manual QR Payload</label>
                        <textarea id="qr_payload" rows="5" class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-800 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 placeholder-slate-400" placeholder="Tempel payload QR di sini..."></textarea>
                    </div>

                    <button id="submitManual" class="inline-flex items-center justify-center rounded-full bg-emerald-600 px-5 py-2.5 text-xs font-bold text-white hover:bg-emerald-700 shadow-sm transition-all focus:outline-none focus:ring-4 focus:ring-emerald-500/20 active:scale-[0.98]">
                        Submit Presensi
                    </button>

                    <div id="scanResult" class="mt-4 hidden rounded-xl px-4 py-3 text-xs font-bold border"></div>
                </div>
            </div>
        @endif
    </div>

</div>

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
        resultBox.className = "mt-4 rounded-xl px-4 py-3 text-xs font-bold border transition-all";

        if (success) {
            resultBox.classList.add('bg-emerald-50/50', 'text-emerald-700', 'border-emerald-100', 'dark:bg-emerald-950/20', 'dark:text-emerald-400', 'dark:border-emerald-900/50');
        } else {
            resultBox.classList.add('bg-rose-50/50', 'text-rose-700', 'border-rose-100', 'dark:bg-rose-950/20', 'dark:text-rose-400', 'dark:border-rose-900/50');
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
            showResult(`${record.student_name} — ${record.status.toUpperCase()} — IN: ${record.check_in_at || '-'} OUT: ${record.check_out_at || '-'}`, true);
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

        try {
            stream = await navigator.mediaDevices.getUserMedia({
                video: { facingMode: 'environment' },
                audio: false,
            });

            video.srcObject = stream;
            scanning = true;

            requestAnimationFrame(scanLoop);
        } catch (err) {
            showResult('Gagal mengakses kamera.', false);
        }
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
@endsection

