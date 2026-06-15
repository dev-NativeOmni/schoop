<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Services\DeveloperPortal\ApiAccessService;
use App\Services\DeveloperPortal\ApiResponseFormatter;
use Illuminate\Http\Request;

class StudentApiController extends Controller
{
    public function __construct(
        private readonly ApiAccessService $access,
        private readonly ApiResponseFormatter $response,
    ) {}

    public function index(Request $request)
    {
        $schoolId = $this->access->externalSchoolId($request);

        return $this->response->ok([
            'students' => Student::query()
                ->where('school_id', $schoolId)
                ->where('is_active', true)
                ->when($request->filled('class_room_id'), fn ($query) => $query->where('class_room_id', $request->integer('class_room_id')))
                ->orderBy('full_name')
                ->limit(200)
                ->get()
                ->map(fn (Student $student): array => $this->payload($student))
                ->values(),
        ]);
    }

    public function show(Request $request, int $student)
    {
        $schoolId = $this->access->externalSchoolId($request);
        $model = Student::query()
            ->where('school_id', $schoolId)
            ->findOrFail($student);

        return $this->response->ok([
            'student' => $this->payload($model),
        ]);
    }

    private function payload(Student $student): array
    {
        return [
            'id' => $student->id,
            'school_id' => $student->school_id,
            'class_room_id' => $student->class_room_id,
            'nis' => $student->student_number,
            'name' => $student->full_name,
            'nickname' => $student->nickname,
            'program_type' => $student->program_type,
            'status' => $student->is_active ? 'active' : 'inactive',
        ];
    }
}
