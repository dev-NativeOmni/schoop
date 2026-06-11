# Backup and Restore Policy — HafizPlus School Platform

## Tujuan

Menjamin data tahfizh, user, orang tua, santri, laporan, dan notifikasi bisa dipulihkan jika terjadi kerusakan data.

## Jenis Backup

Phase 10 membuat backup database MySQL format:

```text
.sql
```

Lokasi local:

```text
storage/app/backups
```

## Frekuensi

Production:

```text
Setiap hari pukul 23:30
```

## Retensi

Default:

```text
14 hari
```

Konfigurasi:

```env
BACKUP_KEEP_DAYS=14
```

## Command Manual

```bash
php artisan app:backup-database
```

## Restore Manual

Contoh restore MySQL:

```bash
mysql -u hafizplus_user -p hafizplus_school_platform < backup-file.sql
```

## Aturan Penting

1. Jangan restore ke production tanpa backup baru.
2. Jangan restore file tidak jelas sumbernya.
3. Jangan simpan backup di folder public.
4. Jangan commit backup ke Git.
5. Simpan salinan backup di media eksternal/server lain jika masuk production sungguhan.

## Test Restore

Minimal lakukan test restore ke database staging:

```bash
CREATE DATABASE hafizplus_restore_test CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
mysql -u root -p hafizplus_restore_test < backup-file.sql
```

Lalu cek:

```bash
php artisan migrate:status
```
