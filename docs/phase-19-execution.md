# Phase 19 Execution Guide — Cashless Kantin / Merchant POS

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
Phase 19 — Cashless Kantin / Merchant POS
```

---

# 1. Keputusan Sebelum Phase 19

## 1.1 Phase 19 Adalah Modul Risiko Tinggi

Phase 19 menyentuh transaksi uang internal sekolah.

Kesalahan pada phase ini dapat menyebabkan:

1. Saldo santri salah.
2. Transaksi dobel.
3. Refund tidak tercatat.
4. Kasir bisa memanipulasi transaksi.
5. Orang tua kehilangan kepercayaan.
6. Laporan kantin tidak cocok dengan saldo ledger.
7. Audit transaksi tidak bisa dilacak.

Karena itu Phase 19 harus dibuat dengan prinsip:

1. Ledger-first.
2. Tidak ada delete transaksi.
3. Semua perubahan saldo harus lewat ledger.
4. Semua transaksi harus punya nomor unik.
5. Semua transaksi harus punya actor/user.
6. Semua void/refund harus punya alasan.
7. Semua endpoint transaksi harus memakai authorization ketat.
8. Semua transaksi harus memakai database transaction.
9. Semua transaksi harus idempotent sejauh mungkin.
10. Tidak boleh ada update saldo langsung tanpa audit trail.

---

## 1.2 UAT Phase 18 Wajib

Sebelum menjalankan Phase 19, agent wajib memastikan Phase 18 White-Label School App Builder sudah aman.

Phase 19 hanya boleh dieksekusi jika:

1. Tenant context Phase 17 berjalan.
2. Data antar sekolah tidak bocor.
3. White-label theme per sekolah tampil benar.
4. Logo, warna, dan branding sekolah tidak bocor ke tenant lain.
5. Custom branding tidak merusak auth dan portal.
6. Parent hanya melihat data anak sendiri dalam tenant yang benar.
7. Student hanya melihat data pribadi dalam tenant yang benar.
8. Admin sekolah hanya mengelola tenant/sekolahnya sendiri.
9. Route internal tidak bisa diakses lintas tenant.
10. `php artisan app:system-health-check` berhasil.
11. `npm run build` berhasil.
12. Tidak ada bug P0/P1 terbuka.

Jika masih ada bug P0/P1, hentikan Phase 19 dan lakukan bug fix sprint dulu.

---

## 1.3 Klasifikasi Bug Sebelum Phase 19

| Prioritas | Contoh Bug | Keputusan |
|---|---|---|
| P0 | Data tenant bocor, saldo bisa berubah tanpa ledger, parent melihat saldo anak lain, kasir lintas sekolah | Wajib fix sebelum lanjut |
| P1 | Transaksi dobel, laporan saldo salah, refund tidak balance, settlement tidak cocok | Wajib fix sebelum lanjut |
| P2 | UI POS kurang rapi, wording kurang jelas, filter laporan kurang nyaman | Boleh dicatat |
| P3 | Enhancement kosmetik | Boleh ditunda |

---

# 2. Tujuan Phase 19

Phase 19 bertujuan membuat modul **Cashless Kantin / Merchant POS** untuk transaksi internal sekolah.

Fokus Phase 19:

1. Master merchant/kantin.
2. Akun kasir merchant.
3. Master produk merchant.
4. Wallet/saldo santri.
5. Top-up saldo manual oleh admin/finance.
6. POS kasir untuk pembelian.
7. Pembayaran menggunakan saldo santri.
8. Refund/void transaksi dengan audit.
9. Settlement harian merchant.
10. Laporan penjualan merchant.
11. Laporan mutasi wallet santri.
12. Portal orang tua untuk melihat saldo dan transaksi anak.
13. Portal santri untuk melihat saldo dan transaksi pribadi.
14. Role-based access.
15. Tenant-based access.
16. Ownership-based access.
17. Audit log transaksi.
18. Dokumentasi Phase 19.

---

# 3. Batasan Phase 19

AI agent tidak boleh membuat fitur berikut pada Phase 19:

1. Payment gateway production.
2. Midtrans.
3. Xendit.
4. Doku.
5. Tripay.
6. Duitku.
7. QRIS otomatis.
8. Virtual account.
9. Bank callback.
10. Rekonsiliasi bank otomatis.
11. Kartu fisik NFC/RFID.
12. Fingerprint payment.
13. Face recognition payment.
14. Native Android POS.
15. Native iOS POS.
16. Offline sync kompleks.
17. Multi-currency.
18. Marketplace antar sekolah.
19. Payroll pegawai.
20. Accounting double-entry penuh.
21. Pajak kompleks.
22. Inventory warehouse kompleks.
23. Supplier management kompleks.
24. Pinjaman / kredit siswa.
25. Split payment eksternal.
26. Auto withdrawal merchant ke bank.

Phase 19 hanya membuat:

```text
Closed-loop cashless wallet untuk kantin internal sekolah berbasis Laravel Blade + MySQL.
```

---

# 4. Prinsip Cashless Phase 19

## 4.1 Closed-Loop Wallet

Cashless Phase 19 adalah wallet tertutup untuk lingkungan sekolah.

Artinya:

1. Saldo hanya berlaku di dalam sekolah.
2. Saldo dipakai untuk transaksi kantin/merchant internal.
3. Top-up dicatat manual oleh admin/finance.
4. Tidak ada integrasi bank/payment gateway otomatis.
5. Tidak ada tarik saldo otomatis ke rekening.
6. Semua transaksi tercatat di ledger internal.

---

## 4.2 Saldo Tidak Boleh Diupdate Langsung

Saldo wallet tidak boleh diubah langsung dengan query seperti:

```php
$wallet->balance += $amount;
$wallet->save();
```

Saldo harus berubah lewat service transaksi yang:

1. Membuka database transaction.
2. Lock wallet row.
3. Membuat wallet transaction ledger.
4. Mengubah balance setelah ledger valid.
5. Membuat audit log.
6. Commit database transaction.

---

## 4.3 Tidak Boleh Delete Transaksi

Transaksi cashless tidak boleh dihapus.

Jika ada kesalahan:

1. Buat void/refund.
2. Buat ledger pembalik.
3. Simpan alasan.
4. Simpan user yang melakukan.
5. Simpan waktu.

---

## 4.4 POS Harus Idempotent

Setiap submit transaksi POS harus punya `idempotency_key`.

Tujuan:

1. Mencegah double charge karena kasir klik dua kali.
2. Mencegah transaksi dobel karena browser retry.
3. Mencegah network glitch membuat sale dobel.

Jika request dengan `idempotency_key` yang sama masuk lagi, sistem harus mengembalikan transaksi yang sama, bukan membuat transaksi baru.

---

## 4.5 Wallet Santri Harus Tenant-Scoped

Wallet santri wajib terkait dengan:

1. `school_id`
2. `student_id`

Aturan keras:

1. Wallet satu santri tidak boleh dipakai di sekolah lain.
2. Merchant sekolah A tidak boleh memotong saldo santri sekolah B.
3. Kasir tenant A tidak boleh melihat transaksi tenant B.
4. Parent tenant A tidak boleh melihat wallet anak tenant B.

---

# 5. Konsep Domain Cashless

## 5.1 Merchant

Merchant adalah unit penjual internal sekolah.

Contoh:

1. Kantin utama.
2. Koperasi sekolah.
3. Toko buku sekolah.
4. Laundry asrama.
5. Fotokopi sekolah.

Phase 19 fokus pada kantin/koperasi sederhana.

---

## 5.2 Cashier

Cashier adalah user yang boleh membuka POS dan membuat transaksi penjualan.

Cashier bisa berupa:

1. User dengan role `cashier` jika role baru dibuat.
2. User admin/finance yang diberi akses merchant.
3. User staff yang terhubung ke merchant melalui pivot table.

Cashier tidak otomatis boleh akses finance ledger umum.

---

## 5.3 Product

Product adalah barang yang dijual merchant.

Contoh:

1. Nasi ayam.
2. Air mineral.
3. Roti.
4. Buku tulis.
5. Pulpen.

Phase 19 membuat stock sederhana opsional, bukan inventory warehouse kompleks.

---

## 5.4 Wallet

Wallet adalah akun saldo santri.

Satu santri hanya boleh punya satu wallet aktif per sekolah.

Status wallet:

| Status | Makna |
|---|---|
| `active` | Bisa dipakai transaksi |
| `frozen` | Tidak bisa dipakai, masih bisa dilihat |
| `closed` | Ditutup, tidak bisa transaksi |

---

## 5.5 Wallet Transaction

Wallet transaction adalah ledger mutasi saldo.

Tipe transaksi:

| Type | Makna |
|---|---|
| `top_up` | Saldo bertambah karena top-up manual |
| `purchase` | Saldo berkurang karena pembelian |
| `refund` | Saldo bertambah karena refund |
| `adjustment_credit` | Koreksi saldo bertambah |
| `adjustment_debit` | Koreksi saldo berkurang |
| `void_purchase` | Pembatalan transaksi pembelian |
| `void_top_up` | Pembatalan top-up |

Direction:

| Direction | Makna |
|---|---|
| `credit` | Saldo bertambah |
| `debit` | Saldo berkurang |

---

## 5.6 POS Session

POS session adalah sesi kerja kasir.

Contoh:

```text
Kasir Kantin Utama — 2026-06-15 Shift Pagi
```

Session menyimpan:

1. Merchant.
2. Cashier.
3. Opening time.
4. Closing time.
5. Opening note.
6. Closing note.
7. Total sales.
8. Total refunds.
9. Status.

Status session:

| Status | Makna |
|---|---|
| `open` | Kasir boleh transaksi |
| `closed` | Tidak bisa transaksi lagi |
| `cancelled` | Sesi dibatalkan |

---

## 5.7 Sale

Sale adalah transaksi pembelian di merchant.

Sale harus menyimpan:

1. Tenant/school.
2. Merchant.
3. POS session.
4. Cashier.
5. Student.
6. Wallet.
7. Total amount.
8. Status.
9. Idempotency key.
10. Transaction number.

Status sale:

| Status | Makna |
|---|---|
| `posted` | Transaksi sukses dan saldo terpotong |
| `void` | Transaksi dibatalkan penuh |
| `partially_refunded` | Sebagian item/refund |
| `refunded` | Refund penuh |
| `failed` | Gagal sebelum saldo berubah |

---

## 5.8 Settlement

Settlement adalah rekap penjualan merchant pada periode tertentu.

Phase 19 settlement hanya laporan internal, bukan transfer uang otomatis.

Settlement menyimpan:

1. Merchant.
2. Periode.
3. Total sales.
4. Total refund.
5. Net sales.
6. Created by.
7. Approved by.
8. Status.

Status settlement:

| Status | Makna |
|---|---|
| `draft` | Masih dihitung |
| `submitted` | Diajukan |
| `approved` | Disetujui |
| `void` | Dibatalkan |

---

# 6. Target Output Phase 19

Setelah Phase 19 selesai, aplikasi harus punya:

1. Menu **Cashless**.
2. Menu **Merchant**.
3. Menu **Produk Merchant**.
4. Menu **Wallet Santri**.
5. Menu **Top Up Wallet**.
6. Menu **POS Kasir**.
7. Menu **Refund / Void**.
8. Menu **Settlement Merchant**.
9. Menu **Laporan Cashless**.
10. Portal saldo orang tua.
11. Portal saldo santri.
12. Tabel:
    - `cashless_merchants`
    - `cashless_merchant_users`
    - `cashless_products`
    - `cashless_wallets`
    - `cashless_wallet_transactions`
    - `cashless_pos_sessions`
    - `cashless_sales`
    - `cashless_sale_items`
    - `cashless_refunds`
    - `cashless_settlements`
    - `cashless_audit_logs`
13. Model:
    - `CashlessMerchant`
    - `CashlessMerchantUser`
    - `CashlessProduct`
    - `CashlessWallet`
    - `CashlessWalletTransaction`
    - `CashlessPosSession`
    - `CashlessSale`
    - `CashlessSaleItem`
    - `CashlessRefund`
    - `CashlessSettlement`
    - `CashlessAuditLog`
14. Controller:
    - `CashlessMerchantController`
    - `CashlessProductController`
    - `CashlessWalletController`
    - `CashlessTopUpController`
    - `CashlessPosSessionController`
    - `CashlessPosController`
    - `CashlessRefundController`
    - `CashlessSettlementController`
    - `CashlessReportController`
    - `ParentCashlessPortalController`
    - `StudentCashlessPortalController`
15. Request:
    - `StoreCashlessMerchantRequest`
    - `UpdateCashlessMerchantRequest`
    - `StoreCashlessProductRequest`
    - `UpdateCashlessProductRequest`
    - `StoreWalletTopUpRequest`
    - `OpenCashlessPosSessionRequest`
    - `CloseCashlessPosSessionRequest`
    - `StoreCashlessSaleRequest`
    - `StoreCashlessRefundRequest`
    - `CashlessReportFilterRequest`
16. Service:
    - `CashlessAccessService`
    - `CashlessWalletService`
    - `CashlessTopUpService`
    - `CashlessPosSessionService`
    - `CashlessSaleService`
    - `CashlessRefundService`
    - `CashlessSettlementService`
    - `CashlessReportService`
    - `CashlessAuditService`
    - `CashlessNumberGenerator`
17. Command:
    - `php artisan app:cashless-create-student-wallets`
    - `php artisan app:cashless-audit-ledger`
18. Seeder:
    - `CashlessMerchantSeeder`
19. View:
    - merchant CRUD
    - product CRUD
    - wallet index/show
    - top-up form
    - POS session index/open/close
    - POS cashier screen
    - sale receipt
    - refund form
    - settlement index/show
    - cashless report dashboard
    - parent cashless portal
    - student cashless portal
20. Dokumentasi Phase 19.
21. Update `docs/project-progress.md`.

---

# 7. Role Access Phase 19

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
finance
cashier
merchant
teacher
guru
guru_tahfidz
parent
student
```

Jika role `finance`, `cashier`, atau `merchant` belum ada, buat role tersebut melalui seeder role baru atau sesuaikan ke role yang sudah ada.

| Role | Merchant | Product | Wallet | Top Up | POS | Refund | Settlement | Report | Portal |
|---|---|---|---|---|---|---|---|---|---|
| Super Admin | CRUD | CRUD | Semua | Ya | Ya | Ya | Ya | Semua | Tidak |
| Admin Sekolah | CRUD | CRUD | Sekolahnya | Ya | Ya | Ya | Ya | Semua sekolahnya | Tidak |
| Finance | Read-only | Read-only | Sekolahnya | Ya | Tidak default | Ya terbatas | Ya | Finance report | Tidak |
| Kepala Sekolah | Read-only | Read-only | Read-only | Tidak | Tidak | Tidak | Read-only | Semua sekolahnya | Tidak |
| Cashier | Tidak | Read-only merchant | Tidak | Tidak | Ya merchant scope | Refund terbatas | Tidak | Session sendiri | Tidak |
| Merchant Owner | Merchant sendiri | CRUD merchant sendiri | Tidak | Tidak | Read-only/opsional | Tidak default | Lihat settlement | Merchant report | Tidak |
| Teacher/Guru | Tidak | Tidak | Tidak | Tidak | Tidak | Tidak | Tidak | Tidak default | Tidak |
| Parent | Tidak | Tidak | Anak sendiri | Tidak | Tidak | Tidak | Tidak | Anak sendiri | Ya |
| Student | Tidak | Tidak | Diri sendiri | Tidak | Tidak | Tidak | Tidak | Diri sendiri | Ya |

Aturan keras:

1. Parent hanya boleh melihat wallet dan transaksi anak sendiri.
2. Student hanya boleh melihat wallet dan transaksi pribadi.
3. Parent/student tidak boleh top-up dari sistem.
4. Parent/student tidak boleh melakukan refund.
5. Cashier tidak boleh top-up wallet.
6. Cashier hanya boleh transaksi di merchant yang ditugaskan.
7. Cashier tidak boleh membuka merchant tenant lain.
8. Teacher/guru tidak boleh akses cashless default.
9. Kepala sekolah read-only.
10. Semua transaksi cashless harus tenant-scoped.

---

# 8. Validasi Awal Sebelum Eksekusi

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
15. Phase 13 selesai.
16. Phase 14 selesai.
17. Phase 15 selesai.
18. Phase 16 selesai.
19. Phase 17 selesai.
20. Phase 18 selesai dan UAT aman.
21. Tabel berikut sudah ada:
    - `users`
    - `roles`
    - `schools`
    - `class_rooms`
    - `students`
    - `parent_profiles`
    - `parent_student`
    - `school_user_memberships` jika dibuat pada Phase 17
    - `school_settings`
    - `system_modules`
    - `white_label_profiles` atau tabel branding Phase 18 jika dibuat
22. Working tree bersih atau semua perubahan diketahui.

Jika Phase 18 belum aman, hentikan Phase 19.

---

# 9. Buat Branch Git Phase 19

Jalankan:

```powershell
git checkout -b phase-19-cashless-merchant-pos
```

Jika branch sudah ada:

```powershell
git checkout phase-19-cashless-merchant-pos
```

---

# 10. Struktur File yang Akan Dibuat

Agent harus membuat atau mengubah file berikut:

```text
app/
├── Console/
│   └── Commands/
│       ├── CashlessCreateStudentWalletsCommand.php
│       └── CashlessAuditLedgerCommand.php
├── Http/
│   ├── Controllers/
│   │   ├── Cashless/
│   │   │   ├── CashlessMerchantController.php
│   │   │   ├── CashlessProductController.php
│   │   │   ├── CashlessWalletController.php
│   │   │   ├── CashlessTopUpController.php
│   │   │   ├── CashlessPosSessionController.php
│   │   │   ├── CashlessPosController.php
│   │   │   ├── CashlessRefundController.php
│   │   │   ├── CashlessSettlementController.php
│   │   │   └── CashlessReportController.php
│   │   └── Portal/
│   │       ├── ParentCashlessPortalController.php
│   │       └── StudentCashlessPortalController.php
│   └── Requests/
│       └── Cashless/
│           ├── StoreCashlessMerchantRequest.php
│           ├── UpdateCashlessMerchantRequest.php
│           ├── StoreCashlessProductRequest.php
│           ├── UpdateCashlessProductRequest.php
│           ├── StoreWalletTopUpRequest.php
│           ├── OpenCashlessPosSessionRequest.php
│           ├── CloseCashlessPosSessionRequest.php
│           ├── StoreCashlessSaleRequest.php
│           ├── StoreCashlessRefundRequest.php
│           └── CashlessReportFilterRequest.php
├── Models/
│   ├── CashlessMerchant.php
│   ├── CashlessMerchantUser.php
│   ├── CashlessProduct.php
│   ├── CashlessWallet.php
│   ├── CashlessWalletTransaction.php
│   ├── CashlessPosSession.php
│   ├── CashlessSale.php
│   ├── CashlessSaleItem.php
│   ├── CashlessRefund.php
│   ├── CashlessSettlement.php
│   └── CashlessAuditLog.php
└── Services/
    └── Cashless/
        ├── CashlessAccessService.php
        ├── CashlessWalletService.php
        ├── CashlessTopUpService.php
        ├── CashlessPosSessionService.php
        ├── CashlessSaleService.php
        ├── CashlessRefundService.php
        ├── CashlessSettlementService.php
        ├── CashlessReportService.php
        ├── CashlessAuditService.php
        └── CashlessNumberGenerator.php

database/
├── migrations/
│   ├── xxxx_xx_xx_xxxxxx_create_cashless_merchants_table.php
│   ├── xxxx_xx_xx_xxxxxx_create_cashless_merchant_users_table.php
│   ├── xxxx_xx_xx_xxxxxx_create_cashless_products_table.php
│   ├── xxxx_xx_xx_xxxxxx_create_cashless_wallets_table.php
│   ├── xxxx_xx_xx_xxxxxx_create_cashless_wallet_transactions_table.php
│   ├── xxxx_xx_xx_xxxxxx_create_cashless_pos_sessions_table.php
│   ├── xxxx_xx_xx_xxxxxx_create_cashless_sales_table.php
│   ├── xxxx_xx_xx_xxxxxx_create_cashless_sale_items_table.php
│   ├── xxxx_xx_xx_xxxxxx_create_cashless_refunds_table.php
│   ├── xxxx_xx_xx_xxxxxx_create_cashless_settlements_table.php
│   └── xxxx_xx_xx_xxxxxx_create_cashless_audit_logs_table.php
└── seeders/
    └── CashlessMerchantSeeder.php

resources/
└── views/
    ├── cashless/
    │   ├── merchants/
    │   │   ├── index.blade.php
    │   │   ├── create.blade.php
    │   │   ├── edit.blade.php
    │   │   └── show.blade.php
    │   ├── products/
    │   │   ├── index.blade.php
    │   │   ├── create.blade.php
    │   │   ├── edit.blade.php
    │   │   └── show.blade.php
    │   ├── wallets/
    │   │   ├── index.blade.php
    │   │   └── show.blade.php
    │   ├── top-ups/
    │   │   └── create.blade.php
    │   ├── pos-sessions/
    │   │   ├── index.blade.php
    │   │   ├── open.blade.php
    │   │   └── show.blade.php
    │   ├── pos/
    │   │   ├── cashier.blade.php
    │   │   └── receipt.blade.php
    │   ├── refunds/
    │   │   └── create.blade.php
    │   ├── settlements/
    │   │   ├── index.blade.php
    │   │   └── show.blade.php
    │   └── reports/
    │       └── dashboard.blade.php
    └── portal/
        ├── parent/
        │   └── cashless.blade.php
        └── student/
            └── cashless.blade.php

routes/
└── web.php

docs/
├── phase-19-execution.md
└── phase-19-cashless-merchant-pos.md
```

---

# 11. Buat Model, Migration, Seeder, Controller, Request, Command

Jalankan:

```powershell
php artisan make:model CashlessMerchant -m
php artisan make:model CashlessMerchantUser -m
php artisan make:model CashlessProduct -m
php artisan make:model CashlessWallet -m
php artisan make:model CashlessWalletTransaction -m
php artisan make:model CashlessPosSession -m
php artisan make:model CashlessSale -m
php artisan make:model CashlessSaleItem -m
php artisan make:model CashlessRefund -m
php artisan make:model CashlessSettlement -m
php artisan make:model CashlessAuditLog -m

php artisan make:seeder CashlessMerchantSeeder

php artisan make:controller Cashless/CashlessMerchantController --resource
php artisan make:controller Cashless/CashlessProductController --resource
php artisan make:controller Cashless/CashlessWalletController
php artisan make:controller Cashless/CashlessTopUpController
php artisan make:controller Cashless/CashlessPosSessionController
php artisan make:controller Cashless/CashlessPosController
php artisan make:controller Cashless/CashlessRefundController
php artisan make:controller Cashless/CashlessSettlementController
php artisan make:controller Cashless/CashlessReportController
php artisan make:controller Portal/ParentCashlessPortalController
php artisan make:controller Portal/StudentCashlessPortalController

php artisan make:request Cashless/StoreCashlessMerchantRequest
php artisan make:request Cashless/UpdateCashlessMerchantRequest
php artisan make:request Cashless/StoreCashlessProductRequest
php artisan make:request Cashless/UpdateCashlessProductRequest
php artisan make:request Cashless/StoreWalletTopUpRequest
php artisan make:request Cashless/OpenCashlessPosSessionRequest
php artisan make:request Cashless/CloseCashlessPosSessionRequest
php artisan make:request Cashless/StoreCashlessSaleRequest
php artisan make:request Cashless/StoreCashlessRefundRequest
php artisan make:request Cashless/CashlessReportFilterRequest

php artisan make:command CashlessCreateStudentWalletsCommand
php artisan make:command CashlessAuditLedgerCommand
```

Buat folder service:

```powershell
mkdir app\Services\Cashless
```

Buat file service:

```powershell
New-Item app\Services\Cashless\CashlessAccessService.php
New-Item app\Services\Cashless\CashlessWalletService.php
New-Item app\Services\Cashless\CashlessTopUpService.php
New-Item app\Services\Cashless\CashlessPosSessionService.php
New-Item app\Services\Cashless\CashlessSaleService.php
New-Item app\Services\Cashless\CashlessRefundService.php
New-Item app\Services\Cashless\CashlessSettlementService.php
New-Item app\Services\Cashless\CashlessReportService.php
New-Item app\Services\Cashless\CashlessAuditService.php
New-Item app\Services\Cashless\CashlessNumberGenerator.php
```

---

# 12. Migration Design

## 12.1 `cashless_merchants`

Field wajib:

```php
$table->id();
$table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
$table->string('name');
$table->string('code');
$table->string('type')->default('canteen');
$table->string('status')->default('active');
$table->string('phone')->nullable();
$table->text('description')->nullable();
$table->timestamps();
$table->softDeletes();

$table->unique(['school_id', 'code']);
$table->index(['school_id', 'status']);
```

Type awal:

```text
canteen
cooperative
bookstore
laundry
other
```

---

## 12.2 `cashless_merchant_users`

Field wajib:

```php
$table->id();
$table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
$table->foreignId('cashless_merchant_id')->constrained('cashless_merchants')->cascadeOnDelete();
$table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
$table->string('role')->default('cashier');
$table->boolean('is_active')->default(true);
$table->timestamps();
$table->softDeletes();

$table->unique(['cashless_merchant_id', 'user_id']);
$table->index(['school_id', 'user_id']);
```

Role merchant user:

```text
owner
manager
cashier
viewer
```

---

## 12.3 `cashless_products`

Field wajib:

```php
$table->id();
$table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
$table->foreignId('cashless_merchant_id')->constrained('cashless_merchants')->cascadeOnDelete();
$table->string('name');
$table->string('sku')->nullable();
$table->unsignedBigInteger('price');
$table->unsignedInteger('stock')->nullable();
$table->boolean('track_stock')->default(false);
$table->string('status')->default('active');
$table->text('description')->nullable();
$table->timestamps();
$table->softDeletes();

$table->unique(['cashless_merchant_id', 'sku']);
$table->index(['school_id', 'cashless_merchant_id', 'status']);
```

Catatan:

1. Gunakan integer rupiah, bukan decimal floating point.
2. Harga Rp 10.000 disimpan sebagai `10000`.
3. Stock boleh nullable jika tidak dilacak.

---

## 12.4 `cashless_wallets`

Field wajib:

```php
$table->id();
$table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
$table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
$table->string('wallet_number')->unique();
$table->unsignedBigInteger('balance')->default(0);
$table->unsignedBigInteger('daily_spending_limit')->nullable();
$table->string('status')->default('active');
$table->timestamp('frozen_at')->nullable();
$table->foreignId('frozen_by')->nullable()->constrained('users')->nullOnDelete();
$table->text('freeze_reason')->nullable();
$table->timestamps();
$table->softDeletes();

$table->unique(['school_id', 'student_id']);
$table->index(['school_id', 'status']);
```

---

## 12.5 `cashless_wallet_transactions`

Field wajib:

```php
$table->id();
$table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
$table->foreignId('cashless_wallet_id')->constrained('cashless_wallets')->cascadeOnDelete();
$table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
$table->string('transaction_number')->unique();
$table->string('type');
$table->string('direction');
$table->unsignedBigInteger('amount');
$table->unsignedBigInteger('balance_before');
$table->unsignedBigInteger('balance_after');
$table->nullableMorphs('reference');
$table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
$table->string('status')->default('posted');
$table->text('note')->nullable();
$table->timestamp('posted_at')->nullable();
$table->timestamps();

$table->index(['school_id', 'student_id']);
$table->index(['cashless_wallet_id', 'created_at']);
$table->index(['type', 'direction', 'status']);
```

Jangan pakai soft delete pada ledger transaksi.

---

## 12.6 `cashless_pos_sessions`

Field wajib:

```php
$table->id();
$table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
$table->foreignId('cashless_merchant_id')->constrained('cashless_merchants')->cascadeOnDelete();
$table->foreignId('cashier_user_id')->constrained('users')->cascadeOnDelete();
$table->string('session_number')->unique();
$table->timestamp('opened_at');
$table->timestamp('closed_at')->nullable();
$table->unsignedBigInteger('opening_cash_note')->nullable();
$table->unsignedBigInteger('total_sales')->default(0);
$table->unsignedBigInteger('total_refunds')->default(0);
$table->unsignedBigInteger('net_sales')->default(0);
$table->string('status')->default('open');
$table->text('opening_note')->nullable();
$table->text('closing_note')->nullable();
$table->timestamps();

$table->index(['school_id', 'cashless_merchant_id', 'status']);
$table->index(['cashier_user_id', 'opened_at']);
```

---

## 12.7 `cashless_sales`

Field wajib:

```php
$table->id();
$table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
$table->foreignId('cashless_merchant_id')->constrained('cashless_merchants')->cascadeOnDelete();
$table->foreignId('cashless_pos_session_id')->constrained('cashless_pos_sessions')->cascadeOnDelete();
$table->foreignId('cashless_wallet_id')->constrained('cashless_wallets')->cascadeOnDelete();
$table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
$table->foreignId('cashier_user_id')->constrained('users')->cascadeOnDelete();
$table->string('sale_number')->unique();
$table->string('idempotency_key');
$table->unsignedBigInteger('subtotal_amount');
$table->unsignedBigInteger('discount_amount')->default(0);
$table->unsignedBigInteger('total_amount');
$table->unsignedBigInteger('refunded_amount')->default(0);
$table->string('status')->default('posted');
$table->text('note')->nullable();
$table->timestamp('posted_at')->nullable();
$table->timestamps();

$table->unique(['school_id', 'idempotency_key']);
$table->index(['school_id', 'student_id']);
$table->index(['cashless_merchant_id', 'created_at']);
$table->index(['cashless_pos_session_id', 'status']);
```

---

## 12.8 `cashless_sale_items`

Field wajib:

```php
$table->id();
$table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
$table->foreignId('cashless_sale_id')->constrained('cashless_sales')->cascadeOnDelete();
$table->foreignId('cashless_product_id')->nullable()->constrained('cashless_products')->nullOnDelete();
$table->string('product_name_snapshot');
$table->string('sku_snapshot')->nullable();
$table->unsignedInteger('quantity');
$table->unsignedBigInteger('unit_price');
$table->unsignedBigInteger('line_total');
$table->timestamps();

$table->index(['school_id', 'cashless_sale_id']);
```

Snapshot wajib agar riwayat transaksi tidak berubah ketika nama/harga produk diedit.

---

## 12.9 `cashless_refunds`

Field wajib:

```php
$table->id();
$table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
$table->foreignId('cashless_sale_id')->constrained('cashless_sales')->cascadeOnDelete();
$table->foreignId('cashless_wallet_id')->constrained('cashless_wallets')->cascadeOnDelete();
$table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
$table->foreignId('refunded_by')->constrained('users')->cascadeOnDelete();
$table->string('refund_number')->unique();
$table->unsignedBigInteger('amount');
$table->string('status')->default('posted');
$table->text('reason');
$table->timestamp('posted_at')->nullable();
$table->timestamps();

$table->index(['school_id', 'student_id']);
$table->index(['cashless_sale_id', 'status']);
```

---

## 12.10 `cashless_settlements`

Field wajib:

```php
$table->id();
$table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
$table->foreignId('cashless_merchant_id')->constrained('cashless_merchants')->cascadeOnDelete();
$table->date('period_start');
$table->date('period_end');
$table->unsignedBigInteger('total_sales')->default(0);
$table->unsignedBigInteger('total_refunds')->default(0);
$table->unsignedBigInteger('net_sales')->default(0);
$table->string('status')->default('draft');
$table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
$table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
$table->timestamp('approved_at')->nullable();
$table->text('note')->nullable();
$table->timestamps();

$table->index(['school_id', 'cashless_merchant_id']);
$table->index(['period_start', 'period_end', 'status']);
```

---

## 12.11 `cashless_audit_logs`

Field wajib:

```php
$table->id();
$table->foreignId('school_id')->nullable()->constrained('schools')->nullOnDelete();
$table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
$table->string('action');
$table->nullableMorphs('auditable');
$table->json('metadata')->nullable();
$table->string('ip_address')->nullable();
$table->text('user_agent')->nullable();
$table->timestamps();

$table->index(['school_id', 'action']);
$table->index(['user_id', 'created_at']);
```

---

# 13. Service Rules

## 13.1 `CashlessWalletService`

Wajib menyediakan method:

```php
createWalletForStudent(Student $student): CashlessWallet
getWalletForStudent(Student $student): ?CashlessWallet
freezeWallet(CashlessWallet $wallet, User $actor, string $reason): void
unfreezeWallet(CashlessWallet $wallet, User $actor): void
assertWalletCanTransact(CashlessWallet $wallet): void
```

Rule:

1. Satu santri satu wallet aktif per sekolah.
2. Wallet frozen tidak boleh transaksi.
3. Wallet closed tidak boleh transaksi.
4. Semua perubahan status masuk audit log.

---

## 13.2 `CashlessTopUpService`

Wajib menyediakan method:

```php
topUp(CashlessWallet $wallet, int $amount, User $actor, ?string $note = null): CashlessWalletTransaction
voidTopUp(CashlessWalletTransaction $transaction, User $actor, string $reason): CashlessWalletTransaction
```

Rule:

1. Top-up hanya boleh oleh admin/finance.
2. Amount wajib > 0.
3. Gunakan `DB::transaction()`.
4. Lock wallet dengan `lockForUpdate()`.
5. Ledger harus mencatat balance before/after.
6. Void top-up membuat transaksi debit pembalik.
7. Top-up yang sudah dipakai boleh di-void hanya jika rules audit mengizinkan dan saldo cukup untuk pembalik.

---

## 13.3 `CashlessSaleService`

Wajib menyediakan method:

```php
createSale(array $payload, User $cashier): CashlessSale
```

Rule:

1. Cashier harus punya akses merchant.
2. POS session harus `open`.
3. Student harus berada pada school/tenant yang sama.
4. Wallet harus aktif.
5. Saldo harus cukup.
6. Idempotency key wajib.
7. Jika idempotency key sudah ada, return sale existing.
8. Gunakan `DB::transaction()`.
9. Lock wallet.
10. Buat sale.
11. Buat sale items dengan snapshot produk.
12. Kurangi stock jika `track_stock = true`.
13. Buat wallet transaction type `purchase` direction `debit`.
14. Update wallet balance.
15. Update total session.
16. Buat audit log.

---

## 13.4 `CashlessRefundService`

Wajib menyediakan method:

```php
refundSale(CashlessSale $sale, int $amount, User $actor, string $reason): CashlessRefund
voidSale(CashlessSale $sale, User $actor, string $reason): CashlessRefund
```

Rule:

1. Refund amount tidak boleh melebihi `total_amount - refunded_amount`.
2. Refund harus membuat wallet transaction credit.
3. Refund harus mengubah status sale.
4. Refund harus masuk audit log.
5. Refund hanya bisa dilakukan oleh admin/finance atau cashier dengan permission terbatas.
6. Refund harus memakai database transaction.

---

## 13.5 `CashlessSettlementService`

Wajib menyediakan method:

```php
createSettlement(CashlessMerchant $merchant, CarbonInterface $start, CarbonInterface $end, User $actor): CashlessSettlement
approveSettlement(CashlessSettlement $settlement, User $actor): void
voidSettlement(CashlessSettlement $settlement, User $actor, string $reason): void
```

Rule:

1. Settlement hanya rekap internal.
2. Settlement tidak mengubah wallet santri.
3. Settlement mengambil sale posted/refunded dalam periode.
4. Settlement harus tenant-scoped.
5. Settlement yang approved tidak boleh diedit langsung.

---

## 13.6 `CashlessAuditService`

Semua action berikut wajib masuk audit:

1. Merchant created/updated.
2. Product created/updated.
3. Wallet created/frozen/unfrozen.
4. Top-up posted/voided.
5. Sale posted.
6. Sale refunded/voided.
7. POS session opened/closed.
8. Settlement created/approved/voided.
9. Failed transaction karena saldo kurang.
10. Unauthorized cashless access attempt jika mudah ditangkap.

---

# 14. Form Request Rules

## 14.1 Merchant Request

Validasi minimal:

```php
'name' => ['required', 'string', 'max:150'],
'code' => ['required', 'string', 'max:50'],
'type' => ['required', 'in:canteen,cooperative,bookstore,laundry,other'],
'status' => ['required', 'in:active,inactive'],
'phone' => ['nullable', 'string', 'max:30'],
'description' => ['nullable', 'string', 'max:1000'],
```

---

## 14.2 Product Request

Validasi minimal:

```php
'cashless_merchant_id' => ['required', 'exists:cashless_merchants,id'],
'name' => ['required', 'string', 'max:150'],
'sku' => ['nullable', 'string', 'max:80'],
'price' => ['required', 'integer', 'min:1'],
'stock' => ['nullable', 'integer', 'min:0'],
'track_stock' => ['nullable', 'boolean'],
'status' => ['required', 'in:active,inactive'],
'description' => ['nullable', 'string', 'max:1000'],
```

---

## 14.3 Top-Up Request

Validasi minimal:

```php
'cashless_wallet_id' => ['required', 'exists:cashless_wallets,id'],
'amount' => ['required', 'integer', 'min:1000'],
'note' => ['nullable', 'string', 'max:1000'],
```

---

## 14.4 Sale Request

Validasi minimal:

```php
'cashless_pos_session_id' => ['required', 'exists:cashless_pos_sessions,id'],
'student_id' => ['required', 'exists:students,id'],
'idempotency_key' => ['required', 'string', 'max:100'],
'items' => ['required', 'array', 'min:1'],
'items.*.cashless_product_id' => ['required', 'exists:cashless_products,id'],
'items.*.quantity' => ['required', 'integer', 'min:1'],
'note' => ['nullable', 'string', 'max:1000'],
```

---

## 14.5 Refund Request

Validasi minimal:

```php
'cashless_sale_id' => ['required', 'exists:cashless_sales,id'],
'amount' => ['required', 'integer', 'min:1'],
'reason' => ['required', 'string', 'min:5', 'max:1000'],
```

---

# 15. Routes

Tambahkan route dengan middleware auth, role, tenant context jika tersedia.

Nama route wajib:

```text
cashless.merchants.index
cashless.merchants.create
cashless.merchants.store
cashless.merchants.show
cashless.merchants.edit
cashless.merchants.update

cashless.products.index
cashless.products.create
cashless.products.store
cashless.products.show
cashless.products.edit
cashless.products.update

cashless.wallets.index
cashless.wallets.show
cashless.wallets.freeze
cashless.wallets.unfreeze

cashless.top-ups.create
cashless.top-ups.store
cashless.top-ups.void

cashless.pos-sessions.index
cashless.pos-sessions.open
cashless.pos-sessions.store
cashless.pos-sessions.show
cashless.pos-sessions.close

cashless.pos.cashier
cashless.pos.sales.store
cashless.pos.receipt

cashless.refunds.create
cashless.refunds.store

cashless.settlements.index
cashless.settlements.store
cashless.settlements.show
cashless.settlements.approve
cashless.settlements.void

cashless.reports.dashboard
cashless.reports.wallet-transactions
cashless.reports.merchant-sales

portal.parent.cashless
portal.student.cashless
```

---

# 16. POS Cashier UX Minimal

Halaman POS kasir wajib punya:

1. Pilih session aktif.
2. Search santri berdasarkan nama/NIS.
3. Tampilkan saldo santri setelah dipilih.
4. Search produk.
5. Tambah produk ke cart.
6. Ubah quantity.
7. Hapus item dari cart sebelum submit.
8. Tampilkan subtotal.
9. Tombol submit transaksi.
10. Hidden `idempotency_key` generated per cart.
11. Setelah sukses, tampilkan receipt.
12. Jika saldo kurang, tampilkan error jelas.
13. Jika session closed, blokir transaksi.
14. Jika wallet frozen, blokir transaksi.

Jangan membuat POS terlalu kompleks pada Phase 19.

---

# 17. Portal Parent dan Student

## 17.1 Parent Cashless Portal

Parent hanya boleh melihat:

1. Saldo wallet anak sendiri.
2. Riwayat top-up anak.
3. Riwayat pembelian anak.
4. Riwayat refund anak.
5. Filter tanggal.
6. Total belanja bulan ini.

Parent tidak boleh:

1. Top-up dari sistem.
2. Transfer saldo.
3. Refund transaksi.
4. Melihat transaksi anak lain.
5. Melihat laporan merchant.

---

## 17.2 Student Cashless Portal

Student hanya boleh melihat:

1. Saldo pribadi.
2. Riwayat transaksi pribadi.
3. Total belanja bulan ini.
4. Status wallet pribadi.

Student tidak boleh:

1. Top-up.
2. Refund.
3. Transfer saldo.
4. Melihat transaksi santri lain.
5. Mengakses POS.

---

# 18. Report Phase 19

Minimal laporan:

1. Saldo wallet per santri.
2. Mutasi wallet per santri.
3. Top-up harian.
4. Penjualan merchant harian.
5. Produk terlaris.
6. Refund harian.
7. Settlement merchant.
8. Selisih ledger audit.

Filter minimal:

1. Tanggal mulai.
2. Tanggal akhir.
3. School/tenant.
4. Merchant.
5. Student.
6. Cashier.
7. Transaction type.
8. Status.

---

# 19. Command `app:cashless-create-student-wallets`

Command ini membuat wallet untuk santri yang belum punya wallet.

Signature:

```php
protected $signature = 'app:cashless-create-student-wallets {--school_id=}';
```

Rule:

1. Jika `school_id` diisi, proses sekolah tersebut saja.
2. Jika kosong, proses semua sekolah yang aktif.
3. Jangan overwrite wallet yang sudah ada.
4. Wallet number harus unik.
5. Balance awal 0.
6. Status awal `active`.

Output command:

```text
Created wallets: X
Skipped existing wallets: Y
Failed: Z
```

---

# 20. Command `app:cashless-audit-ledger`

Command ini mengecek konsistensi wallet dan ledger.

Signature:

```php
protected $signature = 'app:cashless-audit-ledger {--school_id=} {--fix=no}';
```

Audit wajib mengecek:

1. Balance wallet = total credit - total debit.
2. Tidak ada wallet transaction amount 0.
3. Tidak ada balance_after negatif.
4. Tidak ada sale posted tanpa wallet transaction debit.
5. Tidak ada refund posted tanpa wallet transaction credit.
6. Tidak ada sale/refund lintas school_id.
7. Tidak ada wallet milik student school berbeda.

Default `--fix=no` hanya report, jangan mengubah data.

---

# 21. Seeder

Seeder `CashlessMerchantSeeder` minimal membuat:

1. Kantin Utama.
2. Koperasi Sekolah.

Seeder produk contoh:

1. Air Mineral.
2. Nasi Ayam.
3. Roti.
4. Buku Tulis.
5. Pulpen.

Catatan:

Seeder harus tenant-aware. Jika ada banyak sekolah, buat sample hanya untuk sekolah demo/local.

---

# 22. Update Navigation

Tambahkan menu internal:

```text
Cashless
├── Merchant
├── Produk
├── Wallet Santri
├── Top Up
├── POS Kasir
├── Session POS
├── Settlement
└── Laporan Cashless
```

Tampilkan menu berdasarkan role:

1. Admin/finance melihat menu manajemen.
2. Cashier melihat POS dan session.
3. Kepala sekolah melihat laporan read-only.
4. Parent/student hanya melihat portal.

---

# 23. Update Module Registry

Update `system_modules`:

```text
module_key: cashless
label: Cashless Kantin / Merchant POS
status: active
route_name: cashless.reports.dashboard
sort_order: 100
```

Jika Phase 18 memiliki tenant module configuration, aktifkan cashless per sekolah secara eksplisit.

Default:

```text
cashless module disabled untuk sekolah baru sampai admin mengaktifkan.
```

---

# 24. Security Rules

Wajib:

1. Semua route cashless memakai `auth`.
2. Semua route cashless memakai tenant/school context.
3. Semua query cashless filter `school_id`.
4. Semua write action memakai Form Request authorization.
5. Semua service mengecek access ulang, jangan hanya percaya route middleware.
6. Semua transaksi saldo memakai `DB::transaction()`.
7. Semua wallet update memakai row lock.
8. Semua amount integer rupiah.
9. Semua transaction number unique.
10. Semua receipt number tidak menampilkan data sensitif berlebihan.
11. Semua parent/student endpoint ownership-based.

Dilarang:

1. Menampilkan raw wallet ID sebagai credential transaksi.
2. Menggunakan student ID saja untuk charge tanpa validasi tenant.
3. Membiarkan cashier input amount bebas tanpa produk kecuali fitur adjustment khusus admin.
4. Membiarkan cashier top-up saldo.
5. Membiarkan refund tanpa reason.
6. Menghapus ledger transaksi.

---

# 25. Testing Manual

## 25.1 Merchant dan Product

Tes:

1. Admin membuat merchant Kantin Utama.
2. Admin menambah kasir ke merchant.
3. Admin membuat produk Air Mineral Rp 5.000.
4. Admin membuat produk Nasi Ayam Rp 15.000.
5. Kasir hanya melihat merchant yang ditugaskan.
6. Kasir tidak bisa melihat merchant lain.

---

## 25.2 Wallet

Tes:

1. Jalankan command create wallet.
2. Wallet dibuat untuk semua santri aktif.
3. Wallet number unik.
4. Balance awal 0.
5. Parent melihat wallet anak sendiri.
6. Parent tidak bisa melihat wallet anak lain.
7. Student melihat wallet sendiri.
8. Student tidak bisa melihat wallet santri lain.

---

## 25.3 Top-Up

Tes:

1. Admin top-up Rp 50.000 ke wallet santri.
2. Balance berubah dari 0 ke 50.000.
3. Wallet transaction credit dibuat.
4. Audit log dibuat.
5. Cashier tidak bisa top-up.
6. Parent tidak bisa top-up.
7. Student tidak bisa top-up.

---

## 25.4 POS Sale

Tes:

1. Kasir membuka session.
2. Kasir memilih santri dengan saldo Rp 50.000.
3. Kasir memilih produk Rp 20.000.
4. Submit sale sukses.
5. Balance menjadi Rp 30.000.
6. Sale posted dibuat.
7. Sale items dibuat.
8. Wallet transaction debit dibuat.
9. Receipt tampil.
10. Submit ulang idempotency key yang sama tidak membuat sale baru.

---

## 25.5 Saldo Kurang

Tes:

1. Santri saldo Rp 5.000.
2. Kasir mencoba transaksi Rp 20.000.
3. Sistem menolak transaksi.
4. Wallet balance tidak berubah.
5. Sale posted tidak dibuat.
6. Audit failed transaction dibuat jika service mendukung.

---

## 25.6 Refund

Tes:

1. Admin/refund-authorized user refund transaksi Rp 20.000.
2. Wallet bertambah kembali.
3. Refund record dibuat.
4. Wallet transaction credit dibuat.
5. Sale status berubah.
6. Refund kedua tidak boleh melebihi sisa refundable amount.
7. Refund tanpa reason ditolak.

---

## 25.7 Settlement

Tes:

1. Admin membuat settlement merchant untuk tanggal tertentu.
2. Total sales benar.
3. Total refund benar.
4. Net sales benar.
5. Kepala sekolah melihat read-only.
6. Merchant owner melihat merchant sendiri.
7. Merchant owner tidak melihat merchant lain.

---

## 25.8 Tenant Isolation

Tes wajib:

1. Buat merchant di sekolah A dan B.
2. Kasir sekolah A tidak bisa membuka merchant sekolah B.
3. Santri sekolah A tidak bisa dipakai transaksi di merchant sekolah B.
4. Parent sekolah A tidak bisa melihat wallet santri sekolah B.
5. Report cashless sekolah A tidak memuat data sekolah B.

---

# 26. Validation Commands

Jalankan:

```powershell
php artisan migrate
php artisan db:seed --class=CashlessMerchantSeeder
php artisan app:cashless-create-student-wallets
php artisan app:cashless-audit-ledger
php artisan route:list
php artisan app:system-health-check
npm run build
```

Jika memakai test suite:

```powershell
php artisan test
```

Minimal cek route:

```powershell
php artisan route:list | findstr /i cashless
php artisan route:list | findstr /i portal
```

---

# 27. Definition of Done Phase 19

Phase 19 dianggap selesai jika semua poin berikut terpenuhi:

1. Merchant bisa dibuat dan dikelola admin.
2. Produk merchant bisa dibuat dan dikelola.
3. Kasir bisa ditugaskan ke merchant.
4. Wallet santri bisa dibuat otomatis.
5. Wallet santri tenant-scoped.
6. Top-up manual berjalan.
7. Top-up membuat wallet transaction credit.
8. POS session bisa dibuka dan ditutup.
9. Kasir hanya bisa transaksi pada merchant scope-nya.
10. Sale posted memotong saldo wallet.
11. Sale item snapshot tersimpan.
12. Saldo kurang ditolak tanpa mengubah saldo.
13. Idempotency key mencegah transaksi dobel.
14. Refund berjalan dan membuat wallet transaction credit.
15. Refund tidak bisa melebihi nilai transaksi.
16. Settlement merchant bisa dibuat.
17. Laporan merchant tampil.
18. Laporan wallet santri tampil.
19. Parent melihat saldo dan transaksi anak sendiri.
20. Student melihat saldo dan transaksi pribadi.
21. Parent/student tidak bisa akses POS/top-up/refund.
22. Kepala sekolah read-only.
23. Teacher tidak punya akses cashless default.
24. Semua query cashless tenant-scoped.
25. Audit log transaksi penting dibuat.
26. Command audit ledger berjalan.
27. `php artisan app:system-health-check` berhasil.
28. `npm run build` berhasil.
29. Tidak ada bug P0/P1 terbuka.
30. `docs/project-progress.md` diperbarui.

---

# 28. Update Dokumentasi Progress

Update:

```text
docs/project-progress.md
```

Tambahkan:

```md
## Phase 19 — Cashless Kantin / Merchant POS

Status: Done

Output:

- Merchant management.
- Merchant cashier assignment.
- Product management.
- Student wallet.
- Manual top-up.
- Wallet ledger transaction.
- POS cashier screen.
- Sale posting.
- Refund / void flow.
- Merchant settlement.
- Cashless reports.
- Parent cashless portal.
- Student cashless portal.
- Cashless audit command.

Risk notes:

- Cashless is high-risk because it manages balance, purchase, refund, audit, and merchant reporting.
- No payment gateway, QRIS automation, virtual account, bank callback, or native POS app is included in Phase 19.
- All balance mutation must go through ledger services.
```

Update roadmap table:

```md
| Phase | Name | Status |
|---:|---|---|
| 16 | Boarding School Management System | Done |
| 17 | Multi-Tenant Foundation | Done |
| 18 | White-Label School App Builder | Done |
| 19 | Cashless Kantin / Merchant POS | Done |
```

---

# 29. Commit

Jika semua validasi berhasil:

```powershell
git status
git add .
git commit -m "feat: add cashless merchant pos"
```

---

# 30. Laporan Akhir dari AI Agent

Setelah selesai, agent wajib memberi laporan:

```md
# Phase 19 Completion Report

## Summary
Phase 19 Cashless Kantin / Merchant POS completed.

## Files Created
- List models
- List migrations
- List controllers
- List requests
- List services
- List views
- List commands
- List docs updated

## Validation
- php artisan migrate: success/fail
- php artisan db:seed --class=CashlessMerchantSeeder: success/fail
- php artisan app:cashless-create-student-wallets: success/fail
- php artisan app:cashless-audit-ledger: success/fail
- php artisan route:list: success/fail
- php artisan app:system-health-check: success/fail
- npm run build: success/fail

## Manual Test Result
- Merchant: pass/fail
- Product: pass/fail
- Wallet: pass/fail
- Top-up: pass/fail
- POS sale: pass/fail
- Idempotency: pass/fail
- Refund: pass/fail
- Settlement: pass/fail
- Parent portal: pass/fail
- Student portal: pass/fail
- Tenant isolation: pass/fail

## Known Issues
- List remaining issues, if any.

## Next Recommendation
Do not add a new business module immediately. Run a cashless financial audit, UAT with small real data, cashier training, and reconciliation checklist first.
```

---

# 31. Keputusan Setelah Phase 19

Setelah Phase 19 selesai, jangan langsung menambah modul baru.

Urutan yang benar:

```text
Phase 19 selesai
→ cashless UAT terbatas
→ audit ledger
→ test saldo real kecil
→ training kasir/admin finance
→ rekonsiliasi harian
→ security review
→ bug fix P0/P1
→ baru pikirkan scale/productization berikutnya
```

Phase setelah ini bukan menambah fitur besar secara agresif. Rekomendasi berikutnya adalah:

```text
Phase 20 — Platform Audit, Compliance, and Scale Readiness
```

Fokus Phase 20 sebaiknya:

1. Audit data dan tenant isolation.
2. Audit cashless ledger.
3. Monitoring error.
4. Performance review.
5. Backup/restore drill.
6. SOP admin/kasir/finance.
7. Dokumentasi onboarding sekolah.
8. Pricing/package planning.
9. Support workflow.
10. Release readiness.

Jangan masuk payment gateway, native mobile, atau integrasi bank sebelum cashless closed-loop terbukti aman.
