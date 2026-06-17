<?php

namespace App\Services\Ai;

use App\Models\Student;
use App\Models\AiSafetyEvent;
use Illuminate\Support\Facades\Auth;

class AiSafetyGuardService
{
    protected array $prohibitedWords = [
        'malas' => 'perlu konsistensi',
        'gagal' => 'perlu pendampingan',
        'buruk' => 'perlu penguatan',
        'tidak mampu' => 'memerlukan dukungan',
        'tertinggal parah' => 'memerlukan konsistensi tambahan',
        'tidak punya harapan' => 'dapat dibantu dengan latihan ringan',
        'lemah secara permanen' => 'sudah mulai berkembang',
    ];

    public function validateOutput(string $text): array
    {
        $issues = [];
        $lowerText = strtolower($text);

        foreach (array_keys($this->prohibitedWords) as $word) {
            if (str_contains($lowerText, $word)) {
                $issues[] = "Prohibited word detected: '{$word}'";
            }
        }

        return [
            'safe' => empty($issues),
            'issues' => $issues,
        ];
    }

    public function redactNegativeWording(string $text, Student $student): string
    {
        $replacedText = $text;
        $detectedWords = [];

        foreach ($this->prohibitedWords as $bad => $good) {
            // Case-insensitive search and replace
            if (preg_match("/\b{$bad}\b/i", $replacedText)) {
                $detectedWords[] = $bad;
                $replacedText = preg_replace("/\b{$bad}\b/i", $good, $replacedText);
            }
        }

        if (!empty($detectedWords)) {
            // Log Safety Event
            AiSafetyEvent::query()
                ->withoutGlobalScopes()
                ->create([
                    'school_id' => $student->school_id,
                    'user_id' => Auth::id(),
                    'student_id' => $student->id,
                    'event_type' => 'negative_label_detected',
                    'severity' => 'attention',
                    'description' => "Terdeteksi pelabelan negatif (" . implode(', ', $detectedWords) . ") pada draf rekomendasi untuk santri ID {$student->id}.",
                    'metadata' => [
                        'detected_words' => $detectedWords,
                        'original_text' => $text,
                        'sanitized_text' => $replacedText,
                    ]
                ]);
        }

        return $replacedText;
    }

    public function sanitizeForParent(string $text): string
    {
        $disclaimer = "\n\n(Rekomendasi ini dibuat oleh sistem untuk membantu proses belajar dan telah/harus direview oleh guru. Keputusan pembelajaran tetap mengikuti arahan guru.)";
        if (!str_contains($text, "Rekomendasi ini dibuat oleh sistem")) {
            return $text . $disclaimer;
        }
        return $text;
    }

    public function sanitizeForStudent(string $text): string
    {
        return $this->sanitizeForParent($text);
    }
}
