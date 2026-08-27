<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\MutabaahActivity;
use App\Models\MutabaahRecord;
use App\Models\Student;
use App\Services\Mutabaah\MutabaahAccessService;
use App\Services\Mutabaah\MutabaahRecordService;
use App\Services\Mutabaah\MutabaahReportService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentMutabaahPortalController extends Controller
{
    public function __construct(
        private readonly MutabaahAccessService $accessService,
        private readonly MutabaahReportService $reportService,
        private readonly MutabaahRecordService $recordService,
    ) {}

    public function index(Request $request): View
    {
        $user = $request->user();

        if (! $this->accessService->isStudent($user)) {
            abort(403, 'Halaman ini hanya untuk santri.');
        }

        $student = $user->studentProfile;

        if (! $student) {
            abort(404, 'Data santri tidak ditemukan.');
        }

        $targetDate = $request->input('date', today()->toDateString());
        $filters    = $request->only(['start_date', 'end_date']);
        $snapshot   = $this->reportService->studentSnapshot($student, $filters);

        $student->load('user', 'classRoom');

        // Activities for today's checklist
        $checklistActivities = MutabaahActivity::query()
            ->with('category')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        // Records for the targeted date
        $dayRecords = MutabaahRecord::query()
            ->where('student_id', $student->id)
            ->whereDate('record_date', $targetDate)
            ->get()
            ->keyBy('mutabaah_activity_id');

        // Calculate today's stats
        $totalDay = $checklistActivities->count();
        $doneDay  = $dayRecords->where('status', 'done')->count();
        $dayCompletionRate = $totalDay > 0 ? round(($doneDay / $totalDay) * 100) : 0;

        // Calculate current Istiqamah streak
        $streak = $this->calculateStreak($student);

        // Group activities by category for the daily checklist
        $categorizedActivities = $checklistActivities->groupBy(fn ($a) => $a->category?->name ?? 'Aktivitas Umum');

        return view('portal.student.mutabaah', array_merge($snapshot, [
            'filters'               => $filters,
            'targetDate'            => $targetDate,
            'checklistActivities'   => $checklistActivities,
            'categorizedActivities' => $categorizedActivities,
            'dayRecords'            => $dayRecords,
            'dayCompletionRate'     => $dayCompletionRate,
            'doneDay'               => $doneDay,
            'totalDay'              => $totalDay,
            'streak'                => $streak,
        ]));
    }

    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();

        if (! $this->accessService->isStudent($user)) {
            abort(403, 'Halaman ini hanya untuk santri.');
        }

        $student = $user->studentProfile;

        if (! $student) {
            abort(404, 'Data santri tidak ditemukan.');
        }

        $recordDate = $request->input('record_date', today()->toDateString());
        $records    = $request->input('records', []);

        $this->recordService->saveDailyRecords(
            student: $student,
            recordDate: $recordDate,
            records: $records,
            submittedBy: $user,
            source: 'student',
        );

        return redirect()
            ->route('portal.student.mutabaah', ['date' => $recordDate])
            ->with('success', 'Alhamdulillah! Catatan mutabaah Anda berhasil disimpan. Semoga senantiasa istiqamah!');
    }

    private function calculateStreak(Student $student): int
    {
        $dates = MutabaahRecord::query()
            ->where('student_id', $student->id)
            ->where('status', 'done')
            ->distinct()
            ->orderByDesc('record_date')
            ->pluck('record_date')
            ->map(fn ($d) => Carbon::parse($d)->toDateString())
            ->unique()
            ->values();

        if ($dates->isEmpty()) {
            return 0;
        }

        $streak = 0;
        $current = today();

        // If today has no records done yet, check if streak continued from yesterday
        if (! $dates->contains($current->toDateString())) {
            $yesterday = today()->subDay()->toDateString();
            if (! $dates->contains($yesterday)) {
                return 0;
            }
            $current = today()->subDay();
        }

        while ($dates->contains($current->toDateString())) {
            $streak++;
            $current = $current->subDay();
        }

        return $streak;
    }
}

