# Data Protection Plan — HafizPlus School Platform

## 1. Alasan Dokumen

HafizPlus School Platform mengelola data siswa, guru, orang tua, dan progres hafalan. Karena itu perlindungan data harus disiapkan sejak awal.

Data pendidikan dan data anak tidak boleh diperlakukan sembarangan.

---

## 2. Data yang Dikelola

Data awal yang akan dikelola:

1. Nama santri.
2. Kelas.
3. Program / strata.
4. Data orang tua.
5. Relasi parent-anak.
6. Data guru.
7. Riwayat hafalan.
8. Catatan guru.
9. Target hafalan.
10. Hutang hafalan.
11. Notifikasi.
12. Audit log.
13. Login user.

---

## 3. Prinsip Perlindungan Data

1. Orang tua hanya boleh melihat data anak sendiri.
2. Santri hanya boleh melihat data dirinya sendiri.
3. Guru hanya boleh melihat santri yang relevan.
4. Kepala sekolah hanya boleh monitoring, bukan mengubah data teknis.
5. Admin boleh mengelola data sekolah.
6. Super admin boleh mengelola seluruh sistem.
7. Semua perubahan data penting harus masuk audit log.
8. Password harus di-hash.
9. API harus memakai token yang aman.
10. Export laporan harus dibatasi berdasarkan role.
11. Data tidak boleh bercampur antar sekolah saat multi-tenant dibuat.
12. Backup database harus diamankan.
13. File `.env` tidak boleh masuk Git.
14. Credential tidak boleh ditulis di dokumentasi publik.

---

## 4. Risiko Data

| Risiko | Dampak | Pencegahan |
|---|---|---|
| Orang tua melihat data anak lain | Fatal | Policy dan query scope |
| Santri melihat data santri lain | Tinggi | Student ownership check |
| Guru melihat semua kelas | Tinggi | Role permission dan assignment |
| Kepala sekolah bisa mengubah data teknis | Sedang-tinggi | Read-only dashboard |
| Data hafalan salah | Tinggi | Validasi sequential dan audit |
| Akun bocor | Tinggi | Password hash dan token expiry |
| Export bebas | Tinggi | Authorization export |
| Data sekolah bercampur | Fatal | Tenant isolation saat multi-tenant |
| Audit log dihapus | Tinggi | Batasi akses delete audit |
| Backup bocor | Tinggi | Enkripsi dan akses terbatas |
| API token bocor | Tinggi | Token expiry dan revoke mechanism |

---

## 5. Aturan Minimum Keamanan

### Auth

1. Password wajib di-hash.
2. Login harus memakai Laravel auth.
3. Role-based redirect wajib.
4. Akses route harus dibatasi middleware.
5. Jangan membuat route admin tanpa middleware.

### Authorization

1. Gunakan middleware untuk role umum.
2. Gunakan policy untuk resource ownership.
3. Parent ownership wajib dicek.
4. Student ownership wajib dicek.
5. Teacher assignment wajib dicek.

### Export

1. Export laporan hanya untuk role berwenang.
2. Export harus tercatat di audit log.
3. Parent tidak boleh export data anak lain.
4. Guru hanya boleh export data kelas atau santri yang diajar.

### Audit

Data penting yang harus masuk audit log:

1. User dibuat.
2. User diubah.
3. Role diubah.
4. Santri dibuat.
5. Santri diubah.
6. Setoran dibuat.
7. Setoran diubah.
8. Setoran dihapus.
9. Target hafalan diubah.
10. Export laporan dilakukan.

---

## 6. Data Protection untuk Multi-Tenant Nanti

Saat multi-tenant dibuat, semua data sekolah harus terisolasi berdasarkan tenant/school.

Prinsip:

1. Setiap sekolah punya `school_id`.
2. User hanya boleh mengakses sekolahnya.
3. Query harus selalu scoped berdasarkan school.
4. Super admin boleh lintas sekolah.
5. Admin sekolah tidak boleh lintas sekolah.
6. Parent tidak boleh lintas sekolah.
7. Guru tidak boleh lintas sekolah.
8. Export harus berdasarkan school context.

Multi-tenant tidak dibuat di Phase 0.
