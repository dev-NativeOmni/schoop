<?php

namespace App\Http\Controllers\Lms;

use App\Http\Controllers\Controller;
use App\Http\Requests\Lms\StoreLmsLessonResourceRequest;
use App\Models\LmsAssignmentSubmission;
use App\Models\LmsLesson;
use App\Models\LmsLessonResource;
use App\Services\Lms\LmsAccessService;
use App\Services\Lms\LmsResourceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class LmsLessonResourceController extends Controller
{
    public function __construct(
        private readonly LmsResourceService $resourceService,
        private readonly LmsAccessService $accessService,
    ) {
        //
    }

    public function store(StoreLmsLessonResourceRequest $request): RedirectResponse
    {
        $lesson = LmsLesson::findOrFail($request->input('lesson_id'));
        if (! $this->accessService->canManageLesson(Auth::user(), $lesson)) {
            abort(403);
        }

        $resource = $this->resourceService->createResource(
            $request->validated(),
            $request->file('file'),
            Auth::id()
        );

        return redirect()->route('lms.lessons.show', $lesson->id)
            ->with('success', "Lampiran '{$resource->title}' berhasil ditambahkan.");
    }

    public function destroy(LmsLessonResource $resource): RedirectResponse
    {
        if (! $this->accessService->canManageLesson(Auth::user(), $resource->lesson)) {
            abort(403);
        }

        $lessonId = $resource->lesson_id;
        $this->resourceService->deleteResource($resource);

        return redirect()->route('lms.lessons.show', $lessonId)
            ->with('success', 'Lampiran berhasil dihapus.');
    }

    public function downloadPrivateFile(Request $request, string $type, int $id): BinaryFileResponse
    {
        $user = Auth::user();
        if (! $user) {
            abort(401);
        }

        $filePath = null;
        $fileName = 'file';

        if ($type === 'resource') {
            $resource = LmsLessonResource::findOrFail($id);
            if (! $this->accessService->canViewLesson($user, $resource->lesson)) {
                abort(403, 'Anda tidak memiliki akses ke materi ini.');
            }
            $filePath = $resource->file_path;
            $fileName = $resource->title;
        } elseif ($type === 'submission') {
            $submission = LmsAssignmentSubmission::findOrFail($id);

            // Check authorization for submission
            if ($user->isStudent()) {
                $studentProfile = $user->studentProfile;
                if (! $studentProfile || $submission->student_id !== $studentProfile->id) {
                    abort(403, 'Anda tidak diizinkan mengakses pengumpulan tugas ini.');
                }
            } elseif ($user->isParent()) {
                if (! $this->accessService->canViewChildProgress($user, $submission->student)) {
                    abort(403, 'Wali murid tidak memiliki akses ke pengumpulan tugas anak ini.');
                }
            } elseif (! $user->isSuperAdmin() && ! $user->isAdmin() && ! $user->isTeacher() && ! $user->isPrincipal()) {
                abort(403);
            }

            $filePath = $submission->file_path;
            $fileName = $submission->file_name ?? 'submission_file';
        } else {
            abort(404);
        }

        if (! $filePath || ! Storage::disk('local')->exists($filePath)) {
            abort(404, 'File tidak ditemukan di storage server.');
        }

        return response()->download(storage_path('app/'.$filePath), $fileName);
    }
}
