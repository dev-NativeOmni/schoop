<?php

namespace App\Exports\Tahfizh;

use Carbon\CarbonInterface;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class DashboardTahfizhSummaryExport implements FromCollection, WithHeadings, ShouldAutoSize, WithTitle
{
    public function __construct(
        private readonly array $summary,
        private readonly CarbonInterface $periodStart,
        private readonly CarbonInterface $periodEnd,
    ) {
        //
    }

    public function collection(): Collection
    {
        return collect([
            ['Total Santri', $this->summary['total_students'] ?? 0],
            ['Total Setoran', $this->summary['total_records'] ?? 0],
            ['Total Baris', $this->summary['total_lines'] ?? 0],
            ['Total Hutang Baris', $this->summary['total_debt_lines'] ?? 0],
            ['Total Lebih Baris', $this->summary['total_surplus_lines'] ?? 0],
            ['Total Akumulasi Hutang', $this->summary['total_cumulative_debt_lines'] ?? 0],
            ['Santri Tertinggal', $this->summary['behind_count'] ?? 0],
            ['Santri Tercapai', $this->summary['met_count'] ?? 0],
            ['Santri Lebih Target', $this->summary['ahead_count'] ?? 0],
            ['Santri Tanpa Target', $this->summary['no_target_count'] ?? 0],
            ['Periode Mulai', $this->periodStart->format('Y-m-d')],
            ['Periode Akhir', $this->periodEnd->format('Y-m-d')],
        ]);
    }

    public function headings(): array
    {
        return [
            'Metrik',
            'Nilai',
        ];
    }

    public function title(): string
    {
        return 'Dashboard Tahfizh';
    }
}
