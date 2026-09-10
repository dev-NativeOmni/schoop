<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TenantHealthCheckCommand extends Command
{
    protected $signature = 'app:tenant-health-check';

    protected $description = 'Check tenant data isolation readiness.';

    public function handle(): int
    {
        $tables = [
            'users',
            'schools',
            'user_school_memberships',
            'tenant_settings',
            'tenant_modules',
            'tenant_audit_logs',
        ];

        foreach ($tables as $table) {
            if (! Schema::hasTable($table)) {
                $this->error("Missing table: {$table}");

                return self::FAILURE;
            }

            $this->info("OK table: {$table}");
        }

        $tenantTables = [
            'students',
            'class_rooms',
            'hafalan_records',
            'mutabaah_records',
            'attendance_records',
            'tahsin_assessments',
            'student_bills',
            'boarding_student_assignments',
        ];

        foreach ($tenantTables as $table) {
            if (! Schema::hasTable($table)) {
                continue;
            }

            if (! Schema::hasColumn($table, 'school_id')) {
                $this->warn("Tenant column missing: {$table}.school_id");

                continue;
            }

            $nullCount = DB::table($table)->whereNull('school_id')->count();

            if ($nullCount > 0) {
                $this->warn("{$table}: {$nullCount} rows with NULL school_id");
            } else {
                $this->info("OK tenant scope: {$table}");
            }
        }

        return self::SUCCESS;
    }
}
