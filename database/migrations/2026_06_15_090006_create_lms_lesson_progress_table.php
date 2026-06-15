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
        Schema::create('lms_lesson_progress', function (Blueprint $table): void {
            $table->id();
            
            $table->foreignId('school_id')
                ->nullable()
                ->constrained('schools')
                ->nullOnDelete();

            $table->foreignId('lesson_id')
                ->constrained('lms_lessons')
                ->cascadeOnDelete();

            $table->foreignId('student_id')
                ->constrained('students')
                ->cascadeOnDelete();

            $table->string('status')->default('not_started'); // e.g. not_started, in_progress, completed
            $table->dateTime('completed_at')->nullable();
            $table->timestamps();

            $table->unique(['lesson_id', 'student_id'], 'lms_progress_lesson_student_unique');
            $table->index('school_id');
            $table->index('student_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lms_lesson_progress');
    }
};
