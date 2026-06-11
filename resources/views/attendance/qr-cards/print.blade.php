<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Cetak QR Presensi Santri</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #111827;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
        }

        .card {
            border: 1px solid #d1d5db;
            border-radius: 12px;
            padding: 16px;
            text-align: center;
            page-break-inside: avoid;
        }

        .name {
            font-weight: 700;
            font-size: 14px;
            margin-top: 8px;
        }

        .meta {
            font-size: 11px;
            color: #6b7280;
            margin-top: 4px;
        }

        @media print {
            .no-print {
                display: none;
            }

            body {
                margin: 0;
            }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 16px;">
        <button onclick="window.print()">Cetak</button>
    </div>

    <div class="grid">
        @foreach($qrCards as $card)
            <div class="card">
                <div>{!! $card['svg'] !!}</div>
                <div class="name">
                    {{ $card['student']->full_name ?? $card['student']->nama_lengkap ?? $card['student']->name ?? 'Santri #' . $card['student']->id }}
                </div>
                <div class="meta">
                    HafizPlus School Platform — QR Attendance
                </div>
            </div>
        @endforeach
    </div>
</body>
</html>
