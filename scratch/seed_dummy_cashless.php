<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\School;
use App\Models\Role;
use App\Models\User;
use App\Models\UserSchoolMembership;
use App\Models\Student;
use App\Models\CashlessWallet;
use App\Models\CashlessMerchant;
use App\Models\CashlessMerchantUser;
use App\Models\CashlessPosSession;
use App\Models\CashlessProduct;
use App\Models\CashlessSale;
use App\Models\CashlessSaleItem;
use App\Models\CashlessWalletTransaction;
use App\Models\CashlessRefund;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

echo "Starting Cashless Dummy Data Seeding...\n";

DB::transaction(function () {
    $school = School::query()->where(['code' => 'ALAZHAR7'])->firstOrFail();
    $schoolId = $school->id;

    // 1. Roles Check/Ensure
    $cashierRole = Role::query()->firstOrCreate(
        ['name' => 'cashier'],
        ['label' => 'Cashier', 'description' => 'Cashier role', 'is_active' => true]
    );

    $financeRole = Role::query()->firstOrCreate(
        ['name' => 'finance'],
        ['label' => 'Finance', 'description' => 'Finance role', 'is_active' => true]
    );

    // 2. Clean existing cashless transactions & wallets to avoid duplicates/conflicts
    echo "Cleaning old transaction records...\n";
    CashlessRefund::query()->where(['school_id' => $schoolId])->delete();
    CashlessWalletTransaction::query()->where(['school_id' => $schoolId])->delete();
    CashlessSaleItem::query()->where(['school_id' => $schoolId])->delete();
    CashlessSale::query()->where(['school_id' => $schoolId])->delete();
    CashlessPosSession::query()->where(['school_id' => $schoolId])->delete();
    CashlessWallet::query()->where(['school_id' => $schoolId])->delete();
    CashlessMerchantUser::query()->where(['school_id' => $schoolId])->delete();

    // 3. Create/Update Cashier & Finance Users
    echo "Seeding Cashier & Finance users...\n";
    $defaultPassword = Hash::make('password');

    $cashierUser = User::query()->updateOrCreate(
        ['email' => 'kasir@hafizplus.test'],
        [
            'role_id' => $cashierRole->id,
            'school_id' => $schoolId,
            'name' => 'Kasir Kantin',
            'username' => 'kasir',
            'password' => $defaultPassword,
            'password_plain' => Crypt::encryptString('password'),
            'is_active' => true,
        ]
    );

    $financeUser = User::query()->updateOrCreate(
        ['email' => 'keuangan@hafizplus.test'],
        [
            'role_id' => $financeRole->id,
            'school_id' => $schoolId,
            'name' => 'Staf Keuangan',
            'username' => 'keuangan',
            'password' => $defaultPassword,
            'password_plain' => Crypt::encryptString('password'),
            'is_active' => true,
        ]
    );

    UserSchoolMembership::query()->updateOrCreate(
        ['user_id' => $cashierUser->id, 'school_id' => $schoolId],
        ['role_id' => $cashierRole->id, 'membership_status' => 'active']
    );

    UserSchoolMembership::query()->updateOrCreate(
        ['user_id' => $financeUser->id, 'school_id' => $schoolId],
        ['role_id' => $financeRole->id, 'membership_status' => 'active']
    );

    // Link Cashier to Merchant Kantin Utama (ID 1)
    $merchantKantin = CashlessMerchant::query()->where(['school_id' => $schoolId, 'code' => 'KANTIN'])->firstOrFail();
    $merchantKoperasi = CashlessMerchant::query()->where(['school_id' => $schoolId, 'code' => 'KOPERASI'])->firstOrFail();

    CashlessMerchantUser::query()->create([
        'school_id' => $schoolId,
        'cashless_merchant_id' => $merchantKantin->id,
        'user_id' => $cashierUser->id,
        'is_active' => true,
    ]);

    // 4. Create Wallets for students
    echo "Creating wallets...\n";
    $student19 = Student::findOrFail(19); // Santri Contoh
    $student20 = Student::findOrFail(20); // Santri Contoh 2
    $student21 = Student::findOrFail(21); // Santri Contoh 3

    // Wallet 19: Rp 150.000, no PIN, no daily limit
    $wallet19 = CashlessWallet::create([
        'school_id' => $schoolId,
        'student_id' => $student19->id,
        'wallet_number' => 'W5719001',
        'balance' => 150000,
        'pin' => null,
        'daily_limit' => null,
        'status' => 'active',
    ]);

    // Wallet 20: Rp 250.000, PIN 123456 (hashed), daily limit Rp 50.000
    $wallet20 = CashlessWallet::create([
        'school_id' => $schoolId,
        'student_id' => $student20->id,
        'wallet_number' => 'W5720002',
        'balance' => 250000,
        'pin' => Hash::make('123456'),
        'daily_limit' => 50000,
        'status' => 'active',
    ]);

    // Wallet 21: Rp 35.000, no PIN, daily limit Rp 15.000
    $wallet21 = CashlessWallet::create([
        'school_id' => $schoolId,
        'student_id' => $student21->id,
        'wallet_number' => 'W5721003',
        'balance' => 35000,
        'pin' => null,
        'daily_limit' => 15000,
        'status' => 'active',
    ]);

    // 5. Create POS Sessions
    echo "Creating POS Sessions...\n";
    // Kantin Utama open session with cashier
    $sessionKantin = CashlessPosSession::create([
        'school_id' => $schoolId,
        'cashless_merchant_id' => $merchantKantin->id,
        'cashier_id' => $cashierUser->id,
        'session_number' => 'SESS-KNT-001',
        'shift_name' => 'Shift Pagi',
        'opened_at' => now(),
        'status' => 'open',
    ]);

    // Koperasi Sekolah open session with admin
    $adminUser = User::query()->where(['email' => 'admin@hafizplus.test'])->firstOrFail();
    $sessionKoperasi = CashlessPosSession::create([
        'school_id' => $schoolId,
        'cashless_merchant_id' => $merchantKoperasi->id,
        'cashier_id' => $adminUser->id,
        'session_number' => 'SESS-KOP-001',
        'shift_name' => 'Shift Pagi',
        'opened_at' => now(),
        'status' => 'open',
    ]);

    // 6. Seed past and current transactions
    echo "Seeding historical transactions...\n";
    $productAyam = CashlessProduct::query()->where(['cashless_merchant_id' => $merchantKantin->id, 'sku' => 'NA-001'])->firstOrFail(); // Price: 15000
    $productAir = CashlessProduct::query()->where(['cashless_merchant_id' => $merchantKantin->id, 'sku' => 'AM-001'])->firstOrFail();  // Price: 5000
    $productRoti = CashlessProduct::query()->where(['cashless_merchant_id' => $merchantKantin->id, 'sku' => 'RT-001'])->firstOrFail(); // Price: 7000
    $productBuku = CashlessProduct::query()->where(['cashless_merchant_id' => $merchantKoperasi->id, 'sku' => 'BT-001'])->firstOrFail(); // Price: 6000

    // Day 1 (two days ago)
    $dateDay1 = now()->subDays(2);
    
    // Sale 1: Student 19 buys Nasi Ayam (15000)
    $sale1 = CashlessSale::create([
        'school_id' => $schoolId,
        'cashless_merchant_id' => $merchantKantin->id,
        'cashless_pos_session_id' => $sessionKantin->id,
        'cashier_id' => $cashierUser->id,
        'student_id' => $student19->id,
        'cashless_wallet_id' => $wallet19->id,
        'transaction_number' => 'SAL-DAY1-01',
        'receipt_number' => 'RCP-DAY1-01',
        'idempotency_key' => (string) Str::uuid(),
        'total_amount' => 15000,
        'status' => 'posted',
        'note' => 'Beli Nasi Ayam Pagi',
        'posted_at' => $dateDay1,
        'created_at' => $dateDay1,
    ]);

    CashlessSaleItem::create([
        'school_id' => $schoolId,
        'cashless_sale_id' => $sale1->id,
        'cashless_product_id' => $productAyam->id,
        'product_name' => $productAyam->name,
        'product_sku' => $productAyam->sku,
        'unit_price' => $productAyam->price,
        'quantity' => 1,
        'subtotal' => 15000,
        'created_at' => $dateDay1,
    ]);

    CashlessWalletTransaction::create([
        'school_id' => $schoolId,
        'cashless_wallet_id' => $wallet19->id,
        'student_id' => $student19->id,
        'cashless_sale_id' => $sale1->id,
        'actor_id' => $cashierUser->id,
        'transaction_number' => 'PUR-DAY1-01',
        'type' => 'purchase',
        'direction' => 'debit',
        'amount' => 15000,
        'balance_before' => 165000,
        'balance_after' => 150000,
        'note' => 'Pembelian ' . $merchantKantin->name,
        'status' => 'posted',
        'posted_at' => $dateDay1,
        'created_at' => $dateDay1,
    ]);

    // Sale 2: Student 20 buys Roti (7000)
    $sale2 = CashlessSale::create([
        'school_id' => $schoolId,
        'cashless_merchant_id' => $merchantKantin->id,
        'cashless_pos_session_id' => $sessionKantin->id,
        'cashier_id' => $cashierUser->id,
        'student_id' => $student20->id,
        'cashless_wallet_id' => $wallet20->id,
        'transaction_number' => 'SAL-DAY1-02',
        'receipt_number' => 'RCP-DAY1-02',
        'idempotency_key' => (string) Str::uuid(),
        'total_amount' => 7000,
        'status' => 'posted',
        'note' => 'Beli Roti',
        'posted_at' => $dateDay1,
        'created_at' => $dateDay1,
    ]);

    CashlessSaleItem::create([
        'school_id' => $schoolId,
        'cashless_sale_id' => $sale2->id,
        'cashless_product_id' => $productRoti->id,
        'product_name' => $productRoti->name,
        'product_sku' => $productRoti->sku,
        'unit_price' => $productRoti->price,
        'quantity' => 1,
        'subtotal' => 7000,
        'created_at' => $dateDay1,
    ]);

    CashlessWalletTransaction::create([
        'school_id' => $schoolId,
        'cashless_wallet_id' => $wallet20->id,
        'student_id' => $student20->id,
        'cashless_sale_id' => $sale2->id,
        'actor_id' => $cashierUser->id,
        'transaction_number' => 'PUR-DAY1-02',
        'type' => 'purchase',
        'direction' => 'debit',
        'amount' => 7000,
        'balance_before' => 257000,
        'balance_after' => 250000,
        'note' => 'Pembelian ' . $merchantKantin->name,
        'status' => 'posted',
        'posted_at' => $dateDay1,
        'created_at' => $dateDay1,
    ]);

    // Day 2 (yesterday)
    $dateDay2 = now()->subDays(1);

    // Sale 3: Student 19 buys Air Mineral (5000)
    $sale3 = CashlessSale::create([
        'school_id' => $schoolId,
        'cashless_merchant_id' => $merchantKantin->id,
        'cashless_pos_session_id' => $sessionKantin->id,
        'cashier_id' => $cashierUser->id,
        'student_id' => $student19->id,
        'cashless_wallet_id' => $wallet19->id,
        'transaction_number' => 'SAL-DAY2-01',
        'receipt_number' => 'RCP-DAY2-01',
        'idempotency_key' => (string) Str::uuid(),
        'total_amount' => 5000,
        'status' => 'posted',
        'note' => 'Beli Air Mineral',
        'posted_at' => $dateDay2,
        'created_at' => $dateDay2,
    ]);

    CashlessSaleItem::create([
        'school_id' => $schoolId,
        'cashless_sale_id' => $sale3->id,
        'cashless_product_id' => $productAir->id,
        'product_name' => $productAir->name,
        'product_sku' => $productAir->sku,
        'unit_price' => $productAir->price,
        'quantity' => 1,
        'subtotal' => 5000,
        'created_at' => $dateDay2,
    ]);

    CashlessWalletTransaction::create([
        'school_id' => $schoolId,
        'cashless_wallet_id' => $wallet19->id,
        'student_id' => $student19->id,
        'cashless_sale_id' => $sale3->id,
        'actor_id' => $cashierUser->id,
        'transaction_number' => 'PUR-DAY2-01',
        'type' => 'purchase',
        'direction' => 'debit',
        'amount' => 5000,
        'balance_before' => 155000,
        'balance_after' => 150000,
        'note' => 'Pembelian ' . $merchantKantin->name,
        'status' => 'posted',
        'posted_at' => $dateDay2,
        'created_at' => $dateDay2,
    ]);

    // Day 3 (today)
    $dateDay3 = now();

    // Sale 4: Student 21 buys Buku Tulis (6000)
    $sale4 = CashlessSale::create([
        'school_id' => $schoolId,
        'cashless_merchant_id' => $merchantKoperasi->id,
        'cashless_pos_session_id' => $sessionKoperasi->id,
        'cashier_id' => $adminUser->id,
        'student_id' => $student21->id,
        'cashless_wallet_id' => $wallet21->id,
        'transaction_number' => 'SAL-DAY3-01',
        'receipt_number' => 'RCP-DAY3-01',
        'idempotency_key' => (string) Str::uuid(),
        'total_amount' => 6000,
        'status' => 'posted',
        'note' => 'Beli Buku Tulis Koperasi',
        'posted_at' => $dateDay3,
        'created_at' => $dateDay3,
    ]);

    CashlessSaleItem::create([
        'school_id' => $schoolId,
        'cashless_sale_id' => $sale4->id,
        'cashless_product_id' => $productBuku->id,
        'product_name' => $productBuku->name,
        'product_sku' => $productBuku->sku,
        'unit_price' => $productBuku->price,
        'quantity' => 1,
        'subtotal' => 6000,
        'created_at' => $dateDay3,
    ]);

    CashlessWalletTransaction::create([
        'school_id' => $schoolId,
        'cashless_wallet_id' => $wallet21->id,
        'student_id' => $student21->id,
        'cashless_sale_id' => $sale4->id,
        'actor_id' => $adminUser->id,
        'transaction_number' => 'PUR-DAY3-01',
        'type' => 'purchase',
        'direction' => 'debit',
        'amount' => 6000,
        'balance_before' => 41000,
        'balance_after' => 35000,
        'note' => 'Pembelian ' . $merchantKoperasi->name,
        'status' => 'posted',
        'posted_at' => $dateDay3,
        'created_at' => $dateDay3,
    ]);

    // Update POS Session Sales Counters
    $sessionKantin->update([
        'total_sales' => 27000,
        'sales_count' => 3,
    ]);

    $sessionKoperasi->update([
        'total_sales' => 6000,
        'sales_count' => 1,
    ]);
});

echo "Cashless Dummy Data Seeded Successfully!\n";
