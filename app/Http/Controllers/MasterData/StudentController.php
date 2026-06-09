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
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class StudentController extends Controller
{
    public function index(): View
    {
        $students = Student::query()
            ->with(['school', 'classRoom', 'user', 'parents.user'])
            ->latest()
            ->paginate(10);

        return view('master-data.students.index', compact('students'));
    }

    public function create(): View
    {
        return view('master-data.students.create', [
            'schools' => School::query()->where('is_active', true)->orderBy('name')->get(),
            'classRooms' => ClassRoom::query()->where('is_active', true)->orderBy('name')->get(),
            'parents' => ParentProfile::query()->with('user')->get()->sortBy('user.name'),
        ]);
    }

    public function store(StoreStudentRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request): void {
            $userId = null;

            if ($request->boolean('create_login_account')) {
                $role = Role::query()->where('name', 'student')->firstOrFail();

                $user = User::query()->create([
                    'role_id' => $role->id,
                    'school_id' => $request->integer('school_id'),
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
                'school_id' => $request->integer('school_id'),
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

        return redirect()
            ->route('master-data.students.index')
            ->with('success', 'Data santri berhasil dibuat.');
    }

    public function show(Student $student): View
    {
        $student->load(['school', 'classRoom', 'user', 'parents.user']);

        return view('master-data.students.show', compact('student'));
    }

    public function edit(Student $student): View
    {
        $student->load(['school', 'classRoom', 'user', 'parents']);

        return view('master-data.students.edit', [
            'student' => $student,
            'schools' => School::query()->where('is_active', true)->orderBy('name')->get(),
            'classRooms' => ClassRoom::query()->where('is_active', true)->orderBy('name')->get(),
            'parents' => ParentProfile::query()->with('user')->get()->sortBy('user.name'),
            'selectedParents' => $student->parents->pluck('id')->all(),
        ]);
    }

    public function update(UpdateStudentRequest $request, Student $student): RedirectResponse
    {
        DB::transaction(function () use ($request, $student): void {
            if ($student->user) {
                $student->user->update([
                    'school_id' => $request->integer('school_id'),
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
                'school_id' => $request->integer('school_id'),
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
