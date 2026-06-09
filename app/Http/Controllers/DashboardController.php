<?php

namespace App\Http\Controllers;

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
        return view('dashboards.super-admin');
    }

    public function admin(): View
    {
        return view('dashboards.admin');
    }

    public function kepalaSekolah(): View
    {
        return view('dashboards.kepala-sekolah');
    }

    public function teacher(): View
    {
        return view('dashboards.teacher');
    }

    public function parent(): View
    {
        return view('dashboards.parent');
    }

    public function student(): View
    {
        return view('dashboards.student');
    }
}
