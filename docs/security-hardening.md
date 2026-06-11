# Security Hardening — HafizPlus School Platform

## Environment

Production wajib:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://domain-production
SESSION_SECURE_COOKIE=true
LOG_LEVEL=warning
```

## File Protection

Server harus mengarah ke folder:

```text
public/
```

Jangan arahkan web root ke root project.

File berikut tidak boleh bisa diakses publik:

```text
.env
composer.json
composer.lock
package.json
storage/
database/
routes/
config/
```

## Secrets

Rahasia berikut tidak boleh masuk Git:

1. APP_KEY production.
2. DB password.
3. Mail password.
4. API key.
5. Backup file.
6. Production `.env`.

## User Security

1. Semua password default harus diganti.
2. Akun demo harus dihapus atau dinonaktifkan.
3. Role super admin dibatasi.
4. Parent hanya boleh lihat anak sendiri.
5. Student hanya boleh lihat data sendiri.
6. Admin tidak boleh menghapus audit/log penting sembarangan.

## Session

Production disarankan:

```env
SESSION_DRIVER=database
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=lax
```

## Error Handling

Production wajib:

```env
APP_DEBUG=false
```

Jangan tampilkan stack trace ke user.

## Backup

1. Backup database minimal harian.
2. Simpan minimal 14 hari.
3. Jangan simpan backup di public directory.
4. Test restore minimal sebelum rilis.
