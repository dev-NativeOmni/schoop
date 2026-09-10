<?php

namespace App\Services\Ai;

use App\Models\AiLearningSignal;
use App\Models\AttendanceRecord;
use App\Models\HafalanRecord;
use App\Models\LmsCourseEnrollment;
use App\Models\MutabaahRecord;
use App\Models\Student;
use App\Models\TahfizhDebt;
use App\Models\TahsinAssessment;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class LearningSignalAggregator
{
    public function generateForStudent(Student $student, $date = null): Collection
    {
        $date = $date ? Carbon::parse($date) : Carbon::today();
        $schoolId = $student->school_id;
        $signals = collect();

        // 1. Tahfizh signals
        $latestDebt = TahfizhDebt::query()
            ->withoutGlobalScopes()
            ->where('student_id', $student->id)
            ->latest('calculation_date')
            ->first();

        if ($latestDebt && $latestDebt->cumulative_debt_lines > 0) {
            $severity = $latestDebt->cumulative_debt_lines > 10 ? 'urgent' : 'attention';
            $signals->push([
                'school_id' => $schoolId,
                'student_id' => $student->id,
                'signal_date' => $date->toDateString(),
                'source_module' => 'tahfizh',
                'signal_key' => 'tahfizh_debt_increasing',
                'severity' => $severity,
                'score' => (float) $latestDebt->cumulative_debt_lines,
                'description' => "Hutang hafalan menumpuk sebanyak {$latestDebt->cumulative_debt_lines} baris.",
                'evidence' => [
                    'cumulative_debt_lines' => $latestDebt->cumulative_debt_lines,
                    'debt_lines' => $latestDebt->debt_lines,
                    'calculation_date' => $latestDebt->calculation_date?->toDateString(),
                ],
            ]);
        } else {
            $recentHafalan = HafalanRecord::query()
                ->withoutGlobalScopes()
                ->where('student_id', $student->id)
                ->latest('record_date')
                ->take(3)
                ->get();

            $isStable = $recentHafalan->count() > 0 && $recentHafalan->every(fn ($r) => in_array($r->status, ['lunas', 'lebih']));
            if ($isStable) {
                $signals->push([
                    'school_id' => $schoolId,
                    'student_id' => $student->id,
                    'signal_date' => $date->toDateString(),
                    'source_module' => 'tahfizh',
                    'signal_key' => 'tahfizh_progress_stable',
                    'severity' => 'positive',
                    'score' => 100.00,
                    'description' => 'Progres setoran hafalan stabil dan konsisten.',
                    'evidence' => [
                        'recent_records' => $recentHafalan->map(fn ($r) => ['status' => $r->status, 'date' => $r->record_date?->toDateString()]),
                    ],
                ]);
            }
        }

        // 2. Tahsin signals
        $latestTahsin = TahsinAssessment::query()
            ->withoutGlobalScopes()
            ->where('student_id', $student->id)
            ->with(['items.skill'])
            ->latest('assessment_date')
            ->first();

        if ($latestTahsin) {
            foreach ($latestTahsin->items as $item) {
                if ($item->status === 'weak' || ($item->score !== null && $item->skill && $item->skill->maximum_score > 0 && ($item->score / $item->skill->maximum_score) < 0.7)) {
                    $skillCode = $item->skill ? strtolower($item->skill->code ?? $item->skill->name) : 'unknown';
                    $signals->push([
                        'school_id' => $schoolId,
                        'student_id' => $student->id,
                        'signal_date' => $date->toDateString(),
                        'source_module' => 'tahsin',
                        'signal_key' => "tahsin_{$skillCode}_weak",
                        'severity' => 'attention',
                        'score' => (float) ($item->score ?? 0),
                        'description' => 'Kemampuan tahsin pada aspek '.($item->skill?->name ?? 'tajwid').' perlu penguatan tambahan.',
                        'evidence' => [
                            'skill_name' => $item->skill?->name,
                            'score' => $item->score,
                            'max_score' => $item->skill?->maximum_score,
                            'assessment_date' => $latestTahsin->assessment_date?->toDateString(),
                        ],
                    ]);
                }
            }
        }

        // 3. Mutabaah signals
        $recentMutabaah = MutabaahRecord::query()
            ->withoutGlobalScopes()
            ->where('student_id', $student->id)
            ->where('record_date', '>=', $date->copy()->subDays(7)->toDateString())
            ->get();

        if ($recentMutabaah->count() > 0) {
            $totalCount = $recentMutabaah->count();
            $completedCount = $recentMutabaah->where('status', 'checked')->count();
            $completionRate = $totalCount > 0 ? ($completedCount / $totalCount) * 100 : 0;

            if ($completionRate < 50) {
                $signals->push([
                    'school_id' => $schoolId,
                    'student_id' => $student->id,
                    'signal_date' => $date->toDateString(),
                    'source_module' => 'mutabaah',
                    'signal_key' => 'mutabaah_low_consistency',
                    'severity' => 'attention',
                    'score' => (float) $completionRate,
                    'description' => "Konsistensi pengisian mutabaah harian di bawah target (hanya {$completedCount}/{$totalCount} terisi).",
                    'evidence' => [
                        'completion_rate' => $completionRate,
                        'total_activities' => $totalCount,
                        'completed_activities' => $completedCount,
                    ],
                ]);
            } elseif ($completionRate >= 80) {
                $signals->push([
                    'school_id' => $schoolId,
                    'student_id' => $student->id,
                    'signal_date' => $date->toDateString(),
                    'source_module' => 'mutabaah',
                    'signal_key' => 'mutabaah_high_consistency',
                    'severity' => 'positive',
                    'score' => (float) $completionRate,
                    'description' => 'Sangat konsisten mengamalkan mutabaah harian.',
                    'evidence' => [
                        'completion_rate' => $completionRate,
                    ],
                ]);
            }
        }

        // 4. Attendance signals
        $recentAttendance = AttendanceRecord::query()
            ->withoutGlobalScopes()
            ->where('student_id', $student->id)
            ->where('attendance_date', '>=', $date->copy()->subDays(14)->toDateString())
            ->get();

        if ($recentAttendance->count() > 0) {
            $absences = $recentAttendance->filter(fn ($r) => in_array(strtolower($r->status), ['alfa', 'tidak_hadir', 'absent', 'sakit', 'izin']));
            if ($absences->count() > 2) {
                $signals->push([
                    'school_id' => $schoolId,
                    'student_id' => $student->id,
                    'signal_date' => $date->toDateString(),
                    'source_module' => 'attendance',
                    'signal_key' => 'attendance_absence_pattern',
                    'severity' => 'attention',
                    'score' => (float) $absences->count(),
                    'description' => "Terdapat pola ketidakhadiran sebanyak {$absences->count()} kali dalam 14 hari terakhir.",
                    'evidence' => [
                        'absence_count' => $absences->count(),
                        'details' => $absences->map(fn ($r) => ['date' => $r->attendance_date?->toDateString(), 'status' => $r->status]),
                    ],
                ]);
            }
        }

        // 5. LMS signals
        $activeEnrollments = LmsCourseEnrollment::query()
            ->withoutGlobalScopes()
            ->where('student_id', $student->id)
            ->where('progress_percentage', '<', 100)
            ->with(['course'])
            ->get();

        foreach ($activeEnrollments as $enrollment) {
            $signals->push([
                'school_id' => $schoolId,
                'student_id' => $student->id,
                'signal_date' => $date->toDateString(),
                'source_module' => 'lms',
                'signal_key' => 'lms_content_incomplete',
                'severity' => 'info',
                'score' => (float) $enrollment->progress_percentage,
                'description' => 'Modul LMS '.($enrollment->course?->title ?? 'materi')." belum selesai ({$enrollment->progress_percentage}%).",
                'evidence' => [
                    'course_id' => $enrollment->lms_course_id,
                    'course_title' => $enrollment->course?->title,
                    'progress_percentage' => $enrollment->progress_percentage,
                ],
            ]);
        }

        // Persist to database
        $savedSignals = collect();
        foreach ($signals as $signalData) {
            // Check if signal exists for student, key, date
            $signal = AiLearningSignal::query()
                ->withoutGlobalScopes()
                ->updateOrCreate(
                    [
                        'school_id' => $signalData['school_id'],
                        'student_id' => $signalData['student_id'],
                        'signal_date' => $signalData['signal_date'],
                        'signal_key' => $signalData['signal_key'],
                    ],
                    $signalData
                );
            $savedSignals->push($signal);
        }

        return $savedSignals;
    }
}
