<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\School;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ImpersonationTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_can_impersonate_a_teacher(): void
    {
        $superAdminRole = Role::create(['name' => 'super_admin', 'label' => 'Super Admin']);
        $teacherRole = Role::create(['name' => 'teacher', 'label' => 'Guru']);

        $school = School::create([
            'name' => 'Pesantren Al-Ikhlas',
            'code' => 'SCH01',
            'npsn' => '12345678',
            'is_active' => true,
        ]);

        $superAdmin = User::create([
            'role_id' => $superAdminRole->id,
            'name' => 'Super Admin Test',
            'username' => 'superadmin',
            'email' => 'super@example.com',
            'password' => bcrypt('password'),
            'is_active' => true,
        ]);

        $teacher = User::create([
            'role_id' => $teacherRole->id,
            'school_id' => $school->id,
            'name' => 'Ustadz Ahmad',
            'username' => 'ustadzahmad',
            'email' => 'ahmad@example.com',
            'password' => bcrypt('password'),
            'is_active' => true,
        ]);

        $response = $this->actingAs($superAdmin)
            ->post(route('impersonation.start', $teacher));

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($teacher);
        $this->assertEquals($superAdmin->id, session('impersonator_id'));
    }

    public function test_non_super_admin_cannot_impersonate_others(): void
    {
        $teacherRole = Role::create(['name' => 'teacher', 'label' => 'Guru']);
        $parentRole = Role::create(['name' => 'parent', 'label' => 'Wali Santri']);

        $school = School::create([
            'name' => 'Pesantren Al-Ikhlas',
            'code' => 'SCH02',
            'npsn' => '12345678',
            'is_active' => true,
        ]);

        $teacher = User::create([
            'role_id' => $teacherRole->id,
            'school_id' => $school->id,
            'name' => 'Ustadz Ahmad',
            'username' => 'ustadzahmad',
            'email' => 'ahmad@example.com',
            'password' => bcrypt('password'),
            'is_active' => true,
        ]);

        $parent = User::create([
            'role_id' => $parentRole->id,
            'school_id' => $school->id,
            'name' => 'Bapak Budi',
            'username' => 'bapakbudi',
            'email' => 'budi@example.com',
            'password' => bcrypt('password'),
            'is_active' => true,
        ]);

        $response = $this->actingAs($teacher)
            ->post(route('impersonation.start', $parent));

        $response->assertStatus(403);
    }

    public function test_super_admin_can_leave_impersonation(): void
    {
        $superAdminRole = Role::create(['name' => 'super_admin', 'label' => 'Super Admin']);
        $teacherRole = Role::create(['name' => 'teacher', 'label' => 'Guru']);

        $school = School::create([
            'name' => 'Pesantren Al-Ikhlas',
            'code' => 'SCH03',
            'npsn' => '12345678',
            'is_active' => true,
        ]);

        $superAdmin = User::create([
            'role_id' => $superAdminRole->id,
            'name' => 'Super Admin Test',
            'username' => 'superadmin',
            'email' => 'super@example.com',
            'password' => bcrypt('password'),
            'is_active' => true,
        ]);

        $teacher = User::create([
            'role_id' => $teacherRole->id,
            'school_id' => $school->id,
            'name' => 'Ustadz Ahmad',
            'username' => 'ustadzahmad',
            'email' => 'ahmad@example.com',
            'password' => bcrypt('password'),
            'is_active' => true,
        ]);

        // Start impersonation
        $this->actingAs($superAdmin)
            ->post(route('impersonation.start', $teacher));

        $this->assertAuthenticatedAs($teacher);

        // Leave impersonation
        $response = $this->post(route('impersonation.leave'));

        $response->assertRedirect(route('master-data.users.index'));
        $this->assertAuthenticatedAs($superAdmin);
        $this->assertNull(session('impersonator_id'));
    }
}
