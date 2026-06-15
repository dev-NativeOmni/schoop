# Mobile Test Plan

## Backend API

- Parent login succeeds.
- Student login succeeds.
- Teacher login succeeds.
- Merchant/cashier login succeeds if role is active.
- Logout revokes token.
- Refresh revokes old token and returns a new token.
- Device registration succeeds.
- Tenant switch succeeds only for valid membership.
- Force update returns HTTP 426 for unsupported app versions.
- API audit log is written for mobile requests.

## Parent UAT

- Parent can list own children.
- Parent cannot open unrelated student id.
- Parent can read tahfizh, attendance, finance, cashless, mutabaah, and tahsin summaries.
- Parent cannot input tahfizh or POS transactions.

## Student UAT

- Student can read own summary.
- Student cannot choose arbitrary `student_id`.
- Student can read QR card payload.
- Student cannot access teacher endpoint.

## Teacher UAT

- Teacher can read dashboard, classes, and tenant students.
- Teacher can create tahfizh record with sequence guard.
- Teacher can save mutabaah records.
- Teacher can save tahsin assessment.
- Teacher can scan attendance QR and create manual attendance.

## Merchant UAT

- Cashier can open and close POS session.
- Sale preview returns server-calculated total.
- Checkout decreases wallet balance through ledger service.
- Duplicate idempotency key returns existing sale and does not double-charge.
- Insufficient balance is rejected.
- Unauthorized cashier cannot access another merchant.

## Flutter Smoke Test

Skipped until Flutter SDK is available locally.
