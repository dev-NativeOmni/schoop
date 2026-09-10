<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\School;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::query()->with(['role', 'school']);

        // Search filter
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Role filter
        if ($roleId = $request->input('role_id')) {
            $query->where(['role_id' => $roleId]);
        }

        // School filter
        if ($schoolId = $request->input('school_id')) {
            if ($schoolId === 'null') {
                $query->whereNull('school_id');
            } else {
                $query->where(['school_id' => $schoolId]);
            }
        }

        $users = $query->latest()->paginate(15)->withQueryString();

        return view('master-data.users.index', [
            'users' => $users,
            'roles' => Role::query()->orderByRaw('name')->get(),
            'schools' => School::query()->where(['is_active' => true])->orderByRaw('name')->get(),
        ]);
    }

    public function create(): View
    {
        return view('master-data.users.create', [
            'roles' => Role::query()->orderByRaw('name')->get(),
            'schools' => School::query()->where(['is_active' => true])->orderByRaw('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'role_id' => ['required', 'exists:roles,id'],
            'school_id' => ['nullable', 'exists:schools,id'],
            'phone' => ['nullable', 'string', 'max:20'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $role = Role::findOrFail($validated['role_id']);
        // If role is super_admin, school_id must be null
        $schoolId = $role->name === 'super_admin' ? null : $validated['school_id'];

        User::create([
            'role_id' => $validated['role_id'],
            'school_id' => $schoolId,
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'is_active' => $request->has('is_active') ? $request->boolean('is_active') : true,
        ]);

        return redirect()
            ->route('master-data.users.index')
            ->with('success', 'User berhasil dibuat.');
    }

    public function edit(User $user): View
    {
        return view('master-data.users.edit', [
            'user' => $user,
            'roles' => Role::query()->orderByRaw('name')->get(),
            'schools' => School::query()->where(['is_active' => true])->orderByRaw('name')->get(),
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8'],
            'role_id' => ['required', 'exists:roles,id'],
            'school_id' => ['nullable', 'exists:schools,id'],
            'phone' => ['nullable', 'string', 'max:20'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $role = Role::findOrFail($validated['role_id']);
        $schoolId = $role->name === 'super_admin' ? null : $validated['school_id'];

        $userData = [
            'role_id' => $validated['role_id'],
            'school_id' => $schoolId,
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'is_active' => $request->has('is_active') ? $request->boolean('is_active') : false,
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($validated['password']);
        }

        $user->update($userData);

        return redirect()
            ->route('master-data.users.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if (auth()->id() === $user->id) {
            return redirect()
                ->route('master-data.users.index')
                ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();

        return redirect()
            ->route('master-data.users.index')
            ->with('success', 'User berhasil dihapus.');
    }
}
