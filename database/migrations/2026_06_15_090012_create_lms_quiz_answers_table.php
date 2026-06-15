<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lms_quiz_answers', function (Blueprint $table): void {
            $table->id();
            
            $table->foreignId('school_id')
                ->nullable()
                ->constrained('schools')
                ->nullOnDelete();

            $table->foreignId('quiz_attempt_id')
                ->constrained('lms_quiz_attempts')
                ->cascadeOnDelete();

            $table->foreignId('quiz_question_id')
                ->constrained('lms_quiz_questions')
                ->cascadeOnDelete();

            $table->text('student_answer')->nullable();
            $table->boolean('is_correct')->default(false);
            $table->decimal('score_obtained', 5, 2)->default(0.00);
            
            $table->timestamps();

            $table->unique(['quiz_attempt_id', 'quiz_question_id'], 'lms_answers_attempt_question_unique');
            $table->index('school_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lms_quiz_answers');
    }
};
