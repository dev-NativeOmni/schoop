<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use App\Notifications\ResetPasswordNotification;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RoleSeeder::class);

        $role = Role::query()->where('name', 'teacher')->firstOrFail();

        $this->user = User::query()->create([
            'role_id' => $role->id,
            'name' => 'Reset Test User',
            'username' => 'reset_test',
            'email' => 'reset_test@hafizplus.test',
            'password' => bcrypt('old-password'),
            'is_active' => true,
        ]);
    }

    public function test_forgot_password_form_can_be_rendered(): void
    {
        $response = $this->get(route('password.request'));

        $response->assertStatus(200);
    }

    public function test_forgot_password_request_sends_reset_notification_for_existing_email(): void
    {
        Notification::fake();

        $response = $this->post(route('password.email'), [
            'email' => $this->user->email,
        ]);

        $response->assertSessionHas('success');
        Notification::assertSentTo($this->user, ResetPasswordNotification::class);
    }

    public function test_forgot_password_request_shows_generic_message_for_unknown_email(): void
    {
        Notification::fake();

        $response = $this->post(route('password.email'), [
            'email' => 'does-not-exist@hafizplus.test',
        ]);

        // Same generic message regardless of whether the email exists,
        // to avoid leaking which accounts are registered.
        $response->assertSessionHas('success');
        Notification::assertNothingSent();
    }

    public function test_reset_password_form_can_be_rendered(): void
    {
        $response = $this->get(route('password.reset', ['token' => 'some-token', 'email' => $this->user->email]));

        $response->assertStatus(200);
    }

    public function test_user_can_reset_password_with_a_valid_token(): void
    {
        $token = app('auth.password.broker')->createToken($this->user);

        $response = $this->post(route('password.update'), [
            'token' => $token,
            'email' => $this->user->email,
            'password' => 'new-secure-password',
            'password_confirmation' => 'new-secure-password',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('success');

        $this->assertTrue(Hash::check('new-secure-password', $this->user->fresh()->password));
    }

    public function test_reset_fails_with_an_invalid_token(): void
    {
        $response = $this->post(route('password.update'), [
            'token' => 'clearly-not-a-valid-token',
            'email' => $this->user->email,
            'password' => 'new-secure-password',
            'password_confirmation' => 'new-secure-password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertTrue(Hash::check('old-password', $this->user->fresh()->password));
    }

    public function test_reset_fails_when_password_confirmation_does_not_match(): void
    {
        $token = app('auth.password.broker')->createToken($this->user);

        $response = $this->post(route('password.update'), [
            'token' => $token,
            'email' => $this->user->email,
            'password' => 'new-secure-password',
            'password_confirmation' => 'does-not-match',
        ]);

        $response->assertSessionHasErrors('password');
        $this->assertTrue(Hash::check('old-password', $this->user->fresh()->password));
    }

    public function test_reset_token_cannot_be_reused_after_a_successful_reset(): void
    {
        $token = app('auth.password.broker')->createToken($this->user);

        $this->post(route('password.update'), [
            'token' => $token,
            'email' => $this->user->email,
            'password' => 'new-secure-password',
            'password_confirmation' => 'new-secure-password',
        ]);

        // Token should be deleted after a successful reset.
        $exists = DB::table('password_reset_tokens')->where('email', $this->user->email)->exists();
        $this->assertFalse($exists);

        $response = $this->post(route('password.update'), [
            'token' => $token,
            'email' => $this->user->email,
            'password' => 'yet-another-password',
            'password_confirmation' => 'yet-another-password',
        ]);

        $response->assertSessionHasErrors('email');
    }
}
