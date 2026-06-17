<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AiFeedbackTemplate;

class AiFeedbackTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            [
                'template_key' => 'tahfizh_progress_positive',
                'title' => 'Progres Tahfizh Sangat Baik',
                'body_template' => "Ananda {student_name} menunjukkan perkembangan yang sangat baik dalam setoran hafalan Qur'an. Hafalan baru dan murajaah dilakukan secara konsisten dengan kualitas yang baik. Teruskan semangatnya, Nak!",
                'tone' => 'supportive',
            ],
            [
                'template_key' => 'tahfizh_need_consistency',
                'title' => 'Memerlukan Konsistensi Murajaah',
                'body_template' => "Ananda {student_name} telah berusaha menyetorkan hafalannya. Namun, saat ini tren setoran {tahfizh_trend}. Mohon pendampingan orang tua di rumah agar ananda meluangkan waktu 10-15 menit untuk murajaah secara rutin.",
                'tone' => 'supportive',
            ],
            [
                'template_key' => 'tahsin_focus_area',
                'title' => 'Fokus Perbaikan Tajwid & Makhraj',
                'body_template' => "Bacaan Qur'an ananda {student_name} secara umum lancar, namun saat ini aspek {tahsin_trend}. Sangat disarankan untuk melatih kembali makhraj/hukum tajwid terkait fokus area ({focus_area}) agar kualitas bacaan semakin sempurna.",
                'tone' => 'supportive',
            ],
            [
                'template_key' => 'murajaah_reminder',
                'title' => 'Pengingat Rutinitas Murajaah',
                'body_template' => "Mari bantu ananda {student_name} menjaga kelancaran hafalannya dengan meluangkan waktu murajaah bersama di rumah secara terjadwal.",
                'tone' => 'supportive',
            ],
            [
                'template_key' => 'parent_home_support',
                'title' => 'Pendampingan Mutabaah Orang Tua',
                'body_template' => "Dukungan dan motivasi dari ayah dan bunda di rumah sangat berarti bagi perkembangan belajar ananda {student_name}. Mari dampingi ananda mengisi catatan mutabaah harian dengan penuh kasih sayang.",
                'tone' => 'supportive',
            ],
            [
                'template_key' => 'lms_content_suggestion',
                'title' => 'Rekomendasi Materi Belajar LMS',
                'body_template' => "Untuk memperkuat pemahaman ananda {student_name} pada materi tajwid, ananda disarankan menyelesaikan modul belajar {focus_area} di LMS HafizPlus.",
                'tone' => 'supportive',
            ],
        ];

        foreach ($templates as $tmpl) {
            AiFeedbackTemplate::query()
                ->withoutGlobalScopes()
                ->updateOrCreate(
                    [
                        'school_id' => null,
                        'template_key' => $tmpl['template_key'],
                    ],
                    [
                        'title' => $tmpl['title'],
                        'body_template' => $tmpl['body_template'],
                        'tone' => $tmpl['tone'],
                        'is_active' => true,
                    ]
                );
        }
    }
}
