<?php

namespace App\Services\SchoolOs;

use App\Models\SystemModule;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;

class ModuleRegistryService
{
    public function enabledModules(?int $schoolId = null): Collection
    {
        return SystemModule::query()
            ->where('school_id', $schoolId)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get()
            ->map(function (SystemModule $module): SystemModule {
                $module->route_exists = $module->route_name
                    ? Route::has($module->route_name)
                    : false;

                return $module;
            });
    }

    public function allModules(?int $schoolId = null): Collection
    {
        return SystemModule::query()
            ->where('school_id', $schoolId)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get()
            ->map(function (SystemModule $module): SystemModule {
                $module->route_exists = $module->route_name
                    ? Route::has($module->route_name)
                    : false;

                return $module;
            });
    }
}
