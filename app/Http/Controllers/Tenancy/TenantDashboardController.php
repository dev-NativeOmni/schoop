<?php

namespace App\Http\Controllers\Tenancy;

use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
use App\Models\School;
use App\Models\Student;
use App\Models\TenantAuditLog;
use App\Models\TenantModule;
use App\Models\TenantSetting;
use App\Models\UserSchoolMembership;
use App\Services\Tenancy\TenantContextService;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class TenantDashboardController extends Controller
{
    public function index(TenantContextService $contextService): View
    {
        $school = $contextService->activeSchool();
        if (!$school) {
            abort(404, 'Sekolah tidak ditemukan atau belum diset.');
        }

        // ⚡ Bolt: Cache tenant statistics per school for 15 minutes to reduce database load.
        // Impact: Reduces 4 unindexed full-table count queries to 0 for cached hits.
        $stats = Cache::remember('tenant.dashboard.stats.school.' . $school->id, now()->addMinutes(15), function () use ($school) {
            return [
                'students_count' => Student::query()->where('school_id', $school->id)->count(),
                'classrooms_count' => ClassRoom::query()->where('school_id', $school->id)->count(),
                'memberships_count' => UserSchoolMembership::query()->where('school_id', $school->id)->count(),
                'modules_enabled_count' => TenantModule::query()->where('school_id', $school->id)->where('is_enabled', true)->count(),
            ];
        });

        $modules = TenantModule::query()->where('school_id', $school->id)->orderBy('module_key')->get();
        $settings = TenantSetting::query()->where('school_id', $school->id)->orderBy('setting_key')->get();
        $auditLogs = TenantAuditLog::query()
            ->with('user')
            ->where('school_id', $school->id)
            ->latest()
            ->take(5)
            ->get();

        return view('tenancy.dashboard', compact('school', 'stats', 'modules', 'settings', 'auditLogs'));
    }
}
