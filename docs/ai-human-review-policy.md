# AI Human Review Policy - HafizPlus

Kebijakan peninjauan draf keluaran AI oleh pembimbing sebelum dipublikasikan.

## 1. Alur Transisi Status Review
Setiap kali asisten menyusun profil belajar atau practice plan baru, statusnya adalah `draft`.
1. **Draft:** Rekomendasi tersimpan di database internal, hanya terlihat oleh guru/admin pembuat.
2. **Review Queue (Pending):** Masuk ke antrean verifikasi guru pembimbing.
3. **Approved / Edited:** Guru dapat menyetujui langsung atau menyunting konten (misalnya mengedit latihan hari tertentu) lalu menyetujuinya.
4. **Published:** Hasil yang disetujui diterbitkan sehingga dapat diakses oleh wali murid dan siswa di portal masing-masing.
5. **Rejected / Dismissed:** Jika draf dinilai tidak relevan, guru dapat menolaknya agar draf tersebut diarsipkan dan tidak tayang.

## 2. Batas Tanggung Jawab Akademik
Segala hasil yang dipublikasikan menjadi tanggung jawab akademik guru pembimbing yang memberikan persetujuan (approval).
Setiap aksi persetujuan wajib mencatat User ID guru dan waktu persetujuan di kolom database terkait serta log audit.
