<?php

namespace Tests\Feature;

use App\Models\CashlessMerchant;
use App\Models\CashlessPosSession;
use App\Models\CashlessProduct;
use App\Models\CashlessWallet;
use App\Models\ParentProfile;
use App\Models\Role;
use App\Models\School;
use App\Models\Student;
use App\Models\User;
use App\Models\UserSchoolMembership;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CashlessWalletSecurityTest extends TestCase
{
    use RefreshDatabase;

    private School $school;
    private User $admin;
    private User $parent;
    private User $unauthorizedParent;
    private User $student;
    private Student $studentProfile;
    private ParentProfile $parentProfile;
    private ParentProfile $unauthorizedParentProfile;
    private CashlessWallet $wallet;
    private CashlessMerchant $merchant;
    private CashlessPosSession $posSession;
    private CashlessProduct $product;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles
        if (Role::count() === 0) {
            $this->artisan('db:seed', ['--class' => 'RoleSeeder']);
        }

        $adminRole = Role::where('name', 'admin')->first();
        $parentRole = Role::where('name', 'parent')->first();
        $studentRole = Role::where('name', 'student')->first();

        // Create school
        $this->school = School::create(['name' => 'School Test', 'code' => 'SCTST', 'is_active' => true]);

        // Create Users
        $this->admin = User::create([
            'school_id' => $this->school->id,
            'role_id' => $adminRole->id,
            'name' => 'Admin Test',
            'username' => 'admin_test',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'is_active' => true,
        ]);

        $this->parent = User::create([
            'school_id' => $this->school->id,
            'role_id' => $parentRole->id,
            'name' => 'Parent Test',
            'username' => 'parent_test',
            'email' => 'parent@test.com',
            'password' => bcrypt('password'),
            'is_active' => true,
        ]);

        $this->unauthorizedParent = User::create([
            'school_id' => $this->school->id,
            'role_id' => $parentRole->id,
            'name' => 'Parent Unauthorized',
            'username' => 'parent_unauth',
            'email' => 'parent_unauth@test.com',
            'password' => bcrypt('password'),
            'is_active' => true,
        ]);

        $this->student = User::create([
            'school_id' => $this->school->id,
            'role_id' => $studentRole->id,
            'name' => 'Student Test',
            'username' => 'student_test',
            'email' => 'student@test.com',
            'password' => bcrypt('password'),
            'is_active' => true,
        ]);

        // Create profiles
        $this->studentProfile = Student::create([
            'user_id' => $this->student->id,
            'school_id' => $this->school->id,
            'student_number' => 'S123',
            'full_name' => 'Student Test Name',
            'is_active' => true,
        ]);

        $this->parentProfile = ParentProfile::create([
            'user_id' => $this->parent->id,
            'school_id' => $this->school->id,
            'full_name' => 'Parent Test Name',
            'is_active' => true,
        ]);

        $this->unauthorizedParentProfile = ParentProfile::create([
            'user_id' => $this->unauthorizedParent->id,
            'school_id' => $this->school->id,
            'full_name' => 'Parent Unauth Name',
            'is_active' => true,
        ]);

        // Link parent student
        $this->parentProfile->students()->attach($this->studentProfile->id);

        // Memberships
        UserSchoolMembership::create(['user_id' => $this->admin->id, 'school_id' => $this->school->id, 'role_id' => $adminRole->id, 'membership_status' => 'active']);
        UserSchoolMembership::create(['user_id' => $this->parent->id, 'school_id' => $this->school->id, 'role_id' => $parentRole->id, 'membership_status' => 'active']);
        UserSchoolMembership::create(['user_id' => $this->unauthorizedParent->id, 'school_id' => $this->school->id, 'role_id' => $parentRole->id, 'membership_status' => 'active']);
        UserSchoolMembership::create(['user_id' => $this->student->id, 'school_id' => $this->school->id, 'role_id' => $studentRole->id, 'membership_status' => 'active']);

        // Create Cashless Wallet
        $this->wallet = CashlessWallet::create([
            'school_id' => $this->school->id,
            'student_id' => $this->studentProfile->id,
            'wallet_number' => 'W12345',
            'balance' => 200000,
            'status' => 'active',
        ]);

        // Create Cashless Merchant
        $this->merchant = CashlessMerchant::create([
            'school_id' => $this->school->id,
            'name' => 'Canteen Test',
            'code' => 'MCNT01',
            'type' => 'canteen',
            'status' => 'active',
        ]);

        // Create POS session
        $this->posSession = CashlessPosSession::create([
            'school_id' => $this->school->id,
            'cashless_merchant_id' => $this->merchant->id,
            'cashier_id' => $this->admin->id,
            'session_number' => 'SESS-001',
            'opened_at' => now(),
            'status' => 'open',
        ]);

        // Create Cashless Product
        $this->product = CashlessProduct::create([
            'school_id' => $this->school->id,
            'cashless_merchant_id' => $this->merchant->id,
            'name' => 'Nasi Goreng',
            'sku' => 'NSG-01',
            'price' => 10000,
            'stock' => 10,
            'track_stock' => true,
            'status' => 'active',
        ]);
    }

    public function test_parent_can_update_wallet_pin_and_limit(): void
    {
        $this->actingAs($this->parent)
            ->withSession(['active_school_id' => $this->school->id]);

        $response = $this->put(route('portal.parent.cashless.update', $this->wallet), [
            'pin' => '123456',
            'daily_limit' => 50000,
        ]);

        $response->assertRedirect();
        $this->wallet->refresh();

        $this->assertEquals(50000, $this->wallet->daily_limit);
        $this->assertTrue(Hash::check('123456', $this->wallet->pin));
    }

    public function test_unauthorized_parent_cannot_update_wallet(): void
    {
        $this->actingAs($this->unauthorizedParent)
            ->withSession(['active_school_id' => $this->school->id]);

        $response = $this->put(route('portal.parent.cashless.update', $this->wallet), [
            'pin' => '123456',
            'daily_limit' => 50000,
        ]);

        $response->assertStatus(403);
    }

    public function test_sale_fails_if_daily_limit_exceeded(): void
    {
        $this->wallet->update(['daily_limit' => 15000]);

        $this->actingAs($this->admin)
            ->withSession(['active_school_id' => $this->school->id]);

        // First transaction (Rp 10.000) - Should succeed
        $payload = [
            'cashless_pos_session_id' => $this->posSession->id,
            'student_id' => $this->studentProfile->id,
            'idempotency_key' => 'idemp-1',
            'items' => [
                ['cashless_product_id' => $this->product->id, 'quantity' => 1]
            ]
        ];

        $response = $this->post(route('cashless.pos.sales.store'), $payload);
        $response->assertRedirect();

        // Second transaction (Rp 10.000, total Rp 20.000 > Rp 15.000 limit) - Should fail
        $payload2 = [
            'cashless_pos_session_id' => $this->posSession->id,
            'student_id' => $this->studentProfile->id,
            'idempotency_key' => 'idemp-2',
            'items' => [
                ['cashless_product_id' => $this->product->id, 'quantity' => 1]
            ]
        ];

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Limit pembelanjaan harian terlampaui.');

        // Calling direct service to assert the exception
        app(\App\Services\Cashless\CashlessSaleService::class)->createSale($payload2, $this->admin);
    }

    public function test_sale_requires_pin_for_high_value_transaction(): void
    {
        // Set PIN
        $this->wallet->update([
            'pin' => Hash::make('123456')
        ]);

        // Product above threshold (>= 50,000)
        $expensiveProduct = CashlessProduct::create([
            'school_id' => $this->school->id,
            'cashless_merchant_id' => $this->merchant->id,
            'name' => 'Premium Box',
            'sku' => 'PMB-01',
            'price' => 50000,
            'stock' => 5,
            'track_stock' => true,
            'status' => 'active',
        ]);

        $payload = [
            'cashless_pos_session_id' => $this->posSession->id,
            'student_id' => $this->studentProfile->id,
            'idempotency_key' => 'idemp-3',
            'items' => [
                ['cashless_product_id' => $expensiveProduct->id, 'quantity' => 1]
            ]
        ];

        // 1. Submit without PIN - Should throw InvalidArgumentException
        try {
            app(\App\Services\Cashless\CashlessSaleService::class)->createSale($payload, $this->admin);
            $this->fail('Expected InvalidArgumentException was not thrown for missing PIN.');
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString('PIN transaksi wajib diisi', $e->getMessage());
        }

        // 2. Submit with incorrect PIN - Should throw InvalidArgumentException
        $payload['pin'] = '999999';
        try {
            app(\App\Services\Cashless\CashlessSaleService::class)->createSale($payload, $this->admin);
            $this->fail('Expected InvalidArgumentException was not thrown for incorrect PIN.');
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString('PIN transaksi salah', $e->getMessage());
        }

        // 3. Submit with correct PIN - Should succeed
        $payload['pin'] = '123456';
        $sale = app(\App\Services\Cashless\CashlessSaleService::class)->createSale($payload, $this->admin);
        $this->assertNotNull($sale);
        $this->assertEquals('posted', $sale->status);
    }

    public function test_sale_does_not_require_pin_for_low_value_transaction(): void
    {
        $this->wallet->update([
            'pin' => Hash::make('123456')
        ]);

        // Low value (Rp 10.000) without PIN - Should succeed
        $payload = [
            'cashless_pos_session_id' => $this->posSession->id,
            'student_id' => $this->studentProfile->id,
            'idempotency_key' => 'idemp-4',
            'items' => [
                ['cashless_product_id' => $this->product->id, 'quantity' => 1]
            ]
        ];

        $sale = app(\App\Services\Cashless\CashlessSaleService::class)->createSale($payload, $this->admin);
        $this->assertNotNull($sale);
        $this->assertEquals('posted', $sale->status);
    }

    public function test_sale_works_without_pin_if_no_pin_configured(): void
    {
        // No PIN is set on wallet. Above threshold (Rp 50.000) without PIN - Should succeed
        $expensiveProduct = CashlessProduct::create([
            'school_id' => $this->school->id,
            'cashless_merchant_id' => $this->merchant->id,
            'name' => 'Premium Box 2',
            'sku' => 'PMB-02',
            'price' => 50000,
            'stock' => 5,
            'track_stock' => true,
            'status' => 'active',
        ]);

        $payload = [
            'cashless_pos_session_id' => $this->posSession->id,
            'student_id' => $this->studentProfile->id,
            'idempotency_key' => 'idemp-5',
            'items' => [
                ['cashless_product_id' => $expensiveProduct->id, 'quantity' => 1]
            ]
        ];

        $sale = app(\App\Services\Cashless\CashlessSaleService::class)->createSale($payload, $this->admin);
        $this->assertNotNull($sale);
        $this->assertEquals('posted', $sale->status);
    }
}
