# Phase 22 - External API, Partner Integration & Developer Portal

Status: Done

## Tujuan

Phase 22 membuka HafizPlus School Platform untuk integrasi eksternal yang aman, tenant-aware, scope-based, rate-limited, dan logged. Fase ini adalah fondasi integrasi partner, bukan payment gateway production atau marketplace developer publik.

## Scope Implementasi

- Developer Portal untuk dashboard, API clients, API scopes, partner integrations, webhook endpoints, webhook deliveries, request logs, dan API docs.
- External API v1 dengan prefix `/api/v1`.
- Bearer token API client yang disimpan dalam bentuk hash.
- Scope granular untuk setiap endpoint.
- Rate limit per API client per menit.
- Request log untuk semua request external API.
- Webhook endpoint registry dan delivery log dengan signature HMAC.
- Command operasional untuk retry webhook, prune request log, dan rotate token.

## Tabel

- `api_clients`
- `api_client_tokens`
- `api_scopes`
- `api_client_scope`
- `api_request_logs`
- `partner_integrations`
- `webhook_endpoints`
- `webhook_deliveries`
- `api_documentation_pages`

## Endpoint API v1

- `GET /api/v1/students` - `students:read`
- `GET /api/v1/students/{student}` - `students:read`
- `GET /api/v1/classes` - `classes:read`
- `GET /api/v1/attendance-records` - `attendance:read`
- `POST /api/v1/attendance-records` - `attendance:write`
- `GET /api/v1/tahfizh/progress` - `tahfizh:read`
- `GET /api/v1/finance/bills` - `finance:read`
- `GET /api/v1/cashless/transactions` - `cashless:read`
- `POST /api/v1/webhooks/test` - `webhooks:manage`

## Middleware

- `api.client` validates hashed Bearer tokens, client status, token status, expiration, and IP allowlist.
- `api.scope` enforces route-level scope.
- `api.rate_limit` applies client-specific per-minute throttling.
- `api.request_log` stores request id, method, path, scope, status, IP, user agent, duration, and short error.

## Role Access

Developer Portal is limited to `super_admin`, `operations_manager`, `support_staff`, `customer_success`, `admin`, and `admin_sekolah`. Parent, student, teacher, merchant, and cashier roles are not allowed to manage external API clients.

## UAT Checklist

- Create API client and assign tenant.
- Assign scopes explicitly.
- Generate token and verify token is shown once.
- Call allowed endpoint successfully.
- Call unassigned scope and verify 403.
- Revoke token and verify 401.
- Verify request logs are stored.
- Verify client rate limit returns 429.
- Create webhook endpoint and trigger test delivery.
- Run retry and prune commands.

## Exclusions

- No payment gateway production.
- No auto QRIS or virtual account.
- No public developer marketplace.
- No OAuth marketplace.
- No microservices rewrite.
