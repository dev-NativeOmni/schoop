# Phase 1 Execution Guide — Auth, Role, and Initial Database Foundation

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

# 1. Tujuan Phase 1

Phase 1 bertujuan membangun fondasi teknis aplikasi:

1. Auth manual berbasis Blade.
2. Role user awal.
3. Middleware role.
4. Redirect dashboard berdasarkan role.
5. Struktur database awal untuk sekolah, kelas, guru, orang tua, dan santri.
6. Seeder akun awal.
7. Dashboard placeholder per role.
8. Dokumentasi hasil Phase 1.

Phase 1 belum membangun fitur tahfizh detail seperti input setoran, target hafalan, hutang hafalan, dan report. Itu masuk Phase berikutnya.

---

# 2. Aturan Keras untuk AI Agent

AI agent wajib mengikuti batasan berikut.

## 2.1 Jangan Membuat Fitur Tahfizh Dulu

Pada Phase 1, agent tidak boleh membuat:

1. Input setoran hafalan.
2. Validasi urutan hafalan.
3. Hitung hutang hafalan.
4. Report bulanan.
5. Report triwulan.
6. Dashboard statistik real.
7. Parent progress detail.
8. Student progress detail.
9. API setoran.
10. Export PDF/Excel.
11. Notifikasi real.

Phase 1 hanya fondasi auth, role, dan database awal.

---

## 2.2 Jangan Memakai Starter Kit Baru

Jangan menjalankan:

```powershell
laravel new
```

Jangan memasang:

```powershell
laravel/breeze
laravel/jetstream
```

Jangan memasang starter kit React/Vue/Svelte.

Alasan:

1. Project sudah berjalan di Laravel 12.
2. Environment local memakai PHP 8.2.12.
3. Starter kit terbaru sebelumnya gagal karena dependency PHP 8.3.
4. Kita butuh auth yang sederhana, transparan, dan sesuai domain sekolah.

Gunakan auth manual berbasis Laravel session dan Blade.

---

## 2.3 Jangan Memakai Teams Support

Jangan memakai konsep `teams` bawaan starter kit.

Domain aplikasi ini memakai role sekolah:

1. Super Admin.
2. Admin Sekolah.
3. Kepala Sekolah.
4. Guru Tahfidz.
5. Orang Tua.
6. Santri.

Jika nanti butuh multi-school, akan dibuat dengan tabel `schools` dan relasi user-school sendiri.

---

# 3. Target Output Phase 1

Setelah Phase 1 selesai, aplikasi harus punya:

1. Login page.
2. Logout.
3. Auth session.
4. Role user.
5. Middleware `role`.
6. Redirect dashboard berdasarkan role.
7. Dashboard placeholder untuk semua role.
8. Tabel awal:

   * `schools`
   * `roles`
   * `users` tambahan kolom role
   * `class_rooms`
   * `teacher_profiles`
   * `parent_profiles`
   * `students`
   * `parent_student`
9. Seeder:

   * `RoleSeeder`
   * `SchoolSeeder`
   * `InitialUserSeeder`
10. Akun awal:

* Super Admin
* Admin Sekolah
* Kepala Sekolah
* Guru Tahfidz
* Orang Tua
* Santri

11. Dokumentasi Phase 1.

---

# 4. Validasi Awal Sebelum Eksekusi

Jalankan:

```powershell
cd C:\xampp\htdocs\hafizplus-school-platform
php artisan --version
php -v
composer -V
npm -v
```

Target minimal:

```text
Laravel Framework 12.x
PHP 8.2.x
Composer tersedia
NPM tersedia
```

Jika Laravel belum jalan, hentikan eksekusi.

---

# 5. Buat Branch Git untuk Phase 1

Jalankan:

```powershell
git status
git checkout -b phase-1-auth-role-foundation
```

Jika branch sudah ada:

```powershell
git checkout phase-1-auth-role-foundation
```

Pastikan working tree bersih sebelum mulai.

---

# 6. Struktur File yang Akan Dibuat

Agent harus membuat atau mengubah file berikut:

```text
app/
├── Http/
│   ├── Controllers/
│   │   ├── Auth/
│   │   │   ├── LoginController.php
│   │   │   └── LogoutController.php
│   │   └── DashboardController.php
│   └── Middleware/
│       └── CheckRole.php
├── Models/
│   ├── Role.php
│   ├── School.php
│   ├── ClassRoom.php
│   ├── TeacherProfile.php
│   ├── ParentProfile.php
│   └── Student.php

database/
├── migrations/
│   ├── xxxx_xx_xx_xxxxxx_create_roles_table.php
│   ├── xxxx_xx_xx_xxxxxx_create_schools_table.php
│   ├── xxxx_xx_xx_xxxxxx_add_role_and_school_fields_to_users_table.php
│   ├── xxxx_xx_xx_xxxxxx_create_class_rooms_table.php
│   ├── xxxx_xx_xx_xxxxxx_create_teacher_profiles_table.php
│   ├── xxxx_xx_xx_xxxxxx_create_parent_profiles_table.php
│   ├── xxxx_xx_xx_xxxxxx_create_students_table.php
│   └── xxxx_xx_xx_xxxxxx_create_parent_student_table.php
├── seeders/
│   ├── RoleSeeder.php
│   ├── SchoolSeeder.php
│   └── InitialUserSeeder.php

resources/
├── views/
│   ├── auth/
│   │   └── login.blade.php
│   ├── dashboards/
│   │   ├── super-admin.blade.php
│   │   ├── admin.blade.php
│   │   ├── kepala-sekolah.blade.php
│   │   ├── teacher.blade.php
│   │   ├── parent.blade.php
│   │   └── student.blade.php
│   └── layouts/
│       └── app.blade.php

routes/
└── web.php

bootstrap/
└── app.php

docs/
└── phase-1-auth-role-foundation.md
```

---

# 7. Buat Migration

Jalankan command berikut:

```powershell
php artisan make:model Role -m
php artisan make:model School -m
php artisan make:model ClassRoom -m
php artisan make:model TeacherProfile -m
php artisan make:model ParentProfile -m
php artisan make:model Student -m

php artisan make:migration add_role_and_school_fields_to_users_table --table=users
php artisan make:migration create_parent_student_table
```

---

# 8. Isi Migration `create_roles_table`

Cari file migration `create_roles_table`, lalu isi:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table): void {
            $table->id();
            $table->string('name')->unique();
            $table->string('label');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
```

---

# 9. Isi Migration `create_schools_table`

Cari file migration `create_schools_table`, lalu isi:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('schools', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('npsn')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('logo_path')->nullable();
            $table->string('primary_color')->nullable();
            $table->string('secondary_color')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('code');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schools');
    }
};
```

---

# 10. Isi Migration `add_role_and_school_fields_to_users_table`

Cari file migration `add_role_and_school_fields_to_users_table`, lalu isi:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->foreignId('role_id')
                ->nullable()
                ->after('id')
                ->constrained('roles')
                ->nullOnDelete();

            $table->foreignId('school_id')
                ->nullable()
                ->after('role_id')
                ->constrained('schools')
                ->nullOnDelete();

            $table->string('username')->nullable()->unique()->after('name');
            $table->string('phone')->nullable()->after('email');
            $table->boolean('is_active')->default(true)->after('password');
            $table->timestamp('last_login_at')->nullable()->after('is_active');

            $table->index('role_id');
            $table->index('school_id');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropForeign(['role_id']);
            $table->dropForeign(['school_id']);

            $table->dropIndex(['role_id']);
            $table->dropIndex(['school_id']);
            $table->dropIndex(['is_active']);

            $table->dropColumn([
                'role_id',
                'school_id',
                'username',
                'phone',
                'is_active',
                'last_login_at',
            ]);
        });
    }
};
```

---

# 11. Isi Migration `create_class_rooms_table`

Cari file migration `create_class_rooms_table`, lalu isi:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('class_rooms', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('school_id')
                ->constrained('schools')
                ->cascadeOnDelete();

            $table->string('name');
            $table->string('level')->nullable();
            $table->string('academic_year')->nullable();
            $table->foreignId('homeroom_teacher_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['school_id', 'name', 'academic_year']);
            $table->index('school_id');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_rooms');
    }
};
```

---

# 12. Isi Migration `create_teacher_profiles_table`

Cari file migration `create_teacher_profiles_table`, lalu isi:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('teacher_profiles', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('user_id')
                ->unique()
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('school_id')
                ->constrained('schools')
                ->cascadeOnDelete();

            $table->string('employee_number')->nullable()->unique();
            $table->string('specialization')->nullable();
            $table->text('address')->nullable();
            $table->date('joined_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('school_id');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_profiles');
    }
};
```

---

# 13. Isi Migration `create_parent_profiles_table`

Cari file migration `create_parent_profiles_table`, lalu isi:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('parent_profiles', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('user_id')
                ->unique()
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('school_id')
                ->constrained('schools')
                ->cascadeOnDelete();

            $table->string('relationship')->nullable();
            $table->string('occupation')->nullable();
            $table->text('address')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('school_id');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parent_profiles');
    }
};
```

---

# 14. Isi Migration `create_students_table`

Cari file migration `create_students_table`, lalu isi:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('school_id')
                ->constrained('schools')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->nullable()
                ->unique()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('class_room_id')
                ->nullable()
                ->constrained('class_rooms')
                ->nullOnDelete();

            $table->string('student_number')->nullable();
            $table->string('nisn')->nullable();
            $table->string('full_name');
            $table->string('nickname')->nullable();
            $table->string('gender')->nullable();
            $table->string('birth_place')->nullable();
            $table->date('birth_date')->nullable();
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('program_type')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['school_id', 'student_number']);
            $table->index('school_id');
            $table->index('class_room_id');
            $table->index('full_name');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
```

---

# 15. Isi Migration `create_parent_student_table`

Cari file migration `create_parent_student_table`, lalu isi:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('parent_student', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('parent_profile_id')
                ->constrained('parent_profiles')
                ->cascadeOnDelete();

            $table->foreignId('student_id')
                ->constrained('students')
                ->cascadeOnDelete();

            $table->string('relationship')->nullable();
            $table->boolean('is_primary')->default(false);
            $table->timestamps();

            $table->unique(['parent_profile_id', 'student_id']);
            $table->index('parent_profile_id');
            $table->index('student_id');
            $table->index('is_primary');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parent_student');
    }
};
```

---

# 16. Update Model `User`

Buka:

```text
app/Models/User.php
```

Isi lengkap:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'role_id',
        'school_id',
        'name',
        'username',
        'email',
        'phone',
        'password',
        'is_active',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function teacherProfile(): HasOne
    {
        return $this->hasOne(TeacherProfile::class);
    }

    public function parentProfile(): HasOne
    {
        return $this->hasOne(ParentProfile::class);
    }

    public function studentProfile(): HasOne
    {
        return $this->hasOne(Student::class);
    }

    public function hasRole(string|array $roles): bool
    {
        $roleName = $this->role?->name;

        if (is_array($roles)) {
            return in_array($roleName, $roles, true);
        }

        return $roleName === $roles;
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasRole('super_admin');
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    public function isPrincipal(): bool
    {
        return $this->hasRole('principal');
    }

    public function isTeacher(): bool
    {
        return $this->hasRole('teacher');
    }

    public function isParent(): bool
    {
        return $this->hasRole('parent');
    }

    public function isStudent(): bool
    {
        return $this->hasRole('student');
    }
}
```

---

# 17. Isi Model `Role`

Buka:

```text
app/Models/Role.php
```

Isi lengkap:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    protected $fillable = [
        'name',
        'label',
        'description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
```

---

# 18. Isi Model `School`

Buka:

```text
app/Models/School.php
```

Isi lengkap:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class School extends Model
{
    protected $fillable = [
        'name',
        'code',
        'npsn',
        'email',
        'phone',
        'address',
        'logo_path',
        'primary_color',
        'secondary_color',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function classRooms(): HasMany
    {
        return $this->hasMany(ClassRoom::class);
    }

    public function teacherProfiles(): HasMany
    {
        return $this->hasMany(TeacherProfile::class);
    }

    public function parentProfiles(): HasMany
    {
        return $this->hasMany(ParentProfile::class);
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }
}
```

---

# 19. Isi Model `ClassRoom`

Buka:

```text
app/Models/ClassRoom.php
```

Isi lengkap:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClassRoom extends Model
{
    protected $fillable = [
        'school_id',
        'name',
        'level',
        'academic_year',
        'homeroom_teacher_id',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function homeroomTeacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'homeroom_teacher_id');
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }
}
```

---

# 20. Isi Model `TeacherProfile`

Buka:

```text
app/Models/TeacherProfile.php
```

Isi lengkap:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeacherProfile extends Model
{
    protected $fillable = [
        'user_id',
        'school_id',
        'employee_number',
        'specialization',
        'address',
        'joined_at',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'joined_at' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }
}
```

---

# 21. Isi Model `ParentProfile`

Buka:

```text
app/Models/ParentProfile.php
```

Isi lengkap:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ParentProfile extends Model
{
    protected $fillable = [
        'user_id',
        'school_id',
        'relationship',
        'occupation',
        'address',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'parent_student')
            ->withPivot(['relationship', 'is_primary'])
            ->withTimestamps();
    }
}
```

---

# 22. Isi Model `Student`

Buka:

```text
app/Models/Student.php
```

Isi lengkap:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Student extends Model
{
    protected $fillable = [
        'school_id',
        'user_id',
        'class_room_id',
        'student_number',
        'nisn',
        'full_name',
        'nickname',
        'gender',
        'birth_place',
        'birth_date',
        'address',
        'phone',
        'program_type',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function classRoom(): BelongsTo
    {
        return $this->belongsTo(ClassRoom::class);
    }

    public function parents(): BelongsToMany
    {
        return $this->belongsToMany(ParentProfile::class, 'parent_student')
            ->withPivot(['relationship', 'is_primary'])
            ->withTimestamps();
    }
}
```

---

# 23. Buat Middleware `CheckRole`

Jalankan:

```powershell
php artisan make:middleware CheckRole
```

Buka:

```text
app/Http/Middleware/CheckRole.php
```

Isi lengkap:

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        if (! $user->is_active) {
            auth()->logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('login')
                ->withErrors([
                    'email' => 'Akun Anda tidak aktif. Silakan hubungi admin.',
                ]);
        }

        if (! $user->role || ! $user->hasRole($roles)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
```

---

# 24. Daftarkan Middleware di `bootstrap/app.php`

Buka:

```text
bootstrap/app.php
```

Pastikan ada alias middleware.

Isi lengkap file jika struktur masih default Laravel 12:

```php
<?php

use App\Http\Middleware\CheckRole;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => CheckRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
```

Catatan:

Jika `api.php` sudah ada dan terdaftar, jangan hapus kecuali memang belum dipakai.

---

# 25. Buat Auth Controller

Jalankan:

```powershell
mkdir app\Http\Controllers\Auth
php artisan make:controller Auth/LoginController
php artisan make:controller Auth/LogoutController
php artisan make:controller DashboardController
```

---

# 26. Isi `LoginController`

Buka:

```text
app/Http/Controllers/Auth/LoginController.php
```

Isi lengkap:

```php
<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $loginField = filter_var($credentials['login'], FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'username';

        $attemptCredentials = [
            $loginField => $credentials['login'],
            'password' => $credentials['password'],
            'is_active' => true,
        ];

        if (! Auth::attempt($attemptCredentials, $request->boolean('remember'))) {
            return back()
                ->withErrors([
                    'login' => 'Login gagal. Periksa username/email dan password.',
                ])
                ->onlyInput('login');
        }

        $request->session()->regenerate();

        $request->user()->forceFill([
            'last_login_at' => now(),
        ])->save();

        return redirect()->intended(route('dashboard'));
    }
}
```

---

# 27. Isi `LogoutController`

Buka:

```text
app/Http/Controllers/Auth/LogoutController.php
```

Isi lengkap:

```php
<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
```

---

# 28. Isi `DashboardController`

Buka:

```text
app/Http/Controllers/DashboardController.php
```

Isi lengkap:

```php
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
```

---

# 29. Isi `routes/web.php`

Buka:

```text
routes/web.php
```

Isi lengkap:

```php
<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
});

Route::middleware('auth')->group(function (): void {
    Route::post('/logout', LogoutController::class)->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'redirect'])
        ->name('dashboard');

    Route::get('/dashboard/super-admin', [DashboardController::class, 'superAdmin'])
        ->middleware('role:super_admin')
        ->name('dashboard.super-admin');

    Route::get('/dashboard/admin', [DashboardController::class, 'admin'])
        ->middleware('role:super_admin,admin')
        ->name('dashboard.admin');

    Route::get('/dashboard/kepala-sekolah', [DashboardController::class, 'kepalaSekolah'])
        ->middleware('role:super_admin,principal')
        ->name('dashboard.kepala-sekolah');

    Route::get('/dashboard/guru', [DashboardController::class, 'teacher'])
        ->middleware('role:super_admin,teacher')
        ->name('dashboard.teacher');

    Route::get('/dashboard/orang-tua', [DashboardController::class, 'parent'])
        ->middleware('role:super_admin,parent')
        ->name('dashboard.parent');

    Route::get('/dashboard/santri', [DashboardController::class, 'student'])
        ->middleware('role:super_admin,student')
        ->name('dashboard.student');
});
```

---

# 30. Buat Layout Blade

Buat folder:

```powershell
mkdir resources\views\layouts
```

Buat file:

```text
resources/views/layouts/app.blade.php
```

Isi lengkap:

```blade
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'HafizPlus School Platform') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 text-slate-900">
    <div class="min-h-screen">
        @auth
            <header class="border-b bg-white">
                <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4">
                    <div>
                        <h1 class="text-lg font-bold">HafizPlus School Platform</h1>
                        <p class="text-sm text-slate-500">
                            {{ auth()->user()->name }} —
                            {{ auth()->user()->role?->label ?? 'Tanpa Role' }}
                        </p>
                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button
                            type="submit"
                            class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700"
                        >
                            Logout
                        </button>
                    </form>
                </div>
            </header>
        @endauth

        <main class="mx-auto max-w-7xl px-4 py-8">
            @if (session('success'))
                <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</body>
</html>
```

---

# 31. Buat Login View

Buat folder:

```powershell
mkdir resources\views\auth
```

Buat file:

```text
resources/views/auth/login.blade.php
```

Isi lengkap:

```blade
@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-md rounded-2xl bg-white p-8 shadow-sm">
        <div class="mb-8 text-center">
            <h1 class="text-2xl font-bold">Login</h1>
            <p class="mt-2 text-sm text-slate-500">
                HafizPlus School Platform
            </p>
        </div>

        <form method="POST" action="{{ route('login.store') }}" class="space-y-5">
            @csrf

            <div>
                <label for="login" class="mb-2 block text-sm font-semibold">
                    Email atau Username
                </label>
                <input
                    id="login"
                    name="login"
                    type="text"
                    value="{{ old('login') }}"
                    required
                    autofocus
                    class="w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-slate-900 focus:outline-none"
                >
                @error('login')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="mb-2 block text-sm font-semibold">
                    Password
                </label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    required
                    class="w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-slate-900 focus:outline-none"
                >
                @error('password')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center gap-2">
                <input
                    id="remember"
                    name="remember"
                    type="checkbox"
                    value="1"
                    class="rounded border-slate-300"
                >
                <label for="remember" class="text-sm text-slate-600">
                    Ingat saya
                </label>
            </div>

            <button
                type="submit"
                class="w-full rounded-lg bg-slate-900 px-4 py-2 font-semibold text-white hover:bg-slate-700"
            >
                Masuk
            </button>
        </form>
    </div>
@endsection
```

---

# 32. Buat Dashboard Views

Buat folder:

```powershell
mkdir resources\views\dashboards
```

---

## 32.1 `super-admin.blade.php`

Buat file:

```text
resources/views/dashboards/super-admin.blade.php
```

Isi:

```blade
@extends('layouts.app')

@section('content')
    <div class="rounded-2xl bg-white p-8 shadow-sm">
        <h2 class="text-2xl font-bold">Dashboard Super Admin</h2>
        <p class="mt-2 text-slate-600">
            Placeholder dashboard untuk pengelola sistem penuh.
        </p>
    </div>
@endsection
```

---

## 32.2 `admin.blade.php`

Buat file:

```text
resources/views/dashboards/admin.blade.php
```

Isi:

```blade
@extends('layouts.app')

@section('content')
    <div class="rounded-2xl bg-white p-8 shadow-sm">
        <h2 class="text-2xl font-bold">Dashboard Admin Sekolah</h2>
        <p class="mt-2 text-slate-600">
            Placeholder dashboard untuk pengelolaan data sekolah, kelas, santri, dan laporan.
        </p>
    </div>
@endsection
```

---

## 32.3 `kepala-sekolah.blade.php`

Buat file:

```text
resources/views/dashboards/kepala-sekolah.blade.php
```

Isi:

```blade
@extends('layouts.app')

@section('content')
    <div class="rounded-2xl bg-white p-8 shadow-sm">
        <h2 class="text-2xl font-bold">Dashboard Kepala Sekolah</h2>
        <p class="mt-2 text-slate-600">
            Placeholder dashboard untuk monitoring aktivitas guru, santri, dan setoran.
        </p>
    </div>
@endsection
```

---

## 32.4 `teacher.blade.php`

Buat file:

```text
resources/views/dashboards/teacher.blade.php
```

Isi:

```blade
@extends('layouts.app')

@section('content')
    <div class="rounded-2xl bg-white p-8 shadow-sm">
        <h2 class="text-2xl font-bold">Dashboard Guru Tahfidz</h2>
        <p class="mt-2 text-slate-600">
            Placeholder dashboard untuk guru tahfidz.
        </p>
    </div>
@endsection
```

---

## 32.5 `parent.blade.php`

Buat file:

```text
resources/views/dashboards/parent.blade.php
```

Isi:

```blade
@extends('layouts.app')

@section('content')
    <div class="rounded-2xl bg-white p-8 shadow-sm">
        <h2 class="text-2xl font-bold">Dashboard Orang Tua</h2>
        <p class="mt-2 text-slate-600">
            Placeholder dashboard untuk orang tua.
        </p>
    </div>
@endsection
```

---

## 32.6 `student.blade.php`

Buat file:

```text
resources/views/dashboards/student.blade.php
```

Isi:

```blade
@extends('layouts.app')

@section('content')
    <div class="rounded-2xl bg-white p-8 shadow-sm">
        <h2 class="text-2xl font-bold">Dashboard Santri</h2>
        <p class="mt-2 text-slate-600">
            Placeholder dashboard untuk santri.
        </p>
    </div>
@endsection
```

---

# 33. Buat Seeder

Jalankan:

```powershell
php artisan make:seeder RoleSeeder
php artisan make:seeder SchoolSeeder
php artisan make:seeder InitialUserSeeder
```

---

# 34. Isi `RoleSeeder`

Buka:

```text
database/seeders/RoleSeeder.php
```

Isi lengkap:

```php
<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => 'super_admin',
                'label' => 'Super Admin',
                'description' => 'Kelola sistem penuh.',
            ],
            [
                'name' => 'admin',
                'label' => 'Admin Sekolah',
                'description' => 'Kelola data sekolah, kelas, santri, dan laporan.',
            ],
            [
                'name' => 'principal',
                'label' => 'Kepala Sekolah',
                'description' => 'Monitoring aktivitas guru, santri, dan setoran.',
            ],
            [
                'name' => 'teacher',
                'label' => 'Guru Tahfidz',
                'description' => 'Input setoran dan pantau target.',
            ],
            [
                'name' => 'parent',
                'label' => 'Orang Tua',
                'description' => 'Lihat progres anak.',
            ],
            [
                'name' => 'student',
                'label' => 'Santri',
                'description' => 'Lihat progres pribadi.',
            ],
        ];

        foreach ($roles as $role) {
            Role::query()->updateOrCreate(
                ['name' => $role['name']],
                [
                    'label' => $role['label'],
                    'description' => $role['description'],
                    'is_active' => true,
                ]
            );
        }
    }
}
```

---

# 35. Isi `SchoolSeeder`

Buka:

```text
database/seeders/SchoolSeeder.php
```

Isi lengkap:

```php
<?php

namespace Database\Seeders;

use App\Models\School;
use Illuminate\Database\Seeder;

class SchoolSeeder extends Seeder
{
    public function run(): void
    {
        School::query()->updateOrCreate(
            ['code' => 'ALAZHAR7'],
            [
                'name' => 'SMA Islam Al Azhar 7',
                'npsn' => null,
                'email' => null,
                'phone' => null,
                'address' => null,
                'logo_path' => null,
                'primary_color' => '#0f172a',
                'secondary_color' => '#f59e0b',
                'is_active' => true,
            ]
        );
    }
}
```

---

# 36. Isi `InitialUserSeeder`

Buka:

```text
database/seeders/InitialUserSeeder.php
```

Isi lengkap:

```php
<?php

namespace Database\Seeders;

use App\Models\ParentProfile;
use App\Models\Role;
use App\Models\School;
use App\Models\Student;
use App\Models\TeacherProfile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class InitialUserSeeder extends Seeder
{
    public function run(): void
    {
        $school = School::query()->where('code', 'ALAZHAR7')->firstOrFail();

        $roles = Role::query()
            ->whereIn('name', [
                'super_admin',
                'admin',
                'principal',
                'teacher',
                'parent',
                'student',
            ])
            ->get()
            ->keyBy('name');

        $defaultPassword = Hash::make('password');

        $superAdmin = User::query()->updateOrCreate(
            ['email' => 'superadmin@hafizplus.test'],
            [
                'role_id' => $roles['super_admin']->id,
                'school_id' => null,
                'name' => 'Super Admin',
                'username' => 'superadmin',
                'phone' => null,
                'password' => $defaultPassword,
                'is_active' => true,
            ]
        );

        $admin = User::query()->updateOrCreate(
            ['email' => 'admin@hafizplus.test'],
            [
                'role_id' => $roles['admin']->id,
                'school_id' => $school->id,
                'name' => 'Admin Sekolah',
                'username' => 'admin',
                'phone' => null,
                'password' => $defaultPassword,
                'is_active' => true,
            ]
        );

        $principal = User::query()->updateOrCreate(
            ['email' => 'kepalasekolah@hafizplus.test'],
            [
                'role_id' => $roles['principal']->id,
                'school_id' => $school->id,
                'name' => 'Kepala Sekolah',
                'username' => 'kepalasekolah',
                'phone' => null,
                'password' => $defaultPassword,
                'is_active' => true,
            ]
        );

        $teacher = User::query()->updateOrCreate(
            ['email' => 'guru@hafizplus.test'],
            [
                'role_id' => $roles['teacher']->id,
                'school_id' => $school->id,
                'name' => 'Guru Tahfidz',
                'username' => 'guru',
                'phone' => null,
                'password' => $defaultPassword,
                'is_active' => true,
            ]
        );

        TeacherProfile::query()->updateOrCreate(
            ['user_id' => $teacher->id],
            [
                'school_id' => $school->id,
                'employee_number' => 'GT-001',
                'specialization' => 'Tahfidz',
                'address' => null,
                'joined_at' => now()->toDateString(),
                'is_active' => true,
            ]
        );

        $parentUser = User::query()->updateOrCreate(
            ['email' => 'ortu@hafizplus.test'],
            [
                'role_id' => $roles['parent']->id,
                'school_id' => $school->id,
                'name' => 'Orang Tua Santri',
                'username' => 'ortu',
                'phone' => null,
                'password' => $defaultPassword,
                'is_active' => true,
            ]
        );

        $parentProfile = ParentProfile::query()->updateOrCreate(
            ['user_id' => $parentUser->id],
            [
                'school_id' => $school->id,
                'relationship' => 'Wali',
                'occupation' => null,
                'address' => null,
                'is_active' => true,
            ]
        );

        $studentUser = User::query()->updateOrCreate(
            ['email' => 'santri@hafizplus.test'],
            [
                'role_id' => $roles['student']->id,
                'school_id' => $school->id,
                'name' => 'Santri Contoh',
                'username' => 'santri',
                'phone' => null,
                'password' => $defaultPassword,
                'is_active' => true,
            ]
        );

        $student = Student::query()->updateOrCreate(
            ['user_id' => $studentUser->id],
            [
                'school_id' => $school->id,
                'class_room_id' => null,
                'student_number' => 'S-001',
                'nisn' => null,
                'full_name' => 'Santri Contoh',
                'nickname' => 'Santri',
                'gender' => null,
                'birth_place' => null,
                'birth_date' => null,
                'address' => null,
                'phone' => null,
                'program_type' => 'tahfizh',
                'is_active' => true,
            ]
        );

        $parentProfile->students()->syncWithoutDetaching([
            $student->id => [
                'relationship' => 'Wali',
                'is_primary' => true,
            ],
        ]);
    }
}
```

---

# 37. Update `DatabaseSeeder`

Buka:

```text
database/seeders/DatabaseSeeder.php
```

Isi lengkap:

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            SchoolSeeder::class,
            InitialUserSeeder::class,
        ]);
    }
}
```

---

# 38. Jalankan Migration dan Seeder

Jalankan:

```powershell
php artisan migrate:fresh --seed
```

Jika sudah ada data penting, jangan pakai `fresh`. Tapi untuk project baru Phase 1, aman.

---

# 39. Jalankan Server

Jalankan:

```powershell
php artisan serve
```

Buka:

```text
http://127.0.0.1:8000
```

---

# 40. Akun Login Awal

Semua password:

```text
password
```

| Role           | Email                                                               | Username      |
| -------------- | ------------------------------------------------------------------- | ------------- |
| Super Admin    | [superadmin@hafizplus.test](mailto:superadmin@hafizplus.test)       | superadmin    |
| Admin Sekolah  | [admin@hafizplus.test](mailto:admin@hafizplus.test)                 | admin         |
| Kepala Sekolah | [kepalasekolah@hafizplus.test](mailto:kepalasekolah@hafizplus.test) | kepalasekolah |
| Guru Tahfidz   | [guru@hafizplus.test](mailto:guru@hafizplus.test)                   | guru          |
| Orang Tua      | [ortu@hafizplus.test](mailto:ortu@hafizplus.test)                   | ortu          |
| Santri         | [santri@hafizplus.test](mailto:santri@hafizplus.test)               | santri        |

Test login satu per satu.

Setiap role harus masuk ke dashboard masing-masing.

---

# 41. Validasi Route

Jalankan:

```powershell
php artisan route:list
```

Pastikan route ini ada:

```text
GET|HEAD  login
POST      login
POST      logout
GET|HEAD  dashboard
GET|HEAD  dashboard/super-admin
GET|HEAD  dashboard/admin
GET|HEAD  dashboard/kepala-sekolah
GET|HEAD  dashboard/guru
GET|HEAD  dashboard/orang-tua
GET|HEAD  dashboard/santri
```

---

# 42. Validasi Middleware

Test manual:

1. Login sebagai `guru@hafizplus.test`.
2. Buka `/dashboard/admin`.
3. Harus 403.
4. Buka `/dashboard/guru`.
5. Harus berhasil.

Test lain:

1. Login sebagai `ortu@hafizplus.test`.
2. Buka `/dashboard/santri`.
3. Harus 403.
4. Buka `/dashboard/orang-tua`.
5. Harus berhasil.

---

# 43. Buat Dokumentasi Phase 1

Buat file:

```text
docs/phase-1-auth-role-foundation.md
```

Isi:

````md
# Phase 1 — Auth, Role, and Initial Database Foundation

## Status

Phase 1 membangun fondasi teknis awal untuk HafizPlus School Platform.

## Output

1. Auth manual berbasis Blade.
2. Login dengan email atau username.
3. Logout.
4. Role user.
5. Middleware role.
6. Redirect dashboard berdasarkan role.
7. Dashboard placeholder per role.
8. Struktur database awal:
   - roles
   - schools
   - users dengan role dan school
   - class_rooms
   - teacher_profiles
   - parent_profiles
   - students
   - parent_student
9. Seeder role.
10. Seeder sekolah.
11. Seeder akun awal.

## Role

| Role | Name Internal | Fungsi |
|---|---|---|
| Super Admin | super_admin | Kelola sistem penuh |
| Admin Sekolah | admin | Kelola data sekolah |
| Kepala Sekolah | principal | Monitoring |
| Guru Tahfidz | teacher | Input setoran |
| Orang Tua | parent | Lihat progres anak |
| Santri | student | Lihat progres pribadi |

## Akun Awal

Semua password default:

```text
password
````

| Role           | Email                                                               | Username      |
| -------------- | ------------------------------------------------------------------- | ------------- |
| Super Admin    | [superadmin@hafizplus.test](mailto:superadmin@hafizplus.test)       | superadmin    |
| Admin Sekolah  | [admin@hafizplus.test](mailto:admin@hafizplus.test)                 | admin         |
| Kepala Sekolah | [kepalasekolah@hafizplus.test](mailto:kepalasekolah@hafizplus.test) | kepalasekolah |
| Guru Tahfidz   | [guru@hafizplus.test](mailto:guru@hafizplus.test)                   | guru          |
| Orang Tua      | [ortu@hafizplus.test](mailto:ortu@hafizplus.test)                   | ortu          |
| Santri         | [santri@hafizplus.test](mailto:santri@hafizplus.test)               | santri        |

## Validasi

Phase 1 selesai jika:

1. Semua migration berhasil.
2. Seeder berhasil.
3. Login berhasil.
4. Logout berhasil.
5. Setiap role masuk dashboard masing-masing.
6. Middleware role memblokir akses yang salah.
7. `php artisan route:list` menampilkan semua route dashboard.
8. Tidak ada fitur tahfizh detail yang dibuat dulu.

## Catatan

Phase 1 belum membuat input setoran, target hafalan, hutang hafalan, report, atau notifikasi real.

Fitur tersebut masuk fase berikutnya.

````

---

# 44. Build Frontend

Jalankan:

```powershell
npm run build
````

Jika menggunakan `npm run dev`, jalankan di terminal terpisah.

---

# 45. Commit Phase 1

Jalankan:

```powershell
git status
git add .
git commit -m "feat: add auth role and initial database foundation"
```

Jika remote sudah ada:

```powershell
git push origin phase-1-auth-role-foundation
```

---

# 46. Definition of Done Phase 1

Phase 1 selesai jika:

1. Laravel 12 tetap berjalan.
2. Migration berhasil.
3. Seeder berhasil.
4. Login berhasil.
5. Logout berhasil.
6. Semua role bisa login.
7. Semua role diarahkan ke dashboard masing-masing.
8. Middleware role bekerja.
9. User non-authorized mendapat 403.
10. Dokumentasi Phase 1 dibuat.
11. Git commit berhasil.

---

# 47. Output Akhir yang Harus Dilaporkan Agent

Setelah selesai, agent harus melaporkan:

```text
Phase 1 selesai.

Project:
- HafizPlus School Platform
- Laravel 12
- MySQL
- Auth manual Blade
- Role middleware aktif
- Dashboard placeholder aktif

Migration dibuat:
- roles
- schools
- users role/school fields
- class_rooms
- teacher_profiles
- parent_profiles
- students
- parent_student

Seeder dibuat:
- RoleSeeder
- SchoolSeeder
- InitialUserSeeder

Akun login:
- superadmin@hafizplus.test / password
- admin@hafizplus.test / password
- kepalasekolah@hafizplus.test / password
- guru@hafizplus.test / password
- ortu@hafizplus.test / password
- santri@hafizplus.test / password

Belum dibuat:
- Input setoran
- Target hafalan
- Hutang hafalan
- Report
- Notifikasi real
- Parent progress detail
- Student progress detail

Status:
- Siap lanjut Phase 2 setelah validasi manual.
```

---

# 48. Larangan Setelah Phase 1

Agent harus berhenti setelah Phase 1 selesai.

Jangan lanjut membuat:

1. CRUD santri lengkap.
2. CRUD kelas lengkap.
3. CRUD guru lengkap.
4. Input setoran hafalan.
5. Target hafalan.
6. Sequential validation.
7. Report bulanan.
8. Report triwulan.
9. Parent portal detail.
10. Student portal detail.
11. Notification center.

Semua itu masuk Phase berikutnya.
