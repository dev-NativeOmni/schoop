# Phase 2 Execution Guide — Master Data Foundation

## 0. Identitas Phase

Dokumen ini adalah instruksi eksekusi untuk AI coding agent di code editor.

Project:

```text
HafizPlus School Platform
```

Produk pertama:

```text
Tahfizh Monitoring App
```

Framework:

```text
Laravel 12
```

Database:

```text
MySQL
```

Project folder:

```text
C:\xampp\htdocs\hafizplus-school-platform
```

Database local:

```text
hafizplus_school_platform
```

Status:

```text
Proyek mandiri, bukan bagian dari HafizPlus 2.0 atau HafizPlus 3.0.
```

---

# 1. Tujuan Phase 2

Phase 2 bertujuan membuat master data dasar agar aplikasi mulai bisa dipakai untuk menyiapkan data sekolah sebelum masuk fitur tahfizh.

Master data yang harus dibuat:

1. Sekolah.
2. Kelas.
3. Guru Tahfidz.
4. Orang Tua.
5. Santri.
6. Relasi Orang Tua dan Santri.
7. Navigasi dashboard sederhana.
8. Validasi input.
9. Pembatasan akses berdasarkan role.

Phase 2 belum membuat input setoran tahfizh.

---

# 2. Aturan Keras untuk AI Agent

AI agent wajib mengikuti batasan berikut.

## 2.1 Jangan Membuat Fitur Setoran Dulu

Pada Phase 2, agent tidak boleh membuat:

1. Input setoran hafalan.
2. Target hafalan.
3. Hutang hafalan.
4. Validasi urutan hafalan.
5. Report bulanan.
6. Report triwulan.
7. Grafik progres.
8. Parent progress detail.
9. Student progress detail.
10. Notifikasi real.
11. Export PDF.
12. Export Excel.

Semua itu masuk fase berikutnya.

---

## 2.2 Jangan Mengubah Struktur Auth Phase 1 Secara Besar

Phase 1 sudah membuat:

1. Login.
2. Logout.
3. Role.
4. Middleware role.
5. Dashboard per role.
6. Seeder akun awal.

Agent tidak boleh membongkar ulang auth kecuali ada error nyata.

---

## 2.3 Jangan Memasang Package Baru

Pada Phase 2, jangan pasang package baru kecuali benar-benar wajib.

Jangan pasang:

1. Livewire.
2. Filament.
3. Breeze.
4. Jetstream.
5. Spatie Permission.
6. Inertia.
7. React.
8. Vue.
9. Excel/PDF package.
10. Payment package.

CRUD Phase 2 cukup memakai Laravel Controller, Form Request, Blade, dan Eloquent.

---

# 3. Target Output Phase 2

Setelah Phase 2 selesai, aplikasi harus punya:

1. Menu navigasi dasar.
2. CRUD Sekolah.
3. CRUD Kelas.
4. CRUD Guru Tahfidz.
5. CRUD Orang Tua.
6. CRUD Santri.
7. Relasi Orang Tua dan Santri.
8. Validasi form.
9. Akses master data hanya untuk:

   * Super Admin.
   * Admin Sekolah.
10. Kepala Sekolah boleh melihat data, tetapi tidak menjadi role input utama.
11. Guru, Orang Tua, dan Santri tidak boleh mengakses master data admin.
12. Dokumentasi Phase 2.

---

# 4. Validasi Awal Sebelum Eksekusi

Jalankan:

```powershell
cd C:\xampp\htdocs\hafizplus-school-platform
php artisan --version
php artisan migrate:status
php artisan route:list
git status
```

Target:

1. Laravel 12 berjalan.
2. Migration Phase 1 sudah aktif.
3. Route login/dashboard tersedia.
4. Working tree bersih atau semua perubahan sudah diketahui.

Jika Phase 1 belum selesai, hentikan eksekusi.

---

# 5. Buat Branch Git Phase 2

Jalankan:

```powershell
git checkout -b phase-2-master-data-foundation
```

Jika branch sudah ada:

```powershell
git checkout phase-2-master-data-foundation
```

---

# 6. Struktur File yang Akan Dibuat

Agent harus membuat atau mengubah file berikut:

```text
app/
├── Http/
│   ├── Controllers/
│   │   └── MasterData/
│   │       ├── SchoolController.php
│   │       ├── ClassRoomController.php
│   │       ├── TeacherController.php
│   │       ├── ParentController.php
│   │       └── StudentController.php
│   └── Requests/
│       └── MasterData/
│           ├── StoreSchoolRequest.php
│           ├── UpdateSchoolRequest.php
│           ├── StoreClassRoomRequest.php
│           ├── UpdateClassRoomRequest.php
│           ├── StoreTeacherRequest.php
│           ├── UpdateTeacherRequest.php
│           ├── StoreParentRequest.php
│           ├── UpdateParentRequest.php
│           ├── StoreStudentRequest.php
│           └── UpdateStudentRequest.php

resources/
├── views/
│   ├── layouts/
│   │   └── app.blade.php
│   └── master-data/
│       ├── schools/
│       │   ├── index.blade.php
│       │   ├── create.blade.php
│       │   ├── edit.blade.php
│       │   └── show.blade.php
│       ├── class-rooms/
│       │   ├── index.blade.php
│       │   ├── create.blade.php
│       │   ├── edit.blade.php
│       │   └── show.blade.php
│       ├── teachers/
│       │   ├── index.blade.php
│       │   ├── create.blade.php
│       │   ├── edit.blade.php
│       │   └── show.blade.php
│       ├── parents/
│       │   ├── index.blade.php
│       │   ├── create.blade.php
│       │   ├── edit.blade.php
│       │   └── show.blade.php
│       └── students/
│           ├── index.blade.php
│           ├── create.blade.php
│           ├── edit.blade.php
│           └── show.blade.php

routes/
└── web.php

docs/
└── phase-2-master-data-foundation.md
```

---

# 7. Buat Controller

Jalankan:

```powershell
php artisan make:controller MasterData/SchoolController --resource
php artisan make:controller MasterData/ClassRoomController --resource
php artisan make:controller MasterData/TeacherController --resource
php artisan make:controller MasterData/ParentController --resource
php artisan make:controller MasterData/StudentController --resource
```

---

# 8. Buat Form Request

Jalankan:

```powershell
php artisan make:request MasterData/StoreSchoolRequest
php artisan make:request MasterData/UpdateSchoolRequest
php artisan make:request MasterData/StoreClassRoomRequest
php artisan make:request MasterData/UpdateClassRoomRequest
php artisan make:request MasterData/StoreTeacherRequest
php artisan make:request MasterData/UpdateTeacherRequest
php artisan make:request MasterData/StoreParentRequest
php artisan make:request MasterData/UpdateParentRequest
php artisan make:request MasterData/StoreStudentRequest
php artisan make:request MasterData/UpdateStudentRequest
```

---

# 9. Form Request Rules

## 9.1 `StoreSchoolRequest`

Buka:

```text
app/Http/Requests/MasterData/StoreSchoolRequest.php
```

Isi:

```php
<?php

namespace App\Http\Requests\MasterData;

use Illuminate\Foundation\Http\FormRequest;

class StoreSchoolRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole(['super_admin']) ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', 'unique:schools,code'],
            'npsn' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string'],
            'primary_color' => ['nullable', 'string', 'max:20'],
            'secondary_color' => ['nullable', 'string', 'max:20'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
```

---

## 9.2 `UpdateSchoolRequest`

Buka:

```text
app/Http/Requests/MasterData/UpdateSchoolRequest.php
```

Isi:

```php
<?php

namespace App\Http\Requests\MasterData;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSchoolRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole(['super_admin']) ?? false;
    }

    public function rules(): array
    {
        $school = $this->route('school');

        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('schools', 'code')->ignore($school?->id),
            ],
            'npsn' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string'],
            'primary_color' => ['nullable', 'string', 'max:20'],
            'secondary_color' => ['nullable', 'string', 'max:20'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
```

---

## 9.3 `StoreClassRoomRequest`

Buka:

```text
app/Http/Requests/MasterData/StoreClassRoomRequest.php
```

Isi:

```php
<?php

namespace App\Http\Requests\MasterData;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreClassRoomRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole(['super_admin', 'admin']) ?? false;
    }

    public function rules(): array
    {
        return [
            'school_id' => ['required', 'exists:schools,id'],
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('class_rooms', 'name')
                    ->where('school_id', $this->input('school_id'))
                    ->where('academic_year', $this->input('academic_year')),
            ],
            'level' => ['nullable', 'string', 'max:50'],
            'academic_year' => ['nullable', 'string', 'max:20'],
            'homeroom_teacher_id' => ['nullable', 'exists:users,id'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
```

---

## 9.4 `UpdateClassRoomRequest`

Buka:

```text
app/Http/Requests/MasterData/UpdateClassRoomRequest.php
```

Isi:

```php
<?php

namespace App\Http\Requests\MasterData;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClassRoomRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole(['super_admin', 'admin']) ?? false;
    }

    public function rules(): array
    {
        $classRoom = $this->route('class_room');

        return [
            'school_id' => ['required', 'exists:schools,id'],
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('class_rooms', 'name')
                    ->where('school_id', $this->input('school_id'))
                    ->where('academic_year', $this->input('academic_year'))
                    ->ignore($classRoom?->id),
            ],
            'level' => ['nullable', 'string', 'max:50'],
            'academic_year' => ['nullable', 'string', 'max:20'],
            'homeroom_teacher_id' => ['nullable', 'exists:users,id'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
```

---

## 9.5 `StoreTeacherRequest`

Buka:

```text
app/Http/Requests/MasterData/StoreTeacherRequest.php
```

Isi:

```php
<?php

namespace App\Http\Requests\MasterData;

use Illuminate\Foundation\Http\FormRequest;

class StoreTeacherRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole(['super_admin', 'admin']) ?? false;
    }

    public function rules(): array
    {
        return [
            'school_id' => ['required', 'exists:schools,id'],
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:100', 'unique:users,username'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:30'],
            'password' => ['required', 'string', 'min:8'],
            'employee_number' => ['nullable', 'string', 'max:100', 'unique:teacher_profiles,employee_number'],
            'specialization' => ['nullable', 'string', 'max:100'],
            'address' => ['nullable', 'string'],
            'joined_at' => ['nullable', 'date'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
```

---

## 9.6 `UpdateTeacherRequest`

Buka:

```text
app/Http/Requests/MasterData/UpdateTeacherRequest.php
```

Isi:

```php
<?php

namespace App\Http\Requests\MasterData;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTeacherRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole(['super_admin', 'admin']) ?? false;
    }

    public function rules(): array
    {
        $teacher = $this->route('teacher');
        $user = $teacher?->user;

        return [
            'school_id' => ['required', 'exists:schools,id'],
            'name' => ['required', 'string', 'max:255'],
            'username' => [
                'required',
                'string',
                'max:100',
                Rule::unique('users', 'username')->ignore($user?->id),
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user?->id),
            ],
            'phone' => ['nullable', 'string', 'max:30'],
            'password' => ['nullable', 'string', 'min:8'],
            'employee_number' => [
                'nullable',
                'string',
                'max:100',
                Rule::unique('teacher_profiles', 'employee_number')->ignore($teacher?->id),
            ],
            'specialization' => ['nullable', 'string', 'max:100'],
            'address' => ['nullable', 'string'],
            'joined_at' => ['nullable', 'date'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
```

---

## 9.7 `StoreParentRequest`

Buka:

```text
app/Http/Requests/MasterData/StoreParentRequest.php
```

Isi:

```php
<?php

namespace App\Http\Requests\MasterData;

use Illuminate\Foundation\Http\FormRequest;

class StoreParentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole(['super_admin', 'admin']) ?? false;
    }

    public function rules(): array
    {
        return [
            'school_id' => ['required', 'exists:schools,id'],
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:100', 'unique:users,username'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:30'],
            'password' => ['required', 'string', 'min:8'],
            'relationship' => ['nullable', 'string', 'max:100'],
            'occupation' => ['nullable', 'string', 'max:100'],
            'address' => ['nullable', 'string'],
            'student_ids' => ['nullable', 'array'],
            'student_ids.*' => ['integer', 'exists:students,id'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
```

---

## 9.8 `UpdateParentRequest`

Buka:

```text
app/Http/Requests/MasterData/UpdateParentRequest.php
```

Isi:

```php
<?php

namespace App\Http\Requests\MasterData;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateParentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole(['super_admin', 'admin']) ?? false;
    }

    public function rules(): array
    {
        $parent = $this->route('parent');
        $user = $parent?->user;

        return [
            'school_id' => ['required', 'exists:schools,id'],
            'name' => ['required', 'string', 'max:255'],
            'username' => [
                'required',
                'string',
                'max:100',
                Rule::unique('users', 'username')->ignore($user?->id),
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user?->id),
            ],
            'phone' => ['nullable', 'string', 'max:30'],
            'password' => ['nullable', 'string', 'min:8'],
            'relationship' => ['nullable', 'string', 'max:100'],
            'occupation' => ['nullable', 'string', 'max:100'],
            'address' => ['nullable', 'string'],
            'student_ids' => ['nullable', 'array'],
            'student_ids.*' => ['integer', 'exists:students,id'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
```

---

## 9.9 `StoreStudentRequest`

Buka:

```text
app/Http/Requests/MasterData/StoreStudentRequest.php
```

Isi:

```php
<?php

namespace App\Http\Requests\MasterData;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole(['super_admin', 'admin']) ?? false;
    }

    public function rules(): array
    {
        return [
            'school_id' => ['required', 'exists:schools,id'],
            'class_room_id' => ['nullable', 'exists:class_rooms,id'],
            'create_login_account' => ['nullable', 'boolean'],
            'name' => ['nullable', 'string', 'max:255'],
            'username' => ['nullable', 'string', 'max:100', 'unique:users,username'],
            'email' => ['nullable', 'email', 'max:255', 'unique:users,email'],
            'password' => ['nullable', 'string', 'min:8'],
            'student_number' => ['nullable', 'string', 'max:100'],
            'nisn' => ['nullable', 'string', 'max:100'],
            'full_name' => ['required', 'string', 'max:255'],
            'nickname' => ['nullable', 'string', 'max:100'],
            'gender' => ['nullable', 'string', 'max:20'],
            'birth_place' => ['nullable', 'string', 'max:100'],
            'birth_date' => ['nullable', 'date'],
            'address' => ['nullable', 'string'],
            'phone' => ['nullable', 'string', 'max:30'],
            'program_type' => ['nullable', 'string', 'max:100'],
            'parent_profile_ids' => ['nullable', 'array'],
            'parent_profile_ids.*' => ['integer', 'exists:parent_profiles,id'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
```

---

## 9.10 `UpdateStudentRequest`

Buka:

```text
app/Http/Requests/MasterData/UpdateStudentRequest.php
```

Isi:

```php
<?php

namespace App\Http\Requests\MasterData;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole(['super_admin', 'admin']) ?? false;
    }

    public function rules(): array
    {
        $student = $this->route('student');
        $user = $student?->user;

        return [
            'school_id' => ['required', 'exists:schools,id'],
            'class_room_id' => ['nullable', 'exists:class_rooms,id'],
            'name' => ['nullable', 'string', 'max:255'],
            'username' => [
                'nullable',
                'string',
                'max:100',
                Rule::unique('users', 'username')->ignore($user?->id),
            ],
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user?->id),
            ],
            'password' => ['nullable', 'string', 'min:8'],
            'student_number' => ['nullable', 'string', 'max:100'],
            'nisn' => ['nullable', 'string', 'max:100'],
            'full_name' => ['required', 'string', 'max:255'],
            'nickname' => ['nullable', 'string', 'max:100'],
            'gender' => ['nullable', 'string', 'max:20'],
            'birth_place' => ['nullable', 'string', 'max:100'],
            'birth_date' => ['nullable', 'date'],
            'address' => ['nullable', 'string'],
            'phone' => ['nullable', 'string', 'max:30'],
            'program_type' => ['nullable', 'string', 'max:100'],
            'parent_profile_ids' => ['nullable', 'array'],
            'parent_profile_ids.*' => ['integer', 'exists:parent_profiles,id'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
```

---

# 10. Controller Implementation Rules

Agent harus membuat controller dengan prinsip berikut:

1. Semua index memakai pagination.
2. Semua store/update memakai Form Request.
3. Store/update user + profile harus memakai database transaction.
4. Password hanya diubah jika field password diisi.
5. User role harus otomatis sesuai controller:

   * TeacherController memakai role `teacher`.
   * ParentController memakai role `parent`.
   * StudentController memakai role `student`.
6. Admin sekolah tidak boleh membuat data untuk sekolah lain jika nanti multi-school diaktifkan.
7. Untuk sekarang, single-school boleh memakai `school_id` dari form.

---

# 11. Isi `SchoolController`

Buka:

```text
app/Http/Controllers/MasterData/SchoolController.php
```

Isi:

```php
<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Http\Requests\MasterData\StoreSchoolRequest;
use App\Http\Requests\MasterData\UpdateSchoolRequest;
use App\Models\School;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SchoolController extends Controller
{
    public function index(): View
    {
        $schools = School::query()
            ->latest()
            ->paginate(10);

        return view('master-data.schools.index', compact('schools'));
    }

    public function create(): View
    {
        return view('master-data.schools.create');
    }

    public function store(StoreSchoolRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        School::query()->create($data);

        return redirect()
            ->route('master-data.schools.index')
            ->with('success', 'Data sekolah berhasil dibuat.');
    }

    public function show(School $school): View
    {
        return view('master-data.schools.show', compact('school'));
    }

    public function edit(School $school): View
    {
        return view('master-data.schools.edit', compact('school'));
    }

    public function update(UpdateSchoolRequest $request, School $school): RedirectResponse
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        $school->update($data);

        return redirect()
            ->route('master-data.schools.index')
            ->with('success', 'Data sekolah berhasil diperbarui.');
    }

    public function destroy(School $school): RedirectResponse
    {
        $school->delete();

        return redirect()
            ->route('master-data.schools.index')
            ->with('success', 'Data sekolah berhasil dihapus.');
    }
}
```

---

# 12. Isi `ClassRoomController`

Buka:

```text
app/Http/Controllers/MasterData/ClassRoomController.php
```

Isi:

```php
<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Http\Requests\MasterData\StoreClassRoomRequest;
use App\Http\Requests\MasterData\UpdateClassRoomRequest;
use App\Models\ClassRoom;
use App\Models\School;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ClassRoomController extends Controller
{
    public function index(): View
    {
        $classRooms = ClassRoom::query()
            ->with(['school', 'homeroomTeacher'])
            ->latest()
            ->paginate(10);

        return view('master-data.class-rooms.index', compact('classRooms'));
    }

    public function create(): View
    {
        return view('master-data.class-rooms.create', [
            'schools' => School::query()->where('is_active', true)->orderBy('name')->get(),
            'teachers' => User::query()
                ->whereHas('role', fn ($query) => $query->where('name', 'teacher'))
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function store(StoreClassRoomRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        ClassRoom::query()->create($data);

        return redirect()
            ->route('master-data.class-rooms.index')
            ->with('success', 'Data kelas berhasil dibuat.');
    }

    public function show(ClassRoom $classRoom): View
    {
        $classRoom->load(['school', 'homeroomTeacher', 'students']);

        return view('master-data.class-rooms.show', compact('classRoom'));
    }

    public function edit(ClassRoom $classRoom): View
    {
        return view('master-data.class-rooms.edit', [
            'classRoom' => $classRoom,
            'schools' => School::query()->where('is_active', true)->orderBy('name')->get(),
            'teachers' => User::query()
                ->whereHas('role', fn ($query) => $query->where('name', 'teacher'))
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function update(UpdateClassRoomRequest $request, ClassRoom $classRoom): RedirectResponse
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        $classRoom->update($data);

        return redirect()
            ->route('master-data.class-rooms.index')
            ->with('success', 'Data kelas berhasil diperbarui.');
    }

    public function destroy(ClassRoom $classRoom): RedirectResponse
    {
        $classRoom->delete();

        return redirect()
            ->route('master-data.class-rooms.index')
            ->with('success', 'Data kelas berhasil dihapus.');
    }
}
```

---

# 13. Isi `TeacherController`

Buka:

```text
app/Http/Controllers/MasterData/TeacherController.php
```

Isi:

```php
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
        $teachers = TeacherProfile::query()
            ->with(['user', 'school'])
            ->latest()
            ->paginate(10);

        return view('master-data.teachers.index', compact('teachers'));
    }

    public function create(): View
    {
        return view('master-data.teachers.create', [
            'schools' => School::query()->where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function store(StoreTeacherRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request): void {
            $role = Role::query()->where('name', 'teacher')->firstOrFail();

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

            TeacherProfile::query()->create([
                'user_id' => $user->id,
                'school_id' => $request->integer('school_id'),
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
        $teacher->load(['user', 'school']);

        return view('master-data.teachers.show', compact('teacher'));
    }

    public function edit(TeacherProfile $teacher): View
    {
        $teacher->load(['user', 'school']);

        return view('master-data.teachers.edit', [
            'teacher' => $teacher,
            'schools' => School::query()->where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function update(UpdateTeacherRequest $request, TeacherProfile $teacher): RedirectResponse
    {
        DB::transaction(function () use ($request, $teacher): void {
            $teacher->user->update([
                'school_id' => $request->integer('school_id'),
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
                'school_id' => $request->integer('school_id'),
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
        $teacher->user?->delete();

        return redirect()
            ->route('master-data.teachers.index')
            ->with('success', 'Data guru berhasil dihapus.');
    }
}
```

---

# 14. Isi `ParentController`

Buka:

```text
app/Http/Controllers/MasterData/ParentController.php
```

Isi:

```php
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
```

---

# 15. Isi `StudentController`

Buka:

```text
app/Http/Controllers/MasterData/StudentController.php
```

Isi:

```php
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
```

---

# 16. Update Routes

Buka:

```text
routes/web.php
```

Tambahkan import:

```php
use App\Http\Controllers\MasterData\ClassRoomController;
use App\Http\Controllers\MasterData\ParentController;
use App\Http\Controllers\MasterData\SchoolController;
use App\Http\Controllers\MasterData\StudentController;
use App\Http\Controllers\MasterData\TeacherController;
```

Di dalam group `Route::middleware('auth')->group(...)`, tambahkan:

```php
Route::middleware('role:super_admin,admin')->prefix('master-data')->name('master-data.')->group(function (): void {
    Route::resource('class-rooms', ClassRoomController::class);
    Route::resource('teachers', TeacherController::class)->parameters([
        'teachers' => 'teacher',
    ]);
    Route::resource('parents', ParentController::class)->parameters([
        'parents' => 'parent',
    ]);
    Route::resource('students', StudentController::class);
});

Route::middleware('role:super_admin')->prefix('master-data')->name('master-data.')->group(function (): void {
    Route::resource('schools', SchoolController::class);
});
```

Catatan:

1. Sekolah hanya untuk `super_admin`.
2. Kelas, guru, orang tua, dan santri untuk `super_admin` dan `admin`.
3. Kepala sekolah belum diberi akses CRUD karena role ini monitoring, bukan input master data.

---

# 17. Update Layout Navigasi

Buka:

```text
resources/views/layouts/app.blade.php
```

Tambahkan navigasi di dalam header setelah nama user.

Minimal menu:

```blade
@auth
    @if (auth()->user()->hasRole(['super_admin', 'admin']))
        <nav class="mt-4 flex flex-wrap gap-3 text-sm">
            @if (auth()->user()->hasRole('super_admin'))
                <a href="{{ route('master-data.schools.index') }}" class="font-semibold text-slate-700 hover:text-slate-950">
                    Sekolah
                </a>
            @endif

            <a href="{{ route('master-data.class-rooms.index') }}" class="font-semibold text-slate-700 hover:text-slate-950">
                Kelas
            </a>

            <a href="{{ route('master-data.teachers.index') }}" class="font-semibold text-slate-700 hover:text-slate-950">
                Guru
            </a>

            <a href="{{ route('master-data.parents.index') }}" class="font-semibold text-slate-700 hover:text-slate-950">
                Orang Tua
            </a>

            <a href="{{ route('master-data.students.index') }}" class="font-semibold text-slate-700 hover:text-slate-950">
                Santri
            </a>
        </nav>
    @endif
@endauth
```

Jangan merusak tombol logout.

---

# 18. View Implementation Rules

Agent harus membuat view Blade sederhana.

Tidak perlu desain kompleks.

Setiap modul minimal punya:

1. `index.blade.php`
2. `create.blade.php`
3. `edit.blade.php`
4. `show.blade.php`

Setiap index harus punya:

1. Tombol tambah.
2. Tabel data.
3. Tombol lihat.
4. Tombol edit.
5. Tombol hapus.
6. Pagination.

Setiap create/edit harus punya:

1. Form.
2. Error validation.
3. Tombol submit.
4. Tombol batal.

---

# 19. Template Index Minimal

Gunakan pola ini untuk semua index.

Contoh untuk `students/index.blade.php`:

```blade
@extends('layouts.app')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold">Data Santri</h2>
            <p class="text-sm text-slate-500">Kelola data santri.</p>
        </div>

        <a href="{{ route('master-data.students.create') }}"
           class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">
            Tambah Santri
        </a>
    </div>

    <div class="overflow-hidden rounded-2xl bg-white shadow-sm">
        <table class="w-full border-collapse text-left text-sm">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-4 py-3">Nama</th>
                    <th class="px-4 py-3">Kelas</th>
                    <th class="px-4 py-3">Program</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($students as $student)
                    <tr class="border-t">
                        <td class="px-4 py-3 font-semibold">{{ $student->full_name }}</td>
                        <td class="px-4 py-3">{{ $student->classRoom?->name ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $student->program_type ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $student->is_active ? 'Aktif' : 'Nonaktif' }}</td>
                        <td class="px-4 py-3">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('master-data.students.show', $student) }}" class="text-slate-700 hover:underline">Lihat</a>
                                <a href="{{ route('master-data.students.edit', $student) }}" class="text-blue-700 hover:underline">Edit</a>

                                <form method="POST" action="{{ route('master-data.students.destroy', $student) }}"
                                      onsubmit="return confirm('Hapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-700 hover:underline">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-slate-500">
                            Belum ada data.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $students->links() }}
    </div>
@endsection
```

Agent boleh menyesuaikan nama variabel untuk modul lain.

---

# 20. Template Form Minimal

Gunakan pola form sederhana.

Contoh potongan input:

```blade
<div>
    <label class="mb-2 block text-sm font-semibold">Nama Lengkap</label>
    <input
        type="text"
        name="full_name"
        value="{{ old('full_name', $student->full_name ?? '') }}"
        class="w-full rounded-lg border border-slate-300 px-4 py-2"
        required
    >
    @error('full_name')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
```

Checkbox aktif:

```blade
<label class="flex items-center gap-2">
    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $model->is_active ?? true))>
    <span>Aktif</span>
</label>
```

Select sekolah:

```blade
<select name="school_id" class="w-full rounded-lg border border-slate-300 px-4 py-2" required>
    <option value="">Pilih Sekolah</option>
    @foreach ($schools as $school)
        <option value="{{ $school->id }}" @selected(old('school_id', $model->school_id ?? null) == $school->id)>
            {{ $school->name }}
        </option>
    @endforeach
</select>
```

---

# 21. Validasi Route Setelah Selesai

Jalankan:

```powershell
php artisan route:list
```

Pastikan route berikut muncul:

```text
master-data.schools.index
master-data.schools.create
master-data.schools.store
master-data.schools.show
master-data.schools.edit
master-data.schools.update
master-data.schools.destroy

master-data.class-rooms.index
master-data.teachers.index
master-data.parents.index
master-data.students.index
```

---

# 22. Jalankan Build

Jalankan:

```powershell
npm run build
```

Jika ada error Vite/Tailwind, perbaiki.

---

# 23. Jalankan Server

Jalankan:

```powershell
php artisan serve
```

Buka:

```text
http://127.0.0.1:8000
```

---

# 24. Test Manual

## 24.1 Login Super Admin

Login:

```text
superadmin@hafizplus.test
password
```

Harus bisa akses:

1. `/master-data/schools`
2. `/master-data/class-rooms`
3. `/master-data/teachers`
4. `/master-data/parents`
5. `/master-data/students`

---

## 24.2 Login Admin Sekolah

Login:

```text
admin@hafizplus.test
password
```

Harus bisa akses:

1. `/master-data/class-rooms`
2. `/master-data/teachers`
3. `/master-data/parents`
4. `/master-data/students`

Tidak boleh akses:

```text
/master-data/schools
```

Target:

```text
403 Forbidden
```

---

## 24.3 Login Kepala Sekolah

Login:

```text
kepalasekolah@hafizplus.test
password
```

Tidak boleh akses:

```text
/master-data/students
/master-data/teachers
/master-data/class-rooms
```

Target:

```text
403 Forbidden
```

---

## 24.4 Login Guru

Login:

```text
guru@hafizplus.test
password
```

Tidak boleh akses master data admin.

---

## 24.5 Login Orang Tua

Login:

```text
ortu@hafizplus.test
password
```

Tidak boleh akses master data admin.

---

## 24.6 Login Santri

Login:

```text
santri@hafizplus.test
password
```

Tidak boleh akses master data admin.

---

# 25. Dokumentasi Phase 2

Buat file:

```text
docs/phase-2-master-data-foundation.md
```

Isi:

```md
# Phase 2 — Master Data Foundation

## Status

Phase 2 membangun master data dasar untuk HafizPlus School Platform.

## Output

1. CRUD Sekolah.
2. CRUD Kelas.
3. CRUD Guru Tahfidz.
4. CRUD Orang Tua.
5. CRUD Santri.
6. Relasi Orang Tua dan Santri.
7. Navigasi master data.
8. Validasi form menggunakan Form Request.
9. Restriksi akses menggunakan middleware role.

## Role Access

| Modul | Super Admin | Admin Sekolah | Kepala Sekolah | Guru | Orang Tua | Santri |
|---|---|---|---|---|---|---|
| Sekolah | CRUD | Tidak | Tidak | Tidak | Tidak | Tidak |
| Kelas | CRUD | CRUD | Tidak | Tidak | Tidak | Tidak |
| Guru | CRUD | CRUD | Tidak | Tidak | Tidak | Tidak |
| Orang Tua | CRUD | CRUD | Tidak | Tidak | Tidak | Tidak |
| Santri | CRUD | CRUD | Tidak | Tidak | Tidak | Tidak |

## Belum Dibuat

1. Input setoran hafalan.
2. Target hafalan.
3. Hutang hafalan.
4. Validasi urutan hafalan.
5. Report bulanan.
6. Report triwulan.
7. Dashboard statistik.
8. Parent progress detail.
9. Student progress detail.
10. Notifikasi real.

## Definition of Done

Phase 2 selesai jika:

1. Semua halaman master data bisa dibuka.
2. Data sekolah bisa dibuat, dilihat, diedit, dan dihapus oleh Super Admin.
3. Data kelas bisa dibuat, dilihat, diedit, dan dihapus oleh Admin/Super Admin.
4. Data guru bisa dibuat, dilihat, diedit, dan dihapus oleh Admin/Super Admin.
5. Data orang tua bisa dibuat, dilihat, diedit, dan dihapus oleh Admin/Super Admin.
6. Data santri bisa dibuat, dilihat, diedit, dan dihapus oleh Admin/Super Admin.
7. Relasi orang tua dan santri bisa disimpan.
8. Middleware role bekerja.
9. User tanpa akses mendapat 403.
10. Build frontend berhasil.
```

---

# 26. Commit Phase 2

Jalankan:

```powershell
git status
git add .
git commit -m "feat: add master data foundation"
```

Jika remote sudah tersedia:

```powershell
git push origin phase-2-master-data-foundation
```

---

# 27. Output Akhir yang Harus Dilaporkan Agent

Setelah selesai, agent harus melaporkan:

```text
Phase 2 selesai.

Project:
- HafizPlus School Platform
- Laravel 12
- MySQL

Master data dibuat:
- Sekolah
- Kelas
- Guru Tahfidz
- Orang Tua
- Santri
- Relasi Orang Tua dan Santri

Akses:
- Super Admin bisa CRUD semua master data.
- Admin Sekolah bisa CRUD kelas, guru, orang tua, dan santri.
- Kepala Sekolah belum CRUD, hanya monitoring di fase berikutnya.
- Guru, Orang Tua, dan Santri tidak bisa akses master data admin.

Belum dibuat:
- Input setoran
- Target hafalan
- Hutang hafalan
- Sequential validation
- Report
- Notifikasi real

Status:
- Siap lanjut Phase 3 setelah validasi manual master data.
```

---

# 28. Larangan Setelah Phase 2

Agent harus berhenti setelah Phase 2 selesai.

Jangan lanjut membuat:

1. Hafalan records.
2. Hafalan targets.
3. Hutang hafalan.
4. Sequential validation.
5. Report bulanan.
6. Report triwulan.
7. Dashboard statistik.
8. Parent portal detail.
9. Student portal detail.
10. Notification center.

Semua itu masuk Phase berikutnya.
