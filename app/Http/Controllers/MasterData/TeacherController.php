<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Http\Requests\MasterData\StoreTeacherRequest;
use App\Http\Requests\MasterData\UpdateTeacherRequest;
use App\Models\Role;
use App\Models\School;
use App\Models\TeacherProfile;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class TeacherController extends Controller
{
    public function index(): View
    {
        $query = TeacherProfile::query()->with(['user', 'school']);

        if (! auth()->user()->isSuperAdmin()) {
            $query->where(['school_id' => auth()->user()->school_id]);
        }

        $teachers = $query->latest()->paginate(10);

        return view('master-data.teachers.index', compact('teachers'));
    }

    public function create(): View
    {
        $schools = auth()->user()->isSuperAdmin()
            ? School::query()->where(['is_active' => true])->orderByRaw('name')->get()
            : collect([auth()->user()->school]);

        return view('master-data.teachers.create', [
            'schools' => $schools,
        ]);
    }

    public function store(StoreTeacherRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request): void {
            $role = Role::query()->where(['name' => 'teacher'])->firstOrFail();
            $schoolId = auth()->user()->isSuperAdmin() ? $request->integer('school_id') : auth()->user()->school_id;

            $user = User::query()->create([
                'role_id' => $role->id,
                'school_id' => $schoolId,
                'name' => $request->string('name'),
                'username' => $request->string('username'),
                'email' => $request->string('email'),
                'phone' => $request->input('phone'),
                'password' => Hash::make($request->string('password')),
                'is_active' => $request->boolean('is_active'),
            ]);

            TeacherProfile::query()->create([
                'user_id' => $user->id,
                'school_id' => $schoolId,
                'employee_number' => $request->input('employee_number'),
                'specialization' => $request->input('specialization'),
                'address' => $request->input('address'),
                'joined_at' => $request->input('joined_at'),
                'is_active' => $request->boolean('is_active'),
            ]);
        });

        return redirect()
            ->route('master-data.teachers.index')
            ->with('success', 'Data guru berhasil dibuat.');
    }

    public function show(TeacherProfile $teacher): View
    {
        if (! auth()->user()->isSuperAdmin() && $teacher->school_id !== auth()->user()->school_id) {
            abort(403, 'Anda tidak memiliki akses ke data guru ini.');
        }

        $teacher->load(['user', 'school']);

        return view('master-data.teachers.show', compact('teacher'));
    }

    public function edit(TeacherProfile $teacher): View
    {
        if (! auth()->user()->isSuperAdmin() && $teacher->school_id !== auth()->user()->school_id) {
            abort(403, 'Anda tidak memiliki akses ke data guru ini.');
        }

        $teacher->load(['user', 'school']);

        $schools = auth()->user()->isSuperAdmin()
            ? School::query()->where(['is_active' => true])->orderByRaw('name')->get()
            : collect([auth()->user()->school]);

        return view('master-data.teachers.edit', [
            'teacher' => $teacher,
            'schools' => $schools,
        ]);
    }

    public function update(UpdateTeacherRequest $request, TeacherProfile $teacher): RedirectResponse
    {
        if (! auth()->user()->isSuperAdmin() && $teacher->school_id !== auth()->user()->school_id) {
            abort(403, 'Anda tidak memiliki akses ke data guru ini.');
        }

        DB::transaction(function () use ($request, $teacher): void {
            $schoolId = auth()->user()->isSuperAdmin() ? $request->integer('school_id') : auth()->user()->school_id;

            $teacher->user->update([
                'school_id' => $schoolId,
                'name' => $request->string('name'),
                'username' => $request->string('username'),
                'email' => $request->string('email'),
                'phone' => $request->input('phone'),
                'is_active' => $request->boolean('is_active'),
            ]);

            if ($request->filled('password')) {
                $teacher->user->update([
                    'password' => Hash::make($request->string('password')),
                ]);
            }

            $teacher->update([
                'school_id' => $schoolId,
                'employee_number' => $request->input('employee_number'),
                'specialization' => $request->input('specialization'),
                'address' => $request->input('address'),
                'joined_at' => $request->input('joined_at'),
                'is_active' => $request->boolean('is_active'),
            ]);
        });

        return redirect()
            ->route('master-data.teachers.index')
            ->with('success', 'Data guru berhasil diperbarui.');
    }

    public function destroy(TeacherProfile $teacher): RedirectResponse
    {
        if (! auth()->user()->isSuperAdmin() && $teacher->school_id !== auth()->user()->school_id) {
            abort(403, 'Anda tidak memiliki akses ke data guru ini.');
        }

        $teacher->user?->delete();

        return redirect()
            ->route('master-data.teachers.index')
            ->with('success', 'Data guru berhasil dihapus.');
    }
}
