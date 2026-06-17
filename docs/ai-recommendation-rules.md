# AI Recommendation Rules - HafizPlus

Aturan pemetaan sinyal belajar menjadi kartu rekomendasi terstruktur.

## 1. Aturan Pemetaan (Rule Mapping)

| Sinyal Terdeteksi | Rekomendasi yang Dihasilkan | Prioritas | Bukti Pendukung (Evidence) |
|---|---|---|---|
| `tahfizh_debt_increasing` | Program Stabilisasi Setoran Hafalan | High / Urgent | Baris akumulasi hutang setoran |
| `tahsin_*_weak` | Latihan Mandiri Aspek Makhraj/Tajwid | Normal | Skor penilaian item tahsin di bawah 70% |
| `mutabaah_low_consistency` | Pendampingan Konsistensi Ibadah di Rumah | Normal | Persentase checklist mingguan mutabaah < 50% |
| `attendance_absence_pattern` | Evaluasi Kehadiran Kelas | High | Jumlah ketidakhadiran dalam 14 hari > 2 kali |
| `lms_content_incomplete` | Aktivitas Belajar Mandiri LMS | Low | Progres materi LMS aktif < 100% |

## 2. Struktur Output Rekomendasi
Setiap kartu rekomendasi wajib menyediakan:
- Judul tindakan (Title)
- Deskripsi anjuran yang mendasar (Description)
- Checklist instruksi/aktivitas latihan (Recommended Actions)
- Sumber bukti data (Evidence)
