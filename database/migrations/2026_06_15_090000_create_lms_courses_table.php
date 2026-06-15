<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lms_courses', function (Blueprint $table): void {
            $table->id();
            
            $table->foreignId('school_id')
                ->nullable()
                ->constrained('schools')
                ->nullOnDelete();
                
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
                
            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('title');
            $table->string('slug');
            $table->string('course_code');
            $table->string('type')->default('general'); // e.g. general, quran, islamic_studies, Arabic, etc.
            $table->text('description')->nullable();
            $table->string('cover_image_path')->nullable();
            $table->string('level')->nullable();
            $table->enum('visibility', ['draft', 'published', 'archived'])->default('draft');
            $table->enum('enrollment_mode', ['manual', 'class_room', 'school_wide'])->default('manual');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('is_required')->default(false);
            $table->integer('sort_order')->default(0);

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['school_id', 'course_code'], 'lms_courses_code_unique');
            $table->unique(['school_id', 'slug'], 'lms_courses_slug_unique');
            
            $table->index('school_id');
            $table->index('type');
            $table->index('visibility');
            $table->index('start_date');
            $table->index('end_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lms_courses');
    }
};
