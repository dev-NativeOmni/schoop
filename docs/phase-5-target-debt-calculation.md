# Phase 5 — Target and Debt Calculation

## Status

Phase 5 membangun modul target dan hutang hafalan untuk HafizPlus School Platform.

## Output

1. CRUD target tahfizh.
2. Tabel hutang hafalan.
3. Kalkulasi target harian, mingguan, dan bulanan.
4. Kalkulasi capaian dari setoran hafalan.
5. Kalkulasi hutang hafalan.
6. Kalkulasi lebih hafalan.
7. Kalkulasi akumulasi hutang.
8. Status capaian santri.
9. Role-based access.

## Tabel Baru

```text
tahfizh_debts
```

## Service Baru

```text
App\Services\Tahfizh\ActiveTahfizhTargetResolver
App\Services\Tahfizh\TahfizhDebtCalculator
```

## Controller Baru

```text
App\Http\Controllers\Tahfizh\TahfizhTargetController
App\Http\Controllers\Tahfizh\TahfizhDebtController
```

## Route Baru

```text
tahfizh.targets.index
tahfizh.targets.create
tahfizh.targets.store
tahfizh.targets.show
tahfizh.targets.edit
tahfizh.targets.update
tahfizh.targets.destroy

tahfizh.debts.index
tahfizh.debts.show
tahfizh.debts.calculate
```

## Prioritas Target

Target dipilih berdasarkan prioritas berikut:

1. Target khusus santri.
2. Target kelas.
3. Target program.
4. Target umum sekolah.

## Rumus

```text
hutang = max(0, target_lines - actual_lines)
lebih = max(0, actual_lines - target_lines)
akumulasi_hutang = max(0, hutang_sebelumnya + hutang_hari_ini - lebih_hari_ini)
```

## Status

| Status      | Arti                   |
| ----------- | ---------------------- |
| `no_target` | Belum ada target aktif |
| `met`       | Target tercapai        |
| `behind`    | Kurang dari target     |
| `ahead`     | Lebih dari target      |

## Role Access

| Role           | Target      | Hutang           |
| -------------- | ----------- | ---------------- |
| Super Admin    | CRUD        | Lihat dan hitung |
| Admin Sekolah  | CRUD        | Lihat dan hitung |
| Kepala Sekolah | Lihat       | Lihat            |
| Guru Tahfidz   | Lihat       | Lihat            |
| Orang Tua      | Belum akses | Belum akses      |
| Santri         | Belum akses | Belum akses      |

## Belum Dibuat

Phase 5 belum membuat:

1. Report bulanan final.
2. Report triwulan final.
3. Dashboard grafik.
4. Export PDF.
5. Export Excel.
6. Parent progress detail.
7. Student progress detail.
8. Notification center.
9. API mobile.

## Definition of Done

Phase 5 selesai jika:

1. Target tahfizh bisa dibuat.
2. Target tahfizh bisa diedit.
3. Target tahfizh bisa dihapus.
4. Target bisa berlaku untuk sekolah, kelas, program, atau santri.
5. Sistem bisa memilih target aktif berdasarkan prioritas.
6. Hutang harian bisa dihitung.
7. Hutang mingguan bisa dihitung.
8. Hutang bulanan bisa dihitung.
9. Akumulasi hutang berjalan.
10. Status capaian benar.
11. Kepala Sekolah dan Guru bisa melihat hasil hutang.
12. Orang Tua dan Santri belum bisa mengakses halaman ini.
13. Dokumentasi Phase 5 dibuat.
