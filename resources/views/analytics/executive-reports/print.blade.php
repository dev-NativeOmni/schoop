<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $report->title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.5;
            padding: 40px;
        }
        h1 {
            font-size: 24px;
            margin-bottom: 5px;
        }
        h2 {
            font-size: 18px;
            border-bottom: 2px solid #333;
            padding-bottom: 5px;
            margin-top: 30px;
        }
        .meta {
            font-size: 12px;
            color: #666;
            margin-bottom: 30px;
        }
        .section {
            margin-bottom: 25px;
        }
        .highlight-box {
            background-color: #f9f9f9;
            border-left: 4px solid #333;
            padding: 15px;
            margin-bottom: 20px;
        }
        ul {
            padding-left: 20px;
        }
        @media print {
            body {
                padding: 0;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 8px 15px; font-weight: bold; cursor: pointer;">Cetak Laporan</button>
    </div>

    <h1>{{ $report->title }}</h1>
    <div class="meta">
        Periode: {{ $report->period_start->toDateString() }} s/d {{ $report->period_end->toDateString() }} | 
        Tenant: {{ $report->school->name ?? 'Internal HafizPlus' }} | 
        Dibuat oleh: {{ $report->user->name ?? 'System' }} pada {{ $report->created_at->toDateString() }}
    </div>

    <div class="section">
        <h2>Ringkasan Eksekutif</h2>
        <p>{{ $report->summary }}</p>
    </div>

    <div class="highlight-box">
        <h3>Highlights & Poin Utama</h3>
        <ul>
            @foreach($report->highlights ?: [] as $hl)
                <li>{{ $hl }}</li>
            @endforeach
        </ul>
    </div>

    @if(!empty($report->risks))
        <div class="section">
            <h2>Pola Risiko & Isu Terdeteksi</h2>
            <ul>
                @foreach($report->risks as $r)
                    <li>{{ $r }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @foreach($report->sections as $sec)
        <div class="section">
            <h2>{{ $sec->title }}</h2>
            <p>{{ $sec->content }}</p>
        </div>
    @endforeach

    <div class="section">
        <h2>Rekomendasi Tindakan</h2>
        <ul>
            @foreach($report->recommendations ?: [] as $rec)
                <li>{{ $rec }}</li>
            @endforeach
        </ul>
    </div>
</body>
</html>
