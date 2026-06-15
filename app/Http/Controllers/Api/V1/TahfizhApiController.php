<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\HafalanRecord;
use App\Services\DeveloperPortal\ApiAccessService;
use App\Services\DeveloperPortal\ApiResponseFormatter;
use Illuminate\Http\Request;

class TahfizhApiController extends Controller
{
    public function __construct(
        private readonly ApiAccessService $access,
        private readonly ApiResponseFormatter $response,
    ) {}

    public function progress(Request $request)
    {
        $schoolId = $this->access->externalSchoolId($request);

        $rows = HafalanRecord::query()
            ->selectRaw('student_id, SUM(total_lines) as total_lines, MAX(end_page) as current_page, MAX(end_line) as current_line')
            ->where('school_id', $schoolId)
            ->when($request->filled('student_id'), fn ($query) => $query->where('student_id', $request->integer('student_id')))
            ->groupBy('student_id')
            ->limit(200)
            ->get()
            ->map(fn ($row): array => [
                'student_id' => (int) $row->student_id,
                'total_lines' => (int) $row->total_lines,
                'current_page' => (int) $row->current_page,
                'current_line' => (int) $row->current_line,
                'target_status' => 'summary_only',
            ]);

        return $this->response->ok(['progress' => $rows]);
    }
}
