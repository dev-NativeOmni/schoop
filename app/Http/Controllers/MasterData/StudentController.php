<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Http\Requests\MasterData\StoreStudentRequest;
use App\Http\Requests\MasterData\UpdateStudentRequest;
use App\Models\ClassRoom;
use App\Models\ParentProfile;
use App\Models\Role;
use App\Models\School;
use App\Models\Student;
use App\Models\User;
use App\Services\Billing\PlanLimitService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class StudentController extends Controller
{
    public function index(): View
    {
        $query = Student::query()->with(['school', 'classRoom', 'user', 'parents.user']);

        if (! auth()->user()->isSuperAdmin()) {
            $query->where(['school_id' => auth()->user()->school_id]);
        }

        $students = $query->latest()->paginate(10);

        return view('master-data.students.index', compact('students'));
    }

    public function create(): View
    {
        $isSuperAdmin = auth()->user()->isSuperAdmin();
        $schoolId = auth()->user()->school_id;

        $schools = $isSuperAdmin
            ? School::query()->where(['is_active' => true])->orderByRaw('name')->get()
            : collect([auth()->user()->school]);

        $classRoomsQuery = ClassRoom::query()->where(['is_active' => true]);
        if (! $isSuperAdmin) {
            $classRoomsQuery->where(['school_id' => $schoolId]);
        }
        $classRooms = $classRoomsQuery->orderByRaw('name')->get();

        $parentsQuery = ParentProfile::query()->with('user');
        if (! $isSuperAdmin) {
            $parentsQuery->where(['school_id' => $schoolId]);
        }
        $parents = $parentsQuery->get()->sortBy(fn ($parent) => $parent->user?->name);

        return view('master-data.students.create', [
            'schools' => $schools,
            'classRooms' => $classRooms,
            'parents' => $parents,
        ]);
    }

    public function store(StoreStudentRequest $request): RedirectResponse
    {
        $schoolId = auth()->user()->isSuperAdmin() ? $request->integer('school_id') : auth()->user()->school_id;
        $school = School::query()->findOrFail($schoolId);

        $planLimitService = app(PlanLimitService::class);
        if (! $planLimitService->isWithinLimit($school, 'max_students', 1)) {
            if (method_exists(auth()->user(), 'hasRole') && auth()->user()->hasRole(['super_admin', 'admin', 'admin_sekolah'])) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Limit jumlah santri pada plan ini sudah tercapai.');
            }
            abort(403, 'Limit jumlah santri pada plan ini sudah tercapai.');
        }

        DB::transaction(function () use ($request, $schoolId): void {
            $userId = null;

            if ($request->boolean('create_login_account')) {
                $role = Role::query()->where(['name' => 'student'])->firstOrFail();

                $user = User::query()->create([
                    'role_id' => $role->id,
                    'school_id' => $schoolId,
                    'name' => $request->input('name') ?: $request->string('full_name'),
                    'username' => $request->input('username'),
                    'email' => $request->input('email'),
                    'phone' => $request->input('phone'),
                    'password' => Hash::make($request->input('password')),
                    'is_active' => $request->boolean('is_active'),
                ]);

                $userId = $user->id;
            }

            $student = Student::query()->create([
                'school_id' => $schoolId,
                'user_id' => $userId,
                'class_room_id' => $request->input('class_room_id'),
                'student_number' => $request->input('student_number'),
                'nisn' => $request->input('nisn'),
                'full_name' => $request->string('full_name'),
                'nickname' => $request->input('nickname'),
                'gender' => $request->input('gender'),
                'birth_place' => $request->input('birth_place'),
                'birth_date' => $request->input('birth_date'),
                'address' => $request->input('address'),
                'phone' => $request->input('phone'),
                'program_type' => $request->input('program_type'),
                'is_active' => $request->boolean('is_active'),
            ]);

            $student->parents()->sync($request->input('parent_profile_ids', []));
        });

        // Refresh usage after creation
        $planLimitService->refreshUsage($school);

        return redirect()
            ->route('master-data.students.index')
            ->with('success', 'Data santri berhasil dibuat.');
    }

    public function show(Student $student): View
    {
        if (! auth()->user()->isSuperAdmin() && $student->school_id !== auth()->user()->school_id) {
            abort(403, 'Anda tidak memiliki akses ke data santri ini.');
        }

        $student->load(['school', 'classRoom', 'user', 'parents.user']);

        return view('master-data.students.show', compact('student'));
    }

    public function edit(Student $student): View
    {
        if (! auth()->user()->isSuperAdmin() && $student->school_id !== auth()->user()->school_id) {
            abort(403, 'Anda tidak memiliki akses ke data santri ini.');
        }

        $student->load(['school', 'classRoom', 'user', 'parents']);
        $isSuperAdmin = auth()->user()->isSuperAdmin();
        $schoolId = auth()->user()->school_id;

        $schools = $isSuperAdmin
            ? School::query()->where(['is_active' => true])->orderByRaw('name')->get()
            : collect([auth()->user()->school]);

        $classRoomsQuery = ClassRoom::query()->where(['is_active' => true]);
        if (! $isSuperAdmin) {
            $classRoomsQuery->where(['school_id' => $schoolId]);
        }
        $classRooms = $classRoomsQuery->orderByRaw('name')->get();

        $parentsQuery = ParentProfile::query()->with('user');
        if (! $isSuperAdmin) {
            $parentsQuery->where(['school_id' => $schoolId]);
        }
        $parents = $parentsQuery->get()->sortBy(fn ($parent) => $parent->user?->name);

        return view('master-data.students.edit', [
            'student' => $student,
            'schools' => $schools,
            'classRooms' => $classRooms,
            'parents' => $parents,
            'selectedParents' => $student->parents->pluck('id')->all(),
        ]);
    }

    public function update(UpdateStudentRequest $request, Student $student): RedirectResponse
    {
        if (! auth()->user()->isSuperAdmin() && $student->school_id !== auth()->user()->school_id) {
            abort(403, 'Anda tidak memiliki akses ke data santri ini.');
        }

        DB::transaction(function () use ($request, $student): void {
            $schoolId = auth()->user()->isSuperAdmin() ? $request->integer('school_id') : auth()->user()->school_id;

            if ($student->user) {
                $student->user->update([
                    'school_id' => $schoolId,
                    'name' => $request->input('name') ?: $request->string('full_name'),
                    'username' => $request->input('username'),
                    'email' => $request->input('email'),
                    'phone' => $request->input('phone'),
                    'is_active' => $request->boolean('is_active'),
                ]);

                if ($request->filled('password')) {
                    $student->user->update([
                        'password' => Hash::make($request->string('password')),
                    ]);
                }
            }

            $student->update([
                'school_id' => $schoolId,
                'class_room_id' => $request->input('class_room_id'),
                'student_number' => $request->input('student_number'),
                'nisn' => $request->input('nisn'),
                'full_name' => $request->string('full_name'),
                'nickname' => $request->input('nickname'),
                'gender' => $request->input('gender'),
                'birth_place' => $request->input('birth_place'),
                'birth_date' => $request->input('birth_date'),
                'address' => $request->input('address'),
                'phone' => $request->input('phone'),
                'program_type' => $request->input('program_type'),
                'is_active' => $request->boolean('is_active'),
            ]);

            $student->parents()->sync($request->input('parent_profile_ids', []));
        });

        return redirect()
            ->route('master-data.students.index')
            ->with('success', 'Data santri berhasil diperbarui.');
    }

    public function destroy(Student $student): RedirectResponse
    {
        if (! auth()->user()->isSuperAdmin() && $student->school_id !== auth()->user()->school_id) {
            abort(403, 'Anda tidak memiliki akses ke data santri ini.');
        }

        DB::transaction(function () use ($student): void {
            $user = $student->user;
            $student->delete();

            if ($user) {
                $user->delete();
            }
        });

        return redirect()
            ->route('master-data.students.index')
            ->with('success', 'Data santri berhasil dihapus.');
    }
}
