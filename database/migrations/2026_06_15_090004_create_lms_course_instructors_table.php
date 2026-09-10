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
        Schema::create('lms_course_instructors', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('school_id')
                ->nullable()
                ->constrained('schools')
                ->nullOnDelete();

            $table->foreignId('course_id')
                ->constrained('lms_courses')
                ->cascadeOnDelete();

            $table->foreignId('teacher_profile_id')
                ->constrained('teacher_profiles')
                ->cascadeOnDelete();

            $table->boolean('is_primary')->default(false);
            $table->timestamps();

            $table->unique(['course_id', 'teacher_profile_id'], 'lms_instructors_course_teacher_unique');
            $table->index('school_id');
            $table->index('teacher_profile_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lms_course_instructors');
    }
};
