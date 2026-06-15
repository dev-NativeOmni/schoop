# Phase 17 — Multi-Tenant Foundation

## Status

Phase 17 menambahkan fondasi multi-tenant untuk HafizPlus School Platform.

## Strategi

Multi-tenant memakai strategi:

- Single database.
- Shared schema.
- Tenant key menggunakan `school_id`.
- Tenant sama dengan sekolah.
- Active tenant disimpan di session `active_school_id`.

## Scope

Phase ini mencakup:

1. Tenant metadata di schools.
2. User-school memberships.
3. Tenant settings.
4. Tenant modules.
5. Tenant audit logs.
6. Tenant context middleware.
7. Tenant access middleware.
8. Tenant switcher.
9. Tenant-aware query foundation.
10. Backfill `school_id`.
11. Tenant health check.

## Tabel Baru

1. `user_school_memberships`
2. `tenant_settings`
3. `tenant_modules`
4. `tenant_audit_logs`

## Tabel Diubah

1. `schools`
2. Tabel tenant-owned yang belum punya `school_id`

## Service Baru

1. `TenantContextService`
2. `TenantAccessService`
3. `TenantMembershipService`
4. `TenantModuleService`
5. `TenantSettingsService`
6. `TenantAuditLogger`
7. `TenantDataBackfillService`

## Middleware Baru

1. `ResolveTenantContext`
2. `EnsureTenantAccess`

## Command Baru

1. `php artisan app:backfill-tenant-school-id`
2. `php artisan app:tenant-health-check`

## Route Baru

1. `tenancy.dashboard`
2. `tenancy.switcher`
3. `tenancy.switch`
4. `tenancy.memberships.*`
5. `tenancy.settings.index`
6. `tenancy.settings.update`
7. `tenancy.modules.index`
8. `tenancy.modules.update`
9. `tenancy.audit-logs.index`

## Batasan

Phase 17 tidak membuat:

1. White-label branding.
2. Custom domain.
3. Subdomain per sekolah.
4. Billing SaaS.
5. Payment gateway.
6. Cashless POS.
7. Mobile app.
8. Multi-database tenancy.
9. Tenant-specific deployment.

## Definition of Done

Phase 17 selesai jika:

1. Migration berhasil.
2. Backfill berhasil.
3. Tenant health check berhasil.
4. User-school membership berjalan.
5. Active tenant tersimpan di session.
6. Tenant switcher berjalan untuk user yang berhak.
7. Admin hanya melihat data tenant aktif.
8. Kepala sekolah read-only untuk tenant aktif.
9. Parent hanya melihat anak sendiri.
10. Student hanya melihat data sendiri.
11. Export tenant-aware.
12. Student 360 tenant-aware.
13. Finance tenant-aware.
14. Boarding tenant-aware.
15. Tenant modules bisa dikonfigurasi.
16. Tenant settings bisa dikonfigurasi.
17. Tenant audit log mencatat action penting.
18. `npm run build` berhasil.
19. `php artisan app:system-health-check` berhasil.
20. Dokumentasi dibuat.
