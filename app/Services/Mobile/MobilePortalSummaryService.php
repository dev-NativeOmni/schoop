<?php

namespace App\Services\Mobile;

use App\Models\AttendanceQrToken;
use App\Models\AttendanceRecord;
use App\Models\CashlessWallet;
use App\Models\CashlessWalletTransaction;
use App\Models\HafalanRecord;
use App\Models\MutabaahRecord;
use App\Models\Student;
use App\Models\StudentBill;
use App\Models\StudentPayment;
use App\Models\TahfizhDebt;
use App\Models\TahfizhTarget;
use App\Models\TahsinAssessment;
use App\Models\User;
use App\Services\Portal\StudentProgressSnapshotService;
use Illuminate\Notifications\DatabaseNotification;

class MobilePortalSummaryService
{
    public function __construct(
        private readonly StudentProgressSnapshotService $progressSnapshots,
    ) {}

    public function studentCard(Student $student): array
    {
        $student->loadMissing(['school', 'classRoom']);

        return [
            'id' => $student->id,
            'student_number' => $student->student_number,
            'nisn' => $student->nisn,
            'full_name' => $student->full_name,
            'nickname' => $student->nickname,
            'gender' => $student->gender,
            'program_type' => $student->program_type,
            'class_room' => $student->classRoom ? [
                'id' => $student->classRoom->id,
                'name' => $student->classRoom->name,
                'level' => $student->classRoom->level,
            ] : null,
            'school' => $student->school ? [
                'id' => $student->school->id,
                'name' => $student->school->name,
            ] : null,
        ];
    }

    public function summary(Student $student): array
    {
        $snapshot = $this->progressSnapshots->snapshot($student);

        return [
            'student' => $this->studentCard($student),
            'tahfizh' => [
                'total_records_this_period' => $snapshot['total_records'],
                'total_lines_this_period' => $snapshot['total_lines'],
                'target_daily_lines' => $snapshot['target_daily_lines'],
                'target_weekly_lines' => $snapshot['target_weekly_lines'],
                'target_monthly_lines' => $snapshot['target_monthly_lines'],
                'current_debt_lines' => $snapshot['current_debt_lines'],
                'monthly_status' => $snapshot['monthly_status'],
                'latest_record' => $snapshot['latest_record'] ? $this->hafalanRecord($snapshot['latest_record']) : null,
            ],
            'attendance' => [
                'today' => AttendanceRecord::query()
                    ->where('student_id', $student->id)
                    ->whereDate('attendance_date', now()->toDateString())
                    ->latest('id')
                    ->first(),
                'present_this_month' => AttendanceRecord::query()
                    ->where('student_id', $student->id)
                    ->whereBetween('attendance_date', [now()->startOfMonth()->toDateString(), now()->endOfMonth()->toDateString()])
                    ->whereIn('status', ['present', 'late'])
                    ->count(),
            ],
            'finance' => $this->financeSummary($student),
            'cashless' => $this->cashlessSummary($student),
            'mutabaah' => [
                'records_this_month' => MutabaahRecord::query()
                    ->where('student_id', $student->id)
                    ->whereBetween('record_date', [now()->startOfMonth()->toDateString(), now()->endOfMonth()->toDateString()])
                    ->count(),
            ],
            'tahsin' => [
                'latest_assessment' => $this->tahsinAssessments($student, 1)[0] ?? null,
            ],
        ];
    }

    public function tahfizh(Student $student): array
    {
        return [
            'targets' => TahfizhTarget::query()
                ->where(function ($query) use ($student): void {
                    $query->where('student_id', $student->id)
                        ->orWhere('class_room_id', $student->class_room_id);
                })
                ->where('is_active', true)
                ->latest('effective_from')
                ->limit(10)
                ->get()
                ->map(fn (TahfizhTarget $target): array => [
                    'id' => $target->id,
                    'name' => $target->name,
                    'program_type' => $target->program_type,
                    'daily_target_lines' => $target->daily_target_lines,
                    'weekly_target_lines' => $target->weekly_target_lines,
                    'monthly_target_lines' => $target->monthly_target_lines,
                    'effective_from' => $target->effective_from?->toDateString(),
                    'effective_until' => $target->effective_until?->toDateString(),
                ]),
            'debts' => TahfizhDebt::query()
                ->where('student_id', $student->id)
                ->latest('period_end')
                ->limit(12)
                ->get(),
            'records' => HafalanRecord::query()
                ->with(['teacher', 'startSurah', 'endSurah'])
                ->where('student_id', $student->id)
                ->latest('record_date')
                ->latest('id')
                ->limit(50)
                ->get()
                ->map(fn (HafalanRecord $record): array => $this->hafalanRecord($record)),
        ];
    }

    public function mutabaah(Student $student): array
    {
        return [
            'records' => MutabaahRecord::query()
                ->with('activity.category')
                ->where('student_id', $student->id)
                ->latest('record_date')
                ->latest('id')
                ->limit(50)
                ->get()
                ->map(fn (MutabaahRecord $record): array => [
                    'id' => $record->id,
                    'record_date' => $record->record_date?->toDateString(),
                    'status' => $record->status,
                    'score' => $record->score,
                    'count_value' => $record->count_value,
                    'text_value' => $record->text_value,
                    'note' => $record->note,
                    'activity' => $record->activity ? [
                        'id' => $record->activity->id,
                        'name' => $record->activity->name,
                        'input_type' => $record->activity->input_type,
                        'category' => $record->activity->category?->name,
                    ] : null,
                ]),
        ];
    }

    public function attendance(Student $student): array
    {
        return [
            'records' => AttendanceRecord::query()
                ->with('session')
                ->where('student_id', $student->id)
                ->latest('attendance_date')
                ->latest('id')
                ->limit(50)
                ->get(),
        ];
    }

    public function tahsin(Student $student): array
    {
        return [
            'profile' => $student->tahsinProfile()->with('currentLevel')->first(),
            'assessments' => $this->tahsinAssessments($student, 30),
        ];
    }

    public function finance(Student $student): array
    {
        return [
            'summary' => $this->financeSummary($student),
            'bills' => StudentBill::query()
                ->where('student_id', $student->id)
                ->where('status', '!=', StudentBill::STATUS_VOID)
                ->latest('issued_date')
                ->limit(30)
                ->get(),
            'payments' => StudentPayment::query()
                ->where('student_id', $student->id)
                ->where('status', StudentPayment::STATUS_POSTED)
                ->latest('payment_date')
                ->limit(30)
                ->get(),
        ];
    }

    public function cashless(Student $student): array
    {
        return [
            'summary' => $this->cashlessSummary($student),
            'transactions' => CashlessWalletTransaction::query()
                ->where('student_id', $student->id)
                ->latest('posted_at')
                ->latest('id')
                ->limit(50)
                ->get(),
        ];
    }

    public function notifications(User $user): array
    {
        return [
            'notifications' => $user->notifications()
                ->latest()
                ->limit(50)
                ->get()
                ->map(fn (DatabaseNotification $notification): array => [
                    'id' => $notification->id,
                    'type' => $notification->type,
                    'data' => $notification->data,
                    'read_at' => $notification->read_at?->toIso8601String(),
                    'created_at' => $notification->created_at?->toIso8601String(),
                ])
                ->values(),
        ];
    }

    public function qrCard(Student $student): array
    {
        $token = AttendanceQrToken::query()
            ->where('student_id', $student->id)
            ->where('is_active', true)
            ->first();

        return [
            'student' => $this->studentCard($student),
            'qr_payload' => $token ? 'HFP-ATT:'.$token->token : null,
            'expires_at' => null,
            'is_available' => (bool) $token,
        ];
    }

    private function financeSummary(Student $student): array
    {
        return [
            'outstanding_amount' => (int) StudentBill::query()
                ->where('student_id', $student->id)
                ->whereIn('status', [StudentBill::STATUS_POSTED, StudentBill::STATUS_PARTIAL, StudentBill::STATUS_OVERDUE])
                ->sum('outstanding_amount'),
            'paid_this_month' => (int) StudentPayment::query()
                ->where('student_id', $student->id)
                ->where('status', StudentPayment::STATUS_POSTED)
                ->whereBetween('payment_date', [now()->startOfMonth()->toDateString(), now()->endOfMonth()->toDateString()])
                ->sum('amount'),
        ];
    }

    private function cashlessSummary(Student $student): array
    {
        $wallet = CashlessWallet::query()
            ->where('student_id', $student->id)
            ->first();

        return [
            'wallet_number' => $wallet?->wallet_number,
            'balance' => (int) ($wallet?->balance ?? 0),
            'status' => $wallet?->status,
            'last_transaction_at' => $wallet
                ? CashlessWalletTransaction::query()
                    ->where('cashless_wallet_id', $wallet->id)
                    ->latest('posted_at')
                    ->value('posted_at')
                : null,
        ];
    }

    private function tahsinAssessments(Student $student, int $limit): array
    {
        return TahsinAssessment::query()
            ->with(['teacher', 'level', 'items.skill'])
            ->where('student_id', $student->id)
            ->latest('assessment_date')
            ->latest('id')
            ->limit($limit)
            ->get()
            ->map(fn (TahsinAssessment $assessment): array => [
                'id' => $assessment->id,
                'assessment_date' => $assessment->assessment_date?->toDateString(),
                'assessment_type' => $assessment->assessment_type,
                'overall_score' => $assessment->overall_score,
                'grade' => $assessment->grade,
                'status' => $assessment->status,
                'note' => $assessment->note,
                'recommendation' => $assessment->recommendation,
                'teacher' => $assessment->teacher?->name,
                'level' => $assessment->level?->name,
                'items' => $assessment->items->map(fn ($item): array => [
                    'skill' => $item->skill?->name,
                    'score' => $item->score,
                    'status' => $item->status,
                    'note' => $item->note,
                ])->values(),
            ])
            ->values()
            ->all();
    }

    private function hafalanRecord(HafalanRecord $record): array
    {
        return [
            'id' => $record->id,
            'record_date' => $record->record_date?->toDateString(),
            'teacher' => $record->teacher?->name,
            'start_surah' => $record->startSurah?->name,
            'start_ayah' => $record->start_ayah,
            'end_surah' => $record->endSurah?->name,
            'end_ayah' => $record->end_ayah,
            'start_page' => $record->start_page,
            'start_line' => $record->start_line,
            'end_page' => $record->end_page,
            'end_line' => $record->end_line,
            'total_lines' => $record->total_lines,
            'status' => $record->status,
            'quality_score' => $record->quality_score,
            'notes' => $record->notes,
        ];
    }
}
