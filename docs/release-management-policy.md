# Release Management Policy

## Release Flow

- Buat release note draft.
- Jalankan migration/status/build/test/health check.
- Deploy staging atau local verification.
- Publish release note setelah validasi.
- Siapkan rollback plan untuk perubahan berisiko.

## Hotfix

Hotfix hanya untuk P0/P1. Setelah hotfix, buat postmortem singkat dan tambahkan regression checklist.
