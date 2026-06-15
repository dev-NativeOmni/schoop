# API Security Policy

## Token Storage

API client tokens are generated as one-time plain tokens and stored only as SHA-256 hashes. Plain tokens are never persisted and cannot be displayed again after generation.

## Scope Policy

API clients start with no access. Every endpoint requires an explicit scope such as `students:read` or `finance:read`. Sensitive scopes must be reviewed before assignment.

## Tenant Isolation

Tenant-bound clients can only access records for their assigned `school_id`. Global clients are reserved for internal approved integrations and still require explicit scopes.

## Rate Limit

Every API client has a per-minute limit. Requests above the limit return HTTP 429 and are logged.

## Request Logging

External API requests log metadata only: request id, method, path, client, tenant, scope, response status, IP, user agent, duration, and short error text. Full request body and secrets must not be logged.

## Sensitive Data

External API responses must not expose passwords, remember tokens, internal notes, parent contact details, PINs, raw wallet locks, or cross-tenant data.

## Token Leak Response

1. Revoke the affected token immediately.
2. Rotate client credentials.
3. Review request logs for suspicious usage.
4. Suspend the API client if tenant data exposure is suspected.
5. Document incident timeline and follow-up actions.
