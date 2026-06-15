<?php

namespace App\Services\Lms;

use App\Models\LmsLessonResource;
use App\Services\Tenancy\TenantContextService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class LmsResourceService
{
    public function __construct(
        private readonly TenantContextService $tenantContext,
        private readonly LmsActivityLogger $logger,
    ) {
        //
    }

    public function createResource(array $data, ?UploadedFile $file, int $userId): LmsLessonResource
    {
        return DB::transaction(function () use ($data, $file, $userId) {
            $schoolId = $this->tenantContext->activeSchoolId();
            $data['school_id'] = $schoolId;
            $data['uploaded_by'] = $userId;

            if ($file && $data['resource_type'] === 'file') {
                $this->validateFile($file);

                $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9_.-]/', '', $file->getClientOriginalName());
                $path = $file->storeAs("private/lms/{$schoolId}", $filename, 'local');

                $data['file_path'] = $path;
                $data['mime_type'] = $file->getMimeType();
                $data['file_size'] = $file->getSize();
            }

            if (!isset($data['sort_order'])) {
                $maxSort = LmsLessonResource::where('lesson_id', $data['lesson_id'])->max('sort_order');
                $data['sort_order'] = $maxSort !== null ? $maxSort + 1 : 1;
            }

            $resource = LmsLessonResource::create($data);
            $this->logger->log('resource_create', "Resource '{$resource->title}' created.", $resource);

            return $resource;
        });
    }

    public function deleteResource(LmsLessonResource $resource): void
    {
        DB::transaction(function () use ($resource) {
            if ($resource->file_path && Storage::disk('local')->exists($resource->file_path)) {
                Storage::disk('local')->delete($resource->file_path);
            }
            $resource->delete();
            $this->logger->log('resource_delete', "Resource '{$resource->title}' deleted.", $resource);
        });
    }

    public function validateFile(UploadedFile $file): void
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $blockedExtensions = ['exe', 'bat', 'cmd', 'sh', 'php', 'js', 'zip'];

        if (in_array($extension, $blockedExtensions, true)) {
            throw new \InvalidArgumentException('Format file executable atau terkompresi tidak didukung untuk alasan keamanan.');
        }

        // Limit size to 10MB
        $maxSizeBytes = 10 * 1024 * 1024;
        if ($file->getSize() > $maxSizeBytes) {
            throw new \InvalidArgumentException('Ukuran file tidak boleh melebihi 10 MB.');
        }
    }
}
