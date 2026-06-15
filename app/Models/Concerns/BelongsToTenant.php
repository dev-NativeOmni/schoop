<?php

namespace App\Models\Concerns;

use App\Services\Tenancy\TenantContextService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait BelongsToTenant
{
    protected static function bootBelongsToTenant(): void
    {
        static::creating(function (Model $model): void {
            if (! $model->getAttribute('school_id')) {
                $schoolId = app(TenantContextService::class)->activeSchoolId();

                if ($schoolId) {
                    $model->setAttribute('school_id', $schoolId);
                }
            }
        });

        static::addGlobalScope('school', function (Builder $builder): void {
            $schoolId = app(TenantContextService::class)->activeSchoolId();
            if ($schoolId) {
                $builder->where($builder->getModel()->getTable() . '.school_id', $schoolId);
            }
        });
    }

    public function scopeForActiveTenant(Builder $query): Builder
    {
        $schoolId = app(TenantContextService::class)->activeSchoolId();

        if (! $schoolId) {
            return $query;
        }

        return $query->where($query->getModel()->getTable() . '.school_id', $schoolId);
    }

    public function scopeForTenant(Builder $query, int $schoolId): Builder
    {
        return $query->where($query->getModel()->getTable() . '.school_id', $schoolId);
    }
}
