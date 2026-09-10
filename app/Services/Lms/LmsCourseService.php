<?php

namespace App\Services\Lms;

use App\Models\LmsCourse;
use App\Models\LmsCourseInstructor;
use App\Services\Tenancy\TenantContextService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LmsCourseService
{
    public function __construct(
        private readonly TenantContextService $tenantContext,
        private readonly LmsActivityLogger $logger,
    ) {
        //
    }

    public function createCourse(array $data, int $userId): LmsCourse
    {
        return DB::transaction(function () use ($data, $userId) {
            $schoolId = $this->tenantContext->activeSchoolId();

            $data['school_id'] = $schoolId;
            $data['created_by'] = $userId;
            $data['updated_by'] = $userId;

            if (empty($data['slug'])) {
                $baseSlug = Str::slug($data['title']);
                $slug = $baseSlug;
                $counter = 1;
                while (LmsCourse::where('school_id', $schoolId)->where('slug', $slug)->exists()) {
                    $slug = $baseSlug.'-'.$counter;
                    $counter++;
                }
                $data['slug'] = $slug;
            }

            $course = LmsCourse::create($data);

            if (! empty($data['instructor_ids'])) {
                $this->assignInstructors($course, $data['instructor_ids'], $data['primary_instructor_id'] ?? null);
            }

            $this->logger->log('course_create', "Course '{$course->title}' created.", $course);

            return $course;
        });
    }

    public function updateCourse(LmsCourse $course, array $data, int $userId): LmsCourse
    {
        return DB::transaction(function () use ($course, $data, $userId) {
            $schoolId = $course->school_id;
            $data['updated_by'] = $userId;

            if (isset($data['title']) && $data['title'] !== $course->title && empty($data['slug'])) {
                $baseSlug = Str::slug($data['title']);
                $slug = $baseSlug;
                $counter = 1;
                while (LmsCourse::where('school_id', $schoolId)->where('slug', $slug)->where('id', '!=', $course->id)->exists()) {
                    $slug = $baseSlug.'-'.$counter;
                    $counter++;
                }
                $data['slug'] = $slug;
            }

            $course->update($data);

            if (isset($data['instructor_ids'])) {
                $this->assignInstructors($course, $data['instructor_ids'], $data['primary_instructor_id'] ?? null);
            }

            $this->logger->log('course_update', "Course '{$course->title}' updated.", $course);

            return $course;
        });
    }

    public function deleteCourse(LmsCourse $course): void
    {
        DB::transaction(function () use ($course) {
            $course->delete();
            $this->logger->log('course_delete', "Course '{$course->title}' deleted.", $course);
        });
    }

    public function assignInstructors(LmsCourse $course, array $teacherProfileIds, ?int $primaryTeacherProfileId = null): void
    {
        $schoolId = $course->school_id;

        LmsCourseInstructor::where('course_id', $course->id)->delete();

        foreach ($teacherProfileIds as $id) {
            LmsCourseInstructor::create([
                'school_id' => $schoolId,
                'course_id' => $course->id,
                'teacher_profile_id' => $id,
                'is_primary' => ((int) $primaryTeacherProfileId === (int) $id),
            ]);
        }
    }
}
