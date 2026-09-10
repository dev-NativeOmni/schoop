<?php

namespace App\Services\Tenancy;

use App\Models\School;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TenantDataBackfillService
{
    public function backfill(bool $dryRun = false): array
    {
        $defaultSchoolId = School::query()->orderBy('id')->value('id');

        if (! $defaultSchoolId) {
            return ['schools' => 0];
        }

        $tables = [
            'tahfizh_targets',
            'hafalan_records',
            'tahfizh_debts',
            'mutabaah_categories',
            'mutabaah_activities',
            'mutabaah_records',
            'attendance_qr_tokens',
            'attendance_sessions',
            'attendance_records',
            'tahsin_levels',
            'tahsin_skills',
            'tahsin_student_profiles',
            'tahsin_assessments',
            'finance_fee_categories',
            'finance_fee_items',
            'student_bills',
            'student_payments',
            'finance_ledger_entries',
            'boarding_dormitories',
            'boarding_rooms',
            'boarding_student_assignments',
            'boarding_leave_requests',
            'boarding_health_logs',
            'boarding_discipline_logs',
            'boarding_roll_call_sessions',
            'boarding_roll_call_records',
        ];

        $result = [];

        foreach ($tables as $table) {
            if (! Schema::hasTable($table) || ! Schema::hasColumn($table, 'school_id')) {
                $result[$table] = 0;

                continue;
            }

            $count = DB::table($table)->whereNull('school_id')->count();
            $result[$table] = $count;

            if (! $dryRun && $count > 0) {
                DB::table($table)->whereNull('school_id')->update([
                    'school_id' => $defaultSchoolId,
                ]);
            }
        }

        // Backfill user-school memberships
        $usersWithSchool = DB::table('users')->whereNotNull('school_id')->get();
        $membershipCount = 0;
        foreach ($usersWithSchool as $user) {
            $exists = DB::table('user_school_memberships')
                ->where('user_id', $user->id)
                ->where('school_id', $user->school_id)
                ->exists();

            if (! $exists) {
                $membershipCount++;
                if (! $dryRun) {
                    DB::table('user_school_memberships')->insert([
                        'user_id' => $user->id,
                        'school_id' => $user->school_id,
                        'role_id' => $user->role_id ?? null,
                        'membership_status' => 'active',
                        'is_default' => true,
                        'joined_at' => now(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
        $result['user_school_memberships_backfilled'] = $membershipCount;

        return $result;
    }
}
