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
        Schema::create('mutabaah_activities', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('school_id')
                ->nullable()
                ->constrained('schools')
                ->nullOnDelete();

            $table->foreignId('mutabaah_category_id')
                ->nullable()
                ->constrained('mutabaah_categories')
                ->nullOnDelete();

            $table->string('name');
            $table->string('slug')->nullable();
            $table->text('description')->nullable();

            $table->enum('input_type', [
                'checklist',
                'score',
                'count',
                'text',
            ])->default('checklist');

            $table->unsignedSmallInteger('target_score')->nullable();
            $table->unsignedSmallInteger('target_count')->nullable();
            $table->string('target_unit')->nullable();

            $table->boolean('is_required')->default(false);
            $table->boolean('is_active')->default(true);
            $table->boolean('allow_teacher_input')->default(true);
            $table->boolean('allow_parent_input')->default(false);
            $table->boolean('allow_student_input')->default(false);

            $table->unsignedSmallInteger('sort_order')->default(0);

            $table->timestamps();
            $table->softDeletes();

            $table->index(['school_id', 'is_active']);
            $table->index(['mutabaah_category_id', 'is_active']);
            $table->index('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mutabaah_activities');
    }
};
