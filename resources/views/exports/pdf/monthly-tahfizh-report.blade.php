<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Tahfizh Bulanan</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #111827;
        }

        h1, h2, h3 {
            margin: 0;
            padding: 0;
        }

        .header {
            margin-bottom: 18px;
            border-bottom: 2px solid {{ $pdfBrand['primary_color'] ?? '#111827' }};
            padding-bottom: 10px;
        }

        .meta {
            margin-top: 6px;
            color: #4b5563;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #f3f4f6;
            font-weight: bold;
        }

        th, td {
            border: 1px solid #d1d5db;
            padding: 6px;
            vertical-align: top;
        }

        .text-right {
            text-align: right;
        }

        .footer {
            margin-top: 16px;
            font-size: 9px;
            color: #6b7280;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $pdfBrand['name'] ?? 'Schoop School Platform' }}</h1>
        <strong>Laporan Tahfizh Bulanan</strong><br>
        <span>{{ $pdfBrand['tagline'] ?? 'Tahfizh Monitoring App' }}</span>
        <div class="meta">
            Periode: {{ $periodStart->format('d/m/Y') }} - {{ $periodEnd->format('d/m/Y') }}<br>
            Dicetak oleh: {{ $generatedBy->name }}<br>
            Waktu cetak: {{ $generatedAt->format('d/m/Y H:i') }}
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Santri</th>
                <th>Kelas</th>
                <th>Setoran</th>
                <th>Total Baris</th>
                <th>Target</th>
                <th>Hutang</th>
                <th>Lebih</th>
                <th>Akumulasi</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($rows as $row)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $row['student']?->full_name ?? '-' }}</td>
                    <td>{{ $row['class_room']?->name ?? '-' }}</td>
                    <td class="text-right">{{ $row['record_count'] ?? 0 }}</td>
                    <td class="text-right">{{ $row['actual_lines'] ?? 0 }}</td>
                    <td class="text-right">{{ $row['target_lines'] ?? 0 }}</td>
                    <td class="text-right">{{ $row['debt_lines'] ?? 0 }}</td>
                    <td class="text-right">{{ $row['surplus_lines'] ?? 0 }}</td>
                    <td class="text-right">{{ $row['cumulative_debt_lines'] ?? 0 }}</td>
                    <td>{{ $row['status'] ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10">Tidak ada data.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dokumen ini dihasilkan otomatis oleh {{ $pdfBrand['name'] ?? 'Schoop School Platform' }}.
    </div>
</body>
</html>
