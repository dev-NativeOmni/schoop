# Mobile API v1

Base path: `/api/mobile/v1`

Response format:

```json
{
  "success": true,
  "message": "OK",
  "data": {},
  "meta": {}
}
```

Error format:

```json
{
  "success": false,
  "message": "Validasi gagal.",
  "errors": {},
  "meta": {}
}
```

## Headers

- `Authorization: Bearer <token>`
- `Accept: application/json`
- `X-School-Id: <school_id>` optional tenant override for valid memberships
- `X-Mobile-Platform: android|ios|web`
- `X-Mobile-Version: 1.0.0`

## Auth

- `POST /auth/login`
- `POST /auth/logout`
- `GET /auth/me`
- `POST /auth/refresh`
- `POST /auth/change-password`
- `POST /auth/forgot-password/request`
- `POST /auth/forgot-password/reset`

Login accepts `login`, `password`, `device_uuid`, `platform`, optional `platform_version`, `app_version`, `device_name`, and `school_id`.

## Bootstrap and Tenant

- `GET /bootstrap`
- `GET /tenants`
- `POST /tenants/switch`
- `GET /tenants/current`

Bootstrap returns active tenant, user role, branding, theme, enabled modules, feature flags, app version policy, support contact, and navigation.

## Device

- `POST /devices`
- `POST /devices/push-token`
- `POST /devices/revoke`

Device registration stores platform, app version, device uuid, last seen IP/time, and optional push token.

## Parent

- `GET /parent/children`
- `GET /parent/children/{student}/summary`
- `GET /parent/children/{student}/tahfizh`
- `GET /parent/children/{student}/mutabaah`
- `GET /parent/children/{student}/attendance`
- `GET /parent/children/{student}/tahsin`
- `GET /parent/children/{student}/finance`
- `GET /parent/children/{student}/cashless`
- `GET /parent/children/{student}/notifications`

Every parent endpoint checks ownership through the parent-student relation.

## Student

- `GET /student/me/summary`
- `GET /student/me/tahfizh`
- `GET /student/me/mutabaah`
- `GET /student/me/attendance`
- `GET /student/me/tahsin`
- `GET /student/me/finance`
- `GET /student/me/cashless`
- `GET /student/me/qr-card`
- `GET /student/me/notifications`

Student endpoints resolve the student from the authenticated user.

## Teacher

- `GET /teacher/dashboard`
- `GET /teacher/classes`
- `GET /teacher/students`
- `GET /teacher/students/{student}/summary`
- `POST /teacher/tahfizh/records`
- `GET /teacher/tahfizh/records`
- `POST /teacher/mutabaah/records`
- `GET /teacher/attendance/sessions`
- `POST /teacher/attendance/scan`
- `POST /teacher/attendance/manual-records`
- `POST /teacher/tahsin/assessments`
- `GET /teacher/notifications`

Teacher endpoints are tenant-scoped and never expose cross-school data.

## Merchant

- `GET /merchant/profile`
- `GET /merchant/products`
- `POST /merchant/pos-sessions/open`
- `POST /merchant/pos-sessions/{session}/close`
- `POST /merchant/sales/preview`
- `POST /merchant/sales/checkout`
- `POST /merchant/sales/{sale}/void`
- `GET /merchant/sales`
- `GET /merchant/settlements`

Checkout is online, idempotent, atomic, and server-authoritative through the existing cashless ledger service.
