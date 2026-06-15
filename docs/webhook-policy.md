# Webhook Policy

## Events

Initial webhook events:

- `student.created`
- `attendance.recorded`
- `tahfizh.recorded`
- `finance.bill.posted`
- `cashless.sale.posted`
- `webhook.test`

## Payload Format

Webhook delivery payloads include event name, delivery id, tenant id, timestamp, and event data. Payloads must not include API secrets or raw credentials.

## Signature Headers

Webhook requests include:

- `X-HafizPlus-Event`
- `X-HafizPlus-Delivery`
- `X-HafizPlus-Signature`
- `X-HafizPlus-Timestamp`

The signature is an HMAC SHA-256 signature of the JSON payload using the webhook secret.

## Retry Policy

Failed deliveries are stored with response status, response excerpt, error message, attempt count, and `next_retry_at`. Retry command should be run by operations until scheduler policy is enabled.

## Failure Handling

Repeated failures should trigger partner contact, endpoint suspension, or secret rotation depending on risk.

## Secret Rotation

Webhook secrets are hashed at rest. To rotate a secret, edit the endpoint and provide a new secret. Existing secret values cannot be recovered.
