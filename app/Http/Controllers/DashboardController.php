<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\School;
use App\Models\Student;
use App\Models\TeacherProfile;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function redirect(Request $request): RedirectResponse
    {
        $user = $request->user();

        return match ($user->role?->name) {
            'super_admin' => redirect()->route('dashboard.super-admin'),
            'admin' => redirect()->route('dashboard.admin'),
            'principal' => redirect()->route('dashboard.kepala-sekolah'),
            'teacher' => redirect()->route('dashboard.teacher'),
            'parent' => redirect()->route('dashboard.parent'),
            'student' => redirect()->route('dashboard.student'),
            'boarding_supervisor' => redirect()->route('boarding.dashboard'),
            'finance' => redirect()->route('finance.reports.dashboard'),
            'cashier' => redirect()->route('cashless.pos.cashier'),
            'merchant' => redirect()->route('cashless.pos.cashier'),
            'support_staff', 'customer_success', 'sales', 'operations_manager' => redirect()->route('saas-ops.dashboard'),
            default => abort(403, 'Role akun tidak dikenali.'),
        };
    }

    public function superAdmin(): View
    {
        // ⚡ Bolt: Cache these global statistics for 15 minutes to prevent heavy count() queries on every dashboard load.
        // Impact: Reduces 4 unindexed full-table count queries to 0 for cached hits.
        $stats = Cache::remember('dashboard.super_admin.stats', now()->addMinutes(15), function () {
            return [
                'total_schools' => School::count(),
                'total_users' => User::count(),
                'total_students' => Student::count(),
                'total_teachers' => TeacherProfile::count(),
            ];
        });

        return view('dashboards.super-admin', compact('stats'));
    }

    public function admin(Request $request): View
    {
        $schoolId = $request->user()->school_id;

        // ⚡ Bolt: Cache admin statistics per school for 15 minutes to reduce database load.
        // Admin and Principal share the same stats, so using the same cache key here.
        $stats = Cache::remember('dashboard.admin_stats.school.'.$schoolId, now()->addMinutes(15), function () use ($schoolId) {
            return [
                'total_classrooms' => ClassRoom::query()->where(['school_id' => $schoolId])->count(),
                'total_students' => Student::query()->where(['school_id' => $schoolId])->count(),
                'total_teachers' => TeacherProfile::query()->where(['school_id' => $schoolId])->count(),
            ];
        });

        return view('dashboards.admin', compact('stats'));
    }

    public function kepalaSekolah(Request $request): View
    {
        $schoolId = $request->user()->school_id;

        // ⚡ Bolt: Cache principal statistics per school for 15 minutes to reduce database load.
        // Reuses the same cache key as Admin to maximize cache hits.
        $stats = Cache::remember('dashboard.admin_stats.school.'.$schoolId, now()->addMinutes(15), function () use ($schoolId) {
            return [
                'total_classrooms' => ClassRoom::query()->where(['school_id' => $schoolId])->count(),
                'total_students' => Student::query()->where(['school_id' => $schoolId])->count(),
                'total_teachers' => TeacherProfile::query()->where(['school_id' => $schoolId])->count(),
            ];
        });

        return view('dashboards.kepala-sekolah', compact('stats'));
    }

    public function teacher(Request $request): View
    {
        $schoolId = $request->user()->school_id;

        // ⚡ Bolt: Cache teacher statistics per school for 15 minutes to reduce database load.
        $stats = Cache::remember('dashboard.teacher_stats.school.'.$schoolId, now()->addMinutes(15), function () use ($schoolId) {
            return [
                'total_classrooms' => ClassRoom::query()->where(['school_id' => $schoolId])->count(),
                'total_students' => Student::query()->where(['school_id' => $schoolId])->count(),
            ];
        });

        return view('dashboards.teacher', compact('stats'));
    }

    public function parent(): View
    {
        return view('dashboards.parent');
    }

    public function student(): View
    {
        return view('dashboards.student');
    }

    /**
     * Update or delete the global system logo.
     */
    public function updateLogo(Request $request): RedirectResponse
    {
        $request->validate([
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($request->input('delete_logo') == '1') {
            if (Storage::disk('public')->exists('system/logo.png')) {
                Storage::disk('public')->delete('system/logo.png');
            }

            return redirect()->back()->with('success', 'Logo kustom global berhasil dihapus.');
        }

        if ($request->hasFile('logo')) {
            if (Storage::disk('public')->exists('system/logo.png')) {
                Storage::disk('public')->delete('system/logo.png');
            }

            $request->file('logo')->storeAs('system', 'logo.png', 'public');

            return redirect()->back()->with('success', 'Logo kustom global berhasil diperbarui.');
        }

        return redirect()->back()->with('error', 'Tidak ada file logo yang diunggah.');
    }
}
