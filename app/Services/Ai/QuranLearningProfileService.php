<?php

namespace App\Services\Ai;

use App\Models\Student;
use App\Models\AiLearningProfile;
use App\Models\AiLearningSignal;
use App\Services\Ai\LearningSignalAggregator;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class QuranLearningProfileService
{
    protected LearningSignalAggregator $aggregator;

    public function __construct(LearningSignalAggregator $aggregator)
    {
        $this->aggregator = $aggregator;
    }

    public function generateForStudent(Student $student, $date = null): AiLearningProfile
    {
        $date = $date ? Carbon::parse($date) : Carbon::today();
        $schoolId = $student->school_id;

        // Generate and fetch signals
        $signals = $this->aggregator->generateForStudent($student, $date);

        // Calculate confidence score
        $hasTahfizh = $signals->contains('source_module', 'tahfizh');
        $hasTahsin = $signals->contains('source_module', 'tahsin');
        $hasMutabaah = $signals->contains('source_module', 'mutabaah');
        $hasAttendance = $signals->contains('source_module', 'attendance');
        $hasLms = $signals->contains('source_module', 'lms');

        $confidence = 0;
        if ($hasTahfizh) $confidence += 30;
        if ($hasTahsin) $confidence += 20;
        if ($hasMutabaah) $confidence += 25;
        if ($hasAttendance) $confidence += 15;
        if ($hasLms) $confidence += 10;
        if ($confidence === 0) {
            $confidence = 30; // Min default
        }

        // Trends & Summary Determination
        $tahfizhTrend = $hasTahfizh ? ($signals->firstWhere('signal_key', 'tahfizh_debt_increasing') ? 'Perlu perhatian' : 'Stabil') : 'Tidak ada data';
        $tahsinTrend = $hasTahsin ? ($signals->firstWhere('source_module', 'tahsin') ? 'Perlu penguatan skill' : 'Baik') : 'Tidak ada data';
        $mutabaahTrend = $hasMutabaah ? ($signals->firstWhere('signal_key', 'mutabaah_low_consistency') ? 'Kurang konsisten' : 'Konsisten') : 'Tidak ada data';
        $attendanceTrend = $hasAttendance ? ($signals->firstWhere('signal_key', 'attendance_absence_pattern') ? 'Perlu perhatian' : 'Baik') : 'Tidak ada data';
        $lmsTrend = $hasLms ? 'Aktif' : 'Tidak ada data';

        // Strengths & Focus Areas
        $strengths = [];
        $focusAreas = [];

        if ($tahfizhTrend === 'Stabil') {
            $strengths[] = 'Progres setoran hafalan Qur’an berjalan konsisten.';
        } else if ($tahfizhTrend === 'Perlu perhatian') {
            $focusAreas[] = 'Menstabilkan kembali setoran harian untuk mengurangi hutang hafalan.';
        }

        if ($tahsinTrend === 'Baik') {
            $strengths[] = 'Menunjukkan pemahaman tajwid dan makhraj yang baik.';
        } else {
            $weakSkills = $signals->filter(fn($s) => $s['source_module'] === 'tahsin');
            foreach ($weakSkills as $ws) {
                $focusAreas[] = $ws['description'];
            }
        }

        if ($mutabaahTrend === 'Konsisten') {
            $strengths[] = 'Sangat rajin dan konsisten dalam mengamalkan ibadah harian.';
        } else if ($mutabaahTrend === 'Kurang konsisten') {
            $focusAreas[] = 'Meningkatkan konsistensi dalam mencatat dan menjalankan mutabaah harian.';
        }

        if ($attendanceTrend === 'Baik') {
            $strengths[] = 'Tingkat kehadiran di kelas sangat baik.';
        } else if ($attendanceTrend === 'Perlu perhatian') {
            $focusAreas[] = 'Memperbaiki absensi dan mengurangi pola absen tidak hadir.';
        }

        if (empty($strengths)) {
            $strengths[] = 'Menunjukkan kemauan belajar yang terus tumbuh.';
        }
        if (empty($focusAreas)) {
            $focusAreas[] = 'Mempertahankan konsistensi performa belajar saat ini.';
        }

        // Build summary safely without negative labeling
        $summaryParts = [];
        $summaryParts[] = "Ananda memiliki profil pembelajaran dengan tingkat keandalan data {$confidence}%.";
        if ($tahfizhTrend === 'Stabil' && $tahsinTrend === 'Baik') {
            $summaryParts[] = "Secara umum progres hafalan dan kualitas bacaan ananda berkembang dengan baik.";
        } else {
            $summaryParts[] = "Pekan ini ananda disarankan fokus pada latihan penguatan tajwid serta menjaga rutinitas murajaah harian.";
        }

        if ($mutabaahTrend === 'Kurang konsisten') {
            $summaryParts[] = "Dukungan orang tua di rumah sangat diperlukan untuk memotivasi ananda beribadah harian secara rutin.";
        }

        $summary = implode(' ', $summaryParts);

        // Create or update profile
        $profile = AiLearningProfile::query()
            ->withoutGlobalScopes()
            ->updateOrCreate(
                [
                    'school_id' => $schoolId,
                    'student_id' => $student->id,
                    'profile_date' => $date->toDateString(),
                ],
                [
                    'profile_status' => 'draft',
                    'confidence_score' => $confidence,
                    'tahfizh_trend' => $tahfizhTrend,
                    'tahsin_trend' => $tahsinTrend,
                    'mutabaah_trend' => $mutabaahTrend,
                    'attendance_trend' => $attendanceTrend,
                    'lms_engagement_trend' => $lmsTrend,
                    'summary' => $summary,
                    'strengths' => $strengths,
                    'focus_areas' => $focusAreas,
                    'evidence' => [
                        'signal_keys' => $signals->pluck('signal_key')->toArray(),
                    ],
                ]
            );

        // Update signals relation
        foreach ($signals as $sig) {
            $sig->ai_learning_profile_id = $profile->id;
            $sig->save();
        }

        return $profile;
    }
}
