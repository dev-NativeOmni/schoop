<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class LoginThrottleTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RoleSeeder::class);

        $superAdminRole = Role::query()->where('name', 'super_admin')->firstOrFail();

        $this->user = User::query()->create([
            'role_id' => $superAdminRole->id,
            'name' => 'Throttle Test User',
            'username' => 'throttle_test',
            'email' => 'throttle@hafizplus.test',
            'password' => bcrypt('correct-password'),
            'is_active' => true,
        ]);
    }

    public function test_login_is_throttled_after_too_many_failed_attempts(): void
    {
        for ($i = 0; $i < 8; $i++) {
            $response = $this->post(route('login.store'), [
                'login' => 'throttle_test',
                'password' => 'wrong-password',
            ]);

            $response->assertSessionHasErrors('login');
            $this->assertStringContainsString(
                'Login gagal',
                session('errors')->get('login')[0]
            );
        }

        $response = $this->post(route('login.store'), [
            'login' => 'throttle_test',
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors('login');
        $this->assertStringContainsString(
            'Terlalu banyak percobaan',
            session('errors')->get('login')[0]
        );
    }

    public function test_successful_login_clears_the_throttle_counter(): void
    {
        for ($i = 0; $i < 3; $i++) {
            $this->post(route('login.store'), [
                'login' => 'throttle_test',
                'password' => 'wrong-password',
            ]);
        }

        $response = $this->post(route('login.store'), [
            'login' => 'throttle_test',
            'password' => 'correct-password',
        ]);

        $response->assertRedirect();
        $this->assertAuthenticatedAs($this->user);

        $throttleKey = 'login|127.0.0.1|throttle_test';
        $this->assertSame(0, RateLimiter::attempts($throttleKey));
    }
}
