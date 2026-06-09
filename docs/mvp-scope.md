# MVP Scope — Tahfizh Monitoring App

## 1. Produk MVP

Produk MVP dari HafizPlus School Platform adalah:

> Tahfizh Monitoring App

Tujuannya adalah membuat workflow monitoring hafalan yang benar-benar dipakai harian.

---

## 2. Workflow Utama MVP

Workflow utama:

```text
Guru input setoran
→ sistem validasi target dan urutan
→ sistem menghitung hutang hafalan
→ admin melihat rekap
→ kepala sekolah memonitor aktivitas
→ orang tua melihat progres anak
→ santri melihat progres pribadi
```

---

## 3. Fitur Wajib MVP

| No | Fitur                    | Role Utama             | Status |
| -: | ------------------------ | ---------------------- | ------ |
|  1 | Login role-based         | Semua role             | Wajib  |
|  2 | Master data santri       | Admin Sekolah          | Wajib  |
|  3 | Master data guru         | Admin Sekolah          | Wajib  |
|  4 | Master data kelas        | Admin Sekolah          | Wajib  |
|  5 | Master program / strata  | Admin Sekolah          | Wajib  |
|  6 | Target hafalan           | Admin / Guru           | Wajib  |
|  7 | Input setoran            | Guru Tahfidz           | Wajib  |
|  8 | Validasi urutan hafalan  | Sistem                 | Wajib  |
|  9 | Hitung hutang hafalan    | Sistem                 | Wajib  |
| 10 | Dashboard guru           | Guru Tahfidz           | Wajib  |
| 11 | Dashboard admin sekolah  | Admin Sekolah          | Wajib  |
| 12 | Dashboard kepala sekolah | Kepala Sekolah         | Wajib  |
| 13 | Report bulanan           | Admin / Kepala Sekolah | Wajib  |
| 14 | Report triwulan          | Admin / Kepala Sekolah | Wajib  |
| 15 | Parent portal awal       | Orang Tua              | Wajib  |
| 16 | Student portal awal      | Santri                 | Wajib  |
| 17 | Notifikasi dasar         | Sistem                 | Wajib  |
| 18 | Audit log dasar          | Sistem                 | Wajib  |

---

## 4. Fitur yang Ditunda

Fitur berikut tidak masuk MVP:

1. Full LMS.
2. Native Android.
3. Native iOS.
4. Payment gateway.
5. Cashless kantin.
6. White-label app builder.
7. Multi-tenant kompleks.
8. AI voice correction.
9. QR attendance.
10. Finance ledger.
11. Boarding management.
12. Marketplace konten.
13. Video learning platform.
14. WhatsApp gateway.
15. Face recognition.
16. NFC/RFID attendance.

---

## 5. KPI MVP

| KPI                                           | Target                          |
| --------------------------------------------- | ------------------------------- |
| Guru bisa input setoran                       | Kurang dari 30 detik per santri |
| Admin bisa melihat santri belum target        | Ya                              |
| Kepala sekolah bisa monitoring aktivitas guru | Ya                              |
| Kepala sekolah bisa melihat progres santri    | Ya                              |
| Report bulanan bisa dibuat                    | Ya                              |
| Report triwulan bisa dibuat                   | Ya                              |
| Orang tua bisa melihat progres anak           | Ya                              |
| Santri bisa melihat progres pribadi           | Ya                              |
| Data setoran tidak bisa lompat                | Ya                              |
| Audit perubahan data penting                  | Ya                              |
| Sistem bisa dipakai dari HP                   | Ya                              |

---

## 6. Kesalahan yang Harus Dihindari

1. Membuat terlalu banyak fitur sebelum input setoran stabil.
2. Membuat native mobile terlalu cepat.
3. Membuat finance sebelum audit dan data akurat.
4. Membuat cashless POS sebelum ledger matang.
5. Membuat multi-tenant sebelum single-school workflow stabil.
6. Membuat UI kompleks tapi guru lambat input.
7. Membuat dashboard cantik tapi data belum benar.
