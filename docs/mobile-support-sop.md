# Mobile Support SOP

## Intake Data

Support should collect:

- School name or tenant code.
- User name and role.
- Platform: Android, iOS, or web.
- App version and build number.
- Device model and OS version.
- Approximate incident time.
- Screenshot if available.
- Mobile request id from response header if available.

## Triage

- P0: token cannot be revoked, tenant data leaks, parent sees unrelated child, wallet balance changes incorrectly.
- P1: mobile login fails broadly, bootstrap wrong tenant, teacher scope wrong, checkout double-charges.
- P2: endpoint error for limited workflow, empty state or wording issue.
- P3: cosmetic issue.

## Escalation

- P0 and P1 go to technical owner immediately.
- Cashless issues require audit log and ledger review before any correction.
- Do not ask users to retry checkout repeatedly when an idempotency issue is suspected.

## Resolution Notes

Record tenant, user id, device id, route action, request id, and final decision in support ticket notes.
