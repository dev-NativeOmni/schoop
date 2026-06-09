<?php

namespace App\Services\Tahfizh;

use InvalidArgumentException;

class LineRangeCalculator
{
    public const MIN_PAGE = 1;
    public const MAX_PAGE = 604;
    public const MIN_LINE = 1;
    public const MAX_LINE = 15;

    public function calculate(
        int $startPage,
        int $startLine,
        int $endPage,
        int $endLine
    ): int {
        $this->validateRange($startPage, $startLine, $endPage, $endLine);

        if ($startPage === $endPage) {
            return ($endLine - $startLine) + 1;
        }

        $firstPageLines = self::MAX_LINE - $startLine + 1;
        $middlePages = max(0, $endPage - $startPage - 1);
        $middleLines = $middlePages * self::MAX_LINE;
        $lastPageLines = $endLine;

        return $firstPageLines + $middleLines + $lastPageLines;
    }

    public function validateRange(
        int $startPage,
        int $startLine,
        int $endPage,
        int $endLine
    ): void {
        if ($startPage < self::MIN_PAGE || $startPage > self::MAX_PAGE) {
            throw new InvalidArgumentException('Halaman awal harus antara 1 sampai 604.');
        }

        if ($endPage < self::MIN_PAGE || $endPage > self::MAX_PAGE) {
            throw new InvalidArgumentException('Halaman akhir harus antara 1 sampai 604.');
        }

        if ($startLine < self::MIN_LINE || $startLine > self::MAX_LINE) {
            throw new InvalidArgumentException('Baris awal harus antara 1 sampai 15.');
        }

        if ($endLine < self::MIN_LINE || $endLine > self::MAX_LINE) {
            throw new InvalidArgumentException('Baris akhir harus antara 1 sampai 15.');
        }

        if ($endPage < $startPage) {
            throw new InvalidArgumentException('Halaman akhir tidak boleh lebih kecil dari halaman awal.');
        }

        if ($startPage === $endPage && $endLine < $startLine) {
            throw new InvalidArgumentException('Baris akhir tidak boleh lebih kecil dari baris awal pada halaman yang sama.');
        }
    }
}
