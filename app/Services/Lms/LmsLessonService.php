<?php

namespace App\Services\Lms;

use App\Models\LmsLesson;
use App\Services\Tenancy\TenantContextService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LmsLessonService
{
    public function __construct(
        private readonly TenantContextService $tenantContext,
        private readonly LmsActivityLogger $logger,
    ) {
        //
    }

    public function createLesson(array $data, int $userId): LmsLesson
    {
        return DB::transaction(function () use ($data, $userId) {
            $schoolId = $this->tenantContext->activeSchoolId();

            $data['school_id'] = $schoolId;
            $data['created_by'] = $userId;

            if (empty($data['slug'])) {
                $baseSlug = Str::slug($data['title']);
                $slug = $baseSlug;
                $counter = 1;
                while (LmsLesson::where('school_id', $schoolId)->where('slug', $slug)->exists()) {
                    $slug = $baseSlug.'-'.$counter;
                    $counter++;
                }
                $data['slug'] = $slug;
            }

            if (! isset($data['sort_order'])) {
                $maxSort = LmsLesson::where('module_id', $data['module_id'])->max('sort_order');
                $data['sort_order'] = $maxSort !== null ? $maxSort + 1 : 1;
            }

            $lesson = LmsLesson::create($data);
            $this->logger->log('lesson_create', "Lesson '{$lesson->title}' created.", $lesson);

            return $lesson;
        });
    }

    public function updateLesson(LmsLesson $lesson, array $data): LmsLesson
    {
        return DB::transaction(function () use ($lesson, $data) {
            $schoolId = $lesson->school_id;

            if (isset($data['title']) && $data['title'] !== $lesson->title && empty($data['slug'])) {
                $baseSlug = Str::slug($data['title']);
                $slug = $baseSlug;
                $counter = 1;
                while (LmsLesson::where('school_id', $schoolId)->where('slug', $slug)->where('id', '!=', $lesson->id)->exists()) {
                    $slug = $baseSlug.'-'.$counter;
                    $counter++;
                }
                $data['slug'] = $slug;
            }

            $lesson->update($data);
            $this->logger->log('lesson_update', "Lesson '{$lesson->title}' updated.", $lesson);

            return $lesson;
        });
    }

    public function deleteLesson(LmsLesson $lesson): void
    {
        DB::transaction(function () use ($lesson) {
            $lesson->delete();
            $this->logger->log('lesson_delete', "Lesson '{$lesson->title}' deleted.", $lesson);
        });
    }

    public function reorderLessons(array $lessonIds): void
    {
        DB::transaction(function () use ($lessonIds) {
            foreach ($lessonIds as $index => $id) {
                LmsLesson::where('id', $id)->update(['sort_order' => $index + 1]);
            }
        });
    }
}
