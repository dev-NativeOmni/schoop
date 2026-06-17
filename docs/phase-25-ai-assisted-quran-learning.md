# Phase 25: AI-Assisted Qur'an Learning & Product Differentiation

Modul asisten belajar Qur'an berbasis kecerdasan buatan dirancang sebagai growth extension pada platform HafizPlus untuk memberikan diferensiasi produk yang kuat. Modul ini membantu guru, santri, dan orang tua dalam merancang langkah belajar Qur'an selanjutnya tanpa mengesampingkan peran guru sebagai penilai utama dan mutlak.

## Batasan Penggunaan AI (Guardrails)
1. **Human-in-the-Loop:** Semua rekomendasi, rencana latihan harian (practice plans), dan draf catatan guru berstatus `draft` secara default. Guru wajib melakukan review dan persetujuan sebelum output diterbitkan.
2. **Tidak Menggantikan Guru:** AI tidak melakukan auto-grading resmi, tidak meluluskan level tahsin secara otomatis, dan tidak bertindak sebagai fatwa agama.
3. **No Voice AI / Audio Tajwid:** Tidak mendukung speech recognition atau upload rekaman suara murid guna melindungi privasi anak dan menjaga akurasi penilaian tajwid resmi oleh guru.
4. **No External Data Transfer:** Memakai engine rule-based internal sekolah secara isolated (multi-tenant) tanpa mengirimkan data pribadi santri ke API eksternal pihak ketiga.

## Komponen Utama
1. **Learning Profiles:** Agregasi tren belajar Qur'an per santri.
2. **Learning Signals:** Sinyal penanda tren belajar (e.g. hutang hafalan menumpuk, skill makhraj tertentu lemah).
3. **Practice Plans:** Jadwal latihan mandiri harian santri (durasi 3-7 hari).
4. **Review Queue:** Antrean validasi keluaran AI untuk guru.
