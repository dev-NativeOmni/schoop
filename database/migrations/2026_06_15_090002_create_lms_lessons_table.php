<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lms_lessons', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('school_id')
                ->nullable()
                ->constrained('schools')
                ->nullOnDelete();

            $table->foreignId('course_id')
                ->constrained('lms_courses')
                ->cascadeOnDelete();

            $table->foreignId('module_id')
                ->constrained('lms_course_modules')
                ->cascadeOnDelete();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('title');
            $table->string('slug');
            $table->enum('lesson_type', ['text', 'file', 'link', 'embed', 'assignment', 'quiz'])->default('text');
            $table->longText('content')->nullable();
            $table->string('external_url')->nullable();
            $table->text('embed_code')->nullable();
            $table->unsignedInteger('estimated_minutes')->default(0);
            $table->boolean('is_required')->default(true);
            $table->enum('visibility', ['draft', 'published', 'archived'])->default('draft');
            $table->dateTime('available_from')->nullable();
            $table->dateTime('available_until')->nullable();
            $table->integer('sort_order')->default(0);

            $table->timestamps();
            $table->softDeletes();

            $table->index('school_id');
            $table->index('course_id');
            $table->index('module_id');
            $table->index('lesson_type');
            $table->index('visibility');
            $table->unique(['module_id', 'sort_order'], 'lms_lessons_module_sort_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lms_lessons');
    }
};
