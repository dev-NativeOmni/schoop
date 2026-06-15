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
        Schema::create('lms_quizzes', function (Blueprint $table): void {
            $table->id();
            
            $table->foreignId('school_id')
                ->nullable()
                ->constrained('schools')
                ->nullOnDelete();

            $table->foreignId('course_id')
                ->constrained('lms_courses')
                ->cascadeOnDelete();

            $table->foreignId('lesson_id')
                ->constrained('lms_lessons')
                ->cascadeOnDelete();

            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('time_limit_minutes')->default(0); // 0 or null = no limit
            $table->integer('max_attempts')->default(1);
            $table->decimal('passing_score', 5, 2)->default(70.00);
            $table->boolean('is_randomized')->default(false);
            
            $table->timestamps();
            $table->softDeletes();

            $table->index('school_id');
            $table->index('course_id');
            $table->index('lesson_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lms_quizzes');
    }
};
