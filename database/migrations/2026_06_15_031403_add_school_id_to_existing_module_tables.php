<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $tenantTables = [
        'quran_juzs' => false,
        'quran_surahs' => false,
        'mushaf_pages' => false,
        'tahfizh_targets' => true,
        'hafalan_records' => true,
        'tahfizh_debts' => true,
        'mutabaah_categories' => true,
        'mutabaah_activities' => true,
        'mutabaah_records' => true,
        'attendance_qr_tokens' => true,
        'attendance_sessions' => true,
        'attendance_records' => true,
        'tahsin_levels' => true,
        'tahsin_skills' => true,
        'tahsin_student_profiles' => true,
        'tahsin_assessments' => true,
        'finance_fee_categories' => true,
        'finance_fee_items' => true,
        'student_bills' => true,
        'student_payments' => true,
        'finance_ledger_entries' => true,
        'boarding_dormitories' => true,
        'boarding_rooms' => true,
        'boarding_student_assignments' => true,
        'boarding_leave_requests' => true,
        'boarding_health_logs' => true,
        'boarding_discipline_logs' => true,
        'boarding_roll_call_sessions' => true,
        'boarding_roll_call_records' => true,
    ];

    public function up(): void
    {
        foreach ($this->tenantTables as $tableName => $shouldHaveSchoolId) {
            if (! $shouldHaveSchoolId) {
                continue;
            }

            if (! Schema::hasTable($tableName)) {
                continue;
            }

            if (Schema::hasColumn($tableName, 'school_id')) {
                continue;
            }

            Schema::table($tableName, function (Blueprint $table): void {
                $table->foreignId('school_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('schools')
                    ->nullOnDelete();

                $table->index('school_id');
            });
        }
    }

    public function down(): void
    {
        foreach ($this->tenantTables as $tableName => $shouldHaveSchoolId) {
            if (! $shouldHaveSchoolId) {
                continue;
            }

            if (! Schema::hasTable($tableName)) {
                continue;
            }

            if (! Schema::hasColumn($tableName, 'school_id')) {
                continue;
            }

            Schema::table($tableName, function (Blueprint $table): void {
                $table->dropConstrainedForeignId('school_id');
            });
        }
    }
};
