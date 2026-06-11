# Phase 8 — Notification Center

## Status

Phase 8 membangun Notification Center berbasis database.

Kanal awal:

```text
database
```

Belum memakai:

```text
email
whatsapp
push notification
websocket
sms
```

## Output

1. Tabel `notifications`.
2. Halaman daftar notifikasi.
3. Halaman detail notifikasi.
4. Mark as read.
5. Mark all as read.
6. Delete personal notification.
7. Pengumuman manual dari admin.
8. Notifikasi setoran baru.
9. Notifikasi hutang hafalan.
10. Badge unread notification di navbar.

## Controller Baru

```text
App\Http\Controllers\Notifications\NotificationCenterController
App\Http\Controllers\Notifications\AnnouncementController
```

## Request Baru

```text
App\Http\Requests\Notifications\StoreAnnouncementRequest
```

## Notification Class Baru

```text
App\Notifications\AnnouncementNotification
App\Notifications\HafalanRecordCreatedNotification
App\Notifications\TahfizhDebtBehindNotification
```

## Service Baru

```text
App\Services\Notifications\NotificationRecipientResolver
App\Services\Notifications\NotificationDispatchService
```

## Route Baru

```text
notifications.index
notifications.show
notifications.mark-as-read
notifications.mark-all-as-read
notifications.destroy
notifications.announcements.create
notifications.announcements.store
```

## Role Access

| Role           | Lihat Notifikasi Sendiri | Kirim Pengumuman |
| -------------- | -----------------------: | ---------------: |
| Super Admin    |                       Ya |               Ya |
| Admin Sekolah  |                       Ya |               Ya |
| Kepala Sekolah |                       Ya |            Tidak |
| Guru Tahfidz   |                       Ya |            Tidak |
| Orang Tua      |                       Ya |            Tidak |
| Santri         |                       Ya |            Tidak |

## Jenis Notifikasi

| Jenis          | Kategori                 |
| -------------- | ------------------------ |
| Pengumuman     | `announcement`           |
| Setoran Baru   | `hafalan_record_created` |
| Hutang Hafalan | `tahfizh_debt_behind`    |

## Aturan Keamanan

1. User hanya boleh melihat notifikasi miliknya sendiri.
2. User tidak boleh membaca notifikasi user lain.
3. User tidak boleh menghapus notifikasi user lain.
4. Parent dan student tidak boleh membuat pengumuman.
5. Guru tidak boleh membuat pengumuman umum.
6. Notifikasi tidak boleh membuka data anak lain.
7. Pengumuman dikirim hanya oleh Super Admin dan Admin Sekolah.

## Belum Dibuat

Phase 8 belum membuat:

1. WhatsApp gateway.
2. Push notification.
3. Firebase.
4. Websocket.
5. Email otomatis.
6. SMS.
7. Chat parent-guru.
8. Scheduled reminder otomatis.
9. Notification preference per user.
10. Notification template manager.

## Definition of Done

Phase 8 selesai jika:

1. Tabel `notifications` berhasil dibuat.
2. Semua user bisa membuka Notification Center masing-masing.
3. Admin bisa mengirim pengumuman.
4. Parent menerima pengumuman sesuai target penerima.
5. Badge unread tampil.
6. Detail notifikasi bisa dibuka.
7. Notifikasi berubah menjadi dibaca setelah dibuka.
8. Tombol mark all as read berjalan.
9. Tombol delete notification berjalan.
10. Parent/student tidak bisa membuat pengumuman.
11. User tidak bisa membuka notifikasi milik user lain.
12. Notifikasi setoran baru terkirim ke parent/santri yang terhubung.
13. Dokumentasi Phase 8 dibuat.
