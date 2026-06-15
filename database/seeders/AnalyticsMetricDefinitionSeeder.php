<?php

namespace Database\Seeders;

use App\Models\AnalyticsMetricDefinition;
use Illuminate\Database\Seeder;

class AnalyticsMetricDefinitionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $metrics = [
            [
                'metric_key' => 'tenant.active_count',
                'name' => 'Active Tenants',
                'category' => 'tenant',
                'description' => 'Jumlah sekolah/tenant yang berstatus aktif.',
                'formula' => 'count(schools.id) where is_active = true',
                'unit' => 'count',
                'aggregation_type' => 'sum',
                'is_sensitive' => false,
            ],
            [
                'metric_key' => 'tenant.health_score',
                'name' => 'Tenant Health Score',
                'category' => 'tenant',
                'description' => 'Skor kesehatan gabungan dari aktivitas penggunaan dan stabilitas finansial tenant.',
                'formula' => 'weighted_average(components)',
                'unit' => 'score',
                'aggregation_type' => 'avg',
                'is_sensitive' => false,
            ],
            [
                'metric_key' => 'usage.active_users',
                'name' => 'Active Users',
                'category' => 'usage',
                'description' => 'Jumlah user yang aktif login dalam periode tertentu.',
                'formula' => 'count(distinct user_id)',
                'unit' => 'count',
                'aggregation_type' => 'sum',
                'is_sensitive' => false,
            ],
            [
                'metric_key' => 'academic.hafalan_records',
                'name' => 'Hafalan Records',
                'category' => 'academic',
                'description' => 'Jumlah setoran hafalan santri yang dicatat oleh guru.',
                'formula' => 'count(hafalan_records.id)',
                'unit' => 'count',
                'aggregation_type' => 'sum',
                'is_sensitive' => false,
            ],
            [
                'metric_key' => 'academic.tahfizh_achievement_rate',
                'name' => 'Tahfizh Achievement Rate',
                'category' => 'academic',
                'description' => 'Persentase pencapaian hafalan riil terhadap target yang ditetapkan.',
                'formula' => '(actual_lines / target_lines) * 100',
                'unit' => 'percent',
                'aggregation_type' => 'avg',
                'is_sensitive' => false,
            ],
            [
                'metric_key' => 'academic.students_behind_target',
                'name' => 'Students Behind Target',
                'category' => 'academic',
                'description' => 'Jumlah santri yang progres hafalannya tertinggal dari target.',
                'formula' => 'count(students) where actual_lines < target_lines',
                'unit' => 'count',
                'aggregation_type' => 'sum',
                'is_sensitive' => true,
            ],
            [
                'metric_key' => 'mutabaah.completion_rate',
                'name' => 'Mutabaah Completion Rate',
                'category' => 'academic',
                'description' => 'Persentase pengisian mutabaah yaumiyah yang diselesaikan.',
                'formula' => '(completed_records / total_activities) * 100',
                'unit' => 'percent',
                'aggregation_type' => 'avg',
                'is_sensitive' => false,
            ],
            [
                'metric_key' => 'attendance.attendance_rate',
                'name' => 'Attendance Rate',
                'category' => 'operational',
                'description' => 'Persentase kehadiran santri.',
                'formula' => '(present_records / total_attendance) * 100',
                'unit' => 'percent',
                'aggregation_type' => 'avg',
                'is_sensitive' => false,
            ],
            [
                'metric_key' => 'attendance.late_rate',
                'name' => 'Late Rate',
                'category' => 'operational',
                'description' => 'Persentase keterlambatan santri.',
                'formula' => '(late_records / total_attendance) * 100',
                'unit' => 'percent',
                'aggregation_type' => 'avg',
                'is_sensitive' => false,
            ],
            [
                'metric_key' => 'tahsin.average_score',
                'name' => 'Tahsin Average Score',
                'category' => 'academic',
                'description' => 'Nilai rata-rata penilaian tahsin siswa.',
                'formula' => 'avg(tahsin_assessment_items.score)',
                'unit' => 'score',
                'aggregation_type' => 'avg',
                'is_sensitive' => false,
            ],
            [
                'metric_key' => 'finance.outstanding_total',
                'name' => 'Outstanding Total',
                'category' => 'finance',
                'description' => 'Total tagihan yang belum terbayar oleh santri.',
                'formula' => 'sum(bills.balance_amount)',
                'unit' => 'currency',
                'aggregation_type' => 'sum',
                'is_sensitive' => true,
            ],
            [
                'metric_key' => 'cashless.purchase_total',
                'name' => 'Cashless Purchase Total',
                'category' => 'cashless',
                'description' => 'Total belanja cashless di merchant kantin.',
                'formula' => 'sum(transactions.amount) where type = purchase',
                'unit' => 'currency',
                'aggregation_type' => 'sum',
                'is_sensitive' => true,
            ],
            [
                'metric_key' => 'support.sla_breach_rate',
                'name' => 'SLA Breach Rate',
                'category' => 'support',
                'description' => 'Persentase tiket support yang melanggar SLA.',
                'formula' => '(breached_tickets / total_tickets) * 100',
                'unit' => 'percent',
                'aggregation_type' => 'avg',
                'is_sensitive' => false,
            ],
            [
                'metric_key' => 'mobile.active_devices',
                'name' => 'Active Mobile Devices',
                'category' => 'mobile',
                'description' => 'Jumlah perangkat mobile aktif yang terhubung.',
                'formula' => 'count(mobile_devices.id) where is_active = true',
                'unit' => 'count',
                'aggregation_type' => 'sum',
                'is_sensitive' => false,
            ],
            [
                'metric_key' => 'api.error_rate',
                'name' => 'API Error Rate',
                'category' => 'api',
                'description' => 'Persentase kegagalan request external API.',
                'formula' => '(error_requests / total_requests) * 100',
                'unit' => 'percent',
                'aggregation_type' => 'avg',
                'is_sensitive' => false,
            ],
        ];

        foreach ($metrics as $index => $metric) {
            AnalyticsMetricDefinition::query()->updateOrCreate(
                ['metric_key' => $metric['metric_key']],
                $metric + [
                    'is_active' => true,
                    'sort_order' => ($index + 1) * 10,
                ]
            );
        }
    }
}
