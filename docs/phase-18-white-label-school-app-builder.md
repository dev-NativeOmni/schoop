# Phase 18 — White-Label School App Builder

Status: Done

Tanggal eksekusi: 2026-06-15

## Ringkasan

Phase 18 menambahkan layer white-label per tenant sekolah di atas fondasi multi-tenant. Modul ini mengelola identitas brand, tema terbatas berbasis token, domain mapping manual, manifest PWA tenant-aware, preview, publish, dan rollback snapshot.

## Output Utama

- Menu White-Label Builder di navigasi untuk role yang berhak.
- Brand profile per sekolah: display name, short name, tagline, logo, favicon, background login, kontak publik.
- Theme builder per sekolah: warna primer/sekunder/aksen/teks/background, layout, radius kartu/tombol.
- Domain mapping: subdomain/custom domain, token verifikasi, verifikasi manual, aktivasi, nonaktif, edit, hapus.
- Public landing page tenant-aware.
- Login/layout tenant-aware.
- Manifest PWA tenant-aware dengan fallback icon.
- Preview draft branding sebelum publish.
- Publish dan rollback snapshot white-label.
- PDF Tahfizh memakai nama, tagline, dan warna brand tenant.

## Tabel

- `school_brand_profiles`
- `school_theme_settings`
- `school_domain_mappings`
- `school_pwa_settings`
- `white_label_publications`

## Route Penting

- `white-label.dashboard`
- `white-label.brand.show`
- `white-label.brand.edit`
- `white-label.themes.edit`
- `white-label.themes.preview`
- `white-label.domains.index`
- `white-label.domains.create`
- `white-label.domains.edit`
- `white-label.pwa.show`
- `white-label.preview.show`
- `white-label.publish`
- `white-label.rollback`
- `tenant.public.landing`
- `tenant.pwa.manifest`

## Guardrail Keamanan

- White-label hanya mengubah tampilan dan metadata tenant, bukan ownership data.
- Domain resolver tidak memilih tenant random saat domain tidak dikenal.
- Domain action di-scope ke `school_id` aktif sebelum verify/activate/disable/update/delete.
- Admin sekolah hanya bisa mengelola tenant yang dapat diakses.
- Teacher, parent, dan student tidak diberi akses ke builder.
- Theme tidak menerima CSS bebas, hanya token tervalidasi.
- Upload asset disimpan di disk `public` dengan path `white-label/{school_id}/...`.

## Verifikasi

- `php artisan route:list --name=white-label`
- `php artisan view:cache`
- `php artisan test`
- `npm run build`

Semua verifikasi di atas berhasil pada eksekusi lokal.
