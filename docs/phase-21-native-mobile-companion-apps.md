# Phase 21 - Native Mobile Companion Apps & App Store Distribution

Status: Partial

Execution date: 2026-06-15

## Summary

Phase 21 adds the backend foundation for native mobile companion apps. The mobile layer is tenant-aware, role-aware, and server-authoritative. It does not replace the web/PWA admin surface and does not move business logic into the mobile client.

## Main Output

- Mobile API v1 under `/api/mobile/v1`.
- Hashed Bearer token authentication with token revoke and refresh.
- Mobile device registry with push-token readiness.
- Mobile app version management and force-update readiness.
- Mobile API audit log with request id, route action, user, tenant, device, and status.
- Tenant-aware bootstrap response for branding, theme, modules, feature flags, support contact, and navigation.
- Parent endpoints for children, tahfizh, mutabaah, attendance, tahsin, finance, cashless, and notifications.
- Student endpoints for personal summary, tahfizh, mutabaah, attendance, tahsin, finance, cashless, QR card, and notifications.
- Teacher endpoints for dashboard, classes, students, tahfizh input, mutabaah input, attendance scan/manual input, tahsin assessment, and notifications.
- Merchant endpoints for profile, products, POS session, sale preview, checkout, void, sales, and settlements.

## Tables

- `mobile_devices`
- `mobile_app_versions`
- `mobile_api_audit_logs`
- `mobile_access_tokens`

## Guardrails

- Mobile token hashes are stored; raw tokens are returned only once on login/refresh.
- Parent ownership is checked before child data is returned.
- Student endpoints resolve student identity from the authenticated user, not a client-supplied `student_id`.
- Teacher endpoints are tenant-scoped.
- Merchant checkout uses existing `CashlessSaleService`; mobile never mutates wallet balance directly.
- No offline cashless transaction is supported.
- No payment gateway, QRIS automation, VA, or bank callback is added.
- No app store publishing is performed.

## Flutter Status

Flutter SDK is not available in the local PATH. Native mobile skeleton and Flutter validation are skipped until the SDK is installed.

## Next Required UAT

- Parent A cannot access Parent B child data.
- Student A cannot access Student B data.
- Teacher cannot access data outside active tenant.
- Merchant duplicate idempotency key does not double-charge.
- Force-update response returns HTTP 426 when app version is below minimum.
