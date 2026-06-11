# Phase 12 — QR Attendance System

## Status

Phase 12 menambahkan modul QR Attendance System untuk HafizPlus School Platform.

## Scope

Modul ini mencakup:

1. QR unik per santri.
2. Generate QR token internal.
3. Rotate QR token.
4. Cetak QR card.
5. Web QR scanner.
6. Attendance session.
7. Check in.
8. Check out opsional.
9. Late detection.
10. Manual attendance record.
11. Attendance report.
12. Parent attendance portal.
13. Student attendance portal.
14. Role-based access.
15. Ownership-based access.

## Tabel Baru

1. `attendance_qr_tokens`
2. `attendance_sessions`
3. `attendance_records`

## Model Baru

1. `AttendanceQrToken`
2. `AttendanceSession`
3. `AttendanceRecord`

## Controller Baru

1. `AttendanceQrCardController`
2. `AttendanceSessionController`
3. `AttendanceScannerController`
4. `AttendanceManualRecordController`
5. `AttendanceReportController`
6. `ParentAttendancePortalController`
7. `StudentAttendancePortalController`

## Service Baru

1. `AttendanceAccessService`
2. `AttendanceQrTokenService`
3. `QrCodeService`
4. `AttendanceScanService`
5. `AttendanceReportService`

## Command Baru

```powershell
php artisan app:generate-attendance-qr-tokens
```

## Package Baru

```powershell
composer require bacon/bacon-qr-code
```

## Route Baru

1. `attendance.qr-cards.index`
2. `attendance.qr-cards.print`
3. `attendance.qr-cards.rotate`
4. `attendance.scanner.index`
5. `attendance.scanner.scan`
6. `attendance.manual.create`
7. `attendance.manual.store`
8. `attendance.reports.dashboard`
9. `attendance.sessions.index`
10. `attendance.sessions.create`
11. `attendance.sessions.store`
12. `attendance.sessions.show`
13. `attendance.sessions.edit`
14. `attendance.sessions.update`
15. `attendance.sessions.destroy`
16. `portal.parent.attendance`
17. `portal.student.attendance`

## Role Access

| Role           | Akses                                  |
| -------------- | -------------------------------------- |
| Super Admin    | QR card, scanner, manual input, report |
| Admin          | QR card, scanner, manual input, report |
| Kepala Sekolah | Report read-only                       |
| Guru           | Scanner dan manual input terbatas      |
| Parent         | Portal presensi anak sendiri           |
| Student        | Portal presensi pribadi                |

## Batasan

Phase 12 tidak membuat:

1. Tahsin.
2. Finance.
3. Cashless.
4. White-label.
5. Native mobile app.
6. WhatsApp gateway.
7. Push notification.
8. Face recognition.
9. RFID.
10. NFC.
11. Fingerprint.
12. Integrasi mesin absensi.
13. Multi-tenant kompleks.

## Definition of Done

Phase 12 selesai jika:

1. Migration berhasil.
2. QR token berhasil dibuat untuk santri.
3. QR card bisa dicetak.
4. Admin bisa rotate QR.
5. QR lama tidak bisa dipakai setelah rotate.
6. Scanner bisa scan QR.
7. Scan pertama membuat check in.
8. Scan kedua membuat check out.
9. Scan ketiga ditolak.
10. Late detection berjalan.
11. Manual input izin/sakit/absen berjalan.
12. Report attendance tampil.
13. Parent hanya melihat presensi anak sendiri.
14. Student hanya melihat presensi pribadi.
15. Parent/student tidak bisa mengakses scanner.
16. Parent/student tidak bisa mengakses internal report.
17. `npm run build` berhasil.
18. `php artisan app:system-health-check` berhasil.
19. Dokumentasi dibuat.
