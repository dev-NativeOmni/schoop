<?php

namespace App\Exports\Tahfizh;

use Carbon\CarbonInterface;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class QuarterlyTahfizhReportExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping, WithTitle
{
    public function __construct(
        private readonly Collection $rows,
        private readonly CarbonInterface $periodStart,
        private readonly CarbonInterface $periodEnd,
        private readonly string $periodLabel,
    ) {
        //
    }

    public function collection(): Collection
    {
        return $this->rows;
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Santri',
            'Kelas',
            'Jumlah Setoran',
            'Total Baris',
            'Target Baris',
            'Hutang Baris',
            'Lebih Baris',
            'Akumulasi Hutang',
            'Status',
            'Catatan',
            'Label Periode',
            'Periode Mulai',
            'Periode Akhir',
        ];
    }

    public function map($row): array
    {
        static $number = 0;
        $number++;

        return [
            $number,
            $row['student']?->full_name ?? '-',
            $row['class_room']?->name ?? '-',
            $row['record_count'] ?? 0,
            $row['actual_lines'] ?? 0,
            $row['target_lines'] ?? 0,
            $row['debt_lines'] ?? 0,
            $row['surplus_lines'] ?? 0,
            $row['cumulative_debt_lines'] ?? 0,
            $row['status'] ?? '-',
            $row['notes'] ?? '-',
            $this->periodLabel,
            $this->periodStart->format('Y-m-d'),
            $this->periodEnd->format('Y-m-d'),
        ];
    }

    public function title(): string
    {
        return 'Laporan Triwulan';
    }
}
