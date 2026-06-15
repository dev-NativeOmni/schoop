# Phase 20 - Company/Product Scale & SaaS Operations

Status: Done

Tanggal eksekusi: 2026-06-15

## Ringkasan

Phase 20 menambahkan lapisan operasional SaaS untuk HafizPlus School Platform. Fokusnya bukan modul santri baru, tetapi proses produk: subscription manual, invoice tenant manual, onboarding sekolah, support ticket, SLA, incident response, release note, knowledge base, customer success, usage snapshot, dan tenant health score.

## Output Utama

- SaaS Operations Dashboard.
- Subscription plan management.
- School subscription management.
- Tenant invoice manual dan tenant payment manual.
- Implementation project dan onboarding checklist.
- Support ticket system.
- SLA policy dan SLA breach checker.
- Incident report system.
- Release notes.
- Knowledge base.
- Customer success notes.
- Product usage snapshot.
- Tenant health score.
- Role internal: support staff, customer success, sales, operations manager.

## Tabel

- `saas_subscription_plans`
- `saas_school_subscriptions`
- `saas_tenant_invoices`
- `saas_tenant_invoice_items`
- `saas_tenant_payments`
- `implementation_projects`
- `onboarding_checklist_items`
- `onboarding_checklist_records`
- `support_tickets`
- `support_ticket_messages`
- `sla_policies`
- `incident_reports`
- `release_notes`
- `knowledge_base_articles`
- `customer_success_notes`
- `product_usage_snapshots`

## Command

- `php artisan app:capture-product-usage-snapshots`
- `php artisan app:check-support-sla-breaches`
- `php artisan app:generate-tenant-invoices --dry-run`

## Guardrail

- Parent dan student tidak diberi akses SaaS Operations.
- Tenant invoice adalah billing HafizPlus ke sekolah, terpisah dari tagihan santri.
- Tidak ada payment gateway, auto debit, QRIS, VA, atau callback bank.
- Suspend subscription tetap manual dan butuh alasan.
- Invoice/payment tidak dihapus; status berubah secara manual.
- Usage snapshot menyimpan aggregate metrics, bukan nama santri/orang tua.

## Verifikasi

- `php artisan migrate`: success
- `php artisan db:seed --class=SaasSubscriptionPlanSeeder`: success
- `php artisan db:seed --class=SlaPolicySeeder`: success
- `php artisan db:seed --class=OnboardingChecklistItemSeeder`: success
- `php artisan app:capture-product-usage-snapshots`: success
- `php artisan app:check-support-sla-breaches`: success
- `php artisan app:generate-tenant-invoices --dry-run`: success
