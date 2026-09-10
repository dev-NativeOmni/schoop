<?php

namespace App\Services\Mutabaah;

use App\Models\MutabaahActivity;
use App\Models\MutabaahRecord;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class MutabaahRecordService
{
    public function saveDailyRecords(
        Student $student,
        string $recordDate,
        array $records,
        User $submittedBy,
        string $source
    ): Collection {
        return collect($records)->map(function (array $item) use ($student, $recordDate, $submittedBy, $source): MutabaahRecord {
            $activity = MutabaahActivity::query()
                ->whereKey($item['mutabaah_activity_id'] ?? null)
                ->where('is_active', true)
                ->first();

            if (! $activity) {
                throw ValidationException::withMessages([
                    'records' => 'Aktivitas mutabaah tidak ditemukan atau tidak aktif.',
                ]);
            }

            $this->validateInputByType($activity, $item);

            return MutabaahRecord::query()->updateOrCreate(
                [
                    'student_id' => $student->id,
                    'mutabaah_activity_id' => $activity->id,
                    'record_date' => $recordDate,
                ],
                [
                    'school_id' => $student->school_id ?? null,
                    'status' => $item['status'] ?? 'not_done',
                    'score' => $item['score'] ?? null,
                    'count_value' => $item['count_value'] ?? null,
                    'text_value' => $item['text_value'] ?? null,
                    'note' => $item['note'] ?? null,
                    'submitted_by' => $submittedBy->id,
                    'source' => $source,
                ]
            );
        });
    }

    private function validateInputByType(MutabaahActivity $activity, array $item): void
    {
        $status = $item['status'] ?? 'not_done';

        if (! in_array($status, ['done', 'not_done', 'excused'], true)) {
            throw ValidationException::withMessages([
                'records' => 'Status mutabaah tidak valid.',
            ]);
        }

        if ($activity->input_type === 'score') {
            $score = $item['score'] ?? null;

            if ($score !== null && ((int) $score < 0 || (int) $score > 100)) {
                throw ValidationException::withMessages([
                    'records' => 'Skor mutabaah harus berada di antara 0 sampai 100.',
                ]);
            }
        }

        if ($activity->input_type === 'count') {
            $countValue = $item['count_value'] ?? null;

            if ($countValue !== null && (int) $countValue < 0) {
                throw ValidationException::withMessages([
                    'records' => 'Jumlah mutabaah tidak boleh negatif.',
                ]);
            }
        }
    }
}
