# Deployment Checklist — HafizPlus School Platform

## Pre-Deploy

```bash
git status
composer validate
composer install
npm install
npm run build
php artisan test
php artisan route:list
php artisan migrate:status
php artisan app:system-health-check
```

## Deploy Commands

```bash
git pull origin main
composer install --no-dev --optimize-autoloader
npm ci
npm run build
php artisan migrate --force
php artisan optimize
php artisan app:system-health-check
```

## Jika Menggunakan Maintenance Mode

Sebelum deploy:

```bash
php artisan down
```

Setelah deploy:

```bash
php artisan up
```

## Post-Deploy

```bash
php artisan route:list
php artisan migrate:status
php artisan schedule:list
php artisan app:system-health-check
php artisan app:backup-database
```

## Browser Check

Cek:

```text
/login
/dashboard
/reports/tahfizh/dashboard
/portal/parent/dashboard
/portal/student/dashboard
/notifications
/exports/tahfizh
/admin/system/status
/up
```

## Hal yang Tidak Boleh

1. Jangan `migrate:fresh` di production.
2. Jangan `db:wipe` di production.
3. Jangan `composer update` di production.
4. Jangan ubah `APP_KEY` sembarangan.
5. Jangan commit `.env`.
6. Jangan upload backup ke public.
