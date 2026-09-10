<?php

namespace App\Services\Tahfizh;

use App\Models\HafalanRecord;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;

class HafalanRecordService
{
    public function __construct(
        private readonly LineRangeCalculator $lineRangeCalculator,
        private readonly HafalanSequenceGuard $sequenceGuard,
    ) {}

    public function createRecord(array $payload, User $creator): HafalanRecord
    {
        try {
            $totalLines = $this->lineRangeCalculator->calculate(
                (int) $payload['start_page'],
                (int) $payload['start_line'],
                (int) $payload['end_page'],
                (int) $payload['end_line'],
            );
        } catch (InvalidArgumentException $exception) {
            throw ValidationException::withMessages([
                'start_page' => $exception->getMessage(),
            ]);
        }

        $sequence = $this->sequenceGuard->validate(
            studentId: (int) $payload['student_id'],
            recordDate: $payload['record_date'],
            startPage: (int) $payload['start_page'],
            startLine: (int) $payload['start_line'],
        );

        if (! $sequence['valid']) {
            throw ValidationException::withMessages([
                'start_page' => $sequence['note'],
            ]);
        }

        return DB::transaction(function () use ($payload, $creator, $totalLines, $sequence): HafalanRecord {
            $teacherId = $creator->hasRole('teacher')
                ? $creator->id
                : ($payload['teacher_id'] ?? $creator->id);

            return HafalanRecord::query()->create([
                'school_id' => $payload['school_id'],
                'student_id' => $payload['student_id'],
                'teacher_id' => $teacherId,
                'tahfizh_target_id' => $payload['tahfizh_target_id'] ?? null,
                'record_date' => $payload['record_date'],
                'start_surah_id' => $payload['start_surah_id'] ?? null,
                'start_ayah' => $payload['start_ayah'] ?? null,
                'end_surah_id' => $payload['end_surah_id'] ?? null,
                'end_ayah' => $payload['end_ayah'] ?? null,
                'start_page' => $payload['start_page'],
                'start_line' => $payload['start_line'],
                'end_page' => $payload['end_page'],
                'end_line' => $payload['end_line'],
                'total_lines' => $totalLines,
                'status' => $payload['status'],
                'quality_score' => $payload['quality_score'] ?? null,
                'notes' => $payload['notes'] ?? null,
                'is_sequence_valid' => true,
                'sequence_note' => $sequence['note'],
                'created_by' => $creator->id,
                'updated_by' => null,
            ]);
        });
    }

    public function updateRecord(HafalanRecord $hafalanRecord, array $payload, User $updater): HafalanRecord
    {
        try {
            $totalLines = $this->lineRangeCalculator->calculate(
                (int) $payload['start_page'],
                (int) $payload['start_line'],
                (int) $payload['end_page'],
                (int) $payload['end_line'],
            );
        } catch (InvalidArgumentException $exception) {
            throw ValidationException::withMessages([
                'start_page' => $exception->getMessage(),
            ]);
        }

        $sequence = $this->sequenceGuard->validate(
            studentId: (int) $payload['student_id'],
            recordDate: $payload['record_date'],
            startPage: (int) $payload['start_page'],
            startLine: (int) $payload['start_line'],
            ignoreRecordId: $hafalanRecord->id,
        );

        if (! $sequence['valid']) {
            throw ValidationException::withMessages([
                'start_page' => $sequence['note'],
            ]);
        }

        return DB::transaction(function () use ($payload, $updater, $hafalanRecord, $totalLines, $sequence): HafalanRecord {
            $teacherId = $updater->hasRole('teacher')
                ? $updater->id
                : ($payload['teacher_id'] ?? $updater->id);

            $hafalanRecord->update([
                'school_id' => $payload['school_id'],
                'student_id' => $payload['student_id'],
                'teacher_id' => $teacherId,
                'tahfizh_target_id' => $payload['tahfizh_target_id'] ?? null,
                'record_date' => $payload['record_date'],
                'start_surah_id' => $payload['start_surah_id'] ?? null,
                'start_ayah' => $payload['start_ayah'] ?? null,
                'end_surah_id' => $payload['end_surah_id'] ?? null,
                'end_ayah' => $payload['end_ayah'] ?? null,
                'start_page' => $payload['start_page'],
                'start_line' => $payload['start_line'],
                'end_page' => $payload['end_page'],
                'end_line' => $payload['end_line'],
                'total_lines' => $totalLines,
                'status' => $payload['status'],
                'quality_score' => $payload['quality_score'] ?? null,
                'notes' => $payload['notes'] ?? null,
                'is_sequence_valid' => true,
                'sequence_note' => $sequence['note'],
                'updated_by' => $updater->id,
            ]);

            return $hafalanRecord;
        });
    }
}
