<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lms_course_modules', function (Blueprint $table): void {
            $table->id();
            
            $table->foreignId('school_id')
                ->nullable()
                ->constrained('schools')
                ->nullOnDelete();

            $table->foreignId('course_id')
                ->constrained('lms_courses')
                ->cascadeOnDelete();

            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_required')->default(true);

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['course_id', 'sort_order'], 'lms_modules_course_sort_unique');
            $table->index('school_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lms_course_modules');
    }
};
