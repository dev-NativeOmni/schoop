<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Cetak QR Presensi Santri</title>
    <style>
        body {
            font-family: system-ui, -apple-system, sans-serif;
            color: #1f2937;
            background-color: #f9fafb;
            margin: 0;
            padding: 24px;
        }

        .no-print {
            margin-bottom: 24px;
            display: flex;
            justify-content: flex-end;
        }

        .btn-print {
            background-color: #4f46e5;
            color: white;
            font-size: 14px;
            font-weight: 700;
            padding: 10px 20px;
            border-radius: 9999px;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.2);
            transition: all 0.2s ease;
        }

        .btn-print:hover {
            background-color: #4338ca;
            transform: translateY(-1px);
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .card {
            background-color: white;
            border: 2px solid #e5e7eb;
            border-radius: 16px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            page-break-inside: avoid;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .qr-wrapper {
            background-color: #f3f4f6;
            padding: 12px;
            border-radius: 12px;
            display: inline-block;
        }

        .name {
            font-weight: 800;
            font-size: 15px;
            color: #111827;
            margin-top: 14px;
            text-transform: capitalize;
        }

        .meta {
            font-size: 11px;
            font-weight: 600;
            color: #6b7280;
            margin-top: 6px;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        @media print {
            body {
                background-color: white;
                padding: 0;
            }

            .no-print {
                display: none;
            }

            .grid {
                gap: 16px;
            }

            .card {
                border: 1px solid #d1d5db;
                box-shadow: none;
            }
        }
    </style>
</head>
<body>
    <div class="no-print">
        <button onclick="window.print()" class="btn-print">Cetak Sekarang</button>
    </div>

    <div class="grid">
        @foreach($qrCards as $card)
            <div class="card">
                <div class="qr-wrapper">
                    {!! $card['svg'] !!}
                </div>
                <div class="name">
                    {{ $card['student']->full_name ?? $card['student']->nama_lengkap ?? $card['student']->name ?? 'Santri #' . $card['student']->id }}
                </div>
                <div class="meta">
                    Schoop · QR Attendance
                </div>
            </div>
        @endforeach
    </div>
</body>
</html>
