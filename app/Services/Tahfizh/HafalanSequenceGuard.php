<?php

namespace App\Services\Tahfizh;

use App\Models\HafalanRecord;
use Carbon\CarbonInterface;

class HafalanSequenceGuard
{
    public function validate(
        int $studentId,
        CarbonInterface|string $recordDate,
        int $startPage,
        int $startLine,
        ?int $ignoreRecordId = null
    ): array {
        $previousRecord = HafalanRecord::query()
            ->where('student_id', $studentId)
            ->when($ignoreRecordId, function ($query) use ($ignoreRecordId): void {
                $query->where('id', '!=', $ignoreRecordId);
            })
            ->whereDate('record_date', '<=', $recordDate)
            ->orderByDesc('record_date')
            ->orderByDesc('id')
            ->first();

        if (! $previousRecord) {
            return [
                'valid' => true,
                'note' => 'Setoran pertama santri. Sequential guard mengizinkan titik awal ini.',
                'expected_page' => null,
                'expected_line' => null,
            ];
        }

        [$expectedPage, $expectedLine] = $this->nextPosition(
            $previousRecord->end_page,
            $previousRecord->end_line
        );

        $isValid = $startPage === $expectedPage && $startLine === $expectedLine;

        return [
            'valid' => $isValid,
            'note' => $isValid
                ? 'Urutan setoran valid.'
                : "Setoran tidak urut. Posisi berikutnya seharusnya halaman {$expectedPage} baris {$expectedLine}.",
            'expected_page' => $expectedPage,
            'expected_line' => $expectedLine,
            'previous_record_id' => $previousRecord->id,
        ];
    }

    public function nextPosition(int $endPage, int $endLine): array
    {
        if ($endLine < LineRangeCalculator::MAX_LINE) {
            return [$endPage, $endLine + 1];
        }

        return [$endPage + 1, LineRangeCalculator::MIN_LINE];
    }
}
