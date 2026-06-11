<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Ringkasan Dashboard Tahfizh</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #111827;
        }

        .header {
            margin-bottom: 18px;
            border-bottom: 2px solid #111827;
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
            padding: 8px;
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
        <h1>Ringkasan Dashboard Tahfizh</h1>
        <div class="meta">
            Periode: {{ $periodStart->format('d/m/Y') }} - {{ $periodEnd->format('d/m/Y') }}<br>
            Dicetak oleh: {{ $generatedBy->name }}<br>
            Waktu cetak: {{ $generatedAt->format('d/m/Y H:i') }}
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Metrik</th>
                <th>Nilai</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Total Santri</td>
                <td class="text-right">{{ $summary['total_students'] ?? 0 }}</td>
            </tr>
            <tr>
                <td>Total Setoran</td>
                <td class="text-right">{{ $summary['total_records'] ?? 0 }}</td>
            </tr>
            <tr>
                <td>Total Baris</td>
                <td class="text-right">{{ $summary['total_lines'] ?? 0 }}</td>
            </tr>
            <tr>
                <td>Total Hutang Baris</td>
                <td class="text-right">{{ $summary['total_debt_lines'] ?? 0 }}</td>
            </tr>
            <tr>
                <td>Total Lebih Baris</td>
                <td class="text-right">{{ $summary['total_surplus_lines'] ?? 0 }}</td>
            </tr>
            <tr>
                <td>Total Akumulasi Hutang</td>
                <td class="text-right">{{ $summary['total_cumulative_debt_lines'] ?? 0 }}</td>
            </tr>
            <tr>
                <td>Santri Tertinggal</td>
                <td class="text-right">{{ $summary['behind_count'] ?? 0 }}</td>
            </tr>
            <tr>
                <td>Santri Tercapai</td>
                <td class="text-right">{{ $summary['met_count'] ?? 0 }}</td>
            </tr>
            <tr>
                <td>Santri Lebih Target</td>
                <td class="text-right">{{ $summary['ahead_count'] ?? 0 }}</td>
            </tr>
            <tr>
                <td>Santri Tanpa Target</td>
                <td class="text-right">{{ $summary['no_target_count'] ?? 0 }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        Dokumen ini dihasilkan otomatis oleh HafizPlus School Platform.
    </div>
</body>
</html>
