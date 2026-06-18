<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Http\Requests\MasterData\StoreParentRequest;
use App\Http\Requests\MasterData\UpdateParentRequest;
use App\Models\ParentProfile;
use App\Models\Role;
use App\Models\School;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class ParentController extends Controller
{
    public function index(): View
    {
        $query = ParentProfile::query()->with(['user', 'school', 'students']);
        
        if (!auth()->user()->isSuperAdmin()) {
            $query->where(['school_id' => auth()->user()->school_id]);
        }

        $parents = $query->latest()->paginate(10);

        return view('master-data.parents.index', compact('parents'));
    }

    public function create(): View
    {
        $isSuperAdmin = auth()->user()->isSuperAdmin();
        $schoolId = auth()->user()->school_id;

        $schools = $isSuperAdmin
            ? School::query()->where(['is_active' => true])->orderByRaw('name')->get()
            : collect([auth()->user()->school]);

        $studentsQuery = Student::query();
        if (!$isSuperAdmin) {
            $studentsQuery->where(['school_id' => $schoolId]);
        }
        $students = $studentsQuery->orderByRaw('full_name')->get();

        return view('master-data.parents.create', [
            'schools' => $schools,
            'students' => $students,
        ]);
    }

    public function store(StoreParentRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request): void {
            $role = Role::query()->where(['name' => 'parent'])->firstOrFail();
            $schoolId = auth()->user()->isSuperAdmin() ? $request->integer('school_id') : auth()->user()->school_id;

            $user = User::query()->create([
                'role_id' => $role->id,
                'school_id' => $schoolId,
                'name' => $request->string('name'),
                'username' => $request->string('username'),
                'email' => $request->string('email'),
                'phone' => $request->input('phone'),
                'password' => Hash::make($request->string('password')),
                'password_plain' => \Illuminate\Support\Facades\Crypt::encryptString($request->string('password')),
                'is_active' => $request->boolean('is_active'),
            ]);

            $parent = ParentProfile::query()->create([
                'user_id' => $user->id,
                'school_id' => $schoolId,
                'relationship' => $request->input('relationship'),
                'occupation' => $request->input('occupation'),
                'address' => $request->input('address'),
                'is_active' => $request->boolean('is_active'),
            ]);

            $parent->students()->sync($request->input('student_ids', []));
        });

        return redirect()
            ->route('master-data.parents.index')
            ->with('success', 'Data orang tua berhasil dibuat.');
    }

    public function show(ParentProfile $parent): View
    {
        if (!auth()->user()->isSuperAdmin() && $parent->school_id !== auth()->user()->school_id) {
            abort(403, 'Anda tidak memiliki akses ke data orang tua ini.');
        }

        $parent->load(['user', 'school', 'students']);

        return view('master-data.parents.show', compact('parent'));
    }

    public function edit(ParentProfile $parent): View
    {
        if (!auth()->user()->isSuperAdmin() && $parent->school_id !== auth()->user()->school_id) {
            abort(403, 'Anda tidak memiliki akses ke data orang tua ini.');
        }

        $parent->load(['user', 'school', 'students']);
        $isSuperAdmin = auth()->user()->isSuperAdmin();
        $schoolId = auth()->user()->school_id;

        $schools = $isSuperAdmin
            ? School::query()->where(['is_active' => true])->orderByRaw('name')->get()
            : collect([auth()->user()->school]);

        $studentsQuery = Student::query();
        if (!$isSuperAdmin) {
            $studentsQuery->where(['school_id' => $schoolId]);
        }
        $students = $studentsQuery->orderByRaw('full_name')->get();

        return view('master-data.parents.edit', [
            'parent' => $parent,
            'schools' => $schools,
            'students' => $students,
            'selectedStudents' => $parent->students->pluck('id')->all(),
        ]);
    }

    public function update(UpdateParentRequest $request, ParentProfile $parent): RedirectResponse
    {
        if (!auth()->user()->isSuperAdmin() && $parent->school_id !== auth()->user()->school_id) {
            abort(403, 'Anda tidak memiliki akses ke data orang tua ini.');
        }

        DB::transaction(function () use ($request, $parent): void {
            $schoolId = auth()->user()->isSuperAdmin() ? $request->integer('school_id') : auth()->user()->school_id;

            $parent->user->update([
                'school_id' => $schoolId,
                'name' => $request->string('name'),
                'username' => $request->string('username'),
                'email' => $request->string('email'),
                'phone' => $request->input('phone'),
                'is_active' => $request->boolean('is_active'),
            ]);

            if ($request->filled('password')) {
                $parent->user->update([
                    'password' => Hash::make($request->string('password')),
                    'password_plain' => \Illuminate\Support\Facades\Crypt::encryptString($request->string('password')),
                ]);
            }

            $parent->update([
                'school_id' => $schoolId,
                'relationship' => $request->input('relationship'),
                'occupation' => $request->input('occupation'),
                'address' => $request->input('address'),
                'is_active' => $request->boolean('is_active'),
            ]);

            $parent->students()->sync($request->input('student_ids', []));
        });

        return redirect()
            ->route('master-data.parents.index')
            ->with('success', 'Data orang tua berhasil diperbarui.');
    }

    public function destroy(ParentProfile $parent): RedirectResponse
    {
        if (!auth()->user()->isSuperAdmin() && $parent->school_id !== auth()->user()->school_id) {
            abort(403, 'Anda tidak memiliki akses ke data orang tua ini.');
        }

        $parent->user?->delete();

        return redirect()
            ->route('master-data.parents.index')
            ->with('success', 'Data orang tua berhasil dihapus.');
    }
}
