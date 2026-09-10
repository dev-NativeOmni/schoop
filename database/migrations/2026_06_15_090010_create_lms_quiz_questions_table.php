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
        Schema::create('lms_quiz_questions', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('school_id')
                ->nullable()
                ->constrained('schools')
                ->nullOnDelete();

            $table->foreignId('quiz_id')
                ->constrained('lms_quizzes')
                ->cascadeOnDelete();

            $table->text('question_text');
            $table->string('question_type')->default('multiple_choice'); // e.g. multiple_choice, true_false, short_answer
            $table->json('options')->nullable(); // JSON structure of choice options e.g. {"A":"X","B":"Y"}
            $table->text('correct_answer'); // Store server-side only
            $table->text('explanation')->nullable();
            $table->integer('score_weight')->default(10);
            $table->integer('sort_order')->default(0);

            $table->timestamps();
            $table->softDeletes();

            $table->index('school_id');
            $table->index('quiz_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lms_quiz_questions');
    }
};
