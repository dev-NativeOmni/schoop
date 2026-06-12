<?php

namespace App\Services\SchoolOs;

use App\Models\AcademicYear;
use App\Models\SchoolSetting;
use App\Models\SchoolTerm;

class SchoolContextService
{
    public function activeAcademicYear(?int $schoolId = null): ?AcademicYear
    {
        return AcademicYear::query()
            ->where('school_id', $schoolId)
            ->where('is_active', true)
            ->first();
    }

    public function activeTerm(?int $schoolId = null): ?SchoolTerm
    {
        return SchoolTerm::query()
            ->where('school_id', $schoolId)
            ->where('is_active', true)
            ->first();
    }

    public function setting(string $key, mixed $default = null, ?int $schoolId = null): mixed
    {
        $setting = SchoolSetting::query()
            ->where('school_id', $schoolId)
            ->where('key', $key)
            ->first();

        return $setting?->value ?? $default;
    }
}
