# Rollback Plan — HafizPlus School Platform

## Tujuan

Menyiapkan langkah pemulihan jika deploy gagal.

## Skenario Rollback

1. Aplikasi error setelah deploy.
2. Migration gagal.
3. Export error.
4. Login error.
5. Dashboard/report error.
6. Data tidak tampil.
7. Queue/scheduler error.

## Langkah Rollback Kode

```bash
php artisan down
git log --oneline
git checkout PREVIOUS_COMMIT_HASH
composer install --no-dev --optimize-autoloader
npm ci
npm run build
php artisan optimize:clear
php artisan optimize
php artisan up
```

## Rollback Database

Rollback database hanya dilakukan jika benar-benar perlu.

Sebelum rollback database:

```bash
php artisan app:backup-database
```

Restore:

```bash
mysql -u hafizplus_user -p hafizplus_school_platform < backup-before-deploy.sql
```

## Setelah Rollback

```bash
php artisan app:system-health-check
php artisan route:list
php artisan migrate:status
```

Cek browser:

```text
/login
/dashboard
/reports/tahfizh/dashboard
/admin/system/status
```

## Larangan

1. Jangan rollback database tanpa backup baru.
2. Jangan hapus migration sembarangan.
3. Jangan ubah APP_KEY.
4. Jangan hapus storage production.
5. Jangan restore backup dari sumber tidak jelas.
