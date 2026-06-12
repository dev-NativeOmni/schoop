# Phase 14 — Student Finance Ledger

## Status

Phase 14 adds the **Student Finance Ledger** module to the HafizPlus School Platform.

## Scope

This module covers:

1. Cost categories (kategori biaya).
2. Cost items (item biaya).
3. Student bills (tagihan santri).
4. Bill line items.
5. Manual payment entry.
6. Allocation of payments to bills.
7. Student debit/credit ledger.
8. Student overall finance balances.
9. Financial reports dashboard.
10. Parent finance portal.
11. Student finance portal.
12. Role-based access rules.
13. Ownership-based access rules.
14. Voiding transactions with audit logs.

## New Tables

1. `finance_fee_categories`
2. `finance_fee_items`
3. `student_bills`
4. `student_bill_items`
5. `student_payments`
6. `student_payment_allocations`
7. `finance_ledger_entries`

## New Models

1. `FinanceFeeCategory`
2. `FinanceFeeItem`
3. `StudentBill`
4. `StudentBillItem`
5. `StudentPayment`
6. `StudentPaymentAllocation`
7. `FinanceLedgerEntry`

## New Controllers

1. `FinanceFeeCategoryController`
2. `FinanceFeeItemController`
3. `StudentBillController`
4. `StudentPaymentController`
5. `FinanceLedgerController`
6. `FinanceReportController`
7. `ParentFinancePortalController`
8. `StudentFinancePortalController`

## New Services

1. `FinanceAccessService`
2. `InvoiceNumberGenerator`
3. `ReceiptNumberGenerator`
4. `StudentBillService`
5. `StudentPaymentService`
6. `StudentBalanceService`
7. `FinanceReportService`

## New Seeders

1. `FinanceFeeCategorySeeder`

## New Routes

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

| Role | Access |
| --- | --- |
| Super Admin | Full access to CRUD fee categories, fee items, bills, payments, reports, ledgers, and void actions. |
| Admin | Full access to CRUD fee categories, fee items, bills, payments, reports, ledgers, and void actions. |
| Principal (Kepala Sekolah) | Read-only access to reports, ledgers, bills, and payments. Cannot perform CRUD or void actions. |
| Teacher/Guru | No access to finance default. |
| Parent | Read-only access to portal statement of their own children. |
| Student | Read-only access to portal statement of their own account. |

## Limitations / Exclusions

The following features are NOT part of Phase 14:
1. Payment gateway integration (Midtrans, Xendit, etc.).
2. Automatic QRIS payments.
3. Virtual Account (VA) generations.
4. Internal e-wallets or merchant POS systems.
5. Automated bank reconciliations.
6. Mobile applications or WhatsApp reminders.

## Definition of Done

1. Database migrations run successfully.
2. Initial categories are seeded.
3. Admin can manage cost categories and items.
4. Admin can issue bills to students, generating debit ledger entries.
5. Admin can register payments, generating credit ledger entries.
6. Payments are auto-allocated to outstanding bills by due date.
7. Status of bills transitions to `partial` or `paid` accordingly.
8. Void transactions reverse their ledger impacts cleanly using offsetting debit/credit entries.
9. Parent and Student portals respect data ownership and role restrictions.
10. System health check passes.
11. Assets compile cleanly.
