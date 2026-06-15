<?php

namespace App\Services\Analytics;

use App\Models\ExecutiveReportRun;
use App\Models\ExecutiveReportSection;
use App\Models\School;
use App\Models\SchoolAcademicSnapshot;
use App\Models\SchoolFinanceSnapshot;
use App\Models\SchoolOperationalSnapshot;
use App\Models\SchoolSupportSnapshot;
use App\Models\MobileApiUsageSnapshot;
use App\Models\TenantHealthScore;
use Carbon\CarbonInterface;

class ExecutiveReportService
{
    public function generateMonthlyInternalReport(CarbonInterface $periodStart, CarbonInterface $periodEnd, ?int $generatedBy = null): ExecutiveReportRun
    {
        $startStr = $periodStart->toDateString();
        $endStr = $periodEnd->toDateString();

        $activeTenants = School::query()->where('is_active', true)->count();
        $riskCount = TenantHealthScore::query()
            ->whereBetween('score_date', [$startStr, $endStr])
            ->whereIn('status', ['risk', 'critical'])
            ->distinct('school_id')
            ->count();

        $academicRecords = SchoolAcademicSnapshot::query()
            ->whereBetween('snapshot_date', [$startStr, $endStr])
            ->sum('hafalan_records_count') ?: 0;

        $financeOutstanding = SchoolFinanceSnapshot::query()
            ->whereBetween('snapshot_date', [$startStr, $endStr])
            ->avg('student_outstanding_total') ?: 0;

        $ticketsCount = SchoolSupportSnapshot::query()
            ->whereBetween('snapshot_date', [$startStr, $endStr])
            ->sum('open_tickets_count') ?: 0;

        $metrics = [
            'active_tenants' => $activeTenants,
            'risk_tenants' => $riskCount,
            'hafalan_records' => $academicRecords,
            'avg_outstanding' => $financeOutstanding,
            'open_tickets' => $ticketsCount,
        ];

        $highlights = $this->buildHighlights($metrics, true);
        $risks = $this->buildRisks($metrics, true);
        $recommendations = $this->buildRecommendations($metrics, true);

        $report = ExecutiveReportRun::query()->create([
            'school_id' => null,
            'report_type' => 'monthly',
            'status' => 'draft',
            'period_start' => $startStr,
            'period_end' => $endStr,
            'title' => "HafizPlus Executive Report: {$periodStart->format('F Y')}",
            'summary' => "Laporan bulanan kinerja operasional dan finansial platform HafizPlus untuk periode {$periodStart->format('F Y')}.",
            'highlights' => $highlights,
            'risks' => $risks,
            'recommendations' => $recommendations,
            'generated_by' => $generatedBy,
            'generated_at' => now(),
        ]);

        $this->createReportSections($report, $metrics, true);

        return $report;
    }

    public function generateMonthlySchoolReport(int $schoolId, CarbonInterface $periodStart, CarbonInterface $periodEnd, ?int $generatedBy = null): ExecutiveReportRun
    {
        $startStr = $periodStart->toDateString();
        $endStr = $periodEnd->toDateString();

        $school = School::query()->findOrFail($schoolId);

        $academic = SchoolAcademicSnapshot::query()
            ->where('school_id', $schoolId)
            ->whereBetween('snapshot_date', [$startStr, $endStr])
            ->get();

        $operational = SchoolOperationalSnapshot::query()
            ->where('school_id', $schoolId)
            ->whereBetween('snapshot_date', [$startStr, $endStr])
            ->get();

        $finance = SchoolFinanceSnapshot::query()
            ->where('school_id', $schoolId)
            ->whereBetween('snapshot_date', [$startStr, $endStr])
            ->get();

        $support = SchoolSupportSnapshot::query()
            ->where('school_id', $schoolId)
            ->whereBetween('snapshot_date', [$startStr, $endStr])
            ->get();

        $metrics = [
            'hafalan_records' => $academic->sum('hafalan_records_count'),
            'hafalan_lines' => $academic->sum('hafalan_total_lines'),
            'tahfizh_rate' => $academic->avg('tahfizh_target_achievement_rate') ?: 0,
            'mutabaah_rate' => $academic->avg('mutabaah_completion_rate') ?: 0,
            'attendance_rate' => $operational->avg('attendance_rate') ?: 0,
            'late_rate' => $operational->avg('late_rate') ?: 0,
            'outstanding_bills' => $finance->avg('student_outstanding_total') ?: 0,
            'cashless_purchases' => $finance->sum('cashless_purchase_total') ?: 0,
            'tickets' => $support->sum('open_tickets_count') ?: 0,
        ];

        $highlights = $this->buildHighlights($metrics, false);
        $risks = $this->buildRisks($metrics, false);
        $recommendations = $this->buildRecommendations($metrics, false);

        $report = ExecutiveReportRun::query()->create([
            'school_id' => $schoolId,
            'report_type' => 'monthly',
            'status' => 'draft',
            'period_start' => $startStr,
            'period_end' => $endStr,
            'title' => "Laporan Eksekutif Sekolah - {$school->name}: {$periodStart->format('F Y')}",
            'summary' => "Laporan berkala performa akademik, operasional, dan kestabilan finansial sekolah periode {$periodStart->format('F Y')}.",
            'highlights' => $highlights,
            'risks' => $risks,
            'recommendations' => $recommendations,
            'generated_by' => $generatedBy,
            'generated_at' => now(),
        ]);

        $this->createReportSections($report, $metrics, false);

        return $report;
    }

    public function buildHighlights(array $metrics, bool $isInternal): array
    {
        $highlights = [];
        if ($isInternal) {
            $highlights[] = "Total tenant aktif saat ini adalah {$metrics['active_tenants']} sekolah.";
            $highlights[] = "Total setoran hafalan tercatat sebanyak {$metrics['hafalan_records']} records.";
        } else {
            $highlights[] = "Total setoran hafalan santri bulan ini adalah {$metrics['hafalan_records']} records.";
            $highlights[] = "Rata-rata pencapaian target Tahfizh mencapai " . round($metrics['tahfizh_rate'], 1) . "%.";
            $highlights[] = "Rata-rata kehadiran santri mencapai " . round($metrics['attendance_rate'], 1) . "%.";
        }
        return $highlights;
    }

    public function buildRisks(array $metrics, bool $isInternal): array
    {
        $risks = [];
        if ($isInternal) {
            if ($metrics['risk_tenants'] > 0) {
                $risks[] = "Terdapat {$metrics['risk_tenants']} tenant yang terindikasi risiko kesehatan (Status Risk/Critical).";
            }
            if ($metrics['open_tickets'] > 5) {
                $risks[] = "Tiket support yang masih open cukup tinggi ({$metrics['open_tickets']} tiket).";
            }
        } else {
            if ($metrics['tahfizh_rate'] < 60) {
                $risks[] = "Pencapaian target Tahfizh berada di bawah rata-rata nasional (kategori rawan).";
            }
            if ($metrics['mutabaah_rate'] < 50) {
                $risks[] = "Tingkat kedisiplinan pengisian mutabaah yaumiyah masih rendah.";
            }
            if ($metrics['late_rate'] > 10) {
                $risks[] = "Tingkat keterlambatan santri masuk sekolah di atas batas normal ({$metrics['late_rate']}%).";
            }
        }
        return $risks;
    }

    public function buildRecommendations(array $metrics, bool $isInternal): array
    {
        $recs = [];
        if ($isInternal) {
            if ($metrics['risk_tenants'] > 0) {
                $recs[] = "Tim Customer Success direkomendasikan melakukan simulasi onboarding ulang pada tenant berisiko.";
            }
            if ($metrics['open_tickets'] > 0) {
                $recs[] = "Prioritaskan penyelesaian tiket support yang hampir melanggar SLA.";
            }
        } else {
            if ($metrics['tahfizh_rate'] < 60) {
                $recs[] = "Lakukan audit konsistensi input setoran guru tahfizh.";
            }
            if ($metrics['mutabaah_rate'] < 50) {
                $recs[] = "Berikan sosialisasi atau training ulang pengisian modul portal orang tua.";
            }
            if ($metrics['late_rate'] > 10) {
                $recs[] = "Evaluasi jam masuk kedatangan santri dengan tim kedisiplinan.";
            }
        }

        if (empty($recs)) {
            $recs[] = "Pertahankan konsistensi performa operasional saat ini.";
        }

        return $recs;
    }

    private function createReportSections(ExecutiveReportRun $report, array $metrics, bool $isInternal): void
    {
        if ($isInternal) {
            ExecutiveReportSection::query()->create([
                'executive_report_run_id' => $report->id,
                'section_key' => 'tenant_summary',
                'title' => 'Tenant Growth & Status',
                'content' => "Jumlah tenant berstatus aktif bulan ini adalah {$metrics['active_tenants']}. CS disarankan memperhatikan {$metrics['risk_tenants']} tenant berisiko.",
                'metrics' => [
                    'active_tenants' => $metrics['active_tenants'],
                    'risk_tenants' => $metrics['risk_tenants'],
                ],
                'sort_order' => 10,
            ]);
        } else {
            ExecutiveReportSection::query()->create([
                'executive_report_run_id' => $report->id,
                'section_key' => 'academic_performance',
                'title' => 'Performa Akademik & Tahfizh',
                'content' => "Performa akademik menunjukkan pencapaian target hafalan rata-rata santri di angka {$metrics['tahfizh_rate']}%. Total hafalan tercatat sebanyak {$metrics['hafalan_records']} records dengan {$metrics['hafalan_lines']} baris hafalan baru.",
                'metrics' => [
                    'tahfizh_rate' => $metrics['tahfizh_rate'],
                    'hafalan_records' => $metrics['hafalan_records'],
                ],
                'sort_order' => 10,
            ]);
        }
    }
}
