<?php

namespace App\Services\Analytics;

use App\Models\School;
use App\Models\SchoolAcademicSnapshot;
use App\Models\SchoolFinanceSnapshot;
use App\Models\SchoolOperationalSnapshot;
use App\Models\SchoolSupportSnapshot;
use App\Models\MobileApiUsageSnapshot;
use App\Models\TenantHealthScore;
use App\Models\TenantHealthScoreComponent;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TenantHealthScoreService
{
    public function calculateForSchool(int $schoolId, CarbonInterface $date): TenantHealthScore
    {
        $dateStr = $date->toDateString();

        // Check if academic snapshot exists for the date, otherwise use latest
        $academic = SchoolAcademicSnapshot::query()
            ->where('school_id', $schoolId)
            ->where('snapshot_date', '<=', $dateStr)
            ->latest('snapshot_date')
            ->first();

        if (! $academic) {
            // No snapshots found, return unknown
            return TenantHealthScore::query()->updateOrCreate(
                [
                    'school_id' => $schoolId,
                    'score_date' => $dateStr,
                ],
                [
                    'score' => 0,
                    'status' => 'unknown',
                    'summary' => 'Tidak cukup data snapshot untuk menghitung skor kesehatan.',
                    'risk_flags' => ['no_snapshots'],
                    'recommendations' => ['Jalankan capture snapshot harian terlebih dahulu.'],
                ]
            );
        }

        $operational = SchoolOperationalSnapshot::query()
            ->where('school_id', $schoolId)
            ->where('snapshot_date', '<=', $dateStr)
            ->latest('snapshot_date')
            ->first();

        $finance = SchoolFinanceSnapshot::query()
            ->where('school_id', $schoolId)
            ->where('snapshot_date', '<=', $dateStr)
            ->latest('snapshot_date')
            ->first();

        $support = SchoolSupportSnapshot::query()
            ->where('school_id', $schoolId)
            ->where('snapshot_date', '<=', $dateStr)
            ->latest('snapshot_date')
            ->first();

        $mobile = MobileApiUsageSnapshot::query()
            ->where('school_id', $schoolId)
            ->where('snapshot_date', '<=', $dateStr)
            ->latest('snapshot_date')
            ->first();

        // 1. usage_activity (max 20)
        $usageScore = 10; // baseline
        if ($academic->active_students_count > 0) {
            $usageScore = min(20, round(10 + ($academic->mutabaah_completion_rate / 10)));
        }

        // 2. academic_activity (max 20)
        $academicScore = min(20, round(($academic->tahfizh_target_achievement_rate / 100) * 20));

        // 3. parent_engagement (max 15)
        $parentScore = 5;
        if ($academic->active_students_count > 0 && $mobile) {
            $adoptionRate = ($mobile->mobile_devices_count / $academic->active_students_count) * 100;
            $parentScore = min(15, round(($adoptionRate / 100) * 15));
        }

        // 4. finance_health (max 10)
        $financeScore = 10;
        if ($finance && $finance->student_bills_total > 0) {
            $paymentRatio = $finance->student_payments_total / $finance->student_bills_total;
            $financeScore = min(10, round($paymentRatio * 10));
        }

        // 5. support_risk (max 10)
        $supportScore = 10;
        if ($support && $support->open_tickets_count > 0) {
            $breachRatio = $support->sla_breached_tickets_count / $support->open_tickets_count;
            $supportScore = max(0, min(10, round((1 - $breachRatio) * 10)));
        }

        // 6. incident_risk (max 10)
        $incidentScore = 10;
        if ($support) {
            $incidentScore = max(0, 10 - ($support->incidents_count * 2));
        }

        // 7. subscription_status (max 10)
        $subScore = 10;
        $schoolModel = School::query()->find($schoolId);
        if ($schoolModel && Schema::hasTable('saas_school_subscriptions')) {
            $sub = DB::table('saas_school_subscriptions')
                ->where('school_id', $schoolId)
                ->where('status', 'active')
                ->first();
            if (! $sub) {
                // grace period check
                $graceSub = DB::table('saas_school_subscriptions')
                    ->where('school_id', $schoolId)
                    ->where('status', 'grace')
                    ->first();
                if ($graceSub) {
                    $subScore = 3;
                } else {
                    $subScore = 0;
                }
            }
        }

        // 8. mobile_api_adoption (max 5)
        $mobileAdoption = 5;
        if ($mobile && $mobile->api_requests_count === 0) {
            $mobileAdoption = 2;
        }

        $totalScore = $usageScore + $academicScore + $parentScore + $financeScore + $supportScore + $incidentScore + $subScore + $mobileAdoption;
        $status = $this->resolveStatus($totalScore);

        $riskFlags = [];
        if ($totalScore < 40) {
            $riskFlags[] = 'critical_health';
        }
        if ($financeScore < 4) {
            $riskFlags[] = 'high_outstanding_bills';
        }
        if ($supportScore < 4) {
            $riskFlags[] = 'sla_breach_alert';
        }
        if ($subScore === 0) {
            $riskFlags[] = 'no_active_subscription';
        }

        $recommendations = [];
        if (in_array('critical_health', $riskFlags, true)) {
            $recommendations[] = 'Segera lakukan evaluasi menyeluruh terhadap penggunaan platform.';
        }
        if (in_array('high_outstanding_bills', $riskFlags, true)) {
            $recommendations[] = 'Kirim notifikasi tagihan outstanding ke orang tua melalui PWA / Mobile.';
        }
        if (in_array('sla_breach_alert', $riskFlags, true)) {
            $recommendations[] = 'Minta tim support mempercepat respon tiket.';
        }
        if (in_array('no_active_subscription', $riskFlags, true)) {
            $recommendations[] = 'Hubungi operasional manager untuk memperbarui langganan SaaS.';
        }

        if (empty($recommendations)) {
            $recommendations[] = 'Pertahankan aktivitas penggunaan modul tahfizh dan absensi.';
        }

        $healthScore = TenantHealthScore::query()->updateOrCreate(
            [
                'school_id' => $schoolId,
                'score_date' => $dateStr,
            ],
            [
                'score' => $totalScore,
                'status' => $status,
                'summary' => "Skor kesehatan terkalkulasi sebesar {$totalScore}/100. Status sekolah: " . strtoupper($status),
                'risk_flags' => $riskFlags,
                'recommendations' => $recommendations,
            ]
        );

        $components = [
            'usage_activity' => ['name' => 'Aktivitas Penggunaan', 'max' => 20, 'score' => $usageScore, 'explanation' => 'Konsistensi input mutabaah siswa.'],
            'academic_activity' => ['name' => 'Aktivitas Akademik', 'max' => 20, 'score' => $academicScore, 'explanation' => 'Tingkat pencapaian target hafalan tahfizh.'],
            'parent_engagement' => ['name' => 'Keterlibatan Orang Tua', 'max' => 15, 'score' => $parentScore, 'explanation' => 'Adopsi perangkat mobile terdaftar.'],
            'finance_health' => ['name' => 'Stabilitas Keuangan', 'max' => 10, 'score' => $financeScore, 'explanation' => 'Rasio pelunasan tagihan santri.'],
            'support_risk' => ['name' => 'Respon Support', 'max' => 10, 'score' => $supportScore, 'explanation' => 'Tingkat kepatuhan resolusi tiket SLA.'],
            'incident_risk' => ['name' => 'Stabilitas Sistem (Insiden)', 'max' => 10, 'score' => $incidentScore, 'explanation' => 'Jumlah laporan bug / insiden sistem.'],
            'subscription_status' => ['name' => 'Status Langganan SaaS', 'max' => 10, 'score' => $subScore, 'explanation' => 'Keaktifan paket berlangganan sekolah.'],
            'mobile_api_adoption' => ['name' => 'Adopsi Mobile & API', 'max' => 5, 'score' => $mobileAdoption, 'explanation' => 'Penggunaan API partner & Mobile.'],
        ];

        foreach ($components as $key => $comp) {
            TenantHealthScoreComponent::query()->updateOrCreate(
                [
                    'tenant_health_score_id' => $healthScore->id,
                    'component_key' => $key,
                ],
                [
                    'name' => $comp['name'],
                    'max_score' => $comp['max'],
                    'score' => $comp['score'],
                    'explanation' => $comp['explanation'],
                ]
            );
        }

        return $healthScore;
    }

    public function resolveStatus(int $score): string
    {
        if ($score >= 80) {
            return 'healthy';
        }
        if ($score >= 60) {
            return 'watch';
        }
        if ($score >= 40) {
            return 'risk';
        }
        return 'critical';
    }
}
