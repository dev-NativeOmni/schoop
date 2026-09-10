<?php

namespace App\Services\Tahfizh;

use App\Models\HafalanRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;

class HafalanRecordService
{
    public function __construct(
        private readonly LineRangeCalculator $lineRangeCalculator,
        private readonly HafalanSequenceGuard $sequenceGuard,
    ) {
    }

    public function createRecord(array $data, int $userId, bool $isTeacher): HafalanRecord
    {
        $totalLines = $this->calculateTotalLines($data);
        $sequence = $this->validateSequence($data);

        return DB::transaction(function () use ($data, $userId, $isTeacher, $totalLines, $sequence): HafalanRecord {
            $teacherId = $isTeacher ? $userId : ($data['teacher_id'] ?? null);

            return HafalanRecord::query()->create([
                'school_id' => $data['school_id'],
                'student_id' => $data['student_id'],
                'teacher_id' => $teacherId,
                'tahfizh_target_id' => $data['tahfizh_target_id'] ?? null,
                'record_date' => $data['record_date'],

                'start_surah_id' => $data['start_surah_id'] ?? null,
                'start_ayah' => $data['start_ayah'] ?? null,
                'end_surah_id' => $data['end_surah_id'] ?? null,
                'end_ayah' => $data['end_ayah'] ?? null,

                'start_page' => $data['start_page'],
                'start_line' => $data['start_line'],
                'end_page' => $data['end_page'],
                'end_line' => $data['end_line'],

                'total_lines' => $totalLines,
                'status' => $data['status'],
                'quality_score' => $data['quality_score'] ?? null,
                'notes' => $data['notes'] ?? null,

                'is_sequence_valid' => true,
                'sequence_note' => $sequence['note'],

                'created_by' => $userId,
                'updated_by' => null,
            ]);
        });
    }

    public function updateRecord(HafalanRecord $record, array $data, int $userId, bool $isTeacher): void
    {
        $totalLines = $this->calculateTotalLines($data);
        $sequence = $this->validateSequence($data, $record->id);

        DB::transaction(function () use ($record, $data, $userId, $isTeacher, $totalLines, $sequence): void {
            $teacherId = $isTeacher ? $userId : ($data['teacher_id'] ?? null);

            $record->update([
                'school_id' => $data['school_id'],
                'student_id' => $data['student_id'],
                'teacher_id' => $teacherId,
                'tahfizh_target_id' => $data['tahfizh_target_id'] ?? null,
                'record_date' => $data['record_date'],

                'start_surah_id' => $data['start_surah_id'] ?? null,
                'start_ayah' => $data['start_ayah'] ?? null,
                'end_surah_id' => $data['end_surah_id'] ?? null,
                'end_ayah' => $data['end_ayah'] ?? null,

                'start_page' => $data['start_page'],
                'start_line' => $data['start_line'],
                'end_page' => $data['end_page'],
                'end_line' => $data['end_line'],

                'total_lines' => $totalLines,
                'status' => $data['status'],
                'quality_score' => $data['quality_score'] ?? null,
                'notes' => $data['notes'] ?? null,

                'is_sequence_valid' => true,
                'sequence_note' => $sequence['note'],

                'updated_by' => $userId,
            ]);
        });
    }

    private function calculateTotalLines(array $data): int
    {
        try {
            return $this->lineRangeCalculator->calculate(
                (int) $data['start_page'],
                (int) $data['start_line'],
                (int) $data['end_page'],
                (int) $data['end_line'],
            );
        } catch (InvalidArgumentException $exception) {
            throw ValidationException::withMessages([
                'start_page' => $exception->getMessage(),
            ]);
        }
    }

    private function validateSequence(array $data, ?int $ignoreRecordId = null): array
    {
        $sequence = $this->sequenceGuard->validate(
            studentId: (int) $data['student_id'],
            recordDate: $data['record_date'],
            startPage: (int) $data['start_page'],
            startLine: (int) $data['start_line'],
            ignoreRecordId: $ignoreRecordId,
        );

        if (! $sequence['valid']) {
            throw ValidationException::withMessages([
                'start_page' => $sequence['note'],
            ]);
        }

        return $sequence;
    }
}
