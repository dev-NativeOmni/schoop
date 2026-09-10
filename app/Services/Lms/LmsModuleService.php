<?php

namespace App\Services\Lms;

use App\Models\LmsCourseModule;
use App\Services\Tenancy\TenantContextService;
use Illuminate\Support\Facades\DB;

class LmsModuleService
{
    public function __construct(
        private readonly TenantContextService $tenantContext,
        private readonly LmsActivityLogger $logger,
    ) {
        //
    }

    public function createModule(array $data): LmsCourseModule
    {
        return DB::transaction(function () use ($data) {
            $schoolId = $this->tenantContext->activeSchoolId();
            $data['school_id'] = $schoolId;

            if (! isset($data['sort_order'])) {
                $maxSort = LmsCourseModule::where('course_id', $data['course_id'])->max('sort_order');
                $data['sort_order'] = $maxSort !== null ? $maxSort + 1 : 1;
            }

            $module = LmsCourseModule::create($data);
            $this->logger->log('module_create', "Module '{$module->title}' created.", $module);

            return $module;
        });
    }

    public function updateModule(LmsCourseModule $module, array $data): LmsCourseModule
    {
        return DB::transaction(function () use ($module, $data) {
            $module->update($data);
            $this->logger->log('module_update', "Module '{$module->title}' updated.", $module);

            return $module;
        });
    }

    public function deleteModule(LmsCourseModule $module): void
    {
        DB::transaction(function () use ($module) {
            $module->delete();
            $this->logger->log('module_delete', "Module '{$module->title}' deleted.", $module);
        });
    }

    public function reorderModules(array $moduleIds): void
    {
        DB::transaction(function () use ($moduleIds) {
            foreach ($moduleIds as $index => $id) {
                LmsCourseModule::where('id', $id)->update(['sort_order' => $index + 1]);
            }
        });
    }
}
