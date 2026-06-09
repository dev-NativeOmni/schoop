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
        $parents = ParentProfile::query()
            ->with(['user', 'school', 'students'])
            ->latest()
            ->paginate(10);

        return view('master-data.parents.index', compact('parents'));
    }

    public function create(): View
    {
        return view('master-data.parents.create', [
            'schools' => School::query()->where('is_active', true)->orderBy('name')->get(),
            'students' => Student::query()->orderBy('full_name')->get(),
        ]);
    }

    public function store(StoreParentRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request): void {
            $role = Role::query()->where('name', 'parent')->firstOrFail();

            $user = User::query()->create([
                'role_id' => $role->id,
                'school_id' => $request->integer('school_id'),
                'name' => $request->string('name'),
                'username' => $request->string('username'),
                'email' => $request->string('email'),
                'phone' => $request->input('phone'),
                'password' => Hash::make($request->string('password')),
                'is_active' => $request->boolean('is_active'),
            ]);

            $parent = ParentProfile::query()->create([
                'user_id' => $user->id,
                'school_id' => $request->integer('school_id'),
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
        $parent->load(['user', 'school', 'students']);

        return view('master-data.parents.show', compact('parent'));
    }

    public function edit(ParentProfile $parent): View
    {
        $parent->load(['user', 'school', 'students']);

        return view('master-data.parents.edit', [
            'parent' => $parent,
            'schools' => School::query()->where('is_active', true)->orderBy('name')->get(),
            'students' => Student::query()->orderBy('full_name')->get(),
            'selectedStudents' => $parent->students->pluck('id')->all(),
        ]);
    }

    public function update(UpdateParentRequest $request, ParentProfile $parent): RedirectResponse
    {
        DB::transaction(function () use ($request, $parent): void {
            $parent->user->update([
                'school_id' => $request->integer('school_id'),
                'name' => $request->string('name'),
                'username' => $request->string('username'),
                'email' => $request->string('email'),
                'phone' => $request->input('phone'),
                'is_active' => $request->boolean('is_active'),
            ]);

            if ($request->filled('password')) {
                $parent->user->update([
                    'password' => Hash::make($request->string('password')),
                ]);
            }

            $parent->update([
                'school_id' => $request->integer('school_id'),
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
        $parent->user?->delete();

        return redirect()
            ->route('master-data.parents.index')
            ->with('success', 'Data orang tua berhasil dihapus.');
    }
}
