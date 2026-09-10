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
        Schema::create('lms_course_enrollments', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('school_id')
                ->nullable()
                ->constrained('schools')
                ->nullOnDelete();

            $table->foreignId('course_id')
                ->constrained('lms_courses')
                ->cascadeOnDelete();

            $table->foreignId('student_id')
                ->constrained('students')
                ->cascadeOnDelete();

            $table->decimal('progress_percentage', 5, 2)->default(0.00);
            $table->string('status')->default('active'); // e.g. active, completed, dropped
            $table->dateTime('enrolled_at')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->timestamps();

            $table->unique(['course_id', 'student_id'], 'lms_enrollments_course_student_unique');
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
        Schema::dropIfExists('lms_course_enrollments');
    }
};
