# Phase 3 — Tahfizh Core Database Foundation

## Status

Phase 3 membangun fondasi database dan domain logic awal untuk fitur tahfizh di HafizPlus School Platform.

## Output

Tabel yang dibuat:

1. `quran_juzs`
2. `quran_surahs`
3. `mushaf_pages`
4. `tahfizh_targets`
5. `hafalan_records`

Model yang dibuat:

1. `QuranJuz`
2. `QuranSurah`
3. `MushafPage`
4. `TahfizhTarget`
5. `HafalanRecord`

Seeder yang dibuat:

1. `QuranJuzSeeder`
2. `QuranSurahSeeder`
3. `MushafPageSeeder`

Service yang dibuat:

1. `App\Services\Tahfizh\LineRangeCalculator`

## Prinsip Mushaf

Sistem menggunakan prinsip mushaf pojok:

```text
1 halaman = 15 baris
Total halaman = 604 halaman
```

Pada Phase 3, mapping halaman ke surah/ayah masih minimal. Data yang wajib tersedia adalah 604 halaman dan 15 baris per halaman.

## Status Setoran

Status setoran awal:

1. `lunas`
2. `kurang`
3. `lebih`
4. `tidak_hadir`
5. `izin`
6. `sakit`

Status disimpan sebagai string agar fleksibel.

## Belum Dibuat

Phase 3 belum membuat:

1. UI input setoran.
2. Quick input guru.
3. Bulk input.
4. Sequential validation penuh.
5. Hitung hutang otomatis.
6. Report bulanan.
7. Report triwulan.
8. Dashboard statistik.
9. Parent progress detail.
10. Student progress detail.
11. Notifikasi real.
12. Export PDF/Excel.

## Validasi

Phase 3 selesai jika:

1. Migration berhasil.
2. Seeder berhasil.
3. `QuranJuz::count()` menghasilkan `30`.
4. `QuranSurah::count()` menghasilkan `114`.
5. `MushafPage::count()` menghasilkan `604`.
6. `LineRangeCalculator` bisa menghitung total baris.
7. Sample `TahfizhTarget` bisa dibuat.
8. Sample `HafalanRecord` bisa dibuat.
9. Tidak ada fitur UI input setoran yang dibuat pada Phase 3.

## Catatan Strategis

Phase 3 adalah fondasi domain tahfizh.

Jangan melompat ke dashboard atau report sebelum struktur target, setoran, dan kalkulasi baris benar.
