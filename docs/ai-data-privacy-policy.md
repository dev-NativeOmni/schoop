# AI Data Privacy Policy - HafizPlus

Kebijakan privasi perlindungan data pribadi siswa dalam pemrosesan data asisten AI.

## 1. Isolasi Data Multi-Tenant
Seluruh data rekomendasi, practice plan harian, profile, dan audit log AI diisolasi ketat berdasarkan `school_id`. Data antar sekolah tidak akan pernah saling bercampur atau dapat diakses oleh sekolah lain.

## 2. Larangan Pengiriman Data Eksternal
Sistem memproses seluruh logika rekomendasi secara lokal (server-side monolith) menggunakan rule-based engine. Tidak ada data nama siswa, NIS, biodata orang tua, atau catatan akademik siswa yang dikirimkan ke model LLM eksternal (OpenAI, Google Gemini Cloud, dsb.) secara default.

## 3. Kebijakan Tanpa Media Audio/Video
Pada modul asisten belajar Qur'an Phase 25, tidak ada pengumpulan, perekaman, atau penyimpanan audio suara anak atau video rekaman mengaji untuk mencegah risiko kebocoran data biometrik anak di bawah umur.

## 4. Retensi dan Pembersihan Log
Audit log AI dibatasi masa simpannya untuk efisiensi ruang server. Log audit di atas 730 hari akan dipangkas secara otomatis melalui command scheduler `app:ai-prune-audit-logs` tanpa menghapus safety event yang belum diselesaikan admin.
