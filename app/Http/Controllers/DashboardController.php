<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\School;
use App\Models\Student;
use App\Models\TeacherProfile;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
            default => abort(403, 'Role akun tidak dikenali.'),
        };
    }

    public function superAdmin(): View
    {
        $stats = [
            'total_schools' => School::count(),
            'total_users' => User::count(),
            'total_students' => Student::count(),
            'total_teachers' => TeacherProfile::count(),
        ];

        return view('dashboards.super-admin', compact('stats'));
    }

    public function admin(Request $request): View
    {
        $schoolId = $request->user()->school_id;

        $stats = [
            'total_classrooms' => ClassRoom::where('school_id', $schoolId)->count(),
            'total_students' => Student::where('school_id', $schoolId)->count(),
            'total_teachers' => TeacherProfile::where('school_id', $schoolId)->count(),
        ];

        return view('dashboards.admin', compact('stats'));
    }

    public function kepalaSekolah(Request $request): View
    {
        $schoolId = $request->user()->school_id;

        $stats = [
            'total_classrooms' => ClassRoom::where('school_id', $schoolId)->count(),
            'total_students' => Student::where('school_id', $schoolId)->count(),
            'total_teachers' => TeacherProfile::where('school_id', $schoolId)->count(),
        ];

        return view('dashboards.kepala-sekolah', compact('stats'));
    }

    public function teacher(Request $request): View
    {
        $schoolId = $request->user()->school_id;

        $stats = [
            'total_classrooms' => ClassRoom::where('school_id', $schoolId)->count(),
            'total_students' => Student::where('school_id', $schoolId)->count(),
        ];

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
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        if ($request->input('delete_logo') == '1') {
            if (\Illuminate\Support\Facades\Storage::disk('public')->exists('system/logo.png')) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete('system/logo.png');
            }
            return redirect()->back()->with('success', 'Logo kustom global berhasil dihapus.');
        }

        if ($request->hasFile('logo')) {
            if (\Illuminate\Support\Facades\Storage::disk('public')->exists('system/logo.png')) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete('system/logo.png');
            }

            $request->file('logo')->storeAs('system', 'logo.png', 'public');

            return redirect()->back()->with('success', 'Logo kustom global berhasil diperbarui.');
        }

        return redirect()->back()->with('error', 'Tidak ada file logo yang diunggah.');
    }
}
