# Production Checklist — HafizPlus School Platform

## Environment

- [ ] Domain production tersedia.
- [ ] Server production tersedia.
- [ ] PHP minimal 8.2 tersedia.
- [ ] MySQL/MariaDB tersedia.
- [ ] Composer tersedia.
- [ ] Node.js dan NPM tersedia untuk build.
- [ ] Web server mengarah ke folder `public`.
- [ ] HTTPS aktif.
- [ ] `.env` production dibuat.
- [ ] `APP_ENV=production`.
- [ ] `APP_DEBUG=false`.
- [ ] `APP_KEY` production dibuat dan disimpan aman.

## Database

- [ ] Database production dibuat.
- [ ] User database production dibuat.
- [ ] Password database kuat.
- [ ] Migration production berhasil.
- [ ] Tidak memakai database local/test.
- [ ] Seeder demo tidak dijalankan sembarangan.

## Storage

- [ ] Folder `storage` writable.
- [ ] Folder `bootstrap/cache` writable.
- [ ] Folder backup tersedia.
- [ ] Symlink storage dibuat jika diperlukan.

## Security

- [ ] Password default diganti.
- [ ] Akun demo dinonaktifkan atau dihapus.
- [ ] Super admin dibatasi.
- [ ] Parent hanya melihat anak sendiri.
- [ ] Student hanya melihat data sendiri.
- [ ] Export hanya untuk role internal.
- [ ] Notifikasi hanya milik user terkait.
- [ ] APP_DEBUG false.
- [ ] Backup tidak berada di public folder.

## Performance

- [ ] `composer install --no-dev --optimize-autoloader`.
- [ ] `npm run build`.
- [ ] `php artisan optimize`.
- [ ] Route cache aman.
- [ ] View cache aman.
- [ ] Config cache aman.

## Scheduler

- [ ] Cron scheduler production dibuat.
- [ ] `php artisan schedule:list` dicek.
- [ ] Backup harian masuk scheduler.

## Queue

- [ ] `QUEUE_CONNECTION` ditentukan.
- [ ] Jika queue dipakai, worker dijalankan dengan Supervisor.
- [ ] Failed jobs dipantau.

## Backup

- [ ] Backup manual berhasil.
- [ ] Backup scheduled berhasil.
- [ ] Restore test berhasil di staging.
- [ ] Retensi backup dipastikan.

## Final Verification

- [ ] Login semua role berhasil.
- [ ] Dashboard internal berhasil.
- [ ] Input setoran berhasil.
- [ ] Hitung hutang berhasil.
- [ ] Report bulanan berhasil.
- [ ] Report triwulan berhasil.
- [ ] Portal parent berhasil.
- [ ] Portal student berhasil.
- [ ] Notification center berhasil.
- [ ] Export PDF berhasil.
- [ ] Export Excel berhasil.
- [ ] System status healthy.
