# Kamus Metrik Analitik (Analytics Metric Dictionary)

Dokumen ini mendefinisikan rumus, unit satuan, tingkatan sensitivitas, dan kategori untuk metrik utama analitik HafizPlus.

## 1. Metrik Tenant & Penggunaan (Tenant & Usage)

### `tenant.active_count`
- **Kategori**: Tenant  
- **Unit**: Count  
- **Formula**: `count(schools.id) where is_active = true`  
- **Sensitif**: No (Agregat)  
- **Akses**: Internal staff saja (Super Admin, Operations Manager)

### `tenant.health_score`
- **Kategori**: Tenant  
- **Unit**: Score (0-100)  
- **Formula**: Rata-rata terbobot komponen kesehatan (usage, akademik, finance, support, langganan)  
- **Sensitif**: No  
- **Akses**: Internal staff dan Admin Sekolah (hanya tenant sendiri)

---

## 2. Metrik Akademik (Academic)

### `academic.tahfizh_achievement_rate`
- **Kategori**: Academic  
- **Unit**: Percent (%)  
- **Formula**: `(hafalan_total_lines / target_lines) * 100`  
- **Sensitif**: No (Agregat), Yes (Tingkatan Santri)  
- **Akses**: Admin Sekolah, Kepala Sekolah, Guru Tahfizh

### `academic.students_behind_target`
- **Kategori**: Academic  
- **Unit**: Count  
- **Formula**: `count(students) where actual_memorized < target_lines`  
- **Sensitif**: Yes (Hanya Admin Sekolah & CS)  
- **Akses**: Admin Sekolah, Kepala Sekolah

---

## 3. Metrik Finansial & POS (Finance & Cashless)

### `finance.outstanding_total`
- **Kategori**: Finance  
- **Unit**: Currency (IDR)  
- **Formula**: `sum(bills.balance_amount) where status != void`  
- **Sensitif**: Yes (Akses Terbatas)  
- **Akses**: Super Admin, Admin Sekolah, CS (summary)

### `cashless.purchase_total`
- **Kategori**: Cashless  
- **Unit**: Currency (IDR)  
- **Formula**: `sum(transactions.amount) where type = purchase`  
- **Sensitif**: Yes  
- **Akses**: Admin Sekolah, Merchant Owner (summary)
