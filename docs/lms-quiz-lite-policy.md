# Kebijakan Kuis Ringan LMS (LMS Quiz Lite Policy)

## Keamanan Data Kunci Jawaban
- Kunci jawaban kuis (`correct_answer`) disimpan di database dan diproses **hanya di sisi server**.
- JSON payload pertanyaan yang dikirim ke peramban siswa saat pengerjaan kuis sama sekali tidak mengandung kunci jawaban.
- Evaluasi benar/salah jawaban dilakukan setelah siswa mengirimkan seluruh form kuis.

## Perhitungan Skor Kuis
Nilai kuis dihitung berdasarkan bobot skor dari setiap pertanyaan yang benar:

$$\text{Skor Akhir} = \left( \frac{\sum \text{Bobot Pertanyaan Benar}}{\sum \text{Total Bobot Pertanyaan}} \right) \times 100$$

## Pembatasan Percobaan
Siswa dibatasi oleh pengaturan jumlah maksimal pengerjaan kuis (`max_attempts`). Sistem akan menolak pembuatan percobaan baru jika batas ini telah terlampaui.
