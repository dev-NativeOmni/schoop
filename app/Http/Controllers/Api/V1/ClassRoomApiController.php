<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
use App\Services\DeveloperPortal\ApiAccessService;
use App\Services\DeveloperPortal\ApiResponseFormatter;
use Illuminate\Http\Request;

class ClassRoomApiController extends Controller
{
    public function __construct(
        private readonly ApiAccessService $access,
        private readonly ApiResponseFormatter $response,
    ) {}

    public function index(Request $request)
    {
        $schoolId = $this->access->externalSchoolId($request);

        return $this->response->ok([
            'classes' => ClassRoom::query()
                ->where('school_id', $schoolId)
                ->where('is_active', true)
                ->orderBy('name')
                ->get()
                ->map(fn (ClassRoom $classRoom): array => [
                    'id' => $classRoom->id,
                    'school_id' => $classRoom->school_id,
                    'name' => $classRoom->name,
                    'level' => $classRoom->level,
                    'academic_year' => $classRoom->academic_year,
                    'status' => $classRoom->is_active ? 'active' : 'inactive',
                ]),
        ]);
    }
}
