<?php

namespace App\Services\Tahfizh;

use App\Models\Student;
use App\Models\TahfizhTarget;
use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;

class ActiveTahfizhTargetResolver
{
    public function resolveForStudent(Student $student, CarbonInterface|string $date): ?TahfizhTarget
    {
        $date = $date instanceof CarbonInterface
            ? Carbon::instance($date->toDateTime())
            : Carbon::parse($date);

        return TahfizhTarget::query()
            ->where('school_id', $student->school_id)
            ->where('is_active', true)
            ->where(function ($query) use ($date): void {
                $query
                    ->whereNull('effective_from')
                    ->orWhereDate('effective_from', '<=', $date);
            })
            ->where(function ($query) use ($date): void {
                $query
                    ->whereNull('effective_until')
                    ->orWhereDate('effective_until', '>=', $date);
            })
            ->where(function ($query) use ($student): void {
                $query
                    ->where('student_id', $student->id)
                    ->orWhere('class_room_id', $student->class_room_id)
                    ->orWhere('program_type', $student->program_type)
                    ->orWhere(function ($fallbackQuery): void {
                        $fallbackQuery
                            ->whereNull('student_id')
                            ->whereNull('class_room_id')
                            ->whereNull('program_type');
                    });
            })
            ->orderByRaw(
                "
                CASE
                    WHEN student_id = ? THEN 1
                    WHEN class_room_id = ? THEN 2
                    WHEN program_type = ? THEN 3
                    ELSE 4
                END
                ",
                [
                    $student->id,
                    $student->class_room_id,
                    $student->program_type,
                ]
            )
            ->latest('id')
            ->first();
    }
}
