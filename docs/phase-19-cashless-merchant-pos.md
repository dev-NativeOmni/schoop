# Phase 19 - Cashless Kantin / Merchant POS

Status: Done

Tanggal eksekusi: 2026-06-15

## Ringkasan

Phase 19 menambahkan closed-loop cashless wallet untuk transaksi kantin atau merchant internal sekolah. Implementasi memakai prinsip ledger-first: saldo wallet hanya berubah melalui service transaksi yang membuat ledger, memakai database transaction, row lock, actor, nomor transaksi unik, dan audit log.

## Output Utama

- Menu Cashless untuk admin, finance, cashier, merchant, dan kepala sekolah.
- Merchant management dan assignment kasir.
- Product management merchant dengan harga, status, dan stock sederhana.
- Wallet santri tenant-scoped.
- Command pembuatan wallet otomatis.
- Manual top-up oleh admin atau finance.
- POS session open/close.
- POS cashier screen dengan idempotency key.
- Sale posting yang memotong wallet dan membuat ledger debit.
- Refund/void yang mengembalikan saldo dan membuat ledger credit.
- Settlement merchant internal.
- Dashboard dan laporan cashless.
- Portal cashless parent dan student.
- Audit ledger command.

## Tabel

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

## Service Transaksi

- `CashlessWalletService`
- `CashlessTopUpService`
- `CashlessPosSessionService`
- `CashlessSaleService`
- `CashlessRefundService`
- `CashlessSettlementService`
- `CashlessAuditService`
- `CashlessReportService`
- `CashlessAccessService`
- `CashlessNumberGenerator`

## Command

- `php artisan app:cashless-create-student-wallets`
- `php artisan app:cashless-audit-ledger`

## Route Penting

- `cashless.reports.dashboard`
- `cashless.merchants.index`
- `cashless.products.index`
- `cashless.wallets.index`
- `cashless.top-ups.create`
- `cashless.pos-sessions.index`
- `cashless.pos.cashier`
- `cashless.pos.receipt`
- `cashless.refunds.create`
- `cashless.settlements.index`
- `portal.parent.cashless`
- `portal.student.cashless`

## Guardrail Keamanan

- Semua route internal cashless berada di dalam auth, tenant resolve, dan tenant access.
- Semua query utama memakai `school_id`.
- Parent hanya melihat wallet anak yang terhubung ke parent profile miliknya.
- Student hanya melihat wallet student profile miliknya.
- Cashier tidak bisa top-up.
- Saldo wallet tidak diubah langsung dari controller.
- Top-up, sale, dan refund memakai `DB::transaction()` dan `lockForUpdate()`.
- Sale memakai unique `idempotency_key` per sekolah.
- Refund tidak bisa melebihi sisa refundable amount.
- Transaksi tidak dihapus; koreksi memakai ledger pembalik atau refund.

## Verifikasi

- `php artisan migrate`: success
- `php artisan db:seed --class=CashlessMerchantSeeder`: success
- `php artisan app:cashless-create-student-wallets`: success, created wallets: 3
- `php artisan app:cashless-audit-ledger`: success, OK
- `php artisan route:list | findstr /i cashless`: success
- `php artisan view:cache`: success
- `php artisan test`: success, 2 passed
- `npm run build`: success
- `php artisan app:system-health-check`: success

## Smoke Test

Service-level smoke test berhasil:

- Top-up wallet Rp 50.000.
- Open POS session.
- Post sale dengan satu produk.
- Submit ulang payload idempotency key yang sama mengembalikan sale yang sama.
- Refund penuh sale.
- Close POS session.
- Void top-up smoke test melalui ledger pembalik agar saldo demo kembali netral.
- Audit ledger setelah smoke test tetap OK.

## Batasan

- Tidak ada payment gateway, QRIS otomatis, virtual account, bank callback, NFC/RFID, fingerprint, face recognition, atau aplikasi native POS.
- Settlement adalah rekap internal, bukan transfer merchant otomatis.
- POS UI dibuat minimal untuk Phase 19 dan perlu UAT kasir sebelum dipakai dengan saldo nyata.
