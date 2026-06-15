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
        Schema::create('lms_assignment_submissions', function (Blueprint $table): void {
            $table->id();
            
            $table->foreignId('school_id')
                ->nullable()
                ->constrained('schools')
                ->nullOnDelete();

            $table->foreignId('assignment_id')
                ->constrained('lms_assignments')
                ->cascadeOnDelete();

            $table->foreignId('student_id')
                ->constrained('students')
                ->cascadeOnDelete();

            $table->text('submitted_text')->nullable();
            $table->string('file_path')->nullable();
            $table->string('file_name')->nullable();
            $table->unsignedBigInteger('file_size')->nullable();
            
            $table->decimal('score', 5, 2)->nullable();
            $table->foreignId('graded_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->dateTime('graded_at')->nullable();
            $table->text('teacher_feedback')->nullable();
            
            $table->string('status')->default('submitted'); // e.g. submitted, graded, returned
            $table->timestamps();

            $table->unique(['assignment_id', 'student_id'], 'lms_sub_assignment_student_unique');
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
        Schema::dropIfExists('lms_assignment_submissions');
    }
};
