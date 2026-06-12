# Phase 14 Execution Guide — Student Finance Ledger

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

Project folder lokal:

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

Phase saat ini:

```text
Phase 14 — Student Finance Ledger
```

---

# 1. Keputusan Sebelum Phase 14

## 1.1 UAT Phase 13 Wajib

Sebelum menjalankan Phase 14, agent wajib memastikan Phase 13 Tahsin sudah aman.

Jangan menambah Finance jika Tahsin masih rusak.

Phase 14 hanya boleh dieksekusi jika:

1. Level Tahsin berjalan.
2. Skill Tahsin berjalan.
3. Profil Tahsin Santri berjalan.
4. Asesmen Tahsin bisa dibuat.
5. Overall score otomatis benar.
6. Grade otomatis benar.
7. Report Tahsin tampil.
8. Parent hanya melihat Tahsin anak sendiri.
9. Student hanya melihat Tahsin pribadi.
10. Parent/student tidak bisa input asesmen.
11. Parent/student tidak bisa akses dashboard internal Tahsin.
12. `npm run build` berhasil.
13. `php artisan app:system-health-check` berhasil.
14. Tidak ada bug P0/P1 terbuka.

Jika masih ada error P0/P1, hentikan Phase 14 dan fix dulu.

---

## 1.2 Kenapa Finance Harus Sangat Ketat

Finance berbeda dari Tahfizh, Mutabaah, Attendance, dan Tahsin.

Kesalahan data progress masih bisa dikoreksi manual.
Kesalahan data uang bisa merusak kepercayaan sekolah dan orang tua.

Karena itu Phase 14 harus mengikuti prinsip:

1. Tidak boleh asal delete data transaksi.
2. Tidak boleh mengubah transaksi posted sembarangan.
3. Harus ada nomor invoice/tagihan.
4. Harus ada nomor bukti pembayaran.
5. Harus ada ledger entry.
6. Harus ada status transaksi.
7. Harus ada audit trail minimal.
8. Harus bisa melihat saldo/tagihan per santri.
9. Parent hanya melihat tagihan anak sendiri.
10. Student hanya melihat tagihan pribadi.
11. Payment gateway tidak dibuat di phase ini.
12. Cashless POS tidak dibuat di phase ini.

---

## 1.3 Klasifikasi Bug Sebelum Phase 14

| Prioritas | Contoh Bug                                                                                                               | Keputusan     |
| --------- | ------------------------------------------------------------------------------------------------------------------------ | ------------- |
| P0        | Login gagal, parent bisa melihat data anak lain, student bisa melihat data santri lain, payment bisa dihapus tanpa jejak | Wajib fix     |
| P1        | Saldo salah, tagihan salah hitung, pembayaran dobel, ledger tidak balance                                                | Wajib fix     |
| P2        | Tampilan kurang rapi, wording kurang jelas                                                                               | Boleh dicatat |
| P3        | Enhancement kosmetik                                                                                                     | Boleh ditunda |

---

# 2. Tujuan Phase 14

Phase 14 bertujuan membuat modul **Student Finance Ledger**.

Modul ini digunakan untuk:

1. Membuat kategori biaya.
2. Membuat item biaya.
3. Membuat tagihan santri.
4. Membuat detail item tagihan.
5. Mencatat pembayaran manual.
6. Mengalokasikan pembayaran ke tagihan.
7. Membuat ledger debit/kredit per santri.
8. Menghitung saldo tagihan santri.
9. Membuat laporan keuangan sederhana.
10. Menampilkan tagihan ke orang tua.
11. Menampilkan tagihan ke santri.
12. Menjaga role-based access.
13. Menjaga ownership-based access.
14. Dokumentasi Phase 14.

---

# 3. Batasan Phase 14

AI agent tidak boleh membuat fitur berikut pada Phase 14:

1. Payment gateway.
2. Midtrans.
3. Xendit.
4. Doku.
5. Tripay.
6. Duitku.
7. QRIS payment automation.
8. Cashless kantin.
9. Wallet/saldo internal.
10. Merchant POS.
11. Refund otomatis.
12. Rekonsiliasi bank otomatis.
13. Virtual account.
14. Auto payment callback.
15. Integrasi bank.
16. Payroll.
17. Accounting double-entry penuh.
18. Pajak.
19. Invoice PDF final.
20. WhatsApp billing reminder.
21. Push notification billing.
22. Mobile app.
23. White-label billing.
24. Multi-tenant finance kompleks.

Phase 14 hanya membuat:

```text
Student Finance Ledger manual berbasis Blade + MySQL.
```

---

# 4. Konsep Finance Ledger

## 4.1 Konsep Tagihan

Tagihan adalah kewajiban pembayaran santri.

Contoh tagihan:

1. SPP bulanan.
2. Biaya tahfizh.
3. Biaya kegiatan.
4. Biaya daftar ulang.
5. Biaya seragam.
6. Biaya buku.
7. Biaya ujian.
8. Biaya lain-lain.

---

## 4.2 Konsep Pembayaran

Pembayaran adalah uang yang diterima sekolah dan dicatat manual oleh admin.

Metode pembayaran Phase 14:

| Method          | Keterangan                             |
| --------------- | -------------------------------------- |
| `cash`          | Tunai                                  |
| `bank_transfer` | Transfer bank manual                   |
| `qris_external` | QRIS luar sistem, hanya dicatat manual |
| `adjustment`    | Koreksi manual oleh admin              |

Catatan:

`qris_external` bukan payment gateway.
Itu hanya pencatatan manual jika sekolah menerima pembayaran lewat QRIS bank/aplikasi lain.

---

## 4.3 Konsep Ledger

Ledger mencatat mutasi keuangan per santri.

| Entry       | Makna                        |
| ----------- | ---------------------------- |
| Debit       | Tagihan bertambah            |
| Credit      | Pembayaran/pengurang tagihan |
| Void debit  | Pembatalan tagihan           |
| Void credit | Pembatalan pembayaran        |

Rumus saldo:

```text
saldo_tagihan = total_debit - total_credit
```

Jika saldo > 0, santri masih punya tunggakan.

Jika saldo = 0, lunas.

Jika saldo < 0, ada kelebihan bayar.

---

## 4.4 Prinsip Immutable Transaction

Transaksi yang sudah `posted` tidak boleh diedit langsung.

Jika salah:

1. Buat status `void`.
2. Buat ledger entry pembalik.
3. Simpan alasan void.
4. Simpan user yang melakukan void.
5. Simpan waktu void.

Jangan menghapus transaksi finance dari database.

---

# 5. Target Output Phase 14

Setelah Phase 14 selesai, aplikasi harus punya:

1. Menu **Finance**.
2. Menu **Kategori Biaya**.
3. Menu **Item Biaya**.
4. Menu **Tagihan Santri**.
5. Menu **Pembayaran Santri**.
6. Menu **Ledger Santri**.
7. Menu **Laporan Finance**.
8. Portal tagihan orang tua.
9. Portal tagihan santri.
10. Tabel:

    * `finance_fee_categories`
    * `finance_fee_items`
    * `student_bills`
    * `student_bill_items`
    * `student_payments`
    * `student_payment_allocations`
    * `finance_ledger_entries`
11. Model:

    * `FinanceFeeCategory`
    * `FinanceFeeItem`
    * `StudentBill`
    * `StudentBillItem`
    * `StudentPayment`
    * `StudentPaymentAllocation`
    * `FinanceLedgerEntry`
12. Controller:

    * `FinanceFeeCategoryController`
    * `FinanceFeeItemController`
    * `StudentBillController`
    * `StudentPaymentController`
    * `FinanceLedgerController`
    * `FinanceReportController`
    * `ParentFinancePortalController`
    * `StudentFinancePortalController`
13. Request:

    * `StoreFinanceFeeCategoryRequest`
    * `UpdateFinanceFeeCategoryRequest`
    * `StoreFinanceFeeItemRequest`
    * `UpdateFinanceFeeItemRequest`
    * `StoreStudentBillRequest`
    * `StoreStudentPaymentRequest`
    * `FinanceReportFilterRequest`
14. Service:

    * `FinanceAccessService`
    * `InvoiceNumberGenerator`
    * `ReceiptNumberGenerator`
    * `StudentBillService`
    * `StudentPaymentService`
    * `StudentBalanceService`
    * `FinanceReportService`
15. Seeder:

    * `FinanceFeeCategorySeeder`
16. View:

    * category index/create/edit/show
    * item index/create/edit/show
    * bill index/create/show
    * payment index/create/show
    * ledger student
    * report dashboard
    * parent finance portal
    * student finance portal
17. Dokumentasi Phase 14.
18. Update `docs/project-progress.md`.

---

# 6. Role Access Phase 14

Sebelum coding, agent wajib cek role di database:

```powershell
php artisan tinker
```

Lalu:

```php
App\Models\Role::query()->pluck('name')->all();
```

Role default yang diasumsikan:

```text
super_admin
admin
admin_sekolah
kepala_sekolah
teacher
guru
guru_tahfidz
parent
student
```

| Role           | Master Biaya | Tagihan   | Pembayaran  | Ledger       | Report        | Portal |
| -------------- | ------------ | --------- | ----------- | ------------ | ------------- | ------ |
| Super Admin    | CRUD         | CRUD/Void | Create/Void | Semua        | Semua         | Tidak  |
| Admin          | CRUD         | CRUD/Void | Create/Void | Semua        | Semua         | Tidak  |
| Kepala Sekolah | Read-only    | Read-only | Read-only   | Semua        | Semua         | Tidak  |
| Teacher/Guru   | Tidak        | Tidak     | Tidak       | Tidak        | Tidak default | Tidak  |
| Parent         | Tidak        | Tidak     | Tidak       | Anak sendiri | Anak sendiri  | Ya     |
| Student        | Tidak        | Tidak     | Tidak       | Data sendiri | Data sendiri  | Ya     |

Aturan keras:

1. Parent hanya boleh melihat tagihan anak sendiri.
2. Student hanya boleh melihat tagihan pribadi.
3. Parent/student tidak boleh membuat pembayaran.
4. Parent/student tidak boleh mengubah tagihan.
5. Teacher/guru tidak boleh akses finance default.
6. Kepala sekolah read-only.
7. Admin boleh membuat tagihan dan pembayaran.
8. Posted payment tidak boleh diedit langsung.
9. Posted bill tidak boleh diedit langsung.
10. Void harus meninggalkan jejak.

---

# 7. Validasi Awal Sebelum Eksekusi

Jalankan:

```powershell
cd C:\xampp\htdocs\hafizplus-school-platform

php artisan --version
php -v
composer -V
npm -v
php artisan migrate:status
php artisan route:list
php artisan app:system-health-check
npm run build
git status
```

Target:

1. Laravel 12 berjalan.
2. Phase 0 selesai.
3. Phase 1 selesai.
4. Phase 2 selesai.
5. Phase 3 selesai.
6. Phase 4 selesai.
7. Phase 5 selesai.
8. Phase 6 selesai.
9. Phase 7 selesai.
10. Phase 8 selesai.
11. Phase 9 selesai.
12. Phase 10 selesai.
13. Phase 11 selesai.
14. Phase 12 selesai.
15. Phase 13 selesai dan UAT aman.
16. Tabel berikut sudah ada:

    * `users`
    * `roles`
    * `schools`
    * `class_rooms`
    * `students`
    * `parent_profiles`
    * `parent_student`
17. Working tree bersih atau semua perubahan diketahui.

Jika Phase 13 belum aman, hentikan Phase 14.

---

# 8. Buat Branch Git Phase 14

Jalankan:

```powershell
git checkout -b phase-14-student-finance-ledger
```

Jika branch sudah ada:

```powershell
git checkout phase-14-student-finance-ledger
```

---

# 9. Struktur File yang Akan Dibuat

Agent harus membuat atau mengubah file berikut:

```text
app/
├── Http/
│   ├── Controllers/
│   │   ├── Finance/
│   │   │   ├── FinanceFeeCategoryController.php
│   │   │   ├── FinanceFeeItemController.php
│   │   │   ├── StudentBillController.php
│   │   │   ├── StudentPaymentController.php
│   │   │   ├── FinanceLedgerController.php
│   │   │   └── FinanceReportController.php
│   │   └── Portal/
│   │       ├── ParentFinancePortalController.php
│   │       └── StudentFinancePortalController.php
│   └── Requests/
│       └── Finance/
│           ├── StoreFinanceFeeCategoryRequest.php
│           ├── UpdateFinanceFeeCategoryRequest.php
│           ├── StoreFinanceFeeItemRequest.php
│           ├── UpdateFinanceFeeItemRequest.php
│           ├── StoreStudentBillRequest.php
│           ├── StoreStudentPaymentRequest.php
│           └── FinanceReportFilterRequest.php
├── Models/
│   ├── FinanceFeeCategory.php
│   ├── FinanceFeeItem.php
│   ├── StudentBill.php
│   ├── StudentBillItem.php
│   ├── StudentPayment.php
│   ├── StudentPaymentAllocation.php
│   └── FinanceLedgerEntry.php
└── Services/
    └── Finance/
        ├── FinanceAccessService.php
        ├── InvoiceNumberGenerator.php
        ├── ReceiptNumberGenerator.php
        ├── StudentBillService.php
        ├── StudentPaymentService.php
        ├── StudentBalanceService.php
        └── FinanceReportService.php

database/
├── migrations/
│   ├── xxxx_xx_xx_xxxxxx_create_finance_fee_categories_table.php
│   ├── xxxx_xx_xx_xxxxxx_create_finance_fee_items_table.php
│   ├── xxxx_xx_xx_xxxxxx_create_student_bills_table.php
│   ├── xxxx_xx_xx_xxxxxx_create_student_bill_items_table.php
│   ├── xxxx_xx_xx_xxxxxx_create_student_payments_table.php
│   ├── xxxx_xx_xx_xxxxxx_create_student_payment_allocations_table.php
│   └── xxxx_xx_xx_xxxxxx_create_finance_ledger_entries_table.php
└── seeders/
    └── FinanceFeeCategorySeeder.php

resources/
└── views/
    ├── finance/
    │   ├── fee-categories/
    │   │   ├── index.blade.php
    │   │   ├── create.blade.php
    │   │   ├── edit.blade.php
    │   │   └── show.blade.php
    │   ├── fee-items/
    │   │   ├── index.blade.php
    │   │   ├── create.blade.php
    │   │   ├── edit.blade.php
    │   │   └── show.blade.php
    │   ├── bills/
    │   │   ├── index.blade.php
    │   │   ├── create.blade.php
    │   │   └── show.blade.php
    │   ├── payments/
    │   │   ├── index.blade.php
    │   │   ├── create.blade.php
    │   │   └── show.blade.php
    │   ├── ledgers/
    │   │   └── student.blade.php
    │   └── reports/
    │       └── dashboard.blade.php
    └── portal/
        ├── parent/
        │   └── finance.blade.php
        └── student/
            └── finance.blade.php

routes/
└── web.php

docs/
├── phase-14-execution.md
└── phase-14-student-finance-ledger.md
```

---

# 10. Buat Model, Migration, Seeder, Controller, Request

Jalankan:

```powershell
php artisan make:model FinanceFeeCategory -m
php artisan make:model FinanceFeeItem -m
php artisan make:model StudentBill -m
php artisan make:model StudentBillItem -m
php artisan make:model StudentPayment -m
php artisan make:model StudentPaymentAllocation -m
php artisan make:model FinanceLedgerEntry -m

php artisan make:seeder FinanceFeeCategorySeeder

php artisan make:controller Finance/FinanceFeeCategoryController --resource
php artisan make:controller Finance/FinanceFeeItemController --resource
php artisan make:controller Finance/StudentBillController
php artisan make:controller Finance/StudentPaymentController
php artisan make:controller Finance/FinanceLedgerController
php artisan make:controller Finance/FinanceReportController
php artisan make:controller Portal/ParentFinancePortalController
php artisan make:controller Portal/StudentFinancePortalController

php artisan make:request Finance/StoreFinanceFeeCategoryRequest
php artisan make:request Finance/UpdateFinanceFeeCategoryRequest
php artisan make:request Finance/StoreFinanceFeeItemRequest
php artisan make:request Finance/UpdateFinanceFeeItemRequest
php artisan make:request Finance/StoreStudentBillRequest
php artisan make:request Finance/StoreStudentPaymentRequest
php artisan make:request Finance/FinanceReportFilterRequest
```

Buat folder service:

```powershell
mkdir app\Services\Finance
```

Buat file service:

```powershell
New-Item app\Services\Finance\FinanceAccessService.php
New-Item app\Services\Finance\InvoiceNumberGenerator.php
New-Item app\Services\Finance\ReceiptNumberGenerator.php
New-Item app\Services\Finance\StudentBillService.php
New-Item app\Services\Finance\StudentPaymentService.php
New-Item app\Services\Finance\StudentBalanceService.php
New-Item app\Services\Finance\FinanceReportService.php
```

Buat folder view:

```powershell
mkdir resources\views\finance
mkdir resources\views\finance\fee-categories
mkdir resources\views\finance\fee-items
mkdir resources\views\finance\bills
mkdir resources\views\finance\payments
mkdir resources\views\finance\ledgers
mkdir resources\views\finance\reports
```

Jika folder portal sudah ada, jangan hapus.

---

# 11. Migration `create_finance_fee_categories_table`

Buka file migration:

```text
database/migrations/xxxx_xx_xx_xxxxxx_create_finance_fee_categories_table.php
```

Isi lengkap:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('finance_fee_categories', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('school_id')
                ->nullable()
                ->constrained('schools')
                ->nullOnDelete();

            $table->string('name');
            $table->string('slug')->nullable();
            $table->text('description')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();

            $table->index(['school_id', 'is_active']);
            $table->index('sort_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('finance_fee_categories');
    }
};
```

---

# 12. Migration `create_finance_fee_items_table`

Buka file migration:

```text
database/migrations/xxxx_xx_xx_xxxxxx_create_finance_fee_items_table.php
```

Isi lengkap:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('finance_fee_items', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('school_id')
                ->nullable()
                ->constrained('schools')
                ->nullOnDelete();

            $table->foreignId('finance_fee_category_id')
                ->nullable()
                ->constrained('finance_fee_categories')
                ->nullOnDelete();

            $table->string('name');
            $table->string('code')->nullable();
            $table->text('description')->nullable();

            $table->unsignedBigInteger('default_amount')->default(0);

            $table->enum('billing_cycle', [
                'once',
                'daily',
                'weekly',
                'monthly',
                'quarterly',
                'semester',
                'yearly',
            ])->default('once');

            $table->boolean('is_required')->default(false);
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);

            $table->timestamps();
            $table->softDeletes();

            $table->index(['school_id', 'is_active']);
            $table->index('finance_fee_category_id');
            $table->index('billing_cycle');
            $table->index('sort_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('finance_fee_items');
    }
};
```

---

# 13. Migration `create_student_bills_table`

Buka file migration:

```text
database/migrations/xxxx_xx_xx_xxxxxx_create_student_bills_table.php
```

Isi lengkap:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_bills', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('school_id')
                ->nullable()
                ->constrained('schools')
                ->nullOnDelete();

            $table->foreignId('student_id')
                ->constrained('students')
                ->cascadeOnDelete();

            $table->foreignId('class_room_id')
                ->nullable()
                ->constrained('class_rooms')
                ->nullOnDelete();

            $table->string('invoice_number')->unique();
            $table->string('title');
            $table->text('description')->nullable();

            $table->date('issued_date');
            $table->date('due_date')->nullable();

            $table->unsignedBigInteger('total_amount')->default(0);
            $table->unsignedBigInteger('paid_amount')->default(0);
            $table->unsignedBigInteger('outstanding_amount')->default(0);

            $table->enum('status', [
                'draft',
                'posted',
                'partial',
                'paid',
                'overdue',
                'void',
            ])->default('draft');

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('posted_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('posted_at')->nullable();

            $table->foreignId('voided_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('voided_at')->nullable();
            $table->text('void_reason')->nullable();

            $table->timestamps();

            $table->index(['school_id', 'status']);
            $table->index(['student_id', 'status']);
            $table->index('class_room_id');
            $table->index('issued_date');
            $table->index('due_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_bills');
    }
};
```

---

# 14. Migration `create_student_bill_items_table`

Buka file migration:

```text
database/migrations/xxxx_xx_xx_xxxxxx_create_student_bill_items_table.php
```

Isi lengkap:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_bill_items', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('student_bill_id')
                ->constrained('student_bills')
                ->cascadeOnDelete();

            $table->foreignId('finance_fee_item_id')
                ->nullable()
                ->constrained('finance_fee_items')
                ->nullOnDelete();

            $table->string('name');
            $table->text('description')->nullable();

            $table->unsignedInteger('quantity')->default(1);
            $table->unsignedBigInteger('unit_amount')->default(0);
            $table->unsignedBigInteger('total_amount')->default(0);

            $table->timestamps();

            $table->index('student_bill_id');
            $table->index('finance_fee_item_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_bill_items');
    }
};
```

---

# 15. Migration `create_student_payments_table`

Buka file migration:

```text
database/migrations/xxxx_xx_xx_xxxxxx_create_student_payments_table.php
```

Isi lengkap:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_payments', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('school_id')
                ->nullable()
                ->constrained('schools')
                ->nullOnDelete();

            $table->foreignId('student_id')
                ->constrained('students')
                ->cascadeOnDelete();

            $table->string('receipt_number')->unique();

            $table->date('payment_date');

            $table->enum('payment_method', [
                'cash',
                'bank_transfer',
                'qris_external',
                'adjustment',
            ])->default('cash');

            $table->string('reference_number')->nullable();
            $table->unsignedBigInteger('amount')->default(0);
            $table->text('note')->nullable();

            $table->enum('status', [
                'posted',
                'void',
            ])->default('posted');

            $table->foreignId('received_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('voided_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('voided_at')->nullable();
            $table->text('void_reason')->nullable();

            $table->timestamps();

            $table->index(['school_id', 'payment_date']);
            $table->index(['student_id', 'payment_date']);
            $table->index('payment_method');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_payments');
    }
};
```

---

# 16. Migration `create_student_payment_allocations_table`

Buka file migration:

```text
database/migrations/xxxx_xx_xx_xxxxxx_create_student_payment_allocations_table.php
```

Isi lengkap:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_payment_allocations', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('student_payment_id')
                ->constrained('student_payments')
                ->cascadeOnDelete();

            $table->foreignId('student_bill_id')
                ->constrained('student_bills')
                ->cascadeOnDelete();

            $table->unsignedBigInteger('amount')->default(0);

            $table->timestamps();

            $table->unique(
                ['student_payment_id', 'student_bill_id'],
                'student_payment_bill_unique'
            );

            $table->index('student_payment_id');
            $table->index('student_bill_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_payment_allocations');
    }
};
```

---

# 17. Migration `create_finance_ledger_entries_table`

Buka file migration:

```text
database/migrations/xxxx_xx_xx_xxxxxx_create_finance_ledger_entries_table.php
```

Isi lengkap:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('finance_ledger_entries', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('school_id')
                ->nullable()
                ->constrained('schools')
                ->nullOnDelete();

            $table->foreignId('student_id')
                ->constrained('students')
                ->cascadeOnDelete();

            $table->date('entry_date');

            $table->enum('direction', [
                'debit',
                'credit',
            ]);

            $table->unsignedBigInteger('amount')->default(0);

            $table->string('source_type');
            $table->unsignedBigInteger('source_id');

            $table->string('description')->nullable();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->index(['school_id', 'entry_date']);
            $table->index(['student_id', 'entry_date']);
            $table->index(['source_type', 'source_id']);
            $table->index('direction');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('finance_ledger_entries');
    }
};
```

---

# 18. Model `FinanceFeeCategory`

Buka:

```text
app/Models/FinanceFeeCategory.php
```

Isi lengkap:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class FinanceFeeCategory extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'school_id',
        'name',
        'slug',
        'description',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function feeItems(): HasMany
    {
        return $this->hasMany(FinanceFeeItem::class);
    }
}
```

---

# 19. Model `FinanceFeeItem`

Buka:

```text
app/Models/FinanceFeeItem.php
```

Isi lengkap:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class FinanceFeeItem extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'school_id',
        'finance_fee_category_id',
        'name',
        'code',
        'description',
        'default_amount',
        'billing_cycle',
        'is_required',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'default_amount' => 'integer',
        'is_required' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(FinanceFeeCategory::class, 'finance_fee_category_id');
    }
}
```

---

# 20. Model `StudentBill`

Buka:

```text
app/Models/StudentBill.php
```

Isi lengkap:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StudentBill extends Model
{
    public const STATUS_DRAFT = 'draft';
    public const STATUS_POSTED = 'posted';
    public const STATUS_PARTIAL = 'partial';
    public const STATUS_PAID = 'paid';
    public const STATUS_OVERDUE = 'overdue';
    public const STATUS_VOID = 'void';

    protected $fillable = [
        'school_id',
        'student_id',
        'class_room_id',
        'invoice_number',
        'title',
        'description',
        'issued_date',
        'due_date',
        'total_amount',
        'paid_amount',
        'outstanding_amount',
        'status',
        'created_by',
        'posted_by',
        'posted_at',
        'voided_by',
        'voided_at',
        'void_reason',
    ];

    protected $casts = [
        'issued_date' => 'date',
        'due_date' => 'date',
        'total_amount' => 'integer',
        'paid_amount' => 'integer',
        'outstanding_amount' => 'integer',
        'posted_at' => 'datetime',
        'voided_at' => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function classRoom(): BelongsTo
    {
        return $this->belongsTo(ClassRoom::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(StudentBillItem::class);
    }

    public function allocations(): HasMany
    {
        return $this->hasMany(StudentPaymentAllocation::class);
    }
}
```

---

# 21. Model `StudentBillItem`

Buka:

```text
app/Models/StudentBillItem.php
```

Isi lengkap:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentBillItem extends Model
{
    protected $fillable = [
        'student_bill_id',
        'finance_fee_item_id',
        'name',
        'description',
        'quantity',
        'unit_amount',
        'total_amount',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_amount' => 'integer',
        'total_amount' => 'integer',
    ];

    public function bill(): BelongsTo
    {
        return $this->belongsTo(StudentBill::class, 'student_bill_id');
    }

    public function feeItem(): BelongsTo
    {
        return $this->belongsTo(FinanceFeeItem::class, 'finance_fee_item_id');
    }
}
```

---

# 22. Model `StudentPayment`

Buka:

```text
app/Models/StudentPayment.php
```

Isi lengkap:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StudentPayment extends Model
{
    public const METHOD_CASH = 'cash';
    public const METHOD_BANK_TRANSFER = 'bank_transfer';
    public const METHOD_QRIS_EXTERNAL = 'qris_external';
    public const METHOD_ADJUSTMENT = 'adjustment';

    public const STATUS_POSTED = 'posted';
    public const STATUS_VOID = 'void';

    protected $fillable = [
        'school_id',
        'student_id',
        'receipt_number',
        'payment_date',
        'payment_method',
        'reference_number',
        'amount',
        'note',
        'status',
        'received_by',
        'voided_by',
        'voided_at',
        'void_reason',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'integer',
        'voided_at' => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function allocations(): HasMany
    {
        return $this->hasMany(StudentPaymentAllocation::class);
    }
}
```

---

# 23. Model `StudentPaymentAllocation`

Buka:

```text
app/Models/StudentPaymentAllocation.php
```

Isi lengkap:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentPaymentAllocation extends Model
{
    protected $fillable = [
        'student_payment_id',
        'student_bill_id',
        'amount',
    ];

    protected $casts = [
        'amount' => 'integer',
    ];

    public function payment(): BelongsTo
    {
        return $this->belongsTo(StudentPayment::class, 'student_payment_id');
    }

    public function bill(): BelongsTo
    {
        return $this->belongsTo(StudentBill::class, 'student_bill_id');
    }
}
```

---

# 24. Model `FinanceLedgerEntry`

Buka:

```text
app/Models/FinanceLedgerEntry.php
```

Isi lengkap:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FinanceLedgerEntry extends Model
{
    public const DIRECTION_DEBIT = 'debit';
    public const DIRECTION_CREDIT = 'credit';

    protected $fillable = [
        'school_id',
        'student_id',
        'entry_date',
        'direction',
        'amount',
        'source_type',
        'source_id',
        'description',
        'created_by',
    ];

    protected $casts = [
        'entry_date' => 'date',
        'amount' => 'integer',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
```

---

# 25. Update Model `Student`

Buka:

```text
app/Models/Student.php
```

Tambahkan relasi tanpa menghapus relasi lama:

```php
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

public function financeBills(): HasMany
{
    return $this->hasMany(StudentBill::class);
}

public function financePayments(): HasMany
{
    return $this->hasMany(StudentPayment::class);
}

public function financeLedgerEntries(): HasMany
{
    return $this->hasMany(FinanceLedgerEntry::class);
}
```

Jika sudah ada, jangan duplikasi.

---

# 26. Seeder `FinanceFeeCategorySeeder`

Buka:

```text
database/seeders/FinanceFeeCategorySeeder.php
```

Isi lengkap:

```php
<?php

namespace Database\Seeders;

use App\Models\FinanceFeeCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class FinanceFeeCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'SPP', 'description' => 'Biaya SPP bulanan.', 'sort_order' => 10],
            ['name' => 'Tahfizh', 'description' => 'Biaya program tahfizh.', 'sort_order' => 20],
            ['name' => 'Kegiatan', 'description' => 'Biaya kegiatan sekolah.', 'sort_order' => 30],
            ['name' => 'Daftar Ulang', 'description' => 'Biaya daftar ulang.', 'sort_order' => 40],
            ['name' => 'Seragam', 'description' => 'Biaya seragam.', 'sort_order' => 50],
            ['name' => 'Buku', 'description' => 'Biaya buku.', 'sort_order' => 60],
            ['name' => 'Ujian', 'description' => 'Biaya ujian.', 'sort_order' => 70],
            ['name' => 'Lain-lain', 'description' => 'Biaya lain-lain.', 'sort_order' => 999],
        ];

        foreach ($categories as $category) {
            FinanceFeeCategory::query()->updateOrCreate(
                [
                    'school_id' => null,
                    'slug' => Str::slug($category['name']),
                ],
                [
                    'name' => $category['name'],
                    'description' => $category['description'],
                    'sort_order' => $category['sort_order'],
                    'is_active' => true,
                ]
            );
        }
    }
}
```

---

# 27. Update `DatabaseSeeder`

Buka:

```text
database/seeders/DatabaseSeeder.php
```

Tambahkan tanpa menghapus seeder lama:

```php
$this->call([
    FinanceFeeCategorySeeder::class,
]);
```

Jika `DatabaseSeeder` sudah punya banyak seeder, tambahkan di bagian bawah.

---

# 28. Service `FinanceAccessService`

Buka:

```text
app/Services/Finance/FinanceAccessService.php
```

Isi lengkap:

```php
<?php

namespace App\Services\Finance;

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class FinanceAccessService
{
    public function roleName(?User $user): ?string
    {
        if (! $user) {
            return null;
        }

        $roleValue = $user->role ?? null;

        if (is_object($roleValue)) {
            return $roleValue->name ?? $roleValue->slug ?? null;
        }

        return is_string($roleValue) ? $roleValue : null;
    }

    public function isAdmin(?User $user): bool
    {
        return in_array($this->roleName($user), [
            'super_admin',
            'admin',
            'admin_sekolah',
        ], true);
    }

    public function isPrincipal(?User $user): bool
    {
        return in_array($this->roleName($user), [
            'kepala_sekolah',
            'principal',
        ], true);
    }

    public function isParent(?User $user): bool
    {
        return $this->roleName($user) === 'parent';
    }

    public function isStudent(?User $user): bool
    {
        return $this->roleName($user) === 'student';
    }

    public function canManageFinance(?User $user): bool
    {
        return $this->isAdmin($user);
    }

    public function canViewFinanceReport(?User $user): bool
    {
        return $this->isAdmin($user) || $this->isPrincipal($user);
    }

    public function applyStudentScope(Builder $query, User $user): Builder
    {
        if ($this->isAdmin($user) || $this->isPrincipal($user)) {
            return $query;
        }

        if ($this->isParent($user)) {
            $parentProfile = $user->parentProfile ?? null;

            if (! $parentProfile) {
                return $query->whereRaw('1 = 0');
            }

            return $query->whereHas('parents', function (Builder $parentQuery) use ($parentProfile): void {
                $parentQuery->where('parent_profiles.id', $parentProfile->id);
            });
        }

        if ($this->isStudent($user)) {
            return $query->where('user_id', $user->id);
        }

        return $query->whereRaw('1 = 0');
    }

    public function canViewStudent(User $user, Student $student): bool
    {
        if ($this->isAdmin($user) || $this->isPrincipal($user)) {
            return true;
        }

        $query = Student::query()->whereKey($student->id);

        return $this->applyStudentScope($query, $user)->exists();
    }
}
```

---

# 29. Service `InvoiceNumberGenerator`

Buka:

```text
app/Services/Finance/InvoiceNumberGenerator.php
```

Isi lengkap:

```php
<?php

namespace App\Services\Finance;

use App\Models\StudentBill;

class InvoiceNumberGenerator
{
    public function generate(): string
    {
        $prefix = 'INV-' . now()->format('Ymd');

        $count = StudentBill::query()
            ->where('invoice_number', 'like', $prefix . '%')
            ->count() + 1;

        return $prefix . '-' . str_pad((string) $count, 5, '0', STR_PAD_LEFT);
    }
}
```

---

# 30. Service `ReceiptNumberGenerator`

Buka:

```text
app/Services/Finance/ReceiptNumberGenerator.php
```

Isi lengkap:

```php
<?php

namespace App\Services\Finance;

use App\Models\StudentPayment;

class ReceiptNumberGenerator
{
    public function generate(): string
    {
        $prefix = 'RCPT-' . now()->format('Ymd');

        $count = StudentPayment::query()
            ->where('receipt_number', 'like', $prefix . '%')
            ->count() + 1;

        return $prefix . '-' . str_pad((string) $count, 5, '0', STR_PAD_LEFT);
    }
}
```

---

# 31. Service `StudentBillService`

Buka:

```text
app/Services/Finance/StudentBillService.php
```

Isi lengkap:

```php
<?php

namespace App\Services\Finance;

use App\Models\FinanceLedgerEntry;
use App\Models\Student;
use App\Models\StudentBill;
use App\Models\StudentBillItem;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StudentBillService
{
    public function __construct(
        private readonly InvoiceNumberGenerator $invoiceNumberGenerator,
    ) {
        //
    }

    public function createPostedBill(array $data, User $user): StudentBill
    {
        return DB::transaction(function () use ($data, $user): StudentBill {
            $student = Student::query()->findOrFail($data['student_id']);
            $items = collect($data['items'] ?? []);

            if ($items->isEmpty()) {
                throw ValidationException::withMessages([
                    'items' => 'Minimal satu item tagihan harus diisi.',
                ]);
            }

            $totalAmount = 0;

            $bill = StudentBill::query()->create([
                'school_id' => $student->school_id ?? null,
                'student_id' => $student->id,
                'class_room_id' => $student->class_room_id ?? null,
                'invoice_number' => $this->invoiceNumberGenerator->generate(),
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'issued_date' => $data['issued_date'],
                'due_date' => $data['due_date'] ?? null,
                'total_amount' => 0,
                'paid_amount' => 0,
                'outstanding_amount' => 0,
                'status' => StudentBill::STATUS_POSTED,
                'created_by' => $user->id,
                'posted_by' => $user->id,
                'posted_at' => now(),
            ]);

            foreach ($items as $item) {
                $quantity = (int) ($item['quantity'] ?? 1);
                $unitAmount = (int) ($item['unit_amount'] ?? 0);
                $lineTotal = $quantity * $unitAmount;

                if ($lineTotal <= 0) {
                    throw ValidationException::withMessages([
                        'items' => 'Nominal item tagihan harus lebih dari 0.',
                    ]);
                }

                StudentBillItem::query()->create([
                    'student_bill_id' => $bill->id,
                    'finance_fee_item_id' => $item['finance_fee_item_id'] ?? null,
                    'name' => $item['name'],
                    'description' => $item['description'] ?? null,
                    'quantity' => $quantity,
                    'unit_amount' => $unitAmount,
                    'total_amount' => $lineTotal,
                ]);

                $totalAmount += $lineTotal;
            }

            $bill->update([
                'total_amount' => $totalAmount,
                'outstanding_amount' => $totalAmount,
            ]);

            FinanceLedgerEntry::query()->create([
                'school_id' => $student->school_id ?? null,
                'student_id' => $student->id,
                'entry_date' => $data['issued_date'],
                'direction' => FinanceLedgerEntry::DIRECTION_DEBIT,
                'amount' => $totalAmount,
                'source_type' => StudentBill::class,
                'source_id' => $bill->id,
                'description' => 'Tagihan ' . $bill->invoice_number . ' - ' . $bill->title,
                'created_by' => $user->id,
            ]);

            return $bill->fresh(['student', 'items']);
        });
    }

    public function voidBill(StudentBill $bill, User $user, string $reason): StudentBill
    {
        return DB::transaction(function () use ($bill, $user, $reason): StudentBill {
            $bill = StudentBill::query()
                ->whereKey($bill->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($bill->status === StudentBill::STATUS_VOID) {
                throw ValidationException::withMessages([
                    'bill' => 'Tagihan sudah dibatalkan.',
                ]);
            }

            if ($bill->paid_amount > 0) {
                throw ValidationException::withMessages([
                    'bill' => 'Tagihan yang sudah memiliki pembayaran tidak boleh dibatalkan langsung.',
                ]);
            }

            $bill->update([
                'status' => StudentBill::STATUS_VOID,
                'voided_by' => $user->id,
                'voided_at' => now(),
                'void_reason' => $reason,
            ]);

            FinanceLedgerEntry::query()->create([
                'school_id' => $bill->school_id,
                'student_id' => $bill->student_id,
                'entry_date' => now()->toDateString(),
                'direction' => FinanceLedgerEntry::DIRECTION_CREDIT,
                'amount' => $bill->total_amount,
                'source_type' => StudentBill::class,
                'source_id' => $bill->id,
                'description' => 'Void tagihan ' . $bill->invoice_number,
                'created_by' => $user->id,
            ]);

            return $bill->fresh();
        });
    }
}
```

---

# 32. Service `StudentPaymentService`

Buka:

```text
app/Services/Finance/StudentPaymentService.php
```

Isi lengkap:

```php
<?php

namespace App\Services\Finance;

use App\Models\FinanceLedgerEntry;
use App\Models\Student;
use App\Models\StudentBill;
use App\Models\StudentPayment;
use App\Models\StudentPaymentAllocation;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StudentPaymentService
{
    public function __construct(
        private readonly ReceiptNumberGenerator $receiptNumberGenerator,
    ) {
        //
    }

    public function createPayment(array $data, User $user): StudentPayment
    {
        return DB::transaction(function () use ($data, $user): StudentPayment {
            $student = Student::query()->findOrFail($data['student_id']);

            $amount = (int) $data['amount'];

            if ($amount <= 0) {
                throw ValidationException::withMessages([
                    'amount' => 'Nominal pembayaran harus lebih dari 0.',
                ]);
            }

            $payment = StudentPayment::query()->create([
                'school_id' => $student->school_id ?? null,
                'student_id' => $student->id,
                'receipt_number' => $this->receiptNumberGenerator->generate(),
                'payment_date' => $data['payment_date'],
                'payment_method' => $data['payment_method'],
                'reference_number' => $data['reference_number'] ?? null,
                'amount' => $amount,
                'note' => $data['note'] ?? null,
                'status' => StudentPayment::STATUS_POSTED,
                'received_by' => $user->id,
            ]);

            $remaining = $amount;
            $billIds = collect($data['bill_ids'] ?? [])->filter()->values();

            if ($billIds->isNotEmpty()) {
                $bills = StudentBill::query()
                    ->whereIn('id', $billIds)
                    ->where('student_id', $student->id)
                    ->whereIn('status', [
                        StudentBill::STATUS_POSTED,
                        StudentBill::STATUS_PARTIAL,
                        StudentBill::STATUS_OVERDUE,
                    ])
                    ->orderBy('due_date')
                    ->lockForUpdate()
                    ->get();

                foreach ($bills as $bill) {
                    if ($remaining <= 0) {
                        break;
                    }

                    $allocate = min($remaining, (int) $bill->outstanding_amount);

                    if ($allocate <= 0) {
                        continue;
                    }

                    StudentPaymentAllocation::query()->create([
                        'student_payment_id' => $payment->id,
                        'student_bill_id' => $bill->id,
                        'amount' => $allocate,
                    ]);

                    $newPaid = $bill->paid_amount + $allocate;
                    $newOutstanding = max(0, $bill->total_amount - $newPaid);

                    $bill->update([
                        'paid_amount' => $newPaid,
                        'outstanding_amount' => $newOutstanding,
                        'status' => $newOutstanding === 0
                            ? StudentBill::STATUS_PAID
                            : StudentBill::STATUS_PARTIAL,
                    ]);

                    $remaining -= $allocate;
                }
            }

            FinanceLedgerEntry::query()->create([
                'school_id' => $student->school_id ?? null,
                'student_id' => $student->id,
                'entry_date' => $data['payment_date'],
                'direction' => FinanceLedgerEntry::DIRECTION_CREDIT,
                'amount' => $amount,
                'source_type' => StudentPayment::class,
                'source_id' => $payment->id,
                'description' => 'Pembayaran ' . $payment->receipt_number,
                'created_by' => $user->id,
            ]);

            return $payment->fresh(['student', 'allocations.bill']);
        });
    }

    public function voidPayment(StudentPayment $payment, User $user, string $reason): StudentPayment
    {
        return DB::transaction(function () use ($payment, $user, $reason): StudentPayment {
            $payment = StudentPayment::query()
                ->whereKey($payment->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($payment->status === StudentPayment::STATUS_VOID) {
                throw ValidationException::withMessages([
                    'payment' => 'Pembayaran sudah dibatalkan.',
                ]);
            }

            foreach ($payment->allocations()->with('bill')->get() as $allocation) {
                $bill = StudentBill::query()
                    ->whereKey($allocation->student_bill_id)
                    ->lockForUpdate()
                    ->first();

                if (! $bill) {
                    continue;
                }

                $newPaid = max(0, $bill->paid_amount - $allocation->amount);
                $newOutstanding = max(0, $bill->total_amount - $newPaid);

                $bill->update([
                    'paid_amount' => $newPaid,
                    'outstanding_amount' => $newOutstanding,
                    'status' => $newPaid === 0
                        ? StudentBill::STATUS_POSTED
                        : StudentBill::STATUS_PARTIAL,
                ]);
            }

            $payment->update([
                'status' => StudentPayment::STATUS_VOID,
                'voided_by' => $user->id,
                'voided_at' => now(),
                'void_reason' => $reason,
            ]);

            FinanceLedgerEntry::query()->create([
                'school_id' => $payment->school_id,
                'student_id' => $payment->student_id,
                'entry_date' => now()->toDateString(),
                'direction' => FinanceLedgerEntry::DIRECTION_DEBIT,
                'amount' => $payment->amount,
                'source_type' => StudentPayment::class,
                'source_id' => $payment->id,
                'description' => 'Void pembayaran ' . $payment->receipt_number,
                'created_by' => $user->id,
            ]);

            return $payment->fresh(['student', 'allocations.bill']);
        });
    }
}
```

---

# 33. Service `StudentBalanceService`

Buka:

```text
app/Services/Finance/StudentBalanceService.php
```

Isi lengkap:

```php
<?php

namespace App\Services\Finance;

use App\Models\FinanceLedgerEntry;
use App\Models\Student;

class StudentBalanceService
{
    public function balance(Student $student): array
    {
        $debit = (int) FinanceLedgerEntry::query()
            ->where('student_id', $student->id)
            ->where('direction', FinanceLedgerEntry::DIRECTION_DEBIT)
            ->sum('amount');

        $credit = (int) FinanceLedgerEntry::query()
            ->where('student_id', $student->id)
            ->where('direction', FinanceLedgerEntry::DIRECTION_CREDIT)
            ->sum('amount');

        return [
            'debit' => $debit,
            'credit' => $credit,
            'balance' => $debit - $credit,
        ];
    }
}
```

---

# 34. Service `FinanceReportService`

Buka:

```text
app/Services/Finance/FinanceReportService.php
```

Isi lengkap:

```php
<?php

namespace App\Services\Finance;

use App\Models\StudentBill;
use App\Models\StudentPayment;
use Carbon\Carbon;

class FinanceReportService
{
    public function dashboard(array $filters = []): array
    {
        $startDate = Carbon::parse($filters['start_date'] ?? now()->startOfMonth()->toDateString())->toDateString();
        $endDate = Carbon::parse($filters['end_date'] ?? now()->toDateString())->toDateString();

        $billQuery = StudentBill::query()
            ->with(['student.classRoom'])
            ->whereBetween('issued_date', [$startDate, $endDate]);

        $paymentQuery = StudentPayment::query()
            ->with(['student.classRoom'])
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->where('status', StudentPayment::STATUS_POSTED);

        if (! empty($filters['class_room_id'])) {
            $billQuery->where('class_room_id', $filters['class_room_id']);

            $paymentQuery->whereHas('student', function ($query) use ($filters): void {
                $query->where('class_room_id', $filters['class_room_id']);
            });
        }

        if (! empty($filters['student_id'])) {
            $billQuery->where('student_id', $filters['student_id']);
            $paymentQuery->where('student_id', $filters['student_id']);
        }

        $bills = $billQuery->get();
        $payments = $paymentQuery->get();

        return [
            'period' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
            ],
            'summary' => [
                'total_billed' => (int) $bills->where('status', '!=', StudentBill::STATUS_VOID)->sum('total_amount'),
                'total_paid' => (int) $payments->sum('amount'),
                'total_outstanding' => (int) $bills->where('status', '!=', StudentBill::STATUS_VOID)->sum('outstanding_amount'),
                'bill_count' => $bills->count(),
                'payment_count' => $payments->count(),
                'unpaid_count' => $bills->whereIn('status', [
                    StudentBill::STATUS_POSTED,
                    StudentBill::STATUS_PARTIAL,
                    StudentBill::STATUS_OVERDUE,
                ])->count(),
            ],
            'bills' => $bills->sortByDesc('issued_date')->values(),
            'payments' => $payments->sortByDesc('payment_date')->values(),
        ];
    }
}
```

---

# 35. Request `StoreFinanceFeeCategoryRequest`

Buka:

```text
app/Http/Requests/Finance/StoreFinanceFeeCategoryRequest.php
```

Isi lengkap:

```php
<?php

namespace App\Http\Requests\Finance;

use Illuminate\Foundation\Http\FormRequest;

class StoreFinanceFeeCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        $roleValue = $this->user()?->role ?? null;
        $role = is_object($roleValue) ? ($roleValue->name ?? $roleValue->slug ?? null) : $roleValue;

        return in_array($role, ['super_admin', 'admin', 'admin_sekolah'], true);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:65535'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
```

---

# 36. Request `UpdateFinanceFeeCategoryRequest`

Buka:

```text
app/Http/Requests/Finance/UpdateFinanceFeeCategoryRequest.php
```

Isi lengkap:

```php
<?php

namespace App\Http\Requests\Finance;

class UpdateFinanceFeeCategoryRequest extends StoreFinanceFeeCategoryRequest
{
    //
}
```

---

# 37. Request `StoreFinanceFeeItemRequest`

Buka:

```text
app/Http/Requests/Finance/StoreFinanceFeeItemRequest.php
```

Isi lengkap:

```php
<?php

namespace App\Http\Requests\Finance;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreFinanceFeeItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        $roleValue = $this->user()?->role ?? null;
        $role = is_object($roleValue) ? ($roleValue->name ?? $roleValue->slug ?? null) : $roleValue;

        return in_array($role, ['super_admin', 'admin', 'admin_sekolah'], true);
    }

    public function rules(): array
    {
        return [
            'finance_fee_category_id' => ['nullable', 'exists:finance_fee_categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'default_amount' => ['required', 'integer', 'min:0'],
            'billing_cycle' => [
                'required',
                Rule::in(['once', 'daily', 'weekly', 'monthly', 'quarterly', 'semester', 'yearly']),
            ],
            'is_required' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:65535'],
        ];
    }
}
```

---

# 38. Request `UpdateFinanceFeeItemRequest`

Buka:

```text
app/Http/Requests/Finance/UpdateFinanceFeeItemRequest.php
```

Isi lengkap:

```php
<?php

namespace App\Http\Requests\Finance;

class UpdateFinanceFeeItemRequest extends StoreFinanceFeeItemRequest
{
    //
}
```

---

# 39. Request `StoreStudentBillRequest`

Buka:

```text
app/Http/Requests/Finance/StoreStudentBillRequest.php
```

Isi lengkap:

```php
<?php

namespace App\Http\Requests\Finance;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentBillRequest extends FormRequest
{
    public function authorize(): bool
    {
        $roleValue = $this->user()?->role ?? null;
        $role = is_object($roleValue) ? ($roleValue->name ?? $roleValue->slug ?? null) : $roleValue;

        return in_array($role, ['super_admin', 'admin', 'admin_sekolah'], true);
    }

    public function rules(): array
    {
        return [
            'student_id' => ['required', 'exists:students,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'issued_date' => ['required', 'date'],
            'due_date' => ['nullable', 'date', 'after_or_equal:issued_date'],

            'items' => ['required', 'array', 'min:1'],
            'items.*.finance_fee_item_id' => ['nullable', 'exists:finance_fee_items,id'],
            'items.*.name' => ['required', 'string', 'max:255'],
            'items.*.description' => ['nullable', 'string'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.unit_amount' => ['required', 'integer', 'min:1'],
        ];
    }
}
```

---

# 40. Request `StoreStudentPaymentRequest`

Buka:

```text
app/Http/Requests/Finance/StoreStudentPaymentRequest.php
```

Isi lengkap:

```php
<?php

namespace App\Http\Requests\Finance;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreStudentPaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        $roleValue = $this->user()?->role ?? null;
        $role = is_object($roleValue) ? ($roleValue->name ?? $roleValue->slug ?? null) : $roleValue;

        return in_array($role, ['super_admin', 'admin', 'admin_sekolah'], true);
    }

    public function rules(): array
    {
        return [
            'student_id' => ['required', 'exists:students,id'],
            'payment_date' => ['required', 'date'],
            'payment_method' => [
                'required',
                Rule::in(['cash', 'bank_transfer', 'qris_external', 'adjustment']),
            ],
            'reference_number' => ['nullable', 'string', 'max:255'],
            'amount' => ['required', 'integer', 'min:1'],
            'note' => ['nullable', 'string', 'max:3000'],

            'bill_ids' => ['nullable', 'array'],
            'bill_ids.*' => ['integer', 'exists:student_bills,id'],
        ];
    }
}
```

---

# 41. Request `FinanceReportFilterRequest`

Buka:

```text
app/Http/Requests/Finance/FinanceReportFilterRequest.php
```

Isi lengkap:

```php
<?php

namespace App\Http\Requests\Finance;

use Illuminate\Foundation\Http\FormRequest;

class FinanceReportFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        $roleValue = $this->user()?->role ?? null;
        $role = is_object($roleValue) ? ($roleValue->name ?? $roleValue->slug ?? null) : $roleValue;

        return in_array($role, [
            'super_admin',
            'admin',
            'admin_sekolah',
            'kepala_sekolah',
            'principal',
            'parent',
            'student',
        ], true);
    }

    public function rules(): array
    {
        return [
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'class_room_id' => ['nullable', 'exists:class_rooms,id'],
            'student_id' => ['nullable', 'exists:students,id'],
        ];
    }
}
```

---

# 42. Controller `FinanceFeeCategoryController`

Buka:

```text
app/Http/Controllers/Finance/FinanceFeeCategoryController.php
```

Isi lengkap:

```php
<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Http\Requests\Finance\StoreFinanceFeeCategoryRequest;
use App\Http\Requests\Finance\UpdateFinanceFeeCategoryRequest;
use App\Models\FinanceFeeCategory;
use App\Services\Finance\FinanceAccessService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class FinanceFeeCategoryController extends Controller
{
    public function index(Request $request, FinanceAccessService $accessService): View
    {
        abort_unless($accessService->canViewFinanceReport($request->user()), 403);

        $categories = FinanceFeeCategory::query()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(20);

        return view('finance.fee-categories.index', compact('categories'));
    }

    public function create(Request $request, FinanceAccessService $accessService): View
    {
        abort_unless($accessService->canManageFinance($request->user()), 403);

        return view('finance.fee-categories.create');
    }

    public function store(StoreFinanceFeeCategoryRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['name']);
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_active'] = $request->boolean('is_active', true);

        FinanceFeeCategory::query()->create($data);

        return redirect()
            ->route('finance.fee-categories.index')
            ->with('success', 'Kategori biaya berhasil dibuat.');
    }

    public function show(Request $request, FinanceFeeCategory $feeCategory, FinanceAccessService $accessService): View
    {
        abort_unless($accessService->canViewFinanceReport($request->user()), 403);

        $feeCategory->load('feeItems');

        return view('finance.fee-categories.show', compact('feeCategory'));
    }

    public function edit(Request $request, FinanceFeeCategory $feeCategory, FinanceAccessService $accessService): View
    {
        abort_unless($accessService->canManageFinance($request->user()), 403);

        return view('finance.fee-categories.edit', compact('feeCategory'));
    }

    public function update(UpdateFinanceFeeCategoryRequest $request, FinanceFeeCategory $feeCategory): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['name']);
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_active'] = $request->boolean('is_active', true);

        $feeCategory->update($data);

        return redirect()
            ->route('finance.fee-categories.index')
            ->with('success', 'Kategori biaya berhasil diperbarui.');
    }

    public function destroy(Request $request, FinanceFeeCategory $feeCategory, FinanceAccessService $accessService): RedirectResponse
    {
        abort_unless($accessService->canManageFinance($request->user()), 403);

        $feeCategory->delete();

        return redirect()
            ->route('finance.fee-categories.index')
            ->with('success', 'Kategori biaya berhasil dihapus.');
    }
}
```

---

# 43. Controller `FinanceFeeItemController`

Buka:

```text
app/Http/Controllers/Finance/FinanceFeeItemController.php
```

Isi lengkap:

```php
<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Http\Requests\Finance\StoreFinanceFeeItemRequest;
use App\Http\Requests\Finance\UpdateFinanceFeeItemRequest;
use App\Models\FinanceFeeCategory;
use App\Models\FinanceFeeItem;
use App\Services\Finance\FinanceAccessService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FinanceFeeItemController extends Controller
{
    public function index(Request $request, FinanceAccessService $accessService): View
    {
        abort_unless($accessService->canViewFinanceReport($request->user()), 403);

        $items = FinanceFeeItem::query()
            ->with('category')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(30);

        return view('finance.fee-items.index', compact('items'));
    }

    public function create(Request $request, FinanceAccessService $accessService): View
    {
        abort_unless($accessService->canManageFinance($request->user()), 403);

        $categories = FinanceFeeCategory::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('finance.fee-items.create', compact('categories'));
    }

    public function store(StoreFinanceFeeItemRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_required'] = $request->boolean('is_required', false);
        $data['is_active'] = $request->boolean('is_active', true);

        FinanceFeeItem::query()->create($data);

        return redirect()
            ->route('finance.fee-items.index')
            ->with('success', 'Item biaya berhasil dibuat.');
    }

    public function show(Request $request, FinanceFeeItem $feeItem, FinanceAccessService $accessService): View
    {
        abort_unless($accessService->canViewFinanceReport($request->user()), 403);

        $feeItem->load('category');

        return view('finance.fee-items.show', compact('feeItem'));
    }

    public function edit(Request $request, FinanceFeeItem $feeItem, FinanceAccessService $accessService): View
    {
        abort_unless($accessService->canManageFinance($request->user()), 403);

        $categories = FinanceFeeCategory::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('finance.fee-items.edit', compact('feeItem', 'categories'));
    }

    public function update(UpdateFinanceFeeItemRequest $request, FinanceFeeItem $feeItem): RedirectResponse
    {
        $data = $request->validated();
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_required'] = $request->boolean('is_required', false);
        $data['is_active'] = $request->boolean('is_active', true);

        $feeItem->update($data);

        return redirect()
            ->route('finance.fee-items.index')
            ->with('success', 'Item biaya berhasil diperbarui.');
    }

    public function destroy(Request $request, FinanceFeeItem $feeItem, FinanceAccessService $accessService): RedirectResponse
    {
        abort_unless($accessService->canManageFinance($request->user()), 403);

        $feeItem->delete();

        return redirect()
            ->route('finance.fee-items.index')
            ->with('success', 'Item biaya berhasil dihapus.');
    }
}
```

---

# 44. Controller `StudentBillController`

Buka:

```text
app/Http/Controllers/Finance/StudentBillController.php
```

Isi lengkap:

```php
<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Http\Requests\Finance\StoreStudentBillRequest;
use App\Models\FinanceFeeItem;
use App\Models\Student;
use App\Models\StudentBill;
use App\Services\Finance\FinanceAccessService;
use App\Services\Finance\StudentBillService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentBillController extends Controller
{
    public function index(Request $request, FinanceAccessService $accessService): View
    {
        abort_unless($accessService->canViewFinanceReport($request->user()), 403);

        $bills = StudentBill::query()
            ->with(['student.classRoom'])
            ->orderByDesc('issued_date')
            ->orderByDesc('id')
            ->paginate(30);

        return view('finance.bills.index', compact('bills'));
    }

    public function create(Request $request, FinanceAccessService $accessService): View
    {
        abort_unless($accessService->canManageFinance($request->user()), 403);

        $students = Student::query()
            ->with('classRoom')
            ->orderBy('nama_lengkap')
            ->limit(500)
            ->get();

        $feeItems = FinanceFeeItem::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('finance.bills.create', compact('students', 'feeItems'));
    }

    public function store(
        StoreStudentBillRequest $request,
        StudentBillService $billService
    ): RedirectResponse {
        $bill = $billService->createPostedBill(
            $request->validated(),
            $request->user()
        );

        return redirect()
            ->route('finance.bills.show', $bill)
            ->with('success', 'Tagihan santri berhasil dibuat.');
    }

    public function show(Request $request, StudentBill $bill, FinanceAccessService $accessService): View
    {
        abort_unless($accessService->canViewStudent($request->user(), $bill->student), 403);

        $bill->load(['student.classRoom', 'items.feeItem', 'allocations.payment']);

        return view('finance.bills.show', compact('bill'));
    }

    public function void(
        Request $request,
        StudentBill $bill,
        FinanceAccessService $accessService,
        StudentBillService $billService
    ): RedirectResponse {
        abort_unless($accessService->canManageFinance($request->user()), 403);

        $validated = $request->validate([
            'void_reason' => ['required', 'string', 'max:2000'],
        ]);

        $billService->voidBill($bill, $request->user(), $validated['void_reason']);

        return redirect()
            ->route('finance.bills.show', $bill)
            ->with('success', 'Tagihan berhasil dibatalkan.');
    }
}
```

Catatan:

Jika `students` tidak punya kolom `nama_lengkap`, ubah ke kolom asli.

---

# 45. Controller `StudentPaymentController`

Buka:

```text
app/Http/Controllers/Finance/StudentPaymentController.php
```

Isi lengkap:

```php
<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Http\Requests\Finance\StoreStudentPaymentRequest;
use App\Models\Student;
use App\Models\StudentBill;
use App\Models\StudentPayment;
use App\Services\Finance\FinanceAccessService;
use App\Services\Finance\StudentPaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentPaymentController extends Controller
{
    public function index(Request $request, FinanceAccessService $accessService): View
    {
        abort_unless($accessService->canViewFinanceReport($request->user()), 403);

        $payments = StudentPayment::query()
            ->with(['student.classRoom'])
            ->orderByDesc('payment_date')
            ->orderByDesc('id')
            ->paginate(30);

        return view('finance.payments.index', compact('payments'));
    }

    public function create(Request $request, FinanceAccessService $accessService): View
    {
        abort_unless($accessService->canManageFinance($request->user()), 403);

        $students = Student::query()
            ->with('classRoom')
            ->orderBy('nama_lengkap')
            ->limit(500)
            ->get();

        $openBills = StudentBill::query()
            ->with('student')
            ->whereIn('status', [
                StudentBill::STATUS_POSTED,
                StudentBill::STATUS_PARTIAL,
                StudentBill::STATUS_OVERDUE,
            ])
            ->orderBy('due_date')
            ->get();

        return view('finance.payments.create', compact('students', 'openBills'));
    }

    public function store(
        StoreStudentPaymentRequest $request,
        StudentPaymentService $paymentService
    ): RedirectResponse {
        $payment = $paymentService->createPayment(
            $request->validated(),
            $request->user()
        );

        return redirect()
            ->route('finance.payments.show', $payment)
            ->with('success', 'Pembayaran berhasil dicatat.');
    }

    public function show(Request $request, StudentPayment $payment, FinanceAccessService $accessService): View
    {
        abort_unless($accessService->canViewStudent($request->user(), $payment->student), 403);

        $payment->load(['student.classRoom', 'allocations.bill']);

        return view('finance.payments.show', compact('payment'));
    }

    public function void(
        Request $request,
        StudentPayment $payment,
        FinanceAccessService $accessService,
        StudentPaymentService $paymentService
    ): RedirectResponse {
        abort_unless($accessService->canManageFinance($request->user()), 403);

        $validated = $request->validate([
            'void_reason' => ['required', 'string', 'max:2000'],
        ]);

        $paymentService->voidPayment($payment, $request->user(), $validated['void_reason']);

        return redirect()
            ->route('finance.payments.show', $payment)
            ->with('success', 'Pembayaran berhasil dibatalkan.');
    }
}
```

---

# 46. Controller `FinanceLedgerController`

Buka:

```text
app/Http/Controllers/Finance/FinanceLedgerController.php
```

Isi lengkap:

```php
<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\FinanceLedgerEntry;
use App\Models\Student;
use App\Services\Finance\FinanceAccessService;
use App\Services\Finance\StudentBalanceService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FinanceLedgerController extends Controller
{
    public function student(
        Request $request,
        Student $student,
        FinanceAccessService $accessService,
        StudentBalanceService $balanceService
    ): View {
        abort_unless($accessService->canViewStudent($request->user(), $student), 403);

        $entries = FinanceLedgerEntry::query()
            ->where('student_id', $student->id)
            ->orderBy('entry_date')
            ->orderBy('id')
            ->get();

        $balance = $balanceService->balance($student);

        return view('finance.ledgers.student', compact('student', 'entries', 'balance'));
    }
}
```

---

# 47. Controller `FinanceReportController`

Buka:

```text
app/Http/Controllers/Finance/FinanceReportController.php
```

Isi lengkap:

```php
<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Http\Requests\Finance\FinanceReportFilterRequest;
use App\Models\ClassRoom;
use App\Models\Student;
use App\Services\Finance\FinanceAccessService;
use App\Services\Finance\FinanceReportService;
use Illuminate\View\View;

class FinanceReportController extends Controller
{
    public function dashboard(
        FinanceReportFilterRequest $request,
        FinanceAccessService $accessService,
        FinanceReportService $reportService
    ): View {
        abort_unless($accessService->canViewFinanceReport($request->user()), 403);

        $filters = $request->validated();
        $report = $reportService->dashboard($filters);

        $classRooms = ClassRoom::query()
            ->orderBy('name')
            ->get();

        $students = Student::query()
            ->orderBy('nama_lengkap')
            ->limit(500)
            ->get();

        return view('finance.reports.dashboard', compact(
            'report',
            'filters',
            'classRooms',
            'students'
        ));
    }
}
```

Jika `class_rooms` tidak punya kolom `name`, ubah sesuai kolom asli.

---

# 48. Controller `ParentFinancePortalController`

Buka:

```text
app/Http/Controllers/Portal/ParentFinancePortalController.php
```

Isi lengkap:

```php
<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Http\Requests\Finance\FinanceReportFilterRequest;
use App\Models\Student;
use App\Services\Finance\FinanceAccessService;
use App\Services\Finance\StudentBalanceService;
use Illuminate\View\View;

class ParentFinancePortalController extends Controller
{
    public function index(
        FinanceReportFilterRequest $request,
        FinanceAccessService $accessService,
        StudentBalanceService $balanceService
    ): View {
        abort_unless($accessService->isParent($request->user()), 403);

        $students = $accessService
            ->applyStudentScope(Student::query()->orderBy('nama_lengkap'), $request->user())
            ->get();

        $selectedStudent = null;
        $bills = collect();
        $payments = collect();
        $balance = null;

        if ($students->isNotEmpty()) {
            $selectedStudent = $students->firstWhere('id', (int) $request->input('student_id'))
                ?? $students->first();

            $selectedStudent->load([
                'financeBills.items',
                'financePayments.allocations.bill',
            ]);

            $bills = $selectedStudent->financeBills()
                ->orderByDesc('issued_date')
                ->get();

            $payments = $selectedStudent->financePayments()
                ->orderByDesc('payment_date')
                ->get();

            $balance = $balanceService->balance($selectedStudent);
        }

        return view('portal.parent.finance', compact(
            'students',
            'selectedStudent',
            'bills',
            'payments',
            'balance'
        ));
    }
}
```

---

# 49. Controller `StudentFinancePortalController`

Buka:

```text
app/Http/Controllers/Portal/StudentFinancePortalController.php
```

Isi lengkap:

```php
<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Http\Requests\Finance\FinanceReportFilterRequest;
use App\Models\Student;
use App\Services\Finance\FinanceAccessService;
use App\Services\Finance\StudentBalanceService;
use Illuminate\View\View;

class StudentFinancePortalController extends Controller
{
    public function index(
        FinanceReportFilterRequest $request,
        FinanceAccessService $accessService,
        StudentBalanceService $balanceService
    ): View {
        abort_unless($accessService->isStudent($request->user()), 403);

        $student = Student::query()
            ->where('user_id', $request->user()->id)
            ->first();

        if (! $student) {
            return view('portal.student.finance', [
                'student' => null,
                'bills' => collect(),
                'payments' => collect(),
                'balance' => null,
            ]);
        }

        $bills = $student->financeBills()
            ->orderByDesc('issued_date')
            ->get();

        $payments = $student->financePayments()
            ->orderByDesc('payment_date')
            ->get();

        $balance = $balanceService->balance($student);

        return view('portal.student.finance', compact(
            'student',
            'bills',
            'payments',
            'balance'
        ));
    }
}
```

---

# 50. Format Rupiah Helper Sederhana di Blade

Untuk Phase 14, gunakan format inline dulu:

```blade
Rp {{ number_format($amount ?? 0, 0, ',', '.') }}
```

Jangan membuat package money dulu.

---

# 51. View Minimal `finance/bills/index.blade.php`

Buat:

```text
resources/views/finance/bills/index.blade.php
```

Isi lengkap:

```blade
@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Tagihan Santri</h1>
            <p class="text-sm text-gray-600">Kelola tagihan dan status pembayaran santri.</p>
        </div>

        <a href="{{ route('finance.bills.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg">
            Buat Tagihan
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-lg bg-green-50 px-4 py-3 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left">Invoice</th>
                    <th class="px-4 py-3 text-left">Santri</th>
                    <th class="px-4 py-3 text-left">Tanggal</th>
                    <th class="px-4 py-3 text-left">Total</th>
                    <th class="px-4 py-3 text-left">Terbayar</th>
                    <th class="px-4 py-3 text-left">Sisa</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($bills as $bill)
                    <tr>
                        <td class="px-4 py-3">{{ $bill->invoice_number }}</td>
                        <td class="px-4 py-3">{{ $bill->student?->nama_lengkap ?? $bill->student?->name ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $bill->issued_date?->format('d M Y') }}</td>
                        <td class="px-4 py-3">Rp {{ number_format($bill->total_amount, 0, ',', '.') }}</td>
                        <td class="px-4 py-3">Rp {{ number_format($bill->paid_amount, 0, ',', '.') }}</td>
                        <td class="px-4 py-3">Rp {{ number_format($bill->outstanding_amount, 0, ',', '.') }}</td>
                        <td class="px-4 py-3">{{ $bill->status }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('finance.bills.show', $bill) }}" class="text-blue-600">Detail</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-6 text-center text-gray-500">Belum ada tagihan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $bills->links() }}</div>
</div>
@endsection
```

---

# 52. View Minimal `finance/bills/create.blade.php`

Buat:

```text
resources/views/finance/bills/create.blade.php
```

Isi lengkap:

```blade
@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Buat Tagihan Santri</h1>

    @if($errors->any())
        <div class="mb-4 rounded-lg bg-red-50 px-4 py-3 text-red-700">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('finance.bills.store') }}" method="POST" class="bg-white rounded-xl shadow p-6 space-y-5">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Santri</label>
                <select name="student_id" class="mt-1 w-full rounded-lg border-gray-300" required>
                    @foreach($students as $student)
                        <option value="{{ $student->id }}">
                            {{ $student->nama_lengkap ?? $student->name ?? 'Santri #' . $student->id }}
                            — {{ $student->classRoom?->name ?? '-' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Judul Tagihan</label>
                <input type="text" name="title" class="mt-1 w-full rounded-lg border-gray-300" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Tanggal Terbit</label>
                <input type="date" name="issued_date" value="{{ now()->toDateString() }}" class="mt-1 w-full rounded-lg border-gray-300" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Jatuh Tempo</label>
                <input type="date" name="due_date" class="mt-1 w-full rounded-lg border-gray-300">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
            <textarea name="description" rows="2" class="mt-1 w-full rounded-lg border-gray-300"></textarea>
        </div>

        <div class="bg-gray-50 rounded-lg p-4">
            <h2 class="font-semibold text-gray-900 mb-3">Item Tagihan</h2>

            @for($i = 0; $i < 5; $i++)
                <div class="grid grid-cols-1 md:grid-cols-5 gap-3 bg-white rounded-lg border p-3 mb-3">
                    <div>
                        <label class="block text-xs text-gray-500">Master Item</label>
                        <select name="items[{{ $i }}][finance_fee_item_id]" class="mt-1 w-full rounded-lg border-gray-300">
                            <option value="">Manual</option>
                            @foreach($feeItems as $feeItem)
                                <option value="{{ $feeItem->id }}">{{ $feeItem->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs text-gray-500">Nama Item</label>
                        <input type="text" name="items[{{ $i }}][name]" class="mt-1 w-full rounded-lg border-gray-300" @if($i === 0) required @endif>
                    </div>

                    <div>
                        <label class="block text-xs text-gray-500">Qty</label>
                        <input type="number" name="items[{{ $i }}][quantity]" value="1" min="1" class="mt-1 w-full rounded-lg border-gray-300">
                    </div>

                    <div>
                        <label class="block text-xs text-gray-500">Nominal</label>
                        <input type="number" name="items[{{ $i }}][unit_amount]" value="0" min="0" class="mt-1 w-full rounded-lg border-gray-300">
                    </div>

                    <div>
                        <label class="block text-xs text-gray-500">Catatan</label>
                        <input type="text" name="items[{{ $i }}][description]" class="mt-1 w-full rounded-lg border-gray-300">
                    </div>
                </div>
            @endfor
        </div>

        <div class="text-right">
            <button class="px-4 py-2 bg-blue-600 text-white rounded-lg">
                Simpan Tagihan
            </button>
        </div>
    </form>
</div>
@endsection
```

Catatan:

Form ini menyediakan 5 baris item statis agar sederhana. Enhancement dinamis JavaScript bisa dibuat nanti.

---

# 53. View Minimal `finance/bills/show.blade.php`

Buat:

```text
resources/views/finance/bills/show.blade.php
```

Isi lengkap:

```blade
@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-6">
    <div class="bg-white rounded-xl shadow p-6 mb-6">
        <div class="flex justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $bill->invoice_number }}</h1>
                <p class="text-sm text-gray-600">{{ $bill->title }}</p>
                <p class="text-sm text-gray-600">
                    {{ $bill->student?->nama_lengkap ?? $bill->student?->name ?? '-' }}
                    —
                    {{ $bill->student?->classRoom?->name ?? '-' }}
                </p>
            </div>

            <div class="text-right">
                <div class="text-sm text-gray-500">Status</div>
                <div class="font-bold">{{ $bill->status }}</div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
            <div>
                <div class="text-sm text-gray-500">Total</div>
                <div class="text-xl font-bold">Rp {{ number_format($bill->total_amount, 0, ',', '.') }}</div>
            </div>
            <div>
                <div class="text-sm text-gray-500">Terbayar</div>
                <div class="text-xl font-bold">Rp {{ number_format($bill->paid_amount, 0, ',', '.') }}</div>
            </div>
            <div>
                <div class="text-sm text-gray-500">Sisa</div>
                <div class="text-xl font-bold">Rp {{ number_format($bill->outstanding_amount, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow overflow-hidden mb-6">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left">Item</th>
                    <th class="px-4 py-3 text-left">Qty</th>
                    <th class="px-4 py-3 text-left">Nominal</th>
                    <th class="px-4 py-3 text-left">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($bill->items as $item)
                    <tr>
                        <td class="px-4 py-3">{{ $item->name }}</td>
                        <td class="px-4 py-3">{{ $item->quantity }}</td>
                        <td class="px-4 py-3">Rp {{ number_format($item->unit_amount, 0, ',', '.') }}</td>
                        <td class="px-4 py-3">Rp {{ number_format($item->total_amount, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($bill->status !== 'void' && $bill->paid_amount == 0)
        <form action="{{ route('finance.bills.void', $bill) }}" method="POST" class="bg-red-50 rounded-xl p-4" onsubmit="return confirm('Batalkan tagihan ini?')">
            @csrf
            @method('PATCH')

            <label class="block text-sm font-medium text-red-700">Alasan Pembatalan</label>
            <textarea name="void_reason" rows="2" class="mt-1 w-full rounded-lg border-red-300" required></textarea>

            <button class="mt-3 px-4 py-2 bg-red-600 text-white rounded-lg">
                Void Tagihan
            </button>
        </form>
    @endif
</div>
@endsection
```

---

# 54. View Minimal `finance/payments/index.blade.php`

Buat:

```text
resources/views/finance/payments/index.blade.php
```

Isi lengkap:

```blade
@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Pembayaran Santri</h1>
            <p class="text-sm text-gray-600">Catatan pembayaran manual.</p>
        </div>

        <a href="{{ route('finance.payments.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg">
            Catat Pembayaran
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-lg bg-green-50 px-4 py-3 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left">Receipt</th>
                    <th class="px-4 py-3 text-left">Santri</th>
                    <th class="px-4 py-3 text-left">Tanggal</th>
                    <th class="px-4 py-3 text-left">Metode</th>
                    <th class="px-4 py-3 text-left">Nominal</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($payments as $payment)
                    <tr>
                        <td class="px-4 py-3">{{ $payment->receipt_number }}</td>
                        <td class="px-4 py-3">{{ $payment->student?->nama_lengkap ?? $payment->student?->name ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $payment->payment_date?->format('d M Y') }}</td>
                        <td class="px-4 py-3">{{ $payment->payment_method }}</td>
                        <td class="px-4 py-3">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                        <td class="px-4 py-3">{{ $payment->status }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('finance.payments.show', $payment) }}" class="text-blue-600">Detail</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-6 text-center text-gray-500">Belum ada pembayaran.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $payments->links() }}</div>
</div>
@endsection
```

---

# 55. View Minimal `finance/payments/create.blade.php`

Buat:

```text
resources/views/finance/payments/create.blade.php
```

Isi lengkap:

```blade
@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Catat Pembayaran</h1>

    @if($errors->any())
        <div class="mb-4 rounded-lg bg-red-50 px-4 py-3 text-red-700">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('finance.payments.store') }}" method="POST" class="bg-white rounded-xl shadow p-6 space-y-5">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Santri</label>
                <select name="student_id" class="mt-1 w-full rounded-lg border-gray-300" required>
                    @foreach($students as $student)
                        <option value="{{ $student->id }}">
                            {{ $student->nama_lengkap ?? $student->name ?? 'Santri #' . $student->id }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Tanggal Bayar</label>
                <input type="date" name="payment_date" value="{{ now()->toDateString() }}" class="mt-1 w-full rounded-lg border-gray-300" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Metode</label>
                <select name="payment_method" class="mt-1 w-full rounded-lg border-gray-300" required>
                    <option value="cash">Tunai</option>
                    <option value="bank_transfer">Transfer Bank</option>
                    <option value="qris_external">QRIS External</option>
                    <option value="adjustment">Adjustment</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Nominal</label>
                <input type="number" name="amount" min="1" class="mt-1 w-full rounded-lg border-gray-300" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Nomor Referensi</label>
                <input type="text" name="reference_number" class="mt-1 w-full rounded-lg border-gray-300">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Alokasikan ke Tagihan</label>
            <select name="bill_ids[]" multiple class="mt-1 w-full rounded-lg border-gray-300">
                @foreach($openBills as $bill)
                    <option value="{{ $bill->id }}">
                        {{ $bill->invoice_number }}
                        —
                        {{ $bill->student?->nama_lengkap ?? $bill->student?->name ?? '-' }}
                        —
                        Sisa Rp {{ number_format($bill->outstanding_amount, 0, ',', '.') }}
                    </option>
                @endforeach
            </select>
            <p class="text-xs text-gray-500 mt-1">Tekan Ctrl untuk memilih lebih dari satu tagihan.</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Catatan</label>
            <textarea name="note" rows="3" class="mt-1 w-full rounded-lg border-gray-300"></textarea>
        </div>

        <div class="text-right">
            <button class="px-4 py-2 bg-blue-600 text-white rounded-lg">
                Simpan Pembayaran
            </button>
        </div>
    </form>
</div>
@endsection
```

---

# 56. View Minimal `finance/payments/show.blade.php`

Buat:

```text
resources/views/finance/payments/show.blade.php
```

Isi lengkap:

```blade
@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-6">
    <div class="bg-white rounded-xl shadow p-6 mb-6">
        <h1 class="text-2xl font-bold text-gray-900">{{ $payment->receipt_number }}</h1>
        <p class="text-sm text-gray-600">
            {{ $payment->student?->nama_lengkap ?? $payment->student?->name ?? '-' }}
            —
            {{ $payment->payment_date?->format('d M Y') }}
        </p>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-6">
            <div>
                <div class="text-sm text-gray-500">Nominal</div>
                <div class="font-bold">Rp {{ number_format($payment->amount, 0, ',', '.') }}</div>
            </div>

            <div>
                <div class="text-sm text-gray-500">Metode</div>
                <div class="font-bold">{{ $payment->payment_method }}</div>
            </div>

            <div>
                <div class="text-sm text-gray-500">Referensi</div>
                <div class="font-bold">{{ $payment->reference_number ?? '-' }}</div>
            </div>

            <div>
                <div class="text-sm text-gray-500">Status</div>
                <div class="font-bold">{{ $payment->status }}</div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow overflow-hidden mb-6">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left">Invoice</th>
                    <th class="px-4 py-3 text-left">Alokasi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($payment->allocations as $allocation)
                    <tr>
                        <td class="px-4 py-3">{{ $allocation->bill?->invoice_number ?? '-' }}</td>
                        <td class="px-4 py-3">Rp {{ number_format($allocation->amount, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="px-4 py-6 text-center text-gray-500">
                            Pembayaran belum dialokasikan ke tagihan tertentu.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($payment->status !== 'void')
        <form action="{{ route('finance.payments.void', $payment) }}" method="POST" class="bg-red-50 rounded-xl p-4" onsubmit="return confirm('Batalkan pembayaran ini?')">
            @csrf
            @method('PATCH')

            <label class="block text-sm font-medium text-red-700">Alasan Pembatalan</label>
            <textarea name="void_reason" rows="2" class="mt-1 w-full rounded-lg border-red-300" required></textarea>

            <button class="mt-3 px-4 py-2 bg-red-600 text-white rounded-lg">
                Void Pembayaran
            </button>
        </form>
    @endif
</div>
@endsection
```

---

# 57. View Minimal `finance/ledgers/student.blade.php`

Buat:

```text
resources/views/finance/ledgers/student.blade.php
```

Isi lengkap:

```blade
@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Ledger Santri</h1>
        <p class="text-sm text-gray-600">
            {{ $student->nama_lengkap ?? $student->name ?? 'Santri #' . $student->id }}
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow p-4">
            <div class="text-sm text-gray-500">Total Debit</div>
            <div class="text-xl font-bold">Rp {{ number_format($balance['debit'], 0, ',', '.') }}</div>
        </div>

        <div class="bg-white rounded-xl shadow p-4">
            <div class="text-sm text-gray-500">Total Credit</div>
            <div class="text-xl font-bold">Rp {{ number_format($balance['credit'], 0, ',', '.') }}</div>
        </div>

        <div class="bg-white rounded-xl shadow p-4">
            <div class="text-sm text-gray-500">Saldo Tagihan</div>
            <div class="text-xl font-bold">Rp {{ number_format($balance['balance'], 0, ',', '.') }}</div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left">Tanggal</th>
                    <th class="px-4 py-3 text-left">Keterangan</th>
                    <th class="px-4 py-3 text-left">Debit</th>
                    <th class="px-4 py-3 text-left">Credit</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($entries as $entry)
                    <tr>
                        <td class="px-4 py-3">{{ $entry->entry_date?->format('d M Y') }}</td>
                        <td class="px-4 py-3">{{ $entry->description ?? '-' }}</td>
                        <td class="px-4 py-3">
                            @if($entry->direction === 'debit')
                                Rp {{ number_format($entry->amount, 0, ',', '.') }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @if($entry->direction === 'credit')
                                Rp {{ number_format($entry->amount, 0, ',', '.') }}
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-6 text-center text-gray-500">Belum ada ledger.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
```

---

# 58. View Minimal `finance/reports/dashboard.blade.php`

Buat:

```text
resources/views/finance/reports/dashboard.blade.php
```

Isi lengkap:

```blade
@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Laporan Finance</h1>
        <p class="text-sm text-gray-600">Ringkasan tagihan, pembayaran, dan tunggakan.</p>
    </div>

    <form method="GET" action="{{ route('finance.reports.dashboard') }}" class="mb-6 bg-white rounded-xl shadow p-4 grid grid-cols-1 md:grid-cols-5 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700">Mulai</label>
            <input type="date" name="start_date" value="{{ $filters['start_date'] ?? $report['period']['start_date'] }}" class="mt-1 w-full rounded-lg border-gray-300">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Selesai</label>
            <input type="date" name="end_date" value="{{ $filters['end_date'] ?? $report['period']['end_date'] }}" class="mt-1 w-full rounded-lg border-gray-300">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Kelas</label>
            <select name="class_room_id" class="mt-1 w-full rounded-lg border-gray-300">
                <option value="">Semua</option>
                @foreach($classRooms as $classRoom)
                    <option value="{{ $classRoom->id }}" @selected(($filters['class_room_id'] ?? null) == $classRoom->id)>
                        {{ $classRoom->name ?? $classRoom->nama ?? 'Kelas #' . $classRoom->id }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Santri</label>
            <select name="student_id" class="mt-1 w-full rounded-lg border-gray-300">
                <option value="">Semua</option>
                @foreach($students as $student)
                    <option value="{{ $student->id }}" @selected(($filters['student_id'] ?? null) == $student->id)>
                        {{ $student->nama_lengkap ?? $student->name ?? 'Santri #' . $student->id }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="flex items-end">
            <button class="px-4 py-2 bg-blue-600 text-white rounded-lg">Filter</button>
        </div>
    </form>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow p-4">
            <div class="text-sm text-gray-500">Total Tagihan</div>
            <div class="text-xl font-bold">Rp {{ number_format($report['summary']['total_billed'], 0, ',', '.') }}</div>
        </div>

        <div class="bg-white rounded-xl shadow p-4">
            <div class="text-sm text-gray-500">Total Pembayaran</div>
            <div class="text-xl font-bold">Rp {{ number_format($report['summary']['total_paid'], 0, ',', '.') }}</div>
        </div>

        <div class="bg-white rounded-xl shadow p-4">
            <div class="text-sm text-gray-500">Sisa Tunggakan</div>
            <div class="text-xl font-bold">Rp {{ number_format($report['summary']['total_outstanding'], 0, ',', '.') }}</div>
        </div>

        <div class="bg-white rounded-xl shadow p-4">
            <div class="text-sm text-gray-500">Tagihan Belum Lunas</div>
            <div class="text-xl font-bold">{{ $report['summary']['unpaid_count'] }}</div>
        </div>
    </div>
</div>
@endsection
```

---

# 59. View Minimal `portal/parent/finance.blade.php`

Buat:

```text
resources/views/portal/parent/finance.blade.php
```

Isi lengkap:

```blade
@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Finance Anak</h1>
        <p class="text-sm text-gray-600">Pantau tagihan dan pembayaran anak.</p>
    </div>

    @if($students->isEmpty())
        <div class="bg-white rounded-xl shadow p-6 text-gray-600">
            Belum ada data anak yang terhubung. Hubungi admin sekolah.
        </div>
    @else
        <form method="GET" action="{{ route('portal.parent.finance') }}" class="mb-6 bg-white rounded-xl shadow p-4">
            <label class="block text-sm font-medium text-gray-700">Pilih Anak</label>
            <select name="student_id" class="mt-1 rounded-lg border-gray-300" onchange="this.form.submit()">
                @foreach($students as $student)
                    <option value="{{ $student->id }}" @selected($selectedStudent?->id === $student->id)>
                        {{ $student->nama_lengkap ?? $student->name ?? 'Santri #' . $student->id }}
                    </option>
                @endforeach
            </select>
        </form>

        @if($balance)
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-white rounded-xl shadow p-4">
                    <div class="text-sm text-gray-500">Total Tagihan</div>
                    <div class="font-bold">Rp {{ number_format($balance['debit'], 0, ',', '.') }}</div>
                </div>

                <div class="bg-white rounded-xl shadow p-4">
                    <div class="text-sm text-gray-500">Total Pembayaran</div>
                    <div class="font-bold">Rp {{ number_format($balance['credit'], 0, ',', '.') }}</div>
                </div>

                <div class="bg-white rounded-xl shadow p-4">
                    <div class="text-sm text-gray-500">Sisa</div>
                    <div class="font-bold">Rp {{ number_format($balance['balance'], 0, ',', '.') }}</div>
                </div>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow overflow-hidden">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left">Invoice</th>
                        <th class="px-4 py-3 text-left">Judul</th>
                        <th class="px-4 py-3 text-left">Total</th>
                        <th class="px-4 py-3 text-left">Sisa</th>
                        <th class="px-4 py-3 text-left">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($bills as $bill)
                        <tr>
                            <td class="px-4 py-3">{{ $bill->invoice_number }}</td>
                            <td class="px-4 py-3">{{ $bill->title }}</td>
                            <td class="px-4 py-3">Rp {{ number_format($bill->total_amount, 0, ',', '.') }}</td>
                            <td class="px-4 py-3">Rp {{ number_format($bill->outstanding_amount, 0, ',', '.') }}</td>
                            <td class="px-4 py-3">{{ $bill->status }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-gray-500">Belum ada tagihan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
```

---

# 60. View Minimal `portal/student/finance.blade.php`

Buat:

```text
resources/views/portal/student/finance.blade.php
```

Isi lengkap:

```blade
@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Finance Saya</h1>
        <p class="text-sm text-gray-600">Lihat tagihan dan pembayaran pribadi.</p>
    </div>

    @if(! $student)
        <div class="bg-white rounded-xl shadow p-6 text-gray-600">
            Profil santri belum terhubung. Hubungi admin sekolah.
        </div>
    @else
        @if($balance)
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-white rounded-xl shadow p-4">
                    <div class="text-sm text-gray-500">Total Tagihan</div>
                    <div class="font-bold">Rp {{ number_format($balance['debit'], 0, ',', '.') }}</div>
                </div>

                <div class="bg-white rounded-xl shadow p-4">
                    <div class="text-sm text-gray-500">Total Pembayaran</div>
                    <div class="font-bold">Rp {{ number_format($balance['credit'], 0, ',', '.') }}</div>
                </div>

                <div class="bg-white rounded-xl shadow p-4">
                    <div class="text-sm text-gray-500">Sisa</div>
                    <div class="font-bold">Rp {{ number_format($balance['balance'], 0, ',', '.') }}</div>
                </div>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow overflow-hidden">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left">Invoice</th>
                        <th class="px-4 py-3 text-left">Judul</th>
                        <th class="px-4 py-3 text-left">Total</th>
                        <th class="px-4 py-3 text-left">Sisa</th>
                        <th class="px-4 py-3 text-left">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($bills as $bill)
                        <tr>
                            <td class="px-4 py-3">{{ $bill->invoice_number }}</td>
                            <td class="px-4 py-3">{{ $bill->title }}</td>
                            <td class="px-4 py-3">Rp {{ number_format($bill->total_amount, 0, ',', '.') }}</td>
                            <td class="px-4 py-3">Rp {{ number_format($bill->outstanding_amount, 0, ',', '.') }}</td>
                            <td class="px-4 py-3">{{ $bill->status }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-gray-500">Belum ada tagihan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
```

---

# 61. Update Routes `routes/web.php`

Buka:

```text
routes/web.php
```

Tambahkan import:

```php
use App\Http\Controllers\Finance\FinanceFeeCategoryController;
use App\Http\Controllers\Finance\FinanceFeeItemController;
use App\Http\Controllers\Finance\StudentBillController;
use App\Http\Controllers\Finance\StudentPaymentController;
use App\Http\Controllers\Finance\FinanceLedgerController;
use App\Http\Controllers\Finance\FinanceReportController;
use App\Http\Controllers\Portal\ParentFinancePortalController;
use App\Http\Controllers\Portal\StudentFinancePortalController;
```

Tambahkan route di dalam middleware `auth`:

```php
Route::middleware(['auth'])->group(function (): void {
    Route::prefix('finance')
        ->name('finance.')
        ->middleware(['role:super_admin,admin,admin_sekolah,kepala_sekolah,principal'])
        ->group(function (): void {
            Route::get('/reports/dashboard', [FinanceReportController::class, 'dashboard'])
                ->name('reports.dashboard');

            Route::get('/bills', [StudentBillController::class, 'index'])
                ->name('bills.index');

            Route::get('/bills/create', [StudentBillController::class, 'create'])
                ->name('bills.create');

            Route::post('/bills', [StudentBillController::class, 'store'])
                ->name('bills.store');

            Route::get('/bills/{bill}', [StudentBillController::class, 'show'])
                ->name('bills.show');

            Route::patch('/bills/{bill}/void', [StudentBillController::class, 'void'])
                ->name('bills.void');

            Route::get('/payments', [StudentPaymentController::class, 'index'])
                ->name('payments.index');

            Route::get('/payments/create', [StudentPaymentController::class, 'create'])
                ->name('payments.create');

            Route::post('/payments', [StudentPaymentController::class, 'store'])
                ->name('payments.store');

            Route::get('/payments/{payment}', [StudentPaymentController::class, 'show'])
                ->name('payments.show');

            Route::patch('/payments/{payment}/void', [StudentPaymentController::class, 'void'])
                ->name('payments.void');

            Route::get('/ledgers/students/{student}', [FinanceLedgerController::class, 'student'])
                ->name('ledgers.student');

            Route::resource('fee-categories', FinanceFeeCategoryController::class);
            Route::resource('fee-items', FinanceFeeItemController::class);
        });

    Route::get('/portal/parent/finance', [ParentFinancePortalController::class, 'index'])
        ->middleware(['role:parent'])
        ->name('portal.parent.finance');

    Route::get('/portal/student/finance', [StudentFinancePortalController::class, 'index'])
        ->middleware(['role:student'])
        ->name('portal.student.finance');
});
```

Jika middleware role project memakai slug berbeda, sesuaikan.

Jangan hapus route lama.

---

# 62. Update Navigation

Buka salah satu file navigation:

```text
resources/views/layouts/navigation.blade.php
```

atau:

```text
resources/views/layouts/app.blade.php
```

Tambahkan menu:

```blade
@php
    $roleValue = auth()->user()?->role ?? null;
    $roleName = is_object($roleValue) ? ($roleValue->name ?? $roleValue->slug ?? null) : $roleValue;
@endphp

@if(in_array($roleName, ['super_admin', 'admin', 'admin_sekolah', 'kepala_sekolah', 'principal'], true))
    <a href="{{ route('finance.reports.dashboard') }}"
       class="block px-3 py-2 rounded-md text-sm font-medium">
        Finance
    </a>

    <a href="{{ route('finance.bills.index') }}"
       class="block px-3 py-2 rounded-md text-sm font-medium">
        Tagihan Santri
    </a>

    <a href="{{ route('finance.payments.index') }}"
       class="block px-3 py-2 rounded-md text-sm font-medium">
        Pembayaran
    </a>
@endif

@if(in_array($roleName, ['super_admin', 'admin', 'admin_sekolah'], true))
    <a href="{{ route('finance.fee-categories.index') }}"
       class="block px-3 py-2 rounded-md text-sm font-medium">
        Kategori Biaya
    </a>

    <a href="{{ route('finance.fee-items.index') }}"
       class="block px-3 py-2 rounded-md text-sm font-medium">
        Item Biaya
    </a>
@endif

@if($roleName === 'parent')
    <a href="{{ route('portal.parent.finance') }}"
       class="block px-3 py-2 rounded-md text-sm font-medium">
        Finance Anak
    </a>
@endif

@if($roleName === 'student')
    <a href="{{ route('portal.student.finance') }}"
       class="block px-3 py-2 rounded-md text-sm font-medium">
        Finance Saya
    </a>
@endif
```

Sesuaikan class dengan style layout project.

---

# 63. Jalankan Migration dan Seeder

Jalankan:

```powershell
php artisan migrate
php artisan db:seed --class=FinanceFeeCategorySeeder
```

Cek via tinker:

```powershell
php artisan tinker
```

Lalu:

```php
App\Models\FinanceFeeCategory::count();
App\Models\FinanceFeeItem::count();
App\Models\StudentBill::count();
App\Models\StudentPayment::count();
App\Models\FinanceLedgerEntry::count();
```

Target:

```text
FinanceFeeCategory > 0
FinanceFeeItem = 0 boleh
StudentBill = 0 boleh
StudentPayment = 0 boleh
FinanceLedgerEntry = 0 boleh
```

---

# 64. Validasi Route

Jalankan:

```powershell
php artisan route:list --name=finance
php artisan route:list --name=portal.parent.finance
php artisan route:list --name=portal.student.finance
```

Target route tersedia:

```text
finance.reports.dashboard
finance.bills.index
finance.bills.create
finance.bills.store
finance.bills.show
finance.bills.void
finance.payments.index
finance.payments.create
finance.payments.store
finance.payments.show
finance.payments.void
finance.ledgers.student
finance.fee-categories.index
finance.fee-categories.create
finance.fee-categories.store
finance.fee-categories.show
finance.fee-categories.edit
finance.fee-categories.update
finance.fee-categories.destroy
finance.fee-items.index
finance.fee-items.create
finance.fee-items.store
finance.fee-items.show
finance.fee-items.edit
finance.fee-items.update
finance.fee-items.destroy
portal.parent.finance
portal.student.finance
```

---

# 65. UAT Phase 14

## 65.1 Test Admin

Login sebagai admin.

Tes:

1. Buka `/finance/fee-categories`.
2. Buat kategori biaya.
3. Edit kategori biaya.
4. Buka `/finance/fee-items`.
5. Buat item biaya.
6. Buka `/finance/bills/create`.
7. Buat tagihan santri.
8. Buka detail tagihan.
9. Pastikan total tagihan benar.
10. Buka `/finance/payments/create`.
11. Catat pembayaran untuk tagihan.
12. Buka detail pembayaran.
13. Pastikan alokasi pembayaran benar.
14. Buka detail tagihan lagi.
15. Pastikan `paid_amount` dan `outstanding_amount` berubah.
16. Buka ledger santri.
17. Pastikan debit dan credit benar.
18. Buka `/finance/reports/dashboard`.

Expected:

1. Tidak ada error 500.
2. Invoice number otomatis.
3. Receipt number otomatis.
4. Tagihan menghasilkan ledger debit.
5. Pembayaran menghasilkan ledger credit.
6. Saldo = debit - credit.
7. Pembayaran partial membuat tagihan `partial`.
8. Pembayaran penuh membuat tagihan `paid`.
9. Report tampil.

---

## 65.2 Test Void Tagihan

Tes:

1. Buat tagihan baru.
2. Jangan bayar tagihan.
3. Void tagihan dengan alasan.
4. Cek ledger.

Expected:

1. Status tagihan menjadi `void`.
2. Ledger credit pembalik muncul.
3. Tagihan yang sudah punya pembayaran tidak bisa divoid langsung.

---

## 65.3 Test Void Pembayaran

Tes:

1. Buat tagihan.
2. Catat pembayaran.
3. Void pembayaran dengan alasan.
4. Cek tagihan.
5. Cek ledger.

Expected:

1. Status pembayaran menjadi `void`.
2. Tagihan kembali memiliki outstanding.
3. Ledger debit pembalik muncul.
4. Riwayat pembayaran tidak hilang.

---

## 65.4 Test Kepala Sekolah

Login sebagai kepala sekolah.

Tes:

1. Buka finance report.
2. Buka daftar tagihan.
3. Buka detail tagihan.
4. Coba buat tagihan.
5. Coba buat pembayaran.
6. Coba void transaksi.

Expected:

1. Kepala sekolah bisa melihat laporan.
2. Kepala sekolah tidak bisa membuat tagihan.
3. Kepala sekolah tidak bisa membuat pembayaran.
4. Kepala sekolah tidak bisa void transaksi.

---

## 65.5 Test Parent

Login sebagai parent.

Tes:

1. Buka `/portal/parent/finance`.
2. Pastikan hanya anak sendiri tampil.
3. Ubah query `student_id` ke ID anak lain.
4. Pastikan data anak lain tidak tampil.
5. Coba buka `/finance/reports/dashboard`.
6. Coba buka `/finance/bills/create`.
7. Coba buka `/finance/payments/create`.

Expected:

1. Parent hanya melihat finance anak sendiri.
2. Parent tidak bisa melihat data anak lain.
3. Parent tidak bisa akses internal finance.
4. Parent tidak bisa membuat tagihan.
5. Parent tidak bisa membuat pembayaran.

---

## 65.6 Test Student

Login sebagai student.

Tes:

1. Buka `/portal/student/finance`.
2. Pastikan hanya data pribadi tampil.
3. Coba buka `/finance/reports/dashboard`.
4. Coba buka `/finance/bills/create`.
5. Coba buka `/finance/payments/create`.

Expected:

1. Student hanya melihat finance pribadi.
2. Student tidak bisa akses internal finance.
3. Student tidak bisa membuat tagihan.
4. Student tidak bisa membuat pembayaran.

---

# 66. Build Frontend

Jalankan:

```powershell
npm run build
```

Build wajib berhasil.

---

# 67. Validasi Akhir

Jalankan:

```powershell
php artisan migrate:status
php artisan route:list --name=finance
php artisan route:list --name=portal.parent.finance
php artisan route:list --name=portal.student.finance
php artisan app:system-health-check
npm run build
git status
```

Jalankan server:

```powershell
php artisan serve
```

Buka:

```text
http://127.0.0.1:8000/finance/reports/dashboard
http://127.0.0.1:8000/finance/fee-categories
http://127.0.0.1:8000/finance/fee-items
http://127.0.0.1:8000/finance/bills
http://127.0.0.1:8000/finance/bills/create
http://127.0.0.1:8000/finance/payments
http://127.0.0.1:8000/finance/payments/create
http://127.0.0.1:8000/portal/parent/finance
http://127.0.0.1:8000/portal/student/finance
```

---

# 68. Dokumentasi Phase 14

Buat file:

```text
docs/phase-14-student-finance-ledger.md
```

Isi lengkap:

```md
# Phase 14 — Student Finance Ledger

## Status

Phase 14 menambahkan modul Student Finance Ledger untuk HafizPlus School Platform.

## Scope

Modul ini mencakup:

1. Kategori biaya.
2. Item biaya.
3. Tagihan santri.
4. Detail item tagihan.
5. Pembayaran manual.
6. Alokasi pembayaran ke tagihan.
7. Ledger debit/kredit per santri.
8. Saldo tagihan santri.
9. Laporan finance sederhana.
10. Parent finance portal.
11. Student finance portal.
12. Role-based access.
13. Ownership-based access.
14. Void transaction dengan alasan.

## Tabel Baru

1. `finance_fee_categories`
2. `finance_fee_items`
3. `student_bills`
4. `student_bill_items`
5. `student_payments`
6. `student_payment_allocations`
7. `finance_ledger_entries`

## Model Baru

1. `FinanceFeeCategory`
2. `FinanceFeeItem`
3. `StudentBill`
4. `StudentBillItem`
5. `StudentPayment`
6. `StudentPaymentAllocation`
7. `FinanceLedgerEntry`

## Controller Baru

1. `FinanceFeeCategoryController`
2. `FinanceFeeItemController`
3. `StudentBillController`
4. `StudentPaymentController`
5. `FinanceLedgerController`
6. `FinanceReportController`
7. `ParentFinancePortalController`
8. `StudentFinancePortalController`

## Service Baru

1. `FinanceAccessService`
2. `InvoiceNumberGenerator`
3. `ReceiptNumberGenerator`
4. `StudentBillService`
5. `StudentPaymentService`
6. `StudentBalanceService`
7. `FinanceReportService`

## Seeder Baru

1. `FinanceFeeCategorySeeder`

## Route Baru

1. `finance.reports.dashboard`
2. `finance.bills.index`
3. `finance.bills.create`
4. `finance.bills.store`
5. `finance.bills.show`
6. `finance.bills.void`
7. `finance.payments.index`
8. `finance.payments.create`
9. `finance.payments.store`
10. `finance.payments.show`
11. `finance.payments.void`
12. `finance.ledgers.student`
13. `finance.fee-categories.*`
14. `finance.fee-items.*`
15. `portal.parent.finance`
16. `portal.student.finance`

## Role Access

| Role | Akses |
|---|---|
| Super Admin | CRUD master biaya, tagihan, pembayaran, void, report |
| Admin | CRUD master biaya, tagihan, pembayaran, void, report |
| Kepala Sekolah | Read-only report dan ledger |
| Teacher/Guru | Tidak akses finance default |
| Parent | Portal finance anak sendiri |
| Student | Portal finance pribadi |

## Batasan

Phase 14 tidak membuat:

1. Payment gateway.
2. Midtrans/Xendit/Doku/Tripay/Duitku.
3. QRIS otomatis.
4. Virtual account.
5. Cashless kantin.
6. Wallet.
7. Merchant POS.
8. Refund otomatis.
9. Rekonsiliasi bank otomatis.
10. Mobile app.
11. White-label finance.
12. Multi-tenant finance kompleks.

## Definition of Done

Phase 14 selesai jika:

1. Migration berhasil.
2. Seeder berhasil.
3. Admin bisa membuat kategori biaya.
4. Admin bisa membuat item biaya.
5. Admin bisa membuat tagihan santri.
6. Tagihan membuat ledger debit.
7. Admin bisa mencatat pembayaran.
8. Pembayaran membuat ledger credit.
9. Pembayaran partial mengubah status tagihan menjadi `partial`.
10. Pembayaran penuh mengubah status tagihan menjadi `paid`.
11. Void tagihan membuat ledger pembalik.
12. Void pembayaran membuat ledger pembalik.
13. Kepala sekolah read-only.
14. Parent hanya melihat finance anak sendiri.
15. Student hanya melihat finance pribadi.
16. Parent/student tidak bisa akses internal finance.
17. `npm run build` berhasil.
18. `php artisan app:system-health-check` berhasil.
19. Dokumentasi dibuat.
```

---

# 69. Update `docs/project-progress.md`

Buka:

```text
docs/project-progress.md
```

Update menjadi:

```md
# Project Progress — HafizPlus School Platform

| Phase | Nama | Status |
|---:|---|---|
| 0 | Product Foundation | Done |
| 1 | Auth, Role, and Initial Database Foundation | Done |
| 2 | Master Data Foundation | Done |
| 3 | Tahfizh Core Database Foundation | Done |
| 4 | Tahfizh Input Foundation | Done |
| 5 | Target and Debt Calculation | Done |
| 6 | Dashboard and Reports | Done |
| 7 | Parent and Student Progress Portal | Done |
| 8 | Notification Center | Done |
| 9 | Export PDF and Excel | Done |
| 10 | Production Hardening | Done |
| 11 | Mutabaah Yaumiyah Tracker | Done |
| 12 | QR Attendance System | Done |
| 13 | Tahsin Management App | Done |
| 14 | Student Finance Ledger | Done |
| 15 | SchoolOS Mini | Pending |
```

---

# 70. Commit Phase 14

Jalankan:

```powershell
git status
git add .
git commit -m "feat: add student finance ledger"
```

Jika remote tersedia:

```powershell
git push origin phase-14-student-finance-ledger
```

---

# 71. Output Akhir yang Harus Dilaporkan Agent

Setelah selesai, agent harus melaporkan:

```text
Phase 14 selesai.

Project:
- HafizPlus School Platform
- Laravel 12
- MySQL

Fitur dibuat:
- Kategori Biaya
- Item Biaya
- Tagihan Santri
- Detail Item Tagihan
- Pembayaran Manual
- Alokasi Pembayaran
- Finance Ledger debit/credit
- Student Balance
- Finance Report Dashboard
- Parent Finance Portal
- Student Finance Portal
- Void Tagihan
- Void Pembayaran
- Role-based access
- Ownership-based access

Tabel dibuat:
- finance_fee_categories
- finance_fee_items
- student_bills
- student_bill_items
- student_payments
- student_payment_allocations
- finance_ledger_entries

Route dibuat:
- finance.reports.dashboard
- finance.bills.index
- finance.bills.create
- finance.bills.store
- finance.bills.show
- finance.bills.void
- finance.payments.index
- finance.payments.create
- finance.payments.store
- finance.payments.show
- finance.payments.void
- finance.ledgers.student
- finance.fee-categories.*
- finance.fee-items.*
- portal.parent.finance
- portal.student.finance

Belum dibuat:
- Payment gateway
- Cashless kantin
- Wallet
- Merchant POS
- Virtual account
- QRIS otomatis
- Rekonsiliasi bank otomatis
- Refund otomatis
- White-label finance
- Mobile app

Status:
- Siap lanjut Phase 15 hanya setelah UAT Phase 14 aman.
```

---

# 72. Larangan Setelah Phase 14

Agent harus berhenti setelah Phase 14 selesai.

Jangan lanjut membuat:

1. SchoolOS Mini.
2. Boarding School Management.
3. White-label.
4. Cashless POS.
5. Wallet.
6. Payment Gateway.
7. Mobile App.
8. WhatsApp Gateway.
9. Push Notification.
10. Multi-tenant billing.

Semua itu masuk phase berikutnya.

---

# 73. Keputusan Akhir

Phase 14 hanya valid jika Student Finance Ledger stabil, akurat, dan tidak merusak core Tahfizh, Parent Portal, Notification, Mutabaah, Attendance, dan Tahsin.

Prioritas setelah Phase 14:

1. UAT Finance.
2. Fix bug Finance.
3. Cek saldo tagihan.
4. Cek pembayaran partial.
5. Cek pembayaran penuh.
6. Cek void transaksi.
7. Cek akses parent/student.
8. Cek laporan finance.
9. Backup database.
10. Baru pertimbangkan Phase 15 — SchoolOS Mini.

Jangan masuk SchoolOS Mini sebelum Finance aman. Kesalahan saldo/tagihan akan langsung merusak trust sekolah.
