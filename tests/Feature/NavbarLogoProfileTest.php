<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class NavbarLogoProfileTest extends TestCase
{
    use RefreshDatabase;

    private User $superAdmin;
    private User $teacher;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles
        $this->seed(\Database\Seeders\RoleSeeder::class);

        $superAdminRole = Role::query()->where('name', 'super_admin')->firstOrFail();
        $teacherRole = Role::query()->where('name', 'teacher')->firstOrFail();

        // Create Super Admin
        $this->superAdmin = User::query()->create([
            'role_id' => $superAdminRole->id,
            'name' => 'Super Admin Test',
            'username' => 'superadmin_test',
            'email' => 'superadmin@hafizplus.test',
            'password' => bcrypt('password'),
            'is_active' => true,
        ]);

        // Create Teacher User
        $this->teacher = User::query()->create([
            'role_id' => $teacherRole->id,
            'name' => 'Teacher Test',
            'username' => 'teacher_test',
            'email' => 'teacher@hafizplus.test',
            'password' => bcrypt('password'),
            'is_active' => true,
        ]);
    }

    /**
     * Test that Super Admin can upload and delete the global system logo.
     */
    public function test_super_admin_can_manage_global_logo(): void
    {
        Storage::fake('public');

        $this->actingAs($this->superAdmin);

        // Upload custom logo
        $logoFile = UploadedFile::fake()->image('custom_logo.png');

        $response = $this->post(route('super-admin.update-logo'), [
            'logo' => $logoFile,
        ]);

        $response->assertRedirect();
        Storage::disk('public')->assertExists('system/logo.png');

        // Delete custom logo
        $response = $this->post(route('super-admin.update-logo'), [
            'delete_logo' => '1',
        ]);

        $response->assertRedirect();
        Storage::disk('public')->assertMissing('system/logo.png');
    }

    /**
     * Test that user can access profile pages and upload profile picture.
     */
    public function test_user_can_manage_profile_picture(): void
    {
        Storage::fake('public');

        $this->actingAs($this->teacher);

        // Access show page
        $response = $this->get(route('profile.show'));
        $response->assertStatus(200);

        // Access edit page
        $response = $this->get(route('profile.edit'));
        $response->assertStatus(200);

        // Upload profile picture
        $avatarFile = UploadedFile::fake()->image('avatar.jpg');

        $response = $this->put(route('profile.update'), [
            'profile_picture' => $avatarFile,
        ]);

        $response->assertRedirect(route('profile.show'));
        
        $this->teacher->refresh();
        $this->assertNotNull($this->teacher->profile_picture);
        Storage::disk('public')->assertExists($this->teacher->profile_picture);
    }
}
