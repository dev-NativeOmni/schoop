<?php

namespace Database\Seeders;

use App\Models\AiFeatureFlag;
use App\Models\School;
use Illuminate\Database\Seeder;

class AiFeatureFlagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $schools = School::all();

        $features = [
            [
                'feature_key' => 'learning_profile',
                'label' => 'AI-Assisted Learning Profile',
            ],
            [
                'feature_key' => 'practice_plan',
                'label' => 'AI-Assisted Practice Plan',
            ],
            [
                'feature_key' => 'teacher_feedback_draft',
                'label' => 'AI-Assisted Teacher Feedback Draft',
            ],
            [
                'feature_key' => 'parent_guidance_digest',
                'label' => 'AI-Assisted Parent Guidance Digest',
            ],
            [
                'feature_key' => 'student_learning_assistant',
                'label' => 'AI-Assisted Student Learning Assistant',
            ],
            [
                'feature_key' => 'lms_content_recommendation',
                'label' => 'AI-Assisted LMS Content Recommendation',
            ],
        ];

        foreach ($schools as $school) {
            foreach ($features as $feat) {
                AiFeatureFlag::query()
                    ->withoutGlobalScopes()
                    ->updateOrCreate(
                        [
                            'school_id' => $school->id,
                            'feature_key' => $feat['feature_key'],
                        ],
                        [
                            'label' => $feat['label'],
                            'is_enabled' => false, // disabled by default
                            'requires_teacher_review' => true,
                            'visible_to_parent' => false,
                            'visible_to_student' => false,
                            'settings' => [],
                        ]
                    );
            }
        }
    }
}
