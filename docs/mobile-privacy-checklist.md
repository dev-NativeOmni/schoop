# Mobile Privacy Checklist

## Data Allowed In Mobile

- User profile basics.
- Tenant branding and support contact.
- Student academic summaries scoped to owner or role.
- Student attendance, tahfizh, mutabaah, tahsin, finance, and cashless summaries.
- Merchant transaction data for authorized cashier/merchant users.

## Data Not Allowed In Mobile

- Password hashes.
- Raw token hashes.
- Full internal permissions dump.
- Cross-tenant data.
- Parent data for unrelated students.
- Finance reports outside authorized role.
- Cached offline wallet balance as source of truth.

## Storage Rules

- Store raw mobile token only in secure OS storage in the native app.
- Do not store password.
- Do not store cashless payment token beyond transaction need.
- Clear token and push token during logout/device revoke.

## Analytics Rules

- Do not send sensitive student, finance, or wallet data to analytics without explicit policy review.
- Do not log password, token, PIN, QR payload, or push token.
