<?php

namespace App\Http\Controllers\Lms;

use App\Http\Controllers\Controller;
use App\Http\Requests\Lms\StoreLmsCourseModuleRequest;
use App\Http\Requests\Lms\UpdateLmsCourseModuleRequest;
use App\Models\LmsCourse;
use App\Models\LmsCourseModule;
use App\Services\Lms\LmsAccessService;
use App\Services\Lms\LmsModuleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LmsCourseModuleController extends Controller
{
    public function __construct(
        private readonly LmsModuleService $moduleService,
        private readonly LmsAccessService $accessService,
    ) {
        //
    }

    public function store(StoreLmsCourseModuleRequest $request): RedirectResponse
    {
        $course = LmsCourse::findOrFail($request->input('course_id'));
        if (! $this->accessService->canManageCourse(Auth::user(), $course)) {
            abort(403);
        }

        $module = $this->moduleService->createModule($request->validated());

        return redirect()->route('lms.courses.show', $course->id)
            ->with('success', "Modul '{$module->title}' berhasil ditambahkan.");
    }

    public function update(UpdateLmsCourseModuleRequest $request, LmsCourseModule $module): RedirectResponse
    {
        if (! $this->accessService->canManageCourse(Auth::user(), $module->course)) {
            abort(403);
        }

        $this->moduleService->updateModule($module, $request->validated());

        return redirect()->route('lms.courses.show', $module->course_id)
            ->with('success', "Modul '{$module->title}' berhasil diperbarui.");
    }

    public function destroy(LmsCourseModule $module): RedirectResponse
    {
        if (! $this->accessService->canManageCourse(Auth::user(), $module->course)) {
            abort(403);
        }

        $courseId = $module->course_id;
        $this->moduleService->deleteModule($module);

        return redirect()->route('lms.courses.show', $courseId)
            ->with('success', 'Modul berhasil dihapus.');
    }

    public function reorder(Request $request): JsonResponse
    {
        $request->validate([
            'module_ids' => 'required|array',
            'module_ids.*' => 'exists:lms_course_modules,id',
        ]);

        $ids = $request->input('module_ids');
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'Empty array']);
        }

        $firstModule = LmsCourseModule::findOrFail($ids[0]);
        if (! $this->accessService->canManageCourse(Auth::user(), $firstModule->course)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $this->moduleService->reorderModules($ids);

        return response()->json(['success' => true, 'message' => 'Modules reordered successfully.']);
    }
}
