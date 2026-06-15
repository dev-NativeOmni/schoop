# Kebijakan Perhitungan Progres LMS (LMS Progress Calculation Policy)

## Logika Perhitungan Progres
Progres belajar santri untuk kelas tertentu dihitung berdasarkan rasio penyelesaian materi wajib (required lessons):

$$\text{Persentase Progres} = \left( \frac{\text{Jumlah Materi Wajib Selesai}}{\text{Total Materi Wajib di Kelas}} \right) \times 100\%$$

## Ketentuan Khusus
1. **Tidak Ada Materi Wajib:** Jika kelas tidak memiliki materi yang secara eksplisit ditandai sebagai "wajib" (`is_required = true`), maka pembagi akan menggunakan seluruh materi yang tersedia di kelas tersebut.
2. **Kelas Kosong:** Jika kelas tidak memiliki materi sama sekali, progres otomatis diatur menjadi **0.00%**.
3. **Pembulatan:** Persentase dibulatkan hingga 2 angka di belakang koma (misal: 66.67%).
4. **Kelulusan Kelas:** Ketika progres mencapai **100.00%**, status pendaftaran kelas santri secara otomatis berubah menjadi `completed` dengan mencatat timestamp kelulusan `completed_at`.
