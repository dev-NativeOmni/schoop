# Mobile Release Checklist

## Before Closed Testing

- Confirm production API URL.
- Confirm privacy policy URL.
- Confirm support email and phone.
- Confirm app name, short name, icon, splash, and screenshots.
- Confirm minimum supported version in `mobile_app_versions`.
- Confirm force-update policy.
- Run parent, student, teacher, and merchant UAT.
- Run API security review for ownership and tenant isolation.
- Verify no credential, API key, or tenant id is hardcoded in the mobile app.

## Before Store Submission

- Prepare Play Store data safety answers.
- Prepare App Store privacy nutrition labels.
- Prepare test account credentials.
- Prepare release notes.
- Prepare incident rollback plan.
- Confirm support team can identify app version, platform, user, tenant, and device id from logs.

## Not In Phase 21

- Auto publish to Play Store or App Store.
- Push notification provider credentials.
- Crash analytics credentials.
- Payment gateway or QRIS automation.
