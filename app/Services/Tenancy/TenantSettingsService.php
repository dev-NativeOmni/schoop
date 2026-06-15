<?php

namespace App\Services\Tenancy;

use App\Models\TenantSetting;
use Illuminate\Support\Facades\DB;

class TenantSettingsService
{
    protected TenantAuditLogger $logger;

    public function __construct(TenantAuditLogger $logger)
    {
        $this->logger = $logger;
    }

    public function getSetting(int $schoolId, string $key, $default = null)
    {
        $setting = TenantSetting::query()
            ->where('school_id', $schoolId)
            ->where('setting_key', $key)
            ->first();

        if (!$setting) {
            return $default;
        }

        return $this->castValue($setting->setting_value, $setting->value_type);
    }

    public function setSetting(int $schoolId, string $key, $value, string $type = 'string', bool $isPublic = false, ?string $description = null): TenantSetting
    {
        return DB::transaction(function () use ($schoolId, $key, $value, $type, $isPublic, $description) {
            $stringValue = is_array($value) || is_object($value) ? json_encode($value) : (string) $value;

            $setting = TenantSetting::query()
                ->where('school_id', $schoolId)
                ->where('setting_key', $key)
                ->first();

            $oldValues = $setting ? $setting->toArray() : [];

            $setting = TenantSetting::query()->updateOrCreate(
                [
                    'school_id' => $schoolId,
                    'setting_key' => $key,
                ],
                [
                    'setting_value' => $stringValue,
                    'value_type' => $type,
                    'is_public' => $isPublic,
                    'description' => $description,
                ]
            );

            $this->logger->log('set_tenant_setting', $setting, $oldValues, $setting->toArray());

            return $setting;
        });
    }

    protected function castValue(?string $value, string $type)
    {
        if (is_null($value)) {
            return null;
        }

        return match ($type) {
            'boolean', 'bool' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'integer', 'int' => (int) $value,
            'float', 'double' => (float) $value,
            'array', 'json' => json_decode($value, true),
            default => $value,
        };
    }
}
