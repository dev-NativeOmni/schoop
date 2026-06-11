<?php

namespace App\Services\Reports;

use Illuminate\Support\Carbon;
use InvalidArgumentException;

class ReportPeriodResolver
{
    public function custom(?string $dateFrom, ?string $dateUntil): array
    {
        $start = $dateFrom
            ? Carbon::parse($dateFrom)->startOfDay()
            : now()->startOfMonth();

        $end = $dateUntil
            ? Carbon::parse($dateUntil)->endOfDay()
            : now()->endOfDay();

        if ($end->lt($start)) {
            throw new InvalidArgumentException('Tanggal akhir tidak boleh lebih awal dari tanggal mulai.');
        }

        return [$start, $end];
    }

    public function month(?string $month): array
    {
        $date = $month
            ? Carbon::createFromFormat('Y-m', $month)->startOfMonth()
            : now()->startOfMonth();

        return [
            $date->copy()->startOfMonth(),
            $date->copy()->endOfMonth(),
        ];
    }

    public function quarter(?int $year, ?int $quarter): array
    {
        $year = $year ?: (int) now()->format('Y');
        $quarter = $quarter ?: (int) ceil(now()->month / 3);

        if (! in_array($quarter, [1, 2, 3, 4], true)) {
            throw new InvalidArgumentException('Triwulan harus bernilai 1 sampai 4.');
        }

        $startMonth = (($quarter - 1) * 3) + 1;

        $start = Carbon::create($year, $startMonth, 1)->startOfMonth();
        $end = $start->copy()->addMonths(2)->endOfMonth();

        return [$start, $end];
    }

    public function quarterLabel(?int $year, ?int $quarter): string
    {
        $year = $year ?: (int) now()->format('Y');
        $quarter = $quarter ?: (int) ceil(now()->month / 3);

        return "Triwulan {$quarter} Tahun {$year}";
    }
}
