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
        Schema::create('lms_assignments', function (Blueprint $table): void {
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
            $table->text('instructions')->nullable();
            $table->integer('max_score')->default(100);
            $table->integer('passing_score')->default(70);
            $table->dateTime('due_date')->nullable();
            $table->string('allowed_file_types')->nullable();
            $table->integer('max_file_size_kb')->default(10240); // default 10MB
            
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
        Schema::dropIfExists('lms_assignments');
    }
};
