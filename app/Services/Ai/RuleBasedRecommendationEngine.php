<?php

namespace App\Services\Ai;

use App\Models\Student;
use App\Models\AiLearningProfile;
use App\Models\AiLearningRecommendation;
use Illuminate\Support\Collection;

class RuleBasedRecommendationEngine
{
    public function generate(Student $student, Collection $signals, ?AiLearningProfile $profile = null): Collection
    {
        $recommendations = collect();
        $schoolId = $student->school_id;

        foreach ($signals as $signal) {
            $key = $signal->signal_key;
            $evidence = $signal->evidence;

            if ($key === 'tahfizh_debt_increasing') {
                $recommendations->push([
                    'school_id' => $schoolId,
                    'student_id' => $student->id,
                    'ai_learning_profile_id' => $profile?->id,
                    'recommendation_type' => 'tahfizh_practice',
                    'priority' => $signal->severity === 'urgent' ? 'urgent' : 'high',
                    'status' => 'draft',
                    'title' => 'Program Stabilisasi Setoran Hafalan',
                    'description' => 'Mengingat terdapat penambahan hutang hafalan baru-baru ini, ananda disarankan fokus menstabilkan setoran harian.',
                    'recommended_actions' => [
                        'Lakukan murajaah intensif pada bagian halaman yang belum lancar.',
                        'Batasi penambahan hafalan baru hingga target mingguan tercapai.',
                        'Sediakan waktu khusus 15-20 menit per hari untuk melancarkan bacaan.',
                    ],
                    'evidence' => $evidence,
                    'related_content' => [
                        'module' => 'tahfizh',
                        'debt_lines' => $evidence['cumulative_debt_lines'] ?? 0,
                    ]
                ]);
            }

            if (str_starts_with($key, 'tahsin_') && str_ends_with($key, '_weak')) {
                $skillName = $evidence['skill_name'] ?? 'tajwid';
                $recommendations->push([
                    'school_id' => $schoolId,
                    'student_id' => $student->id,
                    'ai_learning_profile_id' => $profile?->id,
                    'recommendation_type' => 'tahsin_focus',
                    'priority' => 'normal',
                    'status' => 'draft',
                    'title' => "Latihan Mandiri Aspek {$skillName}",
                    'description' => "Penguatan teknik pelafalan dan hukum tajwid pada bagian {$skillName} diperlukan untuk menyempurnakan bacaan ananda.",
                    'recommended_actions' => [
                        "Dengarkan rekaman murattal syekh pilihan yang menekankan makhraj {$skillName}.",
                        "Lakukan setoran ujicoba khusus makhraj ini di depan pembimbing/guru.",
                        "Gunakan latihan interaktif LMS pendukung jika tersedia.",
                    ],
                    'evidence' => $evidence,
                    'related_content' => [
                        'module' => 'tahsin',
                        'skill_name' => $skillName,
                    ]
                ]);
            }

            if ($key === 'mutabaah_low_consistency') {
                $recommendations->push([
                    'school_id' => $schoolId,
                    'student_id' => $student->id,
                    'ai_learning_profile_id' => $profile?->id,
                    'recommendation_type' => 'parent_support',
                    'priority' => 'normal',
                    'status' => 'draft',
                    'title' => 'Pendampingan Konsistensi Ibadah di Rumah',
                    'description' => 'Mencatat mutabaah harian membantu memupuk kemandirian dan kedisiplinan beribadah ananda secara berkala.',
                    'recommended_actions' => [
                        'Luangkan waktu sejenak di malam hari untuk mengonfirmasi agenda mutabaah bersama.',
                        'Berikan dukungan verbal yang positif setiap ananda berinisiatif beribadah.',
                        'Hubungi musyrif/pembina asrama jika memerlukan kiat tambahan.',
                    ],
                    'evidence' => $evidence,
                    'related_content' => [
                        'module' => 'mutabaah',
                        'completion_rate' => $evidence['completion_rate'] ?? 0,
                    ]
                ]);
            }

            if ($key === 'attendance_absence_pattern') {
                $recommendations->push([
                    'school_id' => $schoolId,
                    'student_id' => $student->id,
                    'ai_learning_profile_id' => $profile?->id,
                    'recommendation_type' => 'teacher_follow_up',
                    'priority' => 'high',
                    'status' => 'draft',
                    'title' => 'Evaluasi Kehadiran Kelas',
                    'description' => 'Konsistensi kehadiran sangat krusial bagi ketuntasan target akademik dan hafalan ananda di sekolah.',
                    'recommended_actions' => [
                        'Jadwalkan diskusi ringan guru kelas dengan wali kelas/orang tua.',
                        'Identifikasi kendala eksternal (kesehatan/kelelahan) yang memicu absen.',
                        'Sediakan materi susulan LMS agar tidak tertinggal materi kelas.',
                    ],
                    'evidence' => $evidence,
                    'related_content' => [
                        'module' => 'attendance',
                        'absence_count' => $evidence['absence_count'] ?? 0,
                    ]
                ]);
            }

            if ($key === 'lms_content_incomplete') {
                $recommendations->push([
                    'school_id' => $schoolId,
                    'student_id' => $student->id,
                    'ai_learning_profile_id' => $profile?->id,
                    'recommendation_type' => 'lms_content',
                    'priority' => 'low',
                    'status' => 'draft',
                    'title' => 'Aktivitas Belajar Mandiri LMS',
                    'description' => "Modul LMS \"" . ($evidence['course_title'] ?? 'materi') . "\" masih menyisakan beberapa lesson yang belum dituntaskan.",
                    'recommended_actions' => [
                        'Selesaikan video penjelasan atau materi bacaan pendukung.',
                        'Kerjakan quiz pendukung yang tersedia pada course terkait.',
                    ],
                    'evidence' => $evidence,
                    'related_content' => [
                        'module' => 'lms',
                        'course_id' => $evidence['course_id'] ?? null,
                        'progress' => $evidence['progress_percentage'] ?? 0,
                    ]
                ]);
            }
        }

        // Persist
        $savedRecommendations = collect();
        foreach ($recommendations as $recData) {
            $rec = AiLearningRecommendation::query()
                ->withoutGlobalScopes()
                ->updateOrCreate(
                    [
                        'school_id' => $recData['school_id'],
                        'student_id' => $recData['student_id'],
                        'recommendation_type' => $recData['recommendation_type'],
                        'title' => $recData['title'],
                    ],
                    $recData
                );
            $savedRecommendations->push($rec);
        }

        return $savedRecommendations;
    }
}
